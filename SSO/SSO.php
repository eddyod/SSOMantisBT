<?php
require_once(config_get( 'class_path' ) . 'MantisPlugin.class.php');

class SSOPlugin extends MantisPlugin {

	/**
	 *  A method that populates the plugin information and minimum requirements.
	 */
	function register() {
		$this->name        = 'SSO For BADG';
		$this->description = 'Give auto access to SSO People';
		$this->page        = 'config';
		$this->title = "SSO for BADG";
		$this->version  = '0.666';
		$this->requires = array('MantisCore' => '1.2.0',);

		$this->author  = 'Edward ODonnell';
		$this->contact = 'eodonnell@ucsd.edu';
		$this->url     = 'http://som.ucsd.edu';
	}

	/**
	 * Default plugin configuration.
	 */
	function hooks() {
		$hooks = array('EVENT_CORE_READY' => 'autoLogin');
		return $hooks;
	}

	function autoLogin(){
	  if (auth_is_user_authenticated())
	    return;
      
          # REMOTE_USER is domain\username
	  $username = $this->getUser();
	  echo "username is " + $username;
	  $t_user_id = user_get_id_by_name($username);
	  // if they are not in the DB, insert them
	  if (empty($t_user_id)) {
	    $t_user_id = $this->createUser($f_username);
	  }
          # If user has a valid id, log in
	  if ($t_user_id){
              # Mantis Login
	      user_increment_login_count( $t_user_id );
	  
	      user_reset_failed_login_count_to_zero( $t_user_id );
	      user_reset_lost_password_in_progress_count_to_zero( $t_user_id );
	      
	      auth_set_cookies($t_user_id, true);
	      auth_set_tokens($t_user_id);
	    }
	}


	function getUser() {
	  $username = $_SERVER['NETWORKUSERID'];
	  if (empty($username)) $username = $_SERVER['HTTP_RACFID'];
	  return $username;
	}

	function createUser($f_username) {
	  $t_user_id = 0;
	  $f_password = "asldfkassdfad";
	  $f_email = $_SERVER['EMAIL'];
	  $p_access_level = config_get( 'default_new_account_access_level' );
	  $f_protected = false;
	  $f_enabled = true;
	  $t_realname = $_SERVER['FULL_NAME'];
	  $t_cookie = user_create( $f_username, $f_password, $f_email, $p_access_level, $f_protected, $f_enabled, $t_realname );
	  
	  if ( $t_cookie === false ) {
	    $t_user_id = 0;
	  } else {
          # ok, we created the user, get the row again
	    $t_user_id = user_get_id_by_name( $f_username );
	  }
	  return $t_user_id;

	}
}
?>
