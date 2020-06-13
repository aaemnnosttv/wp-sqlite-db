<?php

namespace WP_SQLite_DB;

use PDO;
use PDOException;

/**
 * Function to create tables according to the schemas of WordPress.
 *
 * This is executed only once while installation.
 *
 * @return boolean
 */
function make_db_sqlite() {
	include_once ABSPATH . 'wp-admin/includes/schema.php';
	$index_array = [];

	$table_schemas = wp_get_db_schema();
	$queries       = explode( ";", $table_schemas );
	$query_parser  = new CreateQuery();
	try {
		$pdo = new PDO( 'sqlite:' . FQDB, null, null, [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ] );
	} catch ( PDOException $err ) {
		$err_data = $err->errorInfo;
		$message  = 'Database connection error!<br />';
		$message  .= sprintf( "Error message is: %s", $err_data[2] );
		wp_die( $message, 'Database Error!' );
	}

	try {
		$pdo->beginTransaction();
		foreach ( $queries as $query ) {
			$query = trim( $query );
			if ( empty( $query ) ) {
				continue;
			}
			$rewritten_query = $query_parser->rewrite_query( $query );
			if ( is_array( $rewritten_query ) ) {
				$table_query   = array_shift( $rewritten_query );
				$index_queries = $rewritten_query;
				$table_query   = trim( $table_query );
				$pdo->exec( $table_query );
				//foreach($rewritten_query as $single_query) {
				//  $single_query = trim($single_query);
				//  $pdo->exec($single_query);
				//}
			} else {
				$rewritten_query = trim( $rewritten_query );
				$pdo->exec( $rewritten_query );
			}
		}
		$pdo->commit();
		if ( $index_queries ) {
			// $query_parser rewrites KEY to INDEX, so we don't need KEY pattern
			$pattern = '/CREATE\\s*(UNIQUE\\s*INDEX|INDEX)\\s*IF\\s*NOT\\s*EXISTS\\s*(\\w+)?\\s*.*/im';
			$pdo->beginTransaction();
			foreach ( $index_queries as $index_query ) {
				preg_match( $pattern, $index_query, $match );
				$index_name = trim( $match[2] );
				if ( in_array( $index_name, $index_array ) ) {
					$r           = rand( 0, 50 );
					$replacement = $index_name . "_$r";
					$index_query = str_ireplace( 'EXISTS ' . $index_name, 'EXISTS ' . $replacement,
						$index_query );
				} else {
					$index_array[] = $index_name;
				}
				$pdo->exec( $index_query );
			}
			$pdo->commit();
		}
	} catch ( PDOException $err ) {
		$err_data = $err->errorInfo;
		$err_code = $err_data[1];
		if ( 5 == $err_code || 6 == $err_code ) {
			// if the database is locked, commit again
			$pdo->commit();
		} else {
			$pdo->rollBack();
			$message = sprintf( "Error occured while creating tables or indexes...<br />Query was: %s<br />",
				var_export( $rewritten_query, true ) );
			$message .= sprintf( "Error message is: %s", $err_data[2] );
			wp_die( $message, 'Database Error!' );
		}
	}

	$query_parser = null;
	$pdo          = null;

	return true;
}