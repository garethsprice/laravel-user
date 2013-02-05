Hi {{ $user->username }},

Welcome to {{ $site_name }}, thank you for signing up.

Login at: {{ URL::to( Config::get('user::config.login_route') ) }}


Log in with your e-mail: {{ $user->email }}


You log in with the password you chose at signup. If you forget your password,
you can always reset it at:

{{ URL::to( Config::get('user::config.reset_route') ) }}
