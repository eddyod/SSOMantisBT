# SSOMantisBT
<p>This is a plugin for the <a
href="https://www.mantisbt.org/">MantisBT issue tracking software</a>.
This plugin is still under construction but should work given some
minor tweaks.</p>
<p>Here are some assumptions</p>
<ul>
<li>You want to use Single Sign On (SSO) from either a Shibboleth
server or some other SSO provider</li>
<li>Your entire MantisBT site will be protected by SSO</li>
<li>Anyone with SSO access will be provided a basic set of
privileges</li>
<li>You know what session variables you need to put into the plugin</li>
</ul>
<p>If a person with SSO access comes into your mantis site, and is not
in the database, they will automatically get inserted with the basic
access rights.</p>
<p>If they are already in the database, then they are directed to the
home page of mantisbt</p>
<p>You'll want to edit the SSO.php file to change the _SERVER
variables. We use the NETWORKUSERID variable, but you might want to
change that to REMOTE_USER.</p>
