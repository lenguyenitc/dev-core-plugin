<?php
$border_show = (get_theme_mod('border_around_auth_form') !== false) ? get_theme_mod('border_around_auth_form') : 'yes';
if($border_show == 'yes') {
    $border_color = (get_theme_mod('form_border_color') !== false) ? get_theme_mod('form_border_color') : '#172030';
} else {
	$border_color = 'transparent';
}
?>

<style>
div.privacy-policy-page-link a.privacy-policy-link {
    display: none !important;
}
/*back*/
.page-container {
	width:100%;
	height:100%;
	background-color: <?php if(get_theme_mod('back_color') !== false) echo get_theme_mod('back_color'); else echo '#000000';?>;
	position:fixed;
}
.page-back {
	width:100%;
	height:100%;
	left:0;
	top:0;
	position: absolute;
	background-image: url(<?php if(get_theme_mod('back_file') !== false) echo wp_get_attachment_image_url(get_theme_mod('back_file'), 'full'); else echo wp_get_attachment_image_url(get_option('auth_bg'), 'full')?>);
	background-size:cover;
	background-position: center;
/*	filter:blur(1px);
	-webkit-filter:blur(1px);*/
} /*end back*/

#login{
    max-width: 356px;
    width: 100%;
	position: absolute;
	top: 0;
	left: calc(50% - 180px);
    margin-left: 2px;
}
#login h1{
	display: none;
}
/*links*/
#login #backtoblog a, #login #nav a,
    p.agreePolicy a{
	color: <?php if(get_theme_mod('links_color') !== false) echo get_theme_mod('links_color'); else echo '#ffffff'?>;
	font-size: 15px;
}
p.agreePolicy a {
    font-size: 13px !important;
}
p.agreePolicy label {
    font-size: 15px;
}
#login #backtoblog a:hover, #login #nav a:hover{
    color: <?php if(get_theme_mod('links_hover_color') !== false) echo get_theme_mod('links_hover_color'); else echo '#C32CE2'?>!important;
}
#backtoblog{
	color: <?php if(get_theme_mod('links_color') !== false) echo get_theme_mod('links_color'); else echo '#ffffff'?>;
}
#backtoblog:hover{
    color: <?php if(get_theme_mod('links_hover_color') !== false) echo get_theme_mod('links_hover_color'); else echo '#C32CE2'?>;
}
<?php
$tos_color = (get_theme_mod('tos_link_color') !== false) ? get_theme_mod('tos_link_color'): '#ffffff';
$tos_on_hover = (get_theme_mod('tos_link_color_on_hover') !== false) ? get_theme_mod('tos_link_color_on_hover'): '#ffffff';
$tos_underline = (get_theme_mod('underline_tos') !== false) ? 'underline' : 'none';
?>
a.tos {
    color: <?php echo $tos_color?> !important;
    text-decoration: <?php echo $tos_underline?> !important;
}
a.tos:hover {
    color: <?php echo $tos_on_hover?> !important;
}
/*end links*/

/*form*/
#login form {
    padding: 40px !important;
    /*background: <?=(get_theme_mod('form_back_color') !== false) ? get_theme_mod('form_back_color') : '#172030'?> !important;*/
    box-shadow: 0px 2px 10px rgb(0 0 0 / 25%);
    border-radius: 4px;
	border: 1px solid <?php echo $border_color?>;
	background-color: rgba(<?php
            $hex = get_theme_mod('form_back_color');
            list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
            echo $r.",".$g.",". $b;
            ?>, <?php if(get_theme_mod('form_back_color') !== false) echo get_theme_mod('form_back_opacity').'%'; else echo '63%';?>);
	color: <?php if(get_theme_mod('form_text_color') !== false) echo get_theme_mod('form_text_color'); else echo '#ffffff'?>;
}
.wp-core-ui .button-primary{
    background-color: <?php if(get_theme_mod('form_button_color') !== false) echo get_theme_mod('form_button_color'); else echo '#C32CE2'?> !important;
    border-color: <?php if(get_theme_mod('form_button_border_color') !== false) echo get_theme_mod('form_button_border_color'); else echo '#C32CE2'?>;
    width: 100%;
    margin-top: 10px;
    color: <?php if(get_theme_mod('form_button_text_color') !== false) echo get_theme_mod('form_button_text_color'); else echo '#ffffff'?>;
    border-radius: 4px;
    font-family: 'Roboto',sans-serif;
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 142.69% !important;
    max-width: 328px;
    padding: 8px!important;
}
label[for=rememberme] {
    font-family: 'Roboto',sans-serif;
    font-style: normal;
    font-weight: normal;
    font-size: 14px;
    line-height: 16px;
    color: rgba(<?php
        $hex = get_theme_mod('form_text_color') ? get_theme_mod('form_text_color') : '#ffffff';
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        echo $r.",".$g.",". $b;
        ?>,0.5)!important;
}

