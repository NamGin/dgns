<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="flex justify-between items-center p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Thông tin cá nhân</h2>
        <div>
            <a href="<?= $GLOBALS['config']['base_url'] ?>profile/edit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                <i class="fas fa-user-edit mr-1"></i> Sửa thông tin
            </a>
            <a href="<?= $GLOBALS['config']['base_url'] ?>profile/change-password" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-key mr-1"></i> Đổi mật khẩu
            </a>
        </div>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 m-4 rounded relative" role="alert">
            <span class="block sm:inline"><?= $_SESSION['success'] ?></span>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 m-4 rounded relative" role="alert">
            <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin cơ bản</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                    <p class="text-gray-900 py-2 px-3 bg-gray-100 rounded"><?= htmlspecialchars($user['name']) ?></p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <p class="text-gray-900 py-2 px-3 bg-gray-100 rounded"><?= htmlspecialchars($user['email']) ?></p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vai trò</label>
                    <p class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                        <?php 
                        switch($user['role']) {
                            case 'admin':
                                echo 'bg-purple-100 text-purple-800';
                                break;
                            case 'giam_doc':
                                echo 'bg-red-100 text-red-800';
                                break;
                            case 'lanh_dao':
                                echo 'bg-yellow-100 text-yellow-800';
                                break;
                            default:
                                echo 'bg-green-100 text-green-800';
                        }
                        ?>">
                        <?php 
                        switch($user['role']) {
                            case 'nhan_vien':
                                echo 'Chuyên viên';
                                break;
                            case 'lanh_dao':
                                echo 'Lãnh đạo';
                                break;
                            case 'giam_doc':
                                echo 'Giám đốc';
                                break;
                            case 'admin':
                                echo 'Quản trị viên';
                                break;
                        }
                        ?>
                    </p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin khác</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ngày tạo tài khoản</label>
                    <p class="text-gray-900 py-2 px-3 bg-gray-100 rounded"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID người dùng</label>
                    <p class="text-gray-900 py-2 px-3 bg-gray-100 rounded"><?= $user['id'] ?></p>
                </div>
            </div>
        </div>
    </div>
</div> 