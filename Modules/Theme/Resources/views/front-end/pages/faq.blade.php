@extends('theme::front-end.master')

@section('content')
    <div class="relative py-12 mb-8 overflow-hidden">
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
                <form action="#">
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
    <section class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
        <h2 class="text-2xl font-medium mb-6">Frequently Asked Questions</h2>
        <!-- FAQ Item 1 -->
        <div class="mb-4">
            <button
                    class="w-full text-left px-6 py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition"
                    onclick="toggleFaq(this)"
            >
                <span class="font-medium text-gray-800">Dine Out deals</span>
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
                    class="hidden px-6 py-4 bg-white border border-[#74CA45] rounded-lg mt-2"
            >
                <h3 class="font-medium text-gray-800 mb-3">How to order?</h3>
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
                    class="w-full text-left px-6 py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition"
                    onclick="toggleFaq(this)"
            >
                <span class="font-medium text-gray-800">Policy and warranty?</span>
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
                    class="w-full text-left px-6 py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition"
                    onclick="toggleFaq(this)"
            >
          <span class="font-medium text-gray-800"
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
                    class="hidden px-6 py-4 bg-white border border-[#74CA45] rounded-lg mt-2"
            >
                <p class="text-gray-700">
                    Purchase policy and wholesale information would appear here.
                </p>
            </div>
        </div>

        <!-- FAQ Item 5 -->
        <div class="mb-4">
            <button
                    class="w-full text-left px-6 py-4 bg-gray-100 hover:bg-gray-200 rounded-lg flex justify-between items-center transition"
                    onclick="toggleFaq(this)"
            >
          <span class="font-medium text-gray-800"
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
                    class="hidden px-6 py-4 bg-white border border-[#74CA45] rounded-lg mt-2"
            >
                <p class="text-gray-700">
                    Additional purchase policy and wholesale information would appear
                    here.
                </p>
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