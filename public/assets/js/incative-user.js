$(document).ready(function(){
	$('body').on('click', '.submitConfirm', function(e){
        e.preventDefault();
        var elem = $(this);
            title = elem.data('original-title');
        swal({
            title: "Are you sure to "+title+" this business?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d43f3a",
            confirmButtonText: "Yes!",
            allowOutsideClick: true,
            customClass: 'delete-alert'
        }, 
        function(){
            var action = elem.attr('href');
            $.ajax({
                url:action,
                type:'post',
                success:function(msg){
                    if(msg=='added')
                    	location.reload();
                }
            });
        });
    });
})