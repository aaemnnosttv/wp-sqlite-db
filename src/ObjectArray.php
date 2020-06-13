<?php

namespace WP_SQLite_DB;

/**
 * Class to change queried data to PHP object.
 *
 * @author kjm
 */
class ObjectArray {
	function __construct( $data = null, &$node = null ) {
		foreach ( $data as $key => $value ) {
			if ( is_array( $value ) ) {
				if ( ! $node ) {
					$node =& $this;
				}
				$node->$key = new \stdClass();
				self::__construct( $value, $node->$key );
			} else {
				if ( ! $node ) {
					$node =& $this;
				}
				$node->$key = $value;
			}
		}
	}
}