<form action="admin-post.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="action" value="fue_followup_save_settings" />
	<input type="hidden" name="section" value="<?php echo $tab; ?>" />

	<h3><?php _e('Upload CSV of Emails', 'ultimatewoo-pro'); ?></h3>

	<p><?php _e('Import your existing mailing lists and email addresses. Then go to <a href="admin.php?page=followup-emails-subscribers">Subscribers</a> to assign to lists and manage your addresses.', 'ultimatewoo-pro'); ?></p>    

	<p class="form-field">
		<input type="file" name="csv" />
	</p>
	<p class="submit">
		<input type="submit" class="button-primary" name="upload" value="<?php _e('Upload', 'ultimatewoo-pro'); ?>" />
	</p>

	<hr>

	<h3><?php _e('Page Endpoints', 'ultimatewoo-pro'); ?></h3>

	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Unsubscribe Page', 'ultimatewoo-pro'); ?></th>
			<td><input type="text" name="unsubscribe_endpoint" id="unsubscribe_endpoint" value="<?php echo esc_attr( get_option( 'fue_unsubscribe_endpoint', 'unsubscribe' ) ); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Email Subscriptions Page', 'ultimatewoo-pro'); ?></th>
			<td><input type="text" name="email_subscriptions_endpoint" id="email_subscriptions_endpoint" value="<?php echo esc_attr( get_option( 'fue_email_subscriptions_endpoint', 'email-subscriptions' ) ); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Email Preferences Page', 'ultimatewoo-pro'); ?></th>
			<td><input type="text" name="email_preferences_endpoint" id="email_preferences_endpoint" value="<?php echo esc_attr( get_option( 'fue_email_preferences_endpoint', 'email-preferences' ) ); ?>" /></td>
		</tr>
	</table>

	<?php do_action('fue_settings_subscribers'); ?>

	<p class="submit">
		<input type="submit" name="save" value="<?php _e('Save Settings', 'ultimatewoo-pro'); ?>" class="button-primary" />
	</p>

</form>