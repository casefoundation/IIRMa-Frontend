<?php

require_once dirname( __FILE__ ) . '/core/class-networkmapapi.php';


/**
 * Shortcode to return last timestamp update
 *
 * @param $atts
 * @return string
 */
function ap_timestamp_function( $atts ) {
	$api = new NetworkMapApi();

	$response           = $api->global( [ 'global_date_data' ] );
	$api_timestamp_date = date_parse( $response['impactspace']['end_time'] );
	return 'Data updated ' . $api_timestamp_date['month'] . '/' .
		$api_timestamp_date['day'] . '/' . $api_timestamp_date['year'];
}
add_shortcode( 'ap_timestamp', 'ap_timestamp_function' );


/**
 * Shortcode to return top investors
 *
 * @param $atts
 * @return int|string
 */
function top_investors_function( $atts ) {
	$field = $atts['field'];

	$api = new NetworkMapApi();

	switch ( $field ) {
		case 'percent':
			$total_companies_with_funds = $api->global( [ 'total_companies_with_funds' ] );
			$top_20                     = $api->investors( [ 'top_companies', '20' ] );

			return (int) ( (float) ( $top_20['total_companies'] /
					$total_companies_with_funds['total_companies_with_funds'] ) * 100 );
			break;
		case 'companies_number':
			$top_20 = $api->investors( [ 'top_companies', '20' ] );
			return number_format( $top_20['total_companies'] );
			break;
	}

	return '';
}
add_shortcode( 'api_top_investors', 'top_investors_function' );

/**
 * Shortcode to return global statistic data
 *
 * @param $atts
 * @return string
 */
function global_data_function( $atts ) {
	$field = $atts['field'];

	$api = new NetworkMapApi();

	switch ( $field ) {
		case 'companies':
			$total_companies_with_funds = $api->global( [ 'global_data' ] );
			return number_format( $total_companies_with_funds['total_companies'] );
			break;
		case 'investments':
			$total_companies_with_funds = $api->global( [ 'global_data' ] );
			return '$' . bd_nice_number( $total_companies_with_funds['total_investment'] );
			break;
		case 'investors':
			$total_companies_with_funds = $api->global( [ 'global_data' ] );
			return number_format( $total_companies_with_funds['total_investors'] );
			break;
	}

	return '';
}
add_shortcode( 'api_global_data', 'global_data_function' );

/**
 * Shortcode to make calls to api
 *
 * @param $atts
 * @return string
 */
function api_call_function( $atts ) {
	$api       = new NetworkMapApi();
	$type      = $atts['type'];
	$call_atts = isset( $atts['params'] ) ? explode( ',', $atts['params'] ) : [];

	$get_params = null;
	if ( isset( $atts['get'] ) ) {
		$get_atts = explode( ',', $atts['get'] );
		foreach ( $get_atts as $get_att ) {
			$get_att_values                   = explode( '=', $get_att );
			$get_params[ $get_att_values[0] ] = $get_att_values[1];
		}
	}

	$field = $atts['field'];

	switch ( $type ) {
		case 'investors':
			return api_call_investors( $api, $call_atts, $get_params, $field );
			break;
		case 'companies':
			return api_call_companies( $api, $call_atts, $get_params, $field );
			break;
		case 'global':
			return api_call_global( $api, $call_atts, $get_params, $field );
			break;
	}
	return '[callapi]';
}
add_shortcode( 'api_call', 'api_call_function' );


/**
 * Return investor function call
 *
 * @param $api
 * @param $call_atts
 * @param $get_params
 * @param $field
 * @return string
 */
function api_call_investors( $api, $call_atts, $get_params, $field ) {
	$response = $api->investors( $call_atts, $get_params );

	if ( 'total_fundings_number' === $field ) {
		$total_fundings_number = 0;
		foreach ( $response as $investor ) {
			$total_fundings_number += $investor['total_funds'];
		}
		return number_format( $total_fundings_number );
	} elseif ( 'total_companies_number' === $field ) {
		$total_companies_number = 0;

		foreach ( $response as $investor ) {
			$total_companies_number += $investor['total_companies'];
		}
		return number_format( $total_companies_number );
	} elseif ( 'percentage_disclosed' === $field ) {
		$investors = $api->investors();
		$total     = $response['total'];
		foreach ( $response['data'] as $count ) {
			if ( 'unknown' === $count['legal_structure'] ) {
				$total -= $count['count'];
			}
		}
		return number_format( ( $total / $investors['total'] ) * 100, 1 );
	} elseif ( 'most_frequent' === $field ) {
		$data = $response['data'];
		foreach ( $data as $count ) {
			if ( 'unknown' !== $count['legal_structure'] ) {
				if ( ! isset( $frequent ) ) {
					$frequent = $count;
				} elseif ( $count['count'] > $frequent['count'] ) {
					$frequent = $count;
				}
			}
		}

		return ucwords( $frequent['legal_structure'] );
	}
	return $response[ $field ];
}

