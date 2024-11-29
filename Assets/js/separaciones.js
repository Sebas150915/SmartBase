function openModalEdit()
{

	$('#ModalSeparacionEdit').modal('show');

	var arr = [];

	$('#dataTable-1 > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			$('#update_id').val(arr[1]);
			$('#update_cliente').val(arr[2]).attr('selected', 'selected');
			$('#update_moneda').val(arr[4]);
			$('#update_soles').val(arr[5]);
			$('#update_dolares').val(arr[6]);
			$('#update_fecha').val(arr[7]);
			
		});
}



function openModalDel()
{
	$('#ModalDeleteSeparacion').modal('show');

	var arr = [];

	$('#dataTable-1 > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			$('#delete_id').val(arr[1]);
			
		});
}



function listarseparacion(id)
{
		var id = id;
		var action = 'listarseparacion';
	
	  $.ajax({
	  	url: base_url+'/assets/ajax/ajax_listar_separacion.php',
	  	type: "POST",
	  	async: true,
	  	data: {action:action,id:id},

	  	success: function(response)
	  	{
	  		  console.log(response);
	  		  var data = $.parseJSON(response);
	  		  $('#id_ruc').val(data.serie);
			  $('#ruc_persona').val(data.codcliente);
			  $('#moneda').val(data.codmoneda).attr('selected', 'selected');
              $('#serie').val(data.serie);
			  $('#numero').val(data.correlativo);
	  		
	  	},
	  	error: function(response)
	  	{
	  		console.log(response);
	  	}
       });

	
}