<?php

namespace WP_SQLite_DB;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * USE_MYSQL is a directive for using MySQL for database.
 * If you want to change the database from SQLite to MySQL or from MySQL to SQLite,
 * the line below in the wp-config.php will enable you to use MySQL.
 *
 * <code>
 * define('USE_MYSQL', true);
 * </code>
 *
 * If you want to use SQLite, the line below will do. Or simply removing the line will
 * be enough.
 *
 * <code>
 * define('USE_MYSQL', false);
 * </code>
 */
if ( defined( 'USE_MYSQL' ) && USE_MYSQL ) {
	return;
}

if ( version_compare( phpversion(), '5.4', '<' ) ) {
	wp_die(
		sprintf( "Your server is running PHP version %d but WP SQLite DB requires at least 5.4", phpversion() ),
		'Insufficient PHP Version'
	);
}

if ( ! extension_loaded( 'pdo' ) ) {
	wp_die(
		'Your PHP installation appears to be missing the PDO extension which is required for this version of WordPress.',
		'PHP PDO Extension Required'
	);
}

if ( ! extension_loaded( 'pdo_sqlite' ) ) {
	wp_die(
		'Your PHP installation appears not to have the right PDO drivers loaded. These are required for this version of WordPress and the type of database you have specified.',
		'PDO Driver for SQLite Required'
	);
}

/**
 * Notice:
 * Your scripts have the permission to create directories or files on your server.
 * If you write in your wp-config.php like below, we take these definitions.
 * define('DB_DIR', '/full_path_to_the_database_directory/');
 * define('DB_FILE', 'database_file_name');
 */

/**
 * FQDBDIR is a directory where the sqlite database file is placed.
 * If DB_DIR is defined, it is used as FQDBDIR.
 */
if ( defined( 'DB_DIR' ) ) {
	if ( substr( DB_DIR, - 1, 1 ) != '/' ) {
		define( 'FQDBDIR', DB_DIR . '/' );
	} else {
		define( 'FQDBDIR', DB_DIR );
	}
} else {
	if ( defined( 'WP_CONTENT_DIR' ) ) {
		define( 'FQDBDIR', WP_CONTENT_DIR . '/database/' );
	} else {
		define( 'FQDBDIR', ABSPATH . 'wp-content/database/' );
	}
}

/**
 * FQDB is a database file name. If DB_FILE is defined, it is used
 * as FQDB.
 */
if ( defined( 'DB_FILE' ) ) {
	define( 'FQDB', FQDBDIR . DB_FILE );
} else {
	define( 'FQDB', FQDBDIR . '.ht.sqlite' );
}