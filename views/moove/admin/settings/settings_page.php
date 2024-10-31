<div class="wrap moove-redirect-settings-plugin-wrap" id="redirect404-settings-cnt">

	<h1><?php _e('Redirect 404 Plugin Settings','moove'); ?></h1>

    <?php
        $current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( sanitize_text_field( wp_unslash( urlencode( $_GET['tab'] ) ) ) ) : '';
        if ( isset( $current_tab ) &&  $current_tab !== '' ) :
            $active_tab = $current_tab;
        else :
            $active_tab = "moove_redirect";
        endif; // end if

    ?>
    <br />
    <div class="redirect404-tab-section-cnt">
        <h2 class="nav-tab-wrapper">
            <a href="?page=moove-redirect-settings&tab=moove_redirect" class="nav-tab <?php echo $active_tab == 'moove_redirect' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Redirect Rules','moove'); ?>
            </a>

            <a href="?page=moove-redirect-settings&tab=moove_redirect_new" class="nav-tab <?php echo $active_tab == 'moove_redirect_new' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Add New Redirect Rule','moove'); ?>
            </a>

            <a href="?page=moove-redirect-settings&tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>">
                <?php _e('General Settings','moove'); ?>
            </a>

            <a href="?page=moove-redirect-settings&tab=plugin_documentation" class="nav-tab <?php echo $active_tab == 'plugin_documentation' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Documentation','moove'); ?>
            </a>
        </h2>
        <div class="moove-form-container <?php echo $active_tab; ?>">
            <?php
            if( $active_tab == 'moove_redirect' ) : ?>
                <?php echo Moove_Redirect_View::load( 'moove.admin.settings.redirects' , true ); ?>
            <?php elseif( $active_tab == 'moove_redirect_new' ): ?>
                <?php echo Moove_Redirect_View::load( 'moove.admin.settings.add_new_redirect' , true ); ?>
            <?php elseif( $active_tab == 'plugin_documentation' ): ?>
                <?php echo Moove_Redirect_View::load( 'moove.admin.settings.documentation' , true ); ?>
            <?php elseif( $active_tab == 'general_settings' ): ?>
                <?php echo Moove_Redirect_View::load( 'moove.admin.settings.general_settings' , true ); ?>
            <?php endif; ?>
        </div>
        <!-- moove-form-container -->
    </div>
    <!--  .redirect404-tab-section-cnt -->
    <?php 
        $view_cnt = new Moove_Redirect_View();
        echo $view_cnt->load( 'moove.admin.settings.plugin_boxes', array() );
    ?>
</div>
<!-- wrap -->