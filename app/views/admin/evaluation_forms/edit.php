<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">
        Chỉnh sửa form đánh giá: <?= htmlspecialchars($form['name']) ?>
        <?php 
        $formType = '';
        if (isset($form['form_type'])) {
            if ($form['form_type'] == 'lanh_dao') {
                $formType = '<span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><i class="fas fa-user-tie mr-1"></i> Lãnh đạo</span>';
            } else if ($form['form_type'] == 'nhan_vien') {
                $formType = '<span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-user mr-1"></i> Chuyên viên</span>';
            }
        }
        echo $formType;
        ?>
    </h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p><?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    
    <form action="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms/update/<?= $form['id'] ?>" method="POST" class="space-y-6" id="evaluationForm">
        <div>
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                Tên form đánh giá <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($form['name']) ?>"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                required>
        </div>
        
        <div>
            <label for="department_id" class="block text-gray-700 text-sm font-bold mb-2">
                Phòng ban
            </label>
            <select id="department_id" name="department_id" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Form mặc định (áp dụng cho tất cả phòng ban chưa có form riêng) --</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= $department['id'] ?>" <?= $form['department_id'] == $department['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($department['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="mt-1 text-sm text-gray-500">
                <?php if (empty($form['department_id'])): ?>
                    Đây là form mặc định áp dụng cho tất cả phòng ban chưa có form riêng
                <?php else: ?>
                    Form này hiện đang áp dụng cho phòng ban <?= htmlspecialchars($form['department_name'] ?? 'đã chọn') ?>
                <?php endif; ?>
            </p>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Đối tượng áp dụng <span class="text-red-500">*</span>
            </label>
            <select id="form_type" name="form_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" disabled>
                <option value="nhan_vien" <?= isset($form['form_type']) && $form['form_type'] == 'nhan_vien' ? 'selected' : '' ?>>Form đánh giá cho Chuyên viên</option>
                <option value="lanh_dao" <?= isset($form['form_type']) && $form['form_type'] == 'lanh_dao' ? 'selected' : '' ?>>Form đánh giá cho Lãnh đạo</option>
            </select>
            <input type="hidden" name="form_type" value="<?= $form['form_type'] ?>">
            <p class="mt-1 text-sm text-gray-500">
                Loại form không thể thay đổi sau khi tạo
            </p>
        </div>
        
        <!-- Form builder interface -->
        <div id="formBuilder" class="border border-gray-300 rounded-lg p-4 mb-6">
            <!-- Controls -->
            <div class="flex justify-end mb-4">
                <button type="button" id="addSection" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2">
                    <i class="fas fa-plus-circle"></i> Thêm phần mới
                </button>
            </div>
            
            <!-- Sections container -->
            <div id="sections_container">
                <!-- Sections will be added here dynamically -->
            </div>
        </div>
        
        <!-- Hidden JSON input that will be populated from the form builder -->
        <input type="hidden" id="content" name="content" value='<?= htmlspecialchars($form['content']) ?>' required>
        
        <div class="flex items-center justify-end space-x-3">
            <a href="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms" 
                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                Hủy
            </a>
            <button type="submit" 
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <i class="fas fa-save mr-1"></i> Cập nhật form đánh giá
            </button>
        </div>
    </form>
</div>

