@layout('templates.main')

@section('title')
	{{ HTML::link( Config::get('user::config.admin_user_index_route'), 'Users' ) }}
@endsection

@section('return_link')
 {{ HTML::decode(HTML::link( Config::get('user::config.admin_user_index_route'), '<i class="icon-chevron-left"></i> Back to Users</a>' )) }}
@endsection

@section('content')
<div class="widget">
	
	<div class="widget-header">
		<h3>Delete user</h3>
	</div> <!-- /widget-header -->
			
	<div class="widget-content">
		<p>Are you sure you want to delete {{$user->username}}?</p>

		{{ Form::open(Config::get('user::config.admin_user_delete_route') . '/' . $user->id) }}
			<div class="form-actions">
				{{ Form::button('Delete', array('class' => 'btn btn-danger btn-large')) }}
				{{ HTML::link(Config::get('user::config.admin_user_view_route') . '/' . $user->id, 'Cancel', array('class' => 'btn btn-large')) }}
			</div>
		{{ Form::close() }}
	</div> <!-- /widget-content -->

</div> <!-- /widget -->
@endsection