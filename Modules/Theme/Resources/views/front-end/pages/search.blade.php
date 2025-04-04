@extends('theme::front-end.master')

@section('content')
    <main>
        <section id="all-restaurants"
                 class="flex flex-col gap-10 pb-12 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 ">
            <div class="flex space-x-4">
                <h2 data-type="1"
                    class="cursor-pointer selectType text-2xl font-medium mt-8 {{ ($type == 1) ? 'text-black border-b-2 border-black' : 'text-gray-500' }}">
                    All restaurants </h2>
                <h2 data-type="2"
                    class="cursor-pointer selectType text-2xl font-medium mt-8 {{ ($type == 2) ? 'text-black border-b-2 border-black' : 'text-gray-500' }}">
                    All food</h2>
            </div>
            <div id="sectionData">
                @if($type == 1)
                    @include('theme::front-end.ajax.stores')
                @else
                    @include('theme::front-end.ajax.products')
                @endif
            </div>
        </section>
    </main>

@endsection
@section('script')
    <script type="text/javascript">
        $('body').on('click', '.selectType', function (e) {
            e.preventDefault();
            $('.loading').addClass('loader');
            $('.selectType').removeClass('text-black border-b-2 border-black').addClass('text-gray-500');
            let type = $(this).data('type');
            $(this).addClass('text-black border-b-2 border-black').removeClass('text-gray-500');
            $.ajax({
                url: "{{ url('ajaxFE/searchData') }}",
                type: "GET",
                data: {
                    type: type,
                    min_price: '{{ \Request::get('min_price') }}',
                    max_price: '{{ \Request::get('max_price') }}',
                    keywords: '{{ \Request::get('keywords') }}',
                    categories: '{{ \Request::get('categories') }}'
                },
                success: function (res) {
                    $('#sectionData').html(res);
                    loadSkeleton();
                    $('.loading').removeClass('loader');
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        });
    </script>
@endsection