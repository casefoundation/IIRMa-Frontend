<?php

require_once dirname( __FILE__ ) . '/inc/api/api-functions.php';

if ( ! function_exists( 'network_map_setup' ) ) :
	/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
	function network_map_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on inclusive-entrepreneurship,
		 * use a find and replace to change 'inclusive-entrepreneurship'
		 * to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'network-map', get_template_directory() . '/languages' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link
		 * https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary', 'network-map' ),
				'footer'  => 'Footer Menu',
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

	}
endif;
add_action( 'after_setup_theme', 'network_map_setup' );


/**
 * Enqueue scripts and styles.
 */
function network_map_scripts() {
	$styles_version  = '1.1.9';
	$scripts_version = '1.1.28';

	/****************
	 * STYLES
	 * ****************
	 */
	// VENDORS
	register_vendor_styles();

	// IN HOUSE
	wp_enqueue_style(
		'nm-csl-icons',
		get_template_directory_uri() . '/css/nm-icons.css',
		array( 'font-awesome' ),
		$styles_version,
		'all'
	);
	wp_enqueue_style(
		'nm-csl-theme',
		get_template_directory_uri() . '/css/style.css',
		array( 'font-awesome' ),
		$styles_version,
		'all'
	);

	/* ****************
	 * SCRIPTS
	 * ****************
	 */
	// VENDORS
	register_vendors_scripts();

	// EXPLORER
	register_explorer_scripts( $scripts_version );

	// IN HOUSE
	wp_register_script( 'nm-csl-scripts', get_template_directory_uri() . '/js/main.js', array( 'jquery', 'debouncedresize' ), $scripts_version ); // Custom scripts
	wp_enqueue_script( 'nm-csl-scripts' ); // Enqueue it!

	wp_localize_script(
		'nm-csl-scripts', 'ajax_object', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'root_url' => get_home_url(),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'network_map_scripts' );

/**
 * Register Explorer page scripts
 * @param $scripts_version
 */
function register_explorer_scripts( $scripts_version ) {
	if ( is_page( 'explore' ) ) {
		wp_register_script(
			'react',
			get_template_directory_uri() . '/js/react.min.js',
			null,
			'1.6.1'
		); // React js
		wp_register_script(
			'react-dom',
			get_template_directory_uri() . '/js/react-dom.min.js',
			array( 'react' ),
			'1.6.1'
		); // ReactDOM js

		wp_register_script(
			'sigma',
			get_template_directory_uri() . '/js/sigma/sigma.min.js',
			null,
			'1.2.0'
		);
		wp_register_script(
			'sigma-plugins-animate',
			get_template_directory_uri() . '/js/sigma/plugins/sigma.plugins.animate.min.js',
			array( 'sigma' )
		);
		wp_register_script(
			'sigma-layout-force-atlas-2',
			get_template_directory_uri() . '/js/sigma/plugins/sigma.layout.forceAtlas2.min.js',
			array( 'sigma' )
		);

		$network_map_deps = [
			'jquery',
			'react',
			'react-dom',
			'sigma',
			'sigma-plugins-animate',
			'sigma-layout-force-atlas-2',
			'tooltipster-bundle',
		];
		wp_register_script(
			'case-network-map',
			get_template_directory_uri() . '/js/explore.min.js',
			$network_map_deps, $scripts_version,
			true
		);

		wp_enqueue_script( 'case-network-map' ); // Enqueue it!
	}
}

/**
 * Register Vendor Styles
 */
function register_vendor_styles() {
	wp_enqueue_style(
		'font-awesome',
		get_template_directory_uri() . '/css/vendor/font-awesome/font-awesome.css',
		array(),
		'4.6.5',
		'all'
	);
	
	wp_enqueue_style(
		'font-montserrat',
		'https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700',
		array(),
		'1.0',
		'all'
	);
	
	wp_enqueue_style(
		'bootstrap-table',
		get_template_directory_uri() . '/css/vendor//bootstrap-table.css',
		array(),
		'4.6.3',
		'all'
	);
	wp_enqueue_style(
		'jquery-modal',
		get_template_directory_uri() . '/css/vendor/jquery.modal.css',
		array(),
		'1.2.3',
		'all'
	);
	wp_enqueue_style(
		'jquery-ui',
		get_template_directory_uri() . '/css/vendor/jquery-ui.min.css',
		array(),
		'1.2.3',
		'all'
	);
	wp_enqueue_style(
		'tooltipster-bundle',
		get_template_directory_uri() . '/css/vendor/tooltipster.bundle.min.css',
		array(),
		'1.0.0',
		'all'
	);
}

/**
 * Register Vendor Scripts
 */
function register_vendors_scripts() {
	wp_register_script( 'migrate', get_template_directory_uri() . '/js/migrate.min.js', array( 'jquery' ), '3.3.5' ); // Migrate
	wp_enqueue_script( 'migrate' ); // Enqueue it!

	wp_register_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), '3.3.5' ); // Bootstrap
	wp_enqueue_script( 'bootstrap' ); // Enqueue it!

	wp_register_script( 'plugin', get_template_directory_uri() . '/js/jquery.plugin.js', array( 'jquery' ), '3.3.6' ); // jQuery Plugin
	wp_enqueue_script( 'plugin' ); // Enqueue it!

	wp_register_script( 'debouncedresize', get_template_directory_uri() . '/js/jquery.debouncedresize.js', array( 'jquery' ), '3.3.5' ); // Debounced Resize
	wp_enqueue_script( 'debouncedresize' ); // Enqueue it!

	wp_register_script( 'bootstrap-table', get_template_directory_uri() . '/js/bootstrap-table.js', null, '0.4.2' ); // bootstrap-dataTables
	wp_enqueue_script( 'bootstrap-table' ); // Enqueue it!

	wp_register_script( 'jquery-modal', get_template_directory_uri() . '/js/jquery.modal.js', null, '1.2.3' ); // jQuery-modal
	wp_enqueue_script( 'jquery-modal' ); // Enqueue it!

	wp_register_script( 'chart', get_template_directory_uri() . '/js/Chart.min.js', null, '2.5.0' ); // Chart
	wp_enqueue_script( 'chart' ); // Enqueue it!

	wp_register_script( 'jquery-validate', get_template_directory_uri() . '/js/jquery.validate.min.js', null, '1.14.0' ); // jquery-validate
	wp_enqueue_script( 'jquery-validate' ); // Enqueue it!

	wp_register_script( 'google-charts', get_template_directory_uri() . '/js/loader.js', null, '1.14.0' ); // Google Charts
	wp_enqueue_script( 'google-charts' ); // Enqueue it!

	wp_register_script( 'scrollto', get_template_directory_uri() . '/js/jquery.scrollTo.min.js', null, '1.14.0' ); // Google Charts
	wp_enqueue_script( 'scrollto' ); // Enqueue it!

	wp_register_script( 'match-height', get_template_directory_uri() . '/js/jquery.matchHeight.js', null, '1.14.0' ); // Match Height
	wp_enqueue_script( 'match-height' ); // Enqueue it!

	wp_register_script( 'typed', get_template_directory_uri() . '/js/typed.min.js', null, '1.14.0' ); // Typed
	wp_enqueue_script( 'typed' ); // Enqueue it!

	wp_register_script( 'jquery-cookie', get_template_directory_uri() . '/js/jquery.cookie.js', [ 'jquery' ], '1.14.0' ); // Cookie
	wp_enqueue_script( 'jquery-cookie' ); // Enqueue it!

	wp_register_script( 'jquery-ui', get_template_directory_uri() . '/js/jquery-ui.min.js', [ 'jquery' ], '1.14.0' ); // jQuery UI
	wp_enqueue_script( 'jquery-ui' ); // Enqueue it!

	wp_register_script(
		'tooltipster-bundle',
		get_template_directory_uri() . '/js/tooltipster.bundle.min.js',
		[ 'jquery' ],
		'1.0.0'
	);
	wp_enqueue_script( 'tooltipster-bundle' );
}

