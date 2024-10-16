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
			$('#update_soles').val(arr[4]);
			$('#update_dolares').val(arr[5]);
			$('#update_fecha').val(arr[6]);
			var clb_cliente = arr[2];
			$('#update_cliente').val(clb_cliente).attr('selected', 'selected');
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
