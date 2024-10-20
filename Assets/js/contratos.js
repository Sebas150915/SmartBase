function openModalEdit()
{

	$('#ModalContratoEdit').modal('show');

	var arr = [];

	$('#dataTable-1 > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			$('#update_id').val(arr[1]);
			$('#update_cliente').val(arr[2]).attr('selected', 'selected');
			$('#update_fcontrato').val(arr[4]);
			$('#update_finialquiler').val(arr[5]);
			$('#update_fvencimiento').val(arr[6]);
			$('#update_moneda').val(arr[7]);
			$('#update_tcambio').val(arr[8]);
			$('#update_importesoles').val(arr[9]);
			$('#update_importedolar').val(arr[10]);
			$('#update_obs').val(arr[11]);
		});
}


function openModalDel()
{
	$('#ModalCategoriaDlete').modal('show');

	var arr = [];

	$('#dataTable > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			$('#delete_id').val(arr[1]);
			
		});
}
