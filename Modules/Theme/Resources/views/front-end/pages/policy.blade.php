@extends('theme::front-end.master')
@section('title')
    <title>{{ __('Fast ship Hu Policy') }}</title>
    <meta name="description"
          content="{{ __('Fast ship Hu Policy') }}"/>
    <meta name="keywords" content="{{ __('Fast ship Hu Policy') }}"/>
@endsection
@section('content')
    <!-- Hero Section with Search -->
    <div class="relative py-12 mb-4 overflow-hidden">
        <!-- Background image using <img> -->
        <img src="{{ url('assets/images/banner_ask.svg') }}" alt="Background Image"
             class="absolute inset-0 w-full h-full object-cover"/>
        <!-- Content inside -->
        <div class="relative z-10 py-8">
            <h1 class="text-3xl font-medium mb-6 text-white responsive-px">
                Policy
            </h1>
        </div>
    </div>

    <!-- FAQ Section -->
    <section class="responsive-px">
        <!-- Menu categories -->
        <div class="tab-buttons border-b">
            <div class="flex flex-wrap justify-start overflow-x-auto no-scrollbar">
                <button class="tab-btn px-4 py-3 text-black border-b-2 font-medium border-black" data-tab="terms-of-service">
                    Terms of Service
                </button>
                <button class="tab-btn px-4 py-3 text-gray-500 hover:text-secondary" data-tab="privacy-policy">
                    Privacy Policy
                </button>
                <button class="tab-btn px-4 py-3 text-gray-500 hover:text-secondary" data-tab="payment-policy">
                    Payment policy
                </button>
                <button class="tab-btn px-4 py-3 text-gray-500 hover:text-secondary" data-tab="refund-cancellation">
                    Refund & Cancellation
                </button>
                <button class="tab-btn px-4 py-3 text-gray-500 hover:text-secondary" data-tab="cookies-policy">
                    Cookies Policy
                </button>
            </div>
        </div>
    </section>
    <section class="responsive-px">
        <!-- Tab Content -->
        <div class="tab-content my-6">
            <div class="tab-panel" id="terms-of-service">
                {!! $settings['term_service'] ?? '' !!} }
            </div>

            <div class="tab-panel" id="privacy-policy">
                {!! $settings['privacy_policy'] ?? '' !!} }
            </div>

            <div class="tab-panel hidden" id="payment-policy">
                {!! $settings['payment_policy'] ?? '' !!} }
            </div>

            <div class="tab-panel hidden" id="refund-cancellation">
                {!! $settings['refund_policy'] ?? '' !!} }
            </div>

            <div class="tab-panel hidden" id="cookies-policy">
                {!! $settings['cookie_policy'] ?? '' !!}
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabPanels = document.querySelectorAll('.tab-panel');

        function activateTab(tabId) {
            tabButtons.forEach(btn => {
                btn.classList.remove('text-black', 'border-black', 'border-b-2');
                btn.classList.add('text-gray-500');
            });

            const activeBtn = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
            if (activeBtn) {
                activeBtn.classList.add('text-black', 'border-black', 'border-b-2');
                activeBtn.classList.remove('text-gray-500');
            }

            tabPanels.forEach(panel => {
                panel.classList.add('hidden');
            });

            const activePanel = document.getElementById(tabId);
            if (activePanel) {
                activePanel.classList.remove('hidden');
            }

            history.replaceState(null, '', `#${tabId}`);
        }

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                activateTab(tabId);
            });
        });

        window.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash.substring(1);
            const validTab = document.getElementById(hash);
            if (validTab) {
                activateTab(hash);
            } else {
                activateTab('terms-of-service');
            }
        });

        window.addEventListener('hashchange', () => {
            const hash = window.location.hash.substring(1);
            if (document.getElementById(hash)) {
                activateTab(hash);
            }
        });

    </script>
@endsection
