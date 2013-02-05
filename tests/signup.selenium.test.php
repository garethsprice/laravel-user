<?php
// Init bundle
include dirname(dirname(__FILE__)) . '/start.php';
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class SignupTest extends PHPUnit_Extensions_Selenium2TestCase
{
    protected function setUp()
    {
        // @todo: Find a way to test enable/disable options
        if( !Config::get('user::config.enable_signup') ) {
            $this->markTestSkipped('User signup is not enabled in the configuration.');
        }

        $this->setBrowser('firefox');
        $this->setBrowserUrl( Config::get('application.url') );
    }

    public function testSignupFormValidation()
    {
        $this->url( URL::to( Config::get('user::config.signup_route') ) );
        $this->assertEquals( URL::to( Config::get('user::config.signup_route') ), $this->url() );

        $loginExisting = $this->byId('login_existing');
        $this->assertEquals( URL::to( Config::get('user::config.login_route') ), $loginExisting->attribute('href') );

        // Test basic form fields load
        $username = $this->byName('username');
        $this->assertNotEmpty( $username );

        $email = $this->byName('email');
        $this->assertNotEmpty( $email );

        $email_confirmation = $this->byName('email_confirmation');
        $this->assertNotEmpty( $email_confirmation );

        $password = $this->byName('password');
        $this->assertNotEmpty( $password );

        $password_confirmation = $this->byName('password_confirmation');
        $this->assertNotEmpty( $password_confirmation );

        $signup = $this->byId('signup');
        $this->assertNotEmpty( $signup );

        // Test that HTML5 form validation works
        $this->clickOnElement('signup');

        $checkUsernameRequired = $this->byCssSelector('input[name=username]:required');
        $this->assertNotEmpty($checkUsernameRequired);

        $checkEmailRequired = $this->byCssSelector('input[name=email]:required');
        $this->assertNotEmpty($checkEmailRequired);

        $email->value('NotAnEmailAddress');
        $this->clickOnElement('signup');
        $checkEmailIsEmail = $this->byCssSelector('input[name=email]:invalid');
        $this->assertNotEmpty($checkEmailIsEmail);

        $checkEmailConfirmationRequired = $this->byCssSelector('input[name=email_confirmation]:required');
        $this->assertNotEmpty($checkEmailConfirmationRequired);

        $checkEmailConfirmationIsEmail = $this->byCssSelector('input[name=email_confirmation]:invalid');
        $this->assertNotEmpty($checkEmailConfirmationIsEmail);

        $checkPasswordRequired = $this->byCssSelector('input[name=password]:required');
        $this->assertNotEmpty($checkPasswordRequired);

        $checkPasswordConfirmationRequired = $this->byCssSelector('input[name=password_confirmation]:required');
        $this->assertNotEmpty($checkPasswordConfirmationRequired);

        $username->clear();
        $username->value('a');
        $email->clear();
        $email->value('testsignup@localhost');
        $email_confirmation->clear();
        $email_confirmation->value('NoMatch@localhost');
        $password->clear();
        $password->value('a');
        $password_confirmation->clear();
        $password_confirmation->value('b');
        $this->clickOnElement('signup');

        $this->assertRegExp('/The username must be at least/', $this->byCssSelector('#username-error')->text() );
        $this->assertRegExp('/The email confirmation does not match./', $this->byCssSelector('#email-error')->text() );
        $this->assertRegExp('/The password confirmation does not match./', $this->byCssSelector('#password-error')->text() );
    }

    public function testSignupCreatesUser()
    {
        $this->url( URL::to( Config::get('user::config.signup_route') ) );

        // Reload form elements as page has reloaded
        $username = $this->byName('username');
        $email = $this->byName('email');
        $email_confirmation = $this->byName('email_confirmation');
        $password = $this->byName('password');
        $password_confirmation = $this->byName('password_confirmation');

        $username->value('TestSignupUser');
        $email->value('testsignupuser@mailinator.com');
        $email_confirmation->value('testsignupuser@mailinator.com');
        $password->value('testsignupuser');
        $password_confirmation->value('testsignupuser');

        $this->clickOnElement('signup');

        $this->assertEquals( URL::to( Config::get('user::config.login_route') ), $this->url() );

        $alert = $this->byCssSelector('.alert-success');
        $this->assertRegExp('/Account created|activation link/', $alert->text());

        // Check the user actually got made
        $user = User::where('email', '=', 'testsignupuser@mailinator.com')->first();
        $this->assertNotEmpty($user);

        $user->delete();
    }

    public function testSignupCannotCreateDuplicateUser()
    {
        $user = new User;
        $user->username = 'PHPUnit Test';
        $user->email = 'testforduplicateuser@mailinator.com';
        $user->password = 'testsignupuser';
        $user->status = 1;
        $user->save();  
   
        $this->url( URL::to( Config::get('user::config.signup_route') ) );

        // Reload form elements as page has reloaded
        $username = $this->byName('username');
        $email = $this->byName('email');
        $email_confirmation = $this->byName('email_confirmation');
        $password = $this->byName('password');
        $password_confirmation = $this->byName('password_confirmation');

        $username->value('PHPUnit Test');
        $email->value('testforduplicateuser@mailinator.com');
        $email_confirmation->value('testforduplicateuser@mailinator.com');
        $password->value('testsignupuser');
        $password_confirmation->value('testsignupuser');

        $this->clickOnElement('signup');

        $this->assertRegExp('/The email has already been taken/', $this->byCssSelector('#email-error')->text() );

        // Check the user actually got made
        $user = User::where('email', '=', 'testforduplicateuser@mailinator.com')->first();
        $this->assertNotEmpty($user);
        $user->delete();
    }
}