/**
 * Options Page.
 */
if ( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page(
		array(
			'page_title' => 'Site Options',
			'menu_title' => 'Site Options',
			'menu_slug'  => 'site-options',
			'capability' => 'edit_posts',
			'redirect'   => false,
		)
	);

}

/**
 * Icons Shortcode
 * @param $atts
 * @param null $content
 * @return string
 */
function icon_shortcode( $atts, $content = null ) {
	$attributes = '';
	foreach ( $atts as $key => $val ) {
		$attributes .= ' ' . $key . '="' . $val . '"" ';
	}
	return "<strong $attributes>$content</strong>";
}
add_shortcode( 'icon', 'icon_shortcode' );

add_action(
	'init', function() {
		add_rewrite_rule(
			'^explore/([^/]+)/([^/]*)',
			'index.php?pagename=explore&node_type=$matches[1]&node_id=$matches[2]',
			'top'
		);
	}
);
add_filter(
	'query_vars', function( $vars ) {
		$vars[] = 'pagename';
		$vars[] = 'node_type';
		$vars[] = 'node_id';
		return $vars;
	}
);

require_once dirname( __FILE__ ) . '/inc/cron-tasks.php';


require dirname( __FILE__ ) . '/inc/class-tgm-plugin-activation.php';
require dirname( __FILE__ ) . '/inc/theme-require-plugins.php';
add_action( 'tgmpa_register', 'mb_register_required_plugins' );
