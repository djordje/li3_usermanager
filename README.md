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
* Access control trough AccessController (user auth data inspection and `jails/li3_access` wrapper)
* User managemant (allowed for admins)
  * Create users
  * Promotion (group change)
  * Activation / deactivation
  * Editing users (email, password, about...)

## Instalation

Easiest way to install `li3_usermanager` is trough `composer` (you can find documentation [here](http://getcomposer.org/))!

You should require `li3_usermanager` and `li3_migrations` to migrate database to desired state:

```json

{
    "minimum-stability": "dev",
    "require": {
        "djordje/li3_usermanager": "dev-master",
        "djordje/li3_migrations": "dev-master"
    }
}

```

Then run `composer install`

Now you have all dependencies for both libraries installed.

Next step is to add libraries to `lithium`, go to `app/config/bootstrap/libraries.php` and add next lines:

```php
// li3_migrations
Libraries::add('li3_migrations');
Libraries::add('li3_fixtures');

// li3_usermanager
Libraries::add('li3_gravatar');
Libraries::add('li3_behaviors');
Libraries::add('li3_tree');
Libraries::add('li3_access');
Libraries::add('li3_validators');
Libraries::add('li3_swiftmailer');
Libraries::add('li3_usermanager');
Libraries::add('li3_backend');

```

Now open `terminal` and migrate database (you should have working database connection setup),
assume you have `li3` in your path, or use full path to `lithium/console/li3` instead:

```

// Create DB tables needed by `li3_access`
li3 migrate up --library=li3_access

// Create DB tables needed by `li3_usermanager` and populate `li3_access` table with needed rules
li3 migrate up --library=li3_usermanager

```

## Usage

Go to `http://your-url/login` and login with username: `root`, password `root`. This is default user,
and you should change password to something else.

Now yo can go to `http://your-url/backend/manage/users` creadte, update, delete, promote users.

## TODO

- [ ] Write unit tests for application
- [ ] Finish `ManageUsers` controller (Add abillity to edit user)
- [ ] Finish console command
- [ ] Better documentation (add info about library options)
- [x] Move some logic to models so we can reuse it in console commands
- [x] Adapt library to use `jails/li3_access`