<?php
# MantisBT - a php based bugtracking system
# # Copyright (C) 2002 - 2014  MantisBT Team - mantisbt-dev@lists.sourceforge.net
# # MantisBT is free software: you can redistribute it and/or modify
# # it under the terms of the GNU General Public License as published by
# # the Free Software Foundation, either version 2 of the License, or
# # (at your option) any later version.
# #
# # MantisBT is distributed in the hope that it will be useful,
# # but WITHOUT ANY WARRANTY; without even the implied warranty of
# # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# # GNU General Public License for more details.
# #
# # You should have received a copy of the GNU General Public License
# # along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.
# 


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
        $this->version  = '0.1';
        $this->requires = array('MantisCore' => '1.2.0',);
        $this->author  = 'Edward ODonnell';
        $this->contact = 'eodonnell@ucsd.edu';
        $this->url     = 'http://som.ucsd.edu';
    }

    /**
     * Default plugin configuration hook.
     */
    function hooks() {
        $hooks = array('EVENT_CORE_READY' => 'shibbolethLogin');
        return $hooks;
    }

    function shibbolethLogin(){
        if (auth_is_user_authenticated())
          return;
        // get the username from the _SERVER variable
        $username = $this->getUser();
        $t_user_id = user_get_id_by_name($username);
        // if they are not in the DB, insert them
        if (empty($t_user_id)) {
            $t_user_id = $this->createUser($f_username);
        }
        # If user has a valid id, log in
        if ($t_user_id){
            user_increment_login_count( $t_user_id );
            user_reset_failed_login_count_to_zero( $t_user_id );
            user_reset_lost_password_in_progress_count_to_zero( $t_user_id );
            auth_set_cookies($t_user_id, true);
            auth_set_tokens($t_user_id);
        }
    }


    /**
     * Get the appropriate variable from the Shibboleth headers
     */
    function getUser() {
        $username = $_SERVER['NETWORKUSERID'];
        if (empty($username)) {
            $username = $_SERVER['RACFID'];
        }
        return $username;
    }
    
    /**
     * If the user is not in the DB, insert them
     */
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
            # The user was created, get the row again and return the id
            $t_user_id = user_get_id_by_name( $f_username );
        }
        return $t_user_id;
    }
}
?>