/**
 * Return company function call
 *
 * @param $api
 * @param $call_atts
 * @param $get_params
 * @param $field
 * @return string
 */
function api_call_companies( $api, $call_atts, $get_params, $field ) {
	$response = $api->companies( $call_atts, $get_params );
	if ( 'percentage_disclosed' === $field ) {
		$companies = $api->companies();

		$total = $response['total'];
		foreach ( $response['data'] as $count ) {
			if ( 'unknown' === $count['legal_structure'] ) {
				$total -= $count['count'];
			}
		}
		return number_format( ( $total / $companies['total'] ) * 100, 1 );
	} elseif ( 'most_frequent' === $field ) {
		$data = $response['data'];
		foreach ( $data as $count ) {
			if ( 'unknown' !== $count['legal_structure'] ) {
				if ( ! isset( $frequent ) ) {
					$frequent = $count;
				} elseif ( $count['count'] > $frequent['count'] ) {
					$frequent = $count;
				}
			}
		}

		return ucwords( $frequent['legal_structure'] );
	}
}

/**
 * Return global function call
 *
 * @param $api
 * @param $call_atts
 * @param $get_params
 * @param $field
 * @return string
 */
function api_call_global( $api, $call_atts, $get_params, $field ) {
	$response = $api->global( $call_atts, $get_params );

	if ( 'io_investors_percentage_disclosed' === $field ||
		'io_companies_percentage_disclosed' === $field ) {
		return global_percentage_disclosed( $api, $field, $response );
	} elseif ( 'io_investors_most_frequent_number' === $field ||
		'io_companies_most_frequent_number' === $field ) {
		return global_most_frequent_number( $field, $response );
	} elseif ( 'io_investors_most_frequent_name' === $field ||
		'io_companies_most_frequent_name' === $field ) {
		return global_most_frequent_name( $field, $response );
	} elseif ( 'investors_disclosed_percentage' === $field ||
		'companies_disclosed_percentage' === $field ) {
		return global_disclosed_percentage( $api, $field, $response );
	} elseif ( 'investor_most_frequently_geography' === $field ||
		'company_most_frequently_geography' === $field ) {
		return global_most_frequently_geography( $field, $response );
	} elseif ( 'round_disclosed_percentage' === $field ) {
		$fundings_count = 0;
		foreach ( $response['funds_types'] as $funding_count ) {
			if ( 'unknown' !== $funding_count['type'] ) {
				$fundings_count += $funding_count['count'];
			}
		}
		return number_format( ( $fundings_count / $response['total_funds'] ) * 100, 1 );
	} elseif ( 'amount_disclosed_percentage' === $field ) {
		return number_format(
			( $response['total_funds_without_0'] /
				$response['total_funds'] ) * 100, 1
		);
	}
}

/**
 * Return Global Percentage Disclosed for Companies or Investors
 *
 * @param $api
 * @param $field
 * @param $response
 * @return string
 */
function global_percentage_disclosed( $api, $field, $response ) {
	if ( 'io_investors_percentage_disclosed' === $field ) {
		$data         = $api->investors();
		$response_str = 'total_investors_with_impact_objectives';
	} else {
		$data         = $api->companies();
		$response_str = 'total_companies_with_impact_objectives';
	}

	return number_format( ( $response[ $response_str ] / $data['total'] ) * 100, 1 );
}

/**
 * Return Global Most Frequent Number for Companies or Investors
 *
 * @param $field
 * @param $response
 * @return mixed
 */
