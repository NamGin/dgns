<?php 
$dataRate = [
    // Dữ liệu đánh giá cho lãnh đạo
    'lanh_dao' => [
        'part1' => [
            'title' => 'I. Ý thức tổ chức kỷ luật',
            'total_max' => 20,
            'criteria' => [
                1 => [
                    'text' => 'Thực hiện nghiêm túc các quy định, quy chế, nội quy của cơ quan',
                    'max_score' => 5
                ],
                2 => [
                    'text' => 'Chấp hành sự phân công của tổ chức',
                    'max_score' => 4
                ],
                3 => [
                    'text' => 'Có thái độ đúng mực và phong cách ứng xử, lề lối làm việc chuẩn mực',
                    'max_score' => 4
                ],
                4 => [
                    'text' => 'Có tinh thần trách nhiệm với công việc, phương pháp làm việc khoa học',
                    'max_score' => 4
                ],
                5 => [
                    'text' => 'Báo cáo đầy đủ, kịp thời, trung thực với cấp trên',
                    'max_score' => 3
                ]
            ]
        ],
        'part2' => [
            'title' => 'II. Năng lực và kỹ năng',
            'total_max' => 20,
            'criteria' => [
                6 => [
                    'text' => 'Có năng lực tập hợp công chức, viên chức, xây dựng đơn vị bộ phận đoàn kết',
                    'max_score' => 2
                ],
                7 => [
                    'text' => 'Chỉ đạo, điều hành, kiểm soát việc thực hiện nhiệm vụ',
                    'max_score' => 2
                ],
                8 => [
                    'text' => 'Phối hợp, tạo lập mối quan hệ tốt với cá nhân, tổ chức',
                    'max_score' => 2
                ],
                9 => [
                    'text' => 'Hoàn thành kịp thời và bảo đảm chất lượng, hiệu quả nhiệm vụ đột xuất',
                    'max_score' => 2
                ],
                10 => [
                    'text' => 'Làm tốt công tác tham mưu, hoạch định, xây dựng văn bản quy phạm pháp luật',
                    'max_score' => 2
                ],
                11 => [
                    'text' => 'Làm tốt công tác kiểm tra, thanh tra, giải quyết khiếu nại',
                    'max_score' => 5
                ],
                12 => [
                    'text' => 'Xây dựng chương trình, kế hoạch hoạt động hàng Quý',
                    'max_score' => 5
                ]
            ]
        ],
        'part3' => [
            'title' => 'III. Kết quả thực hiện chức trách, nhiệm vụ được giao',
            'total_max' => 60,
            'criteria' => [
                'level1' => [
                    'text' => 'Thực hiện nhiệm vụ được giao đảm bảo tiến độ và chất lượng',
                    'max_score' => 30
                ],
                'level2' => [
                    'text' => 'Lãnh đạo, chỉ đạo, điều hành các cơ quan, đơn vị hoặc lĩnh vực công tác',
                    'max_score' => 30
                ]
            ]
        ]
    ],
    // Dữ liệu đánh giá cho Chuyên Viên
    'nhan_vien' => [
        'part1' => [
            'title' => 'I. Ý thức tổ chức kỷ luật',
            'total_max' => 20,
            'criteria' => [
                1 => [
                    'text' => 'Thực hiện nghiêm túc các quy định, quy chế, nội quy của cơ quan',
                    'max_score' => 5
                ],
                2 => [
                    'text' => 'Chấp hành sự phân công của tổ chức',
                    'max_score' => 4
                ],
                3 => [
                    'text' => 'Có thái độ đúng mực và phong cách ứng xử, lề lối làm việc chuẩn mực',
                    'max_score' => 4
                ],
                4 => [
                    'text' => 'Có tinh thần trách nhiệm với công việc, phương pháp làm việc khoa học',
                    'max_score' => 4
                ],
                5 => [
                    'text' => 'Báo cáo đầy đủ, kịp thời, trung thực với cấp trên',
                    'max_score' => 3
                ]
            ]
        ],
        'part2' => [
            'title' => 'II. Năng lực và kỹ năng',
            'total_max' => 20,
            'criteria' => [
                6 => [
                    'text' => 'Chủ động nghiên cứu, cập nhật thường xuyên kiến thức pháp luật',
                    'max_score' => 2
                ],
                7 => [
                    'text' => 'Chủ động đề xuất tham mưu giải quyết công việc',
                    'max_score' => 2
                ],
                8 => [
                    'text' => 'Phối hợp, tạo lập mối quan hệ tốt với cá nhân, tổ chức',
                    'max_score' => 2
                ],
                9 => [
                    'text' => 'Có trên 50% nhiệm vụ được giao hoàn thành vượt mức',
                    'max_score' => 10
                ],
                10 => [
                    'text' => 'Sẵn sàng tiếp nhận, xử lý kịp thời các nhiệm vụ được cấp trên giao',
                    'max_score' => 2
                ],
                11 => [
                    'text' => 'Hoàn thành kịp thời và bảo đảm chất lượng, hiệu quả nhiệm vụ',
                    'max_score' => 2
                ]
            ]
        ],
        'part3' => [
            'title' => 'III. Kết quả thực hiện chức trách, nhiệm vụ được giao',
            'total_max' => 60,
            'completion_levels' => [
                '60' => 'Hoàn thành 100% nhiệm vụ',
                '40' => 'Hoàn thành từ 80% đến dưới 100% nhiệm vụ',
                '10' => 'Hoàn thành từ 50% đến dưới 80% nhiệm vụ',
                '0' => 'Hoàn thành dưới 50% nhiệm vụ'
            ]
        ]
    ]
];

