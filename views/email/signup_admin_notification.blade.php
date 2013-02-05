A new user registered at {{ $site_name }}.

Username: {{ $user->username }}

E-mail: {{ $user->email }}

Signup IP: {{ $user->last_login_ip }}



You can view the user's profile at: {{ URL::to( Config::get('user::config.admin_user_view_route') . '/' . $user->id ) }}
