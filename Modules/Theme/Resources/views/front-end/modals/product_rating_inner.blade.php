@foreach($product->rating as $item)
    <div class="review-item pt-3">
        <div class="review-header flex gap-2 justify-between mb-2">
            <div class="reviewer-info flex gap-2 items-center">
                <div class="reviewer-avatar">
                    <img class="rounded-full" src="{{ url('images/avatar.png') }}" width="40"
                         height="40" alt="">
                </div>
                <div class="reviewer-name font-fredoka font-medium text-base leading-[1.4] tracking-[0.2px] text-[#212121]">
                    {{ optional($item->user)->name }}
                </div>
            </div>
            <div class="flex items-center justify-center gap-3">
                <div class="stars flex items-center gap-1">
                    @for ($i = 0; $i < $item->star; $i++)
                        <span class="w-[14px] h-[14px] flex items-center justify-center">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                              <path d="M10.453 8.35301C10.3019 8.49942 10.2325 8.71117 10.2669 8.91884L10.7855 11.7888C10.8293 12.0321 10.7266 12.2783 10.523 12.4188C10.3235 12.5647 10.0581 12.5822 9.8405 12.4655L7.25692 11.118C7.16709 11.0702 7.06734 11.0445 6.96525 11.0416H6.80717C6.75234 11.0498 6.69867 11.0673 6.64967 11.0941L4.0655 12.448C3.93775 12.5122 3.79309 12.5349 3.65134 12.5122C3.306 12.4468 3.07559 12.1178 3.13217 11.7708L3.65134 8.90076C3.68575 8.69134 3.61634 8.47842 3.46525 8.32967L1.35884 6.28801C1.18267 6.11709 1.12142 5.86042 1.20192 5.62884C1.28009 5.39784 1.47959 5.22926 1.7205 5.19134L4.61967 4.77076C4.84017 4.74801 5.03384 4.61384 5.133 4.41551L6.4105 1.79634C6.44084 1.73801 6.47992 1.68434 6.52717 1.63884L6.57967 1.59801C6.60709 1.56767 6.63859 1.54259 6.67359 1.52217L6.73717 1.49884L6.83634 1.45801H7.08192C7.30125 1.48076 7.49434 1.61201 7.59525 1.80801L8.88967 4.41551C8.983 4.60626 9.16442 4.73867 9.37384 4.77076L12.273 5.19134C12.518 5.22634 12.7228 5.39551 12.8038 5.62884C12.8803 5.86276 12.8143 6.11942 12.6347 6.28801L10.453 8.35301Z"
                                    fill="#F17228"></path>
                            </svg>
                        </span>
                    @endfor
                </div>
                <button class="flex w-6 h-6 items-center justify-center">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M10 0.75C15.108 0.75 19.25 4.891 19.25 10C19.25 15.108 15.108 19.25 10 19.25C4.891 19.25 0.75 15.108 0.75 10C0.75 4.892 4.892 0.75 10 0.75Z"
                              stroke="#CEC6C5" stroke-width="1.5" stroke-linecap="round"
                              stroke-linejoin="round"></path>
                        <path d="M13.9389 10.0127H13.9479" stroke="#847D79" stroke-width="2"
                              stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M9.93007 10.0127H9.93907" stroke="#847D79" stroke-width="2"
                              stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M5.92128 10.0127H5.93028" stroke="#847D79" stroke-width="2"
                              stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
        </div>
        <p class="review-text text-[14px] font-fredoka leading-[1.4] tracking-[1%] text-[#3C3836] text-start mb-2">
            {{ $item->content }}
        </p>
        <div class="review-footer flex gap-2 items-center">
            <button class="helpful-btn flex items-center gap-2">
                            <span class="w-5 h-5 flex items-center justify-center">
                              <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                   xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M12.2082 0.0841174C12.734 0.0841174 13.259 0.158284 13.7582 0.325784C16.834 1.32578 17.9424 4.70078 17.0165 7.65078C16.4915 9.15828 15.6332 10.5341 14.509 11.6583C12.8999 13.2166 11.134 14.5999 9.23318 15.7916L9.02485 15.9174L8.80818 15.7833C6.90068 14.5999 5.12485 13.2166 3.50068 11.65C2.38402 10.5258 1.52485 9.15828 0.991518 7.65078C0.049851 4.70078 1.15818 1.32578 4.26735 0.308284C4.50902 0.224951 4.75818 0.166617 5.00818 0.134117H5.10818C5.34235 0.0999508 5.57485 0.0841174 5.80818 0.0841174H5.89985C6.42485 0.0999508 6.93318 0.191617 7.42568 0.359117H7.47485C7.50818 0.374951 7.53318 0.392451 7.54985 0.408284C7.73402 0.467451 7.90818 0.534117 8.07485 0.625784L8.39152 0.767451C8.46804 0.80826 8.55392 0.870617 8.62815 0.924508C8.67518 0.958654 8.71753 0.989401 8.74985 1.00912C8.76345 1.01714 8.77728 1.02521 8.79121 1.03335C8.86266 1.07505 8.93709 1.1185 8.99985 1.16662C9.92568 0.459117 11.0499 0.0757841 12.2082 0.0841174ZM14.4249 6.08412C14.7665 6.07495 15.0582 5.80078 15.0832 5.44995V5.35078C15.1082 4.18328 14.4007 3.12578 13.3249 2.71745C12.9832 2.59995 12.6082 2.78412 12.4832 3.13412C12.3665 3.48412 12.5499 3.86745 12.8999 3.99162C13.434 4.19162 13.7915 4.71745 13.7915 5.29995V5.32578C13.7757 5.51662 13.8332 5.70078 13.9499 5.84245C14.0665 5.98412 14.2415 6.06662 14.4249 6.08412Z"
                                      fill="#74CA45"></path>
                              </svg>
                            </span>
                <span class="font-fredoka text-xs text-[#212121] leading-[1.25] tracking-[1%]">0</span>
            </button>
            <span class="text-xs font-fredoka text-[#847D79] leading-[1.25] tracking-[1%]">{{ $item->created_at->diffForHumans() }}</span>
        </div>
    </div>
@endforeach