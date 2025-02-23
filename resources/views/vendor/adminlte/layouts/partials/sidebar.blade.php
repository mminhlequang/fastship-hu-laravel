<aside class="main-sidebar">
    <section class="sidebar">


        @php

            $arLink = [
                [
                    'icon' => 'fas fa-tv',
                    'title' => __('message.home'),
                    'href' => 'admin',
                ],
              
                [
                    'icon' => 'fa fa-th',
                    'title' => __('message.system'),
                    'child' => [
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.configuration'),
                            'href' => 'admin/settings',
                            'permission' => 'SettingController',
                        ],
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.roles'),
                            'href' => 'admin/roles',
                            'permission' => 'RolesController@index',
                        ],
                    ],
                ],
                  [

                    'icon' => 'fad fa-newspaper' ,
                    'title' => __('message.news'),
                    'child' => [
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('Danh mục'),
                            'href' => 'admin/categories',
                            'permission' => 'CategoryController@index',
                        ],
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('Tin tức'),
                            'href' => 'admin/news',
                            'permission' => 'NewsController@index',
                        ]
                    ],
                ],
                 [
                        'icon' => 'fad fa-suitcase',
                        'title' => __('message.utilities'),
                        'child' => [
                             [
                                'icon' => 'dot fa fa-circle',
                                'title' => __('Tỉnh'),
                                'href' => 'admin/provinces',
                                'permission' => 'ProvinceController@index',
                            ],
                             [
                                'icon' => 'dot fa fa-circle',
                                'title' => __('Huyện'),
                                'href' => 'admin/districts',
                                'permission' => 'DistrictController@index',
                            ],
                             [
                                'icon' => 'dot fa fa-circle',
                                'title' => __('Xã'),
                                'href' => 'admin/wards',
                                'permission' => 'WardController@index',
                            ]

                        ],
                    ],

            ];
        @endphp
        {{ Menu::sidebar(Auth::user(), $arLink) }}
    </section>
</aside>
