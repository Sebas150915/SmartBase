$(document).ready(function(){
//##################################CREAR CLIENTE##################################//
	$('#form_add_nivel').submit(function(e)
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
			url  :  base_url+'/assets/ajax/ajax_nivel.php',
			type : "POST",
			async: true,
			data : $('#form_add_nivel').serialize(),

			success: function(response)
			{
			 
             $('#exito').modal('show'); 
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
             $('#error').modal('show'); 
			}

		});
	});
	
//##################################EDITAR ##################################//
$('#form_edit_nivel').submit(function(e)
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
			url  :  base_url+'/assets/ajax/ajax_nivel.php',
			type : "POST",
			async: true,
			data : $('#form_edit_nivel').serialize(),

			success: function(response)
			{
			 
             $('#exito').modal('show'); 
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
             $('#error').modal('show'); 
			}

		});
});

	//##################################ELIMINAR##################################//
	$('#form_del_nivel').submit(function(e)
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
			url  :  base_url+'/assets/ajax/ajax_nivel.php',
			type : "POST",
			async: true,
			data : $('#form_del_nivel').serialize(),

			success: function(response)
			{
			 
             $('#exito').modal('show'); 
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


