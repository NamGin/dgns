<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="flex justify-between items-center p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Chi tiết người dùng: <?= $user['name'] ?></h2>
        <a href="<?= $GLOBALS['config']['base_url'] ?>admin/users" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>
    
    <div class="p-6">
        <!-- Thông tin cơ bản -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Thông tin cơ bản</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">ID:</p>
                    <p class="font-medium"><?= $user['id'] ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tên người dùng:</p>
                    <p class="font-medium"><?= htmlspecialchars($user['name']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email:</p>
                    <p class="font-medium"><?= htmlspecialchars($user['email']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Vai trò:</p>
                    <p class="font-medium">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Ngày tạo:</p>
                    <p class="font-medium"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tổng số phòng ban:</p>
                    <p class="font-medium"><?= count($user['departments']) ?> phòng ban</p>
                </div>
            </div>
        </div>
        
        <!-- Danh sách phòng ban -->
        <div>
            <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Danh sách phòng ban</h3>
            
            <?php if (empty($user['departments'])): ?>
                <div class="bg-gray-100 border border-gray-400 text-gray-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">Người dùng chưa tham gia phòng ban nào.</span>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên phòng ban</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vai trò</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($user['departments'] as $dept): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $dept['id'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($dept['name']) ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-500">
                                            <?= !empty($dept['description']) ? htmlspecialchars(substr($dept['description'], 0, 100)) . (strlen($dept['description']) > 100 ? '...' : '') : '-' ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($dept['is_leader']): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Lãnh đạo phòng
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Thành viên
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="<?= $GLOBALS['config']['base_url'] ?>departments/members/<?= $dept['id'] ?>" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-users"></i> Xem thành viên
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="mt-8 flex gap-4">
            <a href="<?= $GLOBALS['config']['base_url'] ?>admin/users/edit/<?= $user['id'] ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-edit mr-1"></i> Chỉnh sửa người dùng
            </a>
            <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                <a href="<?= $GLOBALS['config']['base_url'] ?>admin/users/delete/<?= $user['id'] ?>" 
                   class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                   onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                    <i class="fas fa-trash mr-1"></i> Xóa người dùng
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>