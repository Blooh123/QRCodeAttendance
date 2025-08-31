<?php
// ProfileFacilitator.php - Facilitator Profile Page

// Get current user data
$user = new User();
$userData = $user->getUserById($_SESSION['user_id']);

// Get facilitator permissions
$facilitatorPermissions = $user->getUserPermissions($_SESSION['user_id']);
?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-[#a31d1d] rounded-full flex items-center justify-center">
                <i class="fas fa-user-circle text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#a31d1d]">Facilitator Profile</h1>
                <p class="text-gray-600">Manage your account information and permissions</p>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gray-50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-[#a31d1d] mb-4 flex items-center gap-2">
                    <i class="fas fa-user"></i>
                    Personal Information
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Username</label>
                        <p class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($userData['username'] ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Role</label>
                        <p class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($userData['role'] ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Active
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-[#a31d1d] mb-4 flex items-center gap-2">
                    <i class="fas fa-shield-alt"></i>
                    Account Security
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Last Login</label>
                        <p class="text-sm text-gray-800"><?php echo date('F j, Y \a\t g:i A'); ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Session Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-clock mr-1"></i>
                            Active Session
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Section -->
        <div class="bg-gray-50 rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-[#a31d1d] mb-4 flex items-center gap-2">
                <i class="fas fa-key"></i>
                Your Permissions
            </h3>
            
            <?php if (empty($facilitatorPermissions)): ?>
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-3xl text-yellow-500 mb-3"></i>
                    <p class="text-gray-600">No specific permissions assigned yet.</p>
                    <p class="text-sm text-gray-500">Contact your administrator for permission assignments.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($facilitatorPermissions as $permission): ?>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-[#a31d1d] rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800"><?php echo htmlspecialchars($permission); ?></p>
                                    <p class="text-xs text-gray-500">Permission granted</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-[#a31d1d] mb-4 flex items-center gap-2">
                <i class="fas fa-cogs"></i>
                Quick Actions
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="<?php echo ROOT ?>scanner" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-6 py-3 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-qrcode"></i>
                    Scan QR Code
                </a>
                <a href="?page=Dashboard" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-tachometer-alt"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
