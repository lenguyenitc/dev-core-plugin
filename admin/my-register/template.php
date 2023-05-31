<?php require_once 'assets/styles.php'; ?>
<div class="page-container">
    <div class="page-back">
    </div>
</div>
<style>
    <?php if ( $GLOBALS['pagenow'] === 'wp-login.php' && $_REQUEST['action'] === 'register') :?>
    #login {
        display: none;
    }
    <?php endif;?>
</style>
<?php
add_action('login_footer', function() {
if ( $GLOBALS['pagenow'] === 'wp-login.php' && @$_REQUEST['action'] === 'register'): ?>
    <script>
        jQuery(document).ready(function ($){
            $("#registerform #user_login").attr("placeholder", "Username");
        });
    </script>
    <?php
endif;});?>
