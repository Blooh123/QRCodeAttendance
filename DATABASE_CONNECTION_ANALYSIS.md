# Database Connection Issues Analysis
## QR Code Attendance System - Hostinger max_connections_per_hour Exceeded

**Generated:** May 21, 2026  
**Issue:** `SQLSTATE[HY000] [1226] User has exceeded max_connections_per_hour resource (500 limit)`

---

## 🔴 CRITICAL ISSUES IDENTIFIED

### 1. **Connection Anti-Pattern in Database.php (MOST CRITICAL)**

**Problem:** Every method creates a NEW PDO connection that is never reused or explicitly closed.

**File:** `app/core/Database.php`

```php
public function connect(): PDO
{
    $string = "mysql:host=".DBHOST.";port=".DBPORT.";dbname=".DBNAME;
    $con = new PDO($string, DBUSER,DBPASS);  // ❌ NEW CONNECTION EVERY TIME
    return $con;
}

public function query($query, $params = [])
{
    $con = $this->connect();  // ❌ Creates connection #1
    $stmt = $con->prepare($query);
    // ... execute and return
}

public function query2($query, $params = [])
{
    $con = $this->connect();  // ❌ Creates connection #2
    $stmt = $con->prepare($query);
    // ... execute and return
}
```

**Impact:**
- Every single database operation creates a new connection
- No connection pooling or reuse
- On Hostinger (500 connections/hour limit), this is reached in seconds with multiple users

---

### 2. **Excessive Connections in Loops (UpdateAttendance.php)**

**File:** `app/Controller/UpdateAttendance.php` - Lines 140-241

**Problem:** The "finished" case loops through ALL STUDENTS and performs multiple database operations per student.

```php
case 'finished':
    $stmt = $this->connect()->prepare(...);  // ❌ Connection #1
    $stmt->execute();
    
    // Instantiates that all use Database trait
    $sanction = new Sanction();              // Will use connect()
    $student = new Student();                // Will use connect()
    $attendances = new Attendances();        // Will use connect()
    $qrCode = new QRCode();                  // Will use connect()
    
    $attendanceDetails = $attendances->getAttendanceDetails($eventId, $eventName);
    // ❌ Calls connect() inside getAttendanceDetails()
    
    $requiredAttendeesData = $attendances->getRequiredAttendees($eventId);
    // ❌ Calls connect() inside getRequiredAttendees()
    
    $studentList = $student->getAllStudent();
    // ❌ Calls connect() inside getAllStudent() - FETCHES ALL STUDENTS
    
    // ❌ CRITICAL: Loop through EVERY student with DB calls inside
    foreach ($studentList as $student) {
        if ($this->excuseApp->hasApprovedExcuse($student_id, $eventId)) {
            // ❌ Creates new connection for EACH student
        }
        
        // More database calls...
        if(!$qrCode->checkAttendance2($eventId, $student_id)) {
            // ❌ Creates new connection for EACH student
            $sanction->insertSanction(...);
            // ❌ Another connection for EACH student
        }
    }
```

**Impact Calculation:**
- Single student list fetch: ~1-3 connections
- Per-student operations × number of students:
  - If 100 students: 100 excuse checks + 100 attendance checks + 100 sanction inserts
  - That's 300+ connections in ONE request for ONE event finish
  - If 5 facilitators finish events = 1500+ connections in minutes

---

### 3. **Direct `$this->connect()` Calls Bypassing Trait Methods**

**Problem:** Many controllers and models call `$this->connect()` directly instead of using `query()` method.

**Examples:**
- `app/Controller/UpdateAttendance.php`: Lines 81, 94, 111, 133, 141
- `app/Model/Student.php`: Lines 48, 53, 61, 71, 80, 90, 97
- `app/Model/Attendances.php`: Lines 129, 136, 145, 153, etc.

Each direct call creates a connection that's never reused:

```php
// ❌ Bad
$stmt = $this->connect()->prepare($query);  // New connection
$stmt->execute();
// Connection is created but reference is lost immediately

// ❌ Bad
public function getAllStudent() {
    $query = "CALL sp_get_all_students()";
    $stmt = $this->connect()->prepare($query);  // New connection
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Called 100+ times for one attendance finalization
```

---

### 4. **N+1 Query Problem in Loops**

**Problem:** Database queries inside loops instead of batch queries.

