<?php

// Defining new schedules types
function nm_cron_schedule( $schedules ) {
	$schedules['weekly']     = array(
		'interval' => 60 * 60 * 24 * 7, # 604,800, seconds in a week
		'display'  => __( 'Weekly' ),
	);
	$schedules['per_minute'] = array(
		'interval' => 60, # 60, seconds in a minute
		'display'  => __( 'Per Minute' ),
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'nm_cron_schedule' );


// Hook that clears Network Map Caches
function nm_clear_cache_func() {
	global $wpdb;

	$results = $wpdb->get_results( "DELETE FROM `wp_options` WHERE `option_name` LIKE ('case_api-CACHE_VERSION%');", OBJECT ); // @codingStandardsIgnoreLine
	$results = $wpdb->get_results( "DELETE FROM `wp_options` WHERE `option_name` LIKE ('case_nm_api%');", OBJECT ); // @codingStandardsIgnoreLine
}
add_action( 'nm_clear_cache_events', 'nm_clear_cache_func' );


// Create schedule
if ( ! wp_next_scheduled( 'nm_clear_cache_events' ) ) {
	wp_schedule_event( time(), 'weekly', 'nm_clear_cache_events' );
}


