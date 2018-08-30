# wp-sqlite-db

A single file drop-in for using a SQLite database with WordPress. Based on the original SQLite Integration plugin.

## Installation

- Clone or download this repository
- Copy `db.php` into the root of your site's `wp-content` directory

## Overview

Once the drop-in is installed, no other configuration is necessary, but some things are configurable.

By default, the SQLite database is located in `wp-content/database/.ht.sqlite`, but you can change this using a few constants.

```php
define('DB_DIR', '/absolute/custom/path/to/directory/for/sqlite/database/file/');
define('DB_FILE', 'custom_filename_for_sqlite_database');
```

## Credit

This project is based on the [SQLite Integration](https://wordpress.org/plugins/sqlite-integration/) plugin by Kojima Toshiyasu.
