<?php

namespace Tests;

use WP_SQLite_DB\wpsqlitedb;

class SmokeTest extends TestCase {
	/** @test */
	function it_works() {
		global $wpdb;
		$this->assertInstanceOf( wpsqlitedb::class, $wpdb );

		// Do something that triggers a database query.
		$this->assertCount( 0, get_posts() );

		$this->factory()->post->create();

		$this->assertCount( 1, get_posts() );
	}

	/** @test */
	function it_rolls_back_between_tests_successfully() {
		$this->assertCount( 0, get_posts() );
	}
}
