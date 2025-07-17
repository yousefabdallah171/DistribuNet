<?php
/**
 * Settings
 *
 * @package    wp-listings-directory
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}

class WP_Listings_Directory_Settings {

	/**
	 * Option key, and option page slug
	 * @var string
	 */
	private $key = 'wp_listings_directory_settings';

	/**
	 * Array of metaboxes/fields
	 * @var array
	 */
	protected $option_metabox = array();

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Constructor
	 * @since 1.0
	 */
	public function __construct() {
	
		add_action( 'admin_menu', array( $this, 'admin_menu' ) , 10 );

		add_action( 'admin_init', array( $this, 'init' ) );

		//Custom CMB2 Settings Fields
		add_action( 'cmb2_render_wp_listings_directory_title', 'wp_listings_directory_title_callback', 10, 5 );
		add_action( 'cmb2_render_wp_listings_directory_hidden', 'wp_listings_directory_hidden_callback', 10, 5 );

		add_action( "cmb2_save_options-page_fields", array( $this, 'settings_notices' ), 10, 3 );


		add_action( 'cmb2_render_api_keys', 'wp_listings_directory_api_keys_callback', 10, 5 );

		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-wp_listings_directory_listings_page_listing-settings", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	public function admin_menu() {
		//Settings
	 	$wp_listings_directory_settings_page = add_submenu_page( 'edit.php?post_type=listing', __( 'Settings', 'wp-listings-directory' ), __( 'Settings', 'wp-listings-directory' ), 'manage_options', 'listing-settings',
	 		array( $this, 'admin_page_display' ) );
	}

	/**
	 * Register our setting to WP
	 * @since  1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Retrieve settings tabs
	 *
	 * @since 1.0
	 * @return array $tabs
	 */
	public function wp_listings_directory_get_settings_tabs() {
		$tabs             	  = array();
		$tabs['general']  	  = __( 'General', 'wp-listings-directory' );
		$tabs['listing_submission']   = __( 'Listing Submission', 'wp-listings-directory' );
		$tabs['pages']   = __( 'Pages', 'wp-listings-directory' );
		$tabs['user_register_settings']   = __( 'User Register Settings', 'wp-listings-directory' );
		$tabs['review_settings'] = __( 'Review Settings', 'wp-listings-directory' );
	 	$tabs['api_settings'] = __( 'Social API', 'wp-listings-directory' );
	 	$tabs['recaptcha_api_settings'] = __( 'ReCaptcha API', 'wp-listings-directory' );
	 	$tabs['email_notification'] = __( 'Email Notification', 'wp-listings-directory' );

		return apply_filters( 'wp_listings_directory_settings_tabs', $tabs );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  1.0
	 */
	public function admin_page_display() {

		$active_tab = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $this->wp_listings_directory_get_settings_tabs() ) ? $_GET['tab'] : 'general';
		
		?>

		<div class="wrap wp_listings_directory_settings_page cmb2_options_page <?php echo $this->key; ?>">
			<h2 class="nav-tab-wrapper">
				<?php
				foreach ( $this->wp_listings_directory_get_settings_tabs() as $tab_id => $tab_name ) {

					$tab_url = esc_url( add_query_arg( array(
						'settings-updated' => false,
						'tab'              => $tab_id
					) ) );

					$active = $active_tab == $tab_id ? ' nav-tab-active' : '';

					echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">';
					echo esc_html( $tab_name );

					echo '</a>';
				}
				?>
			</h2>
			
			<?php cmb2_metabox_form( $this->wp_listings_directory_settings( $active_tab ), $this->key ); ?>

		</div><!-- .wrap -->

		<?php
	}

	/**
	 * Define General Settings Metabox and field configurations.
	 *
	 * Filters are provided for each settings section to allow add-ons and other plugins to add their own settings
	 *
	 * @param $active_tab active tab settings; null returns full array
	 *
	 * @return array
	 */
	public function wp_listings_directory_settings( $active_tab ) {

		$pages = wp_listings_directory_cmb2_get_page_options( array(
			'post_type'   => 'page',
			'numberposts' => - 1
		) );

		$images_file_types = array();
		$mime_types = WP_Listings_Directory_Mixes::get_image_mime_types();
		foreach($mime_types as $key => $mine_type) {
			$images_file_types[$key] = $key;
		}

		$countries = array( '' => __('All Countries', 'wp-listings-directory') );
		$countries = array_merge( $countries, WP_Listings_Directory_Mixes::get_all_countries() );
		
		// currency
		$currencies = WP_Listings_Directory_Price::get_currencies();
		$currencies_opts = [];
		foreach ($currencies as $k => $wc_currency) {
			$currencies_opts[$k] = $k.' - '.$wc_currency.'('.WP_Listings_Directory_Price::currency_symbol($k).')';
		}
		$wp_listings_directory_settings = array();
		// General
		$wp_listings_directory_settings['general'] = array(
			'id'         => 'options_page',
			'wp_listings_directory_title' => __( 'General Settings', 'wp-listings-directory' ),
			'show_on'    => array(
				'key' => 'options-page',
				'value' => array( $this->key )
			),
			'fields'     => apply_filters( 'wp_listings_directory_settings_general', array(
					array(
						'name' => __( 'General Settings', 'wp-listings-directory' ),
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_general_settings_1',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Number listings per page', 'wp-listings-directory' ),
						'desc'    => __( 'Number of listings to display per page.', 'wp-listings-directory' ),
						'id'      => 'number_listings_per_page',
						'type'    => 'text',
						'default' => '10',
					),
					array(
						'name' => __( 'Currency Settings', 'wp-listings-directory' ),
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_general_settings_2',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Enable Multiple Currencies', 'wp-listings-directory' ),
						'id'      => 'enable_multi_currencies',
						'type'    => 'select',
						'options' => array(
							'yes' 	=> __( 'Yes', 'wp-listings-directory' ),
							'no'   => __( 'No', 'wp-listings-directory' ),
						),
						'default' => 'no',
					),
					array(
						'name'            => __( 'Exchangerate API Key', 'wp-listings-directory' ),
						'id'              => 'exchangerate_api_key',
						'type'            => 'text',
						'desc' => sprintf(__( 'Acquire an API key from the <a href="%s" target="_blank">Exchange Rate API</a>', 'wp-listings-directory' ), 'https://www.exchangerate-api.com/docs/php-currency-api'),
					),
					array(
						'name'    => __( 'Currency', 'wp-listings-directory' ),
						'desc'    => __( 'Choose a currency.', 'wp-listings-directory' ),
						'id'      => 'currency',
						'type'    => 'pw_select',
						'options' => $currencies_opts,
						'default' => 'USD',
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
					),
					array(
						'name'            => __( 'Custom symbol', 'wp-listings-directory' ),
						'id'              => 'custom_symbol',
						'type'            => 'text_small',
						'attributes'        => array(
		                    'placeholder' => __( 'eg: CAD $', 'wp-listings-directory' ),
		                ),
					),
					array(
						'name'    => __( 'Currency Position', 'wp-listings-directory' ),
						'desc'    => 'Choose the position of the currency sign.',
						'id'      => 'currency_position',
						'type'    => 'pw_select',
						'options' => array(
							'before' => __( 'Before - $10', 'wp-listings-directory' ),
							'after'  => __( 'After - 10$', 'wp-listings-directory' ),
							'before_space' => __( 'Before with space - $ 10', 'wp-listings-directory' ),
							'after_space'  => __( 'After with space - 10 $', 'wp-listings-directory' ),
						),
						'default' => 'before',
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
					),
					array(
						'name'    => __( 'Number of decimals', 'wp-listings-directory' ),
						'desc'    => __( 'This sets the number of decimal points shown in displayed prices.', 'wp-listings-directory' ),
						'id'      => 'money_decimals',
						'type'    => 'text_small',
						'attributes' 	    => array(
							'type' 				=> 'number',
							'min'				=> 0,
							'pattern' 			=> '\d*',
						)
					),
					array(
						'name'            => __( 'Decimal Separator', 'wp-listings-directory' ),
						'desc'            => __( 'The symbol (usually , or .) to separate decimal points', 'wp-listings-directory' ),
						'id'              => 'money_dec_point',
						'type'            => 'text_small',
						'default' 		=> '.',
					),
					array(
						'name'    => __( 'Thousands Separator', 'wp-listings-directory' ),
						'desc'    => __( 'If you need space, enter &nbsp;', 'wp-listings-directory' ),
						'id'      => 'money_thousands_separator',
						'type'    => 'text_small',
					),
					/////
					array(
						'name'              => __( 'More Currencies', 'wp-listings-directory' ),
						'id'                => 'multi_currencies',
						'type'              => 'group',
						'options'     		=> array(
							'group_title'       => __( 'Currency', 'wp-listings-directory' ),
							'add_button'        => __( 'Add Another Currency', 'wp-listings-directory' ),
							'remove_button'     => __( 'Remove Currency', 'wp-listings-directory' ),
							'sortable'          => true,
							'closed'         => true,
							'remove_confirm' => __( 'Do you want to remove this currency', 'wp-listings-directory' ),
						),
						'fields'			=> array(
							array(
								'name'    => __( 'Currency', 'wp-listings-directory' ),
								'desc'    => __( 'Choose a currency.', 'wp-listings-directory' ),
								'id'      => 'currency',
								'type'    => 'pw_select',
								'options' => $currencies_opts,
								'attributes'        => array(
				                    'data-allowclear' => 'false',
				                    'data-width'		=> '25em'
				                ),
				                'classes' => 'multi-currency-select'
							),
							array(
								'name'    => __( 'Currency Position', 'wp-listings-directory' ),
								'desc'    => 'Choose the position of the currency sign.',
								'id'      => 'currency_position',
								'type'    => 'pw_select',
								'options' => array(
									'before' => __( 'Before - $10', 'wp-listings-directory' ),
									'after'  => __( 'After - 10$', 'wp-listings-directory' ),
									'before_space' => __( 'Before with space - $ 10', 'wp-listings-directory' ),
									'after_space'  => __( 'After with space - 10 $', 'wp-listings-directory' ),
								),
								'default' => 'before',
								'attributes'        => array(
				                    'data-allowclear' => 'false',
				                    'data-width'		=> '25em'
				                ),
							),
							array(
								'name'    => __( 'Number of decimals', 'wp-listings-directory' ),
								'desc'    => __( 'This sets the number of decimal points shown in displayed prices.', 'wp-listings-directory' ),
								'id'      => 'money_decimals',
								'type'    => 'text_small',
								'attributes' 	    => array(
									'type' 				=> 'number',
									'min'				=> 0,
									'pattern' 			=> '\d*',
								)
							),
							array(
								'name'            => __( 'Rate + Exchange Fee', 'wp-listings-directory' ),
								'id'              => 'rate_exchange_fee',
								'type'            => 'wp_listings_directory_rate_exchange',
								'default' => 1
							),
							array(
								'name'            => __( 'Custom symbol', 'wp-listings-directory' ),
								'id'              => 'custom_symbol',
								'type'            => 'text_small',
								'attributes'        => array(
				                    'placeholder' => __( 'eg: CAD $', 'wp-listings-directory' ),
				                ),
							),
						),
					),
					///////
					array(
						'name' => __( 'Shorten Long Number', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_general_settings_shorten_long_number',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Enable Shorten Long Number', 'wp-listings-directory' ),
						'id'      => 'enable_shorten_long_number',
						'type'    => 'select',
						'options' => array(
							'yes' 	=> __( 'Yes', 'wp-listings-directory' ),
							'no'   => __( 'No', 'wp-listings-directory' ),
						),
						'default' => 'no',
					),
					array(
						'name'    => __( 'Number precision', 'wp-listings-directory' ),
						'desc'    => __( 'This sets the number of precision shown in displayed number.', 'wp-listings-directory' ),
						'id'      => 'shorten_precision',
						'type'    => 'text_small',
						'attributes' 	    => array(
							'type' 				=> 'number',
							'min'				=> 0,
							'pattern' 			=> '\d*',
						),
						'default' => 3
					),
					array(
						'name'            => __( 'Shorten Thousand', 'wp-listings-directory' ),
						'id'              => 'shorten_thousand',
						'type'            => 'wp_listings_directory_enable_input',
						'desc' => __( 'Enter space for translate to all languages "K"', 'wp-listings-directory' ),
						'attributes'        => array(
		                    'placeholder' => __( 'eg: K', 'wp-listings-directory' ),
		                ),
					),
					array(
						'name'            => __( 'Shorten Million', 'wp-listings-directory' ),
						'id'              => 'shorten_million',
						'type'            => 'wp_listings_directory_enable_input',
						'desc' => __( 'Enter space for translate to all languages "M"', 'wp-listings-directory' ),
						'attributes'        => array(
		                    'placeholder' => __( 'eg: M', 'wp-listings-directory' ),
		                ),
					),
					array(
						'name'            => __( 'Shorten Billion', 'wp-listings-directory' ),
						'id'              => 'shorten_billion',
						'type'            => 'wp_listings_directory_enable_input',
						'desc' => __( 'Enter space for translate to all languages "B"', 'wp-listings-directory' ),
						'attributes'        => array(
		                    'placeholder' => __( 'eg: B', 'wp-listings-directory' ),
		                ),
					),
					array(
						'name'            => __( 'Shorten Trillion', 'wp-listings-directory' ),
						'id'              => 'shorten_trillion',
						'type'            => 'wp_listings_directory_enable_input',
						'desc' => __( 'Enter space for translate to all languages "T"', 'wp-listings-directory' ),
						'attributes'        => array(
		                    'placeholder' => __( 'eg: T', 'wp-listings-directory' ),
		                ),
					),
					array(
						'name'            => __( 'Shorten Quadrillion', 'wp-listings-directory' ),
						'id'              => 'shorten_quadrillion',
						'type'            => 'wp_listings_directory_enable_input',
						'desc' => __( 'Enter space for translate to all languages "Qa"', 'wp-listings-directory' ),
						'attributes'        => array(
		                    'placeholder' => __( 'eg: Qa', 'wp-listings-directory' ),
		                ),
					),
					array(
						'name'            => __( 'Shorten Quintillion', 'wp-listings-directory' ),
						'id'              => 'shorten_quintillion',
						'type'            => 'wp_listings_directory_enable_input',
						'desc' => __( 'Enter space for translate to all languages "Qi"', 'wp-listings-directory' ),
						'attributes'        => array(
		                    'placeholder' => __( 'eg: Qi', 'wp-listings-directory' ),
		                ),
					),

					////
					array(
						'name' => __( 'Measurement', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_general_settings_measurement',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Area Unit', 'wp-listings-directory' ),
						'id'      => 'measurement_unit_area',
						'type'    => 'text_small',
						'default' => 'sqft',
					),
					array(
						'name'    => __( 'Distance Unit', 'wp-listings-directory' ),
						'id'      => 'measurement_distance_unit',
						'type'    => 'text_small',
						'default' => 'ft',
					),
					array(
						'name'    => __( 'Search Distance unit', 'wp-listings-directory' ),
						'id'      => 'search_distance_unit',
						'type'    => 'pw_select',
						'options' => array(
							'km' => __('Kilometers', 'wp-listings-directory'),
							'miles' => __('Miles', 'wp-listings-directory'),
						),
						'default' => 'miles',
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
					),

					array(
						'name' => __( 'File Types', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_general_settings_3',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Images File Types', 'wp-listings-directory' ),
						'id'      => 'image_file_types',
						'type'    => 'multicheck_inline',
						'options' => $images_file_types,
						'default' => array('jpg', 'jpeg', 'jpe', 'png')
					),
					array(
						'name' => __( 'Map API Settings', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_general_settings_4',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Map Service', 'wp-listings-directory' ),
						'id'      => 'map_service',
						'type'    => 'pw_select',
						'options' => array(
							'mapbox' => __('Mapbox', 'wp-listings-directory'),
							'google-map' => __('Google Maps', 'wp-listings-directory'),
							'here' => __('Here Maps', 'wp-listings-directory'),
							'openstreetmap' => __('OpenStreetMap', 'wp-listings-directory'),
						),
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
					),
					array(
						'name'    => __( 'Google Map API', 'wp-listings-directory' ),
						'desc'    => __( 'Google requires an API key to retrieve location information for listing listings. Acquire an API key from the <a href="https://developers.google.com/maps/documentation/geocoding/get-api-key">Google Maps API developer site.</a>', 'wp-listings-directory' ),
						'id'      => 'google_map_api_keys',
						'type'    => 'text',
						'default' => '',
					),
					array(
						'name'    => __( 'Google Map Type', 'wp-listings-directory' ),
						'id'      => 'googlemap_type',
						'type'    => 'pw_select',
						'options' => array(
							'roadmap' => __('ROADMAP', 'wp-listings-directory'),
							'satellite' => __('SATELLITE', 'wp-listings-directory'),
							'hybrid' => __('HYBRID', 'wp-listings-directory'),
							'terrain' => __('TERRAIN', 'wp-listings-directory'),
						),
						'default' => 'roadmap',
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
					),
					array(
						'name'    => __( 'Google Maps Style', 'wp-listings-directory' ),
						'desc' 	  => wp_kses(__('<a href="//snazzymaps.com/">Get custom style</a> and paste it below. If there is nothing added, we will fallback to the Google Maps service.', 'wp-listings-directory'), array('a' => array('href' => array()))),
						'id'      => 'google_map_style',
						'type'    => 'textarea',
						'default' => '',
					),
					array(
						'name'    => __( 'Mapbox Token', 'wp-listings-directory' ),
						'desc' => wp_kses(__('<a href="//www.mapbox.com/help/create-api-access-token/">Get a FREE token</a> and paste it below. If there is nothing added, we will fallback to the Google Maps service.', 'wp-listings-directory'), array('a' => array('href' => array()))),
						'id'      => 'mapbox_token',
						'type'    => 'text',
						'default' => '',
					),
					array(
						'name'    => __( 'Mapbox Style', 'wp-listings-directory' ),
						'id'      => 'mapbox_style',
						'type'    => 'wp_listings_directory_image_select',
						'default' => 'streets-v11',
						'options' => array(
		                    'streets-v11' => array(
		                        'alt' => esc_html__('streets', 'wp-listings-directory'),
		                        'img' => WP_LISTINGS_DIRECTORY_PLUGIN_URL . '/assets/images/streets.png'
		                    ),
		                    'light-v10' => array(
		                        'alt' => esc_html__('light', 'wp-listings-directory'),
		                        'img' => WP_LISTINGS_DIRECTORY_PLUGIN_URL . '/assets/images/light.png'
		                    ),
		                    'dark-v10' => array(
		                        'alt' => esc_html__('dark', 'wp-listings-directory'),
		                        'img' => WP_LISTINGS_DIRECTORY_PLUGIN_URL . '/assets/images/dark.png'
		                    ),
		                    'outdoors-v11' => array(
		                        'alt' => esc_html__('outdoors', 'wp-listings-directory'),
		                        'img' => WP_LISTINGS_DIRECTORY_PLUGIN_URL . '/assets/images/outdoors.png'
		                    ),
		                    'satellite-v9' => array(
		                        'alt' => esc_html__('satellite', 'wp-listings-directory'),
		                        'img' => WP_LISTINGS_DIRECTORY_PLUGIN_URL . '/assets/images/satellite.png'
		                    ),
		                ),
					),
					array(
						'name'    => __( 'Here Maps API Key', 'wp-listings-directory' ),
						'desc' => wp_kses(__('<a href="https://developer.here.com/tutorials/getting-here-credentials/">Get a API key</a> and paste it below. If there is nothing added, we will fallback to the Google Maps service.', 'wp-listings-directory'), array('a' => array('href' => array()))),
						'id'      => 'here_map_api_key',
						'type'    => 'text',
						'default' => '',
					),
					array(
						'name'    => __( 'Here Maps Style', 'wp-listings-directory' ),
						'id'      => 'here_map_style',
						'type'    => 'select',
						'options' => array(
							'normal.day' => esc_html__('Normal Day', 'wp-listings-directory'),
							'normal.day.grey' => esc_html__('Normal Day Grey', 'wp-listings-directory'),
							'normal.day.transit' => esc_html__('Normal Day Transit', 'wp-listings-directory'),
							'normal.night' => esc_html__('Normal Night', 'wp-listings-directory'),
							'reduced.day' => esc_html__('Reduced Day', 'wp-listings-directory'),
							'reduced.night' => esc_html__('Reduced Night', 'wp-listings-directory'),
							'pedestrian.day' => esc_html__('Pedestrian Day', 'wp-listings-directory'),
						)
					),
					array(
						'name'    => __( 'Geocoder Country', 'wp-listings-directory' ),
						'id'      => 'geocoder_country',
						'type'    => 'pw_select',
						'options' => $countries,
						'attributes'        => array(
		                    'data-allowclear' => 'true',
		                    'data-width'		=> '25em',
		                    'data-placeholder'	=> __( 'All Countries', 'wp-listings-directory' )
		                ),
					),
					array(
						'name' => __( 'Default maps location', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_general_settings_default_maps_location',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Latitude', 'wp-listings-directory' ),
						'desc'    => __( 'Enter your latitude', 'wp-listings-directory' ),
						'id'      => 'default_maps_location_latitude',
						'type'    => 'text_small',
						'default' => '43.6568',
					),
					array(
						'name'    => __( 'Longitude', 'wp-listings-directory' ),
						'desc'    => __( 'Enter your longitude', 'wp-listings-directory' ),
						'id'      => 'default_maps_location_longitude',
						'type'    => 'text_small',
						'default' => '-79.4512',
					),
					// location
					array(
						'name' => __( 'Location Settings', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_general_settings_location',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Location Multiple Fields', 'wp-listings-directory' ),
						'id'      => 'location_multiple_fields',
						'type'    => 'select',
						'options' => array(
							'yes' 	=> __( 'Yes', 'wp-listings-directory' ),
							'no'   => __( 'No', 'wp-listings-directory' ),
						),
						'default' => 'yes',
						'desc'    => __( 'You can set 4 fields for regions like: Country, State, City, District', 'wp-listings-directory' ),
					),
					array(
						'name'    => __( 'Number Fields', 'wp-listings-directory' ),
						'id'      => 'location_nb_fields',
						'type'    => 'select',
						'options' => array(
							'1' => __('1 Field', 'wp-listings-directory'),
							'2' => __('2 Fields', 'wp-listings-directory'),
							'3' => __('3 Fields', 'wp-listings-directory'),
							'4' => __('4 Fields', 'wp-listings-directory'),
						),
						'default' => '1',
						'desc'    => __( 'You can set 4 fields for regions like: Country, State, City, District', 'wp-listings-directory' ),
					),
					array(
						'name'    => __( 'First Field Label', 'wp-listings-directory' ),
						'desc'    => __( 'First location field label', 'wp-listings-directory' ),
						'id'      => 'location_1_field_label',
						'type'    => 'text',
						'default' => 'Country',
					),
					array(
						'name'    => __( 'Second Field Label', 'wp-listings-directory' ),
						'desc'    => __( 'Second location field label', 'wp-listings-directory' ),
						'id'      => 'location_2_field_label',
						'type'    => 'text',
						'default' => 'State',
					),
					array(
						'name'    => __( 'Third Field Label', 'wp-listings-directory' ),
						'desc'    => __( 'Third location field label', 'wp-listings-directory' ),
						'id'      => 'location_3_field_label',
						'type'    => 'text',
						'default' => 'City',
					),
					array(
						'name'    => __( 'Fourth Field Label', 'wp-listings-directory' ),
						'desc'    => __( 'Fourth location field label', 'wp-listings-directory' ),
						'id'      => 'location_4_field_label',
						'type'    => 'text',
						'default' => 'District',
					),

				), $pages
			)		 
		);

		// Listing Submission
		$wp_listings_directory_settings['listing_submission'] = array(
			'id'         => 'options_page',
			'wp_listings_directory_title' => __( 'Listing Submission', 'wp-listings-directory' ),
			'show_on'    => array(
				'key' => 'options-page',
				'value' => array( $this->key )
			),
			'fields'     => apply_filters( 'wp_listings_directory_settings_listing_submission', array(
					array(
						'name' => __( 'Listing Submission', 'wp-listings-directory' ),
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_listing_submission_settings_1',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Submit Listing Form Page', 'wp-listings-directory' ),
						'desc'    => __( 'This is page to display form for submit listing. The <code>[wp_listings_directory_submission]</code> shortcode should be on this page.', 'wp-listings-directory' ),
						'id'      => 'submit_listing_form_page_id',
						'type'    => 'pw_select',
						'options' => $pages,
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
		                'page-type' => 'page'
					),
					array(
						'name'    => __( 'Moderate New Listings', 'wp-listings-directory' ),
						'desc'    => __( 'Require admin approval of all new listing submissions', 'wp-listings-directory' ),
						'id'      => 'submission_requires_approval',
						'type'    => 'pw_select',
						'options' => array(
							'on' 	=> __( 'Enable', 'wp-listings-directory' ),
							'off'   => __( 'Disable', 'wp-listings-directory' ),
						),
						'default' => 'on',
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
					),
					array(
						'name'    => __( 'Allow Published Edits', 'wp-listings-directory' ),
						'desc'    => __( 'Choose whether published listing listings can be edited and if edits require admin approval. When moderation is required, the original listing listings will be unpublished while edits await admin approval.', 'wp-listings-directory' ),
						'id'      => 'user_edit_published_submission',
						'type'    => 'pw_select',
						'options' => array(
							'no' 	=> __( 'Users cannot edit', 'wp-listings-directory' ),
							'yes'   => __( 'Users can edit without admin approval', 'wp-listings-directory' ),
							'yes_moderated'   => __( 'Users can edit, but edits require admin approval', 'wp-listings-directory' ),
						),
						'default' => 'yes',
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
					),
					array(
						'name'            => __( 'Listing Duration', 'wp-listings-directory' ),
						'desc'            => __( 'Listings will display for the set number of days, then expire. Leave this field blank if you don\'t want listings to have an expiration date.', 'wp-listings-directory' ),
						'id'              => 'submission_duration',
						'type'            => 'text_small',
						'default'         => 30,
					),
				), $pages
			)
		);

		// Listing Submission
		$wp_listings_directory_settings['pages'] = array(
			'id'         => 'options_page',
			'wp_listings_directory_title' => __( 'Pages', 'wp-listings-directory' ),
			'show_on'    => array(
				'key' => 'options-page',
				'value' => array( $this->key )
			),
			'fields'     => apply_filters( 'wp_listings_directory_settings_pages', array(
					array(
						'name'    => __( 'Listings Page', 'wp-listings-directory' ),
						'desc'    => __( 'This lets the plugin know the location of the listings listing page. The <code>[wp_listings_directory_listings]</code> shortcode should be on this page.', 'wp-listings-directory' ),
						'id'      => 'listings_page_id',
						'type'    => 'pw_select',
						'options' => $pages,
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
		                'page-type' => 'page'
					),
					
					array(
						'name'    => __( 'Login Page', 'wp-listings-directory' ),
						'desc'    => __( 'This lets the plugin know the location of the listing listings page. The <code>[wp_listings_directory_login]</code> <code>[wp_listings_directory_register]</code> shortcode should be on this page.', 'wp-listings-directory' ),
						'id'      => 'login_page_id',
						'type'    => 'pw_select',
						'options' => $pages,
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
		                'page-type' => 'page'
					),
					array(
						'name'    => __( 'Register Page', 'wp-listings-directory' ),
						'desc'    => __( 'This lets the plugin know the location of the listing listings page. The <code>[wp_listings_directory_login]</code> <code>[wp_listings_directory_register]</code> shortcode should be on this page.', 'wp-listings-directory' ),
						'id'      => 'register_page_id',
						'type'    => 'pw_select',
						'options' => $pages,
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
		                'page-type' => 'page'
					),
					array(
						'name'    => __( 'After Login Page', 'wp-listings-directory' ),
						'desc'    => __( 'This lets the plugin know the page after User login/register.', 'wp-listings-directory' ),
						'id'      => 'after_login_page_id_user',
						'type'    => 'pw_select',
						'options' => $pages,
						'page-type' => 'page',
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
					),
					array(
						'name'    => __( 'Approve User Page', 'wp-listings-directory' ),
						'desc'    => __( 'This lets the plugin know the location of the job listings page. The <code>[wp_listings_directory_approve_user]</code> shortcode should be on this page.', 'wp-listings-directory' ),
						'id'      => 'approve_user_page_id',
						'type'    => 'pw_select',
						'options' => $pages,
						'page-type' => 'page',
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
					),
					array(
						'name'    => __( 'User Dashboard Page', 'wp-listings-directory' ),
						'desc'    => __( 'This lets the plugin know the location of the user dashboard. The <code>[wp_listings_directory_user_dashboard]</code> shortcode should be on this page.', 'wp-listings-directory' ),
						'id'      => 'user_dashboard_page_id',
						'type'    => 'pw_select',
						'options' => $pages,
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
		                'page-type' => 'page'
					),
					array(
						'name'    => __( 'Edit Profile Page', 'wp-listings-directory' ),
						'desc'    => __( 'This lets the plugin know the location of the user edit profile. The <code>[wp_listings_directory_change_profile]</code> shortcode should be on this page.', 'wp-listings-directory' ),
						'id'      => 'edit_profile_page_id',
						'type'    => 'pw_select',
						'options' => $pages,
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
		                'page-type' => 'page'
					),
					array(
						'name'    => __( 'Change Password Page', 'wp-listings-directory' ),
						'desc'    => __( 'This lets the plugin know the location of the user change password. The <code>[wp_listings_directory_change_password]</code> shortcode should be on this page.', 'wp-listings-directory' ),
						'id'      => 'change_password_page_id',
						'type'    => 'pw_select',
						'options' => $pages,
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
		                'page-type' => 'page'
					),
					array(
						'name'    => __( 'My Listings Page', 'wp-listings-directory' ),
						'desc'    => __( 'This lets the plugin know the location of the my listing page. The <code>[wp_listings_directory_my_listings]</code> shortcode should be on this page.', 'wp-listings-directory' ),
						'id'      => 'my_listings_page_id',
						'type'    => 'pw_select',
						'options' => $pages,
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
		                'page-type' => 'page'
					),
					array(
						'name'    => __( 'Favorite Listings Page', 'wp-listings-directory' ),
						'desc'    => __( 'This lets the plugin know the location of the my favorite listing page. The <code>[wp_listings_directory_my_listing_favorite]</code> shortcode should be on this page.', 'wp-listings-directory' ),
						'id'      => 'favorite_listings_page_id',
						'type'    => 'pw_select',
						'options' => $pages,
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
		                'page-type' => 'page'
					),
					
					array(
						'name'    => __( 'Terms and Conditions Page', 'wp-listings-directory' ),
						'desc'    => __( 'This lets the plugin know the Terms and Conditions page.', 'wp-listings-directory' ),
						'id'      => 'terms_conditions_page_id',
						'type'    => 'pw_select',
						'options' => $pages,
						'attributes'        => array(
		                    'data-allowclear' => 'false',
		                    'data-width'		=> '25em'
		                ),
		                'page-type' => 'page'
					),
				), $pages
			)
		);
		$cc_list = include WP_LISTINGS_DIRECTORY_PLUGIN_DIR.'includes/sms/countries-phone.php';
		$phone_css = [];
		foreach ($cc_list as $country_code => $country_phone_code) {
			$phone_css[$country_phone_code] = $country_code . $country_phone_code;
		}
		// user register settings
		$wp_listings_directory_settings['user_register_settings'] = array(
			'id'         => 'options_page',
			'wp_listings_directory_title' => __( 'User Register Settings', 'wp-listings-directory' ),
			'show_on'    => array(
				'key' => 'options-page',
				'value' => array( $this->key )
			),
			'fields'     => apply_filters( 'wp_listings_directory_settings_user_register_fields_settings', array(
				array(
					'name' => __( 'User Settings', 'wp-listings-directory' ),
					'type' => 'wp_listings_directory_title',
					'id'   => 'wp_listings_directory_title_general_settings_user',
					'before_row' => '<hr>',
					'after_row'  => '<hr>'
				),
				array(
					'name'    => __( 'Moderate New User', 'wp-listings-directory' ),
					'desc'    => __( 'Require admin approval of all new users', 'wp-listings-directory' ),
					'id'      => 'users_requires_approval',
					'type'    => 'pw_select',
					'options' => array(
						'auto' 	=> __( 'Auto Approve', 'wp-listings-directory' ),
						'email_approve' => __( 'Email Approve', 'wp-listings-directory' ),
						'phone_approve' => __( 'Phone Approve', 'wp-listings-directory' ),
						'admin_approve' => __( 'Administrator Approve', 'wp-listings-directory' ),
					),
					'default' => 'auto',
					'attributes'        => array(
	                    'data-allowclear' => 'false',
	                    'data-width'		=> '25em'
	                ),
				),
				array(
					'name' => __( 'Phone Approve Settings', 'wp-listings-directory' ),
					'type' => 'wp_listings_directory_title',
					'id'   => 'wp_listings_directory_title_general_settings_phone_approve',
					'before_row' => '<hr>',
					'after_row'  => '<hr>'
				),
				array(
					'name'    => __( 'Phone Operator', 'wp-listings-directory' ),
					'id'      => 'phone_approve_operator',
					'type'    => 'pw_select',
					'options' => array(
						'twilio' 	=> __( 'twilio', 'wp-listings-directory' ),
						'aws' => __( 'Amazon', 'wp-listings-directory' ),
					),
					'default' => 'twilio',
					'attributes'        => array(
	                    'data-allowclear' => 'false',
	                    'data-width'		=> '25em'
	                ),
				),
				array(
					'name' => __( 'Amazon SNS Settings', 'wp-listings-directory' ),
					'type' => 'wp_listings_directory_title',
					'id'   => 'wp_listings_directory_title_general_settings_amazon_settings',
					'before_row' => '<hr>',
					'after_row'  => '<hr>'
				),
				array(
					'name'    => __( 'Access key', 'wp-listings-directory' ),
					'id'      => 'phone_approve_aws_access_key',
					'type'    => 'text',
				),
				array(
					'name'    => __( 'Secret access key', 'wp-listings-directory' ),
					'id'      => 'phone_approve_aws_secret_access_key',
					'type'    => 'text',
				),
				array(
					'name' => __( 'Twilio Settings', 'wp-listings-directory' ),
					'type' => 'wp_listings_directory_title',
					'id'   => 'wp_listings_directory_title_general_settings_twilio_settings',
					'before_row' => '<hr>',
					'after_row'  => '<hr>'
				),
				array(
					'name'    => __( 'Account SID', 'wp-listings-directory' ),
					'id'      => 'phone_approve_twilio_account_sid',
					'type'    => 'text',
				),
				array(
					'name'    => __( 'Auth Token', 'wp-listings-directory' ),
					'id'      => 'phone_approve_twilio_auth_token',
					'type'    => 'text',
				),
				array(
					'name'    => __( 'Sender\'s Number', 'wp-listings-directory' ),
					'id'      => 'phone_approve_twilio_sender_number',
					'type'    => 'text',
				),
				array(
					'name' => __( 'Register Settings', 'wp-listings-directory' ),
					'type' => 'wp_listings_directory_title',
					'id'   => 'wp_listings_directory_title_general_settings_phone_register_settings',
					'before_row' => '<hr>',
					'after_row'  => '<hr>'
				),
				
				array(
					'name'    => __( 'Default Country Code', 'wp-listings-directory' ),
					'desc'    => __( 'Geolocation = User location.', 'wp-listings-directory' ),
					'id'      => 'phone_approve_default_country_code',
					'type'    => 'pw_select',
					'options' => array(
						'geolocation' 	=> __( 'Geolocation', 'wp-listings-directory' ),
						'custom'   => __( 'Custom', 'wp-listings-directory' ),
					),
					'default' => 'geolocation',
					'attributes'        => array(
	                    'data-allowclear' => 'false',
	                    'data-width'		=> '25em'
	                ),
				),
				array(
					'name'    => __( 'Default Country Code (Custom)', 'wp-listings-directory' ),
					'id'      => 'phone_approve_default_country_code_custom',
					'type'    => 'pw_select',
					'options' => $phone_css,
					'default' => '',
					'attributes'        => array(
	                    'data-allowclear' => 'false',
	                    'data-width'		=> '25em'
	                ),
				),
				array(
					'name'    => __( 'SMS Text', 'wp-listings-directory' ),
					'desc'    => __( 'Shortcodes: [otp]', 'wp-listings-directory' ),
					'id'      => 'phone_approve_sms_text',
					'type'    => 'textarea',
					'default' => '[otp] is your One Time Verification(OTP) to confirm your phone no at xootix.',
				),
				array(
					'name' => __( 'OTP Settings', 'wp-listings-directory' ),
					'type' => 'wp_listings_directory_title',
					'id'   => 'wp_listings_directory_title_general_settings_otp_settings',
					'before_row' => '<hr>',
					'after_row'  => '<hr>'
				),
				array(
					'name'    => __( 'OTP Digits', 'wp-listings-directory' ),
					'id'      => 'phone_approve_otp_digits',
					'type'    => 'text',
					'default' => '4',
					'attributes'        => array(
	                    'autocomplete'		=> 'off',
	                    'type' 				=> 'number',
						'min'				=> 0,
						'pattern' 			=> '\d*',
	                ),
				),
				array(
					'name'    => __( 'Incorrect OTP Limit', 'wp-listings-directory' ),
					'id'      => 'phone_approve_incorrect_otp_limit',
					'type'    => 'text',
					'default' => '10',
					'attributes'        => array(
	                    'autocomplete'		=> 'off',
	                    'type' 				=> 'number',
						'min'				=> 0,
						'pattern' 			=> '\d*',
	                ),
				),
				array(
					'name'    => __( 'OTP Expiry', 'wp-listings-directory' ),
					'desc'    => __( 'In Seconds', 'wp-listings-directory' ),
					'id'      => 'phone_approve_otp_expiry',
					'type'    => 'text',
					'default' => '120',
					'attributes'        => array(
	                    'autocomplete'		=> 'off',
	                    'type' 				=> 'number',
						'min'				=> 0,
						'pattern' 			=> '\d*',
	                ),
				),
				array(
					'name'    => __( 'Resend OTP Limit', 'wp-listings-directory' ),
					'id'      => 'phone_approve_resend_otp_limit',
					'type'    => 'text',
					'default' => '8',
					'attributes'        => array(
	                    'autocomplete'		=> 'off',
	                    'type' 				=> 'number',
						'min'				=> 0,
						'pattern' 			=> '\d*',
	                ),
				),
				array(
					'name'    => __( 'Ban Time', 'wp-listings-directory' ),
					'desc'    => __( 'Time in seconds', 'wp-listings-directory' ),
					'id'      => 'phone_approve_ban_time',
					'type'    => 'text',
					'default' => '600',
					'attributes'        => array(
	                    'autocomplete'		=> 'off',
	                    'type' 				=> 'number',
						'min'				=> 0,
						'pattern' 			=> '\d*',
	                ),
				),
				array(
					'name'    => __( 'Resend OTP Wait Time', 'wp-listings-directory' ),
					'desc'    => __( 'Waiting time to resend a new OTP (In seconds)', 'wp-listings-directory' ),
					'id'      => 'phone_approve_resend_otp_wait_time',
					'type'    => 'text',
					'default' => '30',
					'attributes'        => array(
	                    'autocomplete'		=> 'off',
	                    'type' 				=> 'number',
						'min'				=> 0,
						'pattern' 			=> '\d*',
	                ),
				),
			), $pages )
		);
		

		// Review Settings
		$wp_listings_directory_settings['review_settings'] = array(
			'id'         => 'options_page',
			'wp_listings_directory_title' => __( 'Review Settings', 'wp-listings-directory' ),
			'show_on'    => array(
				'key' => 'options-page',
				'value' => array( $this->key )
			),
			'fields'     => apply_filters( 'wp_listings_directory_settings_review_settings', array(
				// review listing
				array(
					'name' => __( 'Listing review settings', 'wp-listings-directory' ),
					'type' => 'wp_listings_directory_title',
					'id'   => 'wp_listings_directory_title_listing_review_settings_title',
					'before_row' => '<hr>',
					'after_row'  => '<hr>',
				),
				array(
					'name'    => __( 'Enable Listing Review', 'wp-listings-directory' ),
					'id'      => 'enable_listing_review',
					'type'    => 'pw_select',
					'options' => array(
						'on' 	=> __( 'Enable', 'wp-listings-directory' ),
						'off'   => __( 'Disable', 'wp-listings-directory' ),
					),
					'default' => 'on',
					'attributes'        => array(
	                    'data-allowclear' => 'false',
	                    'data-width'		=> '25em'
	                ),
				),
				array(
					'name'    => __( 'Enable Upload Image', 'wp-listings-directory' ),
					'id'      => 'enable_listing_review_upload_image',
					'type'    => 'pw_select',
					'options' => array(
						'on' 	=> __( 'Enable', 'wp-listings-directory' ),
						'off'   => __( 'Disable', 'wp-listings-directory' ),
					),
					'default' => 'on',
					'attributes'        => array(
	                    'data-allowclear' => 'false',
	                    'data-width'		=> '25em'
	                ),
				),
				array(
					'id'          => 'listing_review_category',
					'type'        => 'group',
					'name' => __( 'Listing Review Category', 'wp-listings-directory' ),
					'repeatable'  => true,
					'options'     => array(
						'group_title'       => __( 'Category {#}', 'wp-listings-directory' ), // since version 1.1.4, {#} gets replaced by row number
						'add_button'        => __( 'Add Another Category', 'wp-listings-directory' ),
						'remove_button'     => __( 'Remove Category', 'wp-listings-directory' ),
						'sortable'          => true,
					),
					'fields'	=> array(
						array(
							'name'            => __( 'Category Key', 'wp-listings-directory' ),
							'desc'            => __( 'Enter category key.', 'wp-listings-directory' ),
							'id'              => 'key',
							'type'            => 'text',
							'attributes' 	    => array(
								'data-general-review-key' => 'listing'
							),
						),
						array(
							'name'            => __( 'Category Name', 'wp-listings-directory' ),
							'desc'            => __( 'Enter category name.', 'wp-listings-directory' ),
							'id'              => 'name',
							'type'            => 'text',
						),
					)
				),
				
			) )		 
		);

		// ReCaptcha
		$wp_listings_directory_settings['api_settings'] = array(
			'id'         => 'options_page',
			'wp_listings_directory_title' => __( 'Social API', 'wp-listings-directory' ),
			'show_on'    => array(
				'key' => 'options-page',
				'value' => array( $this->key )
			),
			'fields'     => apply_filters( 'wp_listings_directory_settings_api_settings', array(
					// Facebook
					array(
						'name' => __( 'Facebook API settings', 'wp-listings-directory' ),
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_api_settings_facebook_title',
						'before_row' => '<hr>',
						'after_row'  => '<hr>',
						'desc' => sprintf(__('Callback URL is: %s', 'wp-listings-directory'), admin_url('admin-ajax.php?action=wp_listings_directory_facebook_login')),
					),
					array(
						'name'            => __( 'App ID', 'wp-listings-directory' ),
						'desc'            => __( 'Please enter App ID of your Facebook account.', 'wp-listings-directory' ),
						'id'              => 'facebook_api_app_id',
						'type'            => 'text',
					),
					array(
						'name'            => __( 'App Secret', 'wp-listings-directory' ),
						'desc'            => __( 'Please enter App Secret of your Facebook account.', 'wp-listings-directory' ),
						'id'              => 'facebook_api_app_secret',
						'type'            => 'text',
					),
					array(
						'name'    => __( 'Enable Facebook Login', 'wp-listings-directory' ),
						'id'      => 'enable_facebook_login',
						'type'    => 'checkbox',
					),

					// Linkedin
					array(
						'name' => __( 'Linkedin API settings', 'wp-listings-directory' ),
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_api_settings_linkedin_title',
						'before_row' => '<hr>',
						'after_row'  => '<hr>',
						'desc' => sprintf(__('Callback URL is: %s', 'wp-listings-directory'), home_url('/')),
					),
					array(
						'name'    => __( 'Linkedin Login Type', 'wp-listings-directory' ),
						'id'      => 'linkedin_login_type',
						'type'    => 'radio',
						'options' => array(
							'' => 'OAuth',
							'openid' => 'OpenID',
						),
						'default' => '',
					),
					array(
						'name'            => __( 'Client ID', 'wp-listings-directory' ),
						'desc'            => __( 'Please enter Client ID of your linkedin app.', 'wp-listings-directory' ),
						'id'              => 'linkedin_api_client_id',
						'type'            => 'text',
					),
					array(
						'name'            => __( 'Client Secret', 'wp-listings-directory' ),
						'desc'            => __( 'Please enter Client Secret of your linkedin app.', 'wp-listings-directory' ),
						'id'              => 'linkedin_api_client_secret',
						'type'            => 'text',
					),
					array(
						'name'    => __( 'Enable Linkedin Login', 'wp-listings-directory' ),
						'id'      => 'enable_linkedin_login',
						'type'    => 'checkbox',
					),

					// Twitter
					array(
						'name' => __( 'Twitter API settings', 'wp-listings-directory' ),
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_api_settings_twitter_title',
						'before_row' => '<hr>',
						'after_row'  => '<hr>',
						'desc' => sprintf(__('Callback URL is: %s', 'wp-listings-directory'), home_url('/')),
					),
					array(
						'name'            => __( 'Consumer Key', 'wp-listings-directory' ),
						'desc'            => __( 'Set Consumer Key for twitter.', 'wp-listings-directory' ),
						'id'              => 'twitter_api_consumer_key',
						'type'            => 'text',
					),
					array(
						'name'            => __( 'Consumer Secret', 'wp-listings-directory' ),
						'desc'            => __( 'Set Consumer Secret for twitter.', 'wp-listings-directory' ),
						'id'              => 'twitter_api_consumer_secret',
						'type'            => 'text',
					),
					array(
						'name'            => __( 'Access Token', 'wp-listings-directory' ),
						'desc'            => __( 'Set Access Token for twitter.', 'wp-listings-directory' ),
						'id'              => 'twitter_api_access_token',
						'type'            => 'text',
					),
					array(
						'name'            => __( 'Token Secret', 'wp-listings-directory' ),
						'desc'            => __( 'Set Token Secret for twitter.', 'wp-listings-directory' ),
						'id'              => 'twitter_api_token_secret',
						'type'            => 'text',
					),
					array(
						'name'    => __( 'Enable Twitter Login', 'wp-listings-directory' ),
						'id'      => 'enable_twitter_login',
						'type'    => 'checkbox',
					),

					// Google API
					array(
						'name' => __( 'Google API settings Settings', 'wp-listings-directory' ),
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_api_settings_google_title',
						'before_row' => '<hr>',
						'after_row'  => '<hr>',
						'desc' => sprintf(__('Callback URL is: %s', 'wp-listings-directory'), home_url('/')),
					),
					array(
						'name'            => __( 'API Key', 'wp-listings-directory' ),
						'desc'            => __( 'Please enter API key of your Google account.', 'wp-listings-directory' ),
						'id'              => 'google_api_key',
						'type'            => 'text',
					),
					array(
						'name'            => __( 'Client ID', 'wp-listings-directory' ),
						'desc'            => __( 'Please enter Client ID of your Google account.', 'wp-listings-directory' ),
						'id'              => 'google_api_client_id',
						'type'            => 'text',
					),
					array(
						'name'            => __( 'Client Secret', 'wp-listings-directory' ),
						'desc'            => __( 'Please enter Client secret of your Google account.', 'wp-listings-directory' ),
						'id'              => 'google_api_client_secret',
						'type'            => 'text',
					),
					array(
						'name'    => __( 'Enable Google Login', 'wp-listings-directory' ),
						'id'      => 'enable_google_login',
						'type'    => 'checkbox',
					),
				)
			)		 
		);
		
		// ReCaaptcha
		$wp_listings_directory_settings['recaptcha_api_settings'] = array(
			'id'         => 'options_page',
			'wp_listings_directory_title' => __( 'reCAPTCHA API', 'wp-listings-directory' ),
			'show_on'    => array( 'key' => 'options-page', 'value' => array( $this->key, ), ),
			'fields'     => apply_filters( 'wp_listings_directory_settings_recaptcha_api_settings', array(
					
					// Google Recaptcha
					array(
						'name' => __( 'Google reCAPTCHA API (V2) Settings', 'wp-listings-directory' ),
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_api_settings_google_recaptcha',
						'before_row' => '<hr>',
						'after_row'  => '<hr>',
						'desc' => __('The plugin use ReCaptcha v2', 'wp-listings-directory'),
					),
					array(
						'name'            => __( 'Site Key', 'wp-listings-directory' ),
						'desc'            => __( 'You can retrieve your site key from <a href="https://www.google.com/recaptcha/admin#list">Google\'s reCAPTCHA admin dashboard.</a>', 'wp-listings-directory' ),
						'id'              => 'recaptcha_site_key',
						'type'            => 'text',
					),
					array(
						'name'            => __( 'Secret Key', 'wp-listings-directory' ),
						'desc'            => __( 'You can retrieve your secret key from <a href="https://www.google.com/recaptcha/admin#list">Google\'s reCAPTCHA admin dashboard.</a>', 'wp-listings-directory' ),
						'id'              => 'recaptcha_secret_key',
						'type'            => 'text',
					),
				)
			)		 
		);

		// Email notification
		$wp_listings_directory_settings['email_notification'] = array(
			'id'         => 'options_page',
			'wp_listings_directory_title' => __( 'Email Notification', 'wp-listings-directory' ),
			'show_on'    => array(
				'key' => 'options-page',
				'value' => array( $this->key )
			),
			'fields'     => apply_filters( 'wp_listings_directory_settings_email_notification', array(
					
					array(
						'name' => __( 'Admin Notice of New Listing', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_admin_notice_add_new_listing',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Admin Notice of New Listing', 'wp-listings-directory' ),
						'id'      => 'admin_notice_add_new_listing',
						'type'    => 'checkbox',
						'desc' 	=> __( 'Send a notice to the site administrator when a new listing is submitted on the frontend.', 'wp-listings-directory' ),
					),
					array(
						'name'    => __( 'Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('admin_notice_add_new_listing', 'subject') ),
						'id'      => 'admin_notice_add_new_listing_subject',
						'type'    => 'text',
						'default' => 'New Listing Found',
					),
					array(
						'name'    => __( 'Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('admin_notice_add_new_listing', 'content') ),
						'id'      => 'admin_notice_add_new_listing_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('admin_notice_add_new_listing'),
					),

					array(
						'name' => __( 'Admin Notice of Updated Listing', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_admin_notice_updated_listing',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Admin Notice of Updated Listing', 'wp-listings-directory' ),
						'id'      => 'admin_notice_updated_listing',
						'type'    => 'checkbox',
						'desc' 	=> __( 'Send a notice to the site administrator when a listing is updated on the frontend.', 'wp-listings-directory' ),
					),
					array(
						'name'    => __( 'Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('admin_notice_updated_listing', 'subject') ),
						'id'      => 'admin_notice_updated_listing_subject',
						'type'    => 'text',
						'default' => 'A Listing Updated',
					),
					array(
						'name'    => __( 'Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('admin_notice_updated_listing', 'content') ),
						'id'      => 'admin_notice_updated_listing_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('admin_notice_updated_listing'),
					),

					
					array(
						'name' => __( 'Admin Notice of Expiring Listing', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_admin_notice_expiring_listing',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Admin Notice of Expiring Listing', 'wp-listings-directory' ),
						'id'      => 'admin_notice_expiring_listing',
						'type'    => 'checkbox',
						'desc' 	=> __( 'Send notices to the site administrator before a listing listing expires.', 'wp-listings-directory' ),
					),
					array(
						'name'    => __( 'Notice Period', 'wp-listings-directory' ),
						'desc'    => __( 'days', 'wp-listings-directory' ),
						'id'      => 'admin_notice_expiring_listing_days',
						'type'    => 'text_small',
						'default' => '1',
					),
					array(
						'name'    => __( 'Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('admin_notice_expiring_listing', 'subject') ),
						'id'      => 'admin_notice_expiring_listing_subject',
						'type'    => 'text',
						'default' => 'Listing Listing Expiring: {{listing_title}}',
					),
					array(
						'name'    => __( 'Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('admin_notice_expiring_listing', 'content') ),
						'id'      => 'admin_notice_expiring_listing_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('admin_notice_expiring_listing'),
					),

					
					array(
						'name' => __( 'User Notice of Expiring Listing', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_user_notice_expiring_listing',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'User Notice of Expiring Listing', 'wp-listings-directory' ),
						'id'      => 'user_notice_expiring_listing',
						'type'    => 'checkbox',
						'desc' 	=> __( 'Send notices to user before a listing listing expires.', 'wp-listings-directory' ),
					),
					array(
						'name'    => __( 'Notice Period', 'wp-listings-directory' ),
						'desc'    => __( 'days', 'wp-listings-directory' ),
						'id'      => 'user_notice_expiring_listing_days',
						'type'    => 'text_small',
						'default' => '1',
					),
					array(
						'name'    => __( 'Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_notice_expiring_listing', 'subject') ),
						'id'      => 'user_notice_expiring_listing_subject',
						'type'    => 'text',
						'default' => 'Listing Listing Expiring: {{listing_title}}',
					),
					array(
						'name'    => __( 'Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_notice_expiring_listing', 'content') ),
						'id'      => 'user_notice_expiring_listing_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('user_notice_expiring_listing'),
					),


					array(
						'name' => __( 'Listing Saved Search', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_saved_search',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Listing Saved Search Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('saved_search_notice', 'subject') ),
						'id'      => 'saved_search_notice_subject',
						'type'    => 'text',
						'default' => sprintf(__( 'Listing Saved Search: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('saved_search_notice', 'subject') ),
					),
					array(
						'name'    => __( 'Listing Saved Search Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('saved_search_notice', 'content') ),
						'id'      => 'saved_search_notice_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('saved_search_notice'),
					),

					// contact form Listing
					array(
						'name' => __( 'Listing Contact Form', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_contact_form_listing',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Listing Contact Form Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('listing_contact_form_notice', 'subject') ),
						'id'      => 'listing_contact_form_notice_subject',
						'type'    => 'text',
						'default' => __( 'Contact Form', 'wp-listings-directory' ),
					),
					array(
						'name'    => __( 'Listing Contact Form Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('listing_contact_form_notice', 'content') ),
						'id'      => 'listing_contact_form_notice_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('listing_contact_form_notice'),
					),

					// contact form
					array(
						'name' => __( 'Contact Form', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_contact_form',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Contact Form Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('contact_form_notice', 'subject') ),
						'id'      => 'contact_form_notice_subject',
						'type'    => 'text',
						'default' => sprintf(__( 'Contact Form: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('contact_form_notice', 'subject') ),
					),
					array(
						'name'    => __( 'Contact Form Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('contact_form_notice', 'content') ),
						'id'      => 'contact_form_notice_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('contact_form_notice'),
					),

					// Approve new user register
					array(
						'name' => __( 'New user register (auto approve)', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_user_register_auto_approve',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'New user register Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_register_auto_approve', 'subject') ),
						'id'      => 'user_register_auto_approve_subject',
						'type'    => 'text',
						'default' => sprintf(__( 'New user register: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_register_auto_approve', 'subject') ),
					),
					array(
						'name'    => __( 'New user register Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_register_auto_approve', 'content') ),
						'id'      => 'user_register_auto_approve_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('user_register_auto_approve'),
					),
					// Approve new user register
					array(
						'name' => __( 'Approve new user register', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_user_register_need_approve',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Approve new user register Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_register_need_approve', 'subject') ),
						'id'      => 'user_register_need_approve_subject',
						'type'    => 'text',
						'default' => sprintf(__( 'Approve new user register: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_register_need_approve', 'subject') ),
					),
					array(
						'name'    => __( 'Approve new user register Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_register_need_approve', 'content') ),
						'id'      => 'user_register_need_approve_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('user_register_need_approve'),
					),
					// Approved user register
					array(
						'name' => __( 'Approved user', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_user_register_approved',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Approved user Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_register_approved', 'subject') ),
						'id'      => 'user_register_approved_subject',
						'type'    => 'text',
						'default' => sprintf(__( 'Approve new user register: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_register_approved', 'subject') ),
					),
					array(
						'name'    => __( 'Approved user Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_register_approved', 'content') ),
						'id'      => 'user_register_approved_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('user_register_approved'),
					),
					// Denied user register
					array(
						'name' => __( 'Denied user', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_user_register_denied',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Denied user Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_register_denied', 'subject') ),
						'id'      => 'user_register_denied_subject',
						'type'    => 'text',
						'default' => sprintf(__( 'Approve new user register: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_register_denied', 'subject') ),
					),
					array(
						'name'    => __( 'Denied user Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_register_denied', 'content') ),
						'id'      => 'user_register_denied_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('user_register_denied'),
					),

					// Reset Password
					array(
						'name' => __( 'Reset Password', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_user_reset_password',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Reset Password Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_reset_password', 'subject') ),
						'id'      => 'user_reset_password_subject',
						'type'    => 'text',
						'default' => 'Your new password',
					),
					array(
						'name'    => __( 'Reset Password Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('user_reset_password', 'content') ),
						'id'      => 'user_reset_password_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('user_reset_password'),
					),

					// Claim Claimer
					array(
						'name' => __( 'Send email for claimer', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_claim_claimer',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('claim_claimer_notice', 'subject') ),
						'id'      => 'claim_claimer_notice_subject',
						'type'    => 'text',
						'default' => 'Claim successful',
					),
					array(
						'name'    => __( 'Email Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('claim_claimer_notice', 'content') ),
						'id'      => 'claim_claimer_notice_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('claim_claimer_notice'),
					),

					// Claim Admin
					array(
						'name' => __( 'Send email for Admin (claim listing)', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_claim_admin',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('claim_admin_notice', 'subject') ),
						'id'      => 'claim_admin_notice_subject',
						'type'    => 'text',
						'default' => 'Claim successful',
					),
					array(
						'name'    => __( 'Email Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('claim_admin_notice', 'content') ),
						'id'      => 'claim_admin_notice_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('claim_admin_notice'),
					),

					// Claim Author
					array(
						'name' => __( 'Send email for Author (claim listing)', 'wp-listings-directory' ),
						'desc' => '',
						'type' => 'wp_listings_directory_title',
						'id'   => 'wp_listings_directory_title_claim_auhtor',
						'before_row' => '<hr>',
						'after_row'  => '<hr>'
					),
					array(
						'name'    => __( 'Subject', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('claim_author_notice', 'subject') ),
						'id'      => 'claim_author_notice_subject',
						'type'    => 'text',
						'default' => 'Claim successful',
					),
					array(
						'name'    => __( 'Email Content', 'wp-listings-directory' ),
						'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'wp-listings-directory' ), WP_Listings_Directory_Email::display_email_vars('claim_author_notice', 'content') ),
						'id'      => 'claim_author_notice_content',
						'type'    => 'wysiwyg',
						'default' => WP_Listings_Directory_Email::get_email_default_content('claim_author_notice'),
					),
				)
			)		 
		);
		
		//Return all settings array if necessary
		if ( $active_tab === null   ) {  
			return apply_filters( 'wp_listings_directory_registered_settings', $wp_listings_directory_settings );
		}

		// Add other tabs and settings fields as needed
		return apply_filters( 'wp_listings_directory_registered_'.$active_tab.'_settings', isset($wp_listings_directory_settings[ $active_tab ])?$wp_listings_directory_settings[ $active_tab ]:array() );
	}

	/**
	 * Show Settings Notices
	 *
	 * @param $object_id
	 * @param $updated
	 * @param $cmb
	 */
	public function settings_notices( $object_id, $updated, $cmb ) {

		//Sanity check
		if ( $object_id !== $this->key ) {
			return;
		}

		if ( did_action( 'cmb2_save_options-page_fields' ) === 1 ) {
			settings_errors( 'wp_listings_directory-notices' );
		}

		add_settings_error( 'wp_listings_directory-notices', 'global-settings-updated', __( 'Settings updated.', 'wp-listings-directory' ), 'updated' );
	}


	/**
	 * Public getter method for retrieving protected/private variables
	 *
	 * @since  1.0
	 *
	 * @param  string $field Field to retrieve
	 *
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {

		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'fields', 'wp_listings_directory_title', 'options_page' ), true ) ) {
			return $this->{$field};
		}
		if ( 'option_metabox' === $field ) {
			return $this->option_metabox();
		}

		throw new Exception( 'Invalid listing: ' . $field );
	}


}

// Get it started
$WP_Listings_Directory_Settings = new WP_Listings_Directory_Settings();

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 *
 * @param  string $key Options array key
 *
 * @return mixed        Option value
 */
function wp_listings_directory_get_option( $key = '', $default = false ) {
	global $wp_listings_directory_options;
	
	$wp_listings_directory_options = wp_listings_directory_get_settings();

	$value = ! empty( $wp_listings_directory_options[ $key ] ) ? $wp_listings_directory_options[ $key ] : $default;
	$value = apply_filters( 'wp_listings_directory_get_option', $value, $key, $default );

	return apply_filters( 'wp_listings_directory_get_option_' . $key, $value, $key, $default );
}



/**
 * Get Settings
 *
 * Retrieves all WP_Listings_Directory plugin settings
 *
 * @since 1.0
 * @return array WP_Listings_Directory settings
 */
function wp_listings_directory_get_settings() {
	return apply_filters( 'wp_listings_directory_get_settings', get_option( 'wp_listings_directory_settings' ) );
}


/**
 * WP_Listings_Directory Title
 *
 * Renders custom section titles output; Really only an <hr> because CMB2's output is a bit funky
 *
 * @since 1.0
 *
 * @param       $field_object , $escaped_value, $object_id, $object_type, $field_type_object
 *
 * @return void
 */
function wp_listings_directory_title_callback( $field_object, $escaped_value, $object_id, $object_type, $field_type_object ) {
	$id                = $field_type_object->field->args['id'];
	$title             = $field_type_object->field->args['name'];
	$field_description = $field_type_object->field->args['desc'];
	if ( $field_description ) {
		echo '<div class="desc">'.$field_description.'</div>';
	}
}

function wp_listings_directory_hidden_callback( $field_object, $escaped_value, $object_id, $object_type, $field_type_object ) {
	$id                = $field_type_object->field->args['id'];
	$title             = $field_type_object->field->args['name'];
	$field_description = $field_type_object->field->args['desc'];
	echo '<input type="hidden" name="'.$id.'" value="'.$escaped_value.'">';
	if ( $field_type_object->field->args['human_value'] ) {
		echo '<strong>'.$field_type_object->field->args['human_value'].'</strong>';
	}
	if ( $field_description ) {
		echo '<div class="desc">'.$field_description.'</div>';
	}
}

function wp_listings_directory_cmb2_get_page_options( $query_args, $force = false ) {
	$post_options = array( '' => '' ); // Blank option

	if ( ( ! isset( $_GET['page'] ) || 'listing-settings' != $_GET['page'] ) && ! $force ) {
		return $post_options;
	}

	$args = wp_parse_args( $query_args, array(
		'post_type'   => 'page',
		'numberposts' => 10,
	) );

	$posts = get_posts( $args );

	if ( $posts ) {
		foreach ( $posts as $post ) {
			$post_options[ $post->ID ] = $post->post_title;
		}
	}

	return $post_options;
}

add_filter( 'cmb2_get_metabox_form_format', 'wp_listings_directory_modify_cmb2_form_output', 10, 3 );
function wp_listings_directory_modify_cmb2_form_output( $form_format, $object_id, $cmb ) {
	//only modify the wp_listings_directory settings form
	if ( 'wp_listings_directory_settings' == $object_id && 'options_page' == $cmb->cmb_id ) {

		return '<form class="cmb-form" method="post" id="%1$s" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="object_id" value="%2$s">%3$s<div class="wp_listings_directory-submit-wrap"><input type="submit" name="submit-cmb" value="' . __( 'Save Settings', 'wp-listings-directory' ) . '" class="button-primary"></div></form>';
	}

	return $form_format;

}
