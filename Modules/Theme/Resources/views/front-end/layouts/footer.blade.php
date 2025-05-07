<footer id="footer">
    <div class="py-10 md:py-16 bg-[#f4f4f4] responsive-px">
        <div>
            <div class="grid grid-cols-1 md:gap-8 md:grid-cols-2 xl:grid-cols-4">
                <div class="flex flex-col gap-2.5 md:gap-6 mt-4 md:mt-0">
                    <img data-src="{{ url('assets/images/logo_main.svg') }}" class="w-[214px] lazyload"
                         alt="Fast Ship Hu"/>
                    <p>
                        {{ __('theme::web.footer_company') }} {{ $settings['company_name'] ?? '490039-445,' }}
                        <br/>{{ __('theme::web.footer_register1') }}<br/>
                        {{ __('theme::web.footer_register2') }}
                    </p>
                </div>
                <div class="flex flex-col gap-2.5 md:gap-6 mt-4 md:mt-0">
                    <h3 class="text-lg font-medium">{{ __('theme::web.footer_legal') }}</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ url('legal-policies#terms-of-service') }}" class="underline transition-all hover:text-primary">Terms of Service</a>
                        </li>
                        <li>
                            <a href="{{ url('legal-policies#privacy-policy') }}"
                               class="underline transition-all hover:text-primary">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="{{ url('legal-policies#payment-policy') }}"
                               class="underline transition-all hover:text-primary">Payment Policy</a>
                        </li>
                        <li>
                            <a href="{{ url('legal-policies#refund-cancellation') }}"
                               class="underline transition-all hover:text-primary">Refund & Cancellation</a>
                        </li>
                        <li>
                            <a href="{{ url('legal-policies#cookies-policy') }}" class="underline transition-all hover:text-primary">Cookies Policy</a>
                        </li>

                    </ul>
                </div>
                <div class="flex flex-col gap-2.5 md:gap-6 mt-4 md:mt-0">
                    <h3 class="text-lg font-medium">{{ __('theme::web.footer_import') }}</h3>
                    <ul class="space-y-2">

                        <li>
                            <a href="{{ url('stores') }}" class="underline transition-all hover:text-primary">Add your
                                restaurant</a>
                        </li>
                        <li>
                            <a href="{{ url('become-our-driver') }}"
                               class="underline transition-all hover:text-primary">Sign up to deliver</a>
                        </li>
                        <li>
                            <a href="{{ $settings['follow_ios'] }}"
                               class="underline transition-all hover:text-primary">Download App</a>
                        </li>
                        <li>
                            <a href="{{ url('faq') }}"
                               class="underline transition-all hover:text-primary">Frequently Asked Questions</a>
                        </li>
                        <li>
                            <a href="{{ url('contact') }}"
                               class="underline transition-all hover:text-primary">Contact us</a>
                        </li>
                    </ul>
                </div>
                <div class="flex flex-col gap-2.5 md:gap-6 mt-4 md:mt-0">
                    <h3 class="text-lg font-medium">
                        {{ __('theme::web.footer_inbox') }}
                    </h3>
                    <div class="flex flex-col gap-2">
                    <form id="newsLetterForm" method="POST">
                        @csrf
                        <div class="flex items-center gap-1.5 py-1.5 pl-4 pr-1.5 rounded-full bg-white shadow">
                            <input name="email"
                                   type="email"
                                   class="flex-1 focus:outline-none"
                                   placeholder="youremail@gmail.com"
                                   autocomplete="off"
                                   required
                            />
                            <button type="submit"
                                    class="rounded-full py-2.5 px-4 bg-secondary text-white xl:text-xs 2xl:text-base hover:bg-secondary-700">
                                {{ __('theme::web.footer_subscribe') }}
                            </button>
                        </div>
                    </form>
                    <p class="my-2 text-sm text-center md:text-start">
                        {{ __('theme::web.footer_spam') }}
                        <a href="{{ url('legal-policies') }}"
                           class="underline text-secondary"> {{ __('theme::web.footer_spam_email') }}</a>
                    </p>
                    <div class="flex items-center gap-3 justify-center md:justify-start">
                        <span class="w-5 h-5 flex bg-primary/20 rounded-full">
                            <img data-src="{{ url('assets/icons/fb_icon.svg') }}" class="h-[10px] invert m-auto lazyload" alt="Fast Ship Hu"/>
                        </span>
                        <span class="w-5 h-5 flex bg-primary/20 rounded-full">
                            <img data-src="{{ url('assets/icons/twitter_icon.svg') }}" class="h-[10px] invert m-auto lazyload" alt="Fast Ship Hu"/>
                        </span>
                        <span class="w-5 h-5 flex bg-primary/20 rounded-full">
                            <img data-src="{{ url('assets/icons/youtube_icon.svg') }}" class="h-[10px] invert m-auto lazyload" alt="Fast Ship Hu"/>
                        </span>
                        <span class="w-5 h-5 flex bg-primary/20 rounded-full">
                            <img data-src="{{ url('assets/icons/pinterest_icon.svg') }}" class="h-[10px] invert m-auto lazyload" alt="Fast Ship Hu"/>
                        </span>
                        <span class="w-5 h-5 flex bg-primary/20 rounded-full">
                            <img data-src="{{ url('assets/icons/ins_icon.svg') }}" class="h-[10px] invert m-auto lazyload" alt="Fast Ship Hu"/>
                    </span>
                    </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
    <div class="copyright bg-dark py-4 text-white responsive-px">
        <div class="flex flex-wrap flex-col lg:flex-nowrap lg:flex-row items-start md:items-center justify-between">
            <p class="md:w-auto w-full mb-4 md:mb-0">
                @ Copyright 2025, All Rights Reserved.
            </p>
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-7 md:w-auto w-full">
                <a href="{{ url('legal-policies') }}" class="transition-all hover:text-primary"
                >Privacy Policy</a
                >
                <a href="{{ url('legal-policies') }}" class="transition-all hover:text-primary">Terms</a>
                <a href="{{ url('legal-policies') }}" class="transition-all hover:text-primary">Pricing</a>
                <a href="{{ url('contact') }}" class="transition-all hover:text-primary">Do not sell or share my personal information</a>
            </div>
        </div>
    </div>
</footer>