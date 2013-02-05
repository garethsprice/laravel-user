@layout('user::templates.login')
@section('title')
My Account
@endsection
@section('content')
    {{ Form::open( Config::get('user::config.account_route') ) }}
        <!-- check for login errors flash var -->
        @if ( Session::has('error') )
            <div class="alert alert-error">{{ Session::get('error') }}</div>
        @elseif ( Session::has('success') )
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        
        <!-- username field -->
        <div class="control-group {{ $errors->first('username', 'error') }}">
            {{ Form::label('username', 'Display name', array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::text('username', Input::has('username') ? Input::old('username') : Auth::user()->username, array('required' => 'true')) }}
                {{ $errors->first('username', '<span class="help-inline">:message</span>') }}   
            </div>
        </div>

        <!-- e-mail field -->
        <div class="control-group {{ $errors->first('email', 'error') }}">
            {{ Form::label('email', 'E-mail address', array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::text('email', Input::has('email') ? Input::old('email') : Auth::user()->email, array('required' => 'true')) }}
                {{ $errors->first('email', '<span class="help-inline">:message</span>') }}   
            </div>
        </div>

        <!-- password field -->
        <div class="control-group {{ $errors->first('password', 'error') }}">
            {{ Form::label('password', 'New password', array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::password('password') }}
                {{ $errors->first('password', '<span class="help-inline">:message</span>') }}   
            </div>
        </div>

        <!-- password confirmation field -->
        <div class="control-group {{ $errors->first('password_confirmation', 'error') }}">
            {{ Form::label('password_confirmation', 'Confirm new password', array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::password('password_confirmation') }}
                {{ $errors->first('password_confirmation', '<span class="help-inline">:message</span>') }}   
            </div>
        </div>

        <!-- submit button -->
        <div class="pull-right">
            {{ Form::button('Update', array('class' => 'btn btn-warning btn-large')) }}
        </div>
    {{ Form::close() }}
@endsection