@layout('templates.main')
@section('title')
	Users
@endsection
@section('content')
    @if ( Session::has('error') )
        <div class="alert alert-error">{{ Session::get('error') }}</div>
    @elseif ( Session::has('success') )
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    
	<div class="pull-right">
		<p>
			{{ HTML::decode( HTML::link( Config::get('user::config.admin_user_add_route'), '<i class="icon-plus"></i> Add new user', array('class' => 'btn btn-primary btn-large')) ) }}
		</p>
	</div>

	@if( empty($users) )

	<div class="alert alert-warning">No users are in the database yet. Why not add one?</div>

	@else

    <div class="widget widget-table">
        
        <div class="widget-header">
            <h3>All Users</h3>
        </div> <!-- /widget-header -->

        <div class="widget-content">
			<table class="table table-striped">

				<thead>
					<tr>
						<th>Name</th>
						<th>E-mail</th>
						<th>Status</th>
						<th>Last login</th>
						<th>&nbsp;</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($users->results as $user)
			        	<tr>
			            	<td>{{ HTML::link( Config::get('user::config.admin_user_view_route') . '/' . $user->id, $user->username) }}</td>
			            	<td>{{ $user->email }}</td>
			            	<td>{{ User::get_status_label( $user->status ) }}</td>
			            	<td>
			            		@if ($user->last_login_at == '0000-00-00 00:00:00')
			            			Never
			            		@else
			            			{{ date('m/d/Y H:i', strtotime($user->last_login_at)) }}
			            		@endif
			            	</td>
			            	<td style="text-align: right">
			            		@if(!($user->id == 1 && Auth::user()->id != 1))
			            			{{ HTML::decode( HTML::link( Config::get('user::config.admin_user_edit_route') . '/' . $user->id, '<i class="icon-edit"></i> Edit', array('class' => 'btn')) ) }}
			            		@endif
			            		@if($user->id != 1)
			            			{{ HTML::decode( HTML::link( Config::get('user::config.admin_user_delete_route') . '/' . $user->id, '<i class="icon-trash"></i> Delete', array('class' => 'btn btn-danger')) ) }}
			            		@endif
			            	</td>
			        	</tr>
			    	@endforeach
		    	</tbody>

			</table> 

        </div> <!-- /widget-content -->
        
    </div> <!-- /widget -->

    <div class="pull-left">
    	{{ $users->links() }}
	</div>

	@endif
@endsection