<div class="bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-3xl font-bold mb-6 text-indigo-700">Danh sách đánh giá - Giám đốc phê duyệt</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['success'] ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <!-- Dashboard summary cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-600 text-white p-3 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Tổng đánh giá</h3>
                    <p class="text-2xl font-bold text-blue-700"><?= count($evaluations) ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-green-50 border border-green-100 rounded-lg p-4 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-600 text-white p-3 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Đã phê duyệt</h3>
                    <p class="text-2xl font-bold text-green-700">
                        <?php 
                        $approved = 0;
                        foreach ($evaluations as $eval) {
                            if ($eval['status'] === 'approved') $approved++;
                        }
                        echo $approved;
                        ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-purple-50 border border-purple-100 rounded-lg p-4 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-600 text-white p-3 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Chờ phê duyệt</h3>
                    <p class="text-2xl font-bold text-purple-700">
                        <?php 
                        $pending = 0;
                        foreach ($evaluations as $eval) {
                            if ($eval['status'] === 'reviewed' || $eval['status'] === 'deputy_reviewed') $pending++;
                        }
                        echo $pending;
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter controls -->
    <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg mb-6">
        <div class="flex flex-wrap gap-3">
            <button id="filter-all" class="status-filter px-3 py-1 bg-blue-600 text-white rounded-full text-sm active">
                Tất cả <span class="ml-1 text-xs bg-white text-blue-600 px-1.5 py-0.5 rounded-full"><?= count($evaluations) ?></span>
            </button>
            <button id="filter-reviewed" class="status-filter px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm">
                Đã duyệt <span class="ml-1 text-xs bg-green-100 text-green-800 px-1.5 py-0.5 rounded-full">
                    <?php 
                    $reviewed = 0;
                    foreach ($evaluations as $eval) {
                        if ($eval['status'] === 'reviewed') $reviewed++;
                    }
                    echo $reviewed;
                    ?>
                </span>
            </button>
            <button id="filter-deputy" class="status-filter px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm">
                Chờ phê duyệt <span class="ml-1 text-xs bg-purple-100 text-purple-800 px-1.5 py-0.5 rounded-full">
                    <?php 
                    $deputyReviewed = 0;
                    foreach ($evaluations as $eval) {
                        if ($eval['status'] === 'deputy_reviewed') $deputyReviewed++;
                    }
                    echo $deputyReviewed;
                    ?>
                </span>
            </button>
            <button id="filter-approved" class="status-filter px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm">
                Đã phê duyệt <span class="ml-1 text-xs bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded-full">
                    <?= $approved ?>
                </span>
            </button>
            
            <!-- Search box -->
            <div class="ml-auto">
                <div class="relative">
                    <input type="text" id="search-input" placeholder="Tìm kiếm tên nhân viên..." 
                        class="w-64 pl-10 pr-4 py-1 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (isset($evaluations) && !empty($evaluations)): ?>
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table id="evaluations-table" class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-blue-50 text-blue-800">
                            <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium uppercase tracking-wider">Nhân viên</th>
                            <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium uppercase tracking-wider">Phòng ban</th>
                            <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium uppercase tracking-wider">Ngày tạo</th>
                            <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium uppercase tracking-wider">Nhận xét lãnh đạo</th>
                            <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($evaluations as $evaluation): ?>
                            <tr class="hover:bg-blue-50 transition-colors" 
                                data-status="<?= $evaluation['status'] ?>" 
                                data-employee="<?= strtolower($evaluation['employee_name']) ?>">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?= $evaluation['id'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-700 font-bold"><?= substr($evaluation['employee_name'], 0, 1) ?></span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= $evaluation['employee_name'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <?= $evaluation['department_name'] ?? 'Không có phòng ban' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?= date('d/m/Y', strtotime($evaluation['created_at'])) ?></div>
                                    <div class="text-sm text-gray-500"><?= date('H:i', strtotime($evaluation['created_at'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    switch ($evaluation['status']) {
                                        case 'sent':
                                            echo '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Đã gửi</span>';
                                            break;
                                        case 'reviewed':
                                            echo '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Đã duyệt bởi lãnh đạo</span>';
                                            break;
                                        case 'deputy_reviewed':
                                            echo '<span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">Chờ phê duyệt</span>';
                                            break;
                                        case 'approved':
                                            echo '<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Đã phê duyệt</span>';
                                            break;
                                        default:
                                            echo $evaluation['status'];
                                    }
                                    ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-h-24 overflow-y-auto max-w-xs">
                                        <?php if (!empty($evaluation['manager_comment'])): ?>
                                            <div class="text-sm text-gray-600 italic">
                                                "<?= nl2br(htmlspecialchars(substr($evaluation['manager_comment'], 0, 100))) ?><?= strlen($evaluation['manager_comment']) > 100 ? '...' : '' ?>"
                                            </div>
                                        <?php else: ?>
                                            <span class="text-gray-400 italic text-sm">Không có nhận xét</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="<?= $config['base_url'] ?>giam-doc-xem/<?= $evaluation['id'] ?>" 
                                      class="<?= $evaluation['status'] === 'deputy_reviewed' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-800' ?> 
                                      font-medium py-2 px-4 rounded shadow-sm transition-colors">
                                        <?php if ($evaluation['status'] === 'deputy_reviewed'): ?>
                                            <i class="fas fa-check-circle mr-1"></i> Phê duyệt
                                        <?php else: ?>
                                            <i class="fas fa-eye mr-1"></i> Xem chi tiết
                                        <?php endif; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-8 rounded-lg text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-yellow-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="text-lg font-medium mb-2">Không có đánh giá nào</h3>
            <p class="text-gray-600">Chưa có đánh giá nào đã được lãnh đạo duyệt.</p>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status filter functionality
    const filterButtons = document.querySelectorAll('.status-filter');
    const tableRows = document.querySelectorAll('#evaluations-table tbody tr');
    const searchInput = document.getElementById('search-input');
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const activeFilter = document.querySelector('.status-filter.active').id.replace('filter-', '');
        
        tableRows.forEach(row => {
            const status = row.getAttribute('data-status');
            const employeeName = row.getAttribute('data-employee');
            
            // Check if row matches status filter
            const matchesStatus = (activeFilter === 'all') || 
                                  (activeFilter === 'reviewed' && status === 'reviewed') || 
                                  (activeFilter === 'deputy' && status === 'deputy_reviewed') ||
                                  (activeFilter === 'approved' && status === 'approved');
            
            // Check if row matches search filter
            const matchesSearch = employeeName.includes(searchTerm);
            
            // Show/hide row based on both filters
            if (matchesStatus && matchesSearch) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }
    
    // Set up filter button click handlers
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-blue-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            
            // Add active class to clicked button
            this.classList.add('active', 'bg-blue-600', 'text-white');
            this.classList.remove('bg-gray-200', 'text-gray-700');
            
            // Apply filters
            applyFilters();
        });
    });
    
    // Set up search input handler
    searchInput.addEventListener('input', applyFilters);
});
</script> 