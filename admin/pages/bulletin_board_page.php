<?php
function page_bulletin_board(){?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo __('Ads board', 'arc');?></h1>
	<hr class="wp-header-end">

   <?php global $wpdb;
    $table_bulletin_board = $wpdb->prefix . 'bulletin_board';
    $res = $wpdb->get_results( "SELECT * FROM $table_bulletin_board WHERE status = 0 " );
    $all_ads =[];
    foreach($res as $v){
        $userdata = get_userdata($v->id_advertiser);
        $user_name = $userdata->display_name;
	    if(get_user_meta($v->id_advertiser, 'personal_foto', true) == false){
		    $avatar = get_template_directory_uri(). '/assets/img/picture.png';
	    } else {
		    $avatar = get_user_meta($v->id_advertiser, 'personal_foto', true);
	    }


        $all_ads [] = [
            'ad_type'          => $v->ad_type,
            'id'               => $v->id,
            'id_advertiser'    => $v->id_advertiser,
            'publication_date' => $v->publication_date,
            'status'           => $v->status,
            'text_message'     => $v->text_message,
            'user_name'        => $user_name,
            'user_photo'       => $avatar,
        ];
    }?>
    <form id="comments-form" method="get">
	<table style="margin-top: 30px" class="wp-list-table widefat fixed striped table-view-list comments">
	<thead>
	<tr>
		<th style="padding-left: 10px" scope="col" id="author" class="manage-column column-author">
				Author
		</th>
		<th scope="col" id="response" class="manage-column column-comment column-primary">
			Ads message
		</th>
		<th scope="col" id="date" class="manage-column column-date">
			Publication date
		</th>
		<th scope="col" id="reports" class="manage-column column-response">
			Actions
		</th>
	</tr>
	</thead>
        <tbody id="the-comment-list">
    <?php
    foreach($all_ads as $v){
        echo '<tr class="comment byuser comment-author-admin bypostauthor even thread-even depth-1 approved ' . $v['id'] .'">';
        echo '<td class="author column-author">';
        echo '<img class="avatar avatar-32 photo" style="max-width: 50px;" src="' . $v['user_photo'] . '"/><strong>' . $v['user_name'] . '</strong></td>';
        echo '<td class="comment column-comment has-row-actions column-primary" data-class="'. $v['id'] .'">';
        echo '<div class="comment-author"><strong><img class="avatar avatar-32 photo" style="max-width: 50px;" src="' . $v['user_photo'] . '"/>' . $v['user_name'] . '</strong></div>';
        echo '<p>Ad type: <em>' . $v['ad_type'] . '</em></p>';
        echo '<p data-class="text_'. $v['id'] .'">' . $v['text_message'] . '</p>';
        echo '<button type="button" class="button edit" data-id-for-edit="' . $v['id'] . '">Edit advertising</button>';
        echo '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
        echo '</td>';
        echo '<td class="date column-date" data-colname="Posted on:" data-id="' . $v['id'] . '"><div class="submitted-on"></div></td>';
        echo '<td class="reports column-response" data-colname="Actions:"><div style="left: 0;" class="row-actions">';
        echo '<button type="button" class="button to_publish" data-id-for-publish="' . $v['id'] . '">Publish</button>';
        echo '<button type="button" class="button delete_ads" data-id-for-delete="' . $v['id'] . '">Delete</button></div></td>';
        echo '</tr>';
    }
    echo '</tbody></table></form>';
    echo '</div>';?>
        <div class="popup-fade">
            <div class="popup">
                <a class="popup-close" href="#"><?php echo __('Close', 'arc');?></a>
                <textarea id="ads_text_msg" style="margin-top: 30px; width: 100%" rows="5"></textarea><br>
                <button type="button" class="button apply_edits_made" data-id-for-save="">Apply changes</button>
            </div>
        </div>
        <style>
            .popup-fade {
                display: none;
            }
            .popup {
                position: fixed;
                top: 20%;
                left: 50%;
                padding: 20px;
                width: 285px;
                margin-left: -164px;
                background: #fff;
                border: 1px solid darkslategrey;
                border-radius: 4px;
                z-index: 99999;
                opacity: 1;
            }
            .popup-close {
                position: absolute;
                top: 10px;
                right: 10px;
            }
        </style>

<?php        }
?>
