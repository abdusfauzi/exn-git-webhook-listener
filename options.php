<div class="wrap">
    <h1>GitLab update listener setup</h1>
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
                        echo '<input type="hidden" name="egwl-webhook-token" value="' . bin2hex( random_bytes(32) ) . '">';
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
                <th>Shell Script</th>
                <td><textarea name="egwl-webhook-shell-script" class="widefat" rows="10"><?php echo get_option( 'egwl-webhook-shell-script' ); ?></textarea></td>
            </tr>
        </table>

        <a href="#" id="egwl_help_link" onClick="showHelp()">How do I use this?</a>
        <div id="egwl_help" class="card" style="display:none;">
            <h3>Connecting with GitHub</h3>
            <ol>
                <li>Go to your project settings on GitHub</li>
                <li>Select Webhooks -> Add webhook</li>
                <li>Copy payload URL from here to GitHub</li>
                <li>Select "application/json" as content type</li>
                <li>Create a passcode (a random string) and copy it to "Secret" field on both here and GitHub</li>
                <li>Choose "Let me select individual events" as triggers</li>
                <li>Tick "Release" and untick everything else</li>
                <li>Save your plugin settings</li>
                <li>Click "Add webhook" on GitHub</li>
            </ol>
            <p>
                GitLab sends a ping to your payload URL on webhook activation.
                If the activation was successful it should return status 200 and <code>{"success":true,"release_published":false}</code>.
                Please note that nothing will be published on your site before an actual release is made on GitHub.
            </p>
            <a href="#" onClick="closeHelp()">Close</a>
        </div>
        <?php submit_button(); ?>
    </form>
</div>

<script>
    var helpLink = jQuery('#egwl_help_link');
    var help = jQuery('#egwl_help');

    function showHelp() {
        help.show();
        helpLink.hide();
    }

    function closeHelp() {
        help.hide();
        helpLink.show();
    }
</script>
