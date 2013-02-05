<?php
// Init bundle
include dirname(dirname(__FILE__)) . '/start.php';

/**
 * Class for testing that required routes are set up
 */
class TestUserRoutes extends PHPUnit_Framework_TestCase {
	/**
	 * Test that all required routes are set
	 */
	public function testRoutesAreSet()
	{
		$this->assertNotEmpty( Config::get('user::config.login_route') );
		$this->assertNotEmpty( Config::get('user::config.logout_route') );

		if( Config::get('user::config.enable_signup') ) {
			$this->assertNotEmpty( Config::get('user::config.signup_route') );
		}

		if( Config::get('user::config.signup_activation_required') ) {
			$this->assertNotEmpty( Config::get('user::config.signup_activation_route') );
		}

		if( Config::get('user::config.enable_account') ) {
			$this->assertNotEmpty( Config::get('user::config.account_route') );
		}

		if( Config::get('user::config.enable_reset') ) {
			$this->assertNotEmpty( Config::get('user::config.reset_route') );
		}

		$this->assertNotEmpty( Config::get('user::config.login_redirect') );
		$this->assertNotEmpty( Config::get('user::config.logout_redirect') );
	}

	/**
	 * If admin is enabled, test that admin routes are set
	 */
	public function testAdminRoutesAreSet()
	{
		if( Config::get('user::config.enable_admin') ) {
			$this->assertNotEmpty( Config::get('user::config.admin_user_add_route') );
			$this->assertNotEmpty( Config::get('user::config.admin_user_delete_route') );
			$this->assertNotEmpty( Config::get('user::config.admin_user_edit_route') );
			$this->assertNotEmpty( Config::get('user::config.admin_user_index_route') );
			$this->assertNotEmpty( Config::get('user::config.admin_user_view_route') );
		}
	}
}