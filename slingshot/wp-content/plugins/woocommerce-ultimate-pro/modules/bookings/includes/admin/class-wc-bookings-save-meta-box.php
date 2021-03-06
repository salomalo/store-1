<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Bookings_Save_Meta_Box.
 */
class WC_Bookings_Save_Meta_Box {

	/**
	 * Meta box ID.
	 *
	 * @var string
	 */
	public $id;

	/**
	 * Meta box title.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Meta box context.
	 *
	 * @var string
	 */
	public $context;

	/**
	 * Meta box priority.
	 *
	 * @var string
	 */
	public $priority;

	/**
	 * Meta box post types.
	 * @var array
	 */
	public $post_types;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id         = 'woocommerce-booking-save';
		$this->title      = __( 'Booking actions', 'ultimatewoo-pro' );
		$this->context    = 'side';
		$this->priority   = 'high';
		$this->post_types = array( 'wc_booking' );
	}

	/**
	 * Render inner part of meta box.
	 */
	public function meta_box_inner( $post ) {
		wp_nonce_field( 'wc_bookings_save_booking_meta_box', 'wc_bookings_save_booking_meta_box_nonce' );

		?>
		<div id="delete-action"><a class="submitdelete deletion" href="<?php echo esc_url( get_delete_post_link( $post->ID ) ); ?>"><?php _e( 'Move to trash', 'ultimatewoo-pro' ); ?></a></div>

		<input type="submit" class="button save_order button-primary tips" name="save" value="<?php _e( 'Save Booking', 'ultimatewoo-pro' ); ?>" data-tip="<?php _e( 'Save/update the booking', 'ultimatewoo-pro' ); ?>" />
		<?php
	}
}
return new WC_Bookings_Save_Meta_Box();
