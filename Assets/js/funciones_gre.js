$(document).ready(function()
{
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
		let timerInterval
		Swal.fire({
		  title: 'Procesando!',
		  html: 'Se cerrara en <b></b> milliseconds.',
		  timer: 2000,
		  timerProgressBar: true,
		  didOpen: () => {
		    Swal.showLoading()
		    const b = Swal.getHtmlContainer().querySelector('b')
		    timerInterval = setInterval(() => {
		      b.textContent = Swal.getTimerLeft()
		    }, 100)
		  },
		  willClose: () => {
		    clearInterval(timerInterval)
		  }
		}).then((result) => {
		  /* Read more about handling dismissals below */
		  if (result.dismiss === Swal.DismissReason.timer) {
		    console.log('I was closed by the timer')
		  }
		})
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
                //alert(response);
                console.log(response);
			 }
			 else
			 {
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
 // $('#venta_nueva').submit(function(e)
	 $("#btnGuardarGRE").click(function(e) 
	{
		var user = document.getElementById('ruc_persona').value;
		if(user.length == 0)
		{
			Swal.fire({
								  icon: 'error',
								  title: 'Oops...',
								  text: 'No has escrito nada en el Destinatario..!',
				     		});
			document.gre_nueva.ruc_persona.focus()
			return 0;
     
		}
		var cajas = document.getElementById('ncajas').value;
		if(cajas.length == 0)
		{
			Swal.fire({
								  icon: 'error',
								  title: 'Oops...',
								  text: 'Debe inrgesar informacion en N° de Cajas..!',
				     		});
			document.gre_nueva.ncajas.focus()
			return 0;
     
		}
	   /*	if (document.gre_nueva.tip_cpe.selectedIndex==0){
      		Swal.fire({
								  icon: 'error',
								  title: 'Oops...',
								  text: 'No has seleccionado Tipo Docx. !',
				     		});
      		document.gre_nueva.tip_cpe.focus()
      		return 0;
   	}

	if (document.gre_nueva.chofer.selectedIndex==0){
      		Swal.fire({
								  icon: 'error',
								  title: 'Oops...',
								  text: 'No has seleccionado Chofer. !',
				     		});
      		document.gre_nueva.tip_cpe.focus()
      		return 0;
   	}*/
    /*alertas para */
		swal.fire({
					title: "Cargando...",
					text: "Por favor espere",
					imageUrl: base_url+'/assets/js/ajax.gif',
					showConfirmButton: false,
					allowOutsideClick: false
					});
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_gre.php',
			type : "POST",
			async: true,
			data : $('#gre_nueva').serialize(),
			/*data : new FormData(this),
			contentType: false,
			cache: false,
			processData: false,*/

			success: function(response)
			{
             console.log(response);
             var data1 = $.parseJSON(response);
			 swal.fire({
					title: data1.msj_sunat,
					text: "Por favor espere",
					
					showConfirmButton: true,
					allowOutsideClick: false
					});	
        //window.location = "ventas";
         
         
        
        window.open(base_url+'/gre_pdf/'+data1.idgre);
        window.location = "gre";
         
      },
			error: function(response)
			{
          $('#cargando').modal('hide');
          $('#error').modal('show');
          //location.reload(); 
			}

		});
	});


		$("#cbarras").keypress(function(e) {
		if(e.which == 13) { teclas(1); }
		});





});//end ready

function documentorefgre()
{
   par = $('#modalDocrefGre').modal('show');

}

function enviadocgre(id,tipocomp,nomdoc,serie,correlativo,idcliente,num_doc,nombre_persona,direccion_persona,op_gravadas,op_exoneradas,op_inafectas,igv,total)
{
	$('#id_ruc').val(idcliente);
  $('#ruc_persona').val(num_doc);
  $('#razon_social').val(nombre_persona);
  var idcliente = idcliente;
  buscardestino(idcliente);
  
  $('#id_venta_ref').val(id);
  $('#cod_doc_ref').val(tipocomp);
  $('#tip_ref').val(nomdoc);
  $('#serie_ref').val(serie);
  $('#num_ref').val(correlativo);

  var id_venta_ref = id;
	var action       = 'listarDetalle';
	 

	  $.ajax({
	  	url: base_url+'/assets/ajax/ajax_gre.php',
	  	type: "POST",
	  	async: true,
	  	data: {action:action,id:id_venta_ref},



	  	success: function(response)
	  	{
	  		console.log(response);
	  		 var info = JSON.parse(response);
	  		 console.log(info);
	  		 $('#detalleventa').html(info.detalle);
	  		 //$("#btnListar").hide();
	  		 //$("#btnGuardar").show();
	  	},
	  	error: function(response)
	  	{
	  		console.log(response);
	  	}
	  });



	$('#op_g').val(op_gravadas);
	$('#op_e').val(op_exoneradas);
	$('#op_i').val(op_inafectas);
	$('#igv').val(igv);
	$('#total').val(total);

	$('#modalDocrefGre').modal('hide');
}

    function teclas(event)
    {
       
      var pr = $('#cbarras').val();
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
       
     
       
    }



