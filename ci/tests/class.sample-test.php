<?php
class SampleTest extends WP_UnitTestCase {
	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	// Put convenience methods here
	// Here are two I use for faking things for save_post hooks, et al
	function set_post( $key, $value ) {
		$_POST[$key] = $_REQUEST[$key] = addslashes( $value );
	}

	function unset_post( $key ) {
		unset( $_POST[$key], $_REQUEST[$key] );
	}

}


?>
