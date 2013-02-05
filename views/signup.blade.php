@layout('user::templates.login')
@section('title')
Signup
@endsection
@section('content')
    {{ Form::open( Config::get('user::config.signup_route') ) }}
        <!-- check for login errors flash var -->
        @if (Session::has('notification'))
            <div class="alert alert-error">{{ Session::get('notification') }}</div>
        @endif
        
        <!-- username field -->
        <div class="control-group {{ $errors->first('username', 'error') }}">
            {{ Form::label('username', 'Display name', array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::text('username', Input::old('username'), array('required' => 'true')) }}
                {{ $errors->first('username', '<span class="help-inline" id="username-error">:message</span>') }}   
            </div>
        </div>

        <!-- e-mail field -->
        <div class="control-group {{ $errors->first('email', 'error') }}">
            {{ Form::label('email', 'E-mail address', array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::input('email', 'email', Input::old('email'), array('required' => 'true')) }}
                {{ $errors->first('email', '<span class="help-inline" id="email-error">:message</span>') }}   
            </div>
        </div>

        <!-- e-mail confirmation field -->
        <div class="control-group {{ $errors->first('email_confirmation', 'error') }}">
            {{ Form::label('email_confirmation', 'Confirm e-mail address', array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::input('email', 'email_confirmation', Input::old('email_confirmation'), array('required' => 'true')) }}
                {{ $errors->first('email_confirmation', '<span class="help-inline" id="email-confirmation-error">:message</span>') }}   
            </div>
        </div>

        <!-- password field -->
        <div class="control-group {{ $errors->first('password', 'error') }}">
            {{ Form::label('password', 'Password', array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::password('password', array('required' => 'true')) }}
                {{ $errors->first('password', '<span class="help-inline" id="password-error">:message</span>') }}   
            </div>
        </div>

        <!-- password confirmation field -->
        <div class="control-group {{ $errors->first('password_confirmation', 'error') }}">
            {{ Form::label('password_confirmation', 'Confirm password', array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::password('password_confirmation', array('required' => 'true')) }}
                {{ $errors->first('password_confirmation', '<span class="help-inline" id="password-confirmation-error">:message</span>') }}   
            </div>
        </div>

        <!-- submit button -->
        <div class="pull-right">
            {{ HTML::link( Config::get('user::config.login_route'), 'Login with existing account', array('id' => 'login_existing') ) }} &nbsp; 
            {{ Form::button('Sign up', array('class' => 'btn btn-warning btn-large', 'id' => 'signup')) }}
        </div>
    {{ Form::close() }}
@endsection