<div id="modalInsert" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Team member') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['class' => 'form-horizontal mb-2', 'method' => 'POST', 'id' => 'form-insert-customer' ]) !!}
            <div class="modal-body" >
                <div class="form-group">
                    <label for="name">Name</label>
                    {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'required' => true]) !!}
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    {!! Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone', 'required' => true]) !!}
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'required' => true]) !!}
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    {!! Form::text('address', null, ['class' => 'form-control', 'id' => 'address']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('message.close') }}</button>
                <button type="submit" class="btn btn-info btn-change-status" >{{ trans('message.save') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>