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
    <section class="px-4 bg-[#F9F8F6] pb-[60px]">
        <div class="flex flex-col md:flex-row max-w-[971px] gap-[60px] p-[30px] mx-auto bg-white overflow-hidden">
            <!-- Contact Information -->
            <div class="bg-[#F4F4F4] p-[30px] flex flex-col justify-between max-w-[407px] w-full" >
                <h2 class="text-[32px] leading-[1.4] tracking-[0.64px] font-medium mb-6 text-[#151515]">
                    Contact information
                </h2>
                <div>
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
            </div>

            <!-- Contact Form -->
            <div class="w-full max-w-[444px]">
                <h2 class="text-[24px] leading-[1.2] tracking-[0.48px] font-medium mb-6 text-[#120F0F]">Contact Form</h2>

                <form id="contactForm">
                @csrf
                <!-- Name and Email Row -->
                    <div class="flex flex-col">
                        <label class="label-info !text-[14px] leading-[1.2] tracking-[0.28px] mb-2" for="name">Name</label>
                        <div class="input-container w-full mb-4">
                            <input name="name"
                                   type="text"
                                   id="name"
                                   placeholder=" "
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md outline-none focus:border-primary transition-all duration-300"
                                   required
                            />
                            <label for="name">Name</label>
                        </div>
                        <label class="label-info !text-[14px] leading-[1.2] tracking-[0.28px] mb-2" for="email">Email</label>
                        <div class="input-container w-full mb-4">
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
                    <div class="flex flex-col">
                        <label class="label-info !text-[14px] leading-[1.2] tracking-[0.28px] mb-2" for="phone">Phone</label>
                        <div class="input-container w-full mb-4">
                            <input name="phone"
                                   type="tel"
                                   id="phone"
                                   placeholder=" "
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md outline-none focus:border-primary transition-all duration-300"
                                   required
                            />
                            <label for="phone">Phone</label>
                        </div>
                        <label class="label-info !text-[14px] leading-[1.2] tracking-[0.28px] mb-2" for="subject">Subject</label>
                        <div class="input-container w-full mb-4">
                            <input name="subject"
                                   type="text"
                                   id="subject"
                                   placeholder=" "
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md outline-none focus:border-primary transition-all duration-300"
                            />
                            <label for="subject">Subject</label>
                        </div>
                    </div>
                    <label class="label-info !text-[14px] leading-[1.2] tracking-[0.28px] mb-2" for="message">Message</label>
                    <!-- Message -->
                    <div class="input-container w-full mb-6 mt-2">
                          <textarea
                                  name="message"
                                  id="message"
                                  rows="4"
                                  placeholder=" "
                                  class="w-full px-4 py-3 max-h-[76px] border border-gray-300 rounded-md outline-none focus:border-primary transition-all duration-300"
                          ></textarea>
                        <label for="message">Message</label>
                    </div>

                    <!-- Submit Button -->
                    <button
                            type="submit"
                            class="w-full bg-primary hover:bg-primary-700 text-white font-medium text-[18px] leading-[1.6] tracking-[0.36px] py-[10.5px] px-[24px] rounded-full transition duration-300"
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