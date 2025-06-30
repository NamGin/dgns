<?php
$config = [
    'db_host' => 'localhost',
    'db_name' => 'job_danh_gia_nhan_su',
    'db_user' => 'root',
    'db_pass' => '',
    
    'site_name' => 'Đánh giá nhân sự',
    'base_url' => 'http://localhost/dgns/',

    'debug' => true
];



function dd($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}