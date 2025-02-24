<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Tạo mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {!! Form::open(['method' => 'POST', 'url' => '', 'role' => 'newsletter', 'id' => 'newsletter']) !!}
                    <table class="table table-bordered table-condensed">
                        <tr class="row {{ $errors->has('name') ? 'has-error' : '' }}">
                            <td class="col-md-4 col-lg-3">
                                {!! Form::label('name', trans('type_business.name'), ['class' => 'control-label label-required']) !!}
                            </td>
                            <td class="col-md-8 col-lg-9">
                                {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required' => 'required','id' => 'name_business']) !!}
                                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                            </td>
                        </tr>
                    </table>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="sumit" class="btn btn-primary" id="saveBtn">Lưu</button>
                    </div>
                    {!! Form::close() !!}

                </div>
                <span class="text-danger alert-mail"></span>

            </div>
        </div>
    </div>