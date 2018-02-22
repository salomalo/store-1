<?php
/**
 * WooCommerce Memberships
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Memberships to newer
 * versions in the future. If you wish to customize WooCommerce Memberships for your
 * needs please refer to https://docs.woocommerce.com/document/woocommerce-memberships/ for more information.
 *
 * @package   WC-Memberships/Admin/Meta-Boxes
 * @author    SkyVerge
 * @category  Admin
 * @copyright Copyright (c) 2014-2018, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

/**
 * View for content restriction rules table
 *
 * @since 1.7.0
 */
class WC_Memberships_Meta_Box_View_Content_Restriction_Rules extends WC_Memberships_Meta_Box_View {


	/**
	 * HTML output
	 *
	 * @since 1.7.0
	 * @param array $args
	 */
	public function output( $args = array() ) {

		?>
		<table class="widefat rules content-restriction-rules js-rules">

			<thead>
				<tr>
					<td class="check-column" style="width: 5%;">
						<label class="screen-reader-text" for="content-restriction-rules-select-all"> <?php esc_html_e( 'Select all', 'ultimatewoo-pro' ); ?></label>
						<input
							type="checkbox"
							id="content-restriction-rules-select-all"
						>
					</td>

					<?php if ( 'wc_membership_plan' === $this->post->post_type ) : ?>

						<th scope="col" class="content-restriction-content-type" style="width: 15%;">
							<?php esc_html_e( 'Type', 'ultimatewoo-pro' ); ?>
						</th>

						<th scope="col" class="content-restriction-objects" style="width: 60%;">
							<?php esc_html_e( 'Title', 'ultimatewoo-pro' ); ?>
							<?php echo wc_help_tip( __( 'Search&hellip; or leave blank to apply to all', 'ultimatewoo-pro' ) ); ?>
						</th>

					<?php else : ?>

						<th scope="col" class="content-restriction-membership-plan" style="width: 30%;">
							<?php esc_html_e( 'Plan', 'ultimatewoo-pro' ); ?>
						</th>

					<?php endif; ?>

					<th scope="col" class="content-restriction-access-schedule" style="<?php echo 'wc_membership_plan' === $this->post->post_type ? 'width: 25%;' : 'width: 65%;' ?>">
						<?php esc_html_e( 'Accessible', 'ultimatewoo-pro' ); ?>
						<?php echo wc_help_tip( __( 'When will members gain access to content?', 'ultimatewoo-pro' ) ); ?>
					</th>
				</tr>
			</thead>
			<?php

			// load content restriction rule view object
			require( wc_memberships()->get_plugin_path() . '/includes/admin/meta-boxes/views/class-wc-memberships-meta-box-view-content-restriction-rule.php' );

			// get the rules to output in meta box inputs
			$content_restriction_rules = $this->meta_box->get_content_restriction_rules();

			// output content restriction rule views
			foreach ( $content_restriction_rules as $index => $rule ) {

				$view = new WC_Memberships_Meta_Box_View_Content_Restriction_Rule( $this->meta_box, $rule );
				$view->output( array( 'index' => $index ) );
			}

			// get available membership plans
			$membership_plans = $this->meta_box->get_available_membership_plans();

			?>
			<tbody class="norules <?php if ( count( $membership_plans ) > 0 && count( $content_restriction_rules ) > 1 ) : ?>hide<?php endif; ?>">
				<tr>
					<td colspan="<?php echo ( 'wc_membership_plan' === $this->post->post_type ) ? 4 : 3; ?>">
						<?php

						if ( 'wc_membership_plan' === $this->post->post_type ) {
							esc_html_e( 'There are no rules yet. Click below to add one.', 'ultimatewoo-pro' );
						}

						if ( empty( $membership_plans ) ) {
							$add_membership_plan_link = ' <a target="_blank" href="' . esc_url( admin_url( 'post-new.php?post_type=wc_membership_plan' ) ) . '">' . esc_html__( 'Add a Membership Plan', 'ultimatewoo-pro' ) . '</a>.';
							/* translators: Placeholder: %s outputs "Add a Membership Plan" action link */
							printf( __( 'To create restriction rules, please %s', 'ultimatewoo-pro' ), $add_membership_plan_link );
						} else {
							esc_html_e( 'This content can be viewed by all visitors. Add a rule to restrict it to members.', 'ultimatewoo-pro' );
						}

						?>
					</td>
				</tr>
			</tbody>

			<?php if ( 'wc_membership_plan' === $this->post->post_type || ! empty( $membership_plans ) ) : ?>

				<tfoot>
					<tr>
						<th colspan="<?php echo ( 'wc_membership_plan' === $this->post->post_type ) ? 4 : 3; ?>">
							<button
								type="button"
							    class="button button-primary add-rule js-add-rule">
								<?php esc_html_e( 'Add New Rule', 'ultimatewoo-pro' ); ?>
							</button>
							<button
								type="button"
								class="button button-secondary remove-rules js-remove-rules
						        <?php if ( count( $content_restriction_rules ) < 2 ) : ?>hide<?php endif; ?>">
								<?php esc_html_e( 'Delete Selected', 'ultimatewoo-pro' ); ?>
							</button>
						</th>
					</tr>
				</tfoot>

			<?php endif; ?>

		</table>
		<?php
	}


}