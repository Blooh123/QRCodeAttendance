<?php
global $allStudents, $programList, $yearList, $numOfStudent;
if (empty($_SESSION['csrf_token'])) {
    try {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } catch (\Random\RandomException $e) {
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students • USep Attendance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 1px, transparent 1px),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: -1;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .hover-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .hover-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }
        
        .hover-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .hover-card:hover::before {
            left: 100%;
        }
        
        .search-input {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .filter-select {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .filter-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }
        
        .student-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
            border: 2px solid rgba(255,255,255,0.3);
            position: relative;
            overflow: hidden;
        }
        
        .student-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.3) 50%, transparent 70%);
            transform: rotate(45deg) translateX(100px);
            transition: transform 0.6s;
        }
        
        .student-card:hover::after {
            transform: rotate(45deg) translateX(-100px);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .animate-slide-up {
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .no-results {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .stats-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
            border: 2px solid rgba(255,255,255,0.3);
        }
    </style>
</head>
<body class="p-4 md:p-6">

<header class="glass-card rounded-3xl p-8 mb-8 max-w-7xl mx-auto animate-fade-in">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-3 rounded-2xl">
                <i class="fas fa-user-graduate text-white text-3xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-black gradient-text tracking-tight">Students Management</h1>
                <p class="text-gray-600 font-medium">Search, filter, and manage student records</p>
            </div>
        </div>
        <div class="hidden md:flex items-center space-x-4">
            <div class="text-right">
                <p class="text-sm text-gray-500">Total Students</p>
                <p class="text-2xl font-bold text-gray-700" id="totalStudents"><?php echo $numOfStudent ?></p>
            </div>
        </div>
    </div>
</header>

<div class="max-w-7xl mx-auto">
    <!-- Search and Filter Section -->
    <div class="glass-card rounded-3xl p-8 mb-8 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Search Students</label>
                <div class="relative">
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Search by name, ID, email, program, or year..."
                           class="search-input w-full px-4 py-3 rounded-2xl text-lg focus:outline-none">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <!-- Add Student Button -->
            <div class="flex items-end">
                <a href="<?php echo ROOT ?>add_student" class="btn-modern w-full text-center">
                    <i class="fas fa-plus mr-2"></i>
                    Add Student
                </a>
            </div>
        </div>
        
        <!-- Filter Controls -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Program</label>
                <select id="programFilter" class="filter-select w-full px-4 py-3 rounded-2xl text-lg focus:outline-none">
                    <option value="">All Programs</option>
                    <?php foreach ($programList as $program): ?>
                        <option value="<?php echo htmlspecialchars($program['program']); ?>">
                            <?php echo htmlspecialchars($program['program']); ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Year</label>
                <select id="yearFilter" class="filter-select w-full px-4 py-3 rounded-2xl text-lg focus:outline-none">
                    <option value="">All Years</option>
                    <?php foreach ($yearList as $year): ?>
                        <option value="<?php echo htmlspecialchars($year['acad_year']); ?>">
                            <?php echo htmlspecialchars($year['acad_year']); ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            
            <div class="flex items-end">
                <button id="clearFilters" class="btn-modern w-full">
                    <i class="fas fa-times mr-2"></i>
                    Clear Filters
                </button>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="stats-card rounded-2xl p-4 text-center">
                <div class="text-2xl font-bold text-blue-600" id="totalCount"><?php echo $numOfStudent ?></div>
                <div class="text-sm text-gray-600">Total Students</div>
            </div>
            <div class="stats-card rounded-2xl p-4 text-center">
                <div class="text-2xl font-bold text-green-600" id="filteredCount"><?php echo $numOfStudent ?></div>
                <div class="text-sm text-gray-600">Filtered Results</div>
            </div>
            <div class="stats-card rounded-2xl p-4 text-center">
                <div class="text-2xl font-bold text-purple-600" id="programCount">-</div>
                <div class="text-sm text-gray-600">Programs</div>
            </div>
            <div class="stats-card rounded-2xl p-4 text-center">
                <div class="text-2xl font-bold text-orange-600" id="yearCount">-</div>
                <div class="text-sm text-gray-600">Years</div>
            </div>
        </div>
    </div>

    <!-- Students Grid -->
    <div id="studentsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-slide-up" style="animation-delay: 0.2s;">
        <?php foreach ($allStudents as $student): ?>
            <div class="student-card rounded-3xl p-6 hover-card" data-student='<?php echo json_encode($student); ?>'>
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($student['name']); ?></h3>
                        <p class="text-sm text-gray-500">Student ID: <?php echo htmlspecialchars($student['student_id']); ?></p>
                    </div>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-graduation-cap text-purple-500"></i>
                        <span class="text-gray-700"><?php echo htmlspecialchars($student['program']); ?></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar text-blue-500"></i>
                        <span class="text-gray-700"><?php echo htmlspecialchars($student['acad_year']); ?></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-envelope text-green-500"></i>
                        <span class="text-gray-700 text-sm"><?php echo htmlspecialchars($student['email']); ?></span>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <a href="<?php echo ROOT?>edit_student?id=<?php echo htmlspecialchars($student['student_id']); ?>"
                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>
                    <a href="<?php echo ROOT?>delete_student?id=<?php echo htmlspecialchars($student['student_id']); ?>"
                       onclick="return confirmDelete(event, this.href);"
                       class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-trash"></i>
                        Delete
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- No Results Message -->
    <div id="noResults" class="hidden no-results rounded-3xl p-12 text-center animate-slide-up">
        <div class="text-6xl text-gray-400 mb-4">
            <i class="fas fa-search"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-600 mb-2">No Students Found</h3>
        <p class="text-gray-500">Try adjusting your search terms or filters</p>
    </div>
</div>

<script>
// Student data from PHP
const allStudents = <?php echo json_encode($allStudents); ?>;
const programList = <?php echo json_encode($programList); ?>;
const yearList = <?php echo json_encode($yearList); ?>;

class StudentManager {
    constructor() {
        this.students = allStudents;
        this.filteredStudents = [...allStudents];
        this.searchTerm = '';
        this.programFilter = '';
        this.yearFilter = '';
        
        this.initializeElements();
        this.attachEventListeners();
        this.updateStats();
    }
    
    initializeElements() {
        this.searchInput = document.getElementById('searchInput');
        this.programFilter = document.getElementById('programFilter');
        this.yearFilter = document.getElementById('yearFilter');
        this.clearFiltersBtn = document.getElementById('clearFilters');
        this.studentsContainer = document.getElementById('studentsContainer');
        this.noResults = document.getElementById('noResults');
        
        // Stats elements
        this.totalCount = document.getElementById('totalCount');
        this.filteredCount = document.getElementById('filteredCount');
        this.programCount = document.getElementById('programCount');
        this.yearCount = document.getElementById('yearCount');
        this.totalStudentsHeader = document.getElementById('totalStudents');
    }
    
    attachEventListeners() {
        // Search input with debouncing
        let searchTimeout;
        this.searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.searchTerm = e.target.value.toLowerCase();
                this.filterStudents();
            }, 300);
        });
        
        // Filter selects
        this.programFilter.addEventListener('change', (e) => {
            this.programFilter = e.target.value;
            this.filterStudents();
        });
        
        this.yearFilter.addEventListener('change', (e) => {
            this.yearFilter = e.target.value;
            this.filterStudents();
        });
        
        // Clear filters
        this.clearFiltersBtn.addEventListener('click', () => {
            this.clearFilters();
        });
    }
    
    filterStudents() {
        this.filteredStudents = this.students.filter(student => {
            const matchesSearch = !this.searchTerm || 
                student.name.toLowerCase().includes(this.searchTerm) ||
                student.student_id.toLowerCase().includes(this.searchTerm) ||
                student.email.toLowerCase().includes(this.searchTerm) ||
                student.program.toLowerCase().includes(this.searchTerm) ||
                student.acad_year.toLowerCase().includes(this.searchTerm);
            
            const matchesProgram = !this.programFilter || student.program === this.programFilter;
            const matchesYear = !this.yearFilter || student.acad_year === this.yearFilter;
            
            return matchesSearch && matchesProgram && matchesYear;
        });
        
        this.renderStudents();
        this.updateStats();
    }
    
    renderStudents() {
        if (this.filteredStudents.length === 0) {
            this.studentsContainer.style.display = 'none';
            this.noResults.style.display = 'block';
        } else {
            this.studentsContainer.style.display = 'grid';
            this.noResults.style.display = 'none';
            
            // Clear existing content
            this.studentsContainer.innerHTML = '';
            
            // Add filtered students with animation
            this.filteredStudents.forEach((student, index) => {
                const studentCard = this.createStudentCard(student);
                studentCard.style.animationDelay = `${index * 0.1}s`;
                this.studentsContainer.appendChild(studentCard);
            });
        }
    }
    
    createStudentCard(student) {
        const card = document.createElement('div');
        card.className = 'student-card rounded-3xl p-6 hover-card animate-slide-up';
        card.innerHTML = `
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">${this.escapeHtml(student.name)}</h3>
                    <p class="text-sm text-gray-500">Student ID: ${this.escapeHtml(student.student_id)}</p>
                </div>
            </div>
            
            <div class="space-y-2 mb-4">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-graduation-cap text-purple-500"></i>
                    <span class="text-gray-700">${this.escapeHtml(student.program)}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-calendar text-blue-500"></i>
                    <span class="text-gray-700">${this.escapeHtml(student.acad_year)}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-envelope text-green-500"></i>
                    <span class="text-gray-700 text-sm">${this.escapeHtml(student.email)}</span>
                </div>
            </div>
            
            <div class="flex space-x-2">
                <a href="${ROOT}edit_student?id=${this.escapeHtml(student.student_id)}"
                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-edit"></i>
                    Edit
                </a>
                <a href="${ROOT}delete_student?id=${this.escapeHtml(student.student_id)}"
                   onclick="return confirmDelete(event, this.href);"
                   class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-trash"></i>
                    Delete
                </a>
            </div>
        `;
        return card;
    }
    
    clearFilters() {
        this.searchInput.value = '';
        this.programFilter.value = '';
        this.yearFilter.value = '';
        this.searchTerm = '';
        this.programFilter = '';
        this.yearFilter = '';
        this.filterStudents();
    }
    
    updateStats() {
        const uniquePrograms = new Set(this.filteredStudents.map(s => s.program)).size;
        const uniqueYears = new Set(this.filteredStudents.map(s => s.acad_year)).size;
        
        this.totalCount.textContent = this.students.length;
        this.filteredCount.textContent = this.filteredStudents.length;
        this.programCount.textContent = uniquePrograms;
        this.yearCount.textContent = uniqueYears;
        this.totalStudentsHeader.textContent = this.students.length;
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize the student manager
let studentManager;

document.addEventListener('DOMContentLoaded', function() {
    studentManager = new StudentManager();
});

function confirmDelete(event, url) {
    event.preventDefault();
    
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

// Add smooth scroll behavior
document.documentElement.style.scrollBehavior = 'smooth';
</script>
</body>
</html>