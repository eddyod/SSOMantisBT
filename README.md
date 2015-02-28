SSOMantisBT

This is a plugin for the MantisBT issue tracking software. This plugin is still under construction but should work given some minor tweaks.

Here are some assumptions

    You want to use Single Sign On (SSO) from either a Shibboleth server or some other SSO provider
    Your entire MantisBT site will be protected by SSO
    Anyone with SSO access will be provided a basic set of privileges
    You know what session variables you need to put into the plugin

If a person with SSO access comes into your mantis site, and is not in the database, they will automatically get inserted with the basic access rights.

If they are already in the database, then they are directed to the home page of mantisbt

You'll want to edit the SSO.php file to change the _SERVER variables. We use the NETWORKUSERID variable, but you might want to change that to REMOTE_USER.
