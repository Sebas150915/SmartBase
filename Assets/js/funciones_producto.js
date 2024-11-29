$(document).ready(function(){
//##################################CREAR CLIENTE##################################//
	$('#form_producto').submit(function(e)
	{
		Swal.fire({
				  icon: 'success',
				  title: 'Procesando...',
				  text: 'Espere un momento...!',
				  
				});
		
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_producto.php',
			type : "POST",
			async: true,
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData: false,

			success: function(response)
			{
			 Swal.fire({
				  icon: 'success',
				  title: 'Procesado con exito...',
				  text: 'ok...!',
				  
				});
             console.log(response);
             //location.reload(); 
			},
			error: function(response)
			{
             $('#error').modal('show'); 
			}

		});
	});
	
//##################################EDITAR CLIENTE##################################//
	$('#form_producto_edit').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_producto.php',
			type : "POST",
			async: true,
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData: false,

			success: function(response)
			{
			 $('#cargando').modal('hide');
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

	//##################################ELIMINAR CATEGORIA##################################//
	$('#form_delete_producto').submit(function(e)
	{
		Swal.fire({
				  icon: 'success',
				  title: 'Procesando...',
				  text: 'Espere un momento...!',
				  
				});
		
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_producto.php',
			type : "POST",
			async: true,
			data : $('#form_delete_producto').serialize(),

			success: function(response)
			{
			 Swal.fire({
				  icon: 'success',
				  title: 'Exito...',
				  text: 'Agregado correctamente...!',
				  
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

//################CARGAR DESDE EXCEL PRODUCTOS##################################//
	$('#cargarProducto').submit(function(e)
	{
		Swal.fire({
				  icon: 'success',
				  title: 'Procesando...',
				  text: 'Espere un momento...!',
				  
				});
		
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_producto.php',
			type : "POST",
			async: true,
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData: false,

			success: function(response)
			{
			 Swal.fire({
				  icon: 'success',
				  title: response,
				  text: 'ok...!',
				  
				});
             console.log(response);
             //location.reload(); 
			},
			error: function(response)
			{
             $('#error').modal('show'); 
             console.log(response);
			}

		});
	});
});