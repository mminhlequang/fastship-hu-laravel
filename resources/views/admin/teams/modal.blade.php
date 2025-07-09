<div id="modalPlayers" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Team member') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['class' => 'form-horizontal mb-2', 'method' => 'POST', "url" => url('ajaxPost/addPlayers') ]) !!}
            <div class="modal-body" id="bodyPlayers">
                @include('admin.teams.modal_inner')
            </div>
            <div class="modal-footer">
                <input name="team_id" type="hidden" value="0" id="inputClub" >
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('message.close') }}</button>
                <button type="submit" class="btn btn-primary btn-change-status" >{{ trans('message.update') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>