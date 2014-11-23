<?php

/**
 * modulate forms into pretty router
 */

require_once("src/application/functions.php");

define( 'FORMS', dirname( __FILE__ ) . '/forms/' );
define( 'TEMPLATES', dirname( __FILE__ ) . '/templates/' );

$uri = rtrim( dirname($_SERVER["SCRIPT_NAME"]), '/' );
$uri = '/' . trim( str_replace( $uri, '', $_SERVER['REQUEST_URI'] ), '/' );
$uri = urldecode( $uri );

$rules = define_pages();

foreach ( $rules as $action => $rule ) {
	if ( preg_match( '~^'.$rule.'$~i', $uri, $params ) ) {
		include( TEMPLATES . 'header.php' );
		include( FORMS . $action . '.html' );
		include( TEMPLATES . 'footer.php' );
		exit;
	}
}
include( TEMPLATES . 'header.php' );
include( FORMS . '404.html' );
include( TEMPLATES . 'footer.php' );
exit;

?>