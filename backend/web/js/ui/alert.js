(function($) {
    'use strict';
    $('.swal-message').on('click', function() {
        swal('Here\'s a message!');
    });
    $('.swal-timer').on('click', function() {
		var title = $(this).attr("data-title");
		var text = $(this).attr("data-text");
		var timer = $(this).attr("data-timer");
        swal({
            title: title,
            text: text,
            timer: timer
        });
    });
    $('.swal-title').on('click', function() {
		var title = $(this).attr("data-title");
		var text = $(this).attr("data-text");
        swal(title, text);
    });
    $('.swal-success').on('click', function() {
		var title = $(this).attr("data-title");
		var text = $(this).attr("data-text");
		var timer = $(this).attr("data-timer");
        swal(title, text, 'success');
    });
    $('.swal-error').on('click', function() {
		var title = $(this).attr("data-title");
		var text = $(this).attr("data-text");
		var timer = $(this).attr("data-timer");
        swal(title, text, 'error');
    });
    $('.swal-warning-confirm').on('click', function() {
		var title = $(this).attr("data-title");
		var text = $(this).attr("data-text");
		var type = $(this).attr("data-type");
		var showCancelButton = $(this).attr("data-show-confirm-button");
		var confirmButtonColor = $(this).attr("data-confirm-button-color");
		var confirmButtonText = $(this).attr("data-confirm-button-text");
		var closeOnConfirm = $(this).attr("data-close-on-confirm");
		var id = $(this).attr("data-id");
		var url = $(this).attr("data-url");
		
        swal({
            title: title?title:'Are you sure?',
            text: text?text:'You want to Deactivate this record!',
            type: type?type:'warning',
            showCancelButton: showCancelButton?showCancelButton:true,
            confirmButtonColor: confirmButtonColor?confirmButtonColor:'#DD6B55',
            confirmButtonText: confirmButtonText?confirmButtonText:'Yes, Deactivate it!',
            closeOnConfirm: closeOnConfirm?closeOnConfirm:false
        }, function() {
			$.ajax({
				type:'post',
				url:url,
				onSuccess:function(data){
					if(data){
						swal('Deactivate!','','success');
					}else{
						swal('Not Deactivate!','','error');
					}
				}
			});
        });
    });
    $('.swal-warning-inquiry-cancel').on('click', function() {
		var title = $(this).attr("data-title");
		var text = $(this).attr("data-text");
		var type = $(this).attr("data-type");
		var showCancelButton = $(this).attr("data-show-confirm-button");
		var confirmButtonColor = $(this).attr("data-confirm-button-color");
		var confirmButtonText = $(this).attr("data-confirm-button-text");
		var closeOnConfirm = $(this).attr("data-close-on-confirm");
		var id = $(this).attr("data-id");
		var url = $(this).attr("data-url");
		
        swal({
            title: title?title:'Are you sure?',
            text: text?text:'You want to Cancel this Inquiry!',
            type: type?type:'warning',
            showCancelButton: showCancelButton?showCancelButton:true,
            confirmButtonColor: confirmButtonColor?confirmButtonColor:'#DD6B55',
            confirmButtonText: confirmButtonText?confirmButtonText:'Yes, Cancel it!',
            //cancelButtonText: cancelButtonText?cancelButtonText:'No, cancel!',
            closeOnConfirm: closeOnConfirm?closeOnConfirm:false
            //closeOnCancel: closeOnCancel?closeOnCancel:false
        }, function(isConfirm) {
            if (isConfirm) {
				$.ajax({
					type:'post',
					url:url,
					onSuccess:function(data){
						if(data){
							swal('Deleted!','','success');
						}else{
							swal('Cancelled!','','error');
						}
					}
				});
            } else {
                swal('Cancelled','','error');
            }
        });
    });
    $('.swal-warning-schedule-cancel').on('click', function() {
		var title = $(this).attr("data-title");
		var text = $(this).attr("data-text");
		var type = $(this).attr("data-type");
		var showCancelButton = $(this).attr("data-show-confirm-button");
		var confirmButtonColor = $(this).attr("data-confirm-button-color");
		var confirmButtonText = $(this).attr("data-confirm-button-text");
		var closeOnConfirm = $(this).attr("data-close-on-confirm");
		var id = $(this).attr("data-id");
		var url = $(this).attr("data-url");

        swal({
            title: title?title:'Are you sure?',
            text: text?text:'You want to Delete this Mail!',
            type: type?type:'warning',
            showCancelButton: showCancelButton?showCancelButton:true,
            confirmButtonColor: confirmButtonColor?confirmButtonColor:'#DD6B55',
            confirmButtonText: confirmButtonText?confirmButtonText:'Yes, Delete it!',
            //cancelButtonText: cancelButtonText?cancelButtonText:'No',
            closeOnConfirm: closeOnConfirm?closeOnConfirm:false
            //closeOnCancel: closeOnCancel?closeOnCancel:false
        }, function(isConfirm) {
            if (isConfirm) {
				$.ajax({
					type:'post',
					url:url,
					onSuccess:function(data){
						if(data){
							swal('Deleted!','','success');
						}else{
							swal('Cancelled!','','error');
						}
					}
				});
            } else {
                swal('Cancelled','','error');
            }
        });
    });
	
	  $('.swal-activate').on('click', function() {
		var title = $(this).attr("data-title");
		var text = $(this).attr("data-text");
		var type = $(this).attr("data-type");
		var showCancelButton = $(this).attr("data-show-confirm-button");
		var confirmButtonColor = $(this).attr("data-confirm-button-color");
		var confirmButtonText = $(this).attr("data-confirm-button-text");
		var closeOnConfirm = $(this).attr("data-close-on-confirm");
		var id = $(this).attr("data-id");
		var url = $(this).attr("data-url");
		
        swal({
            title: title?title:'Are you sure?',
            text: text?text:'You want to activate this Record!',
            type: type?type:'warning',
            showCancelButton: showCancelButton?showCancelButton:true,
            confirmButtonColor: confirmButtonColor?confirmButtonColor:'#006666',
            confirmButtonText: confirmButtonText?confirmButtonText:'Yes, Activate it!',
            //cancelButtonText: cancelButtonText?cancelButtonText:'No, cancel!',
            closeOnConfirm: closeOnConfirm?closeOnConfirm:false
            //closeOnCancel: closeOnCancel?closeOnCancel:false
        }, function(isConfirm) {
            if (isConfirm) {
				$.ajax({
					type:'post',
					url:url,
					onSuccess:function(data){
						if(data){
							swal('Deleted!','','success');
						}else{
							swal('Cancelled!','','error');
						}
					}
				});
            } else {
                swal('Cancelled','','error');
            }
        });
    });

	
    $('.swal-custom-icon').on('click', function() {
		var title = $(this).attr("data-title");
		var text = $(this).attr("data-text");
		var image = $(this).attr("data-image");
        swal({
            title: title,
            text: text,
            imageUrl: image
        });
    });
})(jQuery);