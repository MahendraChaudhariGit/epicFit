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

    var form = 'form-1';
    // Initialize the jQuery File Upload widget:
    $('#fileupload-'+form).fileupload({
        // Uncomment the following to send cross-domain cookies:
        xhrFields: {withCredentials: true},
        url: window.location.origin+'/vendor/jquery-file-upload/server/php/index.php',

        add: function (e, data) {
            var fileNumber = $('#fileupload .files .name').length;
            if( fileNumber > 0 ) {
                return false;
            }
            data.submit();
        },
        always: function(e, data) {
            var thumbnail = data.jqXHR.responseJSON.files[0].thumbnailUrl;
            $('#'+form).find('#business-logo').val(thumbnail);

            var fileUploadHeight = $('#fileupload-'+form).height();
            var targetFieldWidth = $('#business-logo-fieldset').width();

            $('#fileupload-'+form).css('width', targetFieldWidth);

            $('#business-logo-fieldset').css('height', fileUploadHeight);

            $('#business-logo-fieldset').offset();

            //fileupload-form-1
            //business-logo-fieldset
        }
    });
});
