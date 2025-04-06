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
            class="bg-gray-100 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 py-3"
    >
        <div class="flex flex-wrap">
            <!-- Left Sidebar -->
        @include('theme::front-end.layouts.sidebar')
        <!-- Right Content -->
            <div class="w-full sm:w-3/4">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-6 flex-wrap">
                        <h2 class="text-xl font-medium w-full sm:w-auto">
                            Order information
                        </h2>
                        <form method="GET" action="{{ url('my-order') }}">

                            <div
                                    class="flex flex-wrap w-full sm:w-auto justify-between sm:justify-start mt-4 sm:mt-0 items-center"
                            >
                                <div class="relative w-full sm:w-auto">
                                    <select name="payment_status"
                                            class="border border-gray-300 rounded-lg px-4 py-2 pr-10 appearance-none w-full sm:w-40"
                                    >
                                        @foreach(\App\Models\Order::$STATUS as $keyS => $valueS)
                                            <option value="{{ $keyS }}">{{ $valueS }}</option>
                                        @endforeach
                                    </select>
                                    <i
                                            class="fas fa-chevron-down absolute right-3 top-3 text-gray-500"
                                    ></i>
                                </div>

                                <div class="relative w-full sm:w-auto">
                                    <input id="from_date"
                                           name="from"
                                            type="text"
                                            placeholder="From date"
                                            class="border border-gray-300 rounded-lg px-4 py-2 w-full sm:w-60"
                                    />
                                    <i
                                            class="far fa-calendar absolute right-3 top-3 text-gray-500"
                                    ></i>
                                    <input id="to_date"
                                           name="to"
                                           type="text"
                                           placeholder="To date"
                                           class="border border-gray-300 rounded-lg px-4 py-2 w-full sm:w-60"
                                    />
                                    <i
                                            class="far fa-calendar absolute right-3 top-3 text-gray-500"
                                    ></i>
                                </div>

                                <button type="submit"
                                        class="bg-primary text-white px-6 py-2 rounded-lg w-full sm:w-auto sm:mt-0"
                                >
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="flex border-b space-x-6 mb-4 overflow-x-auto">
                        <div
                                class="px-4 py-2 border-b-2 border-transparent text-gray-500"
                        >
                            All
                        </div>
                        <div
                                class="px-4 py-2 border-b-2 border-transparent text-gray-500 flex items-center"
                        >
                            Waiting for shipping
                            <span
                                    class="ml-2 bg-orange-100 text-sencondary px-2 py-0.5 rounded-full text-xs"
                            >3</span
                            >
                        </div>
                        <div
                                class="px-4 py-2 border-b-2 border-transparent text-gray-500"
                        >
                            Shipped
                        </div>
                        <div
                                class="px-4 py-2 border-b-2 border-transparent text-gray-500 flex items-center"
                        >
                            Need to evaluate
                            <span
                                    class="ml-2 bg-orange-100 text-sencondary px-2 py-0.5 rounded-full text-xs"
                            >2</span
                            >
                        </div>
                        <div class="px-4 py-2 font-medium">Order history</div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                            <tr class="text-gray-500 text-sm">
                                <th class="text-left py-3">Order code</th>
                                <th class="text-left py-3">Name food</th>
                                <th class="text-left py-3">Date + time</th>
                                <th class="text-left py-3">Amount</th>
                                <th class="text-left py-3">Address</th>
                                <th class="text-left py-3">Delivery man</th>
                                <th class="text-left py-3">Order Status</th>
                                <th class="text-left py-3">Other</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Sample rows -->
                            @forelse($orders as $item)
                                <tr class="border-t">
                                    <td class="py-4 text-secondary">{{ $item->code }}</td>
                                    <td class="py-4">
                                        @if(count($item->orderItems) > 0)
                                            @foreach($item->orderItems as $itemO)
                                                {{ $itemO->product['name'] ?? '' }} <br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('M j, Y') }}
                                        <br>
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('g:i a') }}</td>
                                    <td class="py-4">${{ number_format($item->total_price, 2) }}</td>
                                    <td class="py-4">
                                        {{ $item->address }}
                                    </td>
                                    <td class="py-4">{{ optional($item->driver)->name }}</td>
                                    @if($item->payment_status == 'completed')
                                        <td class="py-4 text-primary">Completed</td>
                                    @elseif($item->payment_status == 'cancel')
                                        <td class="py-4 text-red-600">Cancel</td>
                                    @elseif($item->payment_status == 'progress')
                                        <td class="py-4 text-custom-warning">Inprocess</td>
                                    @else
                                        <td class="py-4 text-secondary">Pending</td>
                                    @endif
                                    <td class="py-4">
                                        <button
                                                class="bg-secondary text-white px-4 py-1 rounded-md"
                                        >
                                            Review
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ __('No data') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-center mt-6 space-x-2">
                        <!-- Nút phân trang trước -->
                        @if ($orders->onFirstPage())
                            <button class="pagination-item cursor-not-allowed" disabled>
                                <i class="fas fa-chevron-left"></i>
                            </button>
                        @else
                            <a href="{{ $orders->appends(['payment_status' => $status, 'from' => $from, 'to' => $to])->previousPageUrl() }}"
                               class="pagination-item">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                    <!-- Các trang -->
                        @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                            <a href="{{ $orders->appends(['payment_status' => $status, 'from' => $from, 'to' => $to])->url($page) }}"
                               class="pagination-item {{ $orders->currentPage() == $page ? 'bg-gray-200' : '' }}">
                                {{ $page }}
                            </a>
                        @endforeach

                    <!-- Nút phân trang sau -->
                        @if ($orders->hasMorePages())
                            <a href="{{ $orders->appends(['payment_status' => $status, 'from' => $from, 'to' => $to])->nextPageUrl() }}"
                               class="pagination-item">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <button class="pagination-item cursor-not-allowed" disabled>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <link href="{{ url('plugins/flatpickr.min.css') }}" rel="stylesheet">
    <script src="{{ url('plugins/flatpickr.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#from_date", {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    document.querySelector("#to_date").focus();
                }
            });

            flatpickr("#to_date", {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                minDate: document.querySelector("#from_date").value || "today",
            });
        });
    </script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            const avatarContainer = document.querySelector(".w-32.h-32");
            const cameraButton = document.querySelector(".fa-camera").parentNode;

            const fileInput = document.createElement("input");
            fileInput.type = "file";
            fileInput.accept = "image/*";
            fileInput.style.display = "none";
            document.body.appendChild(fileInput);

            cameraButton.addEventListener("click", function () {
                fileInput.click();
            });

            fileInput.addEventListener("change", function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        avatarContainer.innerHTML = "";
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.className = "w-full h-full object-cover rounded-full";
                        avatarContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