function global_most_frequent_number( $field, $response ) {
	$most_frequent = [ 0, 0 ];
	if ( 'io_investors_most_frequent_number' === $field ) {
		$response_str = 'investors_count_by_io';
	} else {
		$response_str = 'companies_count_by_io';
	}
	foreach ( $response[ $response_str ] as $number => $count ) {
		if ( 0 !== $number ) {
			if ( ! isset( $most_frequent ) ) {
				$most_frequent = [ $number, $count ];
			} else {
				if ( $count > $most_frequent[1] ) {
					$most_frequent = [ $number, $count ];
				}
			}
		}
	}
	return $most_frequent[0];
}

/**
 * Return Global Most Frequent Name for Companies or Investors
 *
 * @param $field
 * @param $response
 * @return string
 */
function global_most_frequent_name( $field, $response ) {
	if ( 'io_investors_most_frequent_name' === $field ) {
		$response_str = 'io_with_investors_count';
	} else {
		$response_str = 'io_with_companies_count';
	}
	$data  = $response[ $response_str ];
	$first = $data[0];
	return ucwords( $first['name'] );
}

/**
 * Return Global Disclosed Percentage for Companies or Investors
 *
 * @param $api
 * @param $field
 * @param $response
 * @return string
 */
function global_disclosed_percentage( $api, $field, $response ) {
	$data_count = 0;
	if ( 'investors_disclosed_percentage' === $field ) {
		$data = $api->investors();
	} else {
		$data = $api->companies();
	}
	foreach ( $response['geographic_areas_with_investors'] as $country_count ) {
		if ( 'Undisclosed' !== $country_count['name'] ) {
			$data_count += $country_count['count'];
		}
	}
	return number_format( ( $data_count / $data['total'] ) * 100, 1 );
}

/**
 * Return Global Most Frequently Geography for Companies or Investors
 *
 * @param $field
 * @param $response
 * @return string
 */
function global_most_frequently_geography( $field, $response ) {
	if ( 'investor_most_frequently_geography' === $field ) {
		$data = $response['geographic_areas_with_investors'];
	} else {
		$data = $response['geographic_areas_with_companies'];
	}
	$frequent = [];
	foreach ( $data as $count ) {
		if ( 'Undisclosed' !== $count['name'] ) {
			if ( ! isset( $frequent ) ) {
				$frequent = $count;
			} elseif ( $count['count'] > $frequent['count'] ) {
				$frequent = $count;
			}
		}
	}
	return ucwords( $frequent['name'] );
}

/**
 *  Return json from api call
 */
function ajax_get_data() {
	$type = !empty( $_REQUEST['type'] ) ? $_REQUEST['type']: ''; // @codingStandardsIgnoreLine

	$params = [];
	foreach ( $_REQUEST as $key => $val ) { // @codingStandardsIgnoreLine
		if ( 'type' !== $key && 'action' !== $key ) {
			if ( strrpos( $val, '[][]' ) !== false ) { //is array
				$params[ $key ] = explode( '[][]', $val );
			} else {
				$params[ $key ] = $val;
			}
		}
	}
	$cache_revalidate = isset( $_REQUEST['cache_revalidate'] ) ? $_REQUEST['cache_revalidate'] : 0; // @codingStandardsIgnoreLine
	$skip_cache       = ( 1 === $cache_revalidate ) ? true : false;
	$json_graph       = get_data( $type, null, $params, $skip_cache );

	echo wp_json_encode( $json_graph );

	wp_die();
}

/**
 * Get Data from Api
 *
 * @param $type
 * @param null $call_methods
 * @param null $get_params
 * @param bool $skip_cache
 * @return array
 */
function get_data( $type, $call_methods = null, $get_params = null, $skip_cache = false ) {
	$companies_count         = 0;
	$investors_count         = 0;
	$investments_count       = 0;
	$highest_fund            = 0;
	$api                     = new NetworkMapApi();
	$get_params['show_path'] = true;

	if ( 'investor' === $type ) {
		$response = $api->investors( $call_methods, $get_params, null, $skip_cache );
	} elseif ( 'company' === $type ) {
		$response = $api->companies( $call_methods, $get_params, null, $skip_cache );
	}
	$child_type = 'company' === $type ? 'investor' : 'company';
	$nodes      = [
		'company'  => [],
		'investor' => [],
	];

	process_get_data_info(
		$response,
		$companies_count,
		$investors_count,
		$investments_count,
		$highest_fund,
		$nodes,
		$child_type,
		$type
	);

	// PREPARE RESULTS
	$json_graph = [
		'graph'      => [
			'nodes' => $nodes,
		],
		'info'       => [
			'companies_count'   => $companies_count,
			'investors_count'   => $investors_count,
			'investments_count' => $investments_count,
			'highest_fund'      => $highest_fund,
		],
		'pagination' => [
			'current_page' => $response['current_page'],
			'total'        => $response['total'],
			'per_page'     => $response['per_page'],
			'current_page' => $response['current_page'],
			'from'         => $response['from'],
			'to'           => $response['to'],
		],
	];

	return $json_graph;
}
add_action( 'wp_ajax_get_data', 'ajax_get_data' );
add_action( 'wp_ajax_nopriv_get_data', 'ajax_get_data' );


