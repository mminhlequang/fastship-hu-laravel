<div class="col-12 col-lg-3 sidebar">
    <div class="row">
        <div class="col-12 col-sm-6 col-lg-12">
            <h2 class="sidebar-title text-uppercase">
                <i class="fas fa-bars"></i>{{ trans('theme::frontend.news_focus') }}
            </h2>
            @foreach($newsFocusSidebar as $item)
                <div class="row box-row mb-15 d-flex align-items-center">
                    <div class="col-5 col-sm-4 col-md-3 col-lg-5">
                        <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html" class="image-responsive">
                            <img class="image-responsive--lg lazyload" data-src="{{ asset($item->image) }}" alt="{{ $item->title }}">
                        </a>
                    </div>
                    <div class="col-7 col-sm-8 col-md-9 col-lg-7 pl-0">
                        <a class="box-info--link" href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html"
                           title="{{ $item->title }}">
                            {{ \Illuminate\Support\Str::limit($item->title, 50) }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-12 col-sm-6 col-lg-12">
            <h2 class="sidebar-title text-uppercase">
                {{ __('Sản phẩm bán chạy') }}
            </h2>
            @foreach($productFocusSidebar as $item)
                <div class="row box-row mb-15 d-flex align-items-center">
                    <div class="col-5 col-sm-4 col-md-3 col-lg-5">
                        <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html" class="image-responsive">
                            <img class="image-responsive--lg lazyload" data-src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                        </a>
                    </div>
                    <div class="col-7 col-sm-8 col-md-9 col-lg-7 product-info">
                        <div class="box-info">
                            <a class="box-info--link" href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html"
                               title="{{ $item->name }}">
                                {{ \Illuminate\Support\Str::limit($item->name, 50) }}
                            </a>
                        </div>
                        <div class="rating-product">
                            @php($average = Modules\Review\Entities\Review::with('product')->where(['product_id' => $item->id, 'active' => config('settings.active')])->avg('rating'))
                            @if(!empty($average))
                                @php($average = number_format($average,1))
                                @php($arr = explode('.',$average))
                                <div class="review-star">
                                    @for($i = 0; $i < 5; $i++)
                                        @php($w = '0%')
                                        @if($i < $arr[0])
                                            @php($w = 'auto')
                                        @elseif($i == $arr[0] && '0.'.$arr[1] == 0.5)
                                            @php($w = '50%')
                                        @endif
                                        <div class="rating-symbol"
                                             style="display: inline-block; position: relative;">
                                            <div class="rating-symbol-background far fa-star"
                                                 style="visibility: visible;"></div>
                                            <div class="rating-symbol-foreground"
                                                 style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: {{$w}};">
                                                <span class="fas fa-star "></span>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            @else
                                <div class="review-star">
                                    <div class="rating-symbol"
                                         style="display: inline-block; position: relative;">
                                        <div class="rating-symbol-background far fa-star"
                                             style="visibility: visible;"></div>
                                        <div class="rating-symbol-foreground"
                                             style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: auto;">
                                            <span class="fas fa-star "></span>
                                        </div>
                                    </div>
                                    <div class="rating-symbol"
                                         style="display: inline-block; position: relative;">
                                        <div class="rating-symbol-background far fa-star"
                                             style="visibility: visible;"></div>
                                        <div class="rating-symbol-foreground"
                                             style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: auto;">
                                            <span class="fas fa-star "></span>
                                        </div>
                                    </div>
                                    <div class="rating-symbol"
                                         style="display: inline-block; position: relative;">
                                        <div class="rating-symbol-background far fa-star"
                                             style="visibility: visible;"></div>
                                        <div class="rating-symbol-foreground"
                                             style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: auto;">
                                            <span class="fas fa-star "></span>
                                        </div>
                                    </div>
                                    <div class="rating-symbol"
                                         style="display: inline-block; position: relative;">
                                        <div class="rating-symbol-background far fa-star"
                                             style="visibility: visible;"></div>
                                        <div class="rating-symbol-foreground"
                                             style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: auto;">
                                            <span class="fas fa-star "></span>
                                        </div>
                                    </div>
                                    <div class="rating-symbol"
                                         style="display: inline-block; position: relative;">
                                        <div class="rating-symbol-background far fa-star"
                                             style="visibility: visible;"></div>
                                        <div class="rating-symbol-foreground"
                                             style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: auto;">
                                            <span class="fas fa-star "></span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="product-price">
                            {!! !empty($item->price_compare) ? '<span style="text-decoration: line-through; color: #333;"><small style="font-weight: 600;">' . number_format($item->price_compare) . trans('theme::frontend.unit').'</small></span> ' : '' !!}
                            <span class="mount">{{ number_format($item->price) }}{{ trans('theme::frontend.unit') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>