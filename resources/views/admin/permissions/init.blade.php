@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">KHỞI TẠO CÁC QUYỀN HẠN</div>
                    <div class="panel-body">

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::open(['method' => 'POST', 'url' => ['/admin/permissions-init'], 'class' => 'form-horizontal']) !!}
                        <table class="table">
                            <tr>
                                <th>Index</th>
                                <th>Action Controller</th>
                                <th>Tên phân quyền</th>
                                <th></th>
                            </tr>
                        @foreach($actionControllers as $index=>$item)
                            <tr>
                                <th>{{ $index + 1 }}</th>
                                <td>{{ $item }}</td>
                                <td>
                                    {!! Form::text('label['.$item.']', isset($actionOK[$item])?$actionOK[$item]:'', ['class' => 'form-control input-sm']) !!}
                                </td>
                                <th>
                                    {!! Form::checkbox('select['.$item.']', 1, isset($actionOK[$item])?true:false) !!}
                                </th>
                            </tr>
                        @endforeach
                        </table>
                        <div class="clearfix"></div>

                        {!! Form::submit('Tạo quyền', ['class' => 'btn btn-primary']) !!}

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection