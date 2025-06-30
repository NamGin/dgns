<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Thêm người dùng mới</h2>
    </div>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 m-4 rounded relative" role="alert">
            <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="p-6">
        <form action="<?= $GLOBALS['config']['base_url'] ?>admin/users/create" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Họ và tên:</label>
                <input type="text" name="name" id="name" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" name="email" id="email" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mật khẩu:</label>
                <input type="password" name="password" id="password" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-6">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Vai trò:</label>
                <select name="role" id="role" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="nhan_vien">Chuyên viên</option>
                    <option value="lanh_dao">Lãnh đạo</option>
                    <option value="pho_giam_doc">Phó giám đốc</option>
                    <option value="giam_doc">Giám đốc</option>
                    <option value="admin">Quản trị viên</option>
                </select>
                <p class="mt-1 text-sm text-gray-500 italic">Lưu ý: Người dùng có vai trò "Lãnh đạo" sẽ tự động được đặt làm lãnh đạo phòng khi được thêm vào phòng ban.</p>
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <i class="fas fa-save mr-1"></i> Lưu
                </button>
                <a href="<?= $GLOBALS['config']['base_url'] ?>admin/users" class="text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div> 