<div class="box-body">
    <div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
        {!! Form::label('name', trans('message.role.name'), ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group{{ $errors->has('label') ? ' has-error' : ''}}">
        {!! Form::label('label', trans('message.role.label'), ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('label', null, ['class' => 'form-control']) !!}
            {!! $errors->first('label', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    @php($rolePermission = isset($role)?$role->Permissions->pluck('name')->toArray():[])
    <div class="form-group{{ $errors->has('label') ? ' has-error' : ''}}">
        <div class="col-xs-12">
            <h5 class="alert alert-info alert-dismissible">{{trans('message.role.permissions')}}</h5>
        </div>
        <div class="clearfix"></div>
        {{-- Tao table check book--}}
        <div class="col-xs-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">{{trans('message.index')}}</th>
                        <th scope="col" class="text-center">{{trans('message.role.function')}}</th>
                        <th scope="col" class="text-center">{{trans('message.view')}}</th>
                        <th scope="col" class="text-center">{{trans('message.add')}}</th>
                        <th scope="col" class="text-center">{{trans('message.edit')}}</th>
                        <th scope="col" class="text-center">{{trans('message.delete')}}</th>
                        <th scope="col" class="text-center">{{trans('message.role.approved')}}</th>
                        <th class="text-center">
                            <input type="checkbox" name="chkAll" id="chkAll" />
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php ($counter=0)
                    @php ($strModule="")
                    @php ($arrPers="")
                    @php ($index="")
                    @php ($create="")
                    @php ($update="")
                    @php ($destroy="")
                    @php ($active="")
                    @php ($rchk="")

                    @php ($test = $permissions->pluck('name'))
                    @for ($i = 0; $i < count($permissions); $i++) @php ($permission=$permissions[$i]) @php ($arrPers=explode("@", $permission ->name))

                        @if(!empty($arrPers) && count($arrPers) > 1)
                        {{-- Kiem tra neu la module moi thi khoi tao dong moi va cac cot--}}
                        @if(strcmp($strModule, $arrPers[0])!=0)
                        @php ($counter++)
                        @php($strModule = $arrPers[0])
                        @if(in_array($strModule."@index", $rolePermission)||(strcmp($strModule, "ReportsController")==0 && in_array("ReportsController@numberBookingByDate", $rolePermission)))
                        @php($index = 'checked')
                        @endif
                        @if(in_array($strModule."@store", $rolePermission))
                        @php($create = 'checked')
                        @endif
                        @if(in_array($strModule."@update", $rolePermission))
                        @php($update = 'checked')
                        @endif
                        @if(in_array($strModule."@destroy", $rolePermission))
                        @php($destroy = 'checked')
                        @endif
                        @if(in_array($strModule."@active", $rolePermission))
                        @php($active = 'checked')
                        @endif
                        @if(strcmp($index, "checked") == 0 && strcmp($create, "checked") == 0 &&
                        strcmp($update, "checked") ==0 && strcmp($destroy, "checked") == 0 &&
                        strcmp($active, "checked") ==0)
                        @php($rchk = 'checked')
                        @else
                        @php($rchk = "")
                        @endif
                        <tr>
                            <td class="text-center"><span>{{ $counter }}</span></td>
                            <td><span>{{ $permission->label }}</span></td>
                            <td class="text-center">
                                <div class="custom-control custom-checkbox">
                                    @if($test->contains($strModule."@index") || $test->contains($strModule."@numberBookingByDate"))
                                    <input type="checkbox" class="custom-control" name="permissions[]" id={{$strModule}} {{ $index }} value={{$strModule."@index"}}>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-checkbox">
                                    @if($test->contains($strModule."@store"))
                                    <input type="checkbox" class="custom-control" name="permissions[]" id={{$strModule}} {{ $create }} value={{$strModule."@store"}}>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-checkbox">
                                    @if($test->contains($strModule."@update"))
                                    <input type="checkbox" class="custom-control" name="permissions[]" id={{$strModule}} {{ $update }} value={{$strModule."@update"}}>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-checkbox">
                                    @if($test->contains($strModule."@destroy"))
                                    <input type="checkbox" class="custom-control" name="permissions[]" id={{$strModule}} {{ $destroy }} value={{$strModule."@destroy"}}>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-checkbox">
                                    @if($test->contains($strModule."@active"))
                                    <input type="checkbox" class="custom-control" name="permissions[]" id={{$strModule}} {{ $active }} value={{$strModule."@active"}}>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="chkRole" id="chkRole" {{$rchk}} onclick="ActiveModule($(this), '{{$strModule}}');" />
                                </div>
                            </td>
                        </tr>
                        @php ($index="")
                        @php ($create="")
                        @php ($update="")
                        @php ($destroy="")
                        @php ($active="")
                        @endif

                        @elseif(strcmp($permission->name, "SettingController")==0)
                        @php ($counter++)
                        @if(in_array($permission->name, $rolePermission))
                        @php($index = 'checked')
                        @endif
                        <tr>
                            <td class="text-center"><span>{{ $counter }}</span></td>
                            <td><span>{{ $permission->label }}</span></td>
                            <td class="text-center">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control" name="permissions[]" id={{$permission->name}} {{ $index }} value={{$permission->name}}>
                                </div>
                            </td>
                            <td class="text-center">
                            </td>
                            <td class="text-center">
                            </td>
                            <td class="text-center">
                            </td>
                            <td class="text-center">
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="chkRole" id="chkRole" {{$index}} onclick="ActiveModule($(this), '{{$permission->name}}');" />
                                </div>
                            </td>
                        </tr>
                        @endif
                        @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="box-footer">
    {!! Form::button('<i class="fa fa-check-circle"></i> ' . $text = isset($submitButtonText) ? $submitButtonText : __('message.new_add'), ['class' => 'btn btn-info mr-2', 'type'=>'submit']) !!}
</div>
@section('scripts-footer')
<script type="text/javascript">
    function ActiveModule(parent, chkName) {
        $("input[id=" + chkName + "]").prop('checked', parent.prop("checked"));
    }
    $(function() {
        $('#chkAll').on('click', function(e) {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
        });
        $('form').submit(function(e) {
            var $lst = $("input[name='" + "permissions[]" + "']:not(:checked)");
            $lst.prop('name', "p1");
            return true;
        });
    })
</script>
@endsection