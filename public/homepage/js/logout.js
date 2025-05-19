(function ($) {
 "use strict";
 
	$('#logout').click(function(event) {
		event.preventDefault();
		
		Swal.fire({
			title: 'Ready to Leave?',
			text: 'Select "Logout" below if you are ready to end your current session.',
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Logout',
			reverseButtons: true,
			showClass: {
				popup: 'animated fadeInDown'
			},
			hideClass: {
				popup: 'animated fadeOutUp'
			}
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: 'data/logout.php',
					type: 'POST',
					success: function(result) {
						let timerInterval
						location.href = 'index.php';
					},
					error: function() {
						Swal.fire('Error', 'Something went wrong please try again later!', 'error');
					}
				});
			}
		});
	});
 
})(jQuery); 