$(document).ready(function(){
//##################################CREAR CLIENTE##################################//
	$('#form_pago_cta').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_pagos.php',
			type : "POST",
			async: true,
			data : $('#form_pago_cta').serialize(),

			success: function(response)
			{
			 $('#cargando').modal('hide');
             $('#exito').modal('show'); 
             console.log(response);
             alert('respuesta1'+response);
             //location.reload(); 
			},
			error: function(response)
			{$('#exito').modal('hide'); 
            
             $('#error').modal('show'); 
             alert(response);
			}

		});
	});
	
//##################################EDITAR CLIENTE##################################//
	$('#form_edi_categoria').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_categoria.php',
			type : "POST",
			async: true,
			data : $('#form_edi_categoria').serialize(),

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


function openModalEnvia()
{
	$('#ModalVentaEnviada').modal('show');

	var arr = [];

	$('#datatable-ventas > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			$('#enviar_id').val(arr[0]);

		});
}
