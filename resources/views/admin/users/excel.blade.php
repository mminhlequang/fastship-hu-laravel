<table class="table-layout table table-striped table-bordered">
    <tr>
        <th>STT</th>
        <th>{{ trans('customers.name') }}</th>
        <th>{{ trans('customers.email') }}</th>
        <th>{{ trans('customers.phone') }}</th>
        <th>{{ __('message.user.address') }}</th>

    </tr>
    @php($i = 0)
    @foreach($data as $item)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->email ?? "" }}</td>
            <td>{{ optional($item->profile)->phone }}</td>
            <td>{{ optional($item->profile)->address ?? ""}}</td>
        </tr>
    @endforeach
</table>