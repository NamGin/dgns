<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Tạo form đánh giá mới</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p><?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    
    <form action="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms/store" method="POST" class="space-y-6" id="evaluationForm">
        <div>
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                Tên form đánh giá <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" name="name" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                required placeholder="Nhập tên form đánh giá">
        </div>
        
        <div>
            <label for="department_id" class="block text-gray-700 text-sm font-bold mb-2">
                Phòng ban
            </label>
            <select id="department_id" name="department_id" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Form mặc định (áp dụng cho tất cả phòng ban chưa có form riêng) --</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= $department['id'] ?>"><?= htmlspecialchars($department['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <p class="mt-1 text-sm text-gray-500">Để trống nếu muốn tạo form mặc định cho tất cả phòng ban</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Đối tượng áp dụng <span class="text-red-500">*</span>
            </label>
            <select id="form_type" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Chọn đối tượng áp dụng --</option>
                <option value="nhan_vien">Form đánh giá cho Chuyên viên</option>
                <option value="lanh_dao">Form đánh giá cho Lãnh đạo</option>
            </select>
        </div>
        
        <!-- Form builder interface -->
        <div id="formBuilder" class="border border-gray-300 rounded-lg p-4 mb-6" style="display: none;">
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
        <input type="hidden" id="content" name="content" required>
        
        <div class="flex items-center justify-end space-x-3">
            <a href="<?= $GLOBALS['config']['base_url'] ?>admin/evaluation-forms" 
                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                Hủy
            </a>
            <button type="submit" 
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <i class="fas fa-save mr-1"></i> Lưu form đánh giá
            </button>
        </div>
    </form>
</div>