.wp-core-ui .button-primary:hover{
    background-color: <?php if(get_theme_mod('form_button_hover_color') !== false) echo get_theme_mod('form_button_hover_color'); else echo '#C32CE2'?> !important;
    border-color: <?php if(get_theme_mod('form_button_border_color') !== false) echo get_theme_mod('form_button_border_color'); else echo '#C32CE2'?>;
}

button.wp-generate-pw {
    display:none !important;
}
.input:focus,
.wp-core-ui .button-primary:focus {
    outline: none!important;
    box-shadow: 0 0 0 1px transparent!important;
    border-color: transparent!important;
}
.wp-core-ui .button-primary:focus {
    background-color: <?php if(get_theme_mod('form_button_hover_color') !== false) echo get_theme_mod('form_button_hover_color'); else echo '#C32CE2'?> !important;
}
/*end form*/

/*info msg*/
#login .login_error, #login .message, #login .success {
    padding: 12px;
    margin-left: 0;
    margin-bottom: 20px;
}
#login .login_error, #login .message, #login .success{
	border-left: 4px solid <?php if(get_theme_mod('info_border_color') !== false) echo get_theme_mod('info_border_color'); else echo '#C32CE2'?>;
    background-color: <?php if(get_theme_mod('info_back_color') !== false) echo get_theme_mod('info_back_color'); else echo '#172030'?>;
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    color: <?php if(get_theme_mod('info_text_color') !== false) echo get_theme_mod('info_text_color'); else echo '#ffffff'?>;
}
.form-block-rcl .g-recaptcha{
    margin-bottom: 30px;
}
.form-block-rcl .g-recaptcha > div{
    box-sizing: border-box;
    max-width: 100%;
    width: 294px !important;
}
.form-block-rcl .g-recaptcha iframe{
    width: 100%;
}
.login label {
    font-size: 14px!important;
    line-height: 1.5!important;
    display: inline-block!important;
    margin-bottom: 3px!important;
    font-family: 'Roboto',sans-serif!important;
}
input#user_login,
input#user_pass,
input#user_email{
    background-color: <?=get_theme_mod('input_color')?> !important;
    border-radius: 4px !important;
    padding: 10px 20px !important;
    border: none !important;
    box-shadow: none !important;
    font-family: 'Roboto',sans-serif;
    font-style: normal;
    font-weight: normal;
    font-size: 14px;
    line-height: 16px;
    color: <?=get_theme_mod('form_text_color') ? get_theme_mod('form_text_color') : '#ffffff';?>
}
#login .button.wp-hide-pw {
    color: <?php if(get_theme_mod('form_button_color') !== false) echo get_theme_mod('form_button_color'); else echo '#C32CE2'?>!important;
}

#loginform div.nsl-container-buttons,
#registerform div.nsl-container-buttons {
    width: 100% !important;
}
#loginform div.nsl-container-buttons a,
#registerform div.nsl-container-buttons a{
    width: 100% !important;
    border-radius: 4px !important;
    max-width: 100% !important;
}
#loginform div.nsl-container-buttons a > div,
#registerform div.nsl-container-buttons a > div{
    border-radius: 4px !important;
}
#loginform div.nsl-container-buttons a:nth-child(1) > div  div.nsl-button-label-container,
#registerform div.nsl-container-buttons a:nth-child(1) > div  div.nsl-button-label-container{
    color: <?=get_theme_mod('secondary_color_setting')?>!important;
}
p#nav,
p#backtoblog,
p#nav2,
p#backtoblog2{
    text-align: <?php if(get_theme_mod('links_text_position') !== false) { echo get_theme_mod('links_text_position');} else echo 'left';?>;
    font-family: 'Roboto', sans-serif;
    font-style: normal;
    font-weight: normal;
    font-size: 14px;
    line-height: 16px;
    color: rgba(<?php
        $hex = get_theme_mod('form_text_color') ? get_theme_mod('form_text_color') : '#ffffff';
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        echo $r.",".$g.",". $b;
        ?>,0.5)!important;
}
p#nav2,
p#backtoblog2 {
    margin: 24px 0 0 0;
    font-size: 13px;
    padding: 0 24px 0;
}
p#nav > a:nth-child(1) {
    margin-right: 3px;
}
p#nav > a:nth-child(2) {
    margin-left: 3px;
}
/*#pw-weak*/

