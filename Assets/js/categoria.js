function openModalEdit()
{

	$('#ModalCategoriaEdit').modal('show');

	var arr = [];

	$('#dataTable-1 > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			$('#update_id').val(arr[1]);
			$('#update_nombre').val(arr[2]);
			$('#update_cuenta_compra').val(arr[3]);
			$('#update_cuenta_venta').val(arr[4]);
		});
}



function openModalDel()
{
	$('#ModalCategoriaDlete').modal('show');

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


function cargarCategoria()
{
	    tablacategorias=$('#dataTable-1').dataTable({
			'processing': true,
			'serverSide': true,
			autoWidth: false,
			'serverMethod': 'post',
            "ajax":
				{
					url: base_url+'/assets/ajax/ajax_categoria.php',
					type : "post",
					data:{action:'cargarDatos'},
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
			"bDestroy": true,
			"iDisplayLength": 5,//Paginación
	   		"order": [[0, "desc" ]]//Ordenar (columna,orden)
       
    }).DataTable();


}