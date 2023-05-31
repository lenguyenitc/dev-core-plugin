<?php
function faq_edit_page(){ ?>
	<div id="faq_page" style="display: inline-flex; margin-top: 20px;">
        <h1>FAQ Edit </h1>
        <button style="margin-left: 20px;margin-top: 10px; height: 28px;" type="button" id="add_faqs" class="button button-primary desktop_btns">Add new question</button>
        <div id="mobile_btns" style="display: none">
            <button style="margin-left: 20px;margin-top: 10px; height: 28px;" type="button" id="add_faqs" class="button button-primary">Add new question</button>
            <button type="button" id="update_faqs" class="button button-primary update_faqs">Save changes</button>
            <span id="update" style="margin-left:10px; display: none;"><strong>Saved!</strong></span>
        </div>
	</div>
	<style>
		#faq_items_container {
			flex-wrap: wrap;
			margin-top: 20px;
		}
		#faq_items_table {
			width: 100%;
		}
	</style>
	<div class="container-fluid" id="faq_items_container">
		<div class="row form-group">
			<div class="col-8">
				<table id="faq_items_table">
                    <?php
                    $faqs = get_option('faqs_test');
                    if($faqs):
                        foreach ($faqs as $q => $ans):
                            $item = explode('faq_item_',$q)[1];
	                        $qw = stripcslashes(explode('~SEP_BITWEEN_Q~', $ans)[0]);
	                        $answ = stripcslashes(explode('~SEP_GROUP_TYPE~',explode('~SEP_BITWEEN_Q~', $ans)[1])[0]);
                            $qroup = (explode('~SEP_GROUP_TYPE~', $ans)[1] == 'undefined') ? 'General' : explode('~SEP_GROUP_TYPE~', $ans)[1];
                    ?>
					<tr class="faq_item_<?=$item?>">
						<td>
                            <label for="faq_item_title_<?=$item?>">Question</label>
							<input id="faq_item_title_<?=$item?>" class="form-control" type="text" placeholder="Enter the question..." value="<?=$qw?>" /><br>
                            <label for="faqs_groups">FAQs groups</label><br>
                            <?php if($qroup == 'General'):?>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="general_<?=$item?>" value="General" checked> <label for="general_<?=$item?>">General</label>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="abuses_<?=$item?>" value="Abuses"> <label for="abuses_<?=$item?>">Abuses</label>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="uploads_<?=$item?>" value="Uploads"> <label for="uploads_<?=$item?>">Uploads</label>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="technical_<?=$item?>" value="Technical"> <label for="technical_<?=$item?>">Technical</label><br><br>
                            <?php elseif ($qroup == 'Abuses'):?>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="general_<?=$item?>" value="General"> <label for="general_<?=$item?>">General</label>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="abuses_<?=$item?>" value="Abuses" checked> <label for="abuses_<?=$item?>">Abuses</label>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="uploads_<?=$item?>" value="Uploads"> <label for="uploads_<?=$item?>">Uploads</label>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="technical_<?=$item?>" value="Technical"> <label for="technical_<?=$item?>">Technical</label><br><br>
                            <?php elseif ($qroup == 'Uploads'):?>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="general_<?=$item?>" value="General"> <label for="general_<?=$item?>">General</label>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="abuses_<?=$item?>" value="Abuses"> <label for="abuses_<?=$item?>">Abuses</label>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="uploads_<?=$item?>" value="Uploads" checked> <label for="uploads_<?=$item?>">Uploads</label>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="technical_<?=$item?>" value="Technical"> <label for="technical_<?=$item?>">Technical</label><br><br>
                            <?php elseif ($qroup == 'Technical'):?>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="general_<?=$item?>" value="General"> <label for="general_<?=$item?>">General</label>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="abuses_<?=$item?>" value="Abuses"> <label for="abuses_<?=$item?>">Abuses</label>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="uploads_<?=$item?>" value="Uploads"> <label for="uploads_<?=$item?>">Uploads</label>
                                <input type="radio" name="faq_item_group_<?=$item?>" id="technical_<?=$item?>" value="Technical" checked> <label for="technical_<?=$item?>">Technical</label><br><br>
                            <?php endif;?>
                            <label for="faq_item_text_<?=$item?>">Answer</label>
                            <textarea id="faq_item_text_<?=$item?>" class="form-control" placeholder="Enter the answer..." rows="3"><?=$answ?></textarea><br>
							<button type="button" class="button button-default remove_item" data-remove="faq_item_<?=$item?>">Remove item</button>
							<hr>
						</td>
					</tr>
                    <?php
                        endforeach;?>
                    <?php endif;?>
				</table>
			</div>
			<div class="col-4">
				<h4>Update</h4>
				<hr>
				<button type="button" id="update_faqs" class="button button-primary update_faqs">Save changes</button>
                <span id="update" style="margin-left:10px; display: none;"><strong>Saved!</strong></span>
			</div>
		</div>
	</div>
	<script>
		jQuery(document).ready(function ($) {
            var faq_count = 0;
		    /***add new faq***/
		    $('#add_faqs').on('click', function() {

		        $('#faq_items_table tr').each(function () {
		           faq_count++;
		        });
		        $('#faq_items_table').append('<tr class="faq_item_'+faq_count+'">'+
                    '<td>'+
                    '<label for="faq_item_title_'+faq_count+'">Question</label>' +
                    '<input id="faq_item_title_'+faq_count+'" class="form-control" type="text" placeholder="Enter the question..." /><br>'+
                    '<label for="faqs_groups">FAQs groups</label><br>'+
                    '<input type="radio" name="faq_item_group_'+faq_count+'" id="general_'+faq_count+'" value="General"> <label for="general_'+faq_count+'">General</label>'+
                    '<input type="radio" name="faq_item_group_'+faq_count+'" id="abuses_'+faq_count+'" value="Abuses"> <label for="abuses_'+faq_count+'">Abuses</label>'+
                    '<input type="radio" name="faq_item_group_'+faq_count+'" id="uploads_'+faq_count+'" value="Uploads"> <label for="uploads_'+faq_count+'">Uploads</label>'+
                    '<input type="radio" name="faq_item_group_'+faq_count+'" id="technical_'+faq_count+'" value="Technical"> <label for="technical_'+faq_count+'">Technical</label><br><br>'+
                    '<label for="faq_item_text_'+faq_count+'">Answer</label>' +
                    '<textarea id="faq_item_text_'+faq_count+'" class="form-control" placeholder="Enter the answer..." rows="3"></textarea><br>'+
	                '<button type="button" class="button button-default remove_item" data-remove="faq_item_'+faq_count+'">Remove item</button>'+
	                '<hr></td></tr>');

		        $('id#faq_item_title_'+faq_count).focus();
		    });

            /***remove item***/
            $(document).on('click', '.remove_item', function() {
                var item_id = $(this).attr('data-remove');
                $('tr.' + item_id).remove();
            });

            /***update faq***/
            $('.update_faqs').on('click', function () {
                var faqs = {};
                $('#faq_items_table tr').each(function () {
                    var tr_id = $(this).attr('class');
                    var question = $(this).find('td').find('input[type="text"]').val();
                    var group = $(this).find('td').find('input[type="radio"]:checked').val();
                    var answer = $(this).find('td').find('textarea').val();
                    faqs[tr_id] = question + '~SEP_BITWEEN_Q~' + answer + '~SEP_GROUP_TYPE~' + group;
                });
                //console.log(faqs);
                $.ajax({
                    type: "post",
                    url: faqs_obj.url,
                    data: {
                        faqs: {faqs},
                        nonce: faqs_obj.nonce,
                        action: 'save_faq_questions',
                    },
                    success: function (res) {
                        //console.log(res);
                        if(res == 'update') {
                            $('#update').css('display', 'inline-block');
                            setTimeout(()=>{$('#update').css('display', 'none');}, 1200);
                        }
                    }
                });
            });
		});
	</script>
    <style>
        @media (max-width: 740px) {
            div#faq_page {
                flex-wrap: wrap;
                width: 100% !important;
                max-width: 100% !important;
                justify-content: center!important;
            }
            #faq_items_container > div > div.col-4 {
                display: none!important;
            }
            #faq_items_container > div > div.col-8{
                width: 100% !important;
                max-width: 100% !important;
                flex-basis: 100% !important;
            }
            h1
            {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                text-align: center !important;
                clear: both !important;
                margin-bottom: 10px !important;
            }
            div#mobile_btns {
                width: 100% !important;
                display: inline-flex !important;
                justify-content: center !important;
                flex-wrap: nowrap !important;
                align-items: baseline !important;
            }
            div#mobile_btns button{
                width: 100% !important;
                max-width: 143px !important;
                height: 40px !important;
                margin-right: 10px !important;
                margin-left: 0!important;
            }
            div#mobile_btns button:nth-child(2){
                margin-right: 0px !important;
            }
            button.desktop_btns {
                display: none!important;
            }
        }
    </style>
	<?php
}