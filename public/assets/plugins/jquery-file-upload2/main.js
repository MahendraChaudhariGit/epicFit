/*
 * jQuery File Upload Plugin JS Example 8.9.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */




$(function () {
    'use strict';
     //var file = $('meta[name="public_url"]').attr('content');
        //console.log(file);
    // Initialize the jQuery File Upload widget:
   
    $('#fileupload').fileupload({
        xhrFields: {withCredentials: true},

        url: $('meta[name="public_url"]').attr('content')+'assets/plugins/jquery-file-upload2/server/php/index.php'
    }).on('fileuploadadd', function (e, data) {
         $('#addbtn').hide();

         setTimeout( function(){ 
            $('.cancel').click(function(){
                $('#addbtn').show();
            });
         }, 1000);

    }).on('fileuploaddone', function (e, data) {
         $(this).find('.fileupload-progress').addClass('hidden')
         //var fileProcess=data.result.files[0]['name'];
         
        /*setTimeout( function(){ 
            $('.delete').click(function(){
                $('#addbtn').show();
                $("input[name=staffBiography]").val('');
                alert('ok')
            });
         }, 1000);*/

    })

    $(document).on("click", "#fileupload .delete", function(e){
        $('#addbtn').show();
        $("input[name=staffBiography]").val('');
    });

     $(document).on("click", "#fileupload .cancel", function(e){
        $('#addbtn').show();
        $("input[name=staffBiography]").val('');
    });
    
    


    // Enable iframe cross-domain access via redirect option:
    /*$('#fileupload').fileupload(

        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );*/

    if (window.location.hostname === 'blueimp.github.io') {
        // Demo settings:
        $('#fileupload').fileupload('option', {

            url: '//jquery-file-upload.appspot.com/',
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),
            maxFileSize: 1,
            acceptFileTypes: /(\.|\/)(pdf|doc?x)$/i
        });
        // Upload server status check for browsers with CORS support:
        if ($.support.cors) {
            $.ajax({
                url: '//jquery-file-upload.appspot.com/',
                type: 'HEAD'
            }).fail(function () {
                $('<div class="alert alert-danger"/>')
                    .text('Upload server currently unavailable - ' +
                            new Date())
                    .appendTo('#fileupload');
            });
        }
    } else {
        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
            
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
           
        }).always(function () {
            $(this).removeClass('fileupload-processing');
            //$('.start').click(function(){
            
            
            
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
                 
                 var checkFiles = result.files;
                 
                 if(Object.keys(checkFiles).length === 0){
                    $("#addbtn").show();
                 }
                    else{
                    $("#addbtn").hide();
                    
                    $(".delete").click(function(){
                        $("#addbtn").show();
                        
                    });
                    }
                
          

        });

  }

});

