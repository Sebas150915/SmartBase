function ediproducto()

{
	$('#edit').modal('show');

	var arr = [];

	$('#datatable-producto > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			/*$('#update_id').val(arr[1]);
			$('#update_nombre').val(arr[2]);
			$('#update_codigo').val(arr[3]);*/

			var id = arr[2];
			
			var action = 'buscar_productox';
              $.ajax({
	  	url: base_url+'/assets/ajax/ajax_producto.php',
	  	type: "POST",
	  	async: true,
	  	data: {action:action,id:id},
	  	
	  	success: function(response)
	  	{
	  		
	  		console.log(response);
	  		var data = $.parseJSON(response);
	  		$('#update_id').val(data.id);
			$('#update_nombre').val(data.nombre);
			$('#update_descripcion').val(data.descripcion);
			$('#update_precio_venta').val(data.precio_venta);
			$('#update_precio_venta2').val(data.precio2);
			$('#update_por_gan1').val(data.por1);
			$('#update_por_gan2').val(data.por2);

			$('#update_precio_compra').val(data.costo);

			$('#update_factor').val(data.factor);

			$('#update_cod_barras').val(data.sku);
		    $('#update_last_imagen').val(data.imagen);

			$('#update_dventa').val(data.venta);
			$('#update_stock1').val(data.stock);

             img = data.imagen;
			$('#update_marca').val(data.marca).attr('selected', 'selected');
            $('#update_categoria').val(data.categoria).attr('selected', 'selected');

			$('#update_unidad').val(data.unidad).attr('selected', 'selected');
			$('#update_afectacion').val(data.afectacion).attr('selected', 'selected');

		

		 $("#img1").attr("src",base_url+"/assets/images/products/"+img);		
	  		 
	  		 //location.reload(); 
	  	},
	  	error: function(response)
	  	{
	  		console.log(response);
	  	}
	  					});

		});
	
}

function delproducto()
{
	$('#deleteProducto').modal('show');

	var arr = [];

	$('#datatable-producto > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			$('#delete_id').val(arr[2]);
			
		});
}


function calcula_costo()
{
    var costo   = 0.00;
	var gpv     = 0.00;
	var pv      = 0.00;
	var gpvm    = 0.00;
 	var pvm     = 0.00;
 	var fac     = 0.00;


 	costo = document.getElementById("precio_compra").value;
 	gpv   = document.getElementById("por_gan1").value;
 	pv    = document.getElementById("precio_venta").value;
 	gpvm  = document.getElementById("por_gan2").value;
 	pvm   = document.getElementById("precio_venta2").value;
 	fac   = document.getElementById("factor").value;

    
    pv  = (fac*(parseFloat(costo) + (parseFloat(gpv)*parseFloat(costo)/100))).toFixed(2);
    pvm = (fac*(parseFloat(costo) + (parseFloat(gpvm)*parseFloat(costo)/100))).toFixed(2);

    $('#precio_venta').val(pv);
    $('#precio_venta2').val(pvm);
 	


}

function calcula_costo1()
{
    var costo   = 0.00;
	var gpv     = 0.00;
	var pv      = 0.00;
	var gpvm    = 0.00;
 	var pvm     = 0.00;
 	var fac     = 0.00;


 	costo = document.getElementById("update_precio_compra").value;
 	gpv   = document.getElementById("update_por_gan1").value;
 	pv    = document.getElementById("update_precio_venta").value;
 	gpvm  = document.getElementById("update_por_gan2").value;
 	pvm   = document.getElementById("update_precio_venta2").value;
 	fac   = document.getElementById("update_factor").value;

    
    pv  = (fac*(parseFloat(costo) + (parseFloat(gpv)*parseFloat(costo)/100))).toFixed(2);
    pvm = (fac*(parseFloat(costo) + (parseFloat(gpvm)*parseFloat(costo)/100))).toFixed(2);

    $('#update_precio_venta').val(pv);
    $('#update_precio_venta2').val(pvm);
 	


}

