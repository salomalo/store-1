<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Customer Retry
 *
 * Email sent to the customer when an attempt to automatically process a subscription renewal payment has failed
 * and a retry rule has been applied to retry the payment in the future.
 *
 * @version		2.1
 * @package		WooCommerce_Subscriptions/Includes/Emails
 * @author		Prospress
 * @extends		WCS_Email_Customer_Renewal_Invoice
 */
class WCS_Email_Customer_Payment_Retry extends WCS_Email_Customer_Renewal_Invoice {

	/**
	 * Constructor
	 */
	function __construct() {

		$this->id             = 'customer_payment_retry';
		$this->title          = __( 'Customer Payment Retry', 'ultimatewoo-pro' );
		$this->description    = __( 'Sent to a customer when an attempt to automatically process a subscription renewal payment has failed and a retry rule has been applied to retry the payment in the future. The email contains the renewal order information, date of the scheduled retry and payment links to allow the customer to pay for the renewal order manually instead of waiting for the automatic retry.', 'ultimatewoo-pro' );
		$this->customer_email = true;

		$this->template_html  = 'emails/customer-payment-retry.php';
		$this->template_plain = 'emails/plain/customer-payment-retry.php';
		$this->template_base  = plugin_dir_path( WC_Subscriptions::$plugin_file ) . 'templates/';

		$this->subject        = __( 'Automatic payment failed for {order_number}, we will retry {retry_time}', 'ultimatewoo-pro' );
		$this->heading        = __( 'Automatic payment failed for order {order_number}', 'ultimatewoo-pro' );

		// We want all the parent's methods, with none of its properties, so call its parent's constructor, rather than my parent constructor
		WC_Email::__construct();
	}

	/**
	 * trigger function.
	 *
	 * We can use most of WCS_Email_Customer_Renewal_Invoice's trigger method but we need to set up the
	 * retry data ourselves before calling it as WCS_Email_Customer_Renewal_Invoice has no retry
	 * associated with it.
	 *
	 * @access public
	 * @return void
	 */
	function trigger( $order_id, $order = null ) {

		$this->retry = WCS_Retry_Manager::store()->get_last_retry_for_order( $order_id );

		$retry_time_index = array_search( '{retry_time}', $this->find );
		if ( false === $retry_time_index ) {
			$this->find['retry_time']    = '{retry_time}';
			$this->replace['retry_time'] = strtolower( wcs_get_human_time_diff( $this->retry->get_time() ) );
		} else {
			$this->replace[ $retry_time_index ] = strtolower( wcs_get_human_time_diff( $this->retry->get_time() ) );
		}

		parent::trigger( $order_id, $order );
	}

	/**
	 * get_subject function.
	 *
	 * @access public
	 * @return string
	 */
	function get_subject() {
		return apply_filters( 'woocommerce_subscriptions_email_subject_customer_retry', parent::get_subject(), $this->object );
	}

	/**
	 * get_heading function.
	 *
	 * @access public
	 * @return string
	 */
	function get_heading() {
		return apply_filters( 'woocommerce_email_heading_customer_retry', parent::get_heading(), $this->object );
	}

	/**
	 * get_content_html function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_html() {
		ob_start();
		wc_get_template(
			$this->template_html,
			array(
				'order'         => $this->object,
				'retry'         => $this->retry,
				'email_heading' => $this->get_heading(),
				'sent_to_admin' => false,
				'plain_text'    => false,
				'email'         => $this,
			),
			'',
			$this->template_base
		);
		return ob_get_clean();
	}

	/**
	 * get_content_plain function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_plain() {
		ob_start();
		wc_get_template(
			$this->template_plain,
			array(
				'order'         => $this->object,
				'retry'         => $this->retry,
				'email_heading' => $this->get_heading(),
				'sent_to_admin' => false,
				'plain_text'    => true,
				'email'         => $this,
			),
			'',
			$this->template_base
		);
		return ob_get_clean();
	}
}
