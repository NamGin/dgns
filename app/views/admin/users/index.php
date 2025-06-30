<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="flex justify-between items-center p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Danh sách người dùng</h2>
        <a href="<?= $GLOBALS['config']['base_url'] ?>admin/users/create" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-1"></i> Thêm mới
        </a>
    </div>
    
    <!-- Filter Form -->
    <div class="p-4 bg-gray-50 border-b">
        <form action="<?= $GLOBALS['config']['base_url'] ?>admin/users" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="role_filter" class="block text-sm font-medium text-gray-700 mb-1">Vai trò</label>
                <select name="role" id="role_filter" class="shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Tất cả vai trò</option>
                    <option value="nhan_vien" <?= isset($_GET['role']) && $_GET['role'] === 'nhan_vien' ? 'selected' : '' ?>>Chuyên viên</option>
                    <option value="lanh_dao" <?= isset($_GET['role']) && $_GET['role'] === 'lanh_dao' ? 'selected' : '' ?>>Lãnh đạo</option>
                    <option value="pho_giam_doc" <?= isset($_GET['role']) && $_GET['role'] === 'pho_giam_doc' ? 'selected' : '' ?>>Phó giám đốc</option>
                    <option value="giam_doc" <?= isset($_GET['role']) && $_GET['role'] === 'giam_doc' ? 'selected' : '' ?>>Giám đốc</option>
                    <option value="admin" <?= isset($_GET['role']) && $_GET['role'] === 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                </select>
            </div>
            
            <?php if (isset($departments) && !empty($departments)): ?>
            <div>
                <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Phòng ban</label>
                <select name="department_id" id="department_id" class="shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Tất cả phòng ban</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept['id'] ?>" <?= isset($_GET['department_id']) && $_GET['department_id'] == $dept['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dept['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input type="text" name="search" id="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" 
                       placeholder="Tên hoặc email..." 
                       class="shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <i class="fas fa-filter mr-1"></i> Lọc
                </button>
                <?php if (isset($_GET['role']) || isset($_GET['search']) || isset($_GET['department_id'])): ?>
                    <a href="<?= $GLOBALS['config']['base_url'] ?>admin/users" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        <i class="fas fa-times mr-1"></i> Xóa bộ lọc
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <!-- Filter Status -->
    <?php if (isset($filter) && (!empty($filter['role']) || !empty($filter['search']) || !empty($filter['department_id']))): ?>
    <div class="px-6 py-3 bg-blue-50 text-sm text-blue-700 border-b">
        <div class="flex justify-between items-center">
            <div>
                <span class="font-medium">Đang lọc: </span>
                <?php if (!empty($filter['role'])): ?>
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded mr-2">
                        Vai trò: 
                        <?php 
                        switch($filter['role']) {
                            case 'nhan_vien':
                                echo 'Chuyên viên';
                                break;
                            case 'lanh_dao':
                                echo 'Lãnh đạo';
                                break;
                            case 'giam_doc':
                                echo 'Giám đốc';
                                break;
                            case 'pho_giam_doc':
                                echo 'Phó giám đốc';
                                break;
                            case 'admin':
                                echo 'Quản trị viên';
                                break;
                        }
                        ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($filter['department_id']) && isset($departments)): ?>
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded mr-2">
                        Phòng ban: 
                        <?php 
                        foreach ($departments as $dept) {
                            if ($dept['id'] == $filter['department_id']) {
                                echo htmlspecialchars($dept['name']);
                                break;
                            }
                        }
                        ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($filter['search'])): ?>
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">
                        Tìm kiếm: "<?= htmlspecialchars($filter['search']) ?>"
                    </span>
                <?php endif; ?>
            </div>
            <div>
                <span class="font-medium"><?= count($users) ?> kết quả</span>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
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
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vai trò</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $user['id'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= $user['name'] ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500"><?= $user['email'] ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                                        case 'pho_giam_doc':
                                            echo 'bg-orange-100 text-orange-800';
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
                                        case 'pho_giam_doc':
                                            echo 'Phó giám đốc';
                                            break;
                                        case 'admin':
                                            echo 'Quản trị viên';
                                            break;
                                    }
                                    ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('d/m/Y H:i', strtotime($user['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?= $GLOBALS['config']['base_url'] ?>admin/users/edit/<?= $user['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                                    <a href="<?= $GLOBALS['config']['base_url'] ?>admin/users/delete/<?= $user['id'] ?>" 
                                       class="text-red-600 hover:text-red-900 mr-3"
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                        <i class="fas fa-trash"></i> Xóa
                                    </a>
                                <?php endif; ?>
                                
                                <?php
                                // Lấy số lượng phòng ban của người dùng
                                $departmentModel = new \App\Models\Department();
                                $deptCount = $departmentModel->countDepartmentsForUser($user['id']);
                                if ($deptCount > 0):
                                ?>
                                <a href="<?= $GLOBALS['config']['base_url'] ?>admin/users/detail/<?= $user['id'] ?>" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i> Xem (<?= $deptCount ?> phòng ban)
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Không có dữ liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 