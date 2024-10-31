<?php
	$message    = '';
	$alert_box  = '';
	$moove_controller = new Moove_Redirect_Controller();
	if ( isset( $_POST ) && ! empty( $_POST ) && isset( $_POST['moove_404_an_nonce'] ) ) :
		
		$nonce = sanitize_key( $_POST['moove_404_an_nonce'] );
		if ( ! wp_verify_nonce( $nonce, 'moove_404_an_nonce_field' ) ) :
			die( 'Security check' );
		else :
			$base       = sanitize_text_field( wp_unslash( $_POST['moove-redirect-base'] ) );
			$status     = intval( wp_unslash( $_POST['moove-redirect-status'] ) );
			$message    = '';
			$error      = false;
			if ( '' !== $base  && is_numeric( $status ) ) :
				$validated = $moove_controller->moove_validate_url( $base );
				if ( $validated['success'] ) :
					$options = get_option( 'moove_404_redirect_options' );
					$options = is_array( $options ) ? $options : array();
					if ( ! is_array( $options ) ) :
						$options = array();
					endif;
					if ( "/" !== substr( $base, -1 ) ) :
						$base = $base . '/';
					endif;
					$parts = explode( "/", $base );
					$new_entry = array(
						'base'      =>  $base,
						'status'    =>  $status
					);
					if (  isset( $options[ count( $parts ) ] ) &&  in_array( $new_entry, $options[ count( $parts ) ] ) ) :
						$message    = '<h4 class="moove-error">'.__('This rule already exists! Please try another one.','moove').'</h4>';
						$error      = true;
					else:
						$options[ count( $parts ) ][] = $new_entry;
						krsort( $options );
						update_option( 'moove_404_redirect_options', $options );
						$message    = '<h4 class="moove-success">'.__('The rule was added successfully!','moove').'</h4>';
						$error      = false;
					endif;
				else:
					$message    = $validated['error'];
					$error      = true;
				endif;

			else:
				$message    = '<h4 class="moove-error">'.__('Please fill out the "BASE URL" field!','moove').'</h4>';
				$error      = true;
			endif;
		endif;
		ob_start(); ?>
			<div class="moove-redirect-message-cnt <?php echo ( $error ) ? 'moove-error-box' : 'moove-success-box'; ?>">
				<?php echo $message; ?>
			</div>
			<!-- moove-redirect-message-cnt -->
		<?php
		$alert_box = ob_get_clean();
	endif; ?>

	<div class="moove-add-new-redirect-404">
		<h1><?php _e( 'Add new 404 Redirect rule', 'moove' ); ?></h1>
		<?php echo $alert_box; ?>
		<div class="moove-redirect-content-left">
			<form action="" class="moove-redirect-box" method="post">
				<?php wp_nonce_field( 'moove_404_an_nonce_field', 'moove_404_an_nonce' ); ?>
				<h4><?php _e( 'Redirect Settings', 'moove' ); ?></h4>
				<hr>
				<label for="moove-redirect-base"><?php _e( 'Base url: ', 'moove' ); ?>
					<br/>
					<span class="moove-redirect-base"><?php echo home_url('/'); ?><span class="moove-base-url"></span>
					</span>
				</label>
				<input type="text" value="" name="moove-redirect-base" id="moove-redirect-base" />
				<br/><br/><label for="moove-redirect-to"><?php _e( 'Status: ', 'moove' ); ?></label><br/>
				<select name="moove-redirect-status" id="moove-redirect-status">
					<option value="301">301 Moved Permanently</option>
					<option value="302">302 Found</option>
					<option value="307">307 Temporary Redirect</option>
				</select>
				<br />
				<button type="submit" class="button moove-redirect-button"><?php _e( 'Add rule', 'moove' ); ?></button>
			</form>
		</div>
		<!-- moove-redirect-content-left -->
	</div>
	<!-- moove-add-new-redirect-404 -->

