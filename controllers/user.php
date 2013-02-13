<?php
class User_User_Controller extends Base_Controller
{
    public $restful = true;

    public static $rules = array(
        'username'      => 'required|min:4|max:32',
        'email'         => 'required|confirmed|email|unique:users',
        'password'      => 'required|confirmed|min:6|max:32'
    );

    public function get_login()
    {
        return View::exists('user.login') ? View::make('user.login') : View::make('user::login');
    }

    public function post_login()
    {
        $credentials = array();
        $credentials['username'] = Input::get('username');
        $credentials['password'] = Input::get('password');
        $credentials['remember'] = Input::has('remember') ? Input::get('remember') : null;

        if ( Auth::attempt($credentials) ) {
            if( Auth::user()->status == USER_PENDING ) {
                Auth::logout();
                // @todo: Add resend activation e-mail method
                return Redirect::to( Config::get('user.login_route', Config::get('user::config.login_route')) )
                    ->with('error', 'You must activate your account to log in. Check your e-mail inbox for activation details.');                
            } elseif( Auth::user()->status == USER_SUSPENDED ) {
                Auth::logout();
                return Redirect::to( Config::get('user.login_route', Config::get('user::config.login_route')) )
                    ->with('error', 'Your account has been suspended. Please contact the site administrator for support.');                 
            }

            Auth::user()->last_login_at = date('Y-m-d H:i:s');
            Auth::user()->last_login_ip = Request::ip();
            Auth::user()->save();

            Event::fire('user: login');

            return Redirect::to( Config::get('user.login_redirect', Config::get('user::config.login_redirect')) );
        } else {
            return Redirect::to( Config::get('user.login_route', Config::get('user::config.login_route')) )
                ->with('error', 'E-mail or password incorrect');
        }
    }

    public function get_signup()
    {
        return View::exists('user.signup') ? View::make('user.signup') : View::make('user::signup');
    }

    public function post_signup()
    {
        $site_name = Config::get('application.site_name', Request::server('http_host'));

        $new_user = array();
        $new_user['username'] = Input::get('username');
        $new_user['email'] = Input::get('email');
        $new_user['password'] = Input::get('password');
        $new_user['last_login_ip'] = Request::ip();

        if(Config::get('user.signup_activation_required', Config::get('user::config.signup_activation_required'))) {
            $new_user['status'] = USER_PENDING;
        }

        $v = Validator::make( Input::all(), static::$rules );

        if ($v->fails()) {
            return Redirect::to( Config::get('user.signup_route', Config::get('user::config.signup_route')) )
                ->with_errors($v)
                ->with_input();
        }

        $user = new User($new_user);
        $user->save();

        if(!$user->id) {
            return Response::error('500');
        }

        Event::fire('user: new signup');

        if(Config::has('application.site_admin')) {
            $view = View::exists('user.email.signup_admin_notification') ? 'user.email.signup_admin_notification' : 'user::email.signup_admin_notification';
            $body = View::make( $view )->with('user', $user)->with('site_name', $site_name)->render();   
            $subject = '[' . $site_name . '] New user signed up';
            
            mail(Config::get('application.site_admin'), $subject, $body);
        }

        if(Config::get('user.signup_activation_required', Config::get('user::config.signup_activation_required'))) {
            $view = View::exists('user.email.signup_activation_required') ? 'user.email.signup_activation_required' : 'user::email.signup_activation_required';
            $body = View::make( $view )
                        ->with('user', $user)
                        ->with('site_name', $site_name)
                        ->with('activation_url', $user->get_activation_url())
                        ->render();   
            $subject = 'Please activate your account at ' . $site_name;
            mail($user->email, $subject, $body);

            return Redirect::to( Config::get('user.login_route', Config::get('user::config.login_route')) )
                ->with('success', 'Please check your e-mail for an activation link to enable your account');
        } else {
            $view = View::exists('user.email.signup_welcome') ? 'user.email.signup_welcome' : 'user::email.signup_welcome';
            $body = View::make( $view )
                        ->with('user', $user)
                        ->with('site_name', $site_name)
                        ->with('reset_url', Config::get('user.reset_route', Config::get('user::config.reset_route')))
                        ->render();   
            $subject = 'Welcome to ' . $site_name;
            mail($user->email, $subject, $body);

            return Redirect::to( Config::get('user.login_route', Config::get('user::config.login_route')) )
                ->with('success', 'Account created, you can now log in below');
        }
    }

    public function get_account()
    {
        return View::exists('user.account') ? View::make('user.account') : View::make('user::account');
    }

    public function post_account()
    {
        // Different rules for updating the account.
        $rules = array();
        $rules['username'] = 'required|min:4|max:32';
        $rules['email'] = Input::has('email') ? 'required|email' : '';
        $rules['password'] = Input::has('email') ? 'required|confirmed|min:4|max:32' : '';

        $v = Validator::make( Input::all(), $rules );
        if ($v->fails()) {
            return Redirect::to( Config::get('user.account_route', Config::get('user::config.account_route')) )
                ->with_errors($v)
                ->with_input();
        }

        Auth::user()->username = Input::get('username');
        Auth::user()->email = Input::get('email');

        // Leave password alone if the user doesn't want to change it
        if(Input::has('password')) {
            Auth::user()->password = Input::get('password');
        }

        Auth::user()->save();

        Event::fire('user: account updated');

        return Redirect::to( Config::get('user.account_route', Config::get('user::config.account_route')) )
            ->with('success', 'Account changes saved');
    }

