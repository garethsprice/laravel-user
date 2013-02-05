@layout('user::templates.login')
@section('title')
Login
@endsection
@section('content')
    {{ Form::open( Config::get('user::config.login_route')) }}
        @if ( Session::has('error') )
            <div class="alert alert-error">{{ Session::get('error') }}</div>
        @elseif ( Session::has('success') )
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        
        <!-- username field -->
        <div class="control-group {{ $errors->first('username', 'error') }}">
            {{ Form::label('username', 'E-mail', array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::input('email', 'username', Input::old('username'), array('required' => 'true')) }}
                {{ $errors->first('username', '<span class="help-inline">:message</span>') }}   
            </div>
        </div>

        <!-- password field -->
        <div class="control-group {{ $errors->first('password', 'error') }}">
            {{ Form::label('password', 'Password', array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::password('password') }}
                {{ $errors->first('password', '<span class="help-inline">:message</span>') }}   
            </div>
        </div>

        @if(Config::get('user::config.enable_login_remember'))
        <!-- remember field -->
        <div id="remember-me" class="pull-left control-group {{ $errors->first('remember', 'error') }}">
            <div class="controls">
                {{ Form::checkbox('remember') }}
                {{ Form::label('remember', 'Remember me', array('class' => 'control-label')) }}
            </div>
        </div>
        @endif

        <!-- submit button -->
        <div class="pull-right">
            @if (Config::get('user::config.enable_signup'))
                {{ HTML::link( Config::get('user::config.signup_route'), 'Signup' ) }} &nbsp; 
            @endif

            @if (Config::get('user::config.enable_reset'))
                {{ HTML::link( Config::get('user::config.reset_route'), 'Forgot password?' ) }} &nbsp; 
            @endif

            {{ Form::button('Login', array('class' => 'btn btn-warning btn-large', 'id' => 'login')) }}
        </div>
    {{ Form::close() }}
@endsection
