@forelse($carts as $itemC)
    <div class="p-[10px] border border-[#74CA45] rounded-xl mt-5">
        <div class="pb-[10px] border-b border-[#EDEDED]">
            <h3 class="text-base leading-[1.2] text-[#00000] max-w-[235px] overflow-hidden text-ellipsis whitespace-nowrap">{{ optional($itemC->store)->name }}</h3>
            <div class="flex items-center justify-between mt-[6px]">
                <div class="flex gap-2 items-center justify-center">
                    <span class="text-xs leading-[1.2] text-[#7D7575] pr-2 border-r border-[#F1EFE9]">Over 15 mins</span>
                    <span class="text-xs leading-[1.2] text-[#7D7575] pr-2 border-r border-[#F1EFE9]">1,8 km</span>
                </div>
                <div class="discount-badge bg-[#F17228] text-[#FFFFFF] flex items-center gap-0.5 px-2 py-[5px] text-xs leading-[1.2] rounded-lg">
            <span class="w-[14px] h-[14px] flex items-center justify-center">
              <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.41553 0.75C10.7478 0.75 11.8324 1.82443 11.8325 3.14453L11.8335 4.70605C11.8335 4.82045 11.7872 4.93178 11.7056 5.0127C11.6234 5.09412 11.5125 5.13956 11.396 5.13965C10.9171 5.13965 10.5269 5.52644 10.5269 6.00098C10.5271 6.47533 10.9172 6.86133 11.396 6.86133C11.6373 6.8615 11.8335 7.05574 11.8335 7.29492V8.85547C11.8335 10.1755 10.7496 11.2497 9.41748 11.25H2.5835C1.25116 11.25 0.166504 10.1756 0.166504 8.85547V7.29492C0.166504 7.05563 0.362504 6.86133 0.604004 6.86133C1.08337 6.86133 1.47293 6.47533 1.47314 6.00098C1.47314 5.53857 1.09925 5.19043 0.604004 5.19043C0.487923 5.19043 0.376683 5.14497 0.294434 5.06348C0.212212 4.98201 0.166536 4.87124 0.166504 4.75684L0.16748 3.14453C0.167554 1.82455 1.25136 0.750183 2.5835 0.75H9.41553ZM6.00049 3.75586C5.83608 3.75586 5.68789 3.84747 5.61377 3.99414L5.18799 4.84766L4.23975 4.98535C4.07583 5.00847 3.94108 5.11933 3.88916 5.27539C3.83807 5.43121 3.87986 5.59953 3.99854 5.71387L4.68604 6.37793L4.52393 7.31641C4.49612 7.4781 4.56273 7.63891 4.69678 7.73535C4.77251 7.78889 4.86064 7.81641 4.94971 7.81641C5.01788 7.81637 5.08698 7.79951 5.1499 7.7666L6.00049 7.32422L6.84912 7.76562C6.99607 7.84352 7.17069 7.83143 7.3042 7.73438C7.43865 7.63847 7.5039 7.47801 7.47607 7.31641L7.31396 6.37793L8.00146 5.71387C8.12076 5.59952 8.16253 5.43124 8.11084 5.27539C8.05954 5.11942 7.92522 5.00798 7.76318 4.98535L6.81299 4.84766L6.38721 3.99512C6.31433 3.84838 6.16645 3.75653 6.00146 3.75586H6.00049Z"
                      fill="white"></path>
              </svg>
              </span>
                    2,0 off
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-[10px] items-center justify-center">
            @foreach($itemC->cartItems as $item)
                <div class="flex gap-1 mt-[10px] w-full">
                    <div class="w-20 h-20">
                        <img src="{{ url($item->product['image']) }}" alt="order" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col flex-1">
                        <div class="text-start w-full">
                            <div class="text-[14px] text-[#000000] leading-[1.2143]">{{ $item->product['name'] ?? '' }}</div>
                            @if($item->variations != null)
                                @foreach($item->variations as $itemV)
                                    <p class="text-[#847D79] text-xs leading-[1.2] tracking-[1%] mt-[6px]"> {{ $itemV['variation']['name'] ?? '' }}: {{ $itemV['value'] }} {{ $itemV['price'] }} Ft
                                        @if(!$loop->last), @endif
                                    </p>
                                @endforeach
                            @endif

                            <div class="border-b border-[#E6E6E6] border-dashed my-[6px]"></div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="original-price text-base leading-[1.5] text-[#A6A0A0] line-through">{{ number_format($item->product['price'] + 5, 0, '.', ' ') }} Ft</span>
                                &nbsp;
                                <span class="current-price text-base leading-[1.5] text-[#F17228] font-medium tracking-[1%]">{{ number_format($item->product['price'], 0, '.', ' ') }} Ft</span>
                            </div>
                            <div class="flex items-center justify-center gap-[6px] border border[#E7E7E7] rounded-full py-[6px] px-3 ml-auto divAction">
                                <button class="w-5 h-5 flex items-center justify-center cursor-pointer increment-drop" data-id="{{ $item->id }}">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.32186 1L7.32186 12.3563M13 6.67814L1.64373 6.67814" stroke="black"
                                              stroke-width="1.5" stroke-linecap="round"></path>
                                    </svg>
                                </button>
                                <span class="text-sm leading-[1.5] text-[#000000] flex items-center justify-center w-5 h-5 counter">{{ $item->quantity }}</span>
                                <button class="w-5 h-5 flex items-center justify-center cursor-pointer decrement-drop" data-id="{{ $item->id }}">
                                    <svg width="14" height="2" viewBox="0 0 14 2" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13 1L1.64373 1" stroke="#120F0F" stroke-width="1.5"
                                              stroke-linecap="round"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <form method="POST" action="{{ url('check-out') }}">
            @csrf
            <input type="hidden" name="store_id" value="{{ $itemC->store_id }}">
            <button type="submit"
                    class="text-base leading-[1.1875] text-[#FFFFFF] py-[12.5px] bg-[#74CA45] w-full rounded-full mt-4 hover:bg-primary-700">
                Check out now
            </button>
        </form>
    </div>
@empty
    <div class="flex flex-col items-center justify-center gap-6 mt-4 text-center">
        <img src="{{ url('images/no-data.webp') }}" width="190" height="160" class="mx-auto">
        <h6 class="text-dark font-medium">Nothing to Show</h6>
    </div>
@endforelse
