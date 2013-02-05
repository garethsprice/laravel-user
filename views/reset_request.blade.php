@layout('templates.login')
@section('title')
Reset Password
@endsection
@section('content')
    {{ Form::open( Config::get('user::config.reset_route')) }}
        @if ( Session::has('error') )
            <div class="alert alert-error">{{ Session::get('error') }}</div>
        @elseif ( Session::has('success') )
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        
        <!-- username field -->
        <div class="control-group {{ $errors->first('email', 'error') }}">
            {{ Form::label('email', 'E-mail', array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::input('email', 'email', Input::old('email'), array('required' => 'true')) }}
                {{ $errors->first('email', '<span class="help-inline">:message</span>') }}   
            </div>
        </div>

        <!-- submit button -->
        <div class="pull-right">
            {{ HTML::link( Config::get('user::config.login_route'), 'Cancel' ) }} &nbsp; 
            {{ Form::button('Send reset e-mail', array('class' => 'btn btn-warning btn-large')) }}
        </div>
    {{ Form::close() }}
@endsection