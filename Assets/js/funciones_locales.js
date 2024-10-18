$(document).ready(function(){
//##################################CREAR CLIENTE##################################//
	$('#form_add_local').submit(function(e)
	{
		swal.fire({
					title: "Cargando...",
					text: "Por favor espere",
					imageUrl: base_url+'/assets/js/ajax.gif',
					showConfirmButton: false,
					allowOutsideClick: false
					});
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_locales.php',
			type : "POST",
			async: true,
			data : $('#form_add_local').serialize(),

			success: function(response)
			{
                Swal.fire({
                title: "Guardado con exito...!",
                text: response,
                icon: "success"
                }); 
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
             $('#error').modal('show'); 
			}

		});
	});
	
//##################################EDITAR CLIENTE##################################//
	$('#form_edi_local').submit(function(e)
	{
		swal.fire({
					title: "Cargando...",
					text: "Por favor espere",
					imageUrl: base_url+'/assets/js/ajax.gif',
					showConfirmButton: false,
					allowOutsideClick: false
					});
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_locales.php',
			type : "POST",
			async: true,
			data : $('#form_edi_local').serialize(),

			success: function(response)
			{
			 
              Swal.fire({
                title: "Guardado con exito...!",
                text: response,
                icon: "success"
                }); 
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
             $('#error').modal('show'); 
			}

		});
	});

	//##################################ELIMINAR CATEGORIA##################################//
	$('#form_del_local').submit(function(e)
	{
		swal.fire({
					title: "Cargando...",
					text: "Por favor espere",
					imageUrl: base_url+'/assets/js/ajax.gif',
					showConfirmButton: false,
					allowOutsideClick: false
					});
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_locales.php',
			type : "POST",
			async: true,
			data : $('#form_del_local').serialize(),

			success: function(response)
			{
			  Swal.fire({
                title: "Guardado con exito...!",
                text: response,
                icon: "success"
                });  
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
             $('#error').modal('show'); 
			}

		});
	});
});// en ready