<script>
// Templates predefined for both form types
const formTemplates = {
    nhan_vien: {
        sections: [
            {
                id: "section_1",
                title: "I. Năng lực và kỹ năng chung",
                description: "Đánh giá năng lực, kỹ năng chung trong công việc",
                weight: 20,
                criteria: [
                    {
                        id: "criteria_1",
                        text: "Ý thức tổ chức kỷ luật (Chấp hành các quy định đã được ban hành. Thường xuyên đi học hỏi và đóng góp tích cực cho tập thể, có ý thức về thời gian và sử dụng thời gian có hiệu quả)",
                        max_score: 4,
                        examples: [
                            {level: 1, description: "Thường xuyên vi phạm nội quy, quy định cơ quan"},
                            {level: 2, description: "Đôi khi không tuân thủ đầy đủ quy định"},
                            {level: 3, description: "Thực hiện tốt các quy định, đúng giờ làm việc"},
                            {level: 4, description: "Tuân thủ nghiêm túc tất cả quy định, là tấm gương cho đồng nghiệp"}
                        ]
                    },
                    {
                        id: "criteria_2",
                        text: "Chất lượng và năng suất trong công việc",
                        max_score: 4,
                        examples: [
                            {level: 1, description: "Chất lượng công việc thấp, không đạt yêu cầu"},
                            {level: 2, description: "Đạt yêu cầu cơ bản, đôi khi cần chỉnh sửa"},
                            {level: 3, description: "Chất lượng tốt, ít khi cần sửa chữa"},
                            {level: 4, description: "Chất lượng xuất sắc, vượt mức kỳ vọng"}
                        ]
                    },
                    {
                        id: "criteria_3",
                        text: "Tính tự chủ và trách nhiệm trong công việc, khả năng tự làm việc",
                        max_score: 4,
                        examples: [
                            {level: 1, description: "Luôn cần sự giám sát và hướng dẫn thường xuyên"},
                            {level: 2, description: "Đôi khi cần hướng dẫn và giám sát"},
                            {level: 3, description: "Có thể làm việc độc lập và chịu trách nhiệm"},
                            {level: 4, description: "Làm việc độc lập xuất sắc, chủ động giải quyết vấn đề phát sinh"}
                        ]
                    },
                    {
                        id: "criteria_4",
                        text: "Tính chính xác, hoàn thành công việc đúng tiến độ và hiệu quả",
                        max_score: 4,
                        examples: [
                            {level: 1, description: "Thường xuyên làm sai và chậm tiến độ"},
                            {level: 2, description: "Đôi khi còn sai sót và chậm tiến độ"},
                            {level: 3, description: "Hoàn thành đúng tiến độ với độ chính xác cao"},
                            {level: 4, description: "Luôn hoàn thành trước hạn với độ chính xác tuyệt đối"}
                        ]
                    },
                    {
                        id: "criteria_5",
                        text: "Khả năng tư duy, sáng tạo và cải tiến trong công việc",
                        max_score: 4,
                        examples: [
                            {level: 1, description: "Không có ý tưởng sáng tạo, làm việc theo lối mòn"},
                            {level: 2, description: "Đôi khi có ý tưởng cải tiến nhỏ"},
                            {level: 3, description: "Thường xuyên đề xuất cải tiến hợp lý"},
                            {level: 4, description: "Liên tục đề xuất sáng kiến mới, cải tiến quy trình làm việc"}
                        ]
                    }
                ]
            },
            {
                id: "section_2",
                title: "II. Năng lực về kỹ năng (KSCL thực hiện tất cả công tác phân giao)",
                description: "Đánh giá năng lực chuyên môn và kỹ năng thực hiện nhiệm vụ",
                weight: 20,
                criteria: [
                    {
                        id: "criteria_6",
                        text: "Khả năng chuyên môn, trình độ trong giải quyết công việc",
                        max_score: 8,
                        examples: [
                            {level: 2, description: "Kiến thức chuyên môn hạn chế, thường xuyên cần hỗ trợ"},
                            {level: 4, description: "Có đủ kiến thức chuyên môn cơ bản để thực hiện công việc"},
                            {level: 6, description: "Kiến thức chuyên môn tốt, xử lý tốt các tình huống phát sinh"},
                            {level: 8, description: "Chuyên môn xuất sắc, có thể đào tạo, hướng dẫn cho người khác"}
                        ]
                    },
                    {
                        id: "criteria_7",
                        text: "Kỹ năng lập kế hoạch, tổ chức, thực hiện công việc",
                        max_score: 6,
                        examples: [
                            {level: 2, description: "Thiếu kỹ năng lập kế hoạch, thường bị động trong công việc"},
                            {level: 4, description: "Có kỹ năng lập kế hoạch cơ bản, đôi khi cần điều chỉnh"},
                            {level: 6, description: "Lập kế hoạch chi tiết, tổ chức thực hiện hiệu quả"}
                        ]
                    },
                    {
                        id: "criteria_8",
                        text: "Phối hợp, làm việc nhóm và với các phòng, bộ phận liên quan",
                        max_score: 2,
                        examples: [
                            {level: 1, description: "Khó khăn trong phối hợp, thiếu kỹ năng làm việc nhóm"},
                            {level: 2, description: "Phối hợp tốt, chủ động kết nối với các đơn vị liên quan"}
                        ]
                    },
                    {
                        id: "criteria_9",
                        text: "Kỹ năng phân tích, xử lý tình huống và giải quyết vấn đề",
                        max_score: 2,
                        examples: [
                            {level: 1, description: "Khả năng phân tích hạn chế, thiếu kỹ năng giải quyết vấn đề"},
                            {level: 2, description: "Phân tích tốt, giải quyết vấn đề hiệu quả và kịp thời"}
                        ]
                    },
                    {
                        id: "criteria_10",
                        text: "Khả năng sử dụng phương tiện, thiết bị, công nghệ",
                        max_score: 2,
                        examples: [
                            {level: 1, description: "Hạn chế trong sử dụng các phương tiện, thiết bị"},
                            {level: 2, description: "Thành thạo và sử dụng hiệu quả các phương tiện, thiết bị"}
                        ]
                    }
                ]
            },
            {
                id: "section_3",
                title: "III. Kết quả thực hiện nhiệm vụ được giao (KPIs theo định kỳ hàng kỳ)",
                description: "Đánh giá kết quả công việc so với chỉ tiêu được giao",
                weight: 50,
                criteria: [
                    {
                        id: "criteria_11",
                        text: "Kết quả thực hiện các công việc, nhiệm vụ được giao (KPIs)",
                        max_score: 50,
                        examples: [
                            {level: 10, description: "Hoàn thành dưới 50% chỉ tiêu được giao"},
                            {level: 20, description: "Hoàn thành từ 50-70% chỉ tiêu được giao"},
                            {level: 30, description: "Hoàn thành từ 70-85% chỉ tiêu được giao"},
                            {level: 40, description: "Hoàn thành từ 85-95% chỉ tiêu được giao"},
                            {level: 50, description: "Hoàn thành 95-100% hoặc vượt chỉ tiêu được giao"}
                        ]
                    }
                ]
            },
            {
                id: "section_4",
                title: "IV. Đột phá sáng tạo, tinh thần học hỏi, phục vụ",
                description: "Đánh giá khả năng sáng tạo và tinh thần học hỏi",
                weight: 10,
                criteria: [
                    {
                        id: "criteria_12",
                        text: "Tinh thần học hỏi, phát triển kỹ năng và kiến thức",
                        max_score: 4,
                        examples: [
                            {level: 1, description: "Ít chủ động học hỏi, phát triển bản thân"},
                            {level: 2, description: "Thỉnh thoảng chủ động học hỏi khi được khuyến khích"},
                            {level: 3, description: "Thường xuyên học hỏi, nâng cao kỹ năng"},
                            {level: 4, description: "Chủ động học hỏi, không ngừng tự phát triển bản thân"}
                        ]
                    },
                    {
                        id: "criteria_13",
                        text: "Đề xuất sáng kiến, cải tiến trong công việc",
                        max_score: 3,
                        examples: [
                            {level: 1, description: "Không có sáng kiến, đề xuất cải tiến"},
                            {level: 2, description: "Có một số đề xuất cải tiến nhỏ"},
                            {level: 3, description: "Thường xuyên đề xuất sáng kiến và cải tiến có giá trị"}
                        ]
                    },
                    {
                        id: "criteria_14",
                        text: "Tinh thần phục vụ, thái độ với đồng nghiệp và người được phục vụ",
                        max_score: 3,
                        examples: [
                            {level: 1, description: "Thái độ phục vụ chưa tốt, đôi khi gây phiền hà"},
                            {level: 2, description: "Thái độ phục vụ tốt, thân thiện với mọi người"},
                            {level: 3, description: "Luôn nhiệt tình, tận tâm phục vụ, được mọi người đánh giá cao"}
                        ]
                    }
                ]
            }
        ],
        competency_levels: [
            {name: "Không đạt", range: "0-69", description: "Không đáp ứng yêu cầu, cần cải thiện nhiều"},
            {name: "Đạt", range: "70-84", description: "Đáp ứng yêu cầu cơ bản của vị trí"},
            {name: "Xuất sắc", range: "85-100", description: "Vượt mức kỳ vọng, đóng góp xuất sắc"}
        ],
        form_type: "nhan_vien"
    },
    lanh_dao: {
        sections: [
            {
                id: "section_1",
            title: "I. Ý thức tổ chức kỷ luật",
                description: "Đánh giá ý thức, thái độ và tuân thủ kỷ luật của lãnh đạo",
                weight: 15,
                criteria: [
                    {
                        id: "criteria_1",
                        text: "Thực hiện nghiêm túc các quy định, quy chế, nội quy của cơ quan",
                        max_score: 4,
                        examples: [
                            {level: 1, description: "Thường xuyên vi phạm nội quy, quy định"},
                            {level: 2, description: "Thỉnh thoảng không tuân thủ một số quy định nhỏ"},
                            {level: 3, description: "Tuân thủ tốt các quy định của cơ quan"},
                            {level: 4, description: "Luôn gương mẫu chấp hành nghiêm túc, vận động người khác tuân thủ"}
                        ]
                    },
                    {
                        id: "criteria_2",
                        text: "Chấp hành sự phân công của tổ chức",
                        max_score: 3,
                        examples: [
                            {level: 1, description: "Thường từ chối hoặc không hoàn thành nhiệm vụ được giao"},
                            {level: 2, description: "Đôi khi chưa hoàn thành tốt nhiệm vụ được giao"},
                            {level: 3, description: "Luôn chấp hành tốt và chủ động trong mọi phân công"}
                        ]
                    },
                    {
                        id: "criteria_3",
                        text: "Có thái độ đúng mực và phong cách ứng xử, lề lối làm việc chuẩn mực",
                        max_score: 4,
                        examples: [
                            {level: 1, description: "Thái độ và phong cách không phù hợp với vị trí lãnh đạo"},
                            {level: 2, description: "Đôi khi có cách ứng xử chưa phù hợp trong một số tình huống"},
                            {level: 3, description: "Thường có thái độ và cách ứng xử phù hợp"},
                            {level: 4, description: "Luôn là tấm gương về thái độ, phong cách và lề lối làm việc"}
                        ]
                    },
                    {
                        id: "criteria_4",
                        text: "Báo cáo đầy đủ, kịp thời, trung thực với cấp trên",
                        max_score: 4,
                        examples: [
                            {level: 1, description: "Báo cáo không đầy đủ, thiếu trung thực"},
                            {level: 2, description: "Báo cáo chậm trễ hoặc thiếu một số thông tin quan trọng"},
                            {level: 3, description: "Báo cáo đầy đủ và kịp thời các vấn đề quan trọng"},
                            {level: 4, description: "Luôn chủ động báo cáo kịp thời, đầy đủ và trung thực"}
                        ]
                    }
                ]
            },
            {
                id: "section_2",
                title: "II. Năng lực lãnh đạo và quản lý",
                description: "Đánh giá khả năng lãnh đạo, điều hành và quản lý",
                weight: 35,
                criteria: [
                    {
                        id: "criteria_5",
                        text: "Có năng lực tập hợp và xây dựng tập thể đoàn kết",
                        max_score: 6,
                        examples: [
                            {level: 2, description: "Chưa tạo được sự đồng thuận, tập thể thiếu đoàn kết"},
                            {level: 4, description: "Có khả năng tập hợp, duy trì đoàn kết trong tập thể"},
                            {level: 6, description: "Xây dựng được tập thể đoàn kết, gắn bó và hỗ trợ lẫn nhau"}
                        ]
                    },
                    {
                        id: "criteria_6",
                        text: "Khả năng chỉ đạo, điều hành, kiểm soát việc thực hiện nhiệm vụ",
                        max_score: 7,
                        examples: [
                            {level: 2, description: "Thiếu kỹ năng chỉ đạo, điều hành còn lúng túng"},
                            {level: 4, description: "Có khả năng chỉ đạo, điều hành các công việc thường ngày"},
                            {level: 6, description: "Điều hành hiệu quả, kiểm soát tốt tiến độ và chất lượng công việc"},
                            {level: 7, description: "Xuất sắc trong chỉ đạo và điều hành, đạt hiệu quả cao"}
                        ]
                    },
                    {
                        id: "criteria_7",
                        text: "Khả năng phân công, giao việc phù hợp với năng lực",
                        max_score: 6,
                        examples: [
                            {level: 2, description: "Phân công không phù hợp với năng lực của nhân viên"},
                            {level: 4, description: "Phân công công việc tương đối phù hợp với năng lực"},
                            {level: 6, description: "Phân công công việc rất phù hợp, phát huy tối đa năng lực của nhân viên"}
                        ]
                    },
                    {
                        id: "criteria_8",
                        text: "Khả năng ra quyết định và giải quyết vấn đề",
                        max_score: 8,
                        examples: [
                            {level: 2, description: "Thường do dự, trì hoãn hoặc né tránh việc ra quyết định"},
                            {level: 4, description: "Ra quyết định phù hợp trong các tình huống thông thường"},
                            {level: 6, description: "Ra quyết định tốt, giải quyết hiệu quả các vấn đề phức tạp"},
                            {level: 8, description: "Xuất sắc trong việc ra quyết định, giải quyết các vấn đề khó khăn"}
                        ]
                    },
                    {
                        id: "criteria_9",
                        text: "Khả năng lập kế hoạch chiến lược và tổ chức thực hiện",
                        max_score: 8,
                        examples: [
                            {level: 2, description: "Không có tầm nhìn chiến lược, kế hoạch thiếu khả thi"},
                            {level: 4, description: "Có kế hoạch phù hợp, tổ chức thực hiện còn hạn chế"},
                            {level: 6, description: "Lập kế hoạch tốt, tổ chức thực hiện hiệu quả"},
                            {level: 8, description: "Có tầm nhìn chiến lược xuất sắc, triển khai thực hiện đạt kết quả cao"}
                        ]
                    }
                ]
            },
            {
                id: "section_3",
                title: "III. Khả năng chuyên môn và tham mưu",
                description: "Đánh giá kiến thức chuyên môn và khả năng tham mưu, đề xuất",
                weight: 20,
                criteria: [
                    {
                        id: "criteria_10",
                        text: "Làm tốt công tác tham mưu, hoạch định chính sách",
                        max_score: 8,
                        examples: [
                            {level: 2, description: "Năng lực tham mưu còn hạn chế, đề xuất chưa khả thi"},
                            {level: 4, description: "Có khả năng tham mưu các vấn đề thường ngày"},
                            {level: 6, description: "Tham mưu tốt các chính sách, kế hoạch quan trọng"},
                            {level: 8, description: "Xuất sắc trong tham mưu hoạch định chính sách, định hướng phát triển"}
                        ]
                    },
                    {
                        id: "criteria_11",
                        text: "Năng lực chuyên môn trong lĩnh vực phụ trách",
                        max_score: 7,
                        examples: [
                            {level: 2, description: "Kiến thức chuyên môn còn hạn chế"},
                            {level: 4, description: "Có kiến thức chuyên môn đủ để điều hành công việc"},
                            {level: 6, description: "Kiến thức chuyên môn tốt, áp dụng hiệu quả vào thực tiễn"},
                            {level: 7, description: "Kiến thức chuyên môn sâu rộng, là chuyên gia trong lĩnh vực"}
                        ]
                    },
                    {
                        id: "criteria_12",
                        text: "Khả năng đổi mới và sáng tạo trong quản lý",
                        max_score: 5,
                        examples: [
                            {level: 1, description: "Ít có đề xuất đổi mới, thường theo lối mòn cũ"},
                            {level: 3, description: "Có một số cải tiến, đổi mới trong quản lý"},
                            {level: 5, description: "Thường xuyên đề xuất ý tưởng sáng tạo, cải tiến hiệu quả"}
                        ]
                    }
                ]
            },
            {
                id: "section_4",
                title: "IV. Kết quả thực hiện nhiệm vụ",
                description: "Đánh giá kết quả công việc và hiệu quả quản lý",
                weight: 30,
                criteria: [
                    {
                        id: "criteria_13",
                        text: "Kết quả hoàn thành nhiệm vụ được giao",
                        max_score: 10,
                        examples: [
                            {level: 3, description: "Hoàn thành dưới 70% nhiệm vụ được giao"},
                            {level: 6, description: "Hoàn thành từ 70% đến dưới 90% nhiệm vụ được giao"},
                            {level: 8, description: "Hoàn thành từ 90% đến 100% nhiệm vụ được giao"},
                            {level: 10, description: "Hoàn thành 100% nhiệm vụ, có nhiều kết quả vượt trội"}
                        ]
                    },
                    {
                        id: "criteria_14",
                        text: "Kết quả hoạt động của đơn vị được giao phụ trách",
                        max_score: 10,
                        examples: [
                            {level: 3, description: "Kết quả hoạt động của đơn vị còn nhiều hạn chế"},
                            {level: 6, description: "Đơn vị hoạt động ổn định, đạt được các mục tiêu cơ bản"},
                            {level: 8, description: "Đơn vị hoạt động tốt, đạt hầu hết các mục tiêu đề ra"},
                            {level: 10, description: "Đơn vị hoạt động xuất sắc, vượt mọi chỉ tiêu đề ra"}
                        ]
                    },
                    {
                        id: "criteria_15",
                        text: "Hiệu quả sử dụng và phát triển nguồn lực",
                        max_score: 5,
                        examples: [
                            {level: 1, description: "Sử dụng nguồn lực lãng phí, chưa hiệu quả"},
                            {level: 3, description: "Sử dụng nguồn lực hợp lý, đạt hiệu quả trung bình"},
                            {level: 5, description: "Sử dụng nguồn lực hiệu quả, tối ưu hóa chi phí và nguồn lực"}
                        ]
                    },
                    {
                        id: "criteria_16",
                        text: "Mức độ hoàn thành các chỉ tiêu, kế hoạch của tổ chức",
                        max_score: 5,
                        examples: [
                            {level: 1, description: "Không đạt nhiều chỉ tiêu, kế hoạch đề ra"},
                            {level: 3, description: "Đạt được hầu hết các chỉ tiêu, kế hoạch quan trọng"},
                            {level: 5, description: "Vượt mức hầu hết các chỉ tiêu, kế hoạch quan trọng"}
                        ]
                    }
                ]
            }
        ],
        competency_levels: [
            {name: "Yếu", range: "0-49", description: "Không đáp ứng yêu cầu của vị trí lãnh đạo"},
            {name: "Trung bình", range: "50-69", description: "Đáp ứng một phần yêu cầu của vị trí lãnh đạo"},
            {name: "Khá", range: "70-84", description: "Đáp ứng hầu hết yêu cầu của vị trí lãnh đạo"},
            {name: "Tốt", range: "85-94", description: "Đáp ứng đầy đủ, thỉnh thoảng vượt mức yêu cầu"},
            {name: "Xuất sắc", range: "95-100", description: "Vượt xa yêu cầu của vị trí lãnh đạo"}
        ],
        form_type: "lanh_dao"
    }
};

