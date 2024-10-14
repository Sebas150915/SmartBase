$(document).ready(function(){


 	// agragar compra
  $('#frm-dt').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_compra.php',
			type : "POST",
			async: true,
			data : new FormData(this),
			contentType: false,
			cache: false,
			processData: false,

			success: function(response)
			{
       $('#cargando').modal('hide');
			 $('#exito').modal('show'); 	
       
         console.log(response);
         
         //location.reload();
			},
			error: function(response)
			{
          $('#cargando').modal('hide');
          $('#error').modal('show');
          location.reload(); 
			}

		});
	});

	//Buscar porcentaje de detraccion
	$('#det').change(function(e)
	{
		e.preventDefault();
		var detraccion = $(this).val();
    var action = 'searchDet';
		var cod = document.getElementById("det").value;

		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_compras.php',
			type: "POST",
			async: true,
			data: {action:action,detraccion:detraccion,cod:cod},
			success: function(response)
			{
			     console.log(response);
					var data = $.parseJSON(response);
					 if(response!='false')
					 {
					 	$('#por_det').val(data.por);
					 	calcaulaDt();				 	

					 }
					 else
					 {


					 }
			},
			error: function(error)
			{

			}
		});

	});

//buscar proveedor / cliente

//$('#ruc_persona').keyup(function(e){
$(document).on('keyup', '#ruc_persona', function(e)
{	
	e.preventDefault();
	var cl = $(this).val();
	var action = 'buscarPersona';

	$.ajax({
		url  :  base_url+'/assets/ajax/ajax_compras.php',
		type : 'POST',
		async: true,
		data: {action:action,cliente:cl},

					success: function(response)
						
			{
				
				if(response == 0)
				{
					
					$('#id_ruc').val('');
					$('#razon_social').val('');
					
					$('#razon_direccion').val('');
					
					
				}
				else
				{
					console.log(response);
					var data = $.parseJSON(response);
					$('#id_ruc').val(data.id_persona);
					$('#ruc_persona').val(data.num_doc);
					$('#razon_social').val(data.nombre_persona);
					$('#razon_direccion').val(data.direccion_persona);
					

				}

			},
			error: function(error)
			{

			}
	});
});

//agregar persona

	$('#form_add_cliente').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_compras.php',
			type : "POST",
			async: true,
			data : $('#form_add_cliente').serialize(),

			success: function(response)
			{

			 if(response == 'existe')
			 {
			 	$('#cargando').modal('hide');
                $('#error').modal('show'); 
                console.log(response);
			 }
			 else
			 {
			 	$('#cargando').modal('hide');
			    $('#exito').modal('show'); 
			    $('#ModalClientes').modal('hide');
                      
            //location.reload(); 
            //console.log(response);
			        console.log(response);
					var data = $.parseJSON(response);
					$('#id_ruc').val(data.id_persona);
					$('#ruc_persona').val(data.num_doc);
					$('#razon_social').val(data.nombre_persona);
					$('#razon_direccion').val(data.direccion_persona);
			 }
			 
            
			},
			error: function(response)
			{
             $('#cargando').modal('hide');
			}

		});
	});

	// agragar compra
  $('#venta_nueva').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_compra1.php',
			type : "POST",
			async: true,
			data : new FormData(this),
			contentType: false,
			cache: false,
			processData: false,

			success: function(response)
			{
       $('#cargando').modal('hide');
			 $('#exito').modal('show'); 	
        //window.location = "ventas";
         console.log(response);
         var data1 = $.parseJSON(response);
         //console.log(data);
         //window.location = base_url+'/ticket_factura/'+data;
         window.open(base_url+'/ticket_factura_compra/'+data1, '_blank');
         window.location = "compras";
         //location.reload();
			},
			error: function(response)
			{
          $('#cargando').modal('hide');
          $('#error').modal('show');
          location.reload(); 
			}

		});
	});

  	// agragar compra
  $('#nueva_nota_credito_compra').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_compra1.php',
			type : "POST",
			async: true,
			data : new FormData(this),
			contentType: false,
			cache: false,
			processData: false,

			success: function(response)
			{
       $('#cargando').modal('hide');
			 $('#exito').modal('show'); 	
        //window.location = "ventas";
         console.log(response);
         var data1 = $.parseJSON(response);
         //console.log(data);
         //window.location = base_url+'/ticket_factura/'+data;
         window.open(base_url+'/ticket_factura_compra/'+data1, '_blank');
         window.location = "compras";
         //location.reload();
			},
			error: function(response)
			{
          $('#cargando').modal('hide');
          $('#error').modal('show');
          location.reload(); 
			}

		});
	});


});//end ready


  var parametro = '';
  function cliente()
  {
  parametro = $('#modalCliente').modal('show');



  }


  function enviacliente(id,doc,nom,dir){

  document.venta_nueva.id_ruc.value = id;
  document.venta_nueva.ruc_persona.value = doc;
  document.venta_nueva.razon_social.value = nom;
  document.venta_nueva.razon_direccion.value = dir;
  
  $('#modalCliente').modal('hide');
  }

    function enviacliente2(id,doc,nom,dir){

  document.nueva_nota_credito_compra.id_ruc.value = id;
  document.nueva_nota_credito_compra.ruc_persona.value = doc;
  document.nueva_nota_credito_compra.razon_social.value = nom;
  document.nueva_nota_credito_compra.razon_direccion.value = dir;
  
  $('#modalCliente').modal('hide');
  }


  //////////////////////////////////////////////////////fin///////////////////////////////////

  //para detalle de compra


