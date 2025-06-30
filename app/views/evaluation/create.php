<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-3xl font-bold mb-6 text-indigo-700">Tự đánh giá</h2>
    
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
    
    <div class="mb-8">
        <form id="evaluationForm" method="POST" action="<?= $GLOBALS['config']['base_url'] ?>form-danh-gia" class="space-y-6">
            <div class="bg-indigo-50 p-4 mb-6 rounded-lg">
                <p class="text-indigo-800 font-medium">Mẫu đánh giá dành cho Chuyên viên không giữ chức vụ lãnh đạo, quản lý</p>
                
                <?php
                // Lấy thông tin phòng ban từ userDepartments
                $selectedDepartmentId = !empty($userDepartments) ? $userDepartments[0]['id'] : null;
                $selectedDepartmentName = !empty($userDepartments) ? $userDepartments[0]['name'] : null;
                ?>
                
                <?php if ($selectedDepartmentId): ?>
                <div class="mt-2 bg-white p-2 rounded border border-indigo-200">
                    <p class="text-gray-700"><span class="font-medium">Phòng ban:</span> <?= htmlspecialchars($selectedDepartmentName) ?></p>
                    <input type="hidden" name="department_id" value="<?= $selectedDepartmentId ?>">
                </div>
                <?php else: ?>
                <div class="mt-2 bg-yellow-50 p-2 rounded border border-yellow-200">
                    <p class="text-yellow-700"><i class="fas fa-exclamation-triangle mr-1"></i> Bạn chưa được gán vào phòng ban nào.</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Container để load form đánh giá động -->
            <div id="evaluation-form-container">
                <?php 
                // Lấy các phần của form (bỏ qua các key có dạng không phải phần như "form_type")
                $formParts = array_filter(array_keys($formData), function($key) {
                    return strpos($key, 'part') === 0;
                });
                
                // Biến lưu trữ tổng điểm của form
                $totalFormPoints = 0;
                
                // Hiển thị từng phần của form
                foreach ($formParts as $partKey):
                    $part = $formData[$partKey];
                    $totalFormPoints += $part['total_max'] ?? 0;
                ?>
                
                <!-- <?= strtoupper($partKey) ?>: <?= $part['title'] ?? '' ?> -->
                <div class="border border-gray-300 rounded-lg p-4 mb-6">
                    <h3 class="text-xl font-bold mb-4 pb-2 border-b border-gray-300"><?= $part['title'] ?? "Phần không có tiêu đề" ?></h3>
                    
                    <?php if ($partKey === 'part1'): ?>
                    <p class="text-sm text-gray-600 mb-4">Trường hợp đã trừ hết số điểm của tiêu chí nhưng tiếp tục vi phạm thì trừ bổ sung vào tổng số điểm đạt được trước khi xếp loại.</p>
                    <?php endif; ?>
                    
                    <?php if (isset($part['criteria']) && is_array($part['criteria'])): ?>
                <div class="space-y-6">
                        <?php foreach ($part['criteria'] as $key => $criterion): ?>
                    <div class="bg-gray-50 p-4 rounded">
                        <div class="flex justify-between items-start mb-2">
                            <div class="w-4/5">
                                    <p class="font-medium"><?= is_numeric($key) && $partKey === 'part2' ? ($key - 5) : $key ?>. <?= $criterion['text'] ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">Điểm tối đa: <?= $criterion['max_score'] ?></p>
                            </div>
                        </div>
                        <div class="flex items-center mt-2">
                            <label class="w-40 text-sm">Điểm tự chấm:</label>
                            <input type="number" name="criteria[<?= $key ?>][score]" min="0" max="<?= $criterion['max_score'] ?>" class="w-20 px-2 py-1 border rounded" required>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                    <?php endif; ?>
                
                <div class="mt-4 p-3 bg-yellow-50 rounded">
                        <p class="font-medium">Tổng điểm: <span id="part_<?= $partKey ?>_total">0</span>/<span class="font-bold"><?= $part['total_max'] ?? 0 ?> điểm</span></p>
                    </div>
                </div>
                <?php endforeach; ?>
            
            <!-- TỔNG ĐIỂM -->
            <div class="bg-blue-100 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-bold mb-2">Tổng điểm đánh giá</h3>
                    <p class="mb-2">Tổng tất cả các phần = <span class="font-bold"><?= $totalFormPoints ?> điểm</span></p>
                <div class="flex items-center">
                    <label class="font-medium mr-2">Điểm tự đánh giá:</label>
                        <input type="number" name="total_score" min="0" max="<?= $totalFormPoints ?>" class="w-20 px-2 py-1 border rounded" required>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Ghi chú (nếu có)</label>
                <textarea name="notes" id="notes" rows="3" 
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Nhập ghi chú nếu cần..."></textarea>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                    Gửi đánh giá
                </button>
            </div>
        </form>
    </div>
    
    <?php if (isset($evaluations) && !empty($evaluations)): ?>
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Lịch sử đánh giá</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700 tracking-wider">Ngày tạo</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700 tracking-wider">Điểm tự đánh giá</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700 tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700 tracking-wider">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($evaluations as $evaluation): ?>
                        <?php 
                            $evaluationData = json_decode($evaluation['content'], true);
                            $totalScore = isset($evaluationData['total_score']) ? $evaluationData['total_score'] : 'N/A';
                        ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-300">
                                <?= date('d/m/Y H:i', strtotime($evaluation['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-300 font-bold">
                                <?= $totalScore ?> / 100
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-300">
                                <?php
                                switch ($evaluation['status']) {
                                    case 'sent':
                                        echo '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Đã gửi</span>';
                                        break;
                                    case 'reviewed':
                                        echo '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Đã duyệt</span>';
                                        break;
                                    case 'approved':
                                        echo '<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Đã phê duyệt</span>';
                                        break;
                                    default:
                                        echo $evaluation['status'];
                                }
                                ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-300">
                                <a href="<?= $config['base_url'] ?>nhan-vien-review/<?= $evaluation['id'] ?>" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs">
                                    Xem chi tiết
                                </a>
                            </td>
                            
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div> 

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tính toán tổng điểm tự động
    const form = document.getElementById('evaluationForm');
    if (form) {
        // Lấy tất cả các input điểm
        const scoreInputs = form.querySelectorAll('input[type="number"][name^="criteria"]');
        const totalScoreInput = form.querySelector('input[name="total_score"]');
        
        // Ánh xạ tiêu chí vào các phần tương ứng (part1, part2, part3, etc.)
        const criteriaMapping = {};
        
        // Xác định mỗi tiêu chí thuộc phần nào
        <?php foreach ($formParts as $partKey): ?>
        <?php if (isset($formData[$partKey]['criteria']) && is_array($formData[$partKey]['criteria'])): ?>
        <?php foreach (array_keys($formData[$partKey]['criteria']) as $criteriaKey): ?>
        criteriaMapping['<?= $criteriaKey ?>'] = '<?= $partKey ?>';
        <?php endforeach; ?>
        <?php endif; ?>
        <?php endforeach; ?>
        
        // Hàm tính toán tổng điểm cho từng phần và tổng toàn bộ
        const calculateScores = () => {
            // Đặt lại tổng điểm cho mỗi phần
            const partTotals = {};
            <?php foreach ($formParts as $partKey): ?>
            partTotals['<?= $partKey ?>'] = 0;
            <?php endforeach; ?>
            
            // Tính điểm từ các tiêu chí
            scoreInputs.forEach(input => {
                // Trích xuất criteriaKey từ name (criteria[key][score])
                const matches = input.name.match(/criteria\[([^\]]+)\]/);
                if (matches && matches[1]) {
                    const criteriaKey = matches[1];
                    const partKey = criteriaMapping[criteriaKey];
                    
                    if (partKey) {
                        const score = parseInt(input.value || 0);
                        partTotals[partKey] += score;
                    }
                }
            });
            
            // Cập nhật hiển thị tổng điểm cho từng phần
            let totalScore = 0;
            Object.entries(partTotals).forEach(([partKey, score]) => {
                const partTotalElement = document.getElementById(`part_${partKey}_total`);
                if (partTotalElement) {
                    partTotalElement.textContent = score;
                }
                totalScore += score;
            });
            
            // Cập nhật ô tổng điểm toàn bộ
            totalScoreInput.value = totalScore;
        };
        
        // Đăng ký sự kiện cho tất cả các input
        scoreInputs.forEach(input => {
            input.addEventListener('change', calculateScores);
            input.addEventListener('input', calculateScores);
        });

        // Thêm validation trước khi submit form
        form.addEventListener('submit', function(event) {
            // Kiểm tra các input số điểm
            let hasEmptyCriteria = false;
            scoreInputs.forEach(input => {
                if (input.value === '' || input.value === null) {
                    hasEmptyCriteria = true;
                }
            });
            
            // Kiểm tra tổng điểm
            const hasTotalScore = totalScoreInput.value !== '' && totalScoreInput.value !== null;
            
            // Nếu có lỗi, hiển thị thông báo và ngăn form submit
            if (hasEmptyCriteria || !hasTotalScore) {
                event.preventDefault();
                
                let errorMessage = 'Vui lòng kiểm tra lại:';
                if (hasEmptyCriteria) {
                    errorMessage += '\n- Điền đầy đủ điểm cho tất cả các tiêu chí';
                }
                if (!hasTotalScore) {
                    errorMessage += '\n- Điền tổng điểm đánh giá';
                }
                
                alert(errorMessage);
            }
        });
    }
});
</script> 