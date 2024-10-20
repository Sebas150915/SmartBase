$(document).ready(function(){
//##################################CREAR CLIENTE##################################//
	$('#form_add_contrato').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			/*url  :  base_url+'/assets/ajax/ajax_contratos.php',
			type : "POST",
			async: true,
			data : $('#form_add_contrato').serialize(), */
			
			url  :  base_url+'/assets/ajax/ajax_contratos.php',
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
	
//##################################EDITAR ##################################//
	$('#form_edit_contrato').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_contratos.php',
			type : "POST",
			async: true,
			data : $('#form_edit_contrato').serialize(),

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
	$('#form_del_categoria').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_categoria.php',
			type : "POST",
			async: true,
			data : $('#form_del_categoria').serialize(),

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
});// en ready

