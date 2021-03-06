<?php
/**
 * Copyright: 2017 WooCommerce.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if WooCommerce is active
 */
if ( is_woocommerce_active() ) {
	/**
	 * Main Class
	 */
	class WC_Table_Rate_Shipping {

		/**
		 * Constructor
		 */
		public function __construct() {
			define( 'TABLE_RATE_SHIPPING_VERSION', '3.0.6' );
			define( 'TABLE_RATE_SHIPPING_DEBUG', defined( 'WP_DEBUG' ) && 'true' == WP_DEBUG && ( ! defined( 'WP_DEBUG_DISPLAY' ) || 'true' == WP_DEBUG_DISPLAY ) );
			add_action( 'plugins_loaded', array( $this, 'init' ) );
			// ULTIMATEWOO fix - change to hook on init instead of activation hook
			add_action( 'init', array( $this, 'install' ) );
		}

		/**
		 * Register method for usage
		 * @param  array $shipping_methods
		 * @return array
		 */
		public function woocommerce_shipping_methods( $shipping_methods ) {
			$shipping_methods['table_rate'] = 'WC_Shipping_Table_Rate';
			return $shipping_methods;
		}

		/**
		 * Init TRS
		 */
		public function init() {
			include_once( 'includes/functions-ajax.php' );
			include_once( 'includes/functions-admin.php' );

			/**
			 * Install check (for updates)
			 */
			if ( get_option( 'table_rate_shipping_version' ) < TABLE_RATE_SHIPPING_VERSION ) {
				$this->install();
			}

			// 2.6.0+ supports zones and instances
			if ( version_compare( WC_VERSION, '2.6.0', '>=' ) ) {
				add_filter( 'woocommerce_shipping_methods', array( $this, 'woocommerce_shipping_methods' ) );
			} else {
				if ( ! defined( 'SHIPPING_ZONES_TEXTDOMAIN' ) ) {
					define( 'SHIPPING_ZONES_TEXTDOMAIN', 'ultimatewoo-pro' );
				}
				if ( ! class_exists( 'WC_Shipping_zone' ) ) {
					include_once( 'includes/legacy/shipping-zones/class-wc-shipping-zones.php' );
				}
				add_action( 'woocommerce_load_shipping_methods', array( $this, 'load_shipping_methods' ) );
				add_action( 'admin_notices', array( $this, 'welcome_notice' ) );
			}

			// Hooks
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
			add_action( 'woocommerce_shipping_init', array( $this, 'shipping_init' ) );
		}

		/**
		 * Localisation
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'woocommerce-table-rate-shipping', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Row meta
		 * @param  array $links
		 * @param  string $file
		 * @return array
		 */
		public function plugin_row_meta( $links, $file ) {
			if ( $file === plugin_basename( __FILE__ ) ) {
				$row_meta = array(
					'docs'    => '<a href="' . esc_url( apply_filters( 'woocommerce_table_rate_shipping_docs_url', 'http://docs.woothemes.com/document/table-rate-shipping/' ) ) . '" title="' . esc_attr( __( 'View Documentation', 'ultimatewoo-pro' ) ) . '">' . __( 'Docs', 'ultimatewoo-pro' ) . '</a>',
					'support' => '<a href="' . esc_url( apply_filters( 'woocommerce_table_rate_support_url', 'http://support.woothemes.com/' ) ) . '" title="' . esc_attr( __( 'Visit Premium Customer Support Forum', 'ultimatewoo-pro' ) ) . '">' . __( 'Premium Support', 'ultimatewoo-pro' ) . '</a>',
				);
				return array_merge( $links, $row_meta );
			}
			return (array) $links;
		}

		/**
		 * Admin welcome notice
		 */
		public function welcome_notice() {
			if ( get_option( 'hide_table_rate_welcome_notice' ) ) {
				return;
			}
			wp_enqueue_style( 'woocommerce-activation', WC()->plugin_url() . '/assets/css/activation.css' );
			?>
			<div id="message" class="updated woocommerce-message wc-connect">
				<div class="squeezer">
					<h4><?php _e( '<strong>Table Rates is installed</strong> &#8211; Add some shipping zones to get started :)', 'ultimatewoo-pro' ); ?></h4>
					<p class="submit"><a href="<?php echo admin_url('admin.php?page=shipping_zones'); ?>" class="button-primary"><?php _e( 'Setup Zones', 'ultimatewoo-pro' ); ?></a> <a class="skip button-primary" href="http://docs.woothemes.com/document/table-rate-shipping/"><?php _e('Documentation', 'ultimatewoo-pro'); ?></a></p>
				</div>
			</div>
			<?php
			update_option( 'hide_table_rate_welcome_notice', 1 );
		}

		/**
		 * Admin styles + scripts
		 */
		public function admin_enqueue_scripts() {
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_enqueue_style( 'woocommerce_shipping_table_rate_styles', plugins_url( '/assets/css/admin.css', __FILE__ ) );
			wp_register_script( 'woocommerce_shipping_table_rate_rows', plugins_url( '/assets/js/table-rate-rows' . $suffix . '.js', __FILE__ ), array( 'jquery', 'wp-util' ) );
			wp_localize_script( 'woocommerce_shipping_table_rate_rows', 'woocommerce_shipping_table_rate_rows', array(
				'i18n' => array(
					'order'        => __( 'Order', 'ultimatewoo-pro' ),
					'item'         => __( 'Item', 'ultimatewoo-pro' ),
					'line_item'    => __( 'Line Item', 'ultimatewoo-pro' ),
					'class'        => __( 'Class', 'ultimatewoo-pro' ),
					'delete_rates' => __( 'Delete the selected rates?', 'ultimatewoo-pro' ),
					'dupe_rates'   => __( 'Duplicate the selected rates?', 'ultimatewoo-pro' ),
				),
				'delete_rates_nonce' => wp_create_nonce( "delete-rate" ),
			) );
		}

		/**
		 * Load shipping class
		 */
		public function shipping_init() {
			include_once( 'includes/class-wc-shipping-table-rate.php' );
		}

		/**
		 * Load shipping methods
		 */
		public function load_shipping_methods( $package ) {
			// Register the main class
			woocommerce_register_shipping_method( 'WC_Shipping_Table_Rate' );

			if ( ! $package ) return;

			// Get zone for package
			$zone = woocommerce_get_shipping_zone( $package );

			if ( TABLE_RATE_SHIPPING_DEBUG ) {
				$notice_text = 'Customer matched shipping zone <strong>' . $zone->zone_name . '</strong> (#' . $zone->zone_id . ')';

				if ( ! wc_has_notice( $notice_text, 'notice' ) ) {
					wc_add_notice( $notice_text, 'notice' );
				}
			}

			if ( $zone->exists() ) {
				// Register zone methods
				$zone->register_shipping_methods();
			}
		}

		/**
		 * Installer
		 */
		public function install() {
			// ULTIMATEWOO fix - check version, then run installer.php
			if ( get_option( 'table_rate_shipping_version' ) != TABLE_RATE_SHIPPING_VERSION ) {
				include_once( 'installer.php' );
				update_option( 'table_rate_shipping_version', TABLE_RATE_SHIPPING_VERSION );
			}				
		}

	}

	new WC_Table_Rate_Shipping();
}

/**
 * Callback function for loading an instance of this method
 *
 * @param mixed $instance
 * @param mixed $title
 * @return WC_Shipping_Table_Rate
 */
function woocommerce_get_shipping_method_table_rate( $instance = false ) {
	return new WC_Shipping_Table_Rate( $instance );
}

//3.0.6