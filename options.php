<div class="wrap">
    <h1>Git Webhook Listener Setup</h1>
    <form method="post" action="options.php" style="text-align: left;">
        <?php settings_fields('egwl-options'); ?>
        <?php do_settings_sections( 'egwl-options' ); ?>
        
        <table class="form-table">
            <tr>
                <th>Secret Token</th>
                <td>
                    <?php
                    $token = get_option( 'egwl-webhook-token' );
                    if ( false === $token || empty( $token ) ) {
                        echo '<em>Save to generate Token key</em>';
                        echo '<input type="hidden" name="egwl-webhook-token" value="' . bin2hex( random_bytes(32) ) . '" class="widefat">';
                    } else {
                        // echo $token;
                        echo '<input type="text" name="egwl-webhook-token" value="' . $token . '" class="widefat">';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th>Webhook URL</th>
                <td><code><?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>?action=egwl_release_post</code></td>
            </tr>
            <tr>
                <th>Project Root (Absolute path)</th>
                <td>
                    <?php
                    $path = get_option( 'egwl-absolute-path' );
                    if ( false === $path || empty( $path ) ) {
                        echo '<input type="hidden" name="egwl-absolute-path" value="' . ABSPATH . '" class="widefat">';
                    } else {
                        // echo $token;
                        echo '<input type="text" name="egwl-absolute-path" value="' . $path . '" class="widefat">';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th>Shell Script</th>
                <td><textarea name="egwl-webhook-shell-script" class="widefat" rows="10"><?php echo get_option( 'egwl-webhook-shell-script' ); ?></textarea></td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>
</div>
