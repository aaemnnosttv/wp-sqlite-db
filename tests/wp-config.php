<?php
define( 'WP_SQLITE_DB__ROOT', dirname( __DIR__ ) );
/* Path to the WordPress codebase you'd like to test. Add a forward slash in the end. */
define( 'ABSPATH', WP_SQLITE_DB__ROOT . '/wordpress/' );

// Configure WordPress to use an alternate wp-content directory to use the SQLite DB driver.
define( 'WP_CONTENT_DIR', WP_SQLITE_DB__ROOT . '/tests/test-wp-content' );
// Configure the file to use for the database.
// SQLite supports using an in-memory database as well
// which would be ideal, but test runs "install" WordPress
// in a separate process on every run so this isn't possible.
define( 'DB_DIR', WP_CONTENT_DIR );
define( 'DB_FILE', 'database.sqlite');

/*
 * Path to the theme to test with.
 *
 * The 'default' theme is symlinked from test/phpunit/data/themedir1/default into
 * the themes directory of the WordPress installation defined above.
 */
define( 'WP_DEFAULT_THEME', 'default' );

// Test with multisite enabled.
// Alternatively, use the tests/phpunit/multisite.xml configuration file.
// define( 'WP_TESTS_MULTISITE', true );

// Test with WordPress debug mode (default).
define( 'WP_DEBUG', true );

// ** MySQL settings ** //

// This configuration file will be used by the copy of WordPress being tested.
// wordpress/wp-config.php will be ignored.

// WARNING WARNING WARNING!
// These tests will DROP ALL TABLES in the database with the prefix named below.
// DO NOT use a production database or one that is shared with something else.

// SQLite does not require any connection information.
define( 'DB_NAME'       , 'null-db-name' );
define( 'DB_USER'       , 'null-db-user' );
define( 'DB_PASSWORD'   , 'null-db-pass' );
define( 'DB_HOST'       , 'null-db-host' );

define( 'DB_CHARSET'    , 'utf8' );
define( 'DB_COLLATE'    , '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 */
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

$table_prefix = 'wpsqlitedbtests_';   // Only numbers, letters, and underscores please!

define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Test Blog' );

define( 'WP_PHP_BINARY', 'php' );

define( 'WPLANG', '' );