var cont=0;
var detalles=0;
var igv_unitario=0;
$("#btnGuardar").hide();
//$('#det').prop('readonly', 'readonly');

function agregarc(id,nombre,precio,afectacion,por1,por2,precio1,precio2,factor)
{

	if(afectacion==10)
	{
		$igv_unitario = 0.18;
	}

	else
	{
		$igv_unitario = 0.00;
	}

	cont++;
	detalles++;
	var cantidad = 1;
	
	var subtotal = (cantidad*precio).toFixed(2);
  var valor_unitario = (subtotal/(1+$igv_unitario)).toFixed(2);
  var igv_u = (valor_unitario*$igv_unitario).toFixed(2);
	var fila='<tr id="fila'+cont+'">'+
	         '<td><button type="button" class="btn btn-danger" onclick="eliminar('+cont+')"><i class="fa fa-trash"></i></button></td>'+
	         '<td>'+cont+'</td>'+
	          '<td><input type="hidden" name="fecven[]" value="0000-00-00"><input type="hidden" name="itemarticulo[]" value="'+cont+'"><input type="hidden" name="idarticulo[]" value="'+id+'"><input type="hidden" name="nomarticulo[]" value="'+nombre+'">'+nombre+'</td>'+
	          '<td><input type="text" min="1" class="form-control text-right" name="cantidad[]" id="cantidad[]" value="'+cantidad+'" onkeyup="modificarSubtotales()" ></td>'+
	           '<td><input type="text" min="1" class="form-control text-right" name="precio_venta[]" id="precio_venta[]" value="'+precio+'" onkeyup="modificarSubtotales()" ></td>'+
	            '<td><input type="text" min="1" class="form-control text-right" name="por1[]" id="por1[]" value="'+por1+'" onkeyup="modificarPrecioVenta()" ></td>'+
	            '<td><input type="text" min="1" class="form-control text-right" name="precio1[]" id="precio1[]" value="'+precio1+'"></td>'+
	            '<td><input type="text" min="1" class="form-control text-right" name="por2[]" id="por2[]" value="'+por2+'" onkeyup="modificarPrecioVenta()" ></td>'+
	            '<td><input type="text" min="1" class="form-control text-right" name="precio2[]" id="precio2[]" value="'+precio2+'" ></td>'+
	          '<td><span id="subtotal'+cont+'" name="subtotal">'+subtotal+'</span><input type="hidden" id="afectacion'+cont+'" name="afectacion[]" class="form-control" value="'+afectacion+'"><input type="hidden" id="afectacion'+cont+'" name="factor[]" class="form-control" value="'+factor+'"></td>'+
	         '</tr>';


	    $('#tabla').append(fila);
	    reordenar();
	    modificarSubtotales();
	    calcaulaDt();	
	    
	    
}



function eliminar(id)
    {
		$('#fila'+id).remove();
		reordenar();
		detalles=detalles-1;

		if(detalles>0)
		{
			modificarSubtotales();
		}
		else
		{
			limpiarTotales();
		}

	}

function reordenar()
	{
		var num=1;
		$('#tabla tbody tr').each(function(){
			$(this).find('td').eq(1).text(num);
			num++;
		});
	}

function modificarSubtotales()
{

	var cant=document.getElementsByName("cantidad[]");
	var prev=document.getElementsByName("precio_venta[]");
	var afec=document.getElementsByName("afectacion[]");
	var fac=document.getElementsByName("factor[]");
	var sub=document.getElementsByName("subtotal");

	for(var i=0; i< cant.length;i++)
	{
		var inpV=cant[i];
		var inpP=prev[i];
		var inpA=afec[i];
		var inpS=sub[i];

		inpS.value = (inpV.value*inpP.value);
		document.getElementsByName("subtotal")[i].innerHTML=inpS.value.toFixed(2);


	}
calcularTotales();
calcaulaDt();
modificarPrecioVenta();
}

