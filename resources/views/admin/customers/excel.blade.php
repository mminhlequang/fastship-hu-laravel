<table class="table-layout table table-striped table-bordered">
    <tr>
        <th>STT</th>
        <th>{{ trans('customers.name') }}</th>
        <th>{{ trans('customers.email') }}</th>
        <th>{{ trans('customers.phone') }}</th>
        <th>{{ __('Ngày sinh') }}</th>
        <th>{{ __('message.user.address') }}</th>
        <th>{{ __('Tỉnh') }}</th>
        <th>{{ __('Huyện') }}</th>
        <th>{{ __('Xã') }}</th>
        <th>{{ trans('promotions.name') }}</th>
        <th>{{ trans('Thông tin khác') }}</th>
    </tr>
    @php($i = 0)
    @foreach($data as $item)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->email ?? "" }}</td>
            <td>{{ $item->phone }}</td>
            <td>{{ \Carbon\Carbon::parse($item->birthday)->format(config('settings.format.date')) }}</td>
            <td>{{ $item->address ?? ""}}</td>
            <td>{{ optional($item->province)->name ?? ""}}</td>
            <td>{{ optional($item->district)->name ?? ""}}</td>
            <td>{{ optional($item->ward)->name ?? ""}}</td>
            <td>{{ optional($item->promotion)->name ?? ""}}</td>
            <td>
                @if($item->input != null)
                @foreach(json_decode($item->input, true) as $keyI => $valueI)
                @if($keyI != 'form-embed')
                {{ str_replace('_', ' ', $keyI) }}:
                {{ $valueI }}
                <br>
                @endif
                @endforeach
                @endif
            </td>
        </tr>
    @endforeach
</table>