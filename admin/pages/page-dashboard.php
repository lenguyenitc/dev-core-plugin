<?php
function arc_dashboard_page(){
    if($_GET['tab'] == 'reports'):?>
        <script>
            jQuery(document).ready(function($){
                $('#dashboard').removeClass('active');
                $('#dashboard-tab').removeClass('active');
                $('#reports').addClass('active').removeClass('fade');
                $('#reports-tab').addClass('active');
            });
        </script>
    <?php
    elseif($_GET['tab'] == 'support'):?>
        <script>
            jQuery(document).ready(function($){
                $('#dashboard').removeClass('active');
                $('#dashboard-tab').removeClass('active');
                $('#support').addClass('active').removeClass('fade');
                $('#support-tab').addClass('active');
            });
        </script>
    <?php endif;?>
    <div id="vicetemple-dashboard">
    <ul class="nav nav-tabs mt-lg-5" id="arc-dashboard-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="dashboard-tab" data-toggle="tab" href="#dashboard" role="tab" aria-controls="dashboard" aria-selected="true">
                <i class="fas fa-tachometer-alt"></i> <?php echo __('Dashboard', 'arc');?></a>
        </li>
        <!---page tabs---->
	    <?php
        if(get_option('_current_site_user_license') !== false) get_template_part('template-parts/page', 'tabs');
        ?>
    </ul>
    <!---inside tabs section----->
    <div class="tab-content mt-lg-5" id="arc-dashboard">
        <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
            <div class="container-fluid">
                <div class="row mb-lg-3">
                    <div class="col-12">
                        <div id="name_and_version" style="float: right; font-size: 20px; font-style: italic;"></div>
                    </div>
                    <button class="btn btn-info updatePlugin" style="display: none; position: absolute; right: 0"></button>
                </div>
                <!---licence key---->
                <div class="row justify-content-center">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-6" style="margin: 0 auto; text-align: center">
                        <div class="input-group">
                            <?php foreach(maybe_unserialize(VICETEMPLECORE()->get_license_key()) as $value) {
	                            if(in_array(@parse_url(get_site_url(), PHP_URL_HOST), @$value) !== false) {
                                    $flag = true;
	                            }
                            }?>
                            <?php if(VICETEMPLECORE()->get_license_key() !== false && $flag == true) :?>
                            <input class="form-control text-center disabled" type="text" id="input-license" disabled="disabled" placeholder="<?php echo maybe_unserialize(VICETEMPLECORE()->get_license_key())['license']; ?>" />
                            <button id="license-btn" class="btn btn-success disabled" type="button" disabled="disabled"><i class="fas fa-check"></i></button>
                            <?php else:?>
                                <input class="form-control text-center" type="text" id="input-license" placeholder="<?php echo __('Enter your license key', 'arc'); ?>" />
                                <button id="license-btn" class="btn btn-primary" type="button"><i class="fas fa-key"></i> <?php echo __('Activate', 'arc'); ?></button>
                            <?php endif;?>
                        </div>
                        <p id="license_error" style="padding: 10px"> </p>
                    </div>
                    <hr>
                </div>
                <!---end licence key---->
                <br>
                <!--inside-dashboard--->
	            <?php if(VICETEMPLECORE()->get_license_key() !== false && $flag == true) get_template_part('template-parts/inside', 'dashboard');?>
            </div>
        </div>
        <!-----tabs-inside---->
	    <?php if(VICETEMPLECORE()->get_license_key() !== false && $flag == true) get_template_part('template-parts/tabs', 'inside');?>
    </div>
    <div class="container-fluid mt-lg-5" id="arc-dashboard-footer">
    </div>
        <script>
            jQuery(document).ready(function($) {
                $(document).on('click', 'a.changelog_btn', function(e){
                    $('#changelogModal div.modal-body').text('');
                    e.preventDefault();
                    let data_log = $(this).attr('data-log');
                    $.ajax({
                        type: "post",
                        url: arc_dashboard.url,
                        data: {
                            action: 'show_changelog_info',
                            nonce: arc_dashboard.nonce,
                            file_for: data_log
                        },
                        success: function (res) {
                            if(res === false) {
                                $('#changelogModal').show();
                                $('#changelogModal div.modal-body').append('<p>Information temporary unavailable</p>');
                            } else {
                                $('#changelogModal').show();
                                $('#changelogModal div.modal-body').append(res);
                            }

                        }
                    });
                });

                $('#changelogModal button.close').on('click', function (){
                    $('#changelogModal').hide();
                    $('#changelogModal div.modal-body').text('');
                });
            });
        </script>
        <!-- Modal -->
        <div class="modal" id="changelogModal" tabindex="-1" role="dialog" aria-labelledby="changelogModal" aria-hidden="true" style="z-index: 99999;">
            <div class="modal-dialog" role="document" style="max-width: 700px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changelogModalLabel">Changelog</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="overflow: auto;height: 300px;">
                        <style>
                            #changelogModal h2 {
                                font-weight: bold;
                                border-top: 2px solid black;
                                padding-bottom: 10px
                            }
                            #changelogModal ul li {
                                list-style: circle;
                            }
                        </style>
                    </div>
                    <div class="modal-footer">
                        <button class="button button-default"></button>
                        <style>
                            #changelogModal div.modal-footer button.button.button-default {
                                color: #a7aaad!important;
                                border-color: #dcdcde!important;
                                background: #f6f7f7!important;
                                box-shadow: none!important;
                                cursor: default!important;
                                transform: none!important;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}

