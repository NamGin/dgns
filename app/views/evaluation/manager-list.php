<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-3xl font-bold mb-6 text-indigo-700">Duyệt đánh giá Chuyên viên</h2>
    
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
    
    <!-- Pending Evaluations Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800">Đánh giá chờ duyệt</h3>
            
            <div class="inline-flex items-center rounded-full bg-yellow-100 text-yellow-800 text-sm px-3 py-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Chờ phê duyệt: <?= isset($pendingEvaluations) ? count($pendingEvaluations) : 0 ?></span>
            </div>
        </div>
        
        <?php if (isset($pendingEvaluations) && !empty($pendingEvaluations)): ?>
            <div class="overflow-x-auto bg-gray-50 rounded-lg border border-gray-200">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-indigo-50 text-indigo-800">
                            <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold uppercase tracking-wider">Chuyên viên</th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold uppercase tracking-wider">Phòng ban</th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold uppercase tracking-wider">Ngày tạo</th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($pendingEvaluations as $evaluation): ?>
                            <tr class="hover:bg-indigo-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium text-indigo-700"><?= $evaluation['id'] ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <span class="text-indigo-700 font-bold"><?= substr($evaluation['employee_name'], 0, 1) ?></span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= $evaluation['employee_name'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?= $evaluation['department_name'] ?? 'Chưa có phòng ban' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?= date('d/m/Y', strtotime($evaluation['created_at'])) ?></div>
                                    <div class="text-sm text-gray-500"><?= date('H:i', strtotime($evaluation['created_at'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="<?= $config['base_url'] ?>lanh-dao-review/<?= $evaluation['id'] ?>" 
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded transition duration-150">
                                        <i class="fas fa-check-circle mr-1"></i> Duyệt ngay
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-600">Không có đánh giá nào đang chờ duyệt.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Reviewed Evaluations Section -->
    <div>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800">Đánh giá đã duyệt</h3>
            
            <div class="inline-flex items-center rounded-full bg-green-100 text-green-800 text-sm px-3 py-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Đã duyệt: <?= isset($reviewedEvaluations) ? count($reviewedEvaluations) : 0 ?></span>
            </div>
        </div>
        
        <?php if (isset($reviewedEvaluations) && !empty($reviewedEvaluations)): ?>
            <!-- Status filter buttons -->
            <div class="mb-4 flex gap-2">
                <button class="status-filter px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-sm active" data-status="all">
                    Tất cả
                </button>
                <button class="status-filter px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-sm" data-status="reviewed">
                    Đã duyệt
                </button>
                <button class="status-filter px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-sm" data-status="approved">
                    Đã phê duyệt
                </button>
            </div>
        
            <div class="overflow-x-auto bg-gray-50 rounded-lg border border-gray-200">
                <table class="min-w-full bg-white" id="reviewedEvaluationsTable">
                    <thead>
                        <tr class="bg-green-50 text-green-800">
                            <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold uppercase tracking-wider">Chuyên viên</th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold uppercase tracking-wider">Ngày tạo</th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold uppercase tracking-wider" style="min-width: 250px">Nhận xét lãnh đạo</th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold uppercase tracking-wider" style="min-width: 250px">Nhận xét phó giám đốc</th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold uppercase tracking-wider" style="min-width: 250px">Nhận xét giám đốc</th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-sm font-semibold uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($reviewedEvaluations as $evaluation): ?>
                            <tr class="hover:bg-gray-50 transition duration-150" data-status="<?= $evaluation['status'] ?>">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium text-gray-900"><?= $evaluation['id'] ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?= $evaluation['employee_name'] ?>
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
                                            echo '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Đã duyệt</span>';
                                            break;
                                        case 'deputy_reviewed':
                                            echo '<span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">Phó giám đốc đã duyệt</span>';
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
                                    <div class="max-h-24 overflow-y-auto">
                                        <?= !empty($evaluation['manager_comment']) ? 
                                            nl2br(htmlspecialchars($evaluation['manager_comment'])) : 
                                            '<span class="text-gray-400 italic">Không có nhận xét</span>' ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-h-24 overflow-y-auto">
                                        <?= !empty($evaluation['deputy_director_comment']) ? 
                                            nl2br(htmlspecialchars($evaluation['deputy_director_comment'])) : 
                                            '<span class="text-gray-400 italic">Không có nhận xét</span>' ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-h-24 overflow-y-auto">
                                        <?= !empty($evaluation['director_comment']) ? 
                                            nl2br(htmlspecialchars($evaluation['director_comment'])) : 
                                            '<span class="text-gray-400 italic">Không có nhận xét</span>' ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="<?= $config['base_url'] ?>lanh-dao-review/<?= $evaluation['id'] ?>" 
                                        class="inline-flex items-center px-3 py-1 border border-indigo-300 text-indigo-700 bg-indigo-50 rounded hover:bg-indigo-100 transition duration-150">
                                        <i class="fas fa-eye mr-1"></i> Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-600">Không có đánh giá nào đã duyệt.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.status-filter');
        const tableRows = document.querySelectorAll('#reviewedEvaluationsTable tbody tr');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => btn.classList.remove('active', 'bg-indigo-600', 'text-white'));
                this.classList.add('active', 'bg-indigo-600', 'text-white');
                
                const status = this.getAttribute('data-status');
                tableRows.forEach(row => {
                    if (status === 'all' || row.getAttribute('data-status') === status) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            });
        });
    });
</script> 