```php
// ❌ Bad: 1 + N queries (1 fetch all, then N checks)
$studentList = $student->getAllStudent();  // Query 1
foreach ($studentList as $student) {
    if ($this->excuseApp->hasApprovedExcuse($student_id, $eventId)) {
        // Queries 2 to N+1: One per student
    }
}

// ✅ Good: Single batch query
$excusedStudents = $this->excuseApp->getExcusedStudentsByEvent($eventId);
// Then check using array_key_exists() in PHP
```

---

### 5. **No Connection Cleanup/Timeout**

**Problem:** PDO connections rely on script termination for cleanup, but with 500+ connections/hour limit, they accumulate quickly.

**Missing:** No explicit `$con = null;` or connection pooling.

---

## 📊 IMPACT SUMMARY

| Issue | Severity | Connections/Hour |
|-------|----------|------------------|
| New connection per query | CRITICAL | 100-500+ |
| Loop with DB calls | CRITICAL | 100-1000+ |
| No connection reuse | HIGH | 50-200+ |
| Direct connect() calls | HIGH | 50-300+ |
| **TOTAL POTENTIAL** | **CRITICAL** | **500-2000+** |

**Hostinger Limit:** 500/hour → **Issue occurs within minutes**

---

## ✅ SOLUTIONS

### Solution 1: Implement Singleton Connection Pool (BEST)

Modify `Database.php` to reuse connections:

```php
Trait Database
{
    private static ?PDO $connection = null;

    public function connect(): PDO
    {
        if (self::$connection === null) {
            $string = "mysql:host=".DBHOST.";port=".DBPORT.";dbname=".DBNAME;
            self::$connection = new PDO($string, DBUSER, DBPASS);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$connection;
    }
}
```

**Result:** 
- Only 1 connection per script execution
- Reused across all classes
- Reduces ~500 connections/request to just 1

---

### Solution 2: Batch Database Operations

Replace loops with batch queries:

```php
// ❌ Before: 300+ connections
foreach ($studentList as $student) {
    $this->excuseApp->hasApprovedExcuse($student_id, $eventId);  // Per student
    $sanction->insertSanction(...);  // Per student
}

// ✅ After: 3 connections
$excusedStudents = $this->excuseApp->getExcusedStudentsByEvent($eventId);
// Get all student attendance in one query
$attendanceRecords = array_column($attendances->AttendanceRecord2($eventId), 'student_id');
// Build sanction array and bulk insert
$sanctions = [];
foreach ($studentList as $student) {
    if (in_array($student['student_id'], $excusedStudents)) continue;
    $sanctions[] = [...];
}
// Single bulk insert
$sanction->bulkInsertSanctions($sanctions);
```

---

### Solution 3: Use Prepared Statements Correctly

Pool connections in prepared statements:

```php
// ❌ Bad: New connection for each query
public function query($query, $params = []) {
    $con = $this->connect();  // Creates new each time
    $stmt = $con->prepare($query);
    return $stmt->execute($params);
}

// ✅ Good: Reuse connection
public function query($query, $params = []) {
    $con = $this->connect();  // Reused from pool
    $stmt = $con->prepare($query);
    // Statement is cached by PDO, no new connection needed
    return $stmt->execute($params);
}
```

---

### Solution 4: Add Connection Timeouts

Set PDO timeouts to release connections faster:

```php
public function connect(): PDO
{
    $string = "mysql:host=".DBHOST.";port=".DBPORT.";dbname=".DBNAME;
    $options = [
        PDO::ATTR_TIMEOUT => 5,                    // 5 second timeout
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
        PDO::ATTR_PERSISTENT => false              // Don't use persistent connections
    ];
    return new PDO($string, DBUSER, DBPASS, $options);
}
```

---

## 🔧 IMPLEMENTATION PRIORITY

1. **Immediate (TODAY):** Implement Singleton connection pool in `Database.php`
2. **High Priority (THIS WEEK):** Batch database operations in `UpdateAttendance.php`
3. **Medium Priority (NEXT WEEK):** Convert all `N+1` queries to batch queries
4. **Low Priority (OPTIMIZATION):** Add connection timeouts and monitoring

---

## 📋 FILES REQUIRING CHANGES

1. `app/core/Database.php` - ✅ Connection pooling
2. `app/Controller/UpdateAttendance.php` - ✅ Batch operations in "finished" case
3. `app/Model/Sanction.php` - ✅ Add bulk insert method
4. `app/Model/ExcuseApplication.php` - ✅ Add batch fetch method
5. `app/Model/QRCode.php` - ✅ Add batch check method
6. All Models - ✅ Standardize use of `query()` method

---
