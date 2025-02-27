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
                            'title' => __('message.account'),
                            'href' => 'admin/users',
                            'permission' => 'UsersController@index',
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

                    'icon' => 'far fa-bell' ,
                    'title' => __('message.notifications'),
                    'child' => [
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.notifications'),
                            'href' => 'admin/notifications',
                            'permission' => 'NotificationController@index',
                        ]
                    ],
                ],
                [

                    'icon' => 'fad fa-newspaper' ,
                    'title' => __('message.news'),
                    'child' => [
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.news'),
                            'href' => 'admin/news',
                            'permission' => 'NewsController@index',
                        ]
                    ],
                ],
                [
                    'icon' => 'fab fa-product-hunt',
                    'title' => __('message.products'),
                    'child' => [
                         [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.categories'),
                            'href' => 'admin/categories',
                            'permission' => 'CategoryController@index',
                        ],
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.product'),
                            'href' => 'admin/products',
                             'permission' => 'ProductController@index',
                        ]

                    ],
                ],
                  [

                    'icon' => 'fas fa-popcorn',
                    'title' => __('toppings.title'),
                    'child' => [
                         [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('groups.title'),
                            'href' => 'admin/groups',
                             'permission' => 'GroupController@index',
                        ],
                         [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('toppings.title'),
                            'href' => 'admin/toppings',
                             'permission' => 'ToppingController@index',
                        ],
                    ],
                ],
                    [
                    'icon' => 'far fa-user-tag' ,
                    'title' => __('message.customers'),
                    'child' => [
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.customers'),
                            'href' => 'admin/customers',
                            'permission' => 'CustomerController@index',
                        ]
                    ],
                ],
                   [
                    'icon' => 'fas fa-user-tie' ,
                    'title' => __('message.drivers'),
                    'child' => [
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.drivers'),
                            'href' => 'admin/drivers',
                            'permission' => 'DriverController@index',
                        ]
                    ],
                ],
                   [
                    'icon' => 'fas fa-user-plus' ,
                    'title' => __('message.partners'),
                    'child' => [
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.partners'),
                            'href' => 'admin/partners',
                            'permission' => 'PartnerController@index',
                        ]
                    ],
                ],
                 [
                    'icon' => 'fal fa-cart-plus',
                    'title' => __('message.bookings'),
                    'child' => [
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.bookings'),
                            'href' => 'admin/bookings',
                            'permission' => 'BookingController@index',
                        ],
                          [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.discounts'),
                            'href' => 'admin/discounts',
                            'permission' => 'DiscountController@index',
                        ],
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.address_delivery'),
                            'href' => 'admin/address_delivery',
                            'permission' => 'AddressDeliveryController@index',
                        ],

                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.approves'),
                            'href' => 'admin/approves',
                            'permission' => 'ApproveController@index',
                        ],

                    ],
                ],
                   [
                    'icon' => 'fas fa-money-bill-alt' ,
                    'title' => __('transactions.name'),
                    'child' => [
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('transactions.name'),
                            'href' => 'admin/transactions',
                            'permission' => 'TransactionController@index',
                        ]
                    ],
                ],
                 [
                    'icon' => 'fas fa-store' ,
                    'title' => __('message.stores'),
                    'child' => [
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.stores'),
                            'href' => 'admin/stores',
                            'permission' => 'StoreController@index',
                        ]
                    ],
                ],
                [

                    'icon' => 'fas fa-scroll-old' ,
                    'title' => __('message.banner'),
                    'child' => [
                        [
                            'icon' => 'dot fa fa-circle',
                            'title' => __('message.banner'),
                            'href' => 'admin/banners',
                            'permission' => 'BannerController@index',
                        ]
                    ],
                ],
                 [
                        'icon' => 'fad fa-suitcase',
                        'title' => __('message.utilities'),
                        'child' => [
                             [
                                'icon' => 'dot fa fa-circle',
                                'title' => __('message.province'),
                                'href' => 'admin/provinces',
                                'permission' => 'ProvinceController@index',
                            ],
                             [
                                'icon' => 'dot fa fa-circle',
                                'title' => __('message.district'),
                                'href' => 'admin/districts',
                                'permission' => 'DistrictController@index',
                            ],
                             [
                                'icon' => 'dot fa fa-circle',
                                'title' => __('message.ward'),
                                'href' => 'admin/wards',
                                'permission' => 'WardController@index',
                            ],
                             [
                                'icon' => 'dot fa fa-circle',
                                'title' => __('message.contacts'),
                                'href' => 'admin/contacts',
                                'permission' => 'ContactController@index'
                            ],

                        ],
                    ],

            ];
        @endphp
        {{ Menu::sidebar(Auth::user(), $arLink) }}
    </section>
</aside>
