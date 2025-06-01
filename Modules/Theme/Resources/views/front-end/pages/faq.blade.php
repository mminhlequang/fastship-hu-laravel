@extends('theme::front-end.master')
@section('title')
    <title>{{ __('Fast ship Hu Faq') }}</title>
    <meta name="description"
          content="{{ __('Fast ship Hu Faq') }}"/>
    <meta name="keywords" content="{{ __('Fast ship Hu Faq') }}"/>
@endsection
@section('content')
    <div class="relative py-12 overflow-hidden">
        <!-- Background image using <img> -->
        <img
                src="{{ url('assets/images/banner_ask.svg') }}"
                alt="Background Image"
                class="absolute inset-0 w-full h-full object-cover"
        />
        <!-- Content inside -->
        <div class="relative z-10 text-center px-4 py-8">
            <h1 class="text-3xl font-medium mb-6 text-white">
                How can we help you?
            </h1>

            <div class="max-w-md mx-auto">
                <form action="{{ url('faq') }}" method="GET">
                    <div
                            class="flex items-center gap-1.5 py-2 pl-4 pr-2 rounded-full bg-white shadow"
                    >
                        <!-- Icon -->
                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            />
                        </svg>
                        <!-- Input field - added flex-grow to expand properly -->
                        <input
                                type="text"
                                class="flex-grow focus:outline-none px-2 py-2"
                                placeholder="Find questions"
                        />
                        <!-- Button - fixed styling to ensure visibility -->
                        <button
                                type="submit"
                                class="rounded-full py-2.5 px-8 bg-primary text-white hover:bg-primary-700 flex-shrink-0"
                        >
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <section class="responsive-px bg-[#F9F8F6] px-[55px] pb-[37px]">
        <div class ="bg-[#FFFFFF] px-[100px] pt-[43px] pb-[53px]">

            <h2 class="text-2xl leading-[1.3] tracking-[0.24px] text-[#14142A] mb-6">Frequently Asked Questions</h2>
            <!-- FAQ Item 1 -->
            <div class="mb-4">
                <button
                        class="w-full text-left px-[22px] py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition"
                        onclick="toggleFaq(this)"
                >
                    <span class="text-[#3C3836] text-[18px] leading-[1.5] tracking-[0.18px]">Dine Out deals</span>
                    <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="transform transition-transform h-5 w-5 text-gray-500"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                    >
                        <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                        />
                    </svg>
                </button>
                <div
                        class="hidden px-[22px] py-4 bg-white border border-[#74CA45] rounded-2xl mt-2"
                >
                    <h3 class="text-[#120F0F] font-medium text-[18px] leading-[1.5] tracking-[0.18px] mb-2">How to order?</h3>
                    <ul class="list-disc pl-5 space-y-2 text-gray-700">
                        <li>Order online or in store.</li>
                        <li>Pay directly at the delivery location.</li>
                        <li>
                            After placing the order, Heba Kitchen's delivery staff will notify
                            the customer about the delivery. Customers please pay by cash or
                            gift certificate (if any) at the delivery location, the delivery
                            person will provide a valid sales invoice after the customer
                            checks the order.
                        </li>
                    </ul>
                </div>
            </div>
    
            <!-- FAQ Item 3 -->
            <div class="mb-4">
                <button
                        class="w-full text-left px-[22px] py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition"
                        onclick="toggleFaq(this)"
                >
                    <span class="text-[#3C3836] text-[18px] leading-[1.5] tracking-[0.18px]">Policy and warranty?</span>
                    <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="transform transition-transform h-5 w-5 text-gray-500"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                    >
                        <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                        />
                    </svg>
                </button>
                <div
                        class="hidden px-6 py-4 bg-white border border border-[#74CA45] rounded-lg mt-2"
                >
                    <p class="text-gray-700">
                        Policy and warranty information would appear here.
                    </p>
                </div>
            </div>
    
            <!-- FAQ Item 4 -->
            <div class="mb-4">
                <button
                        class="w-full text-left px-[22px] py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition"
                        onclick="toggleFaq(this)"
                >
              <span class="text-[#3C3836] text-[18px] leading-[1.5] tracking-[0.18px]"
              >Purchase policy, wholesale?</span
              >
                    <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="transform transition-transform h-5 w-5 text-gray-500"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                    >
                        <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                        />
                    </svg>
                </button>
                <div
                        class="hidden px-[22px] py-4 bg-white border border-[#74CA45] rounded-2xl mt-2"
                >
                    <p class="text-gray-700">
                        Purchase policy and wholesale information would appear here.
                    </p>
                </div>
            </div>
    
            <!-- FAQ Item 5 -->
            <div class="mb-4">
                <button
                        class="w-full text-left px-[22px] py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition"
                        onclick="toggleFaq(this)"
                >
              <span class="text-[#3C3836] text-[18px] leading-[1.5] tracking-[0.18px]"
              >Purchase policy, wholesale?</span
              >
                    <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="transform transition-transform h-5 w-5 text-gray-500"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                    >
                        <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                        />
                    </svg>
                </button>
                <div
                        class="hidden px-[22px] py-4 bg-white border border-[#74CA45] rounded-2xl mt-2"
                >
                    <p class="text-gray-700">
                        Additional purchase policy and wholesale information would appear
                        here.
                    </p>
                </div>
            </div>
    
    
            <div class="mb-4">
                <button class="w-full text-left px-[22px] py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition" onclick="toggleFaq(this)">
                    <span class="text-[#3C3836] text-[18px] leading-[1.5] tracking-[0.18px]">Policy and warranty?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="transform transition-transform h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <div class="hidden px-[22px] py-4 bg-white border border-[#74CA45] rounded-2xl mt-2">
                    <p class="text-gray-700">Policy and warranty information would appear here.</p>
                </div>
            </div>
    
            <div class="mb-4">
                <button class="w-full text-left px-[22px] py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition" onclick="toggleFaq(this)">
                    <span class="text-[#3C3836] text-[18px] leading-[1.5] tracking-[0.18px]">What is your return policy?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="transform transition-transform h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <div class="hidden px-[22px] py-4 bg-white border border-[#74CA45] rounded-2xl mt-2">
                    <p class="text-gray-700">Our return policy allows you to return items within 30 days of purchase.</p>
                </div>
            </div>
    
            <div class="mb-4">
                <button class="w-full text-left px-[22px] py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition" onclick="toggleFaq(this)">
                    <span class="text-[#3C3836] text-[18px] leading-[1.5] tracking-[0.18px]">How do I contact customer support?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="transform transition-transform h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <div class="hidden px-[22px] py-4 bg-white border border-[#74CA45] rounded-2xl mt-2">
                    <p class="text-gray-700">You can contact our customer support via email or phone. Visit the contact page for more information.</p>
                </div>
            </div>
    
            <!-- Add more questions and answers as needed -->
            <!-- Câu 1 -->
            <div class="mb-4">
                <button class="w-full text-left px-[22px] py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition" onclick="toggleFaq(this)">
                    <span class="text-[#3C3836] text-[18px] leading-[1.5] tracking-[0.18px]">How do I track my order?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="transform transition-transform h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <div class="hidden px-[22px] py-4 bg-white border border-[#74CA45] rounded-2xl mt-2">
                    <p class="text-gray-700">You can track your order using the tracking link sent to your email after shipping.</p>
                </div>
            </div>
    
            <!-- Câu 2 -->
            <div class="mb-4">
                <button class="w-full text-left px-[22px] py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition" onclick="toggleFaq(this)">
                    <span class="text-[#3C3836] text-[18px] leading-[1.5] tracking-[0.18px]">Do you offer international shipping?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="transform transition-transform h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <div class="hidden px-[22px] py-4 bg-white border border-[#74CA45] rounded-2xl mt-2">
                    <p class="text-gray-700">Yes, we ship to over 50 countries worldwide. Shipping fees may vary by location.</p>
                </div>
            </div>
    
            <!-- Câu 3 -->
            <div class="mb-4">
                <button class="w-full text-left px-[22px] py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition" onclick="toggleFaq(this)">
                    <span class="text-[#3C3836] text-[18px] leading-[1.5] tracking-[0.18px]">How can I change my shipping address?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="transform transition-transform h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <div class="hidden px-[22px] py-4 bg-white border border-[#74CA45] rounded-2xl mt-2">
                    <p class="text-gray-700">To change your shipping address, contact our support team before your order ships.</p>
                </div>
            </div>
    
            <!-- Câu 4 -->
            <div class="mb-4">
                <button class="w-full text-left px-[22px] py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition" onclick="toggleFaq(this)">
                    <span class="text-[#3C3836] text-[18px] leading-[1.5] tracking-[0.18px]">Can I cancel or modify my order?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="transform transition-transform h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <div class="hidden px-[22px] py-4 bg-white border border-[#74CA45] rounded-2xl mt-2">
                    <p class="text-gray-700">Orders can only be modified or cancelled within 2 hours after placing them.</p>
                </div>
            </div>
    
            <!-- Câu 5 -->
            <div class="mb-4">
                <button class="w-full text-left px-[22px] py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition" onclick="toggleFaq(this)">
                    <span class="text-[#3C3836] text-[18px] leading-[1.5] tracking-[0.18px]">What payment methods do you accept?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="transform transition-transform h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <div class="hidden px-[22px] py-4 bg-white border border-[#74CA45] rounded-2xl mt-2">
                    <p class="text-gray-700">We accept Visa, MasterCard, PayPal, and bank transfers.</p>
                </div>
            </div>
    
            <!-- Câu 6 -->
            <div class="mb-4">
                <button class="w-full text-left px-[22px] py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition" onclick="toggleFaq(this)">
                    <span class="text-[#3C3836] text-[18px] leading-[1.5] tracking-[0.18px]">How do I reset my password?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="transform transition-transform h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <div class="hidden px-[22px] py-4 bg-white border border-[#74CA45] rounded-2xl mt-2">
                    <p class="text-gray-700">Click on "Forgot password" at login and follow the instructions sent to your email.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">
        function toggleFaq(element) {
            const icon = element.querySelector("svg");
            icon.classList.toggle("rotate-180");

            const content = element.nextElementSibling;
            content.classList.toggle("hidden");
        }
    </script>
@endsection