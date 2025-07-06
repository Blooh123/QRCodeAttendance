<?php

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
    <title>Users • USep Attendance System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-image:
                radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0),
                linear-gradient(to right, rgba(255,255,255,0.2), rgba(255,255,255,0.2));
            background-size: 24px 24px;
            background-color: #f8f9fa;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .hover-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .hover-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15);
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="p-4 md:p-6 bg-[#f8f9fa]">
<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-7xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-users-gear text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Users</h1>
    </div>
</header>

<div class="max-w-7xl mx-auto">
    <!-- Search and Add User -->
    <div class="glass-card rounded-2xl p-6 mb-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <form id="searchForm" onsubmit="return false;" class="flex items-center gap-2 w-full md:w-auto">
            <input type="text" id="search-input" name="search" placeholder="Search..."
                   class="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
            <button type="submit" id="search-btn" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-lg flex items-center gap-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <a href="<?php echo ROOT ?>add_user"
           class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-users-gear"></i> Add User
        </a>
    </div>

    <!-- Users Grid -->
    <div id="usersGrid" class="flex flex-col gap-6 mt-6"></div>
    <div id="pagination" class="flex justify-center mt-8 gap-2"></div>
</div>

<script>
    // User data from PHP
    const userList = <?php echo json_encode($userList ?? []); ?>;
    const ROOT = "<?php echo ROOT; ?>";
    const cardsPerPage = 5;
    let currentPage = 1;
    let filteredList = userList;

    function renderUsers(list, page) {
        const start = (page - 1) * cardsPerPage;
        const end = start + cardsPerPage;
        const paginated = list.slice(start, end);

        let html = '';
        if (paginated.length === 0) {
            html = `<p class="text-center text-gray-600 mt-6">Users Information will be displayed here.</p>`;
        } else {
            html = paginated.map(user => `
                <div class="glass-card w-full rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 flex flex-col space-y-3 hover-card">
                    <div class="flex items-center space-x-3 mb-2">
                        <i class="fas fa-user text-[#a31d1d] text-2xl"></i>
                        <h2 class="text-xl font-semibold text-[#a31d1d]">${escapeHtml(user.username)}</h2>
                    </div>
                    <p class="text-gray-700"><strong>Role:</strong> ${escapeHtml(user.roles)}</p>
                    <p class="text-gray-700">
                        <strong>Status:</strong>
                        ${user.state === 'login'
                            ? `<span class="inline-flex items-center px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                <i class="fas fa-check-circle mr-1"></i> Active
                               </span>`
                            : `<span class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-800 bg-red-100 rounded-full">
                                <i class="fas fa-times-circle mr-1"></i> Inactive
                               </span>`
                        }
                    </p>
                    <div class="flex justify-between mt-4 gap-2">
                        <a href="${ROOT}edit_user?id=${encodeURIComponent(user.id)}"
                           class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
            `).join('');
        }
        document.getElementById('usersGrid').innerHTML = html;
        renderPagination(list, page);
    }

    function renderPagination(list, page) {
        const totalPages = Math.ceil(list.length / cardsPerPage);
        let html = '';
        if (totalPages > 1) {
            html += `<button onclick="gotoPage(1)" class="px-3 py-1 rounded ${page === 1 ? 'bg-[#a31d1d] text-white' : 'bg-white text-[#a31d1d]'} font-bold border border-[#a31d1d]">First</button>`;
            for (let i = 1; i <= totalPages; i++) {
                html += `<button onclick="gotoPage(${i})" class="px-3 py-1 rounded ${page === i ? 'bg-[#a31d1d] text-white' : 'bg-white text-[#a31d1d]'} font-bold border border-[#a31d1d]">${i}</button>`;
            }
            html += `<button onclick="gotoPage(${totalPages})" class="px-3 py-1 rounded ${page === totalPages ? 'bg-[#a31d1d] text-white' : 'bg-white text-[#a31d1d]'} font-bold border border-[#a31d1d]">Last</button>`;
        }
        document.getElementById('pagination').innerHTML = html;
    }

    function gotoPage(page) {
        currentPage = page;
        renderUsers(filteredList, currentPage);
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text.replace(/[&<>"']/g, function (m) {
            return ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            })[m];
        });
    }

    // JS Search
    document.getElementById('searchForm').addEventListener('submit', function () {
        const query = document.getElementById('search-input').value.trim().toLowerCase();
        filteredList = userList.filter(user =>
            user.username.toLowerCase().includes(query) ||
            user.roles.toLowerCase().includes(query) ||
            (user.state && user.state.toLowerCase().includes(query))
        );
        currentPage = 1;
        renderUsers(filteredList, currentPage);
    });

    // Initial render
    renderUsers(filteredList, currentPage);

    // Confirm delete (still works for dynamic content)
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
        return false;
    }
</script>
</body>
</html>