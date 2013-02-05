<?php

/*
|--------------------------------------------------------------------------
| Default User Model
|--------------------------------------------------------------------------
*/

class User extends Eloquent
{
    public function set_password($password)
    {
        $this->set_attribute('password', Hash::make($password));
    }

    public function get_activation_url()
    {
        $timestamp = date('U');
        // @todo: Rename this function now we're using it for activation too
        $hash = $this->get_pass_reset_hash($timestamp);

        return URL::to_action('user::user@activate', array($this->id, $timestamp, $hash));
    }

    /**
     * Reset URL contains :uid/:timestamp/:hash
     */
    public function get_pass_reset_url()
    {
        $timestamp = date('U');
        $hash = $this->get_pass_reset_hash($timestamp);

        return URL::to_action('user::user@reset', array($this->id, $timestamp, $hash));
    }

    /**
     * The reset hash is the MD5 hash of a string containing the user's current
     * password hash and the timestamp.
     *
     * This means the reset link has to have knowledge of the user's current
     * password but doesn't expose any sensitive information, and we can set a
     * tamper-proof expiry based on the timestamp.
     *
     * We use md5() rather than the more secure Laravel hashing functions as
     * an MD5 string can be passed in a URL more elegantly and with less chance
     * of being altered by the user's mail client.
     */
    public function get_pass_reset_hash($timestamp)
    {
        return md5($this->password . $timestamp);
    }

    /**
     * Called as a custom Validator function
     */
    public static function validate_reset_hash($attribute, $hash, $parameters)
    {
        // @todo: Is there a better way to get access to other variables for a validation function?
        $uid = URI::segment(2);
        $timestamp = URI::segment(3);

        $user = User::find($uid);
        if (!$user) {
            return FALSE;
        }

        return $hash == $user->get_pass_reset_hash($timestamp);
    }

    public static function get_status_label( $status_id )
    {
        $status_labels = Config::get('user::config.user_status');

        if(!isset($status_labels[ $status_id ])) {
            return '(unknown)';
        }

        return $status_labels[ $status_id ];
    }
}
