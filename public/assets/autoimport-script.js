/*
jQuery(document).ready(function($) {
   $.ajax({
      type: "post",
      url: arc_ajax_var.url,
       data: {
           action: 'test_autoimport',
           nonce: arc_ajax_var.nonce,
           category: arc_category.cat,
           catId: arc_category.catId
       },
       beforeSend: function () {
       },
       success: function (res) {
          console.log(res);
       },
       complete: function (res) {
           console.log(JSON.stringify(res['responseJSON'][1]));
           /!*$.ajax({
               type: "post",
               url: arc_ajax_var.url,
               data: {
                   action: 'write_file',
                   nonce: arc_ajax_var.nonce,
                   category: res['responseJSON'][0],
                   data: res['responseJSON'][1]
               },
               beforeSend: function () {
               },
               success: function (res) {
                   console.log(res);
               },
           });*!/
       }
   });
});*/
