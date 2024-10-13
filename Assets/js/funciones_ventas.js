$(document).ready(function()
{
//LISTAR CAMBIAR CONDICION DE VENTA
  $('#condicion').change(function(e) 
  {
        if ($(this).val() === "2") {
            $("#btnCuota").show();
            $("#btnPagar").hide();
        } else {
            $("#btnCuota").hide();
            $("#btnPagar").show();
        }
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
 
//enviar para resumen de boletas

  $('#btnenviaResumenCpe').click(function(e)
  {
		$('#cargando').modal('show');
		e.preventDefault();
		 var empresa = $('#empresa').val();
		 var f_ini = $('#f_ini').val();
		 
		 var action = 'resumen_cpe';

		  $.ajax({
		  	url: base_url+'/assets/ajax/ajax_venta1.php',
		  	type: "POST",
		  	async: true,
		  	data: {action:action,empresa:empresa,f_ini:f_ini},

			  	success: function(response)
			  	{
			  	    
			  	    swal.fire({
					title: response,
					text: "Por favor espere",
					
					showConfirmButton: true,
					allowOutsideClick: false
					});	/*
			  		$('#cargando').modal('hide');
			  		$('#exito').modal('show'); 
			  		 console.log(response);
			  		  location.reload(); */
			  	},
			  	error: function(response)
			  	{
			  		console.log(response);
			  	}
			  });
  });

/*generar resumen de bajas - boletas*/
 $("#GenerarBajaBoletas").click(function(e) 
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
			url  :  base_url+'/assets/ajax/ajax_venta1.php',
			type : "POST",
			async: true,
			data : $('#baja_boletas_cpe').serialize(),
			

			success: function(response)
			{
			    console.log(response);
              
			  	    swal.fire({
					title: response,
					text: "Por favor espere",
					
					showConfirmButton: true,
					allowOutsideClick: false
					});	/*
			  		$('#cargando').modal('hide');
			  		$('#exito').modal('show'); 
			  		 console.log(response);
			  		  location.reload(); */
			},
			error: function(response)
			{
            console.log(response);
          
			}

		});
	});

/*generar  bajas - facturas*/
 $("#GenerarBajaFacturas").click(function(e) 
	{
		
   
		swal.fire({
					title: "Cargando...",
					text: "Por favor espere",
					imageUrl: base_url+'/assets/js/ajax.gif',
					showConfirmButton: true,
					allowOutsideClick: false
					});
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_venta1.php',
			type : "POST",
			async: true,
			data : $('#baja_facturas_cpe').serialize(),
			

			success: function(response)
			{
			   Swal.fire({
                        title: response,
                        
                        showDenyButton: true,
                        showCancelButton: false,
                        confirmButtonText: "Aceptar",
                        denyButtonText: "Cancelar"
                        }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                        location.reload();
                        } else if (result.isDenied) {
                        location.reload();
                        }
                    });
              console.log(response);
              //location.reload();
			},
			error: function(response)
			{
			    swal.fire({
					title: response,
					text: "Procesado con error...!",
					
					showConfirmButton: true,
					allowOutsideClick: true
					});
            console.log(response);
          
			}

		});
	});


//cargar datos detalle nota de credito

