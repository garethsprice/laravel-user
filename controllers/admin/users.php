<?php
class User_Admin_Users_Controller extends Base_Controller
{
    public $restful = true;

    // Put rules here as they're used in adding and editing
    private $rules = array(
        'email'         => 'required|email|unique:users',
        'password'      => 'required|confirmed|min:6|max:32',
        'username'      => 'required'
    );

    public function get_add()
    {
        $view = View::exists('user.admin.add') ? 'user.admin.add' : 'user::admin.add';
        return View::make( $view );
    }

    public function post_add()
    {
        $site_name = Config::get('application.site_name', Request::server('http_host'));

        $new_user = array(
            'email' => Input::get('email'),
            'username' => Input::get('username'),
            'password' => Input::get('password'),
            'password_confirmation' => Input::get('password_confirmation'),
        );

        $v = Validator::make($new_user, $this->rules);

        if ($v->fails()) {
            return Redirect::to( Config::get('user::config.admin_user_add_route') )
                ->with_errors($v)
                ->with_input();
        }

        // Don't want to pass this to the model
        unset($new_user['password_confirmation']);

        $user = new User($new_user);
        $user->save();

        if(Input::get('notify')) {
            $view = View::exists('user.email.signup_admin_welcome') ? 'user.email.signup_admin_welcome' : 'user::email.signup_admin_welcome';
            $body = View::make( $view )
                        ->with('user', $user)
                        ->with('site_name', $site_name)
                        ->with('password', Input::get('password'))
                        ->with('reset_url', Config::get('user::config.reset_route'))
                        ->render();   
            $subject = 'Welcome to ' . $site_name;
            mail($user->email, $subject, $body);
        }

        return Redirect::to( Config::get('user::config.admin_user_view_route') . '/' . $user->id )
            ->with('success', 'New user account created');
    }

    public function get_index()
    {
        $users = DB::table('users')->paginate(25);
        $view = View::exists('user.admin.index') ? 'user.admin.index' : 'user::admin.index';
        return View::make( $view )->with('users', $users);
    }

    public function get_view($user)
    {
        $user = User::find($user);
        $view = View::exists('user.admin.view') ? 'user.admin.view' : 'user::admin.view';

        if(!$user) {
            return Response::error('404');
        }

        return View::make( $view )->with('user', $user);
    }

    public function get_edit($user)
    {
        $user = User::find($user);
        $view = View::exists('user.admin.edit') ? 'user.admin.edit' : 'user::admin.edit';

        if(!$user) {
            return Response::error('404');
        }

        // Only let the admin edit/delete themselves
        // Note that any more advanced role management should be managed by
        // a module designed for the purpose and not hacked in here.
        if($user->id == 1 && Auth::user()->id != 1) {
            return Redirect::to( Config::get('user::config.admin_user_index_route') )
                ->with('error', 'User 1 cannot be edited by other users');
        }

        return View::make( $view )->with('user', $user);
    }

    public function post_edit($user)
    {
        $user = User::find($user);

        $user->email = Input::get('email');
        $user->username = Input::get('username');
        $user->status = Input::get('status');

        if (Input::has('password')) {
            $user->password = Input::get('password');
        }

        $rules = $this->rules;
        $rules['email'] = 'required|email';
        $rules['password'] = 'confirmed|min:6|max:32';

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            return Redirect::to( Config::get('user::config.admin_user_edit_route') . '/' . $user->id )
                ->with_errors($v)
                ->with_input();
        }

        $user->save();

        return Redirect::to( Config::get('user::config.admin_user_view_route') . '/' . $user->id )
            ->with('success', 'Account changes saved');
    }

    public function get_delete($user)
    {
        $user = User::find($user);
        $view = View::exists('user.admin.delete') ? 'user.admin.delete' : 'user::admin.delete';

        if(!$user) {
            return Response::error('404');
        }

        // Only let the admin edit/delete themselves
        // Note that any more advanced role management should be managed by
        // a module designed for the purpose and not hacked in here.
        if($user->id == 1) {
            return Redirect::to( Config::get('user::config.admin_user_index_route') )
                ->with('error', 'User 1 cannot be deleted');
        }

        return View::make( $view )
            ->with('user', $user);
    }

    public function post_delete($user)
    {
        $user = User::find($user);

        if(!$user) {
            return Response::error('404');
        }

        // Only let the admin edit/delete themselves
        // Note that any more advanced role management should be managed by
        // a module designed for the purpose and not hacked in here.
        if($user->id == 1 && Auth::user()->id != 1) {
            // NOTE: 403 template isn't supplied by default with Laravel
            return Response::error('403');
        }

        $user->delete();

        return Redirect::to( Config::get('user::config.admin_user_index_route') )->with('success', 'Account deleted');
    }
}
