<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Danh sách form đánh giá</h2>
        <a href="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms/create" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>
            <span>Thêm form mới</span>
        </a>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p><?= $_SESSION['success'] ?></p>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p><?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <!-- Bộ lọc -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <h3 class="text-md font-semibold text-gray-700 mb-3">Bộ lọc</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="filter_department" class="block text-sm font-medium text-gray-700 mb-1">Phòng ban</label>
                <select id="filter_department" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Tất cả phòng ban</option>
                    <option value="null">Form mặc định</option>
                    <?php if (isset($departments) && !empty($departments)): ?>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?= $department['id'] ?>" <?= (isset($_GET['department_id']) && $_GET['department_id'] == $department['id']) ? 'selected' : '' ?>><?= htmlspecialchars($department['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div>
                <label for="filter_type" class="block text-sm font-medium text-gray-700 mb-1">Đối tượng</label>
                <select id="filter_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Tất cả đối tượng</option>
                    <option value="lanh_dao">Lãnh đạo</option>
                    <option value="nhan_vien">Chuyên viên</option>
                </select>
            </div>
            <div class="flex items-end">
                <button id="apply_filter" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md">
                    <i class="fas fa-filter mr-1"></i> Lọc
                </button>
                <button id="reset_filter" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md">
                    <i class="fas fa-undo mr-1"></i> Đặt lại
                </button>
            </div>
        </div>
    </div>
                            
    <?php if (isset($_GET['department_id']) && !empty($_GET['department_id'])): ?>
        <?php 
            // Tìm tên phòng ban
            $departmentName = '';
            $departmentId = $_GET['department_id'];
            foreach ($departments as $dept) {
                if ($dept['id'] == $departmentId) {
                    $departmentName = $dept['name'];
                    break;
                }
            }
            
            // Đếm form hiện có
            $formCount = [
                'total' => 0,
                'lanh_dao' => 0,
                'nhan_vien' => 0
            ];
            
            // Lọc form theo phòng ban
            $departmentForms = [];
            foreach ($forms as $form) {
                if ($form['department_id'] == $departmentId) {
                    $departmentForms[] = $form;
                    $formCount['total']++;
                    if (isset($form['form_type'])) {
                        if ($form['form_type'] == 'lanh_dao') {
                            $formCount['lanh_dao']++;
                        } else if ($form['form_type'] == 'nhan_vien') {
                            $formCount['nhan_vien']++;
                        }
                    }
                }
            }
        ?>
        
        <div class="bg-white border border-gray-200 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Form đánh giá cho phòng ban: <?= htmlspecialchars($departmentName) ?></h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <div class="flex items-center mb-2">
                        <div class="mr-2 w-4 h-4 bg-blue-100 rounded-full"></div>
                        <span class="text-sm">Form đánh giá cho lãnh đạo:</span>
                        <span class="ml-1 font-semibold">
                            <?php if ($formCount['lanh_dao'] > 0): ?>
                                <span class="text-green-600"><?= $formCount['lanh_dao'] ?> form</span>
                            <?php else: ?>
                                <span class="text-red-600">Chưa có</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="mr-2 w-4 h-4 bg-green-100 rounded-full"></div>
                        <span class="text-sm">Form đánh giá cho chuyên viên:</span>
                        <span class="ml-1 font-semibold">
                            <?php if ($formCount['nhan_vien'] > 0): ?>
                                <span class="text-green-600"><?= $formCount['nhan_vien'] ?> form</span>
                            <?php else: ?>
                                <span class="text-red-600">Chưa có</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                
                <div class="flex items-center justify-end">
                    <?php if ($formCount['lanh_dao'] == 0 || $formCount['nhan_vien'] == 0): ?>
                        <a href="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms/create?department_id=<?= $departmentId ?>&form_type=<?= $formCount['lanh_dao'] == 0 ? 'lanh_dao' : 'nhan_vien' ?>" 
                           class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            <span>Thêm form <?= $formCount['lanh_dao'] == 0 ? 'lãnh đạo' : 'chuyên viên' ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($formCount['total'] == 0): ?>
                <div class="bg-yellow-50 text-yellow-700 p-3 rounded">
                    <p>
                        <i class="fas fa-info-circle mr-1"></i>
                        Phòng ban này chưa có form đánh giá nào. Hãy tạo form đánh giá cho cả lãnh đạo và chuyên viên.
                    </p>
                    <div class="flex justify-center mt-3">
                        <a href="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms/create?department_id=<?= $departmentId ?>&form_type=lanh_dao" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md mr-2">
                            <i class="fas fa-plus-circle mr-1"></i> Tạo form lãnh đạo
                        </a>
                        <a href="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms/create?department_id=<?= $departmentId ?>&form_type=nhan_vien" 
                           class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                            <i class="fas fa-plus-circle mr-1"></i> Tạo form chuyên viên
                        </a>
                    </div>
                </div>
            <?php elseif ($formCount['lanh_dao'] == 0 || $formCount['nhan_vien'] == 0): ?>
                <div class="bg-yellow-50 text-yellow-700 p-3 rounded">
                    <p>
                        <i class="fas fa-info-circle mr-1"></i>
                        Phòng ban này chưa có form đánh giá cho <?= $formCount['lanh_dao'] == 0 ? 'lãnh đạo' : 'chuyên viên' ?>.
                    </p>
                </div>
            <?php else: ?>
                <div class="bg-green-50 text-green-700 p-3 rounded">
                    <p>
                        <i class="fas fa-check-circle mr-1"></i>
                        Phòng ban này đã có đầy đủ form đánh giá cho cả lãnh đạo và chuyên viên.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($forms)): ?>
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">
            <p>Chưa có form đánh giá nào. Vui lòng thêm form mới.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white" id="forms-table">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên form</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phòng ban</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đối tượng</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($forms as $form): ?>
                        <tr class="form-row" 
                            data-department="<?= $form['department_id'] ? $form['department_id'] : 'default' ?>" 
                            data-type="<?= isset($form['form_type']) ? $form['form_type'] : '' ?>">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $form['id'] ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= htmlspecialchars($form['name']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $form['department_name'] ? htmlspecialchars($form['department_name']) : '<span class="text-gray-400">Form mặc định</span>' ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php if (isset($form['form_type']) && $form['form_type'] == 'lanh_dao'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-user-tie mr-1"></i> Lãnh đạo
                                    </span>
                                <?php elseif (isset($form['form_type']) && $form['form_type'] == 'nhan_vien'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-user mr-1"></i> Chuyên viên
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400">Không xác định</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('d/m/Y H:i', strtotime($form['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms/preview/<?= $form['id'] ?>" 
                                        class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                    <a href="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms/edit/<?= $form['id'] ?>" 
                                        class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <a href="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms/delete/<?= $form['id'] ?>" 
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa form đánh giá này?');">
                                        <i class="fas fa-trash"></i> Xóa
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
// Xử lý bộ lọc
document.addEventListener('DOMContentLoaded', function() {
    const departmentFilter = document.getElementById('filter_department');
    const typeFilter = document.getElementById('filter_type');
    const applyFilterBtn = document.getElementById('apply_filter');
    const resetFilterBtn = document.getElementById('reset_filter');
    const rows = document.querySelectorAll('.form-row');
    
    // Kiểm tra URL parameters và áp dụng bộ lọc ban đầu
    function applyInitialFilters() {
        // Lấy parameters từ URL
        const urlParams = new URLSearchParams(window.location.search);
        const departmentId = urlParams.get('department_id');
        const formType = urlParams.get('form_type');
        
        // Nếu có department_id trong URL, chọn giá trị trong dropdown
        if (departmentId) {
            departmentFilter.value = departmentId;
        }
        
        // Nếu có form_type trong URL, chọn giá trị trong dropdown
        if (formType) {
            typeFilter.value = formType;
        }
        
        // Áp dụng bộ lọc ban đầu nếu có parameters
        if (departmentId || formType) {
            applyFilters();
        }
    }
    
    // Áp dụng bộ lọc
    function applyFilters() {
        const departmentValue = departmentFilter.value;
        const typeValue = typeFilter.value;
        
        rows.forEach(row => {
            const rowDepartment = row.getAttribute('data-department');
            const rowType = row.getAttribute('data-type');
            
            let showRow = true;
            
            // Lọc theo phòng ban
            if (departmentValue) {
                if (departmentValue === 'null') {
                    // Hiển thị form mặc định (department_id là null hoặc chuỗi rỗng)
                    if (rowDepartment !== 'default' && rowDepartment !== '') {
                        showRow = false;
                    }
                } else if (rowDepartment !== departmentValue) {
                    showRow = false;
                }
            }
            
            // Lọc theo loại form
            if (typeValue && rowType !== typeValue) {
                showRow = false;
            }
            
            // Hiển thị hoặc ẩn hàng dựa trên kết quả lọc
            row.style.display = showRow ? '' : 'none';
        });
        
        // Hiển thị thông báo nếu không có kết quả
        checkEmptyTable();
        
        // Cập nhật URL với các tham số bộ lọc mà không làm tải lại trang
        updateUrlWithFilters();
    }
    
    // Cập nhật URL với các tham số bộ lọc
    function updateUrlWithFilters() {
        const departmentValue = departmentFilter.value;
        const typeValue = typeFilter.value;
        
        const url = new URL(window.location.href);
        
        // Xóa parameters hiện tại
        url.searchParams.delete('department_id');
        url.searchParams.delete('form_type');
        
        // Thêm parameters mới
        if (departmentValue) {
            url.searchParams.set('department_id', departmentValue);
        }
        
        if (typeValue) {
            url.searchParams.set('form_type', typeValue);
        }
        
        // Cập nhật URL mà không làm tải lại trang
        window.history.pushState({}, '', url);
    }
    
    // Kiểm tra và hiển thị thông báo nếu không có kết quả
    function checkEmptyTable() {
        let visibleRows = 0;
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                visibleRows++;
            }
        });
        
        // Lấy bảng và thân bảng
        const table = document.getElementById('forms-table');
        const tbody = table.querySelector('tbody');
        
        // Xóa thông báo cũ nếu có
        const existingMsg = document.getElementById('no-results-message');
        if (existingMsg) {
            existingMsg.remove();
        }
        
        // Thêm thông báo nếu không có kết quả
        if (visibleRows === 0) {
            const noResultsRow = document.createElement('tr');
            noResultsRow.id = 'no-results-message';
            noResultsRow.innerHTML = `
                <td colspan="6" class="px-6 py-8 text-center text-yellow-600 bg-yellow-50">
                    <i class="fas fa-search mr-2"></i>
                    Không tìm thấy form đánh giá phù hợp với bộ lọc. Vui lòng thử lại với tiêu chí khác.
                </td>
            `;
            tbody.appendChild(noResultsRow);
        }
    }
    
    // Đặt lại bộ lọc
    function resetFilters() {
        departmentFilter.value = '';
        typeFilter.value = '';
        
        // Hiển thị lại tất cả các hàng
        rows.forEach(row => {
            row.style.display = '';
        });
        
        // Xóa thông báo không có kết quả nếu có
        const existingMsg = document.getElementById('no-results-message');
        if (existingMsg) {
            existingMsg.remove();
        }
        
        // Xóa parameters khỏi URL
        window.history.pushState({}, '', window.location.pathname);
    }
    
    // Đăng ký sự kiện
    applyFilterBtn.addEventListener('click', applyFilters);
    resetFilterBtn.addEventListener('click', resetFilters);
    
    // Cũng cho phép lọc khi nhấn Enter
    departmentFilter.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
    
    typeFilter.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
    
    // Áp dụng bộ lọc ban đầu dựa trên URL parameters
    applyInitialFilters();
});
</script> 