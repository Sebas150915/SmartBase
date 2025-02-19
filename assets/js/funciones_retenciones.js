$(document).ready(function(){
//##################################CREAR CLIENTE##################################//
	$('#frmbajaretencion').submit(function(e)
	{
		$('.ajaxgif').removeClass('hide');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_venta1.php',
			type : "POST",
			async: true,
			data : $('#frmbajaretencion').serialize(),

			success: function(response)
			{
				console.log(response);
		            
             swal.fire({
        	 icon: "success",
        	 title: "Registro agregado con exito2..!",
        	 showConfirmButton: true,
         	 confirmButtonText: "Cerrar"

		     });
            //location.reload(); 
            //window.location = 'retenciones'
			},
			error: function(response)
			{
             swal.fire({
        	 type: "error",
        	 title: "No se pudo agregar el registro",
        	 showConfirmButton: true,
        	 confirmButtonText: "Cerrar"

             })
			}

		});
	});



});