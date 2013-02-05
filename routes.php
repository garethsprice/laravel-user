<?php
/*
|--------------------------------------------------------------------------
| Bundle Login Routes
|--------------------------------------------------------------------------
*/
Route::any( Config::get('user::config.login_route'), 'user::user@login' );
Route::any( Config::get('user::config.logout_route'), array('before' => 'auth', 'uses' => 'user::user@logout') );

/*
|--------------------------------------------------------------------------
| Bundle Signup Routes
|--------------------------------------------------------------------------
*/
if( Config::get('user::config.enable_signup') ) {
	Route::any( Config::get('user::config.signup_route'), 'user::user@signup' );
}

/*
|--------------------------------------------------------------------------
| Bundle Password Reset Routes
|--------------------------------------------------------------------------
*/
if( Config::get('user::config.enable_reset') ) {
	Route::any( Config::get('user::config.reset_route'), 'user::user@reset_request' );
	Route::any( Config::get('user::config.reset_route') . '/(:num)/(:num)/(:all)', 'user::user@reset' );
}

/*
|--------------------------------------------------------------------------
| Bundle Account Route
|--------------------------------------------------------------------------
*/
if( Config::get('user::config.enable_account') ) {
	Route::any( Config::get('user::config.account_route'), array('before' => 'auth', 'uses' => 'user::user@account') );
}

/*
|--------------------------------------------------------------------------
| Bundle Activation Route
|--------------------------------------------------------------------------
*/
if( Config::get('user::config.signup_activation_required') ) {
	Route::any( Config::get('user::config.signup_activation_route') . '/(:num)/(:num)/(:all)', 'user::user@activate' );
}

/*
|--------------------------------------------------------------------------
| Bundle Admin Route
|--------------------------------------------------------------------------
*/
if( Config::get('user::config.enable_admin') ) {
	Route::any( Config::get('user::config.admin_user_index_route'), array('before' => 'auth', 'uses' => 'user::admin.users@index') );
	Route::any( Config::get('user::config.admin_user_add_route'), array('before' => 'auth', 'uses' => 'user::admin.users@add') );
	Route::any( Config::get('user::config.admin_user_view_route') . '/(:num)', array('before' => 'auth', 'uses' => 'user::admin.users@view') );
	Route::any( Config::get('user::config.admin_user_edit_route') . '/(:num)', array('before' => 'auth', 'uses' => 'user::admin.users@edit') );
	Route::any( Config::get('user::config.admin_user_delete_route') . '/(:num)', array('before' => 'auth', 'uses' => 'user::admin.users@delete') );
}