/**
 * Process Ged Data Information
 *
 * @param $response
 * @param $companies_count
 * @param $investors_count
 * @param $investments_count
 * @param $highest_fund
 * @param $nodes
 * @param $child_type
 * @param $type
 */
function process_get_data_info(
	&$response,
	&$companies_count,
	&$investors_count,
	&$investments_count,
	&$highest_fund,
	&$nodes,
	$child_type,
	$type
) {
	foreach ( $response['data'] as $item ) {
		$item_ob = [
			'i' => $item['id'],
			'n' => $item['name'],
		];

		list($companies_count, $investors_count) =
			incress_data( $type, $companies_count, $investors_count );
		// SUM FUNDINGS
		list($highest_fund,$sum_funds) = sum_funds( $item_ob, $item, $highest_fund );

		if ( isset( $item['investors'] ) ) {
			foreach ( $item['investors'] as $index => $investment ) {
				// CHECK IF INVESTMENT ALREADY EXISTS
				$already_exists = false;
				foreach ( $nodes[ $child_type ] as $node ) {
					if ( $node['i'] === $investment['id'] ) {
						$already_exists = true;
						$investment_ob  = $node;
						break;
					}
				}

				list($highest_fund,$sum_funds) = sum_funds( $item_ob, $investment, $highest_fund );
				$investments_count++;
				if ( ! $already_exists ) {
					$investment_ob                           = [
						'i' => $investment['id'],
						'n' => $investment['name'],
						'f' => $sum_funds,
					];
					list($companies_count, $investors_count) =
						incress_data( $child_type, $companies_count, $investors_count );

					if ( 0 === $investment_ob['f'] ) {
						unset( $investment_ob['f'] );
					}
					$nodes[ $child_type ][] = $investment_ob;
				}
			}
		}
		if ( 0 === $item_ob['f'] ) {
			unset( $item_ob['f'] );
		}
		if ( ! empty( $item_ob['n'] ) ) {
			$nodes[ $type ][] = $item_ob;
		}
	}
}

/**
 * Incress counters
 *
 * @param $child_type
 * @param $companies_count
 * @param $investors_count
 * @return array
 */
function incress_data( $child_type, $companies_count, $investors_count ) {
	if ( 'company' === $child_type ) {
		$companies_count++;
	} elseif ( 'investor' === $child_type ) {
		$investors_count++;
	}
	return [ $companies_count, $investors_count ];
}

/**
 * Return sum of funds
 *
 * @param $item_ob
 * @param $item
 * @param $highest_fund
 * @return array
 */
function sum_funds( &$item_ob, $item, $highest_fund ) {
	$sum_funds = 0;
	if ( isset( $item['funds'] ) ) {
		$sum_funds = count( $item['funds'] );

		if ( $sum_funds > $highest_fund ) {
			$highest_fund = $sum_funds;
		}

		$item_ob['f'] = $sum_funds;
	}
	return [ $highest_fund, $sum_funds ];
}


/**
 * Return graph json data
 */