<script>
// Thiết lập dữ liệu từ form hiện tại
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const formBuilder = document.getElementById('formBuilder');
    const sectionsContainer = document.getElementById('sections_container');
    const formTypeSelect = document.getElementById('form_type');
    const contentInput = document.getElementById('content');
    
    try {
        // Lấy dữ liệu form từ hidden input
        const formData = JSON.parse(contentInput.value);
        
        // Hiển thị form builder
        formBuilder.style.display = 'block';
        
        // Thiết lập loại form
        if (formData.form_type) {
            formTypeSelect.value = formData.form_type;
            formTypeSelect.disabled = true; // Disable để không thay đổi loại form
        }
        
        // Xóa các phần mặc định
        sectionsContainer.innerHTML = '';
        
        // Thêm từng phần từ dữ liệu
        if (formData.sections && Array.isArray(formData.sections)) {
            formData.sections.forEach(section => {
                addSection(section);
            });
            
            // Thêm các mức năng lực nếu có
            if (formData.competency_levels && Array.isArray(formData.competency_levels)) {
                const competencyLevelsContainer = document.createElement('div');
                competencyLevelsContainer.className = 'mt-6 p-4 bg-gray-50 rounded-lg';
                competencyLevelsContainer.innerHTML = `
                    <h3 class="text-lg font-semibold mb-3">Mức năng lực đánh giá</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        ${formData.competency_levels.map(level => `
                            <div class="bg-white p-3 rounded border">
                                <div class="font-medium">${level.name} (${level.range})</div>
                                <div class="text-sm text-gray-600">${level.description}</div>
                            </div>
                        `).join('')}
                    </div>
                `;
                sectionsContainer.appendChild(competencyLevelsContainer);
            }
        }
    } catch (e) {
        console.error('Error parsing form data:', e);
        // Hiển thị thông báo lỗi
        const errorMessage = document.createElement('div');
        errorMessage.className = 'bg-red-100 p-4 rounded-lg text-red-800 mb-4';
        errorMessage.innerHTML = `<p>Lỗi khi đọc dữ liệu form: ${e.message}</p>`;
        formBuilder.insertBefore(errorMessage, sectionsContainer);
    }
    
    // Sự kiện submit form
    document.getElementById('evaluationForm').addEventListener('submit', function(e) {
        // Kiểm tra xem đã chọn loại form hay chưa
        if (!formTypeSelect.value) {
            e.preventDefault();
            alert('Vui lòng chọn đối tượng áp dụng trước khi lưu');
            return;
        }
        
        // Lấy toàn bộ dữ liệu từ form builder
        const formData = collectFormData();
        
        // Kiểm tra tính hợp lệ của form
        if (!validateFormData(formData)) {
            e.preventDefault();
            return;
        }
        
        // Đặt giá trị vào trường hidden
        contentInput.value = JSON.stringify(formData);
    });
    
    // Bấm thêm phần mới
    document.getElementById('addSection').addEventListener('click', function() {
        addSection();
    });
    
    // Hàm thêm phần mới
    function addSection(sectionData = null) {
        const sectionCount = document.querySelectorAll('.section').length + 1;
        const sectionId = sectionData ? sectionData.id : `section_${sectionCount}`;
        
        // Tạo phần mới
        const section = document.createElement('div');
        section.className = 'section border border-gray-300 rounded-lg p-4 mb-6';
        section.id = sectionId;
        
        // Đặt tiêu đề và mô tả cho phần
        const sectionTitle = sectionData ? sectionData.title : `Phần ${sectionCount}`;
        const sectionDescription = sectionData ? sectionData.description : '';
        const sectionWeight = sectionData ? sectionData.weight : 0;
        
        // Tạo HTML cho phần
        section.innerHTML = `
            <div class="flex justify-between items-start mb-4">
                <div class="w-3/4">
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-bold mb-1">
                            Tiêu đề phần <span class="text-red-500">*</span>
                        </label>
                    <input type="text" class="section-title w-full px-3 py-2 border border-gray-300 rounded-md" 
                            value="${sectionTitle}" placeholder="Nhập tiêu đề phần" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-1">
                            Mô tả phần
                        </label>
                        <input type="text" class="section-description w-full px-3 py-2 border border-gray-300 rounded-md" 
                            value="${sectionDescription}" placeholder="Mô tả ngắn về phần này">
                </div>
                </div>
                <div class="w-1/4 pl-4">
                    <label class="block text-gray-700 text-sm font-bold mb-1">
                        Trọng số (%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" min="0" max="100" class="section-weight w-full px-3 py-2 border border-gray-300 rounded-md" 
                        value="${sectionWeight}" required>
                    <p class="mt-1 text-xs text-gray-500">Tổng trọng số các phần phải bằng 100%</p>
                </div>
            </div>
            
            <div class="criteria-container">
                <!-- Tiêu chí sẽ được thêm vào đây -->
            </div>
            
            <div class="flex justify-between mt-4">
                <button type="button" class="add-criterion px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                    <i class="fas fa-plus-circle"></i> Thêm tiêu chí
                </button>
                <button type="button" class="delete-section px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                    <i class="fas fa-trash-alt"></i> Xóa phần
                </button>
            </div>
        `;
        
        // Thêm sự kiện thêm tiêu chí
        section.querySelector('.add-criterion').addEventListener('click', function() {
            addCriterion(section);
        });
        
        // Thêm sự kiện xóa phần
        section.querySelector('.delete-section').addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa phần này?')) {
                section.remove();
            }
        });
        
        // Thêm phần vào container
        sectionsContainer.appendChild(section);
        
        // Nếu có dữ liệu sẵn, thêm các tiêu chí
        if (sectionData && sectionData.criteria) {
            sectionData.criteria.forEach(criterion => {
                addCriterion(section, criterion);
            });
        } else {
            // Thêm tiêu chí đầu tiên
            addCriterion(section);
        }
    }
    
    // Hàm thêm tiêu chí vào phần
    function addCriterion(section, criterionData = null) {
        const criteriaContainer = section.querySelector('.criteria-container');
        const criterionCount = criteriaContainer.children.length + 1;
        const criterionId = criterionData ? criterionData.id : `criterion_${section.id}_${criterionCount}`;
        
        // Tạo tiêu chí mới
        const criterion = document.createElement('div');
        criterion.className = 'criterion border border-gray-200 rounded p-3 mb-3 bg-gray-50';
        criterion.id = criterionId;
        
        // Đặt dữ liệu cho tiêu chí
        const criterionText = criterionData ? criterionData.text : '';
        const maxScore = criterionData ? criterionData.max_score : 0;
        const examples = criterionData ? criterionData.examples : [];
        
        // Tạo HTML cho tiêu chí
        criterion.innerHTML = `
            <div class="flex justify-between items-start mb-3">
                <div class="w-3/4">
                    <label class="block text-gray-700 text-sm font-bold mb-1">
                        Nội dung tiêu chí <span class="text-red-500">*</span>
                    </label>
                    <textarea class="criterion-text w-full px-3 py-2 border border-gray-300 rounded-md" 
                        placeholder="Mô tả tiêu chí đánh giá" required rows="2">${criterionText}</textarea>
                            </div>
                <div class="w-1/4 pl-4">
                    <label class="block text-gray-700 text-sm font-bold mb-1">
                        Điểm tối đa <span class="text-red-500">*</span>
                    </label>
                    <input type="number" min="0" class="max-score w-full px-3 py-2 border border-gray-300 rounded-md" 
                        value="${maxScore}" required>
                                </div>
                            </div>
            
            <div class="mt-2">
                <label class="block text-gray-700 text-sm font-bold mb-1">
                    Mô tả các mức đánh giá
                </label>
                <div class="examples-container">
                    <!-- Các mức đánh giá sẽ được thêm vào đây -->
                </div>
                <button type="button" class="add-example mt-2 px-2 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                    <i class="fas fa-plus"></i> Thêm mức đánh giá
                </button>
            </div>
            
            <button type="button" class="delete-criterion mt-3 px-2 py-1 bg-red-400 text-white text-sm rounded hover:bg-red-500">
                <i class="fas fa-times"></i> Xóa tiêu chí
                </button>
            `;
        
        // Thêm sự kiện xóa tiêu chí
        criterion.querySelector('.delete-criterion').addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa tiêu chí này?')) {
                criterion.remove();
            }
        });
        
        // Thêm sự kiện thêm mức đánh giá
        criterion.querySelector('.add-example').addEventListener('click', function() {
            addExample(criterion);
        });
        
        // Thêm tiêu chí vào container
        criteriaContainer.appendChild(criterion);
        
        // Nếu có dữ liệu sẵn, thêm các mức đánh giá
        if (examples && examples.length > 0) {
            examples.forEach(example => {
                addExample(criterion, example);
            });
        } else {
            // Thêm mức đánh giá đầu tiên
            addExample(criterion);
        }
    }
    
    // Hàm thêm mức đánh giá cho tiêu chí
    function addExample(criterion, exampleData = null) {
        const examplesContainer = criterion.querySelector('.examples-container');
        
        // Tạo mức đánh giá mới
        const example = document.createElement('div');
        example.className = 'example flex items-center mb-2';
        
        // Đặt dữ liệu cho mức đánh giá
        const level = exampleData ? exampleData.level : '';
        const description = exampleData ? exampleData.description : '';
        
        // Tạo HTML cho mức đánh giá
        example.innerHTML = `
            <div class="w-1/6 pr-2">
                <input type="number" min="0" class="example-level w-full px-2 py-1 border border-gray-300 rounded-md text-sm" 
                    placeholder="Điểm" value="${level}">
                    </div>
            <div class="w-4/5 pr-2">
                <input type="text" class="example-description w-full px-2 py-1 border border-gray-300 rounded-md text-sm" 
                    placeholder="Mô tả mức đánh giá" value="${description}">
                    </div>
            <div class="w-1/6 flex justify-end">
                <button type="button" class="delete-example px-2 py-1 text-red-500 hover:text-red-700">
                    <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                `;
                
        // Thêm sự kiện xóa mức đánh giá
        example.querySelector('.delete-example').addEventListener('click', function() {
            example.remove();
        });
        
        // Thêm mức đánh giá vào container
        examplesContainer.appendChild(example);
    }
    
    // Hàm thu thập dữ liệu từ form builder
    function collectFormData() {
        const formType = formTypeSelect.value;
        const sections = [];
        
        // Thu thập dữ liệu từ các phần
        document.querySelectorAll('.section').forEach(sectionEl => {
            if (!sectionEl.id) return; // Bỏ qua phần không có ID
            
            const sectionData = {
                id: sectionEl.id,
                title: sectionEl.querySelector('.section-title').value,
                description: sectionEl.querySelector('.section-description').value,
                weight: parseInt(sectionEl.querySelector('.section-weight').value) || 0,
                criteria: []
            };
            
            // Thu thập dữ liệu từ các tiêu chí
            sectionEl.querySelectorAll('.criterion').forEach(criterionEl => {
                if (!criterionEl.id) return; // Bỏ qua tiêu chí không có ID
                
                const criterionData = {
                    id: criterionEl.id,
                    text: criterionEl.querySelector('.criterion-text').value,
                    max_score: parseInt(criterionEl.querySelector('.max-score').value) || 0,
                    examples: []
                };
                
                // Thu thập dữ liệu từ các mức đánh giá
                criterionEl.querySelectorAll('.example').forEach(exampleEl => {
                    const level = parseInt(exampleEl.querySelector('.example-level').value) || 0;
                    const description = exampleEl.querySelector('.example-description').value;
                    
                    if (level > 0 && description.trim() !== '') {
                        criterionData.examples.push({
                            level: level,
                            description: description
                        });
                    }
                });
                
                // Sắp xếp các mức đánh giá theo điểm
                criterionData.examples.sort((a, b) => a.level - b.level);
                
                sectionData.criteria.push(criterionData);
            });
            
            sections.push(sectionData);
        });
        
        // Lấy mức năng lực từ template hoặc dữ liệu cũ
        let competencyLevels = [];
        try {
            const existingData = JSON.parse(contentInput.value);
            competencyLevels = existingData.competency_levels || [];
        } catch (e) {
            // Nếu không có dữ liệu cũ, lấy từ template
            const templates = formTemplates || {};
            competencyLevels = templates[formType]?.competency_levels || [];
        }
        
        return {
            form_type: formType,
            sections: sections,
            competency_levels: competencyLevels
        };
    }
    
    // Hàm kiểm tra tính hợp lệ của dữ liệu form
    function validateFormData(formData) {
        if (!formData.form_type) {
            alert('Vui lòng chọn đối tượng áp dụng cho form đánh giá');
            return false;
        }
        
        if (formData.sections.length === 0) {
            alert('Form đánh giá phải có ít nhất một phần');
            return false;
        }
        
        // Kiểm tra tổng trọng số các phần có bằng 100% không
        const totalWeight = formData.sections.reduce((sum, section) => sum + section.weight, 0);
        if (totalWeight !== 100) {
            alert(`Tổng trọng số của các phần phải bằng 100%. Hiện tại là ${totalWeight}%`);
            return false;
        }
        
        // Kiểm tra từng phần
        for (const section of formData.sections) {
            if (!section.title) {
                alert('Vui lòng nhập tiêu đề cho tất cả các phần');
                return false;
            }
            
            if (section.criteria.length === 0) {
                alert(`Phần "${section.title}" phải có ít nhất một tiêu chí`);
                return false;
            }
            
            // Kiểm tra tổng điểm tối đa của các tiêu chí có bằng trọng số của phần không
            const totalMaxScore = section.criteria.reduce((sum, criterion) => sum + criterion.max_score, 0);
            if (totalMaxScore !== section.weight) {
                alert(`Tổng điểm tối đa của các tiêu chí trong phần "${section.title}" phải bằng trọng số của phần (${section.weight}). Hiện tại là ${totalMaxScore}`);
                return false;
            }
            
            // Kiểm tra từng tiêu chí
            for (const criterion of section.criteria) {
                if (!criterion.text) {
                    alert(`Vui lòng nhập nội dung cho tất cả các tiêu chí trong phần "${section.title}"`);
                    return false;
                }
                
                if (criterion.max_score <= 0) {
                    alert(`Điểm tối đa của tiêu chí phải lớn hơn 0 trong phần "${section.title}"`);
                    return false;
                }
            }
        }
        
        return true;
    }
});
</script> 