$(document).ready(function(){
	
	$('#btnInstalar').on('click', function(ev){
		
		ev.preventDefault();
		
		var url = $(this).data('ajax');
		
		$.ajax({
			url: url,
			type: 'POST',
			data: $('form').serialize(),
			dataType: 'json',
			beforeSend: function(){
				$('.pnlInstalacion').addClass('ajax-cargando');
			}
		})
		.done(function(data){
			
			$('.pnlInstalacion').removeClass('ajax-cargando');
	
			if (data.estado === 'error') {
				showAdv(data.descripcion);
			} else if(data.estado === 'ok') {
				
				showOk('El sistema se instalo correctamente. Redireccionando...');
				setTimeout(function(){
					window.location = data.url;
				}, 5000);
				
			}

		});
		
	});
	
});