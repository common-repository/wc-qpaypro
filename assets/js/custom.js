jQuery( document ).ready(function($) {

	if($('.woocommerce-checkout').length) {
		jQuery(function($){
			$("#order_review").on("submit", function(s){
		        $("#place_order").attr("disabled", "disabled");
		        s.submit();
		    });
		});

		jQuery(function($){
		    $("#qpaypro_ccnum").on("keydown", function(s){  
				if( document.getElementById("qpaypro_ccnum").value.indexOf("4") == 0){
					document.getElementById("qpaypro_visaencuotas").style.visibility = "visible";
					document.getElementById("name_visa_cuotas").style.visibility = "visible";
				}else{
					document.getElementById("qpaypro_visaencuotas").style.visibility = "hidden";
					document.getElementById("name_visa_cuotas").style.visibility = "hidden";
				}});
			$("#qpaypro_ccnum").on("keyup", function(s){
				if( document.getElementById("qpaypro_ccnum").value.indexOf("4") == 0){
					document.getElementById("qpaypro_visaencuotas").style.visibility = "visible";
					document.getElementById("name_visa_cuotas").style.visibility = "visible";
				}else{
					document.getElementById("qpaypro_visaencuotas").style.visibility = "hidden";
					document.getElementById("name_visa_cuotas").style.visibility = "hidden";
				}});
		});

	}



});