/////////////////////////////////////////funciones para jalar datos del clente / proveedor /////////////////////////////////
  var parametro = '';
  
    function cliente2()
  {
  parametro = $('#modalCliente').modal('show');
  }


  function enviacliente2(id,doc,nom,dir)
  {

  document.gre_nueva.id_ruc.value = id;
  document.gre_nueva.ruc_persona.value = doc;
  document.gre_nueva.razon_social.value = nom;
  //alert(dir);
  buscardestino(id);
  
  $('#modalCliente').modal('hide');
  }

  function buscardestino(id)
  {
  	var id = id;
  	var destinos = $('#pllegada').val();
  	var action   = 'buscar_destino';

  	$.ajax({

  		  url  :  base_url+'/assets/ajax/ajax_gre.php',
				type : 'POST',
				async: true,
				data: {action:action,id:id},

				success: function(response)
								
					{
						
						console.log(response);
						 $("#pllegada").html(response);

					},
					error: function(error)
					{
           console.log(error);
					}

  	});
  }

//////////////////////////////////////////////////////fin///////////////////////////////////

  //para detalle de venta


var cont=0;
var detalles=0;
var igv_unitario=0;
$("#btnGuardar").hide();
$("#btnListar").hide();
//$("#btnPagar").hide();
$("#btnCuota").hide();

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
	var cantidadu = 0;
	

	var subtotal = (cantidadu*precio+cantidad*factor*precio).toFixed(2);
    var valor_unitario = (subtotal/(1+$igv_unitario)).toFixed(2);
    var igv_u = (valor_unitario*$igv_unitario).toFixed(2);
	var fila='<tr id="fila'+cont+'">'+
	         '<td><button type="button" class="btn btn-danger" onclick="eliminar('+cont+')"><i class="fe fe-trash-2"></i></button></td>'+
	         '<td>'+cont+'</td>'+
	          '<td><input type="hidden" name="itemarticulo[]" value="'+cont+'"><input type="hidden" name="idarticulo[]" value="'+id+'"><input type="hidden" name="nomarticulo[]" value="'+nombre+'"><input type="hidden" name="mxmn[]" value="'+mxmn+'"><input class="form-control" name="nomproducto" id="nomproducto" value="'+nombre+'" ></td>'+
	          '<td><input type="hidden" name="precio_compra[]" value="'+precio_compra+'"><input type="hidden" name="factor[]" value="'+factor+'"><input type="text" min="1" class="form-control input-sm" name="cantidad[]" id="cantidad[]" value="'+cantidad+'" onkeyup="modificarSubtotales()" required ></td>'+
	          '<td><input type="text" min="1" class="form-control input-sm" name="cantidadu[]" id="cantidadu[]" value="'+cantidadu+'" required onkeyup="modificarSubtotales()"></td>'+
	          '<td><input type="hidden" class="form-control input-sm" name="valor_unitario[]" id="valor_unitario[]" value="'+valor_unitario+'" readonly>'+
	          '<input type="hidden" id="cantidada'+cont+'" name="cantidada[]" class="form-control input-sm" >'+
	          '<input type="hidden" id="cantidadua'+cont+'" name="cantidadua[]" class="form-control input-sm">'+
	          '<input type="hidden" class="form-control input-sm" name="igv_unitario[]" id="igv_unitario[]" value="'+igv_u+'" readonly>'+
	          '<input type="text" class="form-control input-sm" name="precio_venta[]" id="precio_venta[]" value="'+precio+'" readonly></td>'+
	         '<td><span id="subtotal'+cont+'" name="subtotal">'+subtotal+'</span><input type="hidden" id="afectacion'+cont+'" name="afectacion[]" class="form-control input-sm" value="'+afectacion+'"></td>'+
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
calcaulaDt();	

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
      $('#importe_pago_cuota').val(tot);
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


/////////////////////////////////////funcion para jalar tipo de comprobante afectado por la nota de credito//////////////////////////////

  var par = '';
  function documentoref()
  {
  par = $('#modalDocref').modal('show');
  
  parametro.document.getElementById('1').value = "id";
  parametro.document.getElementById('2').value = "tip";
  parametro.document.getElementById('3').value = "nom";
  parametro.document.getElementById('4').value = "ser";
  parametro.document.getElementById('5').value = "num";
  parametro.document.getElementById('6').value = "ruc";
  parametro.document.getElementById('7').value = "raz";
  parametro.document.getElementById('8').value = "dir";
  parametro.document.getElementById('9').value = "opg";
  parametro.document.getElementById('10').value = "ope";
  parametro.document.getElementById('11').value = "opi";
  parametro.document.getElementById('12').value = "igv";
  parametro.document.getElementById('13').value = "tot";


  }


  function enviaclientenc(id,tip,nom,ser,num,ruc,raz,dir,opg,ope,opi,igv,tot){

  document.venta_nueva_nc.id_venta_ref.value = id;
  document.venta_nueva_nc.cod_doc_ref.value = tip;
  document.venta_nueva_nc.tip_ref.value = nom;
  document.venta_nueva_nc.serie_ref.value = ser;
  document.venta_nueva_nc.num_ref.value = num;
  document.venta_nueva_nc.ruc_persona.value = ruc;
  document.venta_nueva_nc.razon_social.value = raz;
  document.venta_nueva_nc.razon_direccion.value = dir;

  document.venta_nueva_nc.op_g.value = opg;
  document.venta_nueva_nc.op_e.value = ope;
  document.venta_nueva_nc.op_i.value = opi;
  document.venta_nueva_nc.igv.value = igv;
  document.venta_nueva_nc.total.value = tot;
  
  $('#modalDocref').modal('hide');
  }


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
			$('#ruc_id').val(arr[4]);

		});
}