/**convert videos page**/
function arc_convert_videos($name, $value = '') {
    ?>
        <style>
            div#container-convert {
                margin: 0 auto;
                width: 90%;
                margin-top: 40px;
            }
            #container-convert h4.header {
                line-height: 24px;
                padding: 30px;
                font-weight: 600;
                text-decoration: none;
                text-transform: uppercase;
                font-family: "Open Sans", Verdana, Geneva, sans-serif;
                margin-bottom: 0;
                color: #fff !important;
                background: #0095FF;
                text-align: center;
            }
            #file_info label {
                font-size: 13px;
                color: #5e6672;
                display: inline-block;
                line-height: 1.4;
                font-weight: 600;
                font-family: "Open Sans", "Helvetica Neue", "Helvetica", Verdana, Geneva, sans-serif;
                margin-bottom: 0;
            }
            #file_info label ~ span {
                margin-top: 2px;
                line-height: 1.5;
                color: #798392;
                font-size: 12px;
                font-style: italic;
                font-family: "Open Sans", Verdana, Geneva, sans-serif;
                margin-bottom: 20px;
            }
            #uploadedBy {
                color: #007bff !important;
            }
            #file_info label[for=fileName],
            span#fileName strong{
                font-size: 16px !important;
                font-style: normal !important;
            }
            span#fileName strong{
                color: black !important;
            }
            button.upload_image_button {
                background-color: #818181 !important;
                color: #fff !important;
                border-color: #818181 !important;
            }
        </style>
    <div class="container-fluid block-white block-white-first" id="container-convert" style="padding: 0">
        <h4 class="header"><?php echo __('Convert Videos', 'arc');?></h4>
        <form method="post" enctype="multipart/form-data" id="convert-form">
        <?php wp_nonce_field( 'convert_file', 'fileup_nonce' );?>
            <div class="form-group" style="text-align: center; margin-bottom:0">
                <div class="row" style="margin-top: 20px;display: none" id="file_info">
                    <div class="col-3">
                        <p id="fileThumb">
                            <video src="" style="width: 440px; height: 160px">
                                <source type="" src="">
                            </video>
                        </p>
                    </div>
                    <div class="col-9" style="padding-top: 15px;text-align: left;">
                        <div>
                            <label for="fileName"></label>
                            <span id="fileName" value="<?php echo $value; ?>">
                                <strong><?php echo $name; ?></strong>
                            </span>
                        </div>
                        <div>
                            <label for="uploadDate"></label>
                            <span id="uploadDate" value="<?php echo $value; ?>">
		                        <?php echo $name; ?>
                            </span>
                        </div>
                        <div>
                            <label for="uploadedBy"></label>
                            <span id="uploadedBy" value="<?php echo $value; ?>">
                                <?php echo $name; ?>
                            </span>
                        </div>
                        <div>
                            <label for="fileType"></label>
                            <span id="fileType" value="<?php echo $value; ?>">
                                <?php echo $name; ?>
                            </span>
                        </div>
                        <div><label for="fileSize"></label>
                            <span id="fileSize" value="<?php echo $value; ?>">
                                <?php echo $name; ?>
                            </span>
                        </div>
                        <div>
                            <label for="fileLength"></label>
                            <span id="fileLength" value="<?php echo $value; ?>">
                                <?php echo $name; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <button type="submit" class="btn btn-outline-primary upload_image_button"><?php echo __('Choose video file', 'arc'); ?></button>
                <br>
                <hr>
                <div style="display: inline-flex; width: 100%;    justify-content: center;">
                    <label for="selectResolution" style="margin-right: 10px; margin-bottom: 0; margin-top: 5px"><?php echo __('Select resolution', 'arc');?></label>
                    <select id="selectResolution" class="form-control" style="max-width: 175px;padding-top: 6px;padding-bottom: 6px;height: 38px;">
                        <option value="0"><?php echo __('Available resolutions');?></option>
                    </select>
                    <button class="btn btn-primary" id="convert_video" style="margin-left: 15px; margin-bottom: 20px;">
                        <i class="fas fa-crop-alt"></i> <?php echo __('Convert video file', 'arc');?>
                    </button>
                </div>

            </div>
        </form>
        <div style="width: 100%;text-align: center;margin-bottom: 0px">
            <p class="alert alert-info" id="infos" style="margin: 0 auto;margin-bottom: 10px"></p>
            <p class="alert alert-danger" id="allerts" style="margin: 0 auto;margin-bottom: 10px"></p>
            <p class="alert alert-success" id="success_msg" style="margin: 0 auto;margin-bottom: 10px"></p>
        </div>
    </div>
    <?php
}
/** [end] convert videos page**/

