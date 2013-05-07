[![project status](http://stillmaintained.com/djordje/li3_usermanager.png)]
(http://stillmaintained.com/djordje/li3_usermanager)

*You can find first revision of this plugin under TAG `0.1`*

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

Checkout **li3_migrations** and **li3_fixtures** to either of your library directories:

	cd libraries
	git clone git://github.com/djordje/li3_migrations.git
	git clone git://github.com/UnionOfRAD/li3_fixtures.git

Then load it in your main app config. Open `app/config/bootstrap/libraries.php` with your favorite
editor, and add:

	Libraries::add('li3_migrations');
	Libraries::add('li3_fixtures');

Go to you app dir with command line and run `li3 migrate up --library=li3_usermanager`

### Install dependencies

Clone dependencies to your `libraries` and load them in `app/config/bootstrap/libraries.php`.

This plugins use:

* **[li3_validators](http://github.com/djordje/li3_validators)**
* **[li3_swiftmailer](http://github.com/djordje/li3_swiftmailer)** (config-update branch)
* **[swiftmailer](http://github.com/swiftmailer/swiftmailer)**

*This plugin use Twitter Bootstrap classes in view.*

## li3_access support (deprecated, this will be removed after adapting to `jails/li3_access`)

**[li3_access](http://github.com/djordje/li3_access)** :
Checkout the code to either of your library directories:

	cd libraries
	git clone git://github.com/djordje/li3_access.git

With predefined Auth configurations for each user group you can start to use `li3_access`
immediately.

## TODO

* Write unit tests for application
* Finish `ManageUsers` controller (Add abillity to edit user, and move some logic to models to
so we can reuse it in console commands)
* Finish console command
* Adapt library to use `jails/li3_access`