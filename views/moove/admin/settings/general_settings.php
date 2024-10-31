<?php 
	$general_options 	= get_option( 'moove_404_general_options' );
	$general_options 	= $general_options && is_array( $general_options ) ? $general_options : array();
	$stats_hook 			= apply_filters( 'moove_404_redirect_stats_enabled', false );

	if ( isset( $_POST ) && isset( $_POST['moove_404_an_nonce'] ) ) :
		$nonce = sanitize_key( $_POST['moove_404_an_nonce'] );
		if ( ! wp_verify_nonce( $nonce, 'moove_404_an_nonce_field' ) ) :
			die( 'Security check' );
		else :
			if ( is_array( $_POST ) ) :
				if ( isset( $_POST['redirect_statistics'] ) ) :
					$rd404_stats = intval( $_POST['redirect_statistics'] );					
					if ( $rd404_stats === 0 ) :
						update_option( 'moove_404_redirect_statistics', json_encode( array() ) );
					endif;
				else :
					$rd404_stats = $stats_hook ? 1 : 0;
				endif;
				$general_options['redirect_statistics'] = $rd404_stats;
				update_option( 'moove_404_general_options', $general_options );
			endif;
		endif;
	endif;
	
	$statistics 		= isset( $general_options['redirect_statistics'] ) && intval( $general_options['redirect_statistics'] ) >= 0 ? intval( $general_options['redirect_statistics'] ) : ( $stats_hook ? 1 : 0 );

?>
<div class="moove-add-new-redirect-404">
	<h1><?php _e( 'General Settings', 'moove' ); ?></h1>
	<div class="moove-redirect-content-left">
		<form action="<?php esc_url( admin_url( 'admin.php?page=moove-redirect-settings&tab=general_settings' ) ); ?>" class="moove-redirect-box" method="post">
			<?php wp_nonce_field( 'moove_404_an_nonce_field', 'moove_404_an_nonce' ); ?>
			<h4><?php _e( 'Redirect Settings', 'moove' ); ?></h4>
			<hr>
			<br>
			<label for="rd404_statistics"><?php _e( 'Statistics and GeoLocation status', 'moove' ); ?></label><br/>
			<select name="redirect_statistics" id="rd404_statistics">
				<option value="1" <?php echo 1 === $statistics ? 'selected' : ''; ?>>Enabled</option>
				<option value="0" <?php echo 0 === $statistics ? 'selected' : ''; ?>>Disabled</option>
			</select>
			<br/>

			<button type="submit" class="button moove-redirect-button"><?php _e( 'Save Settings', 'moove' ); ?></button>
		</form>
	</div>
	<!-- .moove-redirect-content-left -->
</div>
<!-- .moove-add-new-redirect-404 -->