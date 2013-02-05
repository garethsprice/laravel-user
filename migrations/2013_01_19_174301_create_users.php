<?php

class User_Create_Users {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('users', function($table) {
		    $table->increments('id');
		    $table->string('username');
		    $table->string('email');
		    $table->string('password');
		    $table->integer('status');
		    $table->date('last_login_at');
		    $table->string('last_login_ip');
		    $table->timestamps();   
		});

		DB::table('users')->insert(array(
    		'email'  => 'admin@admin.com',
    		'username'  => 'Admin',
    		'password'  => Hash::make('password')
		)); 
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}