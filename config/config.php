<?php 
define('USER_ACTIVE', 0);
define('USER_PENDING', 1);
define('USER_SUSPENDED', 2);

return array(
	// Routes for our authentication
	'login_route' => 'login',
	'logout_route' => 'logout',

	// Login the user and redirect them to this route
	'login_redirect' => 'admin/cities',
	'logout_redirect' => '/',

	'enable_login_remember' => TRUE,

	// Routes for signup
	'enable_signup' => FALSE,
	'signup_activation_required' => TRUE,
	'signup_route' => 'signup',
	'signup_activation_route' => 'activate',

	// Routes for account settings
	'enable_account' => TRUE,
	'account_route' => 'account',

	// Routes for password reset
	'enable_reset' => TRUE,
	// enable_reset_obfuscation will always say an alert mail has been sent,
	// even if the account does not exist. If it's off, the user will be told
	// if their account is not registered. Less secure, but less confusing.
	'enable_reset_obfuscation' => FALSE,
	'reset_route' => 'reset',

	// Routes for admin
	'enable_admin' => TRUE,
	'enable_admin_blocking' => TRUE,
	'admin_user_add_route' => 'admin/users/add',
	'admin_user_delete_route' => 'admin/users/delete',
	'admin_user_edit_route' => 'admin/users/edit',
	'admin_user_view_route' => 'admin/users/view',
	'admin_user_index_route' => 'admin/users',

	'user_status' => array(
		USER_ACTIVE => 'Active',
		USER_PENDING => 'Unactivated',
		USER_SUSPENDED => 'Suspended'
	),
);