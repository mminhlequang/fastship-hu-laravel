<div id="modal-status-js" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
              
            <h5 class="modal-title" id="exampleModalLabel">{{ trans('theme::bookings.approve') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body">
                <select name="approve" class="form-control">
                    @foreach($status as $key => $val)
                        <option value="{{ $key }}" {{  $key ? 'selected':''}}>{{ $val }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <input id="book_id" name="book_id" type="hidden" value="0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('message.close') }}</button>
                <button type="button" class="btn btn-primary btn-change-status" >{{ trans('message.update') }}</button>
            </div>
        </div>
    </div>
</div>