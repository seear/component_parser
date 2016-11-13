<?php

$filename = $argv[1];
$contents = file_get_contents( $filename );

$lines = explode( "\n", $contents );

$result = array();

foreach ( $lines as $line ) {
	$matches = array();

	if ( preg_match( '@^//\s*components_theme\s+file:([\w.]+)\s+location:([\w]+)\s*$@', $line, $matches ) ) {
		// add last fragment to result
		if ( $file ) {
			$result[ $file ][ $location ][] = concat_fragment_array( $fragment );
		}

		// start new fragment
		$fragment = array();
		$file = $matches[1];
		$location = $matches[2];
	} else {
		$fragment[] = $line;
	}
}
// add final fragment
if ( $file ) {
	$result[ $file ][ $location ][] = concat_fragment_array( $fragment );
}


function concat_fragment_array( $fragment_array ) {
	$tags_to_strip = array(
		"<?php",
		"?>",
		"<script>",
		"</script>",
	);

	$scalar = implode( "\n", $fragment_array );
	$trimmed_array = explode( "\n", trim( $scalar ) );

	$first_line = sizeof( $trimmed_array ) > 0 ? $trimmed_array[0] : "";
	if ( in_array( trim( $first_line ), $tags_to_strip ) ) {
		array_shift( $trimmed_array );
	}

	$array_size = sizeof( $trimmed_array );
	$last_line = $array_size > 0 ? $trimmed_array[ $array_size - 1 ] : "";
	if ( in_array( trim( $last_line ), $tags_to_strip ) ) {
		array_pop( $trimmed_array );
	}

	return implode( "\n", $trimmed_array );
}


print_r( $result );
//print_r( JSON_encode( $result  ) );

		

