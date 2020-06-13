<?php
/**
 * Plugin Name: WP SQLite DB
 * Description: SQLite database driver drop-in. (based on SQLite Integration by Kojima Toshiyasu)
 * Author: Evan Mattson
 * Author URI: https://aaemnnost.tv
 * Plugin URI: https://github.com/aaemnnosttv/wp-sqlite-db
 * Version: 1.1.0
 * Requires PHP: 5.4
 *
 * This file must be placed in wp-content/db.php.
 * WordPress loads this file automatically.
 *
 * This project is based on the original work of Kojima Toshiyasu and his SQLite Integration plugin.
 */

if ( file_exists( __DIR__ . '/../vendor/autoload.php' ) ) {
	require_once __DIR__ . '/../vendor/autoload.php';
}

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/pluggable.php';

$GLOBALS['wpdb'] = new WP_SQLite_DB\wpsqlitedb();
