@extends('theme::front-end.master')
@section('title')
    <title>{{ __('Fast ship Hu Contact') }}</title>
    <meta name="description"
          content="{{ __('Fast ship Hu Contact') }}"/>
    <meta name="keywords" content="{{ __('Fast ship Hu Contact') }}"/>
@endsection
@section('style')
    <style>
        .input-focus-effect:focus {
            border-color: #74CA45;
            box-shadow: 0 0 0 1px #74CA45;
        }

        .input-container {
            position: relative;
            width: 100%;
        }

        .input-container input {
            background: #F9F8F6;
            border-radius: 16px;
            padding: 14px 16px 6px;
            width: 100%;
            font-size: 16px;
            border: 1px solid #ccc;
        }

        /* Label mặc định */
        .input-container label {
            position: absolute;
            left: 16px;
            top: 14px;
            transition: all 0.2s ease-out;
            pointer-events: none;
            color: #847D79;
            font-size: 16px;
            background-color: #F9F8F6;
            padding: 0 4px;
            z-index: 1; /* Đảm bảo label ở trên input */
        }

        /* Khi input focus hoặc có nội dung, label di chuyển lên */
        .input-container input:focus + label,
        .input-container input:not(:placeholder-shown) + label {
            top: 4px;
            font-size: 12px;
            color: #74CA45; /* Màu khi focus */
            z-index: 2; /* Đảm bảo label ở trên */
        }

        /* Thêm class cho label khi nhập */
        .floating-label {
            transition: top 0.2s ease, font-size 0.2s ease;
        }


        .input-container input{
            background: #F9F8F6;
            border-radius: 16px;
        }

        .input-container textarea{
            background: #F9F8F6;
            border-radius: 16px;
            font-size: 16px;
            padding-top: 30px;
        }


        .input-container input:focus + label,
        .input-container input:not(:placeholder-shown) + label,
        .input-container textarea:focus + label,
        .input-container textarea:not(:placeholder-shown) + label {
            font-size: 0.75rem;
            color: #74CA45;
        }

        .input-container label {
            position: absolute;
            left: 15px;
            top: 10px;
            transition: all 0.2s ease-out;
            pointer-events: none;
            color: #847D79;
            font-size: 16px;
        }
        label.label-info{
            color: #847D79;
            font-weight: 400;
            font-size: 14px;
        }
        .p-50{
            padding: 50px;
        }
    </style>
@endsection
@section('content')
    <div class="relative overflow-hidden">
        <!-- Background image using <img> -->
        <img data-src="{{ url('assets/images/banner_contact.svg') }}" alt="Background Image"
             class="absolute inset-0 w-full h-full object-cover lazyload"/>
        <!-- Content inside -->
        <div class="relative z-10 py-8 responsive-px">
            <h1 class="text-3xl font-medium mb-6 text-white max-w-5xl">
                Contact us
            </h1>
        </div>
    </div>

    <!-- FAQ Section -->
    <section class="px-4">
        <div class="flex flex-col md:flex-row max-w-5xl mx-auto bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Contact Information -->
            <div class="w-full md:w-1/3 bg-[#F4F4F4] p-50" >
                <h2 class="text-3xl font-medium mb-6 text-gray-800">
                    Contact<br> information
                </h2>

                <!-- Address -->
                <div class="mb-6">
                    <p class="text-sm text-primary font-medium mb-1">Address</p>
                    <p class="text-dark">
                        {{ $settings['company_address'] ?? 'San Francisco, CA​​661 Bush St & 20th Ave, Apt San Francisco,CA​​94109' }}
                    </p>
                </div>

                <!-- Phone -->
                <div class="mb-6">
                    <p class="text-sm text-primary font-medium mb-1">Phone</p>
                    <p class="text-dark">{{ $settings['company_phone'] ?? '+1 555 505 5050' }}</p>
                </div>

                <!-- Support -->
                <div class="mb-6">
                    <p class="text-sm text-primary font-medium mb-1">
                        Customer Service & Support
                    </p>
                    <p class="text-dark">{{ $settings['company_email'] ?? 'info@example.com' }}</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="w-full md:w-2/3 p-50">
                <h2 class="text-3xl font-medium mb-6 text-gray-800 px-6">Contact Form</h2>

                <form id="contactForm" class="px-6">
                @csrf
                <!-- Name and Email Row -->
                    <div class="flex flex-col gap-4 mb-4">
                        <label class="label-info" for="name">Name</label>
                        <div class="input-container w-full">
                            <input name="name"
                                   type="text"
                                   id="name"
                                   placeholder=" "
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md outline-none focus:border-primary transition-all duration-300"
                                   required
                            />
                            <label for="name">Name</label>
                        </div>
                        <label class="label-info" for="email">Email</label>
                        <div class="input-container w-full">
                            <input name="email"
                                   type="email"
                                   id="email"
                                   placeholder=" "
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md outline-none focus:border-primary transition-all duration-300"
                                   required
                            />
                            <label for="email">Email</label>
                        </div>
                    </div>

                    <!-- Phone and Subject Row -->
                    <div class="flex flex-col gap-4 mb-4">
                        <label class="label-info" for="phone">Phone</label>
                        <div class="input-container w-full">
                            <input name="phone"
                                   type="tel"
                                   id="phone"
                                   placeholder=" "
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md outline-none focus:border-primary transition-all duration-300"
                                   required
                            />
                            <label for="phone">Phone</label>
                        </div>
                        <label class="label-info" for="subject">Subject</label>
                        <div class="input-container w-full">
                            <input name="subject"
                                   type="text"
                                   id="subject"
                                   placeholder=" "
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md outline-none focus:border-primary transition-all duration-300"
                            />
                            <label for="subject">Subject</label>
                        </div>
                    </div>
                    <label class="label-info" for="message">Message</label>
                    <!-- Message -->
                    <div class="input-container w-full mb-6 mt-2">
                          <textarea
                                  name="message"
                                  id="message"
                                  rows="4"
                                  placeholder=" "
                                  class="w-full px-4 py-3 border border-gray-300 rounded-md outline-none focus:border-primary transition-all duration-300"
                          ></textarea>
                        <label for="message">Message</label>
                    </div>

                    <!-- Submit Button -->
                    <button
                            type="submit"
                            class="w-full bg-primary hover:bg-primary-700 text-white font-medium py-3 px-4 rounded-full transition duration-300 mb-4"
                    >
                        Send
                    </button>
                </form>

            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#contactForm').submit(function (e) {
                e.preventDefault();
                $('.loading').addClass('loader');
                $.ajax({
                    url: '{{ url('ajaxFE/postContact') }}',
                    method: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        const data = response;
                        if (data.status) {
                            $('#contactForm')[0].reset();
                            toastr.success(data.message);
                            $('.loading').removeClass('loader');
                        } else {
                            let err = data.errors;
                            let mess = err.join("<br/>");
                            toastr.error(mess);
                            $('.loading').removeClass('loader');
                        }
                    }
                });
            });
        });
    </script>
@endsection