@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Give Permission to Role</div>
                    <div class="panel-body">

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::open(['method' => 'POST', 'url' => ['/admin/give-role-permissions'], 'class' => 'form-horizontal']) !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
                            {!! Form::label('name', 'Role: ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                <select class="roles form-control" onchange="setPermissions(this)" id="role" name="role">
                                    @foreach($roles as $role)
                                    @php
                                        $arIDPermis = array();
                                        foreach ($role->Permissions as $p) {
                                            $arIDPermis[] = $p->name;
                                        }
                                    @endphp
                                    <option data-permistions="{{ implode(",",$arIDPermis) }}" value="{{ $role->name }}">{{ $role->label }}</option>
                                    @endforeach()
                                </select>
                                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('label') ? ' has-error' : ''}}">
                            {!! Form::label('label', 'Permissions: ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                <select class="permissions form-control" id="permissions" name="permissions[]" multiple="multiple">
                                    @foreach($permissions as $permission)
                                    <option value="{{ $permission->name }}">{{ $permission->label }}</option>
                                    @endforeach()
                                </select>
                                {!! $errors->first('label', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-4">
                                {!! Form::submit('Grant', ['class' => 'btn btn-primary']) !!}
                            </div>
                        </div>
                        {!! Form::close() !!}
                        
                        <script type="text/javascript">
                            function setPermissions(ob){
                                var rolePermistions = ob.options[ob.selectedIndex].getAttribute('data-permistions');
                                var arRolePermistions = rolePermistions.split(",");
                                var permission = document.getElementById("permissions");
                                for(var i = 0; i < permission.length; i++){
                                    if(arRolePermistions.indexOf(permission.options[i].value)!=-1){
                                        permission.options[i].selected = true;
                                    }else{
                                        permission.options[i].selected = false;
                                    }
                                }
                            }
                            setPermissions(document.getElementById("role"));
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection