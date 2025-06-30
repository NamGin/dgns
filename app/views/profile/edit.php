<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Chỉnh sửa thông tin cá nhân</h2>
    </div>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 m-4 rounded relative" role="alert">
            <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="p-6">
        <form action="<?= $GLOBALS['config']['base_url'] ?>profile/update" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Họ và tên:</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-6">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-6">
                <p class="block text-gray-700 text-sm font-bold mb-2">Vai trò:</p>
                <div class="py-2 px-3 bg-gray-100 rounded">
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
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
                    </span>
                    <p class="text-gray-600 text-xs mt-2">* Vai trò không thể thay đổi. Liên hệ quản trị viên nếu cần thay đổi.</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <i class="fas fa-save mr-1"></i> Lưu thay đổi
                </button>
                <a href="<?= $GLOBALS['config']['base_url'] ?>profile" class="text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div> 