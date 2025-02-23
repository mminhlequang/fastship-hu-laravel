<?php
/**
 * Setting app
 */
return [
    "app_logo" => "<b>Promotion</b>",
    "app_logo_mini" => "/images/logoFB.png",

	"avatar_default" => '/images/avatar.png',

    "active" => 1,
    "inactive" => 0,

    "public_avatar" => '/images/avatar/',

    "perpage" => 10,
    "group_drivers" =>[
        '1' => 'Nhóm 1',
        '2' => 'Nhóm 2',
        '3' => 'Nhóm 3',
        '4' => 'Nhóm 4'
    ],

    'paginate' => [
        'page5' => 5,
        'page6' => 6,
        'page8' => 8,
        'page12' => 12,
    ],
    'gender' => [
        '1' => 'Nam',
        '2' => 'Nữ',
    ],

    'type_news' => [
        '0' => 'Tất cả',
        '1' => 'Khách hàng',
        '2' => 'Tài xế'
    ],
    'payment' => [
        '1' => 'Thanh toán khi nhận hàng',
        '2' => 'Chuyển khoản ngân hàng',
    ],
    'nation' => [
        '1' => 'Ngân hàng Việt Nam',
        '2' => 'Ngân hàng Hàng Quốc',
    ],
    'status' => [
        '1' => 'Đã tiếp nhận đơn hàng',
        '2' => 'Đang gói hàng',
        '3' => 'Đang vận chuyển',
        '4' => 'Đã nhận hàng',

    ],
    "rate" => [
        '1' => '☆',
        '2' => '☆☆',
        '3' => '☆☆☆',
        '4' => '☆☆☆☆',
        '5' => '☆☆☆☆☆',
    ],
	"log_active" => true,
    "format" => [
	    "datetime" => "d/m/Y H:i",
	    "date" => "d/m/Y",
	    "date_js" => 'dd/mm/yyyy',
    ],
    "approved" => [
	    'cancel' => -10,//hủy vé
	    'tmp' => 5,//lưu tạm
	    'save' => 10//lưu
    ],
	
	//set roles list
	"roles" => [
		'company_admin' => 'admin',
        'user' => 'user',
        'company' => 'company',
	],
    "conclude" => [
        '1' => 'Đạt',
        '2' => 'Không đạt',
    ],
    'settings_site' => [
        [
            "key" => "company_logo",
            "value" => "",
            "description" => "Đường dẫn lưu logo",
            "type"=>"image",
            "group_data"=>"company_info", // Thông tin công ty
        ],
        [
            "key" => "company_website",
            "value" => "",
            "description" =>"Tên website",
            "type"=>"text",
            "group_data"=>"company_info",// Thông tin công ty
        ],
        [
            "key" => "company_address",
            "value" => "",
            "description" =>"Địa chỉ",
            "group_data"=>"company_info",// Thông tin công ty
            "type"=>"text"
        ],
        [
            "key" => "company_phone",
            "value" => "",
            "description" =>"Số điện thoại",
            "group_data"=>"company_info",// Thông tin công ty
            "type"=>"text"
        ],
        [
            "key" => "company_link",
            "value" => '',
            "description" =>"Website",
            "group_data"=>"company_info",// Thông tin công ty
            "type"=>"text"
        ],
        [
            "key" => "follow_facebook",
            "value" => "https://www.facebook.com/",
            "description" =>"Địa chỉ facebook",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_twitter",
            "value" => "https://www.twitter.com/",
            "description" =>"Địa chỉ google",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_linked",
            "value" => "https://www.linkedin.com/",
            "description" =>"Địa chỉ linked in",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_google",
            "value" => "https://www.google.com/",
            "description" =>"Địa chỉ google search",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_youtube",
            "value" => "https://www.youtube.com/",
            "description" =>"Địa chỉ youtube",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_zalo",
            "value" => "https://zalo.me",
            "description" =>"Địa chỉ Zalo",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_instagram",
            "value" => "https://www.instagram.com/",
            "description" =>"Địa chỉ Instagram",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ], [
            "key" => "follow_messenger",
            "value" => "https://www.messenger.com/",
            "description" =>"Địa chỉ Messenger",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "fanpage_facebook_body",
            "value" => "",
            "description" =>"Mã nhúng fanpage facebook body",
            "group_data"=>"iframe_info",// Mã nhúng
            "type"=>"textarea"
        ],
        [
            "key" => "fanpage_facebook",
            "value" => "",
            "description" =>"Mã nhúng fanpage facebook",
            "group_data"=>"iframe_info",// Mã nhúng
            "type"=>"textarea"
        ],
        [
            "key" => "google_analytics",
            "value" => "",
            "description" =>"Mã nhúng google analytics",
            "group_data"=>"iframe_info",// Mã nhúng
            "type"=>"textarea"
        ],
        [
            "key" => "google_map",
            "value" => "",
            "description" =>"Mã nhúng google map",
            "group_data"=>"iframe_info",// Mã nhúng
            "type"=>"textarea"
        ],
        [
            "key" => "meta_title",
            "value" => "",
            "description" =>"SEO tiêu đề web",
            "group_data"=>"system_info",// Cấu hình SEO
            "type"=>"text"
        ],
        [
            "key" => "meta_keyword",
            "value" => "",
            "description" =>"SEO từ khóa web",
            "group_data"=>"system_info",// Cấu hình SEO
            "type"=>"textarea"
        ],
        [
            "key" => "meta_description",
            "value" => "",
            "description" =>"SEO mô tả web",
            "group_data"=>"system_info",// Cấu hình SEO
            "type"=>"textarea"
        ]
    ],
]
?>