// Khi trang được tải
document.addEventListener('DOMContentLoaded', function() {
    // Form builder elements
    const formBuilder = document.getElementById('formBuilder');
    const sectionsContainer = document.getElementById('sections_container');
    const departmentSelect = document.getElementById('department_id');
    const formTypeSelect = document.getElementById('form_type');
    const formContent = document.getElementById('content');
    
    // Biến đếm số phần đã thêm
    let sectionCount = 0;
    
    // Xử lý URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const departmentId = urlParams.get('department_id');
    const formType = urlParams.get('form_type');
    
    // Đặt giá trị từ URL parameters
    if (departmentId) {
        departmentSelect.value = departmentId;
    }
    
    if (formType) {
        formTypeSelect.value = formType;
        // Hiển thị form builder nếu có form_type
        formBuilder.style.display = 'block';
        // Tải mẫu form
        clearFormBuilder();
        loadFormTemplate(formType);
    }
    
    // Xử lý khi chọn loại form
    formTypeSelect.addEventListener('change', function() {
        const formType = this.value;
        
        if (formType) {
            // Hiển thị form builder
            formBuilder.style.display = 'block';
            
            // Xóa dữ liệu cũ
            clearFormBuilder();
            
            // Tải mẫu form dựa trên loại
            loadFormTemplate(formType);
        } else {
            // Ẩn form builder khi không chọn loại form
            formBuilder.style.display = 'none';
        }
    });
    
    // Kiểm tra khi chọn phòng ban và loại form
    departmentSelect.addEventListener('change', function() {
        checkExistingForm();
        generateFormName();
    });
    
    formTypeSelect.addEventListener('change', function() {
        checkExistingForm();
        generateFormName();
    });
    
    function checkExistingForm() {
        const departmentId = document.getElementById('department_id').value;
        const formType = document.getElementById('form_type').value;
        
        // Nếu đã chọn cả phòng ban (không phải form mặc định) và loại form
        if (departmentId && formType) {
            // Gửi request kiểm tra form tồn tại
            const baseUrl = '<?= $GLOBALS['config']['base_url'] ?>';
            fetch(`${baseUrl}admin/evaluation-forms/check-existing?department_id=${departmentId}&form_type=${formType}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        // Hiển thị thông báo nếu form đã tồn tại
                        const warningDiv = document.createElement('div');
                        warningDiv.className = 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4';
                        warningDiv.id = 'existing-form-warning';
                        warningDiv.innerHTML = `
                            <p>Phòng ban này đã có form đánh giá cho ${formType === 'lanh_dao' ? 'lãnh đạo' : 'chuyên viên'}. 
                            <a href="${baseUrl}admin/evaluation-forms/edit/${data.form_id}" class="text-blue-600 underline">
                                Nhấn vào đây để chỉnh sửa form hiện có
                            </a>.</p>
                        `;
                        
                        // Xóa cảnh báo cũ nếu có
                        const oldWarning = document.getElementById('existing-form-warning');
                        if (oldWarning) {
                            oldWarning.remove();
                        }
                        
                        // Thêm cảnh báo mới vào trước form
                        const formEl = document.getElementById('evaluationForm');
                        formEl.parentNode.insertBefore(warningDiv, formEl);
                    } else {
                        // Xóa cảnh báo nếu form chưa tồn tại
                        const oldWarning = document.getElementById('existing-form-warning');
                        if (oldWarning) {
                            oldWarning.remove();
                        }
                    }
                })
                .catch(error => console.error('Error checking existing form:', error));
        }
    }
    
    // Thêm phần mới
    document.getElementById('addSection').addEventListener('click', function() {
        addSection();
    });
    
    // Xử lý submit form
    document.getElementById('evaluationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Kiểm tra xem đã chọn loại form hay chưa
        if (!formTypeSelect.value) {
            alert('Vui lòng chọn đối tượng áp dụng trước khi lưu');
            return;
        }
        
        // Lấy toàn bộ dữ liệu từ form builder
        const formData = collectFormData();
        
        // Kiểm tra tính hợp lệ của form
        if (!validateFormData(formData)) {
            return;
        }
        
        // Đặt giá trị vào trường hidden
        formContent.value = JSON.stringify(formData);
        
        // Submit form sau khi đã xử lý dữ liệu
        this.submit();
    });
    
    // Các hàm hỗ trợ
    function clearFormBuilder() {
        sectionsContainer.innerHTML = '';
        sectionCount = 0;
    }
    
    function loadFormTemplate(formType) {
        if (formTemplates[formType]) {
        const template = formTemplates[formType];
        
            // Tạo các phần từ template
            template.sections.forEach(section => {
                addSection(section);
            });
            
            // Thêm các mức năng lực nếu có
            if (template.competency_levels) {
                const competencyLevelsContainer = document.createElement('div');
                competencyLevelsContainer.className = 'mt-6 p-4 bg-gray-50 rounded-lg';
                competencyLevelsContainer.innerHTML = `
                    <h3 class="text-lg font-semibold mb-3">Mức năng lực đánh giá</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        ${template.competency_levels.map(level => `
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
    }
    
    function addSection(sectionData = null) {
        sectionCount++;
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
    
    function collectFormData() {
        const formType = formTypeSelect.value;
        const sections = [];
        
        // Thu thập dữ liệu từ các phần
        document.querySelectorAll('.section').forEach(sectionEl => {
            const sectionData = {
                id: sectionEl.id,
                title: sectionEl.querySelector('.section-title').value,
                description: sectionEl.querySelector('.section-description').value,
                weight: parseInt(sectionEl.querySelector('.section-weight').value) || 0,
                criteria: []
            };
            
            // Thu thập dữ liệu từ các tiêu chí
            sectionEl.querySelectorAll('.criterion').forEach(criterionEl => {
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
        
        // Lấy thông tin mức năng lực tổng thể từ template
        const competencyLevels = formTemplates[formType].competency_levels || [];
        
        return {
            form_type: formType,
            sections: sections,
            competency_levels: competencyLevels
        };
    }
    
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
    
    // Tự động tạo tên form dựa trên phòng ban và loại form
    function generateFormName() {
        const departmentId = departmentSelect.value;
        const formType = formTypeSelect.value;
        const nameField = document.getElementById('name');
        
        // Nếu người dùng đã nhập tên tùy chỉnh, không thay đổi
        if (nameField.dataset.customized === 'true') {
            return;
        }
        
        // Tìm tên phòng ban
        let departmentName = '';
        if (departmentId) {
            const selectedOption = departmentSelect.options[departmentSelect.selectedIndex];
            departmentName = selectedOption.text;
        } else {
            departmentName = 'Mặc định';
        }
        
        // Tạo tên form
        if (formType && departmentName) {
            const formTypeName = formType === 'lanh_dao' ? 'Lãnh đạo' : 'Chuyên viên';
            nameField.value = `Form đánh giá ${formTypeName} - ${departmentName}`;
        }
    }
    
    // Đánh dấu khi người dùng chỉnh sửa tên form
    document.getElementById('name').addEventListener('input', function() {
        this.dataset.customized = 'true';
    });
    
    // Tạo tên form ban đầu nếu có cả department và form_type
    if (departmentId && formType) {
        generateFormName();
    }
});
</script> 