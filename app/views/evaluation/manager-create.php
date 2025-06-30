<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-3xl font-bold mb-6 text-indigo-700">Tự đánh giá lãnh đạo</h2>
    
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
    
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Với tư cách là lãnh đạo, đánh giá của bạn sẽ được tự động phê duyệt sau khi gửi.
                </p>
            </div>
        </div>
    </div>
    
    <div class="mb-8">
        <form id="evaluationForm" action="<?= $config['base_url'] ?>lanh-dao-danh-gia" method="POST" class="space-y-6">
            <div class="bg-indigo-50 p-4 mb-6 rounded-lg">
                <p class="text-indigo-800 font-medium">Mẫu đánh giá dành cho lãnh đạo, quản lý</p>
                
                <?php
                // Tự động chọn phòng ban đầu tiên nếu có
                $selectedDepartmentId = !empty($userDepartments) ? $userDepartments[0]['id'] : null;
                $selectedDepartmentName = !empty($userDepartments) ? $userDepartments[0]['name'] : 'Không có phòng ban';
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
                                    <p class="font-medium"><?= $key ?>. <?= $criterion['text'] ?></p>
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
                    
                    <?php if ($partKey === 'part3' && isset($part['criteria']['level1']) && isset($part['criteria']['level2'])): ?>
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded">
                            <div class="font-medium mb-2">1. <?= $part['criteria']['level1']['text'] ?> (chọn 1 mức):</div>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="part3_level_1" value="30" class="mr-2" required checked>
                                    <span>Hoàn thành 100% nhiệm vụ (30 điểm)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="part3_level_1" value="20" class="mr-2">
                                    <span>Hoàn thành từ 80% đến dưới 100% nhiệm vụ (20 điểm)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="part3_level_1" value="10" class="mr-2">
                                    <span>Hoàn thành từ 50% đến dưới 80% nhiệm vụ (10 điểm)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="part3_level_1" value="0" class="mr-2">
                                    <span>Hoàn thành dưới 50% nhiệm vụ (0 điểm)</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded">
                            <div class="font-medium mb-2">2. <?= $part['criteria']['level2']['text'] ?> (chọn 1 mức):</div>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="part3_level_2" value="30" class="mr-2" required checked>
                                    <span>100% cơ quan, đơn vị thuộc thẩm quyền phụ trách, quản lý trực tiếp được đánh giá hoàn thành nhiệm vụ trở lên; trong đó ít nhất 70% hoàn thành tốt hoặc hoàn thành xuất sắc nhiệm vụ (30 điểm)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="part3_level_2" value="20" class="mr-2">
                                    <span>100% cơ quan, đơn vị thuộc thẩm quyền phụ trách, quản lý trực tiếp được đánh giá hoàn thành nhiệm vụ trở lên; trong đó ít nhất 50% hoàn thành tốt hoặc hoàn thành xuất sắc nhiệm vụ (20 điểm)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="part3_level_2" value="10" class="mr-2">
                                    <span>Có ít nhất 50% cơ quan, đơn vị thuộc thẩm quyền phụ trách, quản lý trực tiếp được đánh giá hoàn thành tốt nhiệm vụ trở lên (10 điểm)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="part3_level_2" value="0" class="mr-2">
                                    <span>Cơ quan, đơn vị hoặc lĩnh vực công tác được giao phụ trách hoàn thành dưới 50% các chỉ tiêu, nhiệm vụ (0 điểm)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($partKey === 'part3' && isset($part['completion_levels']) && is_array($part['completion_levels'])): ?>
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded">
                            <div class="font-medium mb-2">Mức độ hoàn thành nhiệm vụ (chọn 1 mức):</div>
                            <div class="space-y-2">
                                <?php foreach ($part['completion_levels'] as $score => $text): ?>
                                <label class="flex items-center">
                                    <input type="radio" name="completion_level" value="<?= $score ?>" class="mr-2" <?= $score == '60' ? 'required' : '' ?> <?= array_key_first($part['completion_levels']) == $score ? 'checked' : '' ?>>
                                    <span><?= $text ?> (<?= $score ?> điểm)</span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
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
            </div>
        </form>
    </div>
    
    <?php if (count($evaluations) > 0): ?>
        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Lịch sử đánh giá của bạn</h3>
            
            <table class="min-w-full bg-white border border-gray-300 shadow-sm rounded-md overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Điểm tự đánh giá</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chi tiết</th>
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
                                <a href="<?= $config['base_url'] ?>lanh-dao-view/<?= $evaluation['id'] ?>" 
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
        const radioInputs = form.querySelectorAll('input[type="radio"]');
        
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
        
        // Ánh xạ radio inputs vào các phần tương ứng
        const radioMapping = {
            'completion_level': 'part3',  // Radio mức độ hoàn thành cho nhân viên
            'part3_level_1': 'part3',     // Radio level 1 cho lãnh đạo
            'part3_level_2': 'part3'      // Radio level 2 cho lãnh đạo
        };
        
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
            
            // Tính điểm từ các radio button
            const radioGroups = {};
            
            // Nhóm các radio button theo name
            radioInputs.forEach(input => {
                if (!radioGroups[input.name]) {
                    radioGroups[input.name] = [];
                }
                radioGroups[input.name].push(input);
            });
            
            // Cộng điểm từ mỗi nhóm radio button được chọn
            Object.entries(radioGroups).forEach(([name, group]) => {
                const selectedInput = group.find(input => input.checked);
                if (selectedInput) {
                    const partKey = radioMapping[name];
                    if (partKey) {
                        const score = parseInt(selectedInput.value || 0);
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
        
        radioInputs.forEach(input => {
            input.addEventListener('change', calculateScores);
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
            
            // Kiểm tra các radio button cho phần 3 (lãnh đạo)
            let hasLevel1 = false;
            let hasLevel2 = false;
            
            const level1Radios = form.querySelectorAll('input[name="part3_level_1"]');
            if (level1Radios.length > 0) {
                level1Radios.forEach(radio => {
                    if (radio.checked) {
                        hasLevel1 = true;
                    }
                });
            }
            
            const level2Radios = form.querySelectorAll('input[name="part3_level_2"]');
            if (level2Radios.length > 0) {
                level2Radios.forEach(radio => {
                    if (radio.checked) {
                        hasLevel2 = true;
                    }
                });
            }
            
            // Kiểm tra tổng điểm
            const hasTotalScore = totalScoreInput.value !== '' && totalScoreInput.value !== null;
            
            // Kiểm tra nếu cần validate phần lãnh đạo hay nhân viên
            const isManagerForm = level1Radios.length > 0 && level2Radios.length > 0;
            
            // Nếu có lỗi, hiển thị thông báo và ngăn form submit
            if (hasEmptyCriteria || 
                (isManagerForm && (!hasLevel1 || !hasLevel2)) ||
                !hasTotalScore) {
                
                event.preventDefault();
                
                let errorMessage = 'Vui lòng kiểm tra lại:';
                if (hasEmptyCriteria) {
                    errorMessage += '\n- Điền đầy đủ điểm cho tất cả các tiêu chí';
                }
                
                if (isManagerForm) {
                    if (!hasLevel1) {
                        errorMessage += '\n- Chọn mức độ hoàn thành nhiệm vụ cá nhân';
                    }
                    if (!hasLevel2) {
                        errorMessage += '\n- Chọn mức độ hoàn thành nhiệm vụ của cơ quan, đơn vị';
                    }
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