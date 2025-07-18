<?php
/**
 * Setting app
 */
return [
    "app_logo" => "<b>FastShip</b>",
    "app_logo_mini" => "/images/logoFB.png",

	"avatar_default" => '/images/avatar.png',

    "active" => 1,
    "inactive" => 0,

    "public_avatar" => '/images/avatar/',

    "perpage" => 10,

    "type_notification" => [
        "0" => "Tất cả",
        "1" => "Thành viên",
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

    'payment' => [
        '1' => 'Thanh toán khi nhận hàng',
        '2' => 'Chuyển khoản ngân hàng',
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
            "description" => "Link logo",
            "type"=>"image",
            "group_data"=>"company_info", // Thông tin công ty
        ],
        [
            "key" => "company_website",
            "value" => "",
            "description" =>"Name website",
            "type"=>"text",
            "group_data"=>"company_info",// Thông tin công ty
        ],
        [
            "key" => "company_address",
            "value" => "",
            "description" =>"Company Address",
            "group_data"=>"company_info",// Thông tin công ty
            "type"=>"text"
        ],
        [
            "key" => "company_phone",
            "value" => "",
            "description" =>"Company phone",
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
            "key" => "fee_km",
            "value" => '2000',
            "description" =>"Shipping fee by km",
            "group_data"=>"company_info",// Thông tin công ty
            "type"=>"number"
        ],
        [
            "key" => "app_fee",
            "value" => '3',
            "description" =>"App Fee",
            "group_data"=>"company_info",// Thông tin công ty
            "type"=>"number"
        ],

        [
            "key" => "store_rate",
            "value" => '90',
            "description" =>"Store rate",
            "group_data"=>"company_info",// Thông tin công ty
            "type"=>"number"
        ],

        [
            "key" => "app_rate",
            "value" => '10',
            "description" =>"App rate",
            "group_data"=>"company_info",// Thông tin công ty
            "type"=>"number"
        ],
        [
            "key" => "follow_facebook",
            "value" => "https://www.facebook.com/",
            "description" =>"Link facebook",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_twitter",
            "value" => "https://www.twitter.com/",
            "description" =>"Link google",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_linked",
            "value" => "https://www.linkedin.com/",
            "description" =>"Link linked in",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_google",
            "value" => "https://www.google.com/",
            "description" =>"Link google search",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_youtube",
            "value" => "https://www.youtube.com/",
            "description" =>"Link youtube",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_zalo",
            "value" => "https://zalo.me",
            "description" =>"Link Zalo",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_instagram",
            "value" => "https://www.instagram.com/",
            "description" =>"Link Instagram",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_messenger",
            "value" => "https://www.messenger.com/",
            "description" =>"Link Messenger",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_ios",
            "value" => "https://apps.apple.com/us/app/english-challenge/id1415589178",
            "description" =>"Link IOS APP USER",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_android",
            "value" => "https://play.google.com/store/apps/details?id=com.huesoft.englishchallenge",
            "description" =>"Link Android USER",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_ios_driver",
            "value" => "https://apps.apple.com/us/app/english-challenge/id1415589178",
            "description" =>"Link IOS APP DRIVER",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_android_driver",
            "value" => "https://play.google.com/store/apps/details?id=com.huesoft.englishchallenge",
            "description" =>"Link Android DRIVER",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_ios_partner",
            "value" => "https://apps.apple.com/us/app/english-challenge/id1415589178",
            "description" =>"Link IOS APP PARTNER",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_android_partner",
            "value" => "https://play.google.com/store/apps/details?id=com.huesoft.englishchallenge",
            "description" =>"Link Android PARTNER",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],

        [
            "key" => "meta_title",
            "value" => "",
            "description" =>"SEO meta title web",
            "group_data"=>"system_info",// Cấu hình SEO
            "type"=>"text"
        ],
        [
            "key" => "meta_keyword",
            "value" => "",
            "description" =>"SEO meta keyword web",
            "group_data"=>"system_info",// Cấu hình SEO
            "type"=>"textarea"
        ],
        [
            "key" => "meta_description",
            "value" => "",
            "description" =>"SEO meta description web",
            "group_data"=>"system_info",// Cấu hình SEO
            "type"=>"textarea"
        ],
        [
            "key" => "term_service",
            "value" => "",
            "description" =>"Terms of Service",
            "group_data"=>"system_policy",// Cấu hình SEO
            "type"=> "textarea"
        ],
        [
            "key" => "privacy_policy",
            "value" => "",
            "description" =>"Privacy Policy",
            "group_data"=>"system_policy",// Cấu hình SEO
            "type"=> "textarea"
        ],
        [
            "key" => "payment_policy",
            "value" => "",
            "description" =>"Payment Policy",
            "group_data"=>"system_policy",// Cấu hình SEO
            "type"=> "textarea"
        ],
        [
            "key" => "refund_policy",
            "value" => "",
            "description" =>"Refund & Cancellation Policy",
            "group_data"=>"system_policy",// Cấu hình SEO
            "type"=> "textarea"
        ],
        [
            "key" => "cookie_policy",
            "value" => "",
            "description" =>"Cookies Policy",
            "group_data"=>"system_policy",// Cấu hình SEO
            "type"=> "textarea"
        ],
    ],
]
?>