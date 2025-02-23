<!-- Exel Error -->
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="fa fa-fw fa-check"></i>
        @foreach ($errors->all() as $error)
            {{ $error }} <br>
        @endforeach
    </div>
@endif
@if (session()->has('errorsss'))
    <div class="alert alert-info">
        <p>
            <span class="bell fa fa-bell"></span>
            {{ session('errorsss') }}
        </p>
    </div>
@endif

@if (session()->has('noRecords'))
    <div class="alert alert-danger">
        <p>
            <i class="fas fa-exclamation-triangle"></i>
            {{ session('noRecords') }}
        </p>
    </div>
@endif

@if (session()->has('failures'))
    <table class="table table-scroll table-danger table-bordered">
        <thead>
        <tr>
            <th class="stt" style="width: 3.5%">STT</th>
            <th class="errorField">Trường lỗi</th>
            <th class="error">Lỗi</th>
            <th class="valueFix">Sản phẩm lỗi</th>
        </tr>
        </thead>
        <tbody class="body-half-screen">
        @foreach (session()->get('failures') as $validation)
            <tr>
                <td>{{ $validation->row() }}</td>
                <td>{{ $validation->attribute() }}</td>
                <td>
                    @foreach ($validation->errors() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </td>
                @if(isset($validation->values()[$validation->attribute()]))
                    <td>
                        {{ $validation->values()[$validation->attribute()] }}
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
