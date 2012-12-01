# User managemant for [Lithium PHP framework](http://lithify.me/)

**li3_usermanager** provide:

* User registration
* User activation trough link with token
* Password resets trough link with token
* Updating user data (email, password, about...)
* Log in / log out
* Access control trough AccessController (user auth data inspection)
* User managemant (allowed for admins/root)
  * Promotion (group change)
  * Activation / deactivation
  * Editing users (email, password, about...)

## Instalation

Checkout **li3_usermanager** to either of your library directories:

	cd libraries
	git clone git://github.com/djordje/li3_usermanager.git

Then load it in your main app config. Open `app/config/bootstrap/libraries.php` with your favorite
editor, and add:

	Libraries::add('li3_usermanager');

Setup `default` MySQL database connection in your `app/config/bootstrap/connections.php`.
Uncoment `require` for `connections.php` and `session.php` in your `app/config/bootstrap.php`

### Import database tables

You can do this step in two ways:

* Import `libraries/li3_usermanager/resources/db/UsermanagerTables.sql` to your database with your
favorite tool, eg. `phpMyAdmin`.
* Or you can use **Phinx** to execute migrations and migrate your database. See bellow instructions
for Phinx.

### Install dependencies

Clone dependencies to your `libraries` and load them in `app/config/bootstrap/libraries.php`.

This plugins use:

* **[li3_validators](http://github.com/djordje/li3_validators)**
* **[li3_swiftmailer](http://github.com/djordje/li3_swiftmailer)** (config-update branch)
* **[swiftmailer](http://github.com/swiftmailer/swiftmailer)**

## Phinx (optional)

If you want to use database migrations instead of raw sql you'll need
[Phinx](https://github.com/robmorgan/phinx).
You can find installation and usage instructions in Phinx repo. If you add `phinx` to
your PATH variable you can migrate database like this:
	
	cd libraries/li3_usermanager/resources/db
	phinx migrate


## li3_access support

**[li3_access](http://github.com/tmaiaroto/li3_access)** :
Checkout the code to either of your library directories:

	cd libraries
	git clone git://github.com/tmaiaroto/li3_access.git

With predefined Auth configurations for each user group you can start to use `li3_access`
immediately.

Tested with revision `c2c487842ad2ee2bdd3545f4cff20449aae5561f`

## TODO

* Write unit tests for application
* Finish `ManageUsers` controller
* Write console command for creating root user
* Wait to some ACL solution, or extend **li3_access** to enable true power of user groups!