$('#btnListar').click(function(e)
{
	e.preventDefault();
	 var id_venta_ref = $('#id_venta_ref').val();
	 var action = 'listarDetalle';
	 

	  $.ajax({
	  	url: base_url+'/assets/ajax/ajax_ventas.php',
	  	type: "POST",
	  	async: true,
	  	data: {action:action,id:id_venta_ref},

	  	success: function(response)
	  	{
	  		 var info = JSON.parse(response);
	  		 //console.log(info);
	  		 $('#detalleventa').html(info.detalle);
	  		 $("#btnListar").hide();
	  		 $("#btnGuardar").show();
	  	},
	  	error: function(response)
	  	{
	  		console.log(response);
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
		let timerInterval
		Swal.fire({
		  title: 'Procesando!',
		  html: 'Se cerrara en <b></b> milliseconds.',
		  timer: 500,
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
               // $('#error').modal('show'); 
                console.log(response);
			 }
			 else
			 {
			 	$('#cargando').modal('hide');
			    //$('#exito').modal('show'); 
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
	
//agregar pago

	$('#form_pago_cta').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_ventas.php',
			type : "POST",
			async: true,
			data : $('#form_pago_cta').serialize(),

			success: function(response)
			{
			    console.log(response);
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
	 $("#btnGuardarft").click(function(e) 
	{
		var user = document.getElementById('ruc_persona').value;
		if(user.length == 0)
		{
			Swal.fire({
								  icon: 'error',
								  title: 'Oops...',
								  text: 'No has escrito nada en el cliente  para facturar!',
				     		});
			document.venta_nueva.ruc_persona.focus()
			return 0;
     
		}
		var seriecpe = document.getElementById('serie').value;
		if(seriecpe.length == 0)
		{
			Swal.fire({
								  icon: 'error',
								  title: 'Oops...',
								  text: 'Seleccionado tipo de Documento...!',
				     		});
			document.venta_nueva.ruc_persona.focus()
			return 0;
     
		}
		if (document.venta_nueva.tip_cpe.selectedIndex==0){
      		Swal.fire({
								  icon: 'error',
								  title: 'Oops...',
								  text: 'No has seleccionado Tipo Doc. !',
				     		});
      		document.venta_nueva.tip_cpe.focus()
      		return 0;
   	}
    /*alertas para */
		swal.fire({
					title: "Cargando...",
					text: "Por favor espere",
					imageUrl: base_url+'/assets/js/ajax.gif',
					showConfirmButton: false,
					allowOutsideClick: false
					});
		e.preventDefault();

/*

                        <input type="hidden" id="relacionado_id" name="relacionado_id" value="0">
                        <input type="hidden" id="estadopagoanticipo" name="estadopagoanticipo" value="0">
                        <input type="hidden" id="relacionado_serie" name="relacionado_serie" value="">

*/


console.log('relacionado_id:'+$('#relacionado_id').val());
console.log('estadopagoanticipo:'+$('#estadopagoanticipo').val());
console.log('relacionado_serie:'+$('#relacionado_serie').val());

		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_venta1.php',
			type : "POST",
			async: true,
			data : $('#venta_nueva').serialize(),
		

			success: function(response)
			{
       console.log(response);
		var data1 = $.parseJSON(response);
        console.log(data1);
        var respuesta_sunat = data1.msj_sunat;
        var ticket = data1.lastInsertId;
        var empresa = data1.id_emp;
         //console.log(response);
         //procesado y elegir que hacer imprime ticket / pdf / retornar
Swal.fire({
title: respuesta_sunat,
showDenyButton: true,
showCancelButton: true,
confirmButtonColor: "#3085d6",
confirmButtonText: "Imprime Ticket",
denyButtonText: "Imprime A4"
}).then((result) => {
/* Read more about isConfirmed, isDenied below */
if (result.isConfirmed) 
{
window.open(base_url+'/ticket_factura/'+ticket, '_blank');
window.location = "ventas";
} 
else if (result.isDenied) 
{
     if(empresa == '20565728645')/*HTP LOGISTICA*/
     {
        window.open(base_url+'/factura_pdf3/'+ticket, '_blank');
        window.location = "ventas";   
     }
     else
     {
        window.open(base_url+'/factura_pdf/'+ticket, '_blank');
        window.location = "ventas";
     }
}
else
{
window.location = "ventas";
}
});
         
        /* swal.fire({
					title: respuesta_sunat,
					text: "Procesado con Exito...",
					
					showConfirmButton: true,
					allowOutsideClick: false
					});	*/
         
         
         //window.open(base_url+'/ticket_factura/'+ticket, '_blank');
         //window.location = "ventas";
         //location.reload();
        
			},
			error: function(response)
			{
          swal.fire({
					title: "Error al procesar el archivo...",
					text: response,
					
					showConfirmButton: true,
					allowOutsideClick: false
					});	
			}

		});
	});
		// agragar pos
  //$('#venta_nueva_pos').submit(function(e)
   $("#btnGuardar").click(function(e) 
	{
	    var user = document.getElementById('ruc_persona').value;
		if(user.length == 0)
		{
			Swal.fire({
								  icon: 'error',
								  title: 'No has seleccionado cliente...',
								  text: 'Hacer los pasos indicados!',
				     		});
			document.venta_nueva.ruc_persona.focus()
			return 0;
     
		}
	    /*var detalles = document.getElementById('detalles').value;
		if(detalles == 0)
		{
			Swal.fire({
								  icon: 'error',
								  title: 'Oops...',
								  text: 'No has agregado detalle a la venta...!',
				     		});
			document.venta_nueva.ruc_persona.focus()
			return 0;
     
		}*/
		swal.fire({
					title: "Cargando...",
					text: "Por favor espere",
					imageUrl: base_url+'/assets/js/ajax.gif',
					showConfirmButton: false,
					allowOutsideClick: false
					});
	
		e.preventDefault();
		$.ajax({
			/*url  :  base_url+'/assets/ajax/ajax_venta1.php',
			type : "POST",
			async: true,
			data : new FormData(this),
			contentType: false,
			cache: false,
			processData: false,*/
			
			url  :  base_url+'/assets/ajax/ajax_venta1.php',
			type : "POST",
			async: true,
			data : $('#venta_nueva_pos').serialize(),

			success: function(response)
			{
       console.log(response);
		var data1 = $.parseJSON(response);
        console.log(data1);
        var respuesta_sunat = data1.msj_sunat;
        var ticket = data1.lastInsertId;
        var empresa = data1.id_emp;
         //console.log(response);
         //procesado y elegir que hacer imprime ticket / pdf / retornar
Swal.fire({
title: respuesta_sunat,
showDenyButton: true,
showCancelButton: true,
confirmButtonColor: "#3085d6",
confirmButtonText: "Imprime Ticket",
denyButtonText: "Imprime Ticket 2"
}).then((result) => {
/* Read more about isConfirmed, isDenied below */
if (result.isConfirmed) 
{
window.open(base_url+'/ticket_factura/'+ticket, '_blank');
//window.location = "ventas";
location.reload();
} 
else if (result.isDenied) 
{
     if(empresa == '20565728645')/*HTP LOGISTICA*/
     {
        window.open(base_url+'/factura_pdf3/'+ticket, '_blank');
        //window.location = "ventas";   
        location.reload();
     }
     else
     {
        window.open(base_url+'/ticket_factura_pdf/'+ticket, '_blank');
        //window.location = "ventas";
        location.reload();
     }
}
else
{
//window.location = "ventas";
location.reload();
}
});
         
        /* swal.fire({
					title: respuesta_sunat,
					text: "Procesado con Exito...",
					
					showConfirmButton: true,
					allowOutsideClick: false
					});	*/
         
         
         //window.open(base_url+'/ticket_factura/'+ticket, '_blank');
         //window.location = "ventas";
         //location.reload();
        
			},
			error: function(response)
			{
				  swal.fire({
        	 type: "error",
        	 title: "No se pudo agregar el registro",
        	 showConfirmButton: true,
        	 confirmButtonText: "Cerrar"

             });

                    location.reload(); 
			}

		});
	});

// agragar nota de credito
  //$('#venta_nueva_nc').submit(function(e)
   $("#btnGuardarNC").click(function(e) 
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
		/*	url  :  base_url+'/assets/ajax/ajax_venta1.php',
			type : "POST",
			async: true,
			data : new FormData(this),
			contentType: false,
			cache: false,
			processData: false,*/
			
			url  :  base_url+'/assets/ajax/ajax_venta1.php',
			type : "POST",
			async: true,
			data : $('#venta_nueva_nc').serialize(),
			/*data : new FormData(this),
			contentType: false,
			cache: false,
			processData: false,*/

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
         window.open(base_url+'/ticket_factura/'+data1, '_blank');
         window.location = "ventas";
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
	// reenviar cpe a sunat
  $('#envia_venta').submit(function(e)
	{
		swal.fire({
					title: "Procesando documento...",
					text: "Por favor espere",
					imageUrl: base_url+'/assets/js/ajax.gif',
					showConfirmButton: false,
					allowOutsideClick: false
					});
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_venta1.php',
			type : "POST",
			async: true,
			data : new FormData(this),
			contentType: false,
			cache: false,
			processData: false,

			success: function(response)
			{
               console.log(response);
                var data1 = $.parseJSON(response);
                //console.log(data1);
                var respuesta_sunat = data1.msj_sunat;
                var cod_sunat       = data1.cod_sunat;
                
                console.log(response);
                    Swal.fire({
                        title: respuesta_sunat,
                        text: "Codigo - SUNAT: "+cod_sunat,
                        showDenyButton: true,
                        showCancelButton: false,
                        confirmButtonText: "Aceptar",
                        denyButtonText: "Cancelar"
                        }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                        location.reload();
                        } else if (result.isDenied) {
                        location.reload();
                        }
                    });
                
                //window.location = "ventas";
                //location.reload();
			},
			error: function(response)
			{
                Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "error al procesar..!",
               
                });
	    	}

		});
	});
	// agragar nota venta
 // $('#nota_nueva').submit(function(e)
   $("#btnGuardarNV").click(function(e) 
	{
		var user = document.getElementById('ruc_persona').value;
		if(user.length == 0)
		{
			Swal.fire({
								  icon: 'error',
								  title: 'Oops...',
								  text: 'No has escrito nada en el cliente!',
				     		});
			document.nota_nueva.ruc_persona.focus()
			return 0;
     
		}
		if (document.nota_nueva.tip_cpe.selectedIndex==0){
      		Swal.fire({
								  icon: 'error',
								  title: 'Oops...',
								  text: 'No has seleccionado Tipo Doc. !',
				     		});
      		document.nota_nueva.tip_cpe.focus()
      		return 0;
   	}
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax_venta1.php',
			type : "POST",
			async: true,
			data : $('#nota_nueva').serialize(),

			success: function(response)
			{
       $('#cargando').modal('hide');
			 $('#exito').modal('show'); 	
        //window.location = "ventas";
         console.log(response);
         var data1 = $.parseJSON(response);
         //console.log(data);
         //window.location = base_url+'/ticket_factura/'+data;
         window.open(base_url+'/ticket_factura/'+data1, '_blank');
         window.location = "nota_venta";
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
// editar nota venta
 // $('#nota_nueva').submit(function(e)
   $("#btnGuardarENV").click(function(e) 
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
			url  :  base_url+'/assets/ajax/ajax_venta1.php',
			type : "POST",
			async: true,
			data : $('#editar_nota_venta').serialize(),

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
         window.open(base_url+'/ticket_factura/'+data1, '_blank');
         window.location = "nota_venta";
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


$("#cbarras").keypress(function(e) {
if(e.which == 13) { teclas(1); }
});




});//end ready
/*********LISTAR PRODUCTOS POS************/
function listarproductospos()
{
	//e.preventDefault();
	var codpro = document.getElementById("cbarraspos").value;
	var action = 'listarPOS';

    $.ajax({
    url  :  base_url+'/assets/ajax/ajax_ventas.php',
    type : 'POST',
    async: true,
    data: {action:action,codpro:codpro},
    
    success: function(response)
    {
      var info = JSON.parse(response);
	  		 //console.log(info);
	 $('#resultados').html(info.detalle);
     console.log(response);
    
    },
    error: function(error)
    {
    console.log(response);
    }
    });
    
}

/****VALIDAR CPE*****/
function validarCpe(id)
{
    
    swal.fire({
				title: "Validando Comprobante en SUNAT...",
				text: "Por favor espere",
				imageUrl: base_url+'/assets/js/ajax.gif',
				showConfirmButton: false,
				allowOutsideClick: false
			});
    
    var action = 'validar_cpe_sunat';
    var id     = id;
    $.ajax({
	  	url: base_url+'/assets/ajax/ajax_validar_cpe.php',
	  	type: "POST",
	  	async: true,
	  	data: {action:action,id:id},

    	  	success: function(response)
    	  	{
    	  		
	  		var data = eval(response);
	  		console.log(data);
                Swal.fire({
                title: data.msj_sunat,
                text: "Datos procesados, revisar",
                icon: "success"
                });
	  	     	$('#ModalValidaCpe').modal('show');
	  	     	$('#idcpeconsulta').val(data.id_cpe);
    	  		$('#documento').val(data.doc_sunat);
    	  		$('#respuesta').val(data.msj_sunat);
    	  	
    	  		
    	        
    	  		 //location.reload(); 
    	  	},
    	  	error: function(response)
    	  	{
    	  		console.log(response);
    	  	}
	  	});
}

function cambiaEstado(estado)
{
        var estadox ='';
        var id_cpe = $("#idcpeconsulta").val();
        if(estado == '1')
        {
            estadox ='Aceptado';
        }
        else if(estado == '0')
        {
            estadox = 'Pendiente';
        }
        else
        {
          estadox = 'Anulado / Rechazado';   
        }
    //alert('el estado actual es '+id);
        Swal.fire({
        title: "¿Esta seguro de cambiar el estado del comprobante a "+estadox+"?",
        
        showCancelButton: true,
        confirmButtonText: "Actualizar estado"
        
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            /*cambiar estado*/
       
        var action = 'actualizarEstado';
        
			       
         $.ajax({
				url  :  base_url+'/assets/ajax/ajax_validar_cpe.php',
				type : 'POST',
				async: true,
				data: {action:action,estado:estado,id_cpe:id_cpe},
					success: function(response)								
					{
					 console.log(response);
           			Swal.fire(
           			{
                        title: "Actualizado con exito..!!!",
                        
                        showCancelButton: true,
                        confirmButtonText: "Aceptar"
                        
                        }).then((result) => 
                        {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed)
                        {
                            location.reload();
                        }
                        
            
                    });	

					},
					error: function(error)
					{

					}
			          });     
        /*fin cambiar estas*/
        } 
        });
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
  function cliente()
  {
  parametro = $('#modalCliente').modal('show');

  /*parametro.document.getElementById('1').value = "id";
  parametro.document.getElementById('2').value = "doc";
  parametro.document.getElementById('3').value = "nom";
  parametro.document.getElementById('4').value = "dir";*/

  }


  function enviacliente(id,doc,nom,dir,mail){

  document.venta_nueva.id_ruc.value = id;
  document.venta_nueva.ruc_persona.value = doc;
  document.venta_nueva.razon_social.value = nom;
  document.venta_nueva.razon_direccion.value = dir;
  document.venta_nueva.correo_cliente.value = mail;
  
  $('#modalCliente').modal('hide');
  }


  /////////////en nota de credito

  
  function cliente1()
  {
  parametro = $('#modalCliente').modal('show');

  /*parametro.document.getElementById('1').value = "id";
  parametro.document.getElementById('2').value = "doc";
  parametro.document.getElementById('3').value = "nom";
  parametro.document.getElementById('4').value = "dir";*/

  }


  function enviacliente1(id,doc,nom,dir){

  document.venta_nueva_nc.id_ruc.value = id;
  document.venta_nueva_nc.ruc_persona.value = doc;
  document.venta_nueva_nc.razon_social.value = nom;
  document.venta_nueva_nc.razon_direccion.value = dir;
  
  $('#modalCliente').modal('hide');
  }


    function cliente2()
  {
  parametro = $('#modalCliente').modal('show');

 /* parametro.document.getElementById('1').value = "id";
  parametro.document.getElementById('2').value = "doc";
  parametro.document.getElementById('3').value = "nom";
  parametro.document.getElementById('4').value = "dir";*/

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

  /*parametro.document.getElementById('1').value = "id";
  parametro.document.getElementById('2').value = "doc";
  parametro.document.getElementById('3').value = "nom";
  parametro.document.getElementById('4').value = "dir";*/

  }


  function enviacliente3(id,doc,nom,dir){

  document.venta_nueva_pos.id_ruc.value = id;
  document.venta_nueva_pos.ruc_persona.value = doc;
  document.venta_nueva_pos.razon_social.value = nom;
  document.venta_nueva_pos.razon_direccion.value = dir;
  
  $('#modalCliente').modal('hide');
  }



  function cliente4()
  {
  parametro = $('#modalCliente').modal('show');

  /*parametro.document.getElementById('1').value = "id";
  parametro.document.getElementById('2').value = "doc";
  parametro.document.getElementById('3').value = "nom";
  parametro.document.getElementById('4').value = "dir";*/

  }


  function enviacliente4(id,doc,nom,dir){

  document.editar_nota_venta.id_ruc.value = id;
  document.editar_nota_venta.ruc_persona.value = doc;
  document.editar_nota_venta.razon_social.value = nom;
  document.editar_nota_venta.razon_direccion.value = dir;
  
  $('#modalCliente').modal('hide');
  }

//////////////////////////////////////////////////////fin///////////////////////////////////

  //para detalle de venta


var cont=0;
var detalles=0;
var igv_unitario=0;
var edidetalle='';
var ediprecio='';
$("#btnGuardar").show();
$("#btnListar").hide();
//$("#btnPagar").hide();
$("#btnCuota").hide();

function agregar(id,nombre,precio,afectacion,precio_compra,factor,mxmn,calculaigv,edidetalle,ediprecio)
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
	if(ediprecio == 'NO')
	{
      ediprecio ='disabled="disabled"';
	}
	else
	{
		ediprecio ='';
	}

	if(edidetalle == 'NO')
	{
      edidetalle ='disabled="disabled"';
	}
	else
	{
      edidetalle ='';
	}
	

			var subtotal =cantidadu*precio+cantidad*factor*precio;
			var valor_unitario =subtotal/(1+$igv_unitario);
			var igv_u =valor_unitario*$igv_unitario;
			var fila='<tr id="fila'+cont+'">'+
	         '<td><button type="button" class="btn btn-danger" onclick="eliminar('+cont+')"><i class="fe fe-trash-2"></i></button></td>'+
	         '<td>'+cont+'</td>'+
	          '<td><input type="hidden" name="itemarticulo[]" value="'+cont+'"><input type="hidden" name="idarticulo[]" value="'+id+'"><input type="text" class="form-control w-100" name="nomarticulo[]" value="'+nombre+'" '+edidetalle+'><input type="hidden" name="mxmn[]" value="'+mxmn+'"></td>'+
	          '<td><input type="hidden" name="precio_compra[]" value="'+precio_compra+'"><input type="hidden" name="factor[]" value="'+factor+'"><input type="text" min="1" class="form-control input-sm" name="cantidad[]" id="cantidad[]" value="'+cantidad+'" onkeyup="modificarSubtotales()" required ></td>'+
	          '<td><input type="text" min="1" class="form-control input-sm" name="cantidadu[]" id="cantidadu[]" value="'+cantidadu+'" required onkeyup="modificarSubtotales()"></td>'+
	          '<td><input type="hidden" class="form-control input-sm" name="valor_unitario[]" id="valor_unitario[]" value="'+valor_unitario+'" readonly>'+
	          '<input type="hidden" id="cantidada'+cont+'" name="cantidada[]" class="form-control input-sm" >'+
	          '<input type="hidden" id="cantidadua'+cont+'" name="cantidadua[]" class="form-control input-sm">'+
	          '<input type="hidden" class="form-control input-sm" name="igv_unitario[]" id="igv_unitario[]" value="'+igv_u+'" readonly>'+
	          '<input type="text" class="form-control input-sm" name="precio_venta[]" id="precio_venta[]" value="'+precio+'" onkeyup="modificarSubtotales()" '+ediprecio+'></td>'+
	         '<td><span id="subtotal'+cont+'" name="subtotal">'+subtotal+'</span><input type="hidden" id="afectacion'+cont+'" name="afectacion[]" class="form-control input-sm" value="'+afectacion+'"></td>'+
	         '</tr>';

	    $('#tabla').append(fila);
	    reordenar();
	    modificarSubtotales();
	    calcaulaDt();
	    $('#detalles').val(detalles);
	    
}

function agregarpos(id,nombre,precio,afectacion,precio_compra,factor,mxmn,calculaigv,edidetalle,ediprecio)
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
	if(ediprecio == 'NO')
	{
      ediprecio ='disabled="disabled"';
	}
	else
	{
		ediprecio ='';
	}

	if(edidetalle == 'NO')
	{
      edidetalle ='disabled="disabled"';
	}
	else
	{
      edidetalle ='';
	}
	

			var subtotal =cantidadu*precio+cantidad*factor*precio;
			var valor_unitario =subtotal/(1+$igv_unitario);
			var igv_u =valor_unitario*$igv_unitario;
			var fila='<tr id="fila'+cont+'">'+
	         '<td><button type="button" class="btn btn-danger" onclick="eliminar('+cont+')"><i class="fe fe-trash-2"></i></button></td>'+
	         '<td>'+cont+'</td>'+
	          '<td><input type="hidden" name="itemarticulo[]" value="'+cont+'"><input type="hidden" name="idarticulo[]" value="'+id+'"><input type="text" class="form-control w-100" name="nomarticulo[]" value="'+nombre+'" '+edidetalle+' readonly><input type="hidden" name="mxmn[]" value="'+mxmn+'"></td>'+
	          '<td style="visibility:collapse; display:none;"><input type="hidden" name="precio_compra[]" value="'+precio_compra+'"><input type="hidden" name="factor[]" value="'+factor+'"><input type="text" min="1" class="form-control input-sm" name="cantidad[]" id="cantidad[]" value="'+cantidad+'" onkeyup="modificarSubtotales()" required ></td>'+
	          '<td><input type="text" min="1" class="form-control input-sm" name="cantidadu[]" id="cantidadu[]" value="'+cantidadu+'" required onkeyup="modificarSubtotales()"></td>'+
	          '<td><input type="hidden" class="form-control input-sm" name="valor_unitario[]" id="valor_unitario[]" value="'+valor_unitario+'" readonly>'+
	          '<input type="hidden" id="cantidada'+cont+'" name="cantidada[]" class="form-control input-sm" >'+
	          '<input type="hidden" id="cantidadua'+cont+'" name="cantidadua[]" class="form-control input-sm">'+
	          '<input type="hidden" class="form-control input-sm" name="igv_unitario[]" id="igv_unitario[]" value="'+igv_u+'" readonly>'+
	          '<input type="text" class="form-control input-sm" name="precio_venta[]" id="precio_venta[]" value="'+precio+'" onkeyup="modificarSubtotales()" '+ediprecio+' readonly></td>'+
	         '<td><span id="subtotal'+cont+'" name="subtotal">'+subtotal+'</span><input type="hidden" id="afectacion'+cont+'" name="afectacion[]" class="form-control input-sm" value="'+afectacion+'"></td>'+
	         '</tr>';

	    $('#tabla').append(fila);
	    reordenar();
	    modificarSubtotales();
	    calcaulaDt();
	    $('#detalles').val(detalles);
	    //alert(detalles);
	    
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
 $('#detalles').val(detalles);
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


		inpVu.value = (inpV.value*inpP.value*inpF.value +inpU.value*inpP.value)/(1+$igv_unitario);
		$('#valor_unitario').val(inpVu.value);

        inpI.value = inpVu.value*$igv_unitario;
        $('#igv_unitario').val(inpI.value);
        
	console.log(inpVu.value);

	}
