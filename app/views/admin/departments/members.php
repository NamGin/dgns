<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="flex justify-between items-center p-6 border-b">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Quản lý thành viên: <?= $department['name'] ?></h2>
            <p class="text-sm text-gray-500 mt-1"><?= $department['description'] ?></p>
        </div>
        <a href="<?= $GLOBALS['config']['base_url'] ?>departments" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại
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
    
    <!-- Thêm người dùng vào phòng ban -->
    <div class="p-6 bg-gray-50 border-b">
        <div class="mb-2">
            <h3 class="text-lg font-medium text-gray-800">Thêm người dùng vào phòng ban</h3>
        </div>
        <?php if (empty($availableUsers)): ?>
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">Tất cả người dùng đã được thêm vào phòng ban này.</span>
            </div>
        <?php else: ?>
            <form action="<?= $GLOBALS['config']['base_url'] ?>departments/add-member/<?= $department['id'] ?>" method="POST" class="flex flex-wrap gap-4 items-end">
                <div class="w-1/2">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Chọn người dùng</label>
                    <select name="user_id" id="user_id" class="shadow border rounded py-2 px-3 text-gray-700 w-full" onchange="checkUserRole(this.value)">
                        <option value="">-- Chọn người dùng --</option>
                        <?php foreach ($availableUsers as $user): ?>
                            <?php if ($user['role'] != 'giam_doc'): // Loại bỏ giám đốc khỏi danh sách ?>
                                <?php if ($user['role'] != 'admin'): // Loại bỏ quản trị viên khỏi danh sách ?>
                                <option value="<?= $user['id'] ?>" data-role="<?= $user['role'] ?>"><?= $user['name'] ?> (<?= $user['email'] ?>)
                                    <?php 
                                    switch($user['role']) {
                                        case 'nhan_vien':
                                            echo ' - Chuyên viên';
                                            break;
                                        case 'lanh_dao':
                                            echo ' - Lãnh đạo';
                                            break;
                                    }
                                    ?>
                                    </option>
                                <?php endif; ?>
                            <?php endif; ?> 
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="leader_checkbox_container" style="display: none;">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_leader" id="is_leader" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="is_leader" class="ml-2 block text-sm text-gray-700">Đánh dấu là lãnh đạo phòng</label>
                    </div>
                </div>
                <!-- Hidden field to automatically set leader for lanh_dao role -->
                <input type="hidden" name="auto_leader" id="auto_leader" value="0">
                <div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        <i class="fas fa-plus mr-1"></i> Thêm vào phòng ban
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
    
    <!-- Danh sách thành viên -->
    <div class="p-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Danh sách thành viên (<?= count($members) ?>)</h3>
        
        <?php if (empty($members)): ?>
            <div class="bg-gray-100 border border-gray-400 text-gray-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">Chưa có thành viên nào trong phòng ban này.</span>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vai trò</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lãnh đạo phòng</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($members as $member): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $member['id'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= $member['name'] ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?= $member['email'] ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php 
                                        switch($member['role']) {
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
                                        switch($member['role']) {
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($member['role'] == 'lanh_dao'): ?>
                                        <span class="text-green-600"><i class="fas fa-check-circle"></i> Có</span>
                                        <input type="hidden" name="is_leader" value="1">
                                    <?php else: ?>
                                        <span class="text-gray-400">Không</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="<?= $GLOBALS['config']['base_url'] ?>departments/remove-member/<?= $department['id'] ?>/<?= $member['id'] ?>" 
                                       class="text-red-600 hover:text-red-900"
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này khỏi phòng ban?');">
                                        <i class="fas fa-trash"></i> Xóa khỏi phòng ban
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div> 

<script>
function checkUserRole(userId) {
    if (!userId) return;
    
    const selectedOption = document.querySelector(`#user_id option[value="${userId}"]`);
    const userRole = selectedOption ? selectedOption.getAttribute('data-role') : null;
    const leaderCheckboxContainer = document.getElementById('leader_checkbox_container');
    const autoLeaderField = document.getElementById('auto_leader');
    
    if (userRole === 'lanh_dao') {
        // Hide the leader checkbox for lãnh đạo role as they are automatically leaders
        leaderCheckboxContainer.style.display = 'none';
        autoLeaderField.value = '1'; // Set the hidden field to mark as leader
    } else {
        // For other roles (nhan_vien), leader checkbox is hidden and not set
        leaderCheckboxContainer.style.display = 'none';
        autoLeaderField.value = '0';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const userIdSelect = document.getElementById('user_id');
    if (userIdSelect) {
        checkUserRole(userIdSelect.value);
    }
});
</script> 