function openModalEdit()
{
	$('#bajaModal').modal('show');

	var arr = [];

	$('#datatable-ventas > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			$('#idretencion').val(arr[1]);
			$('#fecharet').val(arr[2]);
			
		});
}