function ajax_get_graph() {
	$type = !empty( $_REQUEST['type'] ) ? $_REQUEST['type']: ''; // @codingStandardsIgnoreLine

	$params = [];

	$cache_key = '';

	foreach ( $_REQUEST as $key => $val ) { // @codingStandardsIgnoreLine
		if ( 'cache_revalidate' === $key ) {
			continue;
		}

		$cache_key .= "$key|$val|";
		if ( 'type' !== $key && 'action' !== $key ) {
			if ( strrpos( $val, '[][]' ) !== false ) { //is array
				$params[ $key ] = explode( '[][]', $val );
			} else {
				$params[ $key ] = $val;
			}
		}
	}
	$cache_key  = 'case_api-' . NetworkMapApi::CACHE_VERSION . '-graph-' . sha1( $cache_key );
	$json_graph = get_transient( $cache_key );
	if ( ! $json_graph || 1 === $_REQUEST['cache_revalidate'] ) { // @codingStandardsIgnoreLine
		$json_graph = wp_json_encode( get_graph_data( $type, null, $params ) );
		set_transient( $cache_key, $json_graph, 3600 * 12 );
	} elseif ( json_decode( $json_graph ) && ! json_decode( $json_graph )->counts->company ) {
		$json_graph = wp_json_encode( get_graph_data( $type, null, $params ) );
		set_transient( $cache_key, $json_graph, 3600 * 12 );
	}

	ini_set( 'zlib.output_compression', 4096 ); // @codingStandardsIgnoreLine
	ini_set( 'zlib.output_compression_level', 9 ); // @codingStandardsIgnoreLine
	header( 'Content-Type: application/json;' );
	echo $json_graph; // @codingStandardsIgnoreLine

	wp_die();
}
add_action( 'wp_ajax_get_graph', 'ajax_get_graph' );
add_action( 'wp_ajax_nopriv_get_graph', 'ajax_get_graph' );


/**
 * Return Graph Data
 *
 * @param $type
 * @param null $call_methods
 * @param null $get_params
 * @return array
 */
function get_graph_data( $type, $call_methods = null, $get_params = null ) {
	$api                     = new NetworkMapApi();
	$get_params['show_path'] = true;

	if ( 'investor' === $type ) {
		$response = $api->investors( $call_methods, $get_params );
	} elseif ( 'company' === $type ) {
		$response = $api->companies( $call_methods, $get_params );
	}

	$nodes = [
		'company'  => [],
		'investor' => [],
	];
	$edges = [];

	$counts = [
		'company'    => 0,
		'investor'   => 0,
		'investment' => 0,
		'max_edges'  => 0,
	];

	process_get_graph_info( $response, $nodes, $edges, $counts, $type );

	$json_graph = [
		'counts'     => $counts,
		'pagination' => [
			'current_page' => $response['current_page'],
			'total'        => $response['total'],
			'per_page'     => $response['per_page'],
			'current_page' => $response['current_page'],
			'from'         => $response['from'],
			'to'           => $response['to'],
		],
		'graph'      => [
			'nodes' => [
				'company'  => array_values( $nodes['company'] ),
				'investor' => array_values( $nodes['investor'] ),
			],
			'edges' => $edges,
		],

	];

	return $json_graph;
}

/**
 * Process get graph data
 * @param $response
 * @param $nodes
 * @param $edges
 * @param $counts
 * @param $type
 */
function process_get_graph_info( &$response, &$nodes, &$edges, &$counts, $type ) {
	$related_type = 'company' === $type ? 'investor' : 'company';
	foreach ( $response['data'] as $item ) {
		$counts[ $type ]++;
		$node = [
			'i' => $item['id'],
			'n' => $item['name'],
		];

		if ( isset( $item['investors'] ) ) {

			foreach ( $item['investors'] as $i => $related_item ) {
				$related_item_id = $related_item['id'];
				if ( ! isset( $nodes[ $related_type ][ $related_item_id ] ) ) {
					$nodes[ $related_type ][ $related_item_id ] = [
						'i' => $related_item['id'],
						'n' => $related_item['name'],
						'e' => 0,
					];
					$counts[ $related_type ]++;
				}

				$edge = [
					'i' => $item['funds'][ $i ]['id'],
				];
				// Make sure that every edge points from investor to company
				if ( 'investor' === $type ) {
					$edge['s'] = $node['i'];
					$edge['t'] = $related_item_id;
				} elseif ( 'company' === $type ) {
					$edge['s'] = $related_item_id;
					$edge['t'] = $node['i'];
				}
				$edges[] = $edge;
				$nodes[ $related_type ][ $related_item_id ]['e']++;

				if ( $counts['max_edges'] < $nodes[ $related_type ][ $related_item_id ]['e'] ) {
					$counts['max_edges'] = $nodes[ $related_type ][ $related_item_id ]['e'];
				}
			}
			$edges_count           = count( $item['investors'] );
			$node['e']             = $edges_count;
			$counts['investment'] += $edges_count;

			if ( $counts['max_edges'] < $edges_count ) {
				$counts['max_edges'] = $edges_count;
			}
		}

		$nodes[ $type ][ $node['i'] ] = $node;
	}
}

