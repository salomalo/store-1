<?php
/**
 * My Bookings - Deprecated
 *
 * Shows bookings on the account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce-bookings/myaccount/my-bookings.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @version 1.9.10
 * @deprecated 1.10.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<h2><?php echo apply_filters( 'woocommerce_my_account_bookings_title', __( 'My Bookings', 'ultimatewoo-pro' ) ); ?></h2>

<table class="shop_table my_account_bookings">
	<thead>
		<tr>
			<th scope="col" class="booking-id"><?php _e( 'ID', 'ultimatewoo-pro' ); ?></th>
			<th scope="col" class="booked-product"><?php _e( 'Booked', 'ultimatewoo-pro' ); ?></th>
			<th scope="col" class="order-number"><?php _e( 'Order', 'ultimatewoo-pro' ); ?></th>
			<th scope="col" class="booking-start-date"><?php _e( 'Start Date', 'ultimatewoo-pro' ); ?></th>
			<th scope="col" class="booking-end-date"><?php _e( 'End Date', 'ultimatewoo-pro' ); ?></th>
			<th scope="col" class="booking-status"><?php _e( 'Status', 'ultimatewoo-pro' ); ?></th>
			<th scope="col" class="booking-cancel"></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $bookings as $booking ) : ?>
			<tr>
				<td class="booking-id"><?php echo esc_html( $booking->get_id() ); ?></td>
				<td class="booked-product">
					<?php if ( $booking->get_product() && $booking->get_product()->is_type( 'booking' ) ) : ?>
					<a href="<?php echo esc_url( get_permalink( $booking->get_product()->get_id() ) ); ?>">
						<?php echo esc_html( $booking->get_product()->get_title() ); ?>
					</a>
					<?php endif; ?>
				</td>
				<td class="order-number">
					<?php if ( $booking->get_order() ) : ?>
					<a href="<?php echo esc_url( $booking->get_order()->get_view_order_url() ); ?>">
						<?php echo esc_html( $booking->get_order()->get_order_number() ); ?>
					</a>
					<?php endif; ?>
				</td>
				<td class="booking-start-date"><?php echo esc_html( $booking->get_start_date() ); ?></td>
				<td class="booking-end-date"><?php echo esc_html( $booking->get_end_date() ); ?></td>
				<td class="booking-status"><?php echo esc_html( wc_bookings_get_status_label( $booking->get_status() ) ); ?></td>
				<td class="booking-cancel">
					<?php if ( $booking->get_status() != 'cancelled' && $booking->get_status() != 'completed' && ! $booking->passed_cancel_day() ) : ?>
					<a href="<?php echo esc_url( $booking->get_cancel_url() ); ?>" class="button cancel"><?php _e( 'Cancel', 'ultimatewoo-pro' ); ?></a>
					<?php endif ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
