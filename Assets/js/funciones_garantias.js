$(document).ready(function(){
//##################################CREAR ##################################//
	$('#form_add_garantia').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_garantias.php',
			type : "POST",
			async: true,
			data : $('#form_add_garantia').serialize(),

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
	$('#form_edit_garantia').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_garantias.php',
			type : "POST",
			async: true,
			data : $('#form_edit_garantia').serialize(),

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
	$('#form_del_garantia').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_garantias.php',
			type : "POST",
			async: true,
			data : $('#form_del_garantia').serialize(),

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