/**
 * Return Filters Data
 * @return array
 */
function get_filters_data() {
	$api = new NetworkMapApi();

	$investor_attributes = $api->investor_attributes();
	$company_attributes  = $api->company_attributes();

	$filters_arr = [
		'company'  => analyze_attributes( $company_attributes ),
		'investor' => analyze_attributes( $investor_attributes ),
	];

	return $filters_arr;
}


/**
 * Analyze and return attributes data for companies and investors
 * @param $atts
 * @return array
 */
function analyze_attributes( $atts ) {

	$atts_return = [];

	foreach ( $atts as $field => $attributes ) {

		$atts_arr = [];

		if ( 'geography' === $field ) {

			$atts_arr = array_merge(
				$atts_arr,
				analyze_graphic_node( $attributes['global'], 'global' )
			);

		} elseif ( 'impact_objective' === $field ) {

			foreach ( $attributes as $sub_att_name => $sub_attributes ) {

				$atts_arr = array_merge( $atts_arr, [ $sub_att_name => $sub_attributes ] );

			}
		} else {

			$atts_arr = $attributes;

		}

		$atts_return[ str_replace( '_', ' ', $field ) ] = $atts_arr;
	}

	return $atts_return;
}

/**
 * Analyze Graphic Node
 *
 * @param $array
 * @param $key
 * @return array
 */
function analyze_graphic_node( $array, $key ) {
	$acum = [];

	if ( isset( $array['regions'] ) || isset( $array['countries'] ) ) {

		$analyze_array = isset( $array['regions'] ) ? $array['regions'] : $array['countries'];

		if ( count( $analyze_array ) > 0 ) {

			foreach ( $analyze_array as $key => $child ) {

				$acum = array_merge( $acum, analyze_graphic_node( $child, $key ) );

			}

			if ( isset( $array['countries'] ) ) {
				if ( isset( $array['name'] ) ) {
					$acum = [ strtolower( $array['name'] ) => $acum ];
				}
			}
		} else {

			$acum[ $key ] = strtolower( $array['name'] );

		}
	} else {

		$acum[ $key ] = strtolower( $array['name'] );

	}

	return $acum;
}

/**
 *  Return json Table data
 */
function ajax_get_table() {
	$type = !empty( $_REQUEST['type'] ) ? $_REQUEST['type']: ''; // @codingStandardsIgnoreLine

	$params = [];
	foreach ( $_REQUEST as $key => $val ) { // @codingStandardsIgnoreLine
		if ( 'type' !== $key && 'action' !== $key ) {
			if ( strrpos( $val, ',' ) !== false ) { //is array
				$params[ $key ] = explode( ',', $val );
			} else {
				$params[ $key ] = $val;
			}
		}
	}

	$data = get_graph_data_table( $type, $params );

	echo wp_json_encode( $data );

	wp_die();
}
add_action( 'wp_ajax_get_table', 'ajax_get_table' );
add_action( 'wp_ajax_nopriv_get_table', 'ajax_get_table' );

/**
 * Return Data Table Information
 * @param $type
 * @param null $params
 * @return array
 */
function get_graph_data_table( $type, $params = null ) {
	$data       = get_graph_data( $type, null, $params );
	$graph_info = $data['graph'];

	$only_companies = true;
	foreach ( $graph_info['nodes'] as $node ) {
		$only_companies &= 'investor' !== $node['role'];
	}

	$return_arr = get_table_arr_for_type( $type );
	foreach ( $graph_info['nodes'] as $node ) {
		if ( 'investor' === $type ) {
			if ( 'investor' === $node['role'] ) {
				$return_arr[0]['childs'][] = $node['id'];
				$return_arr[1]['childs'][] = $node['caption'];
				$return_arr[2]['childs'][] = $node['funding_total'];
			}
		} else {
			if ( 'company' === $node['role'] ) {
				$return_arr[0]['childs'][] = $node['id'];
				$return_arr[1]['childs'][] = $node['caption'];
			}
		}
	}
	$data['table'] = $return_arr;

	return $data;
}


/**
 * Return specific array for each type
 *
 * @param $type
 * @return array
 */
