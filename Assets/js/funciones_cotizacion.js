$(document).ready(function(){

// editar nota de pedido
 // $('#nota_nueva').submit(function(e)
   $("#btnGuardarENP").click(function(e) 
	{
		swal.fire({
					title: "Cargando...",
					text: "Por favor espere",
					imageUrl: base_url+'/assets/js/ajax.gif',
					showConfirmButton: false,
					allowOutsideClick: false
					});
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_cotizacion.php',
			type : "POST",
			async: true,
			data : $('#nota_pedido_editar').serialize(),

			success: function(response)
			{
       swal.fire({
					title: "Exito...",
					text: "Por favor espere",
					
					showConfirmButton: true,
					allowOutsideClick: false
					});		
        //window.location = "ventas";
         console.log(response);
         var data1 = $.parseJSON(response);
         //console.log(data);
         //window.location = base_url+'/ticket_factura/'+data;
         window.open(base_url+'/ticket_cotizacion/'+data1, '_blank');
         window.location = base_url+"/cotizacion";
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



//buscar x codigo de barras

		//$('#ruc_persona').keyup(function(e){
		$(document).on('keyup', '#cbarras', function(e)
		{	
			e.preventDefault();
			var pr = $(this).val();
			var action = 'buscarProducto';

			$.ajax({
				url  :  base_url+'/assets/ajax/ajax_ventas.php',
				type : 'POST',
				async: true,
				data: {action:action,producto:pr},

					success: function(response)
								
					{
						
						if(response == 0)
						{	
						  response='producto no existe';																	
						  console.log(response);
						}
						else
						{
							console.log(response);
							var data = $.parseJSON(response);
							//$('#id_ruc').val(data.id_persona);
							//alert(data.id+' '+data.nombre);
							agregar(data.id,data.nombre,data.precio_venta,data.afectacion,data.costo,data.factor,'MIN');
							$('#cbarras').val('');
							$('#cbarras').focus();						

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
				url  :  base_url+'/assets/ajax/ajax_ventas.php',
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
			url  :  base_url+'/assets/ajax/ajax_ventas.php',
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

//funciones para jalar tipo doc dependiendo el tipo de cpe

	//Buscar serie
	$('#tip_cpe').change(function(e)
	{
		e.preventDefault();
		var cl = $(this).val();
    var user = $('#vendedor').val();
    var emp  = $('#empresa').val();
		var action = 'searchSerie';
		var cod = document.getElementById("tip_cpe").value;

		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_ventas.php',
			type: "POST",
			async: true,
			data: {action:action,cliente:cl,usuario:user,empresa:emp,cod:cod},
			success: function(response)
			{
			     console.log(response);
					var data = $.parseJSON(response);
					 if(response!='false')
					 {
					 	$('#serie').val(data.serie);
					 	$('#numero').val(data.numero);

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
// agragar venta
  $('#cotizacion_nueva').submit(function(e)
	{
		setTimeout(function() {
    swal({
		        icon: 'success',
		        html: '<h5>Cargando!</h5>'
		    });
		}, 500);

		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_cotizacion.php',
			type : "POST",
			async: true,
			data : new FormData(this),
			contentType: false,
			cache: false,
			processData: false,

			success: function(response)
			{
       setTimeout(function() {
			    swal({
			        icon: 'success',
			        html: '<h5>Exito!</h5>'
			    });
			}, 500);	
        //window.location = "ventas";
         console.log(response);
         var data1 = $.parseJSON(response);
         //console.log(data);
         //window.location = base_url+'/ticket_factura/'+data;
         window.open(base_url+'/ticket_cotizacion/'+data1, '_blank');
         
         window.location = "cotizacion";
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

/////////////////////////////////////////funciones para jalar datos del clente / proveedor /////////////////////////////////
  var parametro = '';
  function cliente()
  {
  parametro = $('#modalCliente').modal('show');

  var idempresa = $('#empresa').val();
  var action    = 'listarClientes';

  $.ajax({
	  	url: base_url+'/assets/ajax/ajax_cotizacion.php',
	  	type: "POST",
	  	async: true,
	  	data: {action:action,idempresa:idempresa},

	  	success: function(response)
	  	{
	  		 console.log(response);
	  		 var info = JSON.parse(response);
	  		 console.log(info);
	  		 $('#detallecli').html(info.detalle);
	  		 
	  	},
	  	error: function(response)
	  	{
	  		console.log(response);
	  	}
	  });


  }


  function enviacliente(id,doc,nom,dir){

  document.cotizacion_nueva.id_ruc.value = id;
  document.cotizacion_nueva.ruc_persona.value = doc;
  document.cotizacion_nueva.razon_social.value = nom;
  document.cotizacion_nueva.razon_direccion.value = dir;
  
  $('#modalCliente').modal('hide');
  }


  /////////////en nota de credito

  
  function cliente1()
  {
  parametro = $('#modalCliente').modal('show');

  parametro.document.getElementById('1').value = "id";
  parametro.document.getElementById('2').value = "doc";
  parametro.document.getElementById('3').value = "nom";
  parametro.document.getElementById('4').value = "dir";

  }


  function enviacliente1(id,doc,nom,dir){

  document.cotizacion_nueva_nc.id_ruc.value = id;
  document.cotizacion_nueva_nc.ruc_persona.value = doc;
  document.cotizacion_nueva_nc.razon_social.value = nom;
  document.cotizacion_nueva_nc.razon_direccion.value = dir;
  
  $('#modalCliente').modal('hide');
  }


    function cliente2()
  {
  parametro = $('#modalCliente').modal('show');

  parametro.document.getElementById('1').value = "id";
  parametro.document.getElementById('2').value = "doc";
  parametro.document.getElementById('3').value = "nom";
  parametro.document.getElementById('4').value = "dir";

  }


  function enviacliente2(id,doc,nom,dir){

  document.nota_nueva.id_ruc.value = id;
  document.nota_nueva.ruc_persona.value = doc;
  document.nota_nueva.razon_social.value = nom;
  document.nota_nueva.razon_direccion.value = dir;
  
  $('#modalCliente').modal('hide');
  }

      function cliente3()
  {
  parametro = $('#modalCliente').modal('show');

  parametro.document.getElementById('1').value = "id";
  parametro.document.getElementById('2').value = "doc";
  parametro.document.getElementById('3').value = "nom";
  parametro.document.getElementById('4').value = "dir";

  }


  function enviacliente3(id,doc,nom,dir){

  document.cotizacion_nueva_pos.id_ruc.value = id;
  document.cotizacion_nueva_pos.ruc_persona.value = doc;
  document.cotizacion_nueva_pos.razon_social.value = nom;
  document.cotizacion_nueva_pos.razon_direccion.value = dir;
  
  $('#modalCliente').modal('hide');
  }

//////////////////////////////////////////////////////fin///////////////////////////////////

  //para detalle de venta


var cont=0;
var detalles=0;
var igv_unitario=0;
$('#btnGuardar').hide();

function agregar(id,nombre,precio,afectacion,precio_compra,factor,mxmn)
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
	var cantidad = 0;
	var cantidadu = 1;


	var subtotal = (cantidadu*precio+cantidad*factor*precio).toFixed(2);
    var valor_unitario = (subtotal/(1+$igv_unitario)).toFixed(2);
    var igv_u = (valor_unitario*$igv_unitario).toFixed(2);

    
	var fila='<tr id="fila'+cont+'">'+
	         '<td><button type="button" class="btn btn-danger" onclick="eliminar('+cont+')"><i class="fe fe-trash-2"></i></button></td>'+
	         '<td>'+cont+'</td>'+
	          '<td><input type="hidden" name="itemarticulo[]" value="'+cont+'"><input type="hidden" name="idarticulo[]" value="'+id+'"><input type="hidden" name="nomarticulo[]" value="'+nombre+'"><input type="hidden" name="mxmn[]" value="'+mxmn+'">'+nombre+'-'+mxmn+'</td>'+
	          '<td><input type="hidden" name="precio_compra[]" value="'+precio_compra+'"><input type="hidden" name="factor[]" value="'+factor+'"><input type="text" min="1" class="form-control input-sm" name="cantidad[]" id="cantidad[]" value="'+cantidad+'" onkeyup="modificarSubtotales()" required ></td>'+
	          '<td><input type="text" min="1" class="form-control input-sm" name="cantidadu[]" id="cantidadu[]" value="'+cantidadu+'" required onkeyup="modificarSubtotales()"></td>'+
	          '<td><input type="hidden" class="form-control input-sm" name="valor_unitario[]" id="valor_unitario[]" value="'+valor_unitario+'" readonly>'+
	          '<input type="hidden" class="form-control input-sm" name="igv_unitario[]" id="igv_unitario[]" value="'+igv_u+'" readonly>'+
	          '<input type="text" class="form-control input-sm" name="precio_venta[]" id="precio_venta[]" value="'+precio+'"></td>'+
	         '<td><span id="subtotal'+cont+'" name="subtotal">'+subtotal+'</span><input type="hidden" id="afectacion'+cont+'" name="afectacion[]" class="form-control input-sm" value="'+afectacion+'"></td>'+
	         '</tr>';


	    $('#tabla').append(fila);
	    reordenar();
	    modificarSubtotales();
	    
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
	var cantu=document.getElementsByName("cantidadu[]");
	var fac=document.getElementsByName("factor[]");
	var valu=document.getElementsByName("valor_unitario[]");
	var igvu=document.getElementsByName("igv_unitario[]");
	var prev=document.getElementsByName("precio_venta[]");
	var afec=document.getElementsByName("afectacion[]");
	var sub=document.getElementsByName("subtotal");

	for(var i=0; i< cant.length;i++)
	{
		var inpV=cant[i];
		var inpU=cantu[i];
		var inpF=fac[i];
		var inpVu=valu[i];
		var inpI=igvu[i];
		var inpP=prev[i];
		var inpA=afec[i];
		var inpS=sub[i];

		inpS.value = (inpV.value*inpP.value + inpU.value*inpP.value/inpF.value);
		document.getElementsByName("subtotal")[i].innerHTML=inpS.value.toFixed(2);

		if(inpA.value == '10')
		{
			$igv_unitario = 0.18;
		}
		else
		{
			$igv_unitario = 0.00;
		}

		inpVu.value = ((inpV.value*inpP.value*inpF.value +inpU.value*inpP.value)/(1+$igv_unitario)).toFixed(2);
		$('#valor_unitario').val(inpVu.value);

        inpI.value = (inpVu.value*$igv_unitario).toFixed(2);
        $('#igv_unitario').val(inpI.value);



	}
calcularTotales();

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
      $('#montopago').val(tot);
       evaluar();
 	}


}

function evaluar()
{
	if(detalles>0)
	{
		$('#btnGuardar').show();
		//$('#btnPagar').show();

	}
	else if(detalles=0)
	{
		$('#btnGuardar').hide();
		//$('#btnPagar').hide();

	}
}

function limpiarTotales()
{
	$('#op_g').val('0.00');
	$('#op_e').val('0.00');
	$('#op_i').val('0.00');
	$('#subtotal').val('0.00');
	$('#igv').val('0.00');
	$('#total').val('0.00');
	//$('#btnGuardar').hide();
}

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

