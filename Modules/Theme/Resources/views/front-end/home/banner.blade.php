<style>
  #banner.container-banner {
    padding-top: 1.875rem;
    padding-bottom: 1.875rem;
    display: flex;
    flex-direction: column;
  }

  @media (min-width: 1024px) {
    #banner.container-banner {
      flex-direction: row;
      flex-wrap: nowrap;
      align-items: center;
    }
  }
</style>

<section id="banner" class="responsive-px container-banner">
  <div class="lg:max-w-[530px] 2xl:max-w-[unset] flex flex-col gap-6">
    <div class="flex flex-col gap-2">
      <span class="text-primary hover:opacity-70 text-xl">{{ \App\Helper\LocalizationHelper::greetBasedOnTime() }}</span>
      <h1 class="text-5xl leading-[1.5] md:text-[64px] md:leading-[1.3] font-semibold inline-flex flex-col items-start">
        <span class="relative">
          {{ __('theme::web.header_banner_title1') }}
          <img alt="Fast Ship Hu"
            data-src="{{ url('assets/images/line_text_banner_1.svg') }}"
            class="h-[13px] absolute -bottom-1 right-0 lazyload" />
        </span>

        <span class="relative">
          {{ __('theme::web.header_banner_title2') }}
          <img alt="Fast Ship Hu"
            data-src="{{ url('assets/images/line_text_banner_2.svg') }}"
            class="h-[20px] absolute left-0 right-0 -bottom-1 w-full lazyload" />
        </span>
        <span>{{ __('theme::web.header_banner_title3') }}</span>
      </h1>
      <p class="text-[22px] leading-snug text-muted">
        {{ __('theme::web.header_banner_description') }}
      </p>
    </div>
    <form action="{{ url('search') }}" method="GET">
      <input type="hidden" name="type" value="1">
      <div class="flex items-center gap-1.5 py-2 pl-4 pr-2 rounded-full bg-white shadow">
        <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/map_banner_input_icon.svg') }}" class="w-6 h-6 lazyload" />
        <input autocomplete="off"
          type="text"
          class="flex-1 focus:outline-none" name="keywords"
          placeholder="{{ __('theme::web.header_banner_input') }}" />
        <button class="rounded-full py-2.5 px-8 bg-primary text-white hover:bg-primary-700">
          {{ __('theme::web.header_banner_input_button') }}
        </button>
      </div>
    </form>
    <div class="flex items-center gap-8 text-muted">
      <span class="flex items-center gap-1.5 cursor-pointer">
        <img alt="Fast Ship Hu" data-src="{{ url('assets/icons/gps_banner_icon.svg') }}" class="w-6 h-6 lazyload" />
        <u>{{ __('theme::web.header_banner_share') }}</u>
      </span>
      <span class="flex items-center gap-1.5 cursor-pointer" onclick="toggleModal('modalOverlayLogin')">
        <u>{{ __('theme::web.header_banner_share_address') }}</u>
      </span>
    </div>
  </div>
  <div class="inline-flex flex-1 mt-5 md:mt-0">
    <img alt="Fast Ship Hu" data-src="{{ url('assets/images/banner_img.svg') }}" class="w-full lazyload" />
  </div>
</section>