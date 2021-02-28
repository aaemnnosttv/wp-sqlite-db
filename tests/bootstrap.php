<?php
/**
 * PHPUnit bootstrap file
 */

// Set full path to WP tests config.
putenv( sprintf( 'WP_PHPUNIT__TESTS_CONFIG=%s', __DIR__ . '/wp-config.php' ) );

// Composer autoloader must be loaded before WP_PHPUNIT__DIR will be available
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Reset the DB file completely before each run.
$db_handle = fopen( __DIR__ . '/test-wp-content/database.sqlite', 'w' );
ftruncate( $db_handle, 0 );
fclose( $db_handle );
unset( $db_handle );

// Start up the WP testing environment.
require getenv( 'WP_PHPUNIT__DIR' ) . '/includes/bootstrap.php';