#loginform p.submit {
    margin-bottom: 20px !important;
    padding-bottom: 20px !important;
    border-bottom: 1px solid <?=get_theme_mod('secondary_color_setting')?> !important;
    display: flex;
    clear: both;
}
#loginform div.nsl-container.nsl-container-block.nsl-container-login-layout-below,
#loginform div.nsl-container.nsl-container-block.nsl-container-login-layout-below div.nsl-container-buttons{
    padding-top: 0!important;
}
#loginform p.forgetmenot label.ui-checkboxradio-label.ui-corner-all.ui-button.ui-widget{
    background-image: none !important;
    box-shadow: none !important;
    background-color: transparent !important;
    border: none !important;
    padding-left: 0 !important;
}
#loginform p.forgetmenot label.ui-checkboxradio-checked.ui-state-active {
    background-color: <?=get_theme_mod('btn_color_setting')?> !important;
    color: rgba(<?php
            $hex = get_theme_mod('form_text_color');
            list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
            echo $r.",".$g.",". $b;
            ?>, 1)!important;
}
#loginform p.forgetmenot label.ui-checkboxradio-label.ui-corner-all.ui-button.ui-widget span.ui-icon {
    background-image: none !important;
    box-shadow: none !important;
    background-color: transparent !important;
    border: 1px solid rgba(<?php
            $hex = get_theme_mod('form_text_color');
            list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
            echo $r.",".$g.",". $b;
            ?>, 0.5)!important;
    border-radius: 4px !important;
    margin-right: 10px !important;
}

#loginform p.forgetmenot label.ui-checkboxradio-label.ui-corner-all.ui-button.ui-widget:hover {
    color: rgba(<?php
            $hex = get_theme_mod('form_text_color');
            list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
            echo $r.",".$g.",". $b;
            ?>, 1)!important;
}
#loginform p.forgetmenot label.ui-checkboxradio-label.ui-corner-all.ui-button.ui-widget:hover span.ui-icon {
    border: 1px solid rgba(<?php
            $hex = get_theme_mod('form_text_color');
            list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
            echo $r.",".$g.",". $b;
            ?>, 1)!important;
}
#loginform p.forgetmenot label.ui-checkboxradio-label.ui-corner-all.ui-button.ui-widget.ui-checkboxradio-checked.ui-state-active span.ui-icon.ui-state-checked {
     border: 1px solid rgba(<?php
            $hex = get_theme_mod('form_text_color');
            list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
            echo $r.",".$g.",". $b;
            ?>, 1)!important;
     background-image: none !important;
     background-color: <?=get_theme_mod('btn_hover_color_setting')?> !important;
 }

#wp-submit:hover {
    background-color: <?php if(get_theme_mod('form_button_hover_color') !== false) echo get_theme_mod('form_button_hover_color'); else echo '#C32CE2'?>;
    border: 1px solid <?php if(get_theme_mod('form_button_hover_color') !== false) echo get_theme_mod('form_button_hover_color'); else echo '#C32CE2'?>;
}

<?php if ( 'gradient' === xbox_get_field_value( 'my-theme-options', 'rendering')) : ?>
div.nsl-container-block .nsl-container-buttons a > div,
input#user_login,
input#user_pass,
input#user_email,
.wp-core-ui .button-primary{
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a62b2b2b', endColorstr='#00000000',GradientType=0 )!important;
    -moz-box-shadow: 0 1px 6px 0 rgba(0, 0, 0, 0.12)!important;
    -webkit-box-shadow: 0 1px 6px 0 rgb(0 0 0 / 12%)!important;
    -o-box-shadow: 0 1px 6px 0 rgba(0, 0, 0, 0.12)!important;
    box-shadow: 0 1px 6px 0 rgb(0 0 0 / 12%)!important;
    background: -moz-linear-gradient(top, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0) 70%);
    background: -webkit-linear-gradient(top, rgba(0,0,0,0.3) 0%,rgba(0,0,0,0) 70%);
    background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%,rgba(0,0,0,0) 70%);
}
<?php endif;?>

<?php if(get_theme_mod('enable_demos_color_scheme') == 'demos'):?>

<?php //fetish
if('filf' == xbox_get_field_value( 'my-theme-options', 'choose-niche')):?>

<?php endif;?>

<?php //pornx default
if('trans' == xbox_get_field_value( 'my-theme-options', 'choose-niche')):?>

<?php endif;?>

<?php //light
if('light' == xbox_get_field_value( 'my-theme-options', 'choose-niche')):?>

<?php endif;?>

<?php //milf
if('milf' == xbox_get_field_value( 'my-theme-options', 'choose-niche' )):?>

<?php endif;?>

<?php //gay
if('livexcams' == xbox_get_field_value( 'my-theme-options', 'choose-niche' )):?>

<?php endif;?>

<?php //hentai
if('hentai' == xbox_get_field_value( 'my-theme-options', 'choose-niche' )):?>

<?php endif;?>

<?php //teen
if('college' == xbox_get_field_value( 'my-theme-options', 'choose-niche' )):?>

<?php endif;?>

<?php //trans
if('transs' == xbox_get_field_value( 'my-theme-options', 'choose-niche' )):?>

<?php endif;?>

<?php //lesbian
if('lesbian' == xbox_get_field_value( 'my-theme-options', 'choose-niche' )):?>

<?php endif;?>

<?php endif;?>

</style>