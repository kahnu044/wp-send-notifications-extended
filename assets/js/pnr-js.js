
// settings form validation


jQuery(document).ready(function () {



    jQuery('#wpsne_send_notification').on('click', function () {

        jQuery("#wpsne_response").hide();
        var formData = jQuery('#admin-send-notifications').serialize();

        // validation here



        jQuery.ajax({
            type: "POST",
            url: pnr.ajaxurl,
            data: formData + '&action=wpsne_send_manual_send_notification',
            beforeSend: function () {
                jQuery("#wpsne_send_notification").attr('value', 'Sending.........................');
            },
            success: function (result) {
                jQuery("#wpsne_send_notification").attr('value', 'Send');
                jQuery("#wpsne_response").show();
                let data = JSON.parse(result);

                jQuery("#wpsne_response p").text(data.message);
                if(data.message.errors){
                    jQuery("#wpsne_response p").text(data.message.errors[0]);
                }
                if (data.success) {
                    if (jQuery("#wpsne_response").hasClass('notice-error')) {
                        jQuery("#wpsne_response").removeClass('notice-error');
                        jQuery("#wpsne_response").addClass('notice-success');
                    } else {
                        jQuery("#wpsne_response").addClass('notice-success');
                    }

                } else {

                    if (jQuery("#wpsne_response").hasClass('notice-success')) {
                        jQuery("#wpsne_response").removeClass('notice-success');
                        jQuery("#wpsne_response").addClass('notice-error');
                    } else {
                        jQuery("#wpsne_response").addClass('notice-error');
                    }
                }


                // // console.log('ssssss', data)
                // if (!data.success) {


                // }



                // jQuery('#pnr-send-result-status').show();
                // if (data['errors']) {
                //     jQuery("#pnr-send-result-status").addClass('notice-error');
                //     jQuery("#pnr-send-result-status").html("<p>" + data['errors']['0'] + "</p>");

                // } else if (data['id']) {
                //     jQuery("#pnr-send-result-status").addClass('notice-success');
                //     jQuery("#pnr-send-result-status").html("<p>Notifications Send Successfully</p>");

                // } else {
                //     jQuery("#pnr-send-result-status").addClass('notice-error');
                //     jQuery("#pnr-send-result-status").html("<p>" + data + "</p>");
                // }
                // jQuery('#admin-send-notifications')[0].reset();
            }
        });
    });





    // $("#api-form").validate({
    //     rules: {
    //         wpsne_api_key: {
    //             required: true,
    //             minlength: 48
    //         },

    //         wpsne_app_id: {
    //             required: true,
    //             minlength: 36
    //         }
    //     },
    //     messages: {
    //         wpsne_api_key: {
    //             required: "Please provide API Key",
    //             minlength: "Your API KEY must be at least 48 characters long"
    //         },

    //         wpsne_app_id: {
    //             required: "Please provide APP ID",
    //             minlength: "Your APP ID must be at least 32 characters long"
    //         },
    //     },
    //     submitHandler: function (form) {
    //         form.submit();
    //     }

    // });

    // // admin-send-notifications form validation
    // $('#admin-send-notifications').validate({
    //     rules: {
    //         wpsne_ntfc_heading: {
    //             required: true,
    //             maxlength: 30
    //         },

    //         wpsne_ntfc_content: {
    //             required: true,
    //             maxlength: 50
    //         }
    //     },
    //     messages: {
    //         wpsne_ntfc_heading: {
    //             required: "Notifications Heading Required",
    //             maxlength: "Not Exceeds 30 character"
    //         },

    //         wpsne_ntfc_content: {
    //             required: "Message Can't Be Empty",
    //             maxlength: "Not Exceeds 50 character"
    //         },
    //     },
    //     submitHandler: function (form) {
    //         //get form value
    //         var postDatas = $('#admin-send-notifications').serializeArray();
    //         postDatas.push({ name: "action", value: "wpsne_api_ajax" }, { name: "type", value: "admin_send_notifications" });


    //         console.log('mmmmmmmmmmmmmm' ,postDatas)

    //         //ajax call
    //         $.ajax({
    //             type: "POST",
    //             url: pnr.ajaxurl,
    //             data: 'action=wpsne_api_ajax',
    //             beforeSend: function () {
    //                 $("#wpsne_send_notification").attr('value', 'Sending');
    //             },
    //             success: function (data) {
    //                 $("#wpsne_send_notification").attr('value', 'Send');
    //                 data = JSON.parse(data);
    //                 $('#pnr-send-result-status').show();
    //                 if (data['errors']) {
    //                     $("#pnr-send-result-status").addClass('notice-error');
    //                     $("#pnr-send-result-status").html("<p>" + data['errors']['0'] + "</p>");

    //                 } else if (data['id']) {
    //                     $("#pnr-send-result-status").addClass('notice-success');
    //                     $("#pnr-send-result-status").html("<p>Notifications Send Successfully</p>");

    //                 } else {
    //                     $("#pnr-send-result-status").addClass('notice-error');
    //                     $("#pnr-send-result-status").html("<p>" + data + "</p>");
    //                 }
    //                 $('#admin-send-notifications')[0].reset();
    //             }
    //         });
    //     }
    // });
});

function api_signup_window() {
    window.open('https://app.onesignal.com/signup', "api-signup-window", "width=600, height=550, resizable=0, scrollbars=0, status=0, titlebar=0, left=" + ((screen.width - 600) / 2) + ", top=" + ((screen.height - 550) / 2));
}

function showPnrField(id) {
    jQuery(id).show();
}

function hidePnrField(id) {
    jQuery(id).hide();
}

//for custom img upload
jQuery(document).ready(function ($) {
    $("#delete_img-btn").on("click", function (e) {
        e.preventDefault();
        $('#logo_container').html("");
        $("#delete_img-btn").hide();
    });
    $('#upload_img-btn').on("click", function (e) {
        e.preventDefault();
        var $el = jQuery(this);
        var optionImageFrame = wp.media({
            title: $el.data('choose'),
            button: {
                text: $el.data('update')
            },
            states: [
                new wp.media.controller.Library({
                    title: $el.data('choose'),
                    filterable: 'all',
                    // mutiple: true if you want to upload multiple files at once
                    multiple: false
                })
            ]
        });
        optionImageFrame.on('select', function (e) {
            var uploaded_image = optionImageFrame.state().get('selection').first();
            var attachment = uploaded_image.toJSON();
            var image_url = attachment.url;
            var image_id = attachment.id;
            $('#option_image_id').val(image_id);
            $('#pnr-custom-saved-image').hide();
            $('#logo_container').append('<img class="logo" src="' + image_url + '" height="100px" width="100px" />');
            $('#pnr-custom-img-url').append('<input type="hidden" name="wpsne_img_url" value="' + image_url + '" />');
            $("#delete_img-btn").show();
        });
        optionImageFrame.open();
    });
});