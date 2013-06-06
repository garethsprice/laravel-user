User - A Laravel 3 Authentication Bundle

This bundle is a basic wrapper for the current Authentication system built in Laravel 3. It is designed to drop-in and instantly add everything required for user authentication and management in a basic web application, so you don't have to write it.

Features:

* Login/logout
* Last login date and IP remembered
* Remember me/cookie login
* Secure password reset
* Signup
* Secure user activation
* E-mail notifications on signup
* Edit account settings page (password, etc)
* Admin interface (create, read, update, delete, list)
* Bootstrap-compatible views
* Easy to override views without hacking the bundle
* ~~Full~~ Some Unit and Functional tests with PHPUnit and Selenium

It is very easy to use and customize. Most of the above features can be toggled via a configuration file - got a simple app that just needs login? Disable all the cruft. Need a full featured authentication system for a public facing web application? Enable all the things!

Based partly on the MyAuth bundle.

Note that this module does/should not provide Authorization (roles, permissons, etc) but it should work seamlessly with your choice of role bundle (Authority looks good). It does have some basic protection for the admin user (uid #1 can't be deleted, or edited by another user) and user status (active/pending/blocked).

## Installation

To install the bundle, run the following command

```PHP
~~php artisan bundle:install user~~
```

Not yet! This is a work in progress, so for now:

```PHP
git clone https://github.com/garethsprice/laravel-user.git
```

Next, we will tell the application to auto load the bundle. In your application/bundles.php file add the following line to the array

```PHP
'user' => array('auto' => true),
```

Finally, we need to migrate the users table. Do this by running the command below (note: be sure that you have already run ```php artisan migrate:install```)

```PHP
php artisan migrate user
```

## Configuration

In your config/application.php, create the following variables:

 * site_name - Title of your site. If not present, will default to using the hostname in e-mails.
 * site_admin - E-mail address to send notifications of new user signups to

## Testing it out

If everything was successful you should be able to navigate to your APPLICATION_HOST/login/ and you will see a plain email and password login.

A default user is provided: admin@admin.com with password "password".

Other routes that can be accessed in this bundle are:

- APPLICATION_HOST/login/
- APPLICATION_HOST/signup/
- APPLICATION_HOST/logout/
- APPLICATION_HOST/reset/
- APPLICATION_HOST/account/ (only after authentication)

## Configuration

Inside of the user/config/config.php file you can change the following code inside of the array to your desired URL routes. (example: instead of APPLICATION_HOST/signup/ perhaps you might want it to be APPLICATION_HOST/register/)

```PHP
return array(

	// Routes for our authentication

	'login_route' => 'login',
	'logout_route' => 'logout',
	'signup_route' => 'signup',

	// Login the user and redirect them to this route
	'login_redirect' => '',
);
```

Additionally you can specify the login_redirect route for the user to be redirected to when they are authenticated. (warning: Make sure to protect the login_redirect route with an authentication filter) [Find out about the authentication filter here](http://www.laravel.com/docs/auth/usage#filter)

You can override the default views by copying them to your application's views/user folder. This means you do not have to hack the downloaded bundle files.

## Unit and Integration Testing

The bundle comes with complete unit tests and functional/integration testing using PHPUnit and Selenium.

Requirements:

* PHPUnit
* Selenium Server
* Firefox

To execute the bundle's tests, run:

$ php artisan test user

It is highly recommended to create a mock environment that uses an empty testing database. To do this:

# Create a vhost or symlink just for testing (eg: so http://localhost/mysite and http://localhost/mysite-test resolve to the same application).
# Add to paths.php a 'testing' environment that resolves to your command-line hostname (use PHP's gethostname() to see what this is) AND the testing URL defined above.
# Create an application/config/test/application.php that defines 'url' to be the testing URL
# Create an application/config/test/database.php that connects your test environment to a testing database
# Run php artisan migrate:install --env=test && php artisan migrate --env=test to set up your local database

You can now run php artisan test user and all tests will be run against your mock database, leaving your production/local dev database alone.
