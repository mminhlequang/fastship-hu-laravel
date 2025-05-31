<style>
    .driver-panel {
        position: absolute;
        top: 30%;
        right: 0;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }
</style>
@if(isset($order) && $order->driver_id != null)
    <section class="driver-panel dialog-infomation p-6 bg-[#FFFFFF] rounded-[1.625rem] h-fit w-full max-w-[375px]">
        <div class="flex items-center pb-4 border-b border-[#F8F1F0]">
            <h3 class="text-[#120F0F] text-[20px] font-medium leading-[1.2] font-fredoka">Driver Information</h3>
            <span class="ml-auto p-[6.46px] cursor-pointer doneBtn">
              <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.53516 1.46484L1.46409 8.53591" stroke="#7A838C" stroke-width="1.5"
                      stroke-linecap="round"></path>
                <path d="M1.46387 1.46484L8.53493 8.53591" stroke="#7A838C" stroke-width="1.5"
                      stroke-linecap="round"></path>
              </svg>
            </span>
        </div>
        <div class="flex items-center gap-4 mt-2 w-full">
            <div class="driver-avatar w-24 h-24 rounded-[50%] overflow-hidden flex-shrink-0">
                <img src="{{ url('images/driver.png') }}" alt="Driver" class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col items-start gap-2">
                <div class="font-fredoka text-2xl leading-[1.2] text-[#120F0F] tracking-[1%] font-medium">{{ optional($order->driver)->name }}</div>
                <div class="flex justify-between gap-[70px] items-center">
                    <span class="font-fredoka text-[#F17228] text-base leading-[1.4] tracking-[1%] font-medium underline whitespace-nowrap">{{ optional($order->driver)->phone }}</span>
                    <button class="flex items-center justify-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.0957 1.25684C11.541 1.26389 11.9359 1.27788 12.2881 1.30664C13.0014 1.36492 13.6051 1.48682 14.1563 1.76758C15.05 2.22298 15.777 2.94998 16.2324 3.84375L16.3301 4.05274C16.5423 4.54731 16.6424 5.08764 16.6934 5.71192C16.7294 6.15307 16.7394 6.66155 16.7441 7.25195L17.3066 7.25391C17.6468 7.25805 17.9492 7.26627 18.2197 7.2832C18.7664 7.31743 19.2334 7.38931 19.6729 7.55469L19.9102 7.65137C21.0776 8.16411 21.9936 9.12707 22.4453 10.3271L22.5029 10.4932C22.6279 10.8845 22.6868 11.3018 22.7168 11.7803C22.7506 12.3211 22.75 12.9888 22.75 13.833V15.5996C22.75 16.7072 22.7509 17.5835 22.6934 18.2881C22.6351 19.0014 22.5132 19.6051 22.2324 20.1563C21.777 21.05 21.05 21.777 20.1563 22.2324C19.6051 22.5132 19.0014 22.6351 18.2881 22.6934C17.5835 22.7509 16.7072 22.75 15.5996 22.75H13.833C12.9888 22.75 12.3211 22.7506 11.7803 22.7168C11.3018 22.6868 10.8845 22.6279 10.4932 22.5029L10.3271 22.4453C9.12707 21.9936 8.16411 21.0776 7.65137 19.9102L7.55469 19.6729C7.38931 19.2334 7.31743 18.7664 7.2832 18.2197C7.25747 17.8085 7.25325 17.3239 7.25195 16.7441C6.66155 16.7394 6.15307 16.7294 5.71192 16.6934C5.08764 16.6424 4.54731 16.5423 4.05274 16.3301L3.84375 16.2324C2.94998 15.777 2.22298 15.05 1.76758 14.1563C1.48682 13.6051 1.36492 13.0014 1.30664 12.2881C1.24909 11.5835 1.25 10.7072 1.25 9.59961V8.40039C1.25 7.29277 1.24909 6.41651 1.30664 5.71192C1.36492 4.99862 1.48682 4.39487 1.76758 3.84375C2.22298 2.94998 2.94998 2.22298 3.84375 1.76758C4.39487 1.48682 4.99862 1.36492 5.71192 1.30664C6.41651 1.24909 7.29277 1.25 8.40039 1.25H9.59961L11.0957 1.25684ZM16.75 9.59961C16.75 10.7072 16.7509 11.5835 16.6934 12.2881C16.6351 13.0014 16.5132 13.6051 16.2324 14.1563C15.777 15.05 15.05 15.777 14.1563 16.2324C13.6051 16.5132 13.0014 16.6351 12.2881 16.6934C11.5835 16.7509 10.7072 16.75 9.59961 16.75H8.75195C8.7533 17.3183 8.75734 17.7595 8.78027 18.126C8.81013 18.6028 8.86717 18.903 8.95801 19.1445L9.02442 19.3066C9.3752 20.1056 10.0342 20.7329 10.8555 21.042L11.0488 21.1035C11.2576 21.1593 11.516 21.1973 11.874 21.2197C12.3571 21.25 12.9701 21.25 13.833 21.25H15.5996C16.732 21.25 17.5367 21.2497 18.166 21.1982C18.7866 21.1475 19.1711 21.0506 19.4756 20.8955C20.087 20.5839 20.5839 20.087 20.8955 19.4756C21.0506 19.1711 21.1475 18.7866 21.1982 18.166C21.2497 17.5367 21.25 16.732 21.25 15.5996V13.833C21.25 12.9701 21.25 12.3571 21.2197 11.874C21.1973 11.516 21.1593 11.2576 21.1035 11.0488L21.042 10.8555C20.7329 10.0342 20.1056 9.3752 19.3066 9.02442L19.1445 8.95801C18.903 8.86717 18.6028 8.81013 18.126 8.78027C17.8845 8.76516 17.6104 8.75777 17.2891 8.75391L16.75 8.75195V9.59961ZM8.40039 2.75C7.26798 2.75 6.46335 2.75035 5.83399 2.80176C5.21336 2.85247 4.82889 2.94936 4.52442 3.10449C3.91305 3.41605 3.41605 3.91305 3.10449 4.52442C2.94936 4.82889 2.85247 5.21336 2.80176 5.83399C2.75035 6.46335 2.75 7.26798 2.75 8.40039V9.59961C2.75 10.732 2.75035 11.5367 2.80176 12.166C2.85247 12.7866 2.94936 13.1711 3.10449 13.4756C3.41605 14.087 3.91305 14.5839 4.52442 14.8955L4.64258 14.9512C4.92861 15.0743 5.29083 15.1539 5.83399 15.1982C6.46335 15.2497 7.26798 15.25 8.40039 15.25H9.59961C10.732 15.25 11.5367 15.2497 12.166 15.1982C12.7866 15.1475 13.1711 15.0506 13.4756 14.8955C14.087 14.5839 14.5839 14.087 14.8955 13.4756C15.0506 13.1711 15.1475 12.7866 15.1982 12.166C15.2497 11.5367 15.25 10.732 15.25 9.59961V8.40039C15.25 7.26798 15.2497 6.46335 15.1982 5.83399C15.1539 5.29083 15.0743 4.92861 14.9512 4.64258L14.8955 4.52442C14.5839 3.91305 14.087 3.41605 13.4756 3.10449C13.1711 2.94936 12.7866 2.85247 12.166 2.80176C11.8512 2.77605 11.4927 2.76341 11.0713 2.75684L9.59961 2.75H8.40039Z"
                                  fill="#22272F"></path>
                        </svg>
                    </button>
                </div>
                <div class="rating bg-[#FEF2EC] py-1 px-3 rounded-2xl flex items-center gap-1">
                    <span class="star text-[#F17228] text-xs leading-[1]">★</span>
                    <span class="star text-[#F17228] text-xs leading-[1]">★</span>
                    <span class="star text-[#F17228] text-xs leading-[1]">★</span>
                    <span class="star text-[#F17228] text-xs leading-[1]">★</span>
                    <span class="star text-[#F17228] text-xs leading-[1]">★</span>
                    <span class="rating-number text-base leading-[1.2] font-medium text-[#F17228]">5</span>
                </div>
            </div>
        </div>
        <div class="flex gap-4 w-full justify-between items-center mt-4 shadow-[0px_4px_60px_rgba(4,6,15,0.05)] px-4 py-3 bg-[#FFFFFF] rounded-2xl">
            <div class="text-center">
                <div class="w-10 h-10 bg-[#F4F4F4] rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.9189 12.32C15.6599 12.571 15.5409 12.934 15.5999 13.29L16.4889 18.21C16.5639 18.627 16.3879 19.049 16.0389 19.29C15.6969 19.54 15.2419 19.57 14.8689 19.37L10.4399 17.06C10.2859 16.978 10.1149 16.934 9.93988 16.929H9.66888C9.57488 16.943 9.48288 16.973 9.39888 17.019L4.96888 19.34C4.74988 19.45 4.50188 19.489 4.25888 19.45C3.66688 19.338 3.27188 18.774 3.36888 18.179L4.25888 13.259C4.31788 12.9 4.19888 12.535 3.93988 12.28L0.328876 8.78C0.0268758 8.487 -0.0781242 8.047 0.0598758 7.65C0.193876 7.254 0.535876 6.965 0.948876 6.9L5.91888 6.179C6.29688 6.14 6.62888 5.91 6.79888 5.57L8.98888 1.08C9.04088 0.98 9.10788 0.888 9.18888 0.81L9.27888 0.74C9.32588 0.688 9.37988 0.645 9.43988 0.61L9.54888 0.57L9.71888 0.5H10.1399C10.5159 0.539 10.8469 0.764 11.0199 1.1L13.2389 5.57C13.3989 5.897 13.7099 6.124 14.0689 6.179L19.0389 6.9C19.4589 6.96 19.8099 7.25 19.9489 7.65C20.0799 8.051 19.9669 8.491 19.6589 8.78L15.9189 12.32Z"
                              fill="#74CA45"></path>
                    </svg>
                </div>
                <div class="text-base leading-[1.2] font-fredoka font-medium text-[#212121] mb-1">{{ optional($order->driver)->averageRating() }}</div>
                <div class="text-sm leading-[1.2] font-fredoka tracking-[0.2px] text-[#757575]">Ratings</div>
            </div>
            <span class="w-[1px] h-[53px] bg-[#F8F1F0]"></span>
            <div class="text-center">
                <div class="w-10 h-10 bg-[#F4F4F4] rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.50432 0C12.1843 0 14.3876 2.106 14.4994 4.77441H14.474C14.4772 4.85194 14.4619 4.92916 14.4301 5H14.5863C15.8501 5.00003 17.1756 5.90973 17.644 8.12012L18.4135 14.3145C18.9791 18.3531 16.7075 19.9999 13.6586 20H5.36857C2.31066 20 -0.0314254 18.8627 0.604903 14.3145L1.38225 8.12012C1.7799 5.84629 3.14972 5.00021 4.43107 5H4.51018C4.49699 4.92535 4.49699 4.84906 4.51018 4.77441C4.622 2.10615 6.82458 0.000240541 9.50432 0ZM6.59709 8.3291C6.10899 8.3291 5.7133 8.73688 5.7133 9.23926C5.71347 9.74149 6.10909 10.1484 6.59709 10.1484C7.08497 10.1483 7.48071 9.7414 7.48088 9.23926C7.48088 8.73697 7.08507 8.32925 6.59709 8.3291ZM12.3852 8.3291C11.8973 8.32936 11.5014 8.73704 11.5014 9.23926C11.5016 9.74133 11.8974 10.1482 12.3852 10.1484C12.8732 10.1484 13.2688 9.74149 13.269 9.23926C13.269 8.73688 12.8733 8.3291 12.3852 8.3291ZM9.46525 1.30273C7.54129 1.30291 5.98186 2.85702 5.98186 4.77441C5.99502 4.84902 5.99503 4.9254 5.98186 5H12.9926C12.9648 4.92802 12.9506 4.85153 12.9496 4.77441C12.9496 2.85691 11.3894 1.30273 9.46525 1.30273Z"
                              fill="#74CA45"></path>
                    </svg>
                </div>
                <div class="text-base leading-[1.2] font-fredoka font-medium text-[#212121] mb-1">{{ optional($order->driver)->driverOrders()->count() }}</div>
                <div class="text-sm leading-[1.2] font-fredoka tracking-[0.2px] text-[#757575]">Orders</div>
            </div>
            <span class="w-[1px] h-[53px] bg-[#F8F1F0]"></span>
            <div class="text-center">
                <div class="w-10 h-10 bg-[#F4F4F4] rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M10 20C4.48 20 0 15.53 0 10C0 4.48 4.48 0 10 0C15.53 0 20 4.48 20 10C20 15.53 15.53 20 10 20ZM13.19 13.71C13.31 13.78 13.44 13.82 13.58 13.82C13.83 13.82 14.08 13.69 14.22 13.45C14.43 13.1 14.32 12.64 13.96 12.42L10.4 10.3V5.68C10.4 5.26 10.06 4.93 9.65 4.93C9.24 4.93 8.9 5.26 8.9 5.68V10.73C8.9 10.99 9.04 11.23 9.27 11.37L13.19 13.71Z"
                              fill="#74CA45"></path>
                    </svg>
                </div>
                <div class="text-base leading-[1.2] font-fredoka font-medium text-[#212121] mb-1">4</div>
                <div class="text-sm leading-[1.2] font-fredoka tracking-[0.2px] text-[#757575]">Years</div>
            </div>
        </div>
        <div class="bg-[#F4F4F4] px-2 py-3 flex flex-col gap-3 rounded-2xl mt-4">
            <div class="flex justify-between items-center gap-1">
                <span class="text-[#847D79] font-fredoka text-base leading-[1.4] tracking-[1%]">Member Since</span>
                <span class="text-[#120F0F] font-fredoka text-base leading-[1.4] tracking-[1%]">July 15, 2019</span>
            </div>
            <div class="flex justify-between items-center gap-1">
                <span class="text-[#847D79] font-fredoka text-base leading-[1.4] tracking-[1%]">Motorcycle model</span>
                <span class="text-[#120F0F] font-fredoka text-base leading-[1.4] tracking-[1%]">Yamaha MX King</span>
            </div>
            <div class="flex justify-between items-center gap-1">
                <span class="text-[#847D79] font-fredoka text-base leading-[1.4] tracking-[1%]">Plate Number</span>
                <span class="text-[#120F0F] font-fredoka text-base leading-[1.4] tracking-[1%]">HSW 4736 XK</span>
            </div>
        </div>
        <div class="mt-4 flex justify-items-center justify-center">
            <button class="action-btn btn-call border border-[#F1EFE9]  p-[19.33px] rounded-[50%]">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M9.53174 10.4724C13.5208 14.4604 14.4258 9.84672 16.9656 12.3848C19.4143 14.8328 20.8216 15.3232 17.7192 18.4247C17.3306 18.737 14.8616 22.4943 6.1846 13.8197C-2.49348 5.144 1.26158 2.67244 1.57397 2.28395C4.68387 -0.826154 5.16586 0.589383 7.61449 3.03733C10.1544 5.5765 5.54266 6.48441 9.53174 10.4724Z"
                          fill="#74CA45"></path>
                </svg>
            </button>
        </div>
    </section>
@endif
