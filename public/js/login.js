$(document).ready(function(){
	
	$('#btnLogin').on('click', function(ev){
		
		ev.preventDefault();
		
		var url = $(this).data('ajax');
		
		$.ajax({
			url: url,
			type: 'POST',
			data: $('form').serialize(),
			dataType: 'json',
			beforeSend: function(){
				$('.pnlAcceso').addClass('ajax-cargando');
			}
		})
		.done(function(data){
			
			$('.pnlAcceso').removeClass('ajax-cargando');
	
			if (data.estado === 'error') {
				showAdv(data.descripcion);
			} else if(data.estado === 'ok') {
				window.location = data.url;
			}

		});
		
	});
	
});