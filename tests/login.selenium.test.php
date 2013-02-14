<?php
// Init bundle
include dirname(dirname(__FILE__)) . '/start.php';
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class LoginTest extends PHPUnit_Extensions_Selenium2TestCase
{
    protected function setUp()
    {
        $this->setBrowser('firefox');
        $this->setBrowserUrl( Config::get('application.url') );
    }
 
    public function testLogoutRedirectsToLoginWhenAnonymous()
    {
    	$this->url( URL::to( Config::get('user.logout_route', Config::get('user::config.logout_route')) ) );
    	$this->assertEquals( URL::to( Config::get('user.login_route', Config::get('user::config.login_route')) ), $this->url() );
    }

    public function testLogin()
    {
        $this->url( URL::to( Config::get('user.login_route', Config::get('user::config.login_route')) ) );
     
        // Create a user
        $user = new User;
        $user->username = 'PHPUnit User Login Test';
        $user->email = 'user@localhost';
        $user->password = 'test';
        $user->status = USER_ACTIVE;
        $user->save(); 

        $initial_last_login_at = $user->last_login_at;
        $initial_last_login_ip = $user->last_login_ip;

        // Test basic form fields load
        $username = $this->byName('username');
        $this->assertNotEmpty( $username );

        $password = $this->byName('password');
        $this->assertNotEmpty( $password );

        $login = $this->byId('login');
        $this->assertNotEmpty( $login );

        // Test that HTML5 form validation works
        $this->clickOnElement('login');

        $checkUsernameRequired = $this->byCssSelector('input[name=username]:required');
        $this->assertNotEmpty($checkUsernameRequired);

        $username->value('NotAnEmailAddress');
        $this->clickOnElement('login');
        $checkUsernameIsEmail = $this->byCssSelector('input[name=username]:invalid');
        $this->assertNotEmpty($checkUsernameIsEmail);

        $username->clear();
        $username->value('BadLogin@BadLogin.com');
        $this->clickOnElement('login');

        $alert = $this->byCssSelector('.alert');
        $this->assertRegExp('/E-mail or password incorrect/', $alert->text());

        // OK, lets try a real login
        $username = $this->byName('username');
        $username->clear();
        $username->value( $user->email );
        $password = $this->byName('password');
        $password->value('test');

        $this->clickOnElement('login');

        // Check that we're no longer on the login page
        $this->assertEquals( URL::to( Config::get('user.login_redirect', Config::get('user::config.login_redirect')) ), $this->url() );

        // Log out again (check by visiting the logout route and checking we get redirected)
        // (Not checking for logout_redirect as this can also lead to a redirect)
        $this->url( URL::to( Config::get('user.logout_route', Config::get('user::config.logout_route')) ) );
        $this->assertNotEquals( URL::to( Config::get('user.logout_route', Config::get('user::config.logout_route')) ), $this->url() );

        // Verify we're no longer authenticated
        $this->url( URL::to( Config::get('user.logout_route', Config::get('user::config.logout_route')) ) );
        $this->assertEquals( URL::to( Config::get('user.login_route', Config::get('user::config.login_route')) ), $this->url() );

        // Verify that login was logged
        // Refresh user from database
        $user = User::find( $user->id );
        $this->assertNotEquals( $user->last_login_at, $initial_last_login_at );
        $this->assertNotEquals( $user->last_login_ip, $initial_last_login_ip );

        $user->delete();
    }

    public function testBlockedUsersCannotLogin()
    {
    	// Create a blocked user
    	$user = new User;
		$user->username = 'PHPUnit Blocked User Test';
		$user->email = 'blocked@localhost';
		$user->password = 'test';
		$user->status = USER_SUSPENDED;
		$user->save();	

		// Now try to log in as the blocked user
		$this->url( URL::to( Config::get('user.login_route', Config::get('user::config.login_route')) ) );

        $username = $this->byName('username');
        $username->clear();
        $username->value('blocked@localhost');
        $password = $this->byName('password');
        $password->value('test');

        $this->clickOnElement('login');

        $alert = $this->byCssSelector('.alert');
        $this->assertEquals('Your account has been suspended. Please contact the site administrator for support.', $alert->text());
    
        $user->delete();
    }

    public function testUnactivatedUsersCannotLogin()
    {
    	// Create a blocked user
    	$user = new User;
		$user->username = 'PHPUnit Unactivated User Test';
		$user->email = 'pending@localhost';
		$user->password = 'test';
		$user->status = USER_PENDING;
		$user->save();	

		// Now try to log in as the blocked user
		$this->url( URL::to( Config::get('user.login_route', Config::get('user::config.login_route')) ) );

        $username = $this->byName('username');
        $username->clear();
        $username->value('pending@localhost');
        $password = $this->byName('password');
        $password->value('test');

        $this->clickOnElement('login');

        $alert = $this->byCssSelector('.alert');
        $this->assertEquals('You must activate your account to log in. Check your e-mail inbox for activation details.', $alert->text());
    
        $user->delete();
    }
}