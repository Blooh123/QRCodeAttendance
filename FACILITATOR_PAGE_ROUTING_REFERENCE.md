# Facilitator Home Page Routing Reference

## Overview
The `facilitatorHome.php` now implements a page routing system similar to `adminHome.php`, allowing facilitators to navigate between different sections without leaving the main interface.

## Page Routing Structure

### 1. Page Validation
```php
$page = $_GET['page'] ?? 'Dashboard';
$allowed_pages = ['Dashboard', 'Students', 'Attendance', 'Users', 'ProfileFacilitator', 'StudentApplication'];
if (!in_array($page, $allowed_pages)) {
    $page = 'Dashboard';
}
```

### 2. Available Pages
- **Dashboard** - Main dashboard with QR scanner and current event info
- **Students** - Student management (requires 'manage students' permission)
- **Attendance** - Attendance/event management (requires 'manage attendance' permission)
- **Users** - User management (requires 'manage users' permission)
- **ProfileFacilitator** - Facilitator profile page
- **StudentApplication** - Student excuse applications

### 3. Navigation Links
All navigation links in the sidebar use the format:
```php
<a href="?page=PageName" class="... <?php echo $page === 'PageName' ? 'active-class' : ''; ?>">
```

### 4. Content Loading
The main content area loads different content based on the current page:
```php
<?php if ($page === 'Dashboard'): ?>
    <!-- Dashboard specific content -->
<?php else: ?>
    <!-- Load other pages -->
    <div>
        <?php require "../app/Controller/{$page}.php"; ?>
    </div>
<?php endif; ?>
```

## Controller Files Required

### Existing Controllers (Reused from Admin)
- `Students.php` - Student management
- `Attendance.php` - Attendance management  
- `Users.php` - User management
- `StudentApplication.php` - Excuse applications

### New Controllers Created
- `ProfileFacilitator.php` - Facilitator profile page

## Permission-Based Navigation

The sidebar navigation is dynamically shown based on facilitator permissions:
- **Manage Students** section only shows if user has 'manage students' permission
- **Manage Attendance** section only shows if user has 'manage attendance' permission
- **Manage Users** section only shows if user has 'manage users' permission

## URL Structure
- `facilitator?page=Dashboard` - Main dashboard
- `facilitator?page=Students` - Student management
- `facilitator?page=Attendance` - Attendance management
- `facilitator?page=Users` - User management
- `facilitator?page=ProfileFacilitator` - Profile page
- `facilitator?page=StudentApplication` - Excuse applications

## Key Features

### 1. Active Page Highlighting
Navigation links automatically highlight the current active page using PHP conditionals.

### 2. Permission-Based Access
Navigation items are only shown if the facilitator has the required permissions.

### 3. Responsive Design
The navigation works on both desktop and mobile devices with a collapsible sidebar.

### 4. Consistent Styling
Uses the same design system as adminHome with the red color scheme (#a31d1d).

## Adding New Pages

To add a new page:

1. **Add to allowed pages array:**
```php
$allowed_pages = ['Dashboard', 'Students', 'Attendance', 'Users', 'ProfileFacilitator', 'StudentApplication', 'NewPage'];
```

2. **Create controller file:**
Create `app/Controller/NewPage.php` with the page content.

3. **Add navigation link:**
Add a link in the sidebar navigation section.

4. **Update permissions (if needed):**
Add permission checks for restricted pages.

## Example Usage

```php
// Navigate to Students page
<a href="?page=Students">View Students</a>

// Navigate to Profile page  
<a href="?page=ProfileFacilitator">Profile</a>

// Check if current page is active
<?php if ($page === 'Dashboard'): ?>
    <div class="active-indicator"></div>
<?php endif; ?>
```

## Security Considerations

- Page validation prevents access to unauthorized pages
- Permission checks ensure users only see relevant navigation items
- Session validation is handled in the main Facilitator controller
- All user input is properly escaped using `htmlspecialchars()`

## Maintenance

- Keep the `$allowed_pages` array synchronized with available controller files
- Ensure all controller files exist before adding them to navigation
- Test permission-based navigation thoroughly
- Update this reference when adding new pages or features
