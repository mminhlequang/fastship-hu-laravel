@extends('theme::front-end.master')
@section('style')
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .input-field:focus {
            border-color: #74ca45;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .password-field {
            position: relative;
        }

        .eye-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .avatar-container {
            padding-bottom: 20px;
        }

        .avatar-inner {
            width: 120px;
            height: 120px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin: 0 auto;
        }

        .initials {
            font-size: 48px;
            color: #7ac142;
            font-weight: bold;
        }

        .camera-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #f5f5f5;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #666;
        }

        .menu-item.active {
            border-radius: 12px;
            background-color: #74ca45;
            color: white;
        }

        .menu-item i {
            width: 24px;
            margin-right: 10px;
        }

        .pagination-item {
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .logo {
            color: #7ac142;
            font-weight: bold;
            font-size: 24px;
        }
    </style>
@endsection
@section('content')
    <section
            class="bg-gray-100 responsive-px py-3"
    >
        <div class="flex flex-wrap">
            <!-- Left Sidebar -->
        @include('theme::front-end.layouts.sidebar')
        <!-- Right Content -->
            <div class="w-full sm:w-3/4">
                <div class="bg-white rounded-lg shadow p-6">
                    <!-- Tab Navigation -->
                    <div class="flex flex-col">
                        <h2 class="text-xl font-medium w-full sm:w-auto">Vouchers</h2>
                        <br>
                        <div class="mt-4 p-3 rounded-xl border border-dashed border-[#CEC6C5]">
                            <div id="voucher-list" class="voucher-list">
                                @forelse($vouchers as $itemV)
                                    <div class="voucher-item flex items-center justify-between border-b rounded-lg p-2">
                                        <div class="flex flex-col items-start lg:flex-row lg:items-center gap-3">
                                            <div class="flex items-center gap-2">
                                                <img data-src="{{ url('assets/icons/cart/pr2.png') }}"
                                                     alt="Voucher Image"
                                                     id="voucher-image-1" class="lazyload">
                                                <div class="flex flex-col">
                                                    <span class="text-base lg:text-xl text-[#120F0F]">
                                                       {{ $itemV->name }}
                                                        <strong class="text-[#F17228]">{{ number_format($itemV->value) }} Ft off</strong>
                                                    </span>
                                                    <span class="text-sm text-[#7D7575]">{{ $itemV->description }}</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <hr>
                                @empty
                                    <img data-src="{{ url('images/no-data.webp') }}" class="lazyload">
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

