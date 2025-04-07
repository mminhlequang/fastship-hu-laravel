<footer id="footer">
    <div
            class="py-10 md:py-16 bg-[#f4f4f4] px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
    >
        <div>
            <div class="grid grid-cols-1 md:gap-0 md:grid-cols-2 xl:grid-cols-4">
                <div class="flex flex-col gap-2.5 md:gap-6 mt-4 md:mt-0">
                    <img data-src="{{ url('assets/images/logo_main.svg') }}" class="w-[214px] lazyload"/>
                    <p>
                        Company # 490039-445,<br/>Registered with<br/>House of
                        companies.
                    </p>
                </div>
                <div class="flex flex-col gap-2.5 md:gap-6 mt-4 md:mt-0">
                    <h3 class="text-lg font-medium">Legal Pages</h3>
                    <ul class="space-y-2">
                        <li>
                            <a
                                    href="{{ url('contact') }}"
                                    class="underline transition-all hover:text-primary"
                            >Terms and conditions</a
                            >
                        </li>
                        <li>
                            <a
                                    href="{{ url('policy') }}"
                                    class="underline transition-all hover:text-primary"
                            >Privacy</a
                            >
                        </li>
                        <li>
                            <a
                                    href="{{ url('faq') }}"
                                    class="underline transition-all hover:text-primary"
                            >Cookies</a
                            >
                        </li>
                        <li>
                            <a
                                    href="{{ url('faq') }}"
                                    class="underline transition-all hover:text-primary"
                            >Modern Slavery Statement</a
                            >
                        </li>
                    </ul>
                </div>
                <div class="flex flex-col gap-2.5 md:gap-6 mt-4 md:mt-0">
                    <h3 class="text-lg font-medium">Important Links</h3>
                    <ul class="space-y-2">
                        <li>
                            <a
                                    href="{{ url('faq') }}"
                                    class="underline transition-all hover:text-primary"
                            >Get help</a
                            >
                        </li>
                        <li>
                            <a
                                    href="{{ url('stores') }}"
                                    class="underline transition-all hover:text-primary"
                            >Add your restaurant</a
                            >
                        </li>
                        <li>
                            <a
                                    href="{{ url('become-our-driver') }}"
                                    class="underline transition-all hover:text-primary"
                            >Sign up to deliver</a
                            >
                        </li>
                        <li>
                            <a
                                    href="{{ url('become-our-partner') }}"
                                    class="underline transition-all hover:text-primary"
                            >Create a business account</a
                            >
                        </li>
                    </ul>
                </div>
                <div class="flex flex-col gap-2.5 md:gap-6 mt-4 md:mt-0">
                    <h3 class="text-lg font-medium">
                        Get Exclusive Deals in your Inbox
                    </h3>
                    <form id="newsLetterForm" method="POST">
                        @csrf
                        <div
                                class="flex items-center gap-1.5 py-1.5 pl-4 pr-1.5 rounded-full bg-white shadow"
                        >
                            <input name="email"
                                   type="email"
                                   class="flex-1 focus:outline-none"
                                   placeholder="youremail@gmail.com"
                                   required
                            />
                            <button type="submit"
                                    class="rounded-full py-2.5 px-4 bg-secondary text-white xl:text-xs 2xl:text-base hover:bg-secondary-700"
                            >
                                Subscribe
                            </button>
                        </div>
                    </form>
                    <p class="my-2 text-sm text-center md:text-start">
                        we wont spam, read our
                        <a href="{{ url('policy') }}" class="underline text-secondary">email policy</a>
                    </p>
                    <div
                            class="flex items-center gap-3 justify-center md:justify-start"
                    >
                  <span class="w-5 h-5 flex bg-primary/20 rounded-full">
                    <img
                            data-src="{{ url('assets/icons/fb_icon.svg') }}"
                            class="h-[10px] invert m-auto lazyload"
                    />
                  </span>
                        <span class="w-5 h-5 flex bg-primary/20 rounded-full">
                    <img
                            data-src="{{ url('assets/icons/twitter_icon.svg') }}"
                            class="h-[10px] invert m-auto lazyload"
                    />
                  </span>
                        <span class="w-5 h-5 flex bg-primary/20 rounded-full">
                    <img
                            data-src="{{ url('assets/icons/youtube_icon.svg') }}"
                            class="h-[10px] invert m-auto lazyload"
                    />
                  </span>
                        <span class="w-5 h-5 flex bg-primary/20 rounded-full">
                    <img
                            data-src="{{ url('assets/icons/pinterest_icon.svg') }}"
                            class="h-[10px] invert m-auto lazyload"
                    />
                  </span>
                        <span class="w-5 h-5 flex bg-primary/20 rounded-full">
                    <img
                            data-src="{{ url('assets/icons/ins_icon.svg') }}"
                            class="h-[10px] invert m-auto lazyload"
                    />
                  </span>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div
            class="copyright bg-dark py-4 text-white px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80"
    >
        <div
                class="container mx-auto flex flex-wrap flex-col lg:flex-nowrap lg:flex-row items-start md:items-center justify-between"
        >
            <p class="md:w-auto w-full mb-4 md:mb-0">
                @ Copyright 2025, All Rights Reserved.
            </p>
            <div
                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-7 md:w-auto w-full"
            >
                <a href="{{ url('policy') }}" class="transition-all hover:text-primary"
                >Privacy Policy</a
                >
                <a href="{{ url('policy') }}" class="transition-all hover:text-primary">Terms</a>
                <a href="#{{ url('policy') }} class=" transition-all hover:text-primary">Pricing</a>
                <a href="{{ url('contact') }}" class="transition-all hover:text-primary"
                >Do not sell or share my personal information</a
                >
            </div>
        </div>
    </div>
</footer>