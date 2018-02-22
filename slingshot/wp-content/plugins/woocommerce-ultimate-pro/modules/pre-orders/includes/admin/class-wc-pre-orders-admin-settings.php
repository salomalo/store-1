<?php
/**
 * WooCommerce Pre-Orders
 *
 * @package   WC_Pre_Orders/Admin
 * @author    WooThemes
 * @copyright Copyright (c) 2015, WooThemes
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pre-Orders Admin Settings class.
 */
class WC_Pre_Orders_Admin_Settings {

	/**
	 * Settings page tab ID
	 *
	 * @var string
	 */
	private $settings_tab_id = 'pre_orders';

	/**
	 * Initialize the admin settings actions.
	 */
	public function __construct() {
		// Add 'Pre-Orders' tab to WooCommerce settings.
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab'  ), 21, 1 );

		// Show settings.
		add_action( 'woocommerce_settings_tabs_' . $this->settings_tab_id, array( $this, 'show_settings' ) );

		// Save settings.
		add_action( 'woocommerce_update_options_' . $this->settings_tab_id, array( $this, 'save_settings' ) );
	}

	/**
	 * Add 'Pre-Orders' tab to WooCommerce Settings tabs
	 *
	 * @param  array $settings_tabs Tabs array sans 'Pre-Orders' tab.
	 *
	 * @return array $settings_tabs Now with 100% more 'Pre-Orders' tab!
	 */
	public function add_settings_tab( $settings_tabs ) {
		$settings_tabs[ $this->settings_tab_id ] = __( 'Pre-Orders', 'ultimatewoo-pro' );

		return $settings_tabs;
	}

	/**
	 * Show the 'Pre-Orders' settings page.
	 */
	public function show_settings() {
		woocommerce_admin_fields( $this->get_settings() );
	}

	/**
	 * Save the 'Pre-Orders' settings page.
	 */
	public function save_settings() {
		woocommerce_update_options( $this->get_settings() );
	}

	/**
	 * Returns settings array for use by output/save functions.
	 *
	 * @return array Settings.
	 */
	public function get_settings() {
		return apply_filters( 'wc_pre_orders_settings', array(

			array(
				'title' => __( 'Button Text', 'ultimatewoo-pro' ),
				'type' => 'title'
			),

			array(
				'title'    => __( 'Add to Cart Button Text', 'ultimatewoo-pro' ),
				'desc'     => __( 'This controls the add to cart button text on single product pages for products that have pre-orders enabled.', 'ultimatewoo-pro' ),
				'desc_tip' => true,
				'id'       => 'wc_pre_orders_add_to_cart_button_text',
				'default'  => __( 'Pre-Order Now', 'ultimatewoo-pro' ),
				'type'     => 'text',
			),

			array(
				'title'    => __( 'Place Order Button Text', 'ultimatewoo-pro' ),
				'desc'     => __( 'This controls the place order button text on the checkout when an order contains a pre-orders.', 'ultimatewoo-pro' ),
				'desc_tip' => true,
				'id'       => 'wc_pre_orders_place_order_button_text',
				'default'  => __( 'Place Pre-Order Now', 'ultimatewoo-pro' ),
				'type'     => 'text',
			),

			array( 'type' => 'sectionend' ),

			array(
				'title' => __( 'Product Message', 'ultimatewoo-pro' ),
				'desc'  => sprintf( __( 'Adjust the message by using %1$s{availability_date}%2$s and %1$s{availability_time}%2$s to represent the product\'s availability date and time.', 'ultimatewoo-pro' ), '<code>', '</code>' ),
				'type'  => 'title'
			),

			array(
				'title'    => __( 'Single Product Page Message', 'ultimatewoo-pro' ),
				'desc'     => __( 'Add an optional message to the single product page below the price. Use this to announce when the pre-order will be available by using {availability_date} and {availability_time}. Limited HTML is allowed. Leave blank to disable.', 'ultimatewoo-pro' ),
				'desc_tip' => true,
				'id'       => 'wc_pre_orders_single_product_message',
				'default'  => sprintf( __( 'This item will be released %s.', 'ultimatewoo-pro' ), '{availability_date}' ),
				'type'     => 'textarea',
			),

			array(
				'title'    => __( 'Shop Loop Product Message', 'ultimatewoo-pro' ),
				'desc'     => __( 'Add an optional message to each pre-order enabled product on the shop loop page above the add to cart button. Use this to announce when the pre-order will be available by using {availability_date} and {availability_time}. Limited HTML is allowed. Leave blank to disable.', 'ultimatewoo-pro' ),
				'desc_tip' => true,
				'id'       => 'wc_pre_orders_shop_loop_product_message',
				'default'  => sprintf( __( 'Available %s.', 'ultimatewoo-pro' ), '{availability_date}' ),
				'type'     => 'textarea',
			),

			array( 'type' => 'sectionend' ),

			array(
				'title' => __( 'Cart / Checkout Display Text', 'ultimatewoo-pro' ),
				'desc'  => sprintf( __( 'Adjust the display of the order total by using %1$s{order_total}%2$s to represent the order total and %1$s{availability_date}%2$s to represent the product\'s availability date.', 'ultimatewoo-pro' ), '<code>', '</code>' ),
				'type'  => 'title'
			),

			array(
				'title'    => __( 'Availability Date Title Text', 'ultimatewoo-pro' ),
				'desc'     => __( 'This controls the title of the availability date section on the cart/checkout page. Leave blank to disable display of the availability date in the cart.', 'ultimatewoo-pro' ),
				'desc_tip' => true,
				'id'       => 'wc_pre_orders_availability_date_cart_title_text',
				'default'  => __( 'Available', 'ultimatewoo-pro' ),
				'type'     => 'text',
			),

			array(
				'title'    => __( 'Charged Upon Release Order Total Format', 'ultimatewoo-pro' ),
				'desc'     => __( 'This controls the order total format when the cart contains a pre-order charged upon release. Use this to indicate when the customer will be charged for their pre-order by using {availability_date} and {order_total}.', 'ultimatewoo-pro' ),
				'desc_tip' => true,
				'id'       => 'wc_pre_orders_upon_release_order_total_format',
				'default'  => sprintf( __( '%s charged %s', 'ultimatewoo-pro' ), '{order_total}', '{availability_date}' ),
				'css'      => 'min-width: 300px;',
				'type'     => 'text',
			),

			array(
				'title'    => __( 'Charged Upfront Order Total Format', 'ultimatewoo-pro' ),
				'desc'     => __( 'This controls the order total format when the cart contains a pre-order charged upfront. Use this to indicate how the customer is charged for their pre-order by using {availability_date} and {order_total}.', 'ultimatewoo-pro' ),
				'desc_tip' => true,
				'id'       => 'wc_pre_orders_upfront_order_total_format',
				'default'  => sprintf( __( '%s charged upfront', 'ultimatewoo-pro' ), '{order_total}' ),
				'css'      => 'min-width: 150px;',
				'type'     => 'text',
			),

			array( 'type' => 'sectionend' ),

			array(
				'title' => __( 'Staging/Test', 'ultimatewoo-pro' ),
				'type'  => 'title'
			),

			array(
				'title'    => __( 'Disable automated pre order processing.', 'ultimatewoo-pro' ),
				'desc'     => __( ' This is used for when you\'re on a staging/testing site and don\'t want any pre orders to be processed automatically.', 'ultimatewoo-pro' ),
				'desc_tip' => true,
				'id'       => 'wc_pre_orders_disable_auto_processing',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array( 'type' => 'sectionend' ),
		) );
	}
}

new WC_Pre_Orders_Admin_Settings();
