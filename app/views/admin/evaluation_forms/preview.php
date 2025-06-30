<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($form['name']) ?></h2>
        
        <div>
            <a href="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms/edit/<?= $form['id'] ?>" 
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
        </div>
    </div>
    
    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Loại form:</p>
                <p class="font-medium">
                    <?= $form['form_type'] == 'nhan_vien' ? 'Form đánh giá cho Chuyên viên' : 'Form đánh giá cho Lãnh đạo' ?>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Phòng ban áp dụng:</p>
                <p class="font-medium">
                    <?= $form['department_id'] ? htmlspecialchars($form['department_name']) : 'Form mặc định (áp dụng cho tất cả phòng ban)' ?>
                </p>
            </div>
        </div>
    </div>
    
    <?php
    // Parse form content
    $formData = json_decode($form['content'], true);
    ?>
    
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-3">Bảng tiêu chí đánh giá</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-3 text-left border-b w-7/12">Tiêu chí đánh giá</th>
                        <th class="py-2 px-3 text-center border-b w-2/12">Thang điểm</th>
                        <th class="py-2 px-3 text-center border-b w-3/12">Trọng số</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($formData['sections']) && is_array($formData['sections'])): ?>
                        <?php foreach ($formData['sections'] as $section): ?>
                            <tr class="bg-gray-50">
                                <td class="py-2 px-3 border-b font-medium" colspan="3">
                                    <?= htmlspecialchars($section['title']) ?>
                                    <?php if (!empty($section['description'])): ?>
                                        <span class="font-normal text-sm text-gray-600 ml-2">
                                            (<?= htmlspecialchars($section['description']) ?>)
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            
                            <?php if (isset($section['criteria']) && is_array($section['criteria'])): ?>
                                <?php foreach ($section['criteria'] as $criterion): ?>
                                    <tr>
                                        <td class="py-2 px-3 border-b">
                                            <div><?= htmlspecialchars($criterion['text']) ?></div>
                                            
                                            <?php if (!empty($criterion['examples'])): ?>
                                                <div class="mt-1 text-sm text-gray-600">
                                                    <p class="font-medium">Mô tả các mức đánh giá:</p>
                                                    <ul class="list-disc list-inside ml-2">
                                                        <?php foreach ($criterion['examples'] as $example): ?>
                                                            <li>
                                                                <span class="font-medium"><?= $example['level'] ?> điểm</span>: 
                                                                <?= htmlspecialchars($example['description']) ?>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
        </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-2 px-3 border-b text-center">
                                            <?= $criterion['max_score'] ?> điểm
                                        </td>
                                        <td class="py-2 px-3 border-b text-center">
                                            <?php if ($section['weight'] > 0): ?>
                                                <?= round(($criterion['max_score'] / $section['weight']) * 100) ?>%
                                            <?php else: ?>
                                                0%
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                            <tr class="bg-gray-100">
                                <td class="py-2 px-3 border-b font-medium text-right">
                                    Tổng điểm phần <?= htmlspecialchars($section['title']) ?>
                                </td>
                                <td class="py-2 px-3 border-b text-center font-medium">
                                    <?= $section['weight'] ?> điểm
                                </td>
                                <td class="py-2 px-3 border-b text-center font-medium">
                                    <?= $section['weight'] ?>%
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <tr class="bg-indigo-100">
                            <td class="py-2 px-3 border-b font-bold text-right">
                                TỔNG ĐIỂM ĐÁNH GIÁ
                            </td>
                            <td class="py-2 px-3 border-b text-center font-bold">
                                100 điểm
                            </td>
                            <td class="py-2 px-3 border-b text-center font-bold">
                                100%
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
    </div>
</div>

    <?php if (isset($formData['competency_levels']) && is_array($formData['competency_levels'])): ?>
        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-3">Mức năng lực đánh giá</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($formData['competency_levels'] as $level): ?>
                    <div class="bg-white p-4 rounded-lg border">
                        <div class="font-medium text-lg"><?= htmlspecialchars($level['name']) ?></div>
                        <div class="text-sm text-gray-700 mb-1">Điểm số: <?= htmlspecialchars($level['range']) ?></div>
                        <div><?= htmlspecialchars($level['description']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="mt-8 flex justify-between">
        <a href="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms" 
            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
        
        <a href="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms/edit/<?= $form['id'] ?>" 
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
            <i class="fas fa-edit"></i> Chỉnh sửa
        </a>
    </div>
            </div>