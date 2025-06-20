<!-- Modal -->
<div class="modal fade" id="importModalCenter"  role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        {!! Form::open(['url' => 'admin/menus/import', 'method' => 'POST', 'class' => 'form-inline', 'files' => true]) !!}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Import Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center" >
                <div class="select-group" style="display: flex; justify-content: center;">
                    {!! Form::select('store_id', $stores, null, ['class' => 'form-control input-sm select2', 'required' => 'required']) !!}
                </div>
                <br>
                <input type="file" name="file" id="file-1" class="inputfile inputfile-1"
                       data-multiple-caption="{count} files selected"/>
                <label for="file-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                        <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
                    </svg>
                    <span>{{ trans('message.upload') }}</span>
                </label>
                {!! $errors->first('file', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="modal-footer">
                <a href="{{ url('admin/menus/export') }}"
                   title="Download file sample" class="btn btn-default pull-left btn-sm"><i
                            class="fa fa-cloud-download"></i> Download file sample</a>
                <button type="submit" class="btn btn-success btn-sm"><i
                            class="far fa-save"></i>&nbsp;Save
                </button>

            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<!-- Button trigger modal -->