<?php

function getUriSegments() {
	$ret = explode( '/', parse_url( $_SERVER[ 'REQUEST_URI' ], PHP_URL_PATH ) );
	return array_slice( $ret, 3 );
}

function getServerParams() {
	$ip = $_SERVER[ 'REMOTE_ADDR' ];
	$referer = $_SERVER[ 'HTTP_REFERER' ];
	$useragent = $_SERVER[ 'HTTP_USER_AGENT' ];

	return sprintf(
		"%-18s\t%-15s\t%-20s\t%s\n",
		date( 'Y-m-d H:i:s' ),
		$ip,
		$referer ? $referer : '-',
		$useragent
	);
}

function generateEmail() {
	$ip = $_SERVER[ 'REMOTE_ADDR' ];
	$referer = $_SERVER[ 'HTTP_REFERER' ];
	$useragent = $_SERVER[ 'HTTP_USER_AGENT' ];

	return sprintf(
		"Date:\n%s\n\nIP Address:\n%s\n\nReferer:\n%s\n\nUser Agent:\n%s\n",
		date( 'Y-m-d H:i:s' ),
		$ip,
		$referer ? $referer : '-',
		$useragent
	);
}

function outputGif() {
	header( 'Content-Type: image/gif' );
	echo "\x47\x49\x46\x38\x37\x61\x1\x0\x1\x0\x80\x0\x0\xfc\x6a\x6c\x0\x0\x0\x2c\x0\x0\x0\x0\x1\x0\x1\x0\x0\x2\x2\x44\x1\x0\x3b";
}

///////////////////////////////////////////////////////////////////////////////

$segments = getUriSegments();

switch( $segments[ 0 ] ) {
	case 'wiki.gif':
		$segments[ 0 ] = 'wiki';
		$trace = true;
		break;
	case 'mdreader.gif':
		$segments[ 0 ] = 'mdreader';
		$trace = true;
		break;
	case 'viewer.gif':
		$segments[ 0 ] = 'viewer';
		$trace = true;
		break;
	case 'github.gif':
		$segments[ 0 ] = 'github';
		$trace = true;
		break;
	case 'others.gif':
		$segments[ 0 ] = 'others';
		$trace = true;
		break;
	default:
		$trace = false;
}
// $trace = false;

if ( $trace ) {
	// IP filters
	$ip_filters = array(
		'127.0.0.1',
	);
	foreach( $ip_filters as $ip_filter ) {
		$ip = $_SERVER[ 'REMOTE_ADDR' ];
		if ( $ip_filter == $ip )
			$trace = false;
	}
}

if ( $trace ) {
	$file = 'log/' . $segments[ 0 ] . '.txt';
	$data = getServerParams();

	if ( ! file_exists( $file ) ) {
		$data = sprintf(
			"%-18s\t%-15s\t%-20s\t%s\n",
			'Timestamp',
			'IP Address',
			'Referer',
			'User Agent'
		) . "------------------\t---------------\t--------------------\t--------------------\n"
		  . $data;
	}

	$ret = file_put_contents( $file, $data, FILE_APPEND | LOCK_EX );

	// mail( 'YOUR_EMAIL', 'Notification from ' . ucwords( $segments[ 0 ] ), generateEmail() );
}

outputGif();
?>
