<?php
/**
 * PHPUnit bootstrap file
 */

// Set full path to WP tests config.
putenv( sprintf( 'WP_PHPUNIT__TESTS_CONFIG=%s', __DIR__ . '/wp-config.php' ) );

// Composer autoloader must be loaded before WP_PHPUNIT__DIR will be available
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Reset the DB file completely before each run.
if ( file_exists( __DIR__ . '/database.sqlite' ) ) {
    unlink( __DIR__ . '/database.sqlite' );
}
touch( __DIR__ . '/database.sqlite' );

// Start up the WP testing environment.
require getenv( 'WP_PHPUNIT__DIR' ) . '/includes/bootstrap.php';
