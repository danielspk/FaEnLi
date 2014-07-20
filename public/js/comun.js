function showNotificacion(clase, texto) {
	$('#notificacion')
		.removeClass('notifAdv')
		.removeClass('notifErr')
		.addClass(clase)
		.html(texto)
		.stop()
		.fadeIn(1000)
		.delay(4000)
		.fadeOut(1000);
}

function showOk(texto) {
	showNotificacion('notifOk', texto);
}

function showAdv(texto) {
	showNotificacion('notifAdv', texto);
}

function showError(texto) {
	showNotificacion('notifErr', texto);
}

$(document).ready(function(){
	
	if ($('.icoRefresh').length) {
		
		$('.icoRefresh').on('click', function(){
			$('#imgCaptcha').attr('src', './public/img/captcha.php?'+Math.random());
		});
		
	}
	
	$(document).ajaxError(function(ev, jqXHR, settings, message){
		$('.ajax-cargando').removeClass('ajax-cargando');
		showError("Error en su solicitud al servidor.<br />" + message);
	});
	
});