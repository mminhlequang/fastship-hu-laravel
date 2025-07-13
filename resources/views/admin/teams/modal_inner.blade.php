<table id="dynamicTable" class="table table-striped table-bordered dynamicTable">
    <tr>
        <th>{{ __('Member') }}</th>
        <th>{{ __('Role') }}</th>
        <th></th>
    </tr>
    @if(isset($playersJoin) && count($playersJoin) > 0)
        @foreach($playersJoin as $keyP => $itemP)
            <tr class="tr-add-{{ $itemP->id }}">
                <td>
                    {{ $itemP->name. '-'.$itemP->phone }}
                </td>
                <td>
                    {{ $itemP->role }}
                </td>
                <td>
                    <a class="btn btn-sm btn-danger trash-item remove" data-id="{{ $itemP->id }}" data-type="1"><i
                                class="fa fa-times fa-fw"></i></a>
                </td>
            </tr>
        @endforeach
        <tr>
            <td>
                <select class="form-control input-sm selectPlayer select2-hidden-accessible"
                        name="players[0][player_id]" tabindex="-1" aria-hidden="true">
                </select>
            </td>
            <td>
                <select name="players[0][type]" class="form-control input-sm select2">
                    @foreach(\App\Models\Team::$TYPE as $keyR => $itemR)
                        <option value="{{ $keyR }}">{{ $itemR }}</option>
                    @endforeach
                </select>
            </td>
            <td><a class="btn btn-md btn-info addYearBtn" href="javascript:;" title="Thêm"><i class="fa fa-plus-circle"
            </td>
        </tr>
    @else
        <tr>
            <td><select class="form-control input-sm select2 selectPlayer select2-hidden-accessible"
                        name="players[0][player_id]" tabindex="-1" aria-hidden="true">
                </select>
            </td>
            <td>
                <select name="players[0][type]" class="form-control input-sm select2">
                    @foreach(\App\Models\Team::$TYPE as $keyR => $itemR)
                        <option value="{{ $keyR }}">{{ $itemR }}</option>
                    @endforeach
                </select>
            </td>
            <td><a class="btn btn-md btn-info addYearBtn" href="javascript:;" title="Thêm"><i class="fa fa-plus-circle"
            </td>
        </tr>
    @endif
</table>
