<?php
	$remove_item = '';
	$remove_box_message = '';
	$page_url = htmlspecialchars( "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}", ENT_QUOTES, 'UTF-8' );
	if ( isset( $_POST ) && !empty( $_POST ) ) :

		if (  isset( $_POST['moove-redirect-remove'] ) && '' !== $_POST['moove-redirect-remove'] && isset( $_POST['moove_404_rm_nonce'] ) ) :
			$nonce = sanitize_key( $_POST['moove_404_rm_nonce'] );
			if ( ! wp_verify_nonce( $nonce, 'moove_404_rm_nonce_field' ) ) :
				die( 'Security check' );
			else :
				$remove_item = sanitize_text_field( wp_unslash( $_POST['moove-redirect-remove'] ) );
				ob_start(); ?>
				<div class="moove-redirect-message-cnt moove-success-box">
					<h4 class="moove-success">
						<?php _e( 'REMOVED:', 'moove' ); ?>
						<i><?php echo home_url('/').$remove_item; ?></i></h4>
				</div>
				<!-- moove-redirect-message-cnt -->
				<?php
				$remove_box_message = ob_get_clean();
			endif;
		endif;

		if ( isset( $_POST['moove_404_ks_nonce'] ) ) :
			$nonce = sanitize_key( $_POST['moove_404_ks_nonce'] );
			if ( ! wp_verify_nonce( $nonce, 'moove_404_ks_nonce_field' ) ) :
				die( 'Security check' );
			else :
				if ( isset( $_POST['moove-redirect-activate'] ) ) :
					update_option( 'moove_404_redirect_activate', true );
				else :
					update_option( 'moove_404_redirect_activate', false );
				endif;
			endif;
		endif;
	endif;
?>
<div class="moove-list-redirects-404">
	<h1><?php _e( '404 Redirect Rules', 'moove' ); ?></h1>
	
	<?php echo $remove_box_message; ?>
	<div class="moove-redirect-content-left">
		<?php
			$options = get_option( 'moove_404_redirect_options' );
			Moove_Redirect_Controller::moove_remove_empty_array();

			$stats_hook             = apply_filters( 'moove_404_redirect_stats_enabled', false );
      $general_options        = get_option( 'moove_404_general_options' );
      $general_options        = $general_options && is_array( $general_options ) ? $general_options : array();
      $statistics_status      = isset( $general_options['redirect_statistics'] ) && intval( $general_options['redirect_statistics'] ) >= 0 ? intval( $general_options['redirect_statistics'] ) : ( $stats_hook ? 1 : 0 );			
			$statistics = $statistics_status > 0 ? json_decode( get_option( 'moove_404_redirect_statistics' ), true ) : array();
			if ( ! empty( $options ) && is_array( $options ) ) :
				foreach ( $options as $redirects ) :
				   foreach ( $redirects as $redirect ) :  ?>
						<?php
						if ( '' !== $remove_item && $remove_item === $redirect['base'] ) :
							Moove_Redirect_Controller::moove_redirect_remove_item( $remove_item );
							continue;
						endif;
						?>
						<form action="" class="moove-redirect-single-rule" name="moove-redirect-settings" method="post">
							<?php wp_nonce_field( 'moove_404_rm_nonce_field', 'moove_404_rm_nonce' ); ?>
							<p>
								<strong><?php _e( 'Base url:', 'moove' ); ?></strong>
								<span><?php echo home_url('/'); ?><?php echo $redirect['base']; ?></span>
							</p>
							<p>
								<strong><?php _e( 'Status:', 'moove' ); ?></strong>
								<span><?php echo $redirect['status']; ?></span>
							</p>
							<input type="hidden" value="<?php echo $redirect['base']; ?>" name="moove-redirect-remove"/>
							<button type="submit" class="button moove-redirect-button">
								<?php _e('Remove this rule','moove'); ?>
							</button>
							<?php
							if ( $statistics_status > 0 ) : ?>
								<?php if ( isset( $statistics[ $redirect['base'] ] ) && $statistics[ $redirect['base'] ] ) : ?>
									<button type="button" class="button moove-redirect-button moove-show-stat-btn">
										<?php _e('Show redirect statistics','moove'); ?>
									</button>
									<div class="moove-redirect-stat">
										<table>
											<thead>
												<tr>
													<th>Date/Time</th>
													<th>IP Address</th>
													<th>City</th>
													<th>Target URL</th>
												</tr>
											</thead>
											<tbody>
											<?php
											$i = 0;
											$stats = array_slice( array_reverse( $statistics[ $redirect['base'] ] ) , 0, 10 );
											foreach ( $stats as $statistic ) :
												$i++;
												$alt_class = ( $i % 2 ) ? '' : $alt_class = 'class="alt"';?>
												<tr <?php echo $alt_class ?>>
													<td><?php echo $statistic['date'] ?></td>
													<td><?php echo $statistic['uip'] ?></td>
													<td><?php echo $statistic['city'] ?></td>
													<td><?php echo $statistic['target_url'] ?></td>
												</tr>
											<?php endforeach ?>
											</tbody>
										</table>
										<?php if ( count( $statistics[ $redirect['base'] ] ) > 10 ) : ?>
											<button data-href="<?php echo $page_url; ?>&download_csv=<?php echo $redirect['base']; ?>" class="button moove-redirect-button moove-download-stat-btn">
												<?php _e('Download the full report as csv','moove'); ?>
											</button>
										<?php endif; ?>
									</div>
									<!-- moove-redirect-stat -->
								<?php endif; ?>
							<?php else : 
								update_option( 'moove_404_redirect_statistics', json_encode( array() ) );
							endif; ?>
						</form>
					<?php endforeach;
				endforeach;
				$options = get_option( 'moove_404_redirect_options' );
				if ( empty( $options ) ) : ?>
					<div class="moove-redirect-message-cnt moove-info-box">
						<h4>No redirects rules found! Please add new rule <a href="?page=moove-redirect-settings&tab=moove_redirect_new">here</a></h4>
					</div>
					<!-- moove-redirect-message-cnt -->
				<?php endif;

			else: ?>
				<div class="moove-redirect-message-cnt moove-info-box">
					<h4>No redirects rules found! Please add new rule <a href="?page=moove-redirect-settings&tab=moove_redirect_new">here</a></h4>
				</div>
				<!-- moove-redirect-message-cnt -->
			<?php endif;
		   
		?>
	</div>
	<!-- moove-redirect-content-left -->
	<hr />
	<div class="moove-redirect-keep-settings">
		<form action="" class="moove-redirect-activate-settings moove-redirect-single-rule" name="moove-redirect-settings" method="post">
			<?php
				$checked        = ( get_option( 'moove_404_redirect_activate' ) ) ? 'checked="checked"' : '';
				$checkvalue     = ( get_option( 'moove_404_redirect_activate' ) ) ? 'true' : 'false';
			?>
			<?php wp_nonce_field( 'moove_404_ks_nonce_field', 'moove_404_ks_nonce' ); ?>
			<input type="hidden" id="moove-redirect-activate-val" name="moove-redirect-activate-val" value="<?php echo $checkvalue; ?>">
			<input type="checkbox" name="moove-redirect-activate" id="moove-redirect-activate" value="<?php echo $checkvalue; ?>" <?php echo $checked; ?>>
			<label for="moove-redirect-activate">Keep saved rules after plugin deactivation</label>
			<button type="submit" class="button moove-redirect-button">
				<?php _e('Save settings','moove'); ?>
			</button>
		</form>
	</div>
	<!-- moove-redirect-keep-settings -->
</div>
<!-- moove-list-redirects-404 -->