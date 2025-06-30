<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Hệ thống đánh giá nhân sự' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $config['base_url'] ?>public/style.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-gradient-to-r from-indigo-700 to-purple-700 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-chart-line text-3xl mr-3"></i>
                        <h1 class="text-2xl font-bold tracking-tight"><?= $config['site_name'] ?></h1>
                    </div>
                    <div class="md:hidden">
                        <button id="mobile-menu-button" class="text-white focus:outline-none">
                            <i class="fas fa-bars text-2xl"></i>
                        </button>
                    </div>
                </div>
                
                <nav class="hidden md:flex items-center">
                    <?php if (isset($_SESSION['user'])): ?>
                        <ul class="flex space-x-6 items-center">
                            <?php if ($_SESSION['user']['role'] === 'nhan_vien'): ?>
                                <li>
                                    <a href="<?= $config['base_url'] ?>form-danh-gia" class="flex items-center text-white hover:text-indigo-200 transition duration-150">
                                        <i class="fas fa-clipboard-list mr-2"></i>
                                        <span>Tự đánh giá</span>
                                    </a>
                                </li>
                            <?php elseif ($_SESSION['user']['role'] === 'lanh_dao'): ?>
                                <li>
                                    <a href="<?= $config['base_url'] ?>lanh-dao-review" class="flex items-center text-white hover:text-indigo-200 transition duration-150">
                                        <i class="fas fa-tasks mr-2"></i>
                                        <span>Duyệt đánh giá</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $config['base_url'] ?>lanh-dao-danh-gia" class="flex items-center text-white hover:text-indigo-200 transition duration-150">
                                        <i class="fas fa-clipboard-list mr-2"></i>
                                        <span>Tự đánh giá</span>
                                    </a>
                                </li>
                            <?php elseif ($_SESSION['user']['role'] === 'pho_giam_doc'): ?>
                                <li>
                                    <a href="<?= $config['base_url'] ?>pho-giam-doc-xem" class="flex items-center text-white hover:text-indigo-200 transition duration-150">
                                        <i class="fas fa-check-double mr-2"></i>
                                        <span>Phê duyệt đánh giá</span>
                                    </a>
                                </li>
                            <?php elseif ($_SESSION['user']['role'] === 'giam_doc'): ?>
                                <li>
                                    <a href="<?= $config['base_url'] ?>giam-doc-xem" class="flex items-center text-white hover:text-indigo-200 transition duration-150">
                                        <i class="fas fa-chart-bar mr-2"></i>
                                        <span>Xem đánh giá</span>
                                    </a>
                                </li>
                            <?php elseif ($_SESSION['user']['role'] === 'admin'): ?>
                                <li>
                                    <a href="<?= $config['base_url'] ?>admin/users" class="flex items-center text-white hover:text-indigo-200 transition duration-150">
                                        <i class="fas fa-users-cog mr-2"></i>
                                        <span>Quản lý người dùng</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $config['base_url'] ?>departments" class="flex items-center text-white hover:text-indigo-200 transition duration-150">
                                        <i class="fas fa-building mr-2"></i>
                                        <span>Quản lý phòng ban</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $config['base_url'] ?>admin/evaluation-forms" class="flex items-center text-white hover:text-indigo-200 transition duration-150">
                                        <i class="fas fa-file-alt mr-2"></i>
                                        <span>Quản lý form đánh giá</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <!-- User profile dropdown -->
                            <li class="dropdown">
                                <div class="flex items-center space-x-1 py-2 px-3 rounded-full bg-indigo-800 hover:bg-indigo-900 cursor-pointer transition duration-150">
                                    <i class="fas fa-user-circle text-lg"></i>
                                    <span class="ml-1"><?= $_SESSION['user']['name'] ?></span>
                                    <i class="fas fa-chevron-down text-xs ml-1"></i>
                                </div>
                                <div class="dropdown-content mt-1 bg-white rounded-md shadow-lg py-1">
                                    <a href="<?= $config['base_url'] ?>profile" class="block px-4 py-2 text-gray-800 hover:bg-indigo-100 transition duration-150">
                                        <i class="fas fa-user mr-2"></i>
                                        <span>Thông tin cá nhân</span>
                                    </a>
                                    <a href="<?= $config['base_url'] ?>profile/edit" class="block px-4 py-2 text-gray-800 hover:bg-indigo-100 transition duration-150">
                                        <i class="fas fa-user-edit mr-2"></i>
                                        <span>Sửa thông tin</span>
                                    </a>
                                    <a href="<?= $config['base_url'] ?>profile/change-password" class="block px-4 py-2 text-gray-800 hover:bg-indigo-100 transition duration-150">
                                        <i class="fas fa-key mr-2"></i>
                                        <span>Đổi mật khẩu</span>
                                    </a>
                                    <div class="border-t border-gray-200 my-1"></div>
                                    <a href="<?= $config['base_url'] ?>logout" class="block px-4 py-2 text-red-600 hover:bg-red-100 transition duration-150">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        <span>Đăng xuất</span>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    <?php else: ?>
                        <a href="<?= $config['base_url'] ?>login" class="flex items-center px-4 py-2 rounded-md bg-white text-indigo-600 hover:bg-indigo-100 transition duration-150">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <span>Đăng nhập</span>
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
            

            <div id="mobile-menu" class="mobile-menu md:hidden pb-4">
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="grid gap-2 pt-2 pb-3">
                        <?php if ($_SESSION['user']['role'] === 'nhan_vien'): ?>
                            <a href="<?= $config['base_url'] ?>form-danh-gia" class="block px-3 py-2 rounded-md bg-indigo-800 text-white font-medium">
                                <i class="fas fa-clipboard-list mr-2"></i>
                                <span>Tự đánh giá</span>
                            </a>
                        <?php elseif ($_SESSION['user']['role'] === 'lanh_dao'): ?>
                            <a href="<?= $config['base_url'] ?>lanh-dao-review" class="block px-3 py-2 rounded-md bg-indigo-800 text-white font-medium">
                                <i class="fas fa-tasks mr-2"></i>
                                <span>Duyệt đánh giá</span>
                            </a>
                            <a href="<?= $config['base_url'] ?>lanh-dao-danh-gia" class="block px-3 py-2 rounded-md bg-indigo-800 text-white font-medium">
                                <i class="fas fa-clipboard-list mr-2"></i>
                                <span>Tự đánh giá</span>
                            </a>
                        <?php elseif ($_SESSION['user']['role'] === 'pho_giam_doc'): ?>
                            <a href="<?= $config['base_url'] ?>pho-giam-doc-xem" class="block px-3 py-2 rounded-md bg-indigo-800 text-white font-medium">
                                <i class="fas fa-check-double mr-2"></i>
                                <span>Phê duyệt đánh giá</span>
                            </a>
                        <?php elseif ($_SESSION['user']['role'] === 'giam_doc'): ?>
                            <a href="<?= $config['base_url'] ?>giam-doc-xem" class="block px-3 py-2 rounded-md bg-indigo-800 text-white font-medium">
                                <i class="fas fa-chart-bar mr-2"></i>
                                <span>Xem đánh giá</span>
                            </a>
                        <?php elseif ($_SESSION['user']['role'] === 'admin'): ?>
                            <a href="<?= $config['base_url'] ?>admin/users" class="block px-3 py-2 rounded-md bg-indigo-800 text-white font-medium">
                                <i class="fas fa-users-cog mr-2"></i>
                                <span>Quản lý người dùng</span>
                            </a>
                            <a href="<?= $config['base_url'] ?>departments" class="block px-3 py-2 rounded-md bg-indigo-800 text-white font-medium">
                                <i class="fas fa-building mr-2"></i>
                                <span>Quản lý phòng ban</span>
                            </a>
                            <a href="<?= $config['base_url'] ?>admin/evaluation-forms" class="block px-3 py-2 rounded-md bg-indigo-800 text-white font-medium">
                                <i class="fas fa-file-alt mr-2"></i>
                                <span>Quản lý form đánh giá</span>
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?= $config['base_url'] ?>profile" class="block px-3 py-2 rounded-md hover:bg-indigo-800 hover:text-white transition duration-150">
                            <i class="fas fa-user mr-2"></i>
                            <span>Thông tin cá nhân</span>
                        </a>
                        <a href="<?= $config['base_url'] ?>profile/edit" class="block px-3 py-2 rounded-md hover:bg-indigo-800 hover:text-white transition duration-150">
                            <i class="fas fa-user-edit mr-2"></i>
                            <span>Sửa thông tin</span>
                        </a>
                        <a href="<?= $config['base_url'] ?>profile/change-password" class="block px-3 py-2 rounded-md hover:bg-indigo-800 hover:text-white transition duration-150">
                            <i class="fas fa-key mr-2"></i>
                            <span>Đổi mật khẩu</span>
                        </a>
                        <a href="<?= $config['base_url'] ?>logout" class="block px-3 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 transition duration-150">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            <span>Đăng xuất (<?= $_SESSION['user']['name'] ?>)</span>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="pt-2 pb-3">
                        <a href="<?= $config['base_url'] ?>login" class="block px-3 py-2 rounded-md bg-white text-indigo-700 font-medium hover:bg-indigo-100 transition duration-150">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <span>Đăng nhập</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <main class="container mx-auto px-4 py-8">
    
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('show');
        });
    </script> 