(function( $ ){
	$.fn.multipleInTextbox = function(options) {
		// Default options
		var defaults = {
			position: "top"
		};
		
		// Merge send options with defaults
		var settings = $.extend( {}, defaults, options );
		
		var deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><span class="glyphicon glyphicon-remove"></span></a>';
		
		return this.each(function() {
			//$orig refers to the input HTML node
			var $orig = $(this);
			var $list = $('<ul class="multiple_emails-ul" />'); // create html elements - list of email addresses as unordered list

			if ($(this).val() != '' && IsJsonString($(this).val())) {
				$.each(jQuery.parseJSON($(this).val()), function( index, val ) {
					$list.append($('<li class="multiple_emails-email"><span class="email_name" data-email="' + val.toLowerCase() + '">' + val + '</span></li>')
					  .prepend($(deleteIconHTML)
						   .click(function(e) { $(this).parent().remove(); refresh_emails(); e.preventDefault(); })
					  )
					);
				});
			}
			
			var $input = $('<input type="text" class="multiple_emails-input text-left" />').on('keyup', function(e) { // input
				$(this).removeClass('multiple_emails-error');
				var input_length = $(this).val().length;
				
				var keynum;
				if(window.event){ // IE					
					keynum = e.keyCode;
				}
				else if(e.which){ // Netscape/Firefox/Opera					
					keynum = e.which;
                }
				
				//if(event.which == 8 && input_length == 0) { $list.find('li').last().remove(); } //Removes last item on backspace with no input
				
				// Supported key press is tab, enter, space or comma, there is no support for semi-colon since the keyCode differs in various browsers
				if(keynum == 9 || keynum == 32 || keynum == 188) { 
					display_email($(this), settings.checkDupEmail);
				}
				else if (keynum == 13) {
					display_email($(this), settings.checkDupEmail);
					//Prevents enter key default
					//This is to prevent the form from submitting with  the submit button
					//when you press enter in the email textbox
					e.preventDefault();
				}

			}).on('blur', function(event){ 
				if ($(this).val() != '') { display_email($(this), settings.checkDupEmail); }
			});

			var $container = $('<div class="multiple_emails-container" />').click(function() { $input.focus(); } ); // container div
 
			// insert elements into DOM
			if (settings.position.toLowerCase() === "top")
				$container.append($list).append($input).insertAfter($(this));
			else
				$container.append($input).append($list).insertBefore($(this));

			/*
			t is the text input device.
			Value of the input could be a long line of copy-pasted emails, not just a single email.
			As such, the string is tokenized, with each token validated individually.
			
			If the dupEmailCheck variable is set to true, scans for duplicate emails, and invalidates input if found.
			Otherwise allows emails to have duplicated values if false.
			*/
			function display_email(t, dupEmailCheck) {
				
				//Remove space, comma and semi-colon from beginning and end of string
				//Does not remove inside the string as the email will need to be tokenized using space, comma and semi-colon
				var arr = t.val().trim().replace(/^,|,$/g , '').replace(/^;|;$/g , '');
				//Remove the double quote
				arr = arr.replace(/"/g,"");
				//Split the string into an array, with the space, comma, and semi-colon as the separator
				arr = arr.split(/[\s,;]+/);
				
				var errorEmails = new Array(); //New array to contain the errors
				
				for	(var i = 0; i < arr.length; i++) {
					$list.append($('<li class="multiple_emails-email"><span class="email_name" data-email="' + arr[i].toLowerCase() + '">' + arr[i] + '</span></li>')
						  .prepend($(deleteIconHTML)
							   .click(function(e) { $(this).parent().remove(); refresh_emails(); e.preventDefault(); })
						  )
					);
				}
				// If erroneous emails found, or if duplicate email found
				if(errorEmails.length > 0)
					t.val(errorEmails.join("; ")).addClass('multiple_emails-error');
				else
					t.val("");
				refresh_emails ();
			}
			
			function refresh_emails () {
				var emails = new Array();
				var container = $orig.siblings('.multiple_emails-container');
				container.find('.multiple_emails-email span.email_name').each(function() { emails.push($(this).html()); });
				$orig.val(JSON.stringify(emails)).trigger('change');
			}
			
			function IsJsonString(str) {
				try { JSON.parse(str); }
				catch (e) {	return false; }
				return true;
			}
			
			return $(this).hide();
 
		});
		
	};
	
})(jQuery);
