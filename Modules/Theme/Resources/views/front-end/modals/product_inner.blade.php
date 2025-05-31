@if(isset($product))
    <style>
        .reviews-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .reviews-content.expanded {
            max-height: 800px;
        }

        .text-\[\#120F0F\] {
            --tw-text-opacity: 1;
            color: rgb(18 15 15 / var(--tw-text-opacity, 1));
        }

        .text-\[\#212121\] {
            --tw-text-opacity: 1;
            color: rgb(33 33 33 / var(--tw-text-opacity, 1));
        }

        .text-\[\#3C3836\] {
            --tw-text-opacity: 1;
            color: rgb(60 56 54 / var(--tw-text-opacity, 1));
        }

        .text-\[\#424242\] {
            --tw-text-opacity: 1;
            color: rgb(66 66 66 / var(--tw-text-opacity, 1));
        }

        .bg-\[\#74CA45\] {
            --tw-bg-opacity: 1;
            background-color: rgb(116 202 69 / var(--tw-bg-opacity, 1));
        }

        .h-\[6px\] {
            height: 6px;
        }

        .bg-\[\#F4F4F4\] {
            --tw-bg-opacity: 1;
            background-color: rgb(244 244 244 / var(--tw-bg-opacity, 1));
        }

        .text-\[\#212121\] {
            --tw-text-opacity: 1;
            color: rgb(33 33 33 / var(--tw-text-opacity, 1));
        }

        .leading-\[1\.4\] {
            line-height: 1.4;
        }

        .rating-bars {
            flex: 1;
        }

        .text-\[\#8E8E8E\] {
            --tw-text-opacity: 1;
            color: rgb(142 142 142 / var(--tw-text-opacity, 1));
        }
    </style>
    <!-- Close Button -->
    <!-- Product Header -->
    <div class="px-4 pt-8 pb-4 mb-3">
        <div class="flex justify-between mb-3">
            <h3 class="text-lg font-medium">
                {{ $product->name }}
            </h3>
            <button
                    onclick="toggleModal('modalOverlayProduct')"
                    class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                          clip-rule="evenodd"/>
                </svg>
            </button>
        </div>

        <!-- Product Content -->
        <!-- Product Image & Price -->
        <div class="flex flex-wrap flex-col items-start">
            <div class="relative block w-full pb-3 border-b border-gray-200 border-dashed mb-3">
                <img onerror="this.onerror=null; this.src='{{ url('images/no-image.png') }}'"
                     data-src="{{ url($product->image) }}"
                     class="aspect-square rounded-2xl object-cover w-full h-[300px] lazyload "/>
                <div class="p-2 absolute top-0 left-0 right-0 flex items-start md:items-center justify-between z-10">
                <span class="bg-secondary text-white rounded-full py-1 px-2.5 md:w-auto md:px-3 md:py-1.5 flex items-center text-sm gap-1">
                    <img data-src="{{ url('assets/icons/ticket_star_icon.svg') }}" class="w-6 h-6 lazyload"/>
                    20% off
                </span>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="flex items-center justify-between font-medium">
                    <div class="mb-1">
                        <span class="font-medium text-lg">Price:</span>
                    </div>
                    <div class="flex items-center gap-1 text-base md:text-lg">
                        <span class="text-[#8E8E8E] line-through">{{ number_format($product->price + 5, 0, '.', '') }}&nbsp;Ft</span><span
                                class="text-secondary">{{ number_format($product->price, 0, '.', '') }}&nbsp;Ft</span>
                    </div>
                </div>
                <p class="text-sm text-gray-500 line-clamp-2" id="product-description">
                    {{ $product->description }}
                </p>
                <button onclick="toggleDescription()" class="text-primary text-sm mt-1 hover:underline">
                    <span id="show-more-text">show more</span>
                </button>
            </div>
        </div>
        @foreach($product->variationsX as $itemV)
            <div class="mb-4" data-group="variation-{{ $itemV->id }}">
                <p class="text-sm font-medium text-gray-700 mb-2">
                    {{ $itemV->name }}
                </p>
                <div class="space-y-2">
                    @foreach($itemV->values as $keyVL => $itemVL)
                        <label class="flex items-center justify-between p-2 border rounded-md {{ ($keyVL == 0) ? 'bg-green-50 border-primary' : '' }}">
                            <div>
                                <input
                                        type="radio"
                                        name="variation_{{ $itemV->id }}"
                                        value="{{ $itemVL->id }}"
                                        data-price="{{ $itemVL->price }}"
                                        class="hidden variation-radio"
                                        {{ $keyVL == 0 ? 'checked' : '' }} />
                                <span class="text-sm">{{ $itemVL->value }}</span>
                            </div>
                            <span class="text-sm text-gray-500">+{{ number_format($itemVL->price, 0, '.', '') }}&nbsp;Ft</span>
                        </label>
                    @endforeach
                </div>
                @endforeach
            </div>
            <div class="notes-section border border-[#EFEFEF] rounded-2xl p-3">
                <div class="flex justify-between mb-2">
                    <div class="option-title text-[14px] font-fredoka font-medium leading-[1.4] text-[#000000]">
                        Notice:
                    </div>
                    <div class="notes-counter text-right text-[14px] font-fredoka leading-[1.4] text-[#847D79]">0/300
                    </div>
                </div>
                <textarea
                        class="notes-input h-[116px] bg-[#F9F8F6] w-full py-2 px-[10px] rounded-xl placeholder-[#CEC6C5] placeholder:text-sm placeholder:leading-[1.4] placeholder:font-fredoka resize-none"
                        placeholder="Write a note to the restaurant" maxlength="300" rows="5"></textarea>
            </div>
            <div class="reviews-section border border-[#F8F1F0] rounded-2xl p-4 mb-4">
                <div class="section-header flex justify-between items-center mb-3 cursor-pointer"
                     onclick="toggleReviews()">
                    <span class="section-title text-[20px] font-fredoka font-medium leading-[1.2] text-[#212121]">Rating &amp; Reviews</span>
                    <span class="expand-icon w-6 h-6 flex items-center justify-center" id="expandIcon">
                  <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 1.5L6 6.5L11 1.5" stroke="#3C3836" stroke-width="1.5" stroke-linecap="round"
                          stroke-linejoin="round"></path>
                    </svg>
                </span>
                </div>
                <div class="reviews-content pt-3 border-t border-[#EEEEEE]" id="reviewsContent">
                    <div class="rating-overview flex items-center gap-4 pb-3 border-b border-[#EEEEEE]">
                        <div class="flex flex-col gap-3 items-center justify-center pr-4 border-r border-[#EEEEEE]">
                            <div class="rating-score text-[44px] font-fredoka font-medium leading-[1.2] text-[#212121]">
                                {{ $product->averageRating() }}
                            </div>
                            <div class="stars flex gap-[6px]">
                        <span class="w-6 h-6 flex items-center justify-center">
                          <svg width="20" height="19" viewBox="0 0 20 19" fill="none"
                               xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.9184 11.82C15.6594 12.071 15.5404 12.434 15.5994 12.79L16.4884 17.71C16.5634 18.127 16.3874 18.549 16.0384 18.79C15.6964 19.04 15.2414 19.07 14.8684 18.87L10.4394 16.56C10.2854 16.478 10.1144 16.434 9.93939 16.429H9.66839C9.57439 16.443 9.48239 16.473 9.39839 16.519L4.96839 18.84C4.74939 18.95 4.50139 18.989 4.25839 18.95C3.66639 18.838 3.27139 18.274 3.36839 17.679L4.25839 12.759C4.31739 12.4 4.19839 12.035 3.93939 11.78L0.328388 8.28C0.0263875 7.987 -0.0786125 7.547 0.0593875 7.15C0.193388 6.754 0.535388 6.465 0.948388 6.4L5.91839 5.679C6.29639 5.64 6.62839 5.41 6.79839 5.07L8.98839 0.58C9.04039 0.48 9.10739 0.388 9.18839 0.31L9.27839 0.24C9.32539 0.188 9.37939 0.145 9.43939 0.11L9.54839 0.07L9.71839 0H10.1394C10.5154 0.039 10.8464 0.264 11.0194 0.6L13.2384 5.07C13.3984 5.397 13.7094 5.624 14.0684 5.679L19.0384 6.4C19.4584 6.46 19.8094 6.75 19.9484 7.15C20.0794 7.551 19.9664 7.991 19.6584 8.28L15.9184 11.82Z"
                                  fill="#F17228"></path>
                          </svg>
                        </span>
                                <span class="w-6 h-6 flex items-center justify-center">
                          <svg width="20" height="19" viewBox="0 0 20 19" fill="none"
                               xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.9184 11.82C15.6594 12.071 15.5404 12.434 15.5994 12.79L16.4884 17.71C16.5634 18.127 16.3874 18.549 16.0384 18.79C15.6964 19.04 15.2414 19.07 14.8684 18.87L10.4394 16.56C10.2854 16.478 10.1144 16.434 9.93939 16.429H9.66839C9.57439 16.443 9.48239 16.473 9.39839 16.519L4.96839 18.84C4.74939 18.95 4.50139 18.989 4.25839 18.95C3.66639 18.838 3.27139 18.274 3.36839 17.679L4.25839 12.759C4.31739 12.4 4.19839 12.035 3.93939 11.78L0.328388 8.28C0.0263875 7.987 -0.0786125 7.547 0.0593875 7.15C0.193388 6.754 0.535388 6.465 0.948388 6.4L5.91839 5.679C6.29639 5.64 6.62839 5.41 6.79839 5.07L8.98839 0.58C9.04039 0.48 9.10739 0.388 9.18839 0.31L9.27839 0.24C9.32539 0.188 9.37939 0.145 9.43939 0.11L9.54839 0.07L9.71839 0H10.1394C10.5154 0.039 10.8464 0.264 11.0194 0.6L13.2384 5.07C13.3984 5.397 13.7094 5.624 14.0684 5.679L19.0384 6.4C19.4584 6.46 19.8094 6.75 19.9484 7.15C20.0794 7.551 19.9664 7.991 19.6584 8.28L15.9184 11.82Z"
                                  fill="#F17228"></path>
                          </svg>
                        </span>
                                <span class="w-6 h-6 flex items-center justify-center">
                          <svg width="20" height="19" viewBox="0 0 20 19" fill="none"
                               xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.9184 11.82C15.6594 12.071 15.5404 12.434 15.5994 12.79L16.4884 17.71C16.5634 18.127 16.3874 18.549 16.0384 18.79C15.6964 19.04 15.2414 19.07 14.8684 18.87L10.4394 16.56C10.2854 16.478 10.1144 16.434 9.93939 16.429H9.66839C9.57439 16.443 9.48239 16.473 9.39839 16.519L4.96839 18.84C4.74939 18.95 4.50139 18.989 4.25839 18.95C3.66639 18.838 3.27139 18.274 3.36839 17.679L4.25839 12.759C4.31739 12.4 4.19839 12.035 3.93939 11.78L0.328388 8.28C0.0263875 7.987 -0.0786125 7.547 0.0593875 7.15C0.193388 6.754 0.535388 6.465 0.948388 6.4L5.91839 5.679C6.29639 5.64 6.62839 5.41 6.79839 5.07L8.98839 0.58C9.04039 0.48 9.10739 0.388 9.18839 0.31L9.27839 0.24C9.32539 0.188 9.37939 0.145 9.43939 0.11L9.54839 0.07L9.71839 0H10.1394C10.5154 0.039 10.8464 0.264 11.0194 0.6L13.2384 5.07C13.3984 5.397 13.7094 5.624 14.0684 5.679L19.0384 6.4C19.4584 6.46 19.8094 6.75 19.9484 7.15C20.0794 7.551 19.9664 7.991 19.6584 8.28L15.9184 11.82Z"
                                  fill="#F17228"></path>
                          </svg>
                        </span>
                                <span class="w-6 h-6 flex items-center justify-center">
                          <svg width="20" height="19" viewBox="0 0 20 19" fill="none"
                               xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.9184 11.82C15.6594 12.071 15.5404 12.434 15.5994 12.79L16.4884 17.71C16.5634 18.127 16.3874 18.549 16.0384 18.79C15.6964 19.04 15.2414 19.07 14.8684 18.87L10.4394 16.56C10.2854 16.478 10.1144 16.434 9.93939 16.429H9.66839C9.57439 16.443 9.48239 16.473 9.39839 16.519L4.96839 18.84C4.74939 18.95 4.50139 18.989 4.25839 18.95C3.66639 18.838 3.27139 18.274 3.36839 17.679L4.25839 12.759C4.31739 12.4 4.19839 12.035 3.93939 11.78L0.328388 8.28C0.0263875 7.987 -0.0786125 7.547 0.0593875 7.15C0.193388 6.754 0.535388 6.465 0.948388 6.4L5.91839 5.679C6.29639 5.64 6.62839 5.41 6.79839 5.07L8.98839 0.58C9.04039 0.48 9.10739 0.388 9.18839 0.31L9.27839 0.24C9.32539 0.188 9.37939 0.145 9.43939 0.11L9.54839 0.07L9.71839 0H10.1394C10.5154 0.039 10.8464 0.264 11.0194 0.6L13.2384 5.07C13.3984 5.397 13.7094 5.624 14.0684 5.679L19.0384 6.4C19.4584 6.46 19.8094 6.75 19.9484 7.15C20.0794 7.551 19.9664 7.991 19.6584 8.28L15.9184 11.82Z"
                                  fill="#F17228"></path>
                          </svg>
                        </span>
                                <span class="w-6 h-6 flex items-center justify-center">
                          <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                               xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.4"
                                  d="M12.9763 3.61361L15.2028 8.08789C15.3668 8.41205 15.6799 8.63717 16.041 8.68719L21.042 9.41556C21.3341 9.45658 21.5992 9.61066 21.7782 9.84578C21.9552 10.0779 22.0312 10.3721 21.9882 10.6612C21.9532 10.9013 21.8402 11.1234 21.6672 11.2935L18.0434 14.8063C17.7783 15.0514 17.6583 15.4146 17.7223 15.7698L18.6145 20.7083C18.7095 21.3046 18.3144 21.8669 17.7223 21.98C17.4783 22.019 17.2282 21.978 17.0082 21.8659L12.5472 19.5417C12.2161 19.3746 11.8251 19.3746 11.494 19.5417L7.03303 21.8659C6.48491 22.157 5.80576 21.9589 5.5007 21.4187C5.38767 21.2036 5.34766 20.9584 5.38467 20.7193L6.27686 15.7798C6.34088 15.4256 6.21985 15.0604 5.95579 14.8153L2.33202 11.3045C1.90092 10.8883 1.88792 10.203 2.30301 9.77175C2.31201 9.76274 2.32201 9.75274 2.33202 9.74273C2.50405 9.56764 2.7301 9.45658 2.97415 9.42757L7.97523 8.6982C8.33531 8.64717 8.64837 8.42406 8.81341 8.09789L10.9599 3.61361C11.1509 3.22942 11.547 2.9903 11.9771 3.0003H12.1111C12.4842 3.04533 12.8093 3.27644 12.9763 3.61361Z"
                                  fill="#F17228"></path>
                            <path d="M11.992 19.4171C11.7983 19.4231 11.6096 19.4752 11.4399 19.5682L7.00072 21.8871C6.45756 22.1464 5.80756 21.9452 5.50303 21.4258C5.39021 21.2136 5.34927 20.9704 5.38721 20.7322L6.27384 15.8032C6.33375 15.4449 6.21394 15.0806 5.95334 14.8284L2.32794 11.3185C1.8976 10.8971 1.88961 10.2056 2.31096 9.77421C2.31695 9.76821 2.32195 9.7632 2.32794 9.7582C2.49967 9.58806 2.72133 9.47597 2.95996 9.44094L7.96523 8.70433C8.32767 8.6583 8.64219 8.43211 8.80194 8.10384L10.9776 3.56312C11.1843 3.19682 11.5806 2.97864 12 3.00166C11.992 3.2989 11.992 19.215 11.992 19.4171Z"
                                  fill="#F17228"></path>
                          </svg>
                        </span>
                            </div>
                            <div class="review-count font-fredoka text-base leading-[1.4] tracking-[0.2px] text-[#424242]">
                                ({{ count($product->rating) }} reviews)
                            </div>
                        </div>
                        <div class="rating-bars">
                            <div class="rating-bar flex items-center gap-2 mb-1">
                                <span class="font-fredoka text-base font-medium leading-[1.4] text-[#212121]">5</span>
                                <div class="bar-fill flex-1 h-[6px] bg-[#F4F4F4] rounded-full overflow-hidden">
                                    <div class="bar-progress h-full bg-[#74CA45] rounded-full block" data-width="{{ $product->percentRating(5) }}"></div>
                                </div>
                            </div>
                            <div class="rating-bar flex items-center gap-2 mb-1">
                                <span class="font-fredoka text-base font-medium leading-[1.4] text-[#212121]">4</span>
                                <div class="bar-fill flex-1 h-[6px] bg-[#F4F4F4] rounded-full overflow-hidden">
                                    <div class="bar-progress h-full bg-[#74CA45] rounded-full block" data-width="{{ $product->percentRating(4) }}"></div>
                                </div>
                            </div>
                            <div class="rating-bar flex items-center gap-2 mb-1">
                                <span class="font-fredoka text-base font-medium leading-[1.4] text-[#212121]">3</span>
                                <div class="bar-fill flex-1 h-[6px] bg-[#F4F4F4] rounded-full overflow-hidden">
                                    <div class="bar-progress h-full bg-[#74CA45] rounded-full block" data-width="{{ $product->percentRating(3) }}"></div>
                                </div>
                            </div>
                            <div class="rating-bar flex items-center gap-2 mb-1">
                                <span class="font-fredoka text-base font-medium leading-[1.4] text-[#212121]">2</span>
                                <div class="bar-fill flex-1 h-[6px] bg-[#F4F4F4] rounded-full overflow-hidden">
                                    <div class="bar-progress h-full bg-[#74CA45] rounded-full block" data-width="{{ $product->percentRating(2) }}"></div>
                                </div>
                            </div>
                            <div class="rating-bar flex items-center gap-2">
                                <span class="font-fredoka text-base font-medium leading-[1.4] text-[#212121]">1</span>
                                <div class="bar-fill flex-1 h-[6px] bg-[#F4F4F4] rounded-full overflow-hidden">
                                    <div class="bar-progress h-full bg-[#74CA45] rounded-full block" data-width="{{ $product->percentRating(1) }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sort-options flex gap-2 py-3 font-fredoka border-b border-[#EEEEEE]">
                        <button data-id="{{ $product->id }}" data-sort="asc" class="sort-btn sort-asc flex items-center justify-center gap-2 text-[#3C3836] text-base leading-[1.4] tracking-[0.2px] py-2 px-[14.5px] border border-[#F8F1F0] rounded-full">
                              <span class="w-4 h-4 flex items-center justify-center">
                                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M12.0337 8.55371C12.8417 8.55373 13.5738 8.59909 14.0239 8.68457C14.0388 8.68622 14.4434 8.77012 14.5796 8.82422C14.7776 8.90967 14.9453 9.06384 15.0522 9.25684C15.1287 9.4112 15.1665 9.57493 15.1665 9.74512C15.1593 9.9225 15.0446 10.2552 14.9907 10.3867C14.6558 11.2531 13.5573 12.909 12.8872 13.5439C12.7805 13.6514 12.6512 13.7678 12.6206 13.7988C12.4521 13.9301 12.2465 14 12.0259 14C11.8272 13.9999 11.629 13.9386 11.4692 13.8145C11.3618 13.7377 11.1905 13.5624 11.1802 13.5518C10.4858 12.9025 9.44154 11.2843 9.10596 10.4639C9.09679 10.4603 8.90011 9.97645 8.89209 9.74512V9.71387C8.89217 9.35775 9.09025 9.02476 9.41064 8.85449C9.58475 8.76261 10.0891 8.67805 10.105 8.66895C10.5622 8.59931 11.2639 8.55372 12.0337 8.55371ZM4.97021 8.59375C5.31429 8.59375 5.59326 8.87639 5.59326 9.22559L5.76416 12.2783C5.76401 12.7232 5.40861 13.083 4.97021 13.083C4.53196 13.0828 4.17642 12.7231 4.17627 12.2783L4.34814 9.22559C4.34814 8.87649 4.62628 8.59392 4.97021 8.59375ZM4.9751 2C5.17235 2.00009 5.37016 2.06212 5.53076 2.18555C5.64155 2.26552 5.8208 2.44922 5.8208 2.44922C6.51436 3.09946 7.55852 4.71587 7.89404 5.53613C7.90106 5.53613 8.1 6.02377 8.10791 6.25586V6.28711C8.10785 6.64255 7.90916 6.97528 7.58936 7.14551C7.41578 7.23789 6.91477 7.32153 6.896 7.33105C6.43887 7.40072 5.73719 7.44725 4.96729 7.44727C4.15898 7.44727 3.42626 7.40095 2.97607 7.31543C2.959 7.31346 2.55613 7.22977 2.42041 7.17578C2.22232 7.09109 2.05465 6.93625 1.94775 6.74316C1.87129 6.58876 1.8335 6.42531 1.8335 6.25586C1.84059 6.07779 1.95521 5.74586 2.0083 5.61426C2.34379 4.74721 3.44181 3.09128 4.11279 2.45703C4.21967 2.34857 4.34897 2.23205 4.37939 2.20117C4.54714 2.06973 4.75352 2 4.9751 2ZM12.0298 2.91699C12.4682 2.91702 12.8228 3.27766 12.8228 3.72266L12.6528 6.77441C12.6527 7.12345 12.3737 7.40622 12.0298 7.40625C11.6859 7.40612 11.4069 7.12339 11.4067 6.77441L11.2358 3.72266C11.2358 3.27772 11.5914 2.91712 12.0298 2.91699Z"
                                        fill="#3C3836"></path>
                                </svg>
                              </span>
                            Sort by
                        </button>
                        <button data-id="{{ $product->id }}" class="sort-btn text-base tracking-[0.2px] leading-[1.375] transition-all duration-200 flex gap-2 items-center justify-center py-2 px-[18px] border border-[#F8F1F0] rounded-full">
                      <span class="w-4 h-4 flex items-center justify-center">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M10.6956 8.54699C10.5229 8.71433 10.4436 8.95633 10.4829 9.19366L11.0756 12.4737C11.1256 12.7517 11.0083 13.033 10.7756 13.1937C10.5476 13.3603 10.2443 13.3803 9.99559 13.247L7.04292 11.707C6.94026 11.6523 6.82626 11.623 6.70959 11.6197H6.52892C6.46626 11.629 6.40493 11.649 6.34893 11.6797L3.39559 13.227C3.24959 13.3003 3.08426 13.3263 2.92226 13.3003C2.52759 13.2257 2.26426 12.8497 2.32893 12.453L2.92226 9.17299C2.96159 8.93366 2.88226 8.69033 2.70959 8.52033L0.302258 6.18699C0.100925 5.99166 0.030925 5.69833 0.122925 5.43366C0.212258 5.16966 0.440258 4.97699 0.715592 4.93366L4.02892 4.45299C4.28093 4.42699 4.50226 4.27366 4.61559 4.04699L6.07559 1.05366C6.11026 0.986992 6.15493 0.925659 6.20893 0.873659L6.26892 0.826992C6.30026 0.792326 6.33626 0.763659 6.37626 0.740326L6.44893 0.713659L6.56226 0.666992H6.84293C7.09359 0.692992 7.31426 0.842992 7.42959 1.06699L8.90893 4.04699C9.01559 4.26499 9.22293 4.41633 9.46226 4.45299L12.7756 4.93366C13.0556 4.97366 13.2896 5.16699 13.3823 5.43366C13.4696 5.70099 13.3943 5.99433 13.1889 6.18699L10.6956 8.54699Z"
                                fill="#74CA45"></path>
                        </svg>
                      </span>
                            5
                        </button>
                        <button data-id="{{ $product->id }}" class="sort-btn text-base tracking-[0.2px] leading-[1.375] transition-all duration-200 flex gap-2 items-center justify-center py-2 px-[18px] border border-[#F8F1F0] rounded-full">
                      <span class="w-4 h-4 flex items-center justify-center">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M10.6956 8.54699C10.5229 8.71433 10.4436 8.95633 10.4829 9.19366L11.0756 12.4737C11.1256 12.7517 11.0083 13.033 10.7756 13.1937C10.5476 13.3603 10.2443 13.3803 9.99559 13.247L7.04292 11.707C6.94026 11.6523 6.82626 11.623 6.70959 11.6197H6.52892C6.46626 11.629 6.40493 11.649 6.34893 11.6797L3.39559 13.227C3.24959 13.3003 3.08426 13.3263 2.92226 13.3003C2.52759 13.2257 2.26426 12.8497 2.32893 12.453L2.92226 9.17299C2.96159 8.93366 2.88226 8.69033 2.70959 8.52033L0.302258 6.18699C0.100925 5.99166 0.030925 5.69833 0.122925 5.43366C0.212258 5.16966 0.440258 4.97699 0.715592 4.93366L4.02892 4.45299C4.28093 4.42699 4.50226 4.27366 4.61559 4.04699L6.07559 1.05366C6.11026 0.986992 6.15493 0.925659 6.20893 0.873659L6.26892 0.826992C6.30026 0.792326 6.33626 0.763659 6.37626 0.740326L6.44893 0.713659L6.56226 0.666992H6.84293C7.09359 0.692992 7.31426 0.842992 7.42959 1.06699L8.90893 4.04699C9.01559 4.26499 9.22293 4.41633 9.46226 4.45299L12.7756 4.93366C13.0556 4.97366 13.2896 5.16699 13.3823 5.43366C13.4696 5.70099 13.3943 5.99433 13.1889 6.18699L10.6956 8.54699Z"
                                fill="#74CA45"></path>
                        </svg>
                      </span>
                            4
                        </button>
                        <button data-id="{{ $product->id }}" class="sort-btn text-base tracking-[0.2px] leading-[1.375] transition-all duration-200 flex gap-2 items-center justify-center py-2 px-[18px] border border-[#F8F1F0] rounded-full">
                      <span class="w-4 h-4 flex items-center justify-center">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M10.6956 8.54699C10.5229 8.71433 10.4436 8.95633 10.4829 9.19366L11.0756 12.4737C11.1256 12.7517 11.0083 13.033 10.7756 13.1937C10.5476 13.3603 10.2443 13.3803 9.99559 13.247L7.04292 11.707C6.94026 11.6523 6.82626 11.623 6.70959 11.6197H6.52892C6.46626 11.629 6.40493 11.649 6.34893 11.6797L3.39559 13.227C3.24959 13.3003 3.08426 13.3263 2.92226 13.3003C2.52759 13.2257 2.26426 12.8497 2.32893 12.453L2.92226 9.17299C2.96159 8.93366 2.88226 8.69033 2.70959 8.52033L0.302258 6.18699C0.100925 5.99166 0.030925 5.69833 0.122925 5.43366C0.212258 5.16966 0.440258 4.97699 0.715592 4.93366L4.02892 4.45299C4.28093 4.42699 4.50226 4.27366 4.61559 4.04699L6.07559 1.05366C6.11026 0.986992 6.15493 0.925659 6.20893 0.873659L6.26892 0.826992C6.30026 0.792326 6.33626 0.763659 6.37626 0.740326L6.44893 0.713659L6.56226 0.666992H6.84293C7.09359 0.692992 7.31426 0.842992 7.42959 1.06699L8.90893 4.04699C9.01559 4.26499 9.22293 4.41633 9.46226 4.45299L12.7756 4.93366C13.0556 4.97366 13.2896 5.16699 13.3823 5.43366C13.4696 5.70099 13.3943 5.99433 13.1889 6.18699L10.6956 8.54699Z"
                                fill="#74CA45"></path>
                        </svg>
                      </span>
                            3
                        </button>
                        <button data-id="{{ $product->id }}" class="sort-btn text-base tracking-[0.2px] leading-[1.375] transition-all duration-200 flex gap-2 items-center justify-center py-2 px-[18px] border border-[#F8F1F0] rounded-full active">
                      <span class="w-4 h-4 flex items-center justify-center">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M10.6956 8.54699C10.5229 8.71433 10.4436 8.95633 10.4829 9.19366L11.0756 12.4737C11.1256 12.7517 11.0083 13.033 10.7756 13.1937C10.5476 13.3603 10.2443 13.3803 9.99559 13.247L7.04292 11.707C6.94026 11.6523 6.82626 11.623 6.70959 11.6197H6.52892C6.46626 11.629 6.40493 11.649 6.34893 11.6797L3.39559 13.227C3.24959 13.3003 3.08426 13.3263 2.92226 13.3003C2.52759 13.2257 2.26426 12.8497 2.32893 12.453L2.92226 9.17299C2.96159 8.93366 2.88226 8.69033 2.70959 8.52033L0.302258 6.18699C0.100925 5.99166 0.030925 5.69833 0.122925 5.43366C0.212258 5.16966 0.440258 4.97699 0.715592 4.93366L4.02892 4.45299C4.28093 4.42699 4.50226 4.27366 4.61559 4.04699L6.07559 1.05366C6.11026 0.986992 6.15493 0.925659 6.20893 0.873659L6.26892 0.826992C6.30026 0.792326 6.33626 0.763659 6.37626 0.740326L6.44893 0.713659L6.56226 0.666992H6.84293C7.09359 0.692992 7.31426 0.842992 7.42959 1.06699L8.90893 4.04699C9.01559 4.26499 9.22293 4.41633 9.46226 4.45299L12.7756 4.93366C13.0556 4.97366 13.2896 5.16699 13.3823 5.43366C13.4696 5.70099 13.3943 5.99433 13.1889 6.18699L10.6956 8.54699Z"
                                fill="#74CA45"></path>
                        </svg>
                      </span>
                            2
                        </button>
                    </div>
                    <div id="sectionRatingProduct">
                        @include('theme::front-end.modals.product_rating_inner')
                    </div>
                </div>
            </div>
    </div>
    <!-- Quantity and Add to Order -->
    <div class="px-4 py-3 -shadow flex gap-6 items-center justify-between">
        <div class="flex items-center border rounded-full p-3">
            <button id="decreaseBtn" class="w-6 h-6 text-lg flex items-center justify-center">
                -
            </button>
            <span id="quantity" class="mx-4 text-lg font-normal">1</span>
            <button id="increaseBtn" class="w-6 h-6 text-lg flex items-center justify-center">
                +
            </button>
        </div>
        <button
                id="addToOrderBtn" data-id="{{ $product->id }}" data-store="{{ $product->store_id }}"
                class="bg-primary text-white w-full py-3 rounded-full hover:bg-primary-700">
            Add to order • {{ number_format((float) $product->price, 0, '.', '') }}&nbsp;Ft
        </button>
        <input type="hidden" name="inputPrice" id="inputPrice"
               value="{{ number_format((float) $product->price, 0, '.', '') }}">
    </div>

    <script>
        if (typeof reviewsExpanded === 'undefined') {
            var reviewsExpanded = false;
        }

        function toggleReviews() {
            const content = document.getElementById('reviewsContent');
            const icon = document.getElementById('expandIcon');

            reviewsExpanded = !reviewsExpanded;

            if (reviewsExpanded) {
                content.classList.add('expanded');
                icon.classList.add('expanded');
            } else {
                content.classList.remove('expanded');
                icon.classList.remove('expanded');
            }
        }

        function toggleDescription() {
            const description = document.getElementById('product-description');
            const showMoreText = document.getElementById('show-more-text');

            if (description.classList.contains('line-clamp-2')) {
                description.classList.remove('line-clamp-2');
                showMoreText.textContent = 'show less';
            } else {
                description.classList.add('line-clamp-2');
                showMoreText.textContent = 'show more';
            }
        }
    </script>

@endif