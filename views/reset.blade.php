@layout('templates.login')
@section('title')
Reset Password
@endsection
@section('content')
    {{ Form::open( Config::get('user::config.reset_route') . '/' . $id . '/' . $timestamp . '/' . $hash ) }}
        @if ( Session::has('error') )
            <div class="alert alert-error">{{ Session::get('error') }}</div>
        @elseif ( Session::has('success') )
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        
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
            {{ Form::button('Set new password', array('class' => 'btn btn-warning btn-large')) }}
        </div>
    {{ Form::close() }}
@endsection