    public function get_reset_request()
    {
        return View::exists('user.reset_request') ? View::make('user.reset_request') : View::make('user::reset_request');
    }

    public function post_reset_request()
    {
        // Different rules for updating the account.
        $rules = array();
        $rules['email'] = 'required|email';

        $v = Validator::make( Input::all(), $rules );
        if ($v->fails()) {
            return Redirect::to( Config::get('user.reset_route', Config::get('user::config.reset_route')) )
                ->with_errors($v)
                ->with_input();
        }

        $user = User::where_email(Input::get('email'))->first();

        if(!$user && !Config::get('user.enable_reset_obfuscation', Config::get('user::config.enable_reset_obfuscation'))) {
            return Redirect::to( Config::get('user.reset_route', Config::get('user::config.reset_route')) )
                ->with('error', 'No account is registered with this e-mail address.');            
        } elseif($user) {
            $url = $user->get_pass_reset_url();

            $view = View::exists('user.email.reset_request') ? 'user.email.reset_request' : 'user::email.reset_request';
            $body = View::make( $view )->with('url', $url)->render();   
            $site_name = Config::get('application.site_name', Request::server('http_host'));
            $subject = '[' . $site_name . '] Password reset request';

            mail($user->email, $subject, $body);
        }

        Event::fire('user: password reset request');

        return Redirect::to( Config::get('user.reset_route', Config::get('user::config.reset_route')) )
            ->with('success', 'Check your e-mail for link to reset your password');       
    }

    public function get_reset($id, $timestamp, $hash) 
    {
        // 86400 seconds = 24 hours
        $past_timestamp = time() - 86400;

        $rules = array(
            'id' => 'exists:users|numeric',
            'timestamp' => 'numeric|min:' . $past_timestamp,
            'hash' => 'valid_reset_hash'
        );

        $messages = array(
            'id_exists' => 'Password reset link is invalid. Please request a new reset link below.',
            'timestamp_min' => 'Password reset link has expired. Please request a new reset link below.',
            'valid_reset_hash' => 'Password reset link is invalid. Please request a new reset link below.',
        );

        Validator::register('valid_reset_hash', 'User::validate_reset_hash');

        $v = Validator::make( array('id' => $id, 'timestamp' => $timestamp, 'hash' => $hash), $rules, $messages );
        if ($v->fails()) {
            return Redirect::to( Config::get('user::config.reset_route') )
                ->with('error', $v->errors->first())
                ->with_input();
        }

        $view = View::exists('user.reset') ? 'user.reset' : 'user::reset';
        return View::make( $view )
            ->with('id', $id)
            ->with('timestamp', $timestamp)
            ->with('hash', $hash);
    }

    public function post_reset($id, $timestamp, $hash)
    {
        // Different rules for updating the account so we don't use the static::$rules above.
        $rules = array();
        $rules['password'] = Input::has('password') ? 'required|confirmed|min:4|max:32' : '';

        $v = Validator::make( Input::all(), $rules );
        if ($v->fails()) {
            return Redirect::to( implode('/', array( Config::get('user.reset_route', Config::get('user::config.reset_route')), $id, $timestamp, $hash )) )
                ->with_errors($v)
                ->with_input();
        }

        $user = User::find($id);
        $user->password = Input::get('password');
        $user->save();

        Event::fire('user: password reset');

        return Redirect::to( Config::get('user.login_route', Config::get('user::config.login_route')) )
            ->with('success', 'Password reset, you can now log in with your new password');        
    }

    public function get_activate($id, $timestamp, $hash)
    {
        $rules = array(
            'id' => 'exists:users|numeric',
            'timestamp' => 'numeric',
            'hash' => 'valid_reset_hash'
        );

        $messages = array(
            'id_exists' => 'Activation link is invalid.',
            'valid_reset_hash' => 'Activation link is invalid.',
        );

        Validator::register('valid_reset_hash', 'User::validate_reset_hash');

        $v = Validator::make( array('id' => $id, 'timestamp' => $timestamp, 'hash' => $hash), $rules, $messages );
        if ($v->fails()) {
            return Redirect::to( Config::get('user.login_route', Config::get('user::config.login_route')) )
                ->with('error', $v->errors->first())
                ->with_input();
        }

        $user = User::find($id);
        $user->status = USER_ACTIVE;
        $user->save();

        Event::fire('user: activated');

        return Redirect::to( Config::get('user.login_route', Config::get('user::config.login_route')) )
            ->with('success', 'Account activated, you can now log in with your password');        
    }

    public function get_logout()
    {
        Auth::logout();

        Event::fire('user: logout');
        
        return Redirect::to( Config::get('user.logout_redirect', Config::get('user::config.logout_redirect')) );
    }
}