function get_table_arr_for_type( $type ) {
	if ( 'investor' === $type ) {
		$return_arr = [
			[
				'name'   => 'id',
				'attr'   => 'id',
				'childs' => [],
			],
			[
				'name'   => 'Investor or Fund',
				'attr'   => 'name',
				'childs' => [],
			],
			[
				'name'   => '# of Investments',
				'attr'   => 'investments',
				'childs' => [],
			],
		];
	} else {
		$return_arr = [
			[
				'name'   => 'id',
				'attr'   => 'id',
				'childs' => [],
			],
			[
				'name'   => 'Company',
				'attr'   => 'name',
				'childs' => [],
			],
		];
	}
	return $return_arr;
}

/**
 * Return json node information
 */
function ajax_get_node() {
	try {
		$type = !empty( $_REQUEST['type'] ) ? $_REQUEST['type']: ''; // @codingStandardsIgnoreLine
		$id = !empty( $_REQUEST['id'] ) ? $_REQUEST['id']: ''; // @codingStandardsIgnoreLine

		$api = new NetworkMapApi();

		if ( 'company' === $type ) {
			$response = $api->company( [ $id ] );
		} else {
			if ( 'investor' === $type ) {
				$response = $api->investor( [ $id ] );
			}
		}
		if ( isset( $response['type'] ) ) {
			$response['investor_type'] = $response['type'];
		}
		$response['type'] = $type;

		echo wp_json_encode( $response );
	} catch ( Exception $e ) {
		$error = [
			'status'  => 'error',
			'message' => $e->getMessage(),
		];
		if ( defined( WP_DEBUG ) && WP_DEBUG ) {
			$error['trace'] = $e->getTrace();
		}

		echo wp_json_encode( $error );
	}
	wp_die();
}
add_action( 'wp_ajax_get_node', 'ajax_get_node' );
add_action( 'wp_ajax_nopriv_get_node', 'ajax_get_node' );

/**
 * Return Search Node Json
 */
function ajax_search_node() {
	$keywords = !empty( $_REQUEST['keywords'] ) ? $_REQUEST['keywords']: ''; // @codingStandardsIgnoreLine
	$timestamp = !empty( $_REQUEST['timestamp'] ) ? $_REQUEST['timestamp']: ''; // @codingStandardsIgnoreLine

	$api = new NetworkMapApi();

	$companies = $api->companies( null, [ 'search' => $keywords ] );
	$investors = $api->investors( null, [ 'search' => $keywords ] );

	$results = [];
	foreach ( $companies['data'] as $company ) {
		$company['type'] = 'company';
		$results[]       = $company;
	}
	foreach ( $investors['data'] as $investor ) {
		$investor['type'] = 'investor';
		$results[]        = $investor;
	}

	echo wp_json_encode(
		[
			'timestamp' => $timestamp,
			'response'  => $results,
		]
	);

	wp_die();
}
add_action( 'wp_ajax_search_node', 'ajax_search_node' );
add_action( 'wp_ajax_nopriv_search_node', 'ajax_search_node' );

/**
 * Return Api timestamp Json
 */
function ajax_get_api_timestamp() {

	$api = new NetworkMapApi();

	$response = $api->global( [ 'global_date_data' ], null, null, true );
	echo wp_json_encode( [ 'timestamp' => $response['impactspace']['end_time'] ] );

	wp_die();
}
add_action( 'wp_ajax_get_api_timestamp', 'ajax_get_api_timestamp' );
add_action( 'wp_ajax_nopriv_get_api_timestamp', 'ajax_get_api_timestamp' );


/**
 * Output easy-to-read numbers
 * by james at bandit.co.nz
 *
 * @param $n
 * @return bool|string
 */
function bd_nice_number( $n ) {
	// first strip any formatting;
	$n = ( 0 + str_replace( ',', '', $n ) );

	// is this a number?
	if ( ! is_numeric( $n ) ) {
		return false;
	}

	// now filter it;
	if ( $n > 1000000000000 ) {
		return round( ( $n / 1000000000000 ), 1 ) . ' trillion';
	} elseif ( $n > 1000000000 ) {
		return round( ( $n / 1000000000 ), 1 ) . ' billion';
	} elseif ( $n > 1000000 ) {
		return round( ( $n / 1000000 ), 1 ) . ' million';
	} elseif ( $n > 1000 ) {
		return round( ( $n / 1000 ), 1 ) . ' thousand';
	}

	return number_format( $n );
}
