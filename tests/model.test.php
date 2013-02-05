<?php
// Init bundle
include dirname(dirname(__FILE__)) . '/start.php';

class TestUserModel extends PHPUnit_Framework_TestCase {

	/**
	 * Test that setting the user's password hashes it
	 */
	public function testPasswordEncryptedOnSet()
	{
		$user = new User;
		$user->password = 'test';
		$this->assertTrue( Hash::check('test', $user->password) );
	}

	/**
	 * Test that we can retrieve status labels from the user object
	 */
	public function testStatusLabels()
	{
		$statuses = Config::get('user::config.user_status');

		$this->assertTrue( is_array($statuses) );
		$this->assertEquals( User::get_status_label(0), $statuses[0] );
	}

	/**
	 * Test that fetching an unknown status label is handled
	 */
	public function testInvalidStatusLabelReturnsUnknown()
	{
		$this->assertEquals( User::get_status_label(-1), '(unknown)' );
	}

	/**
	 * Test that an MD5 hash is generated for resetting passwords
	 */
	public function testResetHash()
	{
		$user = new User;
		$user->password = 'test';

		$timestamp = 1357020000;
		$expected_hash = md5($user->password . $timestamp);

		$this->assertEquals( $user->get_pass_reset_hash( $timestamp ), $expected_hash );		
	}

	/**
	 * Ensure we can create a user and read them from the database
	 */
	public function testCreateRead()
	{
		$user = $this->createUser();

		$this->assertTrue( is_integer($user->id) );
		$this->assertTrue( $user->id > 0 );

		$find_user = User::find( $user->id );

		$this->assertEquals( $find_user->username, $user->username );

		$user->delete();
	}

	/**
	 * Ensure we can make changes to a user
	 */
	public function testUpdate()
	{
		$user = $this->createUser();		

		$find_user = User::find( $user->id );
		$find_user->username = 'PHPUnit Update Test';
		$find_user->save();

		$find_user = User::find( $user->id );
		$this->assertEquals( $find_user->username, 'PHPUnit Update Test' );
		
		$find_user->delete();
	}

	/**
	 * Ensure we can delete a user from the database
	 */
	public function testDelete()
	{
		$user = $this->createUser();

		$find_user = User::find( $user->id );

		$find_user->delete();

		$find_user = User::find( $user->id );
		$this->assertEmpty( $find_user );	
	}

	/**
	 * Creates a mock user in the database for testing
	 */
	private function createUser()
	{
		$user = new User;
		$user->username = 'PHPUnit Test';
		$user->email = 'phpunit@localhost';
		$user->password = 'test';
		$user->status = 1;
		$user->save();	

		return $user;
	}
}