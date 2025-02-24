<div class="box-body">

    <div>
        <input type="hidden" name="back_url" value="{{ !empty($backUrl) ? $backUrl : '' }}">
    </div>
    <table class="table table-condensed">
        <tr class="row">
            <th colspan="2">
                {{ __('theme::bookings.customer_info') }}
            </th>
        </tr>
        <tr class="row {{ $errors->has('customer_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('customer_id', trans('message.customers'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('customer_id', $customers, null, ['class' => 'form-control input-sm select2', 'id' => 'customer']) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('address_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('address_id', trans('address_delivery.name'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('address_id', $address ?? [], null, ['class' => 'form-control input-sm select2', 'id' => 'address']) !!}
            </td>
        </tr>
        <!--BOOKING INFO-->
        <tr class="row">
            <th colspan="2">
                {{ __('theme::bookings.booking_info') }}
            </th>
        </tr>
        <tr class="row {{ $errors->has('note') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('product_id', trans('theme::products.product'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('product_id', !empty($allProducts) ? $allProducts : null, null, ['class' => 'form-control input-sm select2']) !!}
                {!! $errors->first('product_id', '<p class="help-block">:message</p>') !!}
                <div id="products-js" style="display: {{ isset($products) ? 'block' : 'none' }}; margin-top: 5px;">
                    <table class="table" id="products-table-js">
                        <thead>
                        <tr class="row">
                            <th class="col-md-4">
                                {{ __('theme::products.product') }}
                            </th>
                            <th class="col-md-2">
                                {{ __('theme::products.price') }}
                            </th>
                            <th class="col-md-2">
                                {{ __('theme::products.amount') }}
                            </th>
                            <th class="col-md-2">
                                {{ __('theme::products.amount') }}
                            </th>
                            <th></th>

                        </thead>
                        <tbody>
                        @if (isset($products))
                            @foreach ($products as $item)
                                <tr id="result-{{ $item->product->id }}" class="row">
                                    <td class="col-md-4">
                                        {{ $item->product->name }}
                                    </td>
                                    <td class="col-md-2">

                                        {{ number_format($item->product->price) ?? ''}}
                                    </td>
                                    <td class="col-md-2">
                                        <input type="number" id="amount" data-id="{{ $item->product->id }}"
                                               data-price="{{ $item->product->price }}"
                                               class="form-control form-control-sm btn-js" min="1"
                                               name="product[{{ $item->product->id }}][quantity]"
                                               value="{{ $item->quantity }}"/>
                                        <input type="hidden" name="qty-js-{{ $item->product->id }}"
                                               value="{{ $item->quantity }}">
                                    </td>

                                    <td class="col-md-2" id="total-price{{ $item->product->id }}">
                                        {{ number_format($item->product->price * $item->quantity) }} đ
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="delProduct({{ $item->product->id }})">
                                            <i class="far fa-trash-alt" aria-hidden="true"></i>
                                        </button>
                                        <input type="hidden" name="price-js-{{ $item->product->id }}"
                                               value="{{ $item->product->price * $item->quantity }}">
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        <tr class="row">
                            <th colspan="3" class="col-md-10">{{ __('Tổng tiền') }}</th>
                            <th colspan="2" class="col-md-2" id="total-js">
                                {{ number_format($booking->total_price)  }} đ
                            </th>
                            <input type="hidden" name="amount"
                                   value="{{ !empty($booking->amount) ? json_decode($booking->amount)->total_price : 0 }}"/>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('note') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('note', trans('theme::bookings.note'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('note', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('note', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('approve_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('approve_id', trans('theme::bookings.approve'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('approve_id', !empty($approves) ? $approves : null, null, ['class' => 'form-control input-sm select2']) !!}
                {!! $errors->first('approve_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('payment_type') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('payment_type', trans('theme::bookings.payment'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('payment_type', config('settings.payment') ?? [], null, ['class' => 'form-control input-sm select2']) !!}
            </td>
        </tr>

    </table>
</div>
<div class="box-footer">
    <button type="submit" name="approved" class="btn btn-primary"
            style="margin-right: 5px;">{{ __('message.save') }}</button>
    <a href="{{ !empty($backUrl) ? $backUrl : url('/admin/bookings/') }}"
       class="btn btn-secondary">{{ __('message.close') }}</a>
</div>
@section('scripts-footer')
    <script>
        $("#customer").change(function () {
            var customer = $(this).val();
            $.ajax({
                url: "{{ url('ajax/getAddress') }}",
                type: "GET",
                data: {
                    id: customer
                },
                dataType: "json",
                success: function (data) {
                    $('#address').children().remove().end().append(
                        '<option value="">{{ trans('message.please_select') }}</option>');
                    $.each(data, function (key, value) {
                        $("#address").append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                }
            });
        });
    </script>
    <script type="text/javascript">
        function formatNumber(amount) {
            const roundedAmount = Math.round(amount).toString();
            const segments = [];
            for (let i = roundedAmount.length; i > 0; i -= 3)
                segments.unshift(roundedAmount.substring(Math.max(0, i - 3), i));
            const formattedAmount = segments.join(',');
            return formattedAmount;
        }

        $('body').on('change', '#product_id', function () {
            var productId = $(this).val();
            var amount = $('[name="amount"]').val();
            $('#products-js').slideDown();
            axios.get('{{ url('ajax/getProduct?id=') }}' + productId)
                .then((res) => {
                    let product = res.data;
                    let html = '';
                    html += `<tr id="result-` + product.id + `" class="row">` +
                        `<td class="col-md-4">` + product.name + `</td>` +
                        `<td class="col-md-2">` + formatNumber(product.price) + `</td>` +
                        `<td class="col-md-2">` +
                        `<input type="number" id="amount" data-id="` + product.id + `" data-price="` + product.price + `" class="form-control form-control-sm btn-js"
                                min="1" onkeyup="updateQty(this,` + product.id + `,` + product.price +
                        `)" value="1" name="product[` + product.id + `][quantity]" onclick="updateQty(this,` +
                        product.id + `,` + product.price + `)"/>` +
                        `<input type="hidden" name="qty-js-` + product.id + `" value="1">` +
                        `</td>` +
                        `<td class="col-md-2" id="total-price` + product.id + `">` + formatNumber(product.price) + ` đ</td>` +
                        `<td>` +
                        `<button type="button" class="btn btn-sm btn-danger" onclick="delProduct(` + product
                            .id + `)">
                                    <i class="far fa-trash-alt" aria-hidden="true"></i>
                                </button>` +
                        `<input type="hidden" name="price-js-` + product.id + `" value="` + formatNumber(product.price) +
                        `">` +
                        `</td>` +
                        `</tr>`;

                    $('#products-table-js tbody').append(html);
                    let total = parseInt(amount) + parseInt(product.price);
                    $('#total-js').html(formatNumber(total) + '&nbsp;₫');
                    $('[name="amount"]').attr('value', total);
                })
                .catch(e => {
                    alert('Có lỗi xảy ra. Vui lòng kiểm tra lại!');
                })
        });

        function updateQty(ob, id, price) {
            var qty = ob.value;
            if (qty < 1) return alert('Vui lòng nhập số lượng lớn hơn 0!');
            changeQtyPrice(id, qty, price);
        }

        $('body').on('click', '#amount', function () {
            var id = $(this).data("id");
            var price = $(this).data("price");
            var qty = $(this).val();
            if (qty < 1) return alert('Vui lòng nhập số lượng lớn hơn 0!');
            changeQtyPrice(id, qty, price);
        });

        function changeQtyPrice(id, qty, price) {
            var total = $('[name="amount"]').val();
            var hidQty = $('[name="qty-js-' + id + '"]').val();
            console.log(total);

            var amount = qty * price;
            $('[name="qty-js-' + id + '"]').attr('value', qty);
            qty = qty - hidQty;
            total = parseInt(total) + parseInt(price * qty);
            $('#total-price' + id).html(formatNumber(amount) + '&nbsp;₫');
            $('#total-js').html(formatNumber(total) + '&nbsp;₫');
            $('[name="price-js-' + id + '"]').attr('value', amount);
            $('[name="amount"]').attr('value', total);
        }

        function delProduct(id) {
            var total = $('[name="amount"]').val();
            var amount = $('[name="price-js-' + id + '"]').val();
            $('#result-' + id).remove();
            $('#total-js').html(formatNumber(total - amount) + '&nbsp;₫');
            $('[name="amount"]').attr('value', total - amount);
        }
    </script>
@endsection