calcularTotales();
redondear()
calcaulaDt();	
pagos();
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

       subt = (op_gravadas/1.18) + op_exoneradas + op_inafectas;
       $('#subt').val(subt);
       $('#igv').val(igv);
       tot = ((op_gravadas/1.18) + op_exoneradas + op_inafectas + (op_gravadas/1.18)*0.18).toFixed(2);
       $('#total').val(tot);
      $('#montopago').val(tot);
      $('#importe_pago_cuota').val(tot);
       evaluar();

       console.log(tot);
 	}


}
function redondear()
{
    var redondeo  = $('#redondeo').val();
	var total   = $('#total').val();
	var tot   = 0;
	var tot1   = Math.floor(total*10)/10;
	var tot2   = 0;
	tot2 = (total - tot1).toFixed(2);
	
	 $('#redondeo').val(tot2);
	 tot = (total - tot2).toFixed(2);
	 $('#total').val(tot);
	 $('#montopago').val(tot);
	
	
	

}
function evaluar()
{
	if(detalles>0)
	{
		$('#btnGuardar').show();

	}
	else if(detalles=0)
	{
		$('#btnGuardar').show();

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
	$('#btnGuardar').show();
}

function pagos()
{ 
	var mpago = document.getElementById("montopago").value;
	var efectivo = document.getElementById("efectivo").value;
  var visa = document.getElementById("visa").value;

  var dif = 0.00;

  dif = (mpago - efectivo - visa).toFixed(2);

  if(dif>0)
  {
  	$('#saldo').val(dif);
  	$('#vuelto').val('0.00');
  	$('#btnGuardar').show();
  }
  
  else
  {
  	$('#vuelto').val(dif);
  	$('#saldo').val('0.00');
  	$('#btnGuardar').show();
  }
}


/////////////////////////////////////funcion para jalar tipo de comprobante afectado por la nota de credito//////////////////////////////

  var par = '';
  
    var par = '';
  function documentrefpedido()
  {
  par = $('#modalDocrefpedido').modal('show');
  
  }
  function enviaclientepd(id,tip,nom,ser,num,codcli,ruc,raz,dir,opg,ope,opi,igv,tot){

  document.venta_nueva_pos.id_coti_ref.value = id;
  document.venta_nueva_pos.cod_doc_ref.value = tip;
  document.venta_nueva_pos.tip_ref.value = nom;
  document.venta_nueva_pos.serie_ref.value = ser;
  document.venta_nueva_pos.num_ref.value = num;
   
  document.venta_nueva_pos.id_ruc.value = codcli;
  document.venta_nueva_pos.ruc_persona.value = ruc;
  document.venta_nueva_pos.razon_social.value = raz;
  document.venta_nueva_pos.razon_direccion.value = dir;

  document.venta_nueva_pos.op_g.value = opg;
  document.venta_nueva_pos.op_e.value = ope;
  document.venta_nueva_pos.op_i.value = opi;
  document.venta_nueva_pos.igv.value = igv;
  document.venta_nueva_pos.total.value = tot;
  
  var id_venta_ref = id;
	var action       = 'listarDetallePedido';
	 

	  $.ajax({
	  	url: base_url+'/assets/ajax/ajax_ventas.php',
	  	type: "POST",
	  	async: true,
	  	data: {action:action,id:id_venta_ref},



	  	success: function(response)
	  	{
	  		//console.log(response);
	  		 var info = JSON.parse(response);
	  		 //console.log(info);
	  		 $('#detalleventa').html(info.detalle);
             reordenar();
	  		 modificarSubtotales();
	  		 //$("#btnListar").hide();
	  		 $("#btnGuardar").show();
	  	},
	  	error: function(response)
	  	{
	  		console.log(response);
	  	}
	  });


  
  
  $('#modalDocrefpedido').modal('hide');
  }
  
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


function enviaclientenc(id,tip,nom,ser,num,codcli,ruc,raz,dir,opg,ope,opi,igv,tot){

  document.venta_nueva_nc.id_venta_ref.value = id;
  document.venta_nueva_nc.cod_doc_ref.value = tip;
  document.venta_nueva_nc.tip_ref.value = nom;
  document.venta_nueva_nc.serie_ref.value = ser;
  document.venta_nueva_nc.num_ref.value = num;
   
  document.venta_nueva_nc.id_ruc.value = codcli;
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

	$('#example > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			$('#enviar_id').val(arr[0]);
			$('#ruc_id').val(arr[7]);

		});
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


/**
 * ANTICIPOS
 * 
 */

function openModalanticipos(){
	$('#modalDocrefpedido').modal('show');
}


function agregaranticipos(id,tip,nom,ser,num,codcli,ruc,raz,dir,opg,ope,opi,igv,tot){

var montoanticipo=$("#anticipo"+id).val();

if(montoanticipo=='0.00'){

Swal.fire('DEBE PONER UN ANTICIPO');

}else{

var factor=1;

$.ajax({
	  	url: base_url+'/assets/ajax/ajax_ventas.php',
	  	type: "POST",
		dataType : 'json',
	  	data: {action:'listaranticipo', id:id, montoanticipo:montoanticipo},
success: function(response){

console.log(response);

if(response.idanticipo=='3'){

Swal.fire('EL MONTO PAGADO EXCEDE AL SALDO');

}else{

$("#anticipo_saldo").val(response.saldo);
$("#anticipo_pago").val(response.pagado);
$("#anticipo_total").val(response.total);

$("#relacionado_serie").val(response.referencia);
$("#relacionado_id").val(response.id);
$("#estadopagoanticipo").val(response.idanticipo);

//id,doc,nom,dir,mail
enviacliente(response.clienteid, response.clientedoc, response.clientenom, response.clientedir, response.clientemail);

precio=montoanticipo;

/**/
if(response.afectacion==10){
$igv_unitario = 0.18;
}else{
$igv_unitario = 0.00;
}

cont++;
detalles++;
var cantidad = 1;
var cantidadu =0;
if(response.ediprecio == 'NO'){
ediprecio =' readonly ';
}else{
ediprecio ='';
}
if(response.edidetalle == 'NO'){
edidetalle =' readonly';
}else{
edidetalle ='';
}

precio_compra=precio;
mxmn='min';

var subtotal =parseFloat(cantidadu*precio+cantidad*factor*precio);
var valor_unitario =parseFloat(subtotal/(1+$igv_unitario));
var igv_u =parseFloat(valor_unitario*$igv_unitario);




var fila='<tr id="fila'+cont+'">'+
	         '<td><button type="button" class="btn btn-danger" onclick="eliminar('+cont+')"><i class="fe fe-trash-2"></i></button></td>'+
	         '<td>'+cont+'</td>'+
	          '<td><input type="hidden" name="itemarticulo[]" value="'+cont+'"><input type="hidden" name="idarticulo[]" value="'+response.idproducto+'"><input type="text" class="form-control w-100" name="nomarticulo[]" value="'+response.nombre+'" '+edidetalle+'><input type="hidden" name="mxmn[]" value="'+mxmn+'"></td>'+
	          '<td><input type="hidden" name="precio_compra[]" value="'+precio_compra+'"><input type="hidden" name="factor[]" value="'+factor+'"><input type="text" min="1" class="form-control input-sm" name="cantidad[]" id="cantidad[]" value="'+cantidad+'" onkeyup="modificarSubtotales()" required ></td>'+
	          '<td><input type="text" min="1" class="form-control input-sm" name="cantidadu[]" id="cantidadu[]" value="'+cantidadu+'" required onkeyup="modificarSubtotales()"></td>'+
	          '<td><input type="hidden" class="form-control input-sm" name="valor_unitario[]" id="valor_unitario[]" value="'+valor_unitario+'" readonly>'+
	          '<input type="hidden" id="cantidada'+cont+'" name="cantidada[]" class="form-control input-sm" >'+
	          '<input type="hidden" id="cantidadua'+cont+'" name="cantidadua[]" class="form-control input-sm">'+
	          '<input type="hidden" class="form-control input-sm" name="igv_unitario[]" id="igv_unitario[]" value="'+igv_u+'" readonly>'+
	          '<input type="text" class="form-control input-sm" name="precio_venta[]" id="precio_venta[]" value="'+precio+'" onkeyup="modificarSubtotales()" '+ediprecio+'></td>'+
	         '<td><span id="subtotal'+cont+'" name="subtotal">'+subtotal+'</span><input type="hidden" id="afectacion'+cont+'" name="afectacion[]" class="form-control input-sm" value="'+response.afectacion+'"></td>'+
	         '</tr>';

	    $('#tabla').append(fila);
	    reordenar();
	    modificarSubtotales();
	    calcaulaDt();
	    $('#detalles').val(detalles);


//$('#detalleventa').html(info.detalle);

//modificarSubtotales();
//$("#btnListar").hide();
$("#btnGuardar").show();

}


	  	},
	  	error: function(response)
	  	{
	  		console.log(response);
	  	}
	  });
  
  $('#modalDocrefpedido').modal('hide');

}

  
  }


/**
 * ANTICIPOS
 * 
 */

