@extends('theme::front-end.master')

@section('content')
    <main>
        <section
                class="px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 flex flex-wrap items-center justify-between">
            <div class="text-3xl font-medium mb-6 text-black py-3 mt-3"> Your favorites</div>
            <div class="flex flex-wrap justify-center overflow-x-auto no-scrollbar">
                <button data-type="1" class="selectType px-4 py-3 text-gray-500 whitespace-nowrap hover:text-secondary"
                > Favorite restaurant
                </button>
                <button data-type="2" class="selectType px-4 py-3 text-black whitespace-nowrap hover:text-secondary"
                > Favorite food
                </button>
            </div>
        </section>
        <section id="all-restaurants"
                 class="flex flex-col gap-10 pb-12 px-4 lg:px-6 xl:px-10 2xl:px-40 3xl:px-60 4xl:px-80 relative">
            <div id="sectionData">
                @include('theme::front-end.ajax.products')
            </div>
        </section>
    </main>
    <input type="hidden" name="type" id="inputType" value="1">
@endsection
@section('script')
    <script type="text/javascript">
        $('body').on('click', '.selectType', function (e) {
            e.preventDefault();
            $('.loading').addClass('loader');
            $('.selectType').removeClass('text-black').addClass('text-gray-500');
            let type = $(this).data('type');
            $('#inputType').val(type);
            $(this).addClass('text-black').removeClass('text-gray-500');
            $.ajax({
                url: "{{ url('ajaxFE/selectDataFavorite') }}",
                type: "GET",
                data: {
                    type: type
                },
                success: function (res) {
                    $('#sectionData').html(res);
                    loadSkeleton();
                    $('.loading').removeClass('loader');
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        });
    </script>
@endsection