/**email settings page**/
function arc_email_settings() { ?>
    <style>
            div#email_container {
                width: 98%;
                margin-top: 40px;
                margin-left: 0;
                background: #fbfbfb;
                padding: 15px 15px;
                border: 1px solid #ddd;
                border-top: 1px solid #ddd;
                border-radius: 5px 5px 0 0;
            }
            div#email_container h1 {
                font-size: 2rem;
            }
            .tab input.accordion_input, .tab-content {
                display: none;
            }
            .tab {
                font: 0.8rem/1.2 Arial, sans-serif;
                font-size: 16px;
                border: 1px solid #e9eaec;
                border-radius: 3px;
                color: black;
                margin-bottom: 10px;
                letter-spacing: 1px;
            }
            .tab-title {
                padding: 10px;
                display: block;
                font-weight: bold;
                cursor: pointer;
            }
            .tab-title::after {
                content: '+';
                float: right;
            }
            .tab-content {
                padding: 10px 20px;
            }
            .tab :checked + .tab-title {
                background-color: #0463a8;
                border-radius: 3px 3px 0 0;
                color: #fff;
            }
            .tab :checked + .tab-title::after {
                content: '−';
            }
            .tab :checked ~ .tab-content {
                display: block;
            }
            #shortcode_list li {
                font-size: 16px;
            }

            ul#shortcode_list {
                /*top: 105px;*/
                position: absolute;
                background: white;
                border: 1px solid grey;
                padding: 10px;
                right: 17px;
                z-index: 99;
                display: none;
                clear: both;
            }
            ul#shortcode_list * {
                display: none;
            }
            ul#shortcode_list button {
                display: block;
            }
            div#setting-patch-settings_updated {
                display: none;
            }
            @media (min-width: 320px) and (max-width: 445px) {
                #email_container h1 {
                    justify-content: center !important;
                }
                #email_container h1 div:nth-child(1) {
                    text-align: center !important;
                }
                #email_container h1 div:nth-child(2) {
                    margin-top: 10px !important;
                }
                #select_type_templates {
                    margin-top: 20px !important;
                }
            }
            @media (min-width: 446px) and (max-width: 593px) {
                #email_container h1 {
                    justify-content: center !important;
                }
                #email_container h1 div:nth-child(2) {
                    margin-top: 10px !important;
                }

            }
        </style>
    <div id="email_container" style="padding-right: 20px;">
        <h1 style="width: 98%;display: inline-flex; flex-wrap: wrap; justify-content: space-between;">
            <div>
	            <?php echo __('Email Settings', 'arc');?>
                <select id="select_type_templates">
                    <option value="0"><?= __('All email templates', 'arc');?></option>
                    <option value="1"><?= __('User email templates', 'arc');?></option>
                    <option value="2"><?= __('Admin email templates', 'arc');?></option>
                </select>
            </div>
            <div>
                <button style="float:right" class="button button-primary hide_shortcodes">
                    <i class="fa fa-close"></i>
                    <?php echo __('Show shortcodes', 'arc');?>
                </button>
            </div>
        </h1>
        <script>
            jQuery(document).ready(function ($) {
                var flag = false;
                $('.hide_shortcodes').on('click', function () {
                    if(false === flag) {
                        $('ul#shortcode_list').css('display', 'block');
                        $('ul#shortcode_list *').css('display', 'block');
                        $(this).text('Hide shortcodes');
                        flag = true;
                    } else {
                        $('ul#shortcode_list').css('display', 'none');
                        $('ul#shortcode_list *').css('display', 'none');
                        $(this).css('display', 'block').text('Show shortcodes');
                        flag = false;
                    }
                });
                $('#select_type_templates').on('change', function (){
                   var option = $(this).val();
                   if(option == 1) {
                       $('#admin_templates').css('display', 'none');
                       $('#users_templates').css('display', 'block');
                   }
                   else if(option == 2) {
                       $('#users_templates').css('display', 'none');
                       $('#admin_templates').css('display', 'block');
                   }
                   else {
                       $('#admin_templates').css('display', 'block');
                       $('#users_templates').css('display', 'block');
                   }
                });
            });
        </script>
        <ul id="shortcode_list">
            <h2 style="font-size: 16px">
                <strong>
					<?php echo __('Copy and paste available shortcodes into the email templates', 'arc');?>
                </strong>
            </h2>
            <hr>
            <h2><?php echo __('General shortcodes', 'arc');?></h2>
            <li>[site_name]</li>
            <li>[site_link anchor_text="Visit "][site_name][/site_link] or [site_link] or [site_link anchor_text="Visit PornX"]</li>
            <li>[admin_email]</li>
            <li>[admin_name]</li>
            <li>[login_page anchor_text="Login"]</li>
            <li>[community_feed anchor_text="Community Feed"]</li>
            <li>[contact_page anchor_text="Contact us"]</li>
            <li>[members anchor_text="Members"]</li>
            <li>[support_email anchor_text="Email support"]</li>
            <li>[password_link anchor_text="Set password"]</li>
            <li>[delete_account anchor_text="Delete account"]</li>
            <li>[delete_video anchor_text="Delete video"]</li>
            <li>[delete_album anchor_text="Delete album"]</li>
            <li>[watch_video anchor_text="Watch video"]</li>
            <h2><?php echo __('User information', 'arc');?></h2>
            <li>[user_firstname]</li>
            <li>[user_email]</li>
            <li>[user_login]</li>
            <li>[user_uploads anchor_text="My Uploads"]</li>
            <h2><?php echo __('Administrator-only shortcodes', 'arc');?></h2>
            <li>[new_video anchor_text="New video"]</li>
            <li>[pending_videos anchor_text="Pending videos"]</li>
            <li>[new_album anchor_text="New album"]</li>
            <li>[pending_albums anchor_text="Pending albums"]</li>
            <li>[new_post anchor_text="New post"]</li>
            <li>[pending_posts anchor_text="Pending posts"]</li>
            <li>[view_comment anchor_text="View comment"]</li>
            <li>[reported_comment anchor_text="Reported comment"]</li>
            <li>[support_messages anchor_text="Support messages"]</li>
            <li>[reports anchor_text="Reports"]</li>
        </ul>
        <br>
        <div class="accordion" style="padding-right: 20px;">
            <form action="admin.php?page=email-settings" method="post">
				<?php
				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					/***admin reg***/
					update_option('sendAdminReg', $_POST['sendAdminReg'], false);
					if(!empty($_POST['titleAdminReg'])) {
						update_option('titleAdminReg', stripslashes($_POST['titleAdminReg']), false);
					}
					if(!empty($_POST['regAdminText'])) {
						update_option('regAdminText', stripslashes($_POST['regAdminText']), false);
					}
					/***[end] admin reg***/

					/***user reg***/
					update_option('sendUserReg', $_POST['sendUserReg'], false);
					if(!empty($_POST['titleUserReg'])) {
						update_option('titleUserReg', stripslashes($_POST['titleUserReg']), false);
					}
					if(!empty($_POST['regUserText'])) {
						update_option('regUserText', stripslashes($_POST['regUserText']), false);
					}
					/***[end] user reg***/

					/***video deletion user***/
					update_option('sendDeleteUserVideo', $_POST['sendDeleteUserVideo'], false);
					if(!empty($_POST['titleDeleteUserVideo'])) {
						update_option('titleDeleteUserVideo', stripslashes($_POST['titleDeleteUserVideo']), false);
					}
					if(!empty($_POST['DeleteUserVideoText'])) {
						update_option('DeleteUserVideoText', stripslashes($_POST['DeleteUserVideoText']), false);
					}
					/***[end] video deletion user***/

					/***video deletion album***/
					update_option('sendDeleteUserAlbum', $_POST['sendDeleteUserAlbum'], false);
					if(!empty($_POST['titleDeleteUserAlbum'])) {
						update_option('titleDeleteUserAlbum', stripslashes($_POST['titleDeleteUserAlbum']), false);
					}
					if(!empty($_POST['DeleteUserAlbumText'])) {
						update_option('DeleteUserAlbumText', stripslashes($_POST['DeleteUserAlbumText']), false);
					}
					/***[end] video deletion album***/

					/***user lost pass***/
					update_option('sendLostPassUser', $_POST['sendLostPassUser'], false);
					if(!empty($_POST['titleLostPassUser'])) {
						update_option('titleLostPassUser', stripslashes($_POST['titleLostPassUser']), false);
					}
					if(!empty($_POST['lostPassUserText'])) {
						update_option('lostPassUserText', stripslashes($_POST['lostPassUserText']), false);
					}
					/***[end] lost pass***/

					/***user change pass***/
					update_option('sendChangePassUser', $_POST['sendChangePassUser'], false);
					if(!empty($_POST['titleChangePassUser'])) {
						update_option('titleChangePassUser', stripslashes($_POST['titleChangePassUser']), false);
					}
					if(!empty($_POST['changePassUserText'])) {
						update_option('changePassUserText', stripslashes($_POST['changePassUserText']), false);
					}
					/***[end] change pass***/

					/***user change email***/
					update_option('sendChangeEmail', $_POST['sendChangeEmail'], false);
					if(!empty($_POST['titleChangeEmail'])) {
						update_option('titleChangeEmail', stripslashes($_POST['titleChangeEmail']), false);
					}
					if(!empty($_POST['changeEmailText'])) {
						update_option('changeEmailText', stripslashes($_POST['changeEmailText']), false);
					}
					/***[end] change email***/


					/***user confirm delete account***/
					update_option('sendDeleteAccountUser', $_POST['sendDeleteAccountUser'], false);
					if(!empty($_POST['titleDeleteAccountUser'])) {
						update_option('titleDeleteAccountUser', stripslashes($_POST['titleDeleteAccountUser']), false);
					}
					if(!empty($_POST['DeleteAccountUserText'])) {
						update_option('DeleteAccountUserText', stripslashes($_POST['DeleteAccountUserText']), false);
					}
					/***[end] user confirm delete account***/

					/***leave a comment***/
					update_option('sendLeaveComment', $_POST['sendLeaveComment'], false);
					if(!empty($_POST['titleLeaveComment'])) {
						update_option('titleLeaveComment', stripslashes($_POST['titleLeaveComment']), false);
					}
					if(!empty($_POST['leaveCommentText'])) {
						update_option('leaveCommentText', stripslashes($_POST['leaveCommentText']), false);
					}
					/***[end] leave a comment***/

					/***submit a video admin***/
					update_option('sendSubmitVideoAdmin', $_POST['sendSubmitVideoAdmin'], false);
					if(!empty($_POST['titleSubmitVideoAdmin'])) {
						update_option('titleSubmitVideoAdmin', stripslashes($_POST['titleSubmitVideoAdmin']), false);
					}
					if(!empty($_POST['submitVideoAdminText'])) {
						update_option('submitVideoAdminText', stripslashes($_POST['submitVideoAdminText']), false);
					}
					/***[end] submit a video admin***/

					/***submit a video user***/
					update_option('sendSubmitVideoUser', $_POST['sendSubmitVideoUser'], false);
					if(!empty($_POST['titleSubmitVideoUser'])) {
						update_option('titleSubmitVideoUser', stripslashes($_POST['titleSubmitVideoUser']), false);
					}
					if(!empty($_POST['submitVideoUserText'])) {
						update_option('submitVideoUserText', stripslashes($_POST['submitVideoUserText']), false);
					}
					/***[end] submit a video user***/

					/***submit a photos admin***/
					update_option('sendSubmitPhotosAdmin', $_POST['sendSubmitPhotosAdmin'], false);
					if(!empty($_POST['titleSubmitPhotosAdmin'])) {
						update_option('titleSubmitPhotosAdmin', stripslashes($_POST['titleSubmitPhotosAdmin']), false);
					}
					if(!empty($_POST['submitPhotosAdminText'])) {
						update_option('submitPhotosAdminText', stripslashes($_POST['submitPhotosAdminText']), false);
					}
					/***[end] submit a photos admin***/

					/***submit a photos user***/
					update_option('sendSubmitPhotosUser', $_POST['sendSubmitPhotosUser'], false);
					if(!empty($_POST['titleSubmitPhotosUser'])) {
						update_option('titleSubmitPhotosUser', stripslashes($_POST['titleSubmitPhotosUser']), false);
					}
					if(!empty($_POST['submitPhotosUserText'])) {
						update_option('submitPhotosUserText', stripslashes($_POST['submitPhotosUserText']), false);
					}
					/***[end] submit a photos user***/


					/***submit a post admin***/
					update_option('sendSubmitPostAdmin', $_POST['sendSubmitPostAdmin'], false);
					if(!empty($_POST['titleSubmitPostAdmin'])) {
						update_option('titleSubmitPostAdmin', stripslashes($_POST['titleSubmitPostAdmin']), false);
					}
					if(!empty($_POST['submitPostAdminText'])) {
						update_option('submitPostAdminText', stripslashes($_POST['submitPostAdminText']), false);
					}
					/***[end] submit a post admin***/

					/***submit a post user***/
					update_option('sendSubmitPostUser', $_POST['sendSubmitPostUser'], false);
					if(!empty($_POST['titleSubmitPostUser'])) {
						update_option('titleSubmitPostUser', stripslashes($_POST['titleSubmitPostUser']), false);
					}
					if(!empty($_POST['submitPostUserText'])) {
						update_option('submitPostUserText', stripslashes($_POST['submitPostUserText']), false);
					}
					/***[end] submit a post user***/

					/***subscriber message***/
					update_option('sendSubscriptionUser', $_POST['sendSubscriptionUser'], false);
					if(!empty($_POST['titleSubscriptionUser'])) {
						update_option('titleSubscriptionUser', stripslashes($_POST['titleSubscriptionUser']), false);
					}
					if(!empty($_POST['subscriptionUserText'])) {
						update_option('subscriptionUserText', stripslashes($_POST['subscriptionUserText']), false);
					}
					/***[end] subscriber message***/

					/***moderate ads***/
					update_option('sendModerateAds', $_POST['sendModerateAds'], false);
					if(!empty($_POST['titleModerateAds'])) {
						update_option('titleModerateAds', stripslashes($_POST['titleModerateAds']), false);
					}
					if(!empty($_POST['moderateAdsText'])) {
						update_option('moderateAdsText', stripslashes($_POST['moderateAdsText']), false);
					}
					/***[end] moderate ads***/

					/***report video***/
					update_option('sendReportVideo', $_POST['sendReportVideo'], false);
					if(!empty($_POST['titleReportVideo'])) {
						update_option('titleReportVideo', stripslashes($_POST['titleReportVideo']), false);
					}
					if(!empty($_POST['reportVideoText'])) {
						update_option('reportVideoText', stripslashes($_POST['reportVideoText']), false);
					}
					/***[end] report video***/

					/***report comment***/
					update_option('sendReportComment', $_POST['sendReportComment'], false);
					if(!empty($_POST['titleReportComment'])) {
						update_option('titleReportComment', stripslashes($_POST['titleReportComment']), false);
					}
					if(!empty($_POST['reportCommentText'])) {
						update_option('reportCommentText', stripslashes($_POST['reportCommentText']), false);
					}
					/***[end] report comment***/

					/***support message***/
					update_option('sendMsgToSupport', $_POST['sendMsgToSupport'], false);
					if(!empty($_POST['titleMsgToSupport'])) {
						update_option('titleMsgToSupport', stripslashes($_POST['titleMsgToSupport']), false);
					}
					if(!empty($_POST['msgToSupportText'])) {
						update_option('msgToSupportText', stripslashes($_POST['msgToSupportText']), false);
					}
					/***[end] support message***/
				}
				?>
                <div id="users_templates" style="display: block;">
                    <!--Register new user-->
                    <div class="tab regUser">
                        <input class="accordion_input" type="checkbox" id="regUser" name="regUser">
                        <label for="regUser" class="tab-title"><?php echo __('New User Registration (User)', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
		                        <?php
		                        if(get_option('sendUserReg') == 'on') {
			                        $checked = " checked='checked' ";
			                        echo '<input type="checkbox" name="sendUserReg" id="sendUserReg"'.  $checked .'/>';
		                        } else {
			                        $checked = "";
			                        echo '<input type="checkbox" name="sendUserReg" id="sendUserReg"'.  $checked .'/>';
		                        }
		                        ?>
                                <label for="sendAdminReg"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
                            <h4><?php echo __('New User Email Title', 'arc');?></h4>
			                <?php
			                if(get_option('titleUserReg') !== false) {
				                $userTitle = get_option('titleUserReg');
			                } else {
				                $userTitle = '[site_name] Login Details';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleUserReg" id="titleUserReg" value="<?php echo $userTitle;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'regUserText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if (get_option( 'regUserText' ) !== false) {
				                $content = get_option('regUserText');
			                } else {
				                $content = '<h2>Your information</h2>';
			                }
			                wp_editor( $content, 'reguser', $settings );
			                ?>
                        </section>
                    </div>
                    <!--Lost password-->
                    <div class="tab lostPassUser">
                        <input class="accordion_input" type="checkbox" id="lostPassUser" name="lostPassUser">
                        <label for="lostPassUser" class="tab-title"><?php echo __('Lost Password', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendLostPassUser') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendLostPassUser" id="sendLostPassUser" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendLostPassUser" id="sendLostPassUser" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendLostPassUser"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleLostPassUser') !== false) {
				                $tLP = get_option('titleLostPassUser');
			                } else {
				                $tLP = 'Password reset request on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleLostPassUser" id="titleLostPassUser"
                                   value="<?php echo $tLP;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'lostPassUserText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('lostPassUserText') !== false) {
				                $content = get_option('lostPassUserText');
			                } else {
				                $content = '<h2>Someone has requested a password reset for the following account:</h2>';
			                }
			                wp_editor( $content, 'lostpassuser', $settings );
			                ?>
                        </section>
                    </div>
                    <!--Change password-->
                    <div class="tab changePassUser">
                        <input class="accordion_input" type="checkbox" id="changePassUser" name="changePassUser">
                        <label for="changePassUser" class="tab-title"><?php echo __('Password Change', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendChangePassUser') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendChangePassUser" id="sendChangePassUser" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendChangePassUser" id="sendChangePassUser" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendChangePassUser"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleChangePassUser') !== false) {
				                $tChangePass = get_option('titleChangePassUser');
			                } else {
				                $tChangePass = 'Password changed on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleChangePassUser" id="titleChangePassUser"
                                   value="<?php echo $tChangePass;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'changePassUserText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('changePassUserText') !== false) {
				                $content = get_option('changePassUserText');
			                } else {
				                $content  = '<h2>Hi, [user_login]</h2>';
				                $content .= '<p>This notice confirms that your password was changed on [site_name]</p>';
				                $content .= '<p>If you did not change your password, please contact the Site Administrator at [admin_email]</p>';
				                $content .= '<p>This email has been sent to [user_email]</p>';
				                $content .= '<p><em>Regards, All at [site_name]</em></p>';
			                }
			                wp_editor( $content, 'changepassuser', $settings );
			                ?>
                        </section>
                    </div>
                    <!--Change email-->
                    <div class="tab changeEmail">
                        <input class="accordion_input" type="checkbox" id="changeEmail" name="changeEmail">
                        <label for="changeEmail" class="tab-title"><?php echo __('Email Change', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendChangeEmail') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendChangeEmail" id="sendChangeEmail" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendChangeEmail" id="sendChangeEmail" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendChangeEmail"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleChangeEmail') !== false) {
				                $tChangeEmail = get_option('titleChangeEmail');
			                } else {
				                $tChangeEmail = 'Email changed on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleChangeEmail" id="titleChangeEmail"
                                   value="<?php echo $tChangeEmail;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'changeEmailText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('changeEmailText') !== false) {
				                $content = get_option('changeEmailText');
			                } else {
				                $content  = '<h2>Hi, [user_login]</h2>';
				                $content .= '<p>This notice confirms that your email was changed on [site_name]</p>';
				                $content .= '<p>If you did not change your email, please contact the Site Administrator at [admin_email]</p>';
				                $content .= '<p>This email has been sent to [user_email]</p>';
				                $content .= '<p><em>Regards, All at [site_name]</em></p>';
			                }
			                wp_editor( $content, 'changeemail', $settings );
			                ?>
                        </section>
                    </div>
                    <!--Submit a video (User)-->
                    <div class="tab submitVideoUser">
                        <input class="accordion_input" type="checkbox" id="submitVideoUser" name="submitVideoUser">
                        <label for="submitVideoUser" class="tab-title"><?php echo __('New Video Submission (User)', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendSubmitVideoUser') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendSubmitVideoUser" id="sendSubmitVideoUser" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendSubmitVideoUser" id="sendSubmitVideoUser" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendSubmitVideoUser"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleSubmitVideoUser') !== false) {
				                $tSubmitU = get_option('titleSubmitVideoUser');
			                } else {
				                $tSubmitU = 'Video successfully uploaded on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleSubmitVideoUser" id="titleSubmitVideoUser"
                                   value="<?php echo $tSubmitU;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'submitVideoUserText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('submitVideoUserText') !== false) {
				                $content = get_option('submitVideoUserText');
			                } else {
				                $content = '<h2>Hi, [user_login]</h2>';
				                $content .= '<p>You uploaded video on [site_name]</p>';
				                $content .= '<p>After moderating video will display on your [link_on_user_channel_page anchor_text="channel page"]</p>';
			                }

			                wp_editor($content, 'submitvideouser', $settings);
			                ?>
                        </section>
                    </div>
                    <!--Submit a photos (User)-->
                    <div class="tab submitPhotosUser">
                        <input class="accordion_input" type="checkbox" id="submitPhotosUser" name="submitPhotosUser">
                        <label for="submitPhotosUser" class="tab-title"><?php echo __('New Album Submission (User)', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendSubmitPhotosUser') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendSubmitPhotosUser" id="sendSubmitPhotosUser" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendSubmitPhotosUser" id="sendSubmitPhotosUser" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendSubmitPhotosUser"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleSubmitPhotosUser') !== false) {
				                $tSubmitU = get_option('titleSubmitPhotosUser');
			                } else {
				                $tSubmitU = 'Album successfully uploaded on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleSubmitPhotosUser" id="titleSubmitPhotosUser"
                                   value="<?php echo $tSubmitU;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'submitPhotosUserText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('submitPhotosUserText') !== false) {
				                $content = get_option('submitPhotosUserText');
			                } else {
				                $content = '<h2>Hi, [user_login]</h2>';
				                $content .= '<p>You uploaded photos on [site_name]</p>';
				                $content .= '<p>After moderating photos will display on your [link_on_user_channel_page anchor_text="channel page"]</p>';
			                }

			                wp_editor($content, 'submitphotosuser', $settings);
			                ?>
                        </section>
                    </div>
                    <!--Submit a posts (User)-->
                    <div class="tab submitPostUser">
                        <input class="accordion_input" type="checkbox" id="submitPostUser" name="submitPostUser">
                        <label for="submitPostUser" class="tab-title"><?php echo __('New Post Submission (User)', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendSubmitPostUser') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendSubmitPostUser" id="sendSubmitPostUser" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendSubmitPostUser" id="sendSubmitPostUser" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendSubmitPhotosUser"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleSubmitPostUser') !== false) {
				                $tSubmitU = get_option('titleSubmitPostUser');
			                } else {
				                $tSubmitU = 'Post successfully added on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleSubmitPostUser" id="titleSubmitPostUser"
                                   value="<?php echo $tSubmitU;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'submitPostUserText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('submitPostUserText') !== false) {
				                $content = get_option('submitPostUserText');
			                } else {
				                $content = '<h2>Hi, [user_login]</h2>';
				                $content .= '<p>You publish post on [site_name]</p>';
				                $content .= '<p>After moderating post will display on <a href="'.site_url('/community/').'">Community Page</a></p>';
			                }

			                wp_editor($content, 'submitpostsuser', $settings);
			                ?>
                        </section>
                    </div>

                    <!--Subscribed video published-->
                    <div class="tab subscriptionUser">
                        <input class="accordion_input" type="checkbox" id="subscriptionUser" name="subscriptionUser">
                        <label for="subscriptionUser" class="tab-title"><?php echo __('Subscribed video published', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendSubscriptionUser') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendSubscriptionUser" id="sendSubscriptionUser" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendSubscriptionUser" id="sendSubscriptionUser" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendSubmitVideoUser"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleSubscriptionUser') !== false) {
				                $tSubscription = get_option('titleSubscriptionUser');
			                } else {
				                $tSubscription = 'Someone you follow has a new video on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleSubscriptionUser" id="titleSubscriptionUser"
                                   value="<?php echo $tSubscription;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'subscriptionUserText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('subscriptionUserText') !== false) {
				                $content = get_option('subscriptionUserText');
			                } else {
				                $content = '<h2>Hi, [user_login]</h2>';
				                $content .= '<p>User uploaded video on [site_name]</p>';
			                }
			                wp_editor($content, 'subscribtionuser', $settings);
			                ?>
                        </section>
                    </div>
                    <!--Confirm Deleting Account-->
                    <div class="tab submitDeletingAccount">
                        <input class="accordion_input" type="checkbox" id="submitDeleteAccountUser" name="submitDeleteAccountUser">
                        <label for="submitDeleteAccountUser" class="tab-title"><?php echo __('Account Deletion (User)', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendDeleteAccountUser') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendDeleteAccountUser" id="sendDeleteAccountUser" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendDeleteAccountUser" id="sendDeleteAccountUser" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendDeleteAccountUser"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleDeleteAccountUser') !== false) {
				                $delAccU = get_option('titleDeleteAccountUser');
			                } else {
				                $delAccU = 'Delete your account on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleDeleteAccountUser" id="titleDeleteAccountUser"
                                   value="<?php echo $delAccU;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'DeleteAccountUserText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('DeleteAccountUserText') !== false) {
				                $content = get_option('DeleteAccountUserText');
			                } else {
				                $content = '<h2>Hi, [user_login]</h2>';
				                $content .= '<p>You sent the request for deleting your account on [site_name]</p>';
				                $content .= '<p>Please, click on link bellow if you want to confirm the deleting.</p>';
			                }
			                wp_editor($content, 'delaccountuser', $settings);
			                ?>
                        </section>
                    </div>
                    <!--Confirm Deleting Video-->
                    <div class="tab submitDeletingVideo">
                        <input class="accordion_input" type="checkbox" id="submitDeletingVideoUser" name="submitDeletingVideoUser">
                        <label for="submitDeletingVideoUser" class="tab-title"><?php echo __('Video Deletion (User)', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendDeleteUserVideo') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendDeleteUserVideo" id="sendDeleteUserVideo" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendDeleteUserVideo" id="sendDeleteUserVideo" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendDeleteUserVideo"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleDeleteUserVideo') !== false) {
				                $delUserV = get_option('titleDeleteUserVideo');
			                } else {
				                $delUserV = 'Delete a video on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleDeleteUserVideo" id="titleDeleteUserVideo"
                                   value="<?php echo $delUserV;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'DeleteUserVideoText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('DeleteUserVideoText') !== false) {
				                $content = get_option('DeleteUserVideoText');
			                } else {
				                $content = '<h2>Hi, [user_login]</h2>';
				                $content .= '<p>You sent the request for deleting your video on [site_name]</p>';
				                $content .= '<p>Please, click on link bellow if you want to confirm the deleting.</p>';
			                }
			                wp_editor($content, 'delvideouser', $settings);
			                ?>
                        </section>
                    </div>
                    <!--Confirm Deleting Album-->
                    <div class="tab submitDeletingAlbum">
                        <input class="accordion_input" type="checkbox" id="submitDeletingAlbumUser" name="submitDeletingAlbumUser">
                        <label for="submitDeletingAlbumUser" class="tab-title"><?php echo __('Album Deletion (User)', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendDeleteUserAlbum') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendDeleteUserAlbum" id="sendDeleteUserAlbum" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendDeleteUserAlbum" id="sendDeleteUserAlbum" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendDeleteUserAlbum"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleDeleteUserAlbum') !== false) {
				                $delUserA = get_option('titleDeleteUserAlbum');
			                } else {
				                $delUserA = 'Delete an album on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleDeleteUserAlbum" id="titleDeleteUserAlbum"
                                   value="<?php echo $delUserA;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'DeleteUserAlbumText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('DeleteUserAlbumText') !== false) {
				                $content = get_option('DeleteUserAlbumText');
			                } else {
				                $content = '<h2>Hi, [user_login]</h2>';
				                $content .= '<p>You sent the request for deleting your album on [site_name]</p>';
				                $content .= '<p>Please, click on link bellow if you want to confirm the deleting.</p>';
			                }
			                wp_editor($content, 'delalbumuser', $settings);
			                ?>
                        </section>
                    </div>
                </div>

                <div id="admin_templates" style="display: block;">
                    <!--New user registration (Administrator)-->
                    <div class="tab regAdmin">
                        <input class="accordion_input" type="checkbox" id="regAdmin" name="regAdmin">
                        <label for="regAdmin" class="tab-title"><?php echo __('New User Registration (Admin)', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendAdminReg') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendAdminReg" id="sendAdminReg"'.  $checked .'/>';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendAdminReg" id="sendAdminReg"'.  $checked .'/>';
				                }
				                ?>
                                <label for="sendAdminReg"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
                            <h4><?php echo __('Admin Email Title', 'arc');?></h4>
			                <?php
			                if(get_option('titleAdminReg') !== false) {
				                $regTitle = get_option('titleAdminReg');
			                } else {
				                $regTitle = 'New user registered at [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleAdminReg" id="titleAdminReg"
                                   value="<?php echo $regTitle;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'regAdminText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if (get_option( 'regAdminText' ) !== false) {
				                $content = get_option( 'regAdminText' );
			                } else {
				                $content = '<h2>New user registration at site [site_name]:</h2>';
				                $content .= '<p>User information: </p>';
			                }
			                wp_editor( $content, 'regadmin', $settings);
			                ?>
                        </section>
                    </div>
                    <!--Moderate a comment-->
                    <div class="tab leaveComment">
                        <input class="accordion_input" type="checkbox" id="leaveComment" name="leaveComment">
                        <label for="leaveComment" class="tab-title"><?php echo __('New Comment', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendLeaveComment') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendLeaveComment" id="sendLeaveComment" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendLeaveComment" id="sendLeaveComment" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendLeaveComment"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleLeaveComment') !== false) {
				                $tC = get_option('titleLeaveComment');
			                } else {
				                $tC = 'A comment on [site_name] requires moderation';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleLeaveComment" id="titleLeaveComment"
                                   value="<?php echo $tC;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'leaveCommentText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('leaveCommentText') !== false) {
				                $content = get_option('leaveCommentText');
			                } else {
				                $content = __('<h2>A new comment on the post is waiting for your approval</h2>', 'arc');
				                $content .= __('<p>Moderate comment: [moderate_comment_link anchor_text="click on this link"]</p>', 'arc');
			                }
			                wp_editor( $content, 'leavecomment', $settings );
			                ?>
                        </section>
                    </div>
                    <!--Submit a video (Administrator)-->
                    <div class="tab submitVideoAdmin">
                        <input class="accordion_input" type="checkbox" id="submitVideoAdmin" name="submitVideoAdmin">
                        <label for="submitVideoAdmin" class="tab-title"><?php echo __('New Video Submission (Admin)', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendSubmitVideoAdmin') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendSubmitVideoAdmin" id="sendSubmitVideoAdmin" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendSubmitVideoAdmin" id="sendSubmitVideoAdmin" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendSubmitVideoAdmin"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleSubmitVideoAdmin') !== false) {
				                $tSubmit = get_option('titleSubmitVideoAdmin');
			                } else {
				                $tSubmit = 'A new video has been submitted on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleSubmitVideoAdmin" id="titleSubmitVideoAdmin"
                                   value="<?php echo $tSubmit;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'submitVideoAdminText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('submitVideoAdminText') !== false) {
				                $content = get_option('submitVideoAdminText');
			                } else {
				                $content = '<h2>New video was uploaded to your site.</h2>';
				                $content .= '<p>For watch all videos with pending status [get_link_on_all_pending_videos anchor_text="click on this link"]</p>';
				                $content .= '<p>Submitted video - [link_on_submitted_video anchor_text="click on this link"]</p>';
			                }
			                wp_editor($content, 'submitvideoadmin', $settings);
			                ?>
                        </section>
                    </div>
                    <!--Submit a photos (Administrator)-->
                    <div class="tab submitPhotosAdmin">
                        <input class="accordion_input" type="checkbox" id="submitPhotosAdmin" name="submitPhotosAdmin">
                        <label for="submitPhotosAdmin" class="tab-title"><?php echo __('New Album Submission (Admin)', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendSubmitPhotosAdmin') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendSubmitPhotosAdmin" id="sendSubmitPhotosAdmin" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendSubmitPhotosAdmin" id="sendSubmitPhotosAdmin" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendSubmitPhotosAdmin"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleSubmitPhotosAdmin') !== false) {
				                $tSubmit = get_option('titleSubmitPhotosAdmin');
			                } else {
				                $tSubmit = 'A new album has been submitted on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleSubmitPhotosAdmin" id="titleSubmitPhotosAdmin"
                                   value="<?php echo $tSubmit;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'submitPhotosAdminText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('submitPhotosAdminText') !== false) {
				                $content = get_option('submitPhotosAdminText');
			                } else {
				                $content = '<h2>New photos was uploaded to your site.</h2>';
				                $content .= '<p>For watch all photos with pending status [get_link_on_all_pending_photos anchor_text="click on this link"]</p>';
				                $content .= '<p>Submitted photo - [link_on_submitted_photos anchor_text="click on this link"]</p>';
			                }
			                wp_editor($content, 'submitphotosadmin', $settings);
			                ?>
                        </section>
                    </div>
                    <!--Submit a posts (Administrator)-->
                    <div class="tab submitPostAdmin">
                        <input class="accordion_input" type="checkbox" id="submitPostAdmin" name="submitPostAdmin">
                        <label for="submitPostAdmin" class="tab-title"><?php echo __('New Post Submission (Admin)', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendSubmitPostAdmin') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendSubmitPostAdmin" id="sendSubmitPostAdmin" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendSubmitPostAdmin" id="sendSubmitPostAdmin" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendSubmitPostAdmin"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleSubmitPostAdmin') !== false) {
				                $tSubmit = get_option('titleSubmitPostAdmin');
			                } else {
				                $tSubmit = 'A new post has been added on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleSubmitPostAdmin" id="titleSubmitPostAdmin"
                                   value="<?php echo $tSubmit;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'submitPostAdminText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('submitPostAdminText') !== false) {
				                $content = get_option('submitPostAdminText');
			                } else {
				                $content = '<h2>New post was added to your site.</h2>';
				                $content .= '<p>For watch all posts with pending status [get_link_on_all_pending_posts anchor_text="click on this link"]</p>';
				                $content .= '<p>Pending post - [link_on_submitted_posts anchor_text="click on this link"]</p>';
			                }
			                wp_editor($content, 'submitpostadmin', $settings);
			                ?>
                        </section>
                    </div>
                    <!--Reported video or album-->
                    <div class="tab reportVideo">
                        <input class="accordion_input" type="checkbox" id="reportVideo" name="reportVideo">
                        <label for="reportVideo" class="tab-title"><?php echo __('Content Reported', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendReportVideo') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendReportVideo" id="sendReportVideo" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendReportVideo" id="sendReportVideo" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendReportVideo"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleReportVideo') !== false) {
				                $tRV = get_option('titleReportVideo');
			                } else {
				                $tRV = 'Content reported on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleReportVideo" id="titleReportVideo"
                                   value="<?php echo $tRV;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'reportVideoText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('reportVideoText') !== false) {
				                $content = get_option('reportVideoText');
			                } else {
				                $content = '<h2>Someone send report.</h2>';
				                $content .= '<p>Visit [reports_link anchor_text="Reports Tab"] for watch a report</p>';
			                }
			                wp_editor( $content, 'reportvideo', $settings );
			                ?>
                        </section>
                    </div>
                    <!--Reported comment-->
                    <div class="tab reportComment">
                        <input class="accordion_input" type="checkbox" id="reportComment" name="reportComment">
                        <label for="reportComment" class="tab-title"><?php echo __('Comment Reported', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendReportComment') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendReportComment" id="sendReportComment" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendReportComment" id="sendReportComment" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendModerateAds"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleReportComment') !== false) {
				                $tRC = get_option('titleReportComment');
			                } else {
				                $tRC = 'Comment reported on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleReportComment" id="titleReportComment"
                                   value="<?php echo $tRC;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'reportCommentText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('reportCommentText') !== false) {
				                $content = get_option('reportCommentText');
			                } else {
				                $content = '<h2>Someone mark comment like a spam</h2>';
				                $content .= '<p>Watch spam comment - [reported_comment_link anchor_text="click on this link"]</p>';
			                }
			                wp_editor( $content, 'reportcomment', $settings );
			                ?>
                        </section>
                    </div>
                    <!--Support ticket-->
                    <div class="tab msgToSupport">
                        <input class="accordion_input" type="checkbox" id="msgToSupport" name="msgToSupport">
                        <label for="msgToSupport" class="tab-title"><?php echo __('Support Message', 'arc');?></label>
                        <section class="tab-content">
                            <strong>
				                <?php
				                if(get_option('sendMsgToSupport') == 'on') {
					                $checked = " checked='checked' ";
					                echo '<input type="checkbox" name="sendMsgToSupport" id="sendMsgToSupport" '.  $checked .' />';
				                } else {
					                $checked = "";
					                echo '<input type="checkbox" name="sendMsgToSupport" id="sendMsgToSupport" '.  $checked .' />';
				                }
				                ?>
                                <label for="sendModerateAds"><?php echo __('Enable this email template', 'arc');?></label>
                            </strong>
                            <br>
                            <br>
                            <hr>
			                <?php
			                if(get_option('titleMsgToSupport') !== false) {
				                $mTS = get_option('titleMsgToSupport');
			                } else {
				                $mTS = 'Support message on [site_name]';
			                }
			                ?>
                            <input style="width: 100%" type="text" name="titleMsgToSupport" id="titleMsgToSupport"
                                   value="<?php echo $mTS;?>"/>
                            <br>
                            <br>
			                <?php
			                $settings = [
				                'wpautop'       => 1,
				                'media_buttons' => 1,
				                'textarea_name' => 'msgToSupportText',
				                'textarea_rows' => 20,
				                'tinymce'       => 1,
				                'quicktags'     => false
			                ];
			                if(get_option('msgToSupportText') !== false) {
				                $content = get_option('msgToSupportText');
			                } else {
				                $content = '<h2>User sent a message to support</h2>';
				                $content .= '<p>Watch support message [support_messages_link anchor_text="go to Support messages Tab"]</p>';
			                }
			                wp_editor( $content, 'msgtosupport', $settings );
			                ?>
                        </section>
                    </div>
                </div>

                <div id="setting-patch-settings_updated" class="notice notice-success settings-error is-dismissible">
                    <p>
                        <strong>Settings saved.</strong>
                    </p>
                </div>
                <br>
                <input type="submit" id="saveEmailSettings" class="button button-primary" value="<?php echo __('Save Changes', 'arc');?>">

                <script>
                    jQuery(document).ready(function ($) {
                        $('#saveEmailSettings').on('click', function () {
                            $('div#setting-patch-settings_updated').css('display', 'block');
                        });
                    });
                </script>
            </form>
        </div>
    </div>
<?php }
/** [end] email settings page**/