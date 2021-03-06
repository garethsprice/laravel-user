@layout('user::templates.main')

@section('title')
    {{ HTML::link( Config::get('user::config.admin_user_index_route'), 'Users' ) }}
@endsection

@section('return_link')
 {{ HTML::decode(HTML::link( Config::get('user::config.admin_user_index_route'), '<i class="icon-chevron-left"></i> Back to Users</a>') ) }}
@endsection

@section('content')

{{ Form::open( Config::get('user::config.admin_user_add_route'), 'POST', array('class' => 'form-horizontal') ) }}
<div class="widget">
    
    <div class="widget-header">
        <h3>Add new user</h3>
    </div> <!-- /widget-header -->
            
    <div class="widget-content">
        
        <fieldset>

            <div class="control-group {{ $errors->first('email', 'error') }}">                                           
                {{ Form::label('email', 'E-mail address', array('class' => 'control-label')) }}
                <div class="controls">
                    {{ Form::input('email', 'email', Input::old('email'), array('class' => 'input-medium', 'required')) }}
                    {{ $errors->first('email', '<span class="help-inline">:message</span>') }}   
                </div> <!-- /controls -->   
            </div> <!-- /control-group -->

            <div class="control-group {{ $errors->first('username', 'error') }}">                                           
                {{ Form::label('username', 'Display name', array('class' => 'control-label')) }}
                <div class="controls">
                    {{ Form::text('username', Input::old('username'), array('class' => 'input-medium', 'required')) }}
                    {{ $errors->first('username', '<span class="help-inline">:message</span>') }}   
                </div> <!-- /controls -->   
            </div> <!-- /control-group -->

            <div class="control-group {{ $errors->first('password', 'error') }}">                                           
                {{ Form::label('password', 'Password', array('class' => 'control-label')) }}
                <div class="controls">
                    {{ Form::password('password') }}
                    {{ $errors->first('password', '<span class="help-inline">:message</span>') }}   
                </div> <!-- /controls -->   
            </div> <!-- /control-group -->

            <div class="control-group {{ $errors->first('password_confirmation', 'error') }}">                                           
                {{ Form::label('password_confirmation', 'Confirm password', array('class' => 'control-label')) }}
                <div class="controls">
                    {{ Form::password('password_confirmation') }}
                    {{ $errors->first('password_confirmation', '<span class="help-inline">:message</span>') }}   
                </div> <!-- /controls -->   
            </div> <!-- /control-group -->

            <div class="control-group {{ $errors->first('notify', 'error') }}">
                {{ Form::label('notify', 'Send welcome e-mail', array('class' => 'control-label')) }}
                <div class="controls">
                    {{ Form::checkbox('notify', '1', true) }}
                </div>
            </div>

        </fieldset>

        <div class="form-actions">
            {{ Form::button('Add user', array('class' => 'btn btn-primary btn-large')) }}
            {{ HTML::link( Config::get('user::config.admin_user_index_route'), 'Cancel', array('class' => 'btn btn-large') ) }}
        </div>

    </div> <!-- /widget-content -->
</div> <!-- /widget -->
{{ Form::close() }}
@endsection