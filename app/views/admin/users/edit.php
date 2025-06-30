<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="flex justify-between items-center p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Chỉnh sửa người dùng</h2>
        <a href="<?= $GLOBALS['config']['base_url'] ?>admin/users" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 m-4 rounded relative" role="alert">
            <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="p-6">
        <form action="<?= $GLOBALS['config']['base_url'] ?>admin/users/update/<?= $user['id'] ?>" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Tên người dùng</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            
            <div class="mb-4">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Vai trò</label>
                <select name="role" id="role" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="nhan_vien" <?= $user['role'] === 'nhan_vien' ? 'selected' : '' ?>>Chuyên viên</option>
                    <option value="lanh_dao" <?= $user['role'] === 'lanh_dao' ? 'selected' : '' ?>>Lãnh đạo</option>
                    <option value="pho_giam_doc" <?= $user['role'] === 'pho_giam_doc' ? 'selected' : '' ?>>Phó giám đốc</option>
                    <option value="giam_doc" <?= $user['role'] === 'giam_doc' ? 'selected' : '' ?>>Giám đốc</option>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                </select>
                <p class="mt-1 text-sm text-gray-500 italic">Lưu ý: Người dùng có vai trò "Lãnh đạo" sẽ tự động được đặt làm lãnh đạo phòng khi được thêm vào phòng ban.</p>
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mật khẩu mới (để trống nếu không thay đổi)</label>
                <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <?php 
            // Lấy danh sách phòng ban của người dùng
            $departmentModel = new \App\Models\Department();
            $userDepartments = $departmentModel->getUserDepartments($user['id']);
            ?>

            <?php if (!empty($userDepartments)): ?>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Phòng ban đã tham gia</label>
                <div class="bg-gray-100 p-4 rounded">
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($userDepartments as $dept): ?>
                            <li>
                                <?= htmlspecialchars($dept['name']) ?>
                                <?php if ($dept['is_leader']): ?>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full ml-2">Lãnh đạo phòng</span>
                                <?php endif; ?>
                                <a href="<?= $GLOBALS['config']['base_url'] ?>departments/members/<?= $dept['id'] ?>" class="text-blue-600 hover:text-blue-900 text-sm ml-2">
                                    (Xem chi tiết phòng ban)
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <i class="fas fa-save mr-1"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div> 