function modificarPrecioVenta()
{

	var cos=document.getElementsByName("precio_venta[]");
	var por1=document.getElementsByName("por1[]");
	var por2=document.getElementsByName("por2[]");
	var pv=document.getElementsByName("precio1[]");
	var pvm=document.getElementsByName("precio2[]");
	var fac=document.getElementsByName("factor[]");

	for(var i=0; i< cos.length;i++)
	{
		var inpC=cos[i];
		var inpP1=por1[i];
		var inpP2=por2[i];
		var inpPv=pv[i];
		var inpPvm=pvm[i];
		var factm=fac[i];

		inpPv.value = ((parseFloat(inpC.value)+parseFloat(inpC.value*inpP1.value/100))*factm.value).toFixed(2);
		document.getElementsByName("precio1"+[i]).innerHTML=inpPv.value;

		inpPvm.value = ((parseFloat(inpC.value)+parseFloat(inpC.value*inpP2.value/100))*factm.value).toFixed(2);
		document.getElementsByName("precio2"+[i]).innerHTML=inpPvm.value;


	}

}

function calcularTotales()
{
	var op_gravadas   = 0.00;
	var op_exoneradas = 0.00;
	var op_inafectas  = 0.00;
	var subtotal      = 0.00;
 	var igv           = 0.00;
 	var total         = 0.00;
 	var sog           = 0.00;
  var subt          = 0.00;
  var tot           = 0.00;
 	var sub           = document.getElementsByName("subtotal");
   var afec=document.getElementsByName("afectacion[]");
 	for(var i=0; i< sub.length; i++)
 	{
 	    var inpA=afec[i];
      //var afe = document.getElementsByName("afectacion[]").value;

       if(inpA.value==10)
       {
       	op_gravadas +=  document.getElementsByName("subtotal")[i].value;
       }

       else if(inpA.value==20)
       {
       	op_exoneradas +=  document.getElementsByName("subtotal")[i].value;
       }
       else if(inpA.value==30)
       {
       	op_inafectas +=  document.getElementsByName("subtotal")[i].value;
       }

       sog = (op_gravadas/1.18).toFixed(2);
       igv = (sog*0.18).toFixed(2);
       $('#op_g').val(sog);
       $('#op_e').val(op_exoneradas);
       $('#op_i').val(op_inafectas);

       subt = ((op_gravadas/1.18) + op_exoneradas + op_inafectas).toFixed(2);
       $('#subt').val(subt);
       $('#igv').val(igv);
       tot = ((op_gravadas/1.18) + op_exoneradas + op_inafectas + (op_gravadas/1.18)*0.18).toFixed(2);
       $('#total').val(tot);
       evaluar();
 	}


}

function evaluar()
{
	if(detalles>0)
	{
		$('#btnGuardar').show();

	}
	else if(detalles=0)
	{
		$('#btnGuardar').hide();

	}

	var totx = $('#total').val();
	if(totx<700)
	{
		$('#det').prop('readonly', 'readonly');
	}
	else
	{
		$('#det').prop('disabled', false);
	}
}

function calcaulaDt()
{
	var det1  = $('#por_det').val();
	var tot   = $('#total').val();
	var dt    = 0.00;
	
	dt       = ((tot*det1)/100).toFixed(0);
	var saldo = (tot - dt).toLocaleString(undefined, { 
  minimumFractionDigits: 2, 
  maximumFractionDigits: 2 
});

 $('#imp_det').val(dt);

	$('#saldo_ft').val(saldo);
	
}

function limpiarTotales()
{
	$('#op_g').val('0.00');
	$('#op_e').val('0.00');
	$('#op_i').val('0.00');
	$('#subtotal').val('0.00');
	$('#igv').val('0.00');
	$('#total').val('0.00');
	$('#btnGuardar').hide();
}



//Función Listar
function listarcliente(){

tablacontribuentes=$('#datatable-contribuyente').dataTable({
		'processing': true,
      'serverSide': true,
		autoWidth: false,
		'serverMethod': 'post',
		"ajax":
				{
					url: base_url+'/assets/ajax/ajax.php',
					type : "post",
					data:{action:'listarclientes'},
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



function listarCompraCab(id)
{
	var id_venta_ref = id;
	var action       = 'listarCompraCab';
	
	  $.ajax({
	  	url: base_url+'/assets/ajax/ajax_compras.php',
	  	type: "POST",
	  	async: true,
	  	data: {action:action,id:id_venta_ref},



	  	success: function(response)
	  	{
	  		//console.log(response);
	  		 var info = JSON.parse(response);
	  		 //var datos = eval(response);
	  		 console.log(info);

				$('#op_g').val(id);
				$('#op_e').val(idempresa);
				/*$('#op_i').val(op_inafectas);
				$('#igv').val(igv);
				$('#total').val(total);*/
	  		
	  	},
	  	error: function(response)
	  	{
	  		console.log(response);
	  	}
	  });



	

	
}
