@layout('templates.main')
@section('return_link')
 {{ HTML::decode(HTML::link( Config::get('user::config.admin_user_index_route') , '<i class="icon-chevron-left"></i> Back to Users</a>')) }}
@endsection
@section('title')
	{{ HTML::link( Config::get('user::config.admin_user_index_route'), 'Users' ) }}
@endsection
@section('content')
    @if ( Session::has('error') )
        <div class="alert alert-error">{{ Session::get('error') }}</div>
    @elseif ( Session::has('success') )
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif

	<div class="pull-right">
		<p>
			@if(!($user->id == 1 && Auth::user()->id != 1))
				{{ HTML::decode( HTML::link(Config::get('user::config.admin_user_edit_route') . '/' . $user->id, '<i class="icon-edit"></i> Edit user', array('class' => 'btn btn-primary btn-large')) ) }}
			@endif
			@if($user->id != 1)
				{{ HTML::decode( HTML::link(Config::get('user::config.admin_user_delete_route') . '/' . $user->id, '<i class="icon-trash"></i> Delete user', array('class' => 'btn btn-danger btn-large')) ) }}
			@endif
		</p>
	</div>

   <div class="widget">
        
        <div class="widget-header">
            <h3>{{$user->username}}</h3>
        </div> <!-- /widget-header -->
                                            
        <div class="widget-content">
			<dl>
				<dt>E-mail address</dt>
				<dd>{{$user->email}}</dd>
				<dt>Status</dt>
				<dd>{{ User::get_status_label( $user->status ) }}</dd>
				<dt>Created</dt>
				<dd>{{$user->created_at}}</dd>
				<dt>Updated</dt>
				<dd>{{$user->updated_at}}</dd>
				<dt>Last login</dt>
				<dd>{{$user->last_login_at}}</dd>
				<dt>Last login IP</dt>
				<dd>{{$user->last_login_ip}}</dd>
			</dl>
        </div> <!-- /widget-content -->
    </div>
@endsection