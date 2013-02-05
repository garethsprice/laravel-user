Hi {{ $user->username }},

An administrator created an account for you at {{ $site_name }}.

Login at: {{ URL::to( Config::get('user::config.login_route') ) }}


Log in with your e-mail: {{ $user->email }}


Your password is: {{ $password }}


If you forget your password, you can always reset it at:

{{ URL::to( Config::get('user::config.reset_route') ) }}
