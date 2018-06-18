<?php
class NetworkMapApi {
	const CACHE_VERSION = 1;

	private $username;
	private $password;
	private $webservice;

	private $token;
	private $api_version = NETWORKMAP_API_VERSION;

	private $method = false;

	/**
	 * Return new NetworkMapApi instance
	 * @return NetworkMapApi
	 */
	static function get_api() {
		return new NetworkMapApi();
	}

	/**
	 * NetworkMapApi constructor.
	 * @param null $parent
	 * @param string $proto_method
	 * @throws Exception
	 */
	function __construct( $parent = null, $proto_method = '' ) {
		if ( null === $parent ) {
			// GET CONFIGURATIONS
			$this->username   = NETWORKMAP_API_USER;
			$this->password   = NETWORKMAP_API_PASS;
			$this->webservice = NETWORKMAP_API_ENDPOINT;

			if ( empty( $this->username ) ||
				empty( $this->password ) ||
				empty( $this->webservice ) ) {
				$exception_string = 'Credentials or endpoint missing for connecting Networkmap API';
				throw new Exception( $exception_string );
			}
		} else {
			$this->username   = $parent->username;
			$this->password   = $parent->password;
			$this->webservice = $parent->webservice;
			$this->method     = ( $parent->method ? $parent->method . '.' : '' ) . $proto_method;
		}
	}

	/**
	 * Call method sent
	 *
	 * Params Order
	 * 0 - API method
	 * 1 - Call _GET parameters
	 * 2 - Call _POST parameters
	 * 3 - (boolean) force use cache flag
	 *
	 * @param $method
	 * @param $params
	 * @return array|mixed|object|void
	 */
	function __call( $method, $params ) {
		list($api_method, $get_parameters, $post_parameters, $skip_cache) = $params;
		$sub_methods = '';

		if ( ! is_null( $api_method ) ) {
			if ( is_array( $api_method ) ) {
				$sub_methods = '/' . implode( '/', $api_method );
			} else {
				$sub_methods = '/' . $api_method;
			}
		}

		if ( is_array( $get_parameters ) ) {
			ksort( $get_parameters );
		}
		if ( is_array( $post_parameters ) ) {
			ksort( $post_parameters );
			$post_string = http_build_query( $post_parameters, '', '&', PHP_QUERY_RFC3986 );
		} else {
			$post_string = '';
		}

		$query_string = is_array( $get_parameters ) && ! empty( $get_parameters ) ?
			'?' . http_build_query( $get_parameters, '', '&', PHP_QUERY_RFC3986 )
			: '';

		$cache_key = 'case_api-' . self::CACHE_VERSION . '-' .
			sha1( $method . $sub_methods . $query_string . $post_string );
		$result    = get_transient( $cache_key );

		if ( empty( $result ) || $skip_cache ) {
			$this->check_authentication();

			$result = $this->load_file_from_url(
				$this->webservice . $method . $sub_methods . $query_string,
				$post_parameters
			);

			if ( empty( $result ) || ( isset( $result['status_code'] ) &&
					intval( $result['status_code'] ) >= 400 ) ) {
				// Service failed, getting last valid response
				$result = get_option( $cache_key, [] );
			} else {
				// Updating transient and db stored option
				$expires = defined( 'CASE_NETWORK_MAP_API_CACHE_EXPIRATION' ) ?
					CASE_NETWORK_MAP_API_CACHE_EXPIRATION :
					86400;
				set_transient( $cache_key, $result, $expires );
				update_option( $cache_key, $result, false );
			}
		}

		return $result;
	}


	/**
	 * Load file from a URL
	 *
	 * @param $url
	 * @param null $post_params
	 * @return array|mixed|object
	 */
	private function load_file_from_url( $url, $post_params = [], $review_auth = true ) {
		$args   = $post_params;
		$method = 'GET';
		if ( count( $post_params ) ) {
			$method = 'POST';
		}
		$headers = array();
		if ( isset( $this->token ) ) {
			$headers = array(
				'Accept'        => "application/x.networkmap.$this->api_version+json",
				'Authorization' => "Bearer $this->token",
			);
		}
		$request = array(
			'headers' => $headers,
			'method'  => $method,
		);

		if ( 'GET' === $method && ! empty( $args ) && is_array( $args ) ) {
			$url = add_query_arg( $args, $url );
		} else {
			$request['body'] = $args;
		}
		$response = wp_remote_request( $url, $request );

		$result = json_decode( $response['body'], true );
		if ( isset( $result['error'] ) &&
			'invalid_credentials' === $result['error'] &&
			$review_auth
		) {

			unset( $this->token );
			$this->check_authentication();
			$response = wp_remote_request( $url, $request );
			$result   = json_decode( $response['body'], true );
		}

		return $result;
	}


	/**
	 * Verify authentication
	 */
	private function check_authentication() {
		if ( ! isset( $this->token ) ) {

			$result      = $this->load_file_from_url(
				$this->webservice . 'authenticate', [
					'email'    => $this->username,
					'password' => $this->password,
				], false
			);
			$this->token = $result['token'];

		}
	}

}
