<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="flex justify-between items-center p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Danh sách phòng ban</h2>
        <a href="<?= $GLOBALS['config']['base_url'] ?>departments/create" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-1"></i> Thêm mới
        </a>
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
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên phòng ban</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số thành viên</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($departments)): ?>
                    <?php foreach ($departments as $department): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $department['id'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= $department['name'] ?></div>
                                <?php if (!empty($department['description'])): ?>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars(substr($department['description'], 0, 50)) ?><?= strlen($department['description']) > 50 ? '...' : '' ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="<?= $GLOBALS['config']['base_url'] ?>departments/members/<?= $department['id'] ?>" class="text-blue-600 hover:text-blue-900">
                                    <?= $department['member_count'] ?> thành viên
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('d/m/Y H:i', strtotime($department['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?= $GLOBALS['config']['base_url'] ?>departments/members/<?= $department['id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-users"></i> Quản lý thành viên
                                </a>
                                <a href="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms?department_id=<?= $department['id'] ?>" class="text-green-600 hover:text-green-900 mr-3">
                                    <i class="fas fa-clipboard-list"></i> Form đánh giá
                                </a>
                                <a href="<?= $GLOBALS['config']['base_url'] ?>departments/edit/<?= $department['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a href="<?= $GLOBALS['config']['base_url'] ?>departments/delete/<?= $department['id'] ?>" 
                                   class="text-red-600 hover:text-red-900"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa phòng ban này? Tất cả mối quan hệ với nhân viên sẽ bị xóa.');">
                                    <i class="fas fa-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Không có dữ liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 