@extends('theme::front-end.master')
@section('style')
    <style>
        .input-focus-effect:focus {
            border-color: #65b741;
            box-shadow: 0 0 0 1px #65b741;
        }

        .input-container {
            position: relative;
        }

        .input-container input:focus + label,
        .input-container input:not(:placeholder-shown) + label,
        .input-container textarea:focus + label,
        .input-container textarea:not(:placeholder-shown) + label {
            transform: translateY(-24px);
            font-size: 0.75rem;
            color: #65b741;
        }

        .input-container label {
            position: absolute;
            left: 16px;
            top: 12px;
            transition: all 0.2s ease-out;
            pointer-events: none;
            color: #9ca3af;
        }
    </style>
@endsection
@section('content')
    <div class="relative py-12 mb-4 overflow-hidden">
        <!-- Background image using <img> -->
        <img
                data-src="{{ url('assets/images/banner_contact.svg') }}"
                alt="Background Image"
                class="absolute inset-0 w-full h-full object-cover lazyload"
        />
        <!-- Content inside -->
        <div class="relative z-10 py-8 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
            <h1
                    class="text-3xl font-medium mb-6 text-white max-w-5xl"
            >
                Contact us
            </h1>
        </div>
    </div>

    <!-- FAQ Section -->
    <section class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80">
        <div
                class="flex flex-col md:flex-row max-w-5xl mx-auto my-10 bg-white rounded-lg shadow-sm overflow-hidden"
        >
            <!-- Contact Information -->
            <div class="w-full md:w-1/3 bg-[#F4F4F4] p-2">
                <h2 class="text-xl font-medium mb-6 text-gray-800">
                    Contact information
                </h2>

                <!-- Address -->
                <div class="mb-6">
                    <p class="text-sm text-green-600 font-medium mb-1">Address</p>
                    <p class="text-gray-700">
                        San Francisco, CA​​661 Bush St & 20th Ave, Apt San Francisco,
                        CA​​94109
                    </p>
                </div>

                <!-- Phone -->
                <div class="mb-6">
                    <p class="text-sm text-green-600 font-medium mb-1">Phone</p>
                    <p class="text-gray-700">+1 555 505 5050</p>
                </div>

                <!-- Support -->
                <div class="mb-6">
                    <p class="text-sm text-green-600 font-medium mb-1">
                        Customer Service & Support
                    </p>
                    <p class="text-gray-700">info@example.com</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="w-full md:w-2/3 p-2">
                <h2 class="text-xl font-medium mb-6 text-gray-800">Contact Form</h2>

                <form>
                    <!-- Name and Email Row -->
                    <div class="flex flex-col gap-4 mb-4">
                        <div class="input-container w-full">
                            <input
                                    type="text"
                                    id="name"
                                    placeholder=" "
                                    class="w-full px-4 py-3 border border-gray-300 rounded-md outline-none focus:border-blue-500 transition-all duration-300"
                            />
                            <label for="name">Name</label>
                        </div>
                        <div class="input-container w-full">
                            <input
                                    type="email"
                                    id="email"
                                    placeholder=" "
                                    class="w-full px-4 py-3 border border-gray-300 rounded-md outline-none focus:border-blue-500 transition-all duration-300"
                            />
                            <label for="email">Email</label>
                        </div>
                    </div>

                    <!-- Phone and Subject Row -->
                    <div class="flex flex-col gap-4 mb-4">
                        <div class="input-container w-full">
                            <input
                                    type="tel"
                                    id="phone"
                                    placeholder=" "
                                    class="w-full px-4 py-3 border border-gray-300 rounded-md outline-none focus:border-blue-500 transition-all duration-300"
                            />
                            <label for="phone">Phone</label>
                        </div>
                        <div class="input-container w-full">
                            <input
                                    type="text"
                                    id="subject"
                                    placeholder=" "
                                    class="w-full px-4 py-3 border border-gray-300 rounded-md outline-none focus:border-blue-500 transition-all duration-300"
                            />
                            <label for="subject">Subject</label>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="input-container w-full mb-6">
              <textarea
                      id="message"
                      rows="4"
                      placeholder=" "
                      class="w-full px-4 py-3 border border-gray-300 rounded-md outline-none focus:border-blue-500 transition-all duration-300"
              ></textarea>
                        <label for="message">Message</label>
                    </div>

                    <!-- Submit Button -->
                    <button
                            type="submit"
                            class="w-full bg-primary hover:bg-primary-700 text-white font-medium py-3 px-4 rounded-full transition duration-300"
                    >
                        Send
                    </button>
                </form>

            </div>
        </div>
    </section>
@endsection