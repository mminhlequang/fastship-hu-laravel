<!-- Modal -->
<div class="modal fade" id="importModalCenter" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        {!! Form::open(['url' => 'customers/import_excel', 'method' => 'POST', 'class' => 'form-inline', 'files' => true]) !!}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Import file Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height:500px;">
                <div class="select-group" style="display: flex; justify-content: center;">
                    {!! Form::select('id', $promotions, null, ['class' => 'form-control input-sm select2', 'required' => 'required']) !!}
                </div>
                <br>
                {!! Form::file('file', ['class' => 'form-control btn-sm file_task', 'multiple' => 'multiple', 'id' => 'file_task']) !!}
                {!! $errors->first('file', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="modal-footer">
                <a href="{{ asset('excel_sample/import.xlsx') }}" download
                   title="Tải file mẫu" class="btn btn-default pull-left btn-sm"><i
                            class="fa fa-cloud-download"></i> Tải file mẫu</a>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng
                </button>
                <button type="submit" class="btn btn-primary btn-sm"><i
                            class="fas fa-cloud-upload-alt"></i>Tải
                    lên
                </button>

            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<!-- Button trigger modal -->