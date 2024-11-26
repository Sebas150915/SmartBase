$(document).ready(function()
	
{
  
 //################CARGAR DESDE EXCEL COMPRAS##################################//
	$('#cargarCompra').submit(function(e)
	{
		Swal.fire({
				  icon: 'success',
				  title: 'Procesando...',
				  text: 'Espere un momento...!',
				  
				});
		
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax.php',
			type : "POST",
			async: true,
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData: false,

			success: function(response)
			{
			 Swal.fire({
				  icon: 'success',
				  title: response,
				  text: 'ok...!',
				  
				});
             console.log(response);
             //location.reload(); 
             //window.location = base_url+'/compras';
			},
			error: function(response)
			{
             Swal.fire({
				  icon: 'error',
				  title: response,
				  text: 'ok...!',
				  
				});
             console.log(response);
			}

		});
	});   
    
//################CARGAR DESDE EXCEL VENTAS##################################//
	$('#cargarVenta').submit(function(e)
	{
		Swal.fire({
				  icon: 'success',
				  title: 'Procesando...',
				  text: 'Espere un momento...!',
				  
				});
		
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax.php',
			type : "POST",
			async: true,
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData: false,

			success: function(response)
			{
			 Swal.fire({
				  icon: 'success',
				  title: response,
				  text: 'ok...!',
				  
				});
             console.log(response);
             //location.reload(); 
			},
			error: function(response)
			{
             Swal.fire({
				  icon: 'error',
				  title: response,
				  text: 'ok...!',
				  
				});
             console.log(response);
			}

		});
	});   

	    //##################################CREAR CONCEPTO GASTO##################################//
	$('#addConceptoGasto').submit(function(e)
	{
		
		swal.fire({
					title: "Cargando...",
					text: "Por favor espere",
					imageUrl: base_url+'/Assets/js/ajax.gif',
					showConfirmButton: false,
					allowOutsideClick: false
					});
		
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/Assets/ajax/ajax.php',
			type : "POST",
			async: true,
			data : $('#addConceptoGasto').serialize(),

			success: function(response)
			{
			            
             swal.fire({
        	 icon: "success",
        	 title: "Registro agregado con exito..!",
        	 showConfirmButton: true,
         	 confirmButtonText: "Cerrar"

		     });
		     console.log(response);
            location.reload(); 
            //window.location = 'articulos'
			},
			error: function(response)
			{
             swal.fire({
        	 type: "error",
        	 title: "No se pudo agregar el registro",
        	 showConfirmButton: true,
        	 confirmButtonText: "Cerrar"

             })
			}

		});
	});



			//################################## editar datos de empresa##################################//
	$('#datos_empresa').submit(function(e)
	{
		$('#cargando').modal('show');
		
		e.preventDefault();
		
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax.php',
			type : "POST",
			async: true,
			//data : $('#datos_empresa').serialize(),
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData: false,

			success: function(response)
			{ 
			 $('#cargando').modal('hide');
			 $('#exito').modal('show'); 		            
             console.log(response);
             location.reload(); 

			},
			error: function(response)
			{
			  $('#cargando').modal('hide');
              $('#error').modal('show');
              location.reload(); 
			}

		});
	});
			//################################## editar almacen##################################//
	$('#form_delete_almacen').submit(function(e)
	{
		$('#cargando').modal('show');
		
		e.preventDefault();
		
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax.php',
			type : "POST",
			async: true,
			data : $('#form_delete_almacen').serialize(),

			success: function(response)
			{ 
			 $('#cargando').modal('hide');
			 $('#exito').modal('show'); 		            
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
			  $('#cargando').modal('hide');
              $('#error').modal('show');
              location.reload(); 
			}

		});
	});

		//################################## editar almacen##################################//
	$('#form_almacen_editar').submit(function(e)
	{
		$('#cargando').modal('show');
		
		e.preventDefault();
		
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax.php',
			type : "POST",
			async: true,
			data : $('#form_almacen_editar').serialize(),

			success: function(response)
			{ 
			 $('#cargando').modal('hide');
			 $('#exito').modal('show'); 		            
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
			  $('#cargando').modal('hide');
              $('#error').modal('show');
              location.reload(); 
			}

		});
	});
	//################################## nuevo almacen##################################//
	$('#form_almacen').submit(function(e)
	{
		$('#cargando').modal('show');
		
		e.preventDefault();
		
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax.php',
			type : "POST",
			async: true,
			data : $('#form_almacen').serialize(),

			success: function(response)
			{ 
			 $('#cargando').modal('hide');
			 $('#exito').modal('show'); 		            
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
			  $('#cargando').modal('hide');
              $('#error').modal('show');
              location.reload(); 
			}

		});
	});
	//################################## nuevo serie  - usuario##################################//
	$('#form_serie_usuario').submit(function(e)
	{
		$('#cargando').modal('show');
		
		e.preventDefault();
		
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax.php',
			type : "POST",
			async: true,
			data : $('#form_serie_usuario').serialize(),

			success: function(response)
			{ 
			 $('#cargando').modal('hide');
			 $('#exito').modal('show'); 		            
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
			  $('#cargando').modal('hide');
              $('#error').modal('show');
              location.reload(); 
			}

		});
	});
	//################################## delete serie  - usuario##################################//
	$('#form_series_usuario_delete').submit(function(e)
	{
		$('#cargando').modal('show');
		
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax.php',
			type : "POST",
			async: true,
			data : $('#form_series_usuario_delete').serialize(),

			success: function(response)
			{ 
			 $('#cargando').modal('hide');
			 $('#exito').modal('show'); 		            
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
			  $('#cargando').modal('hide');
              $('#error').modal('show');
              location.reload(); 
			}

		});
	});
	//################################## delete serie ##################################//
	$('#form_series_delete').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax.php',
			type : "POST",
			async: true,
			data : $('#form_series_delete').serialize(),

			success: function(response)
			{ 
			 $('#cargando').modal('hide');
			 $('#exito').modal('show'); 		            
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
			  $('#cargando').modal('hide');
              $('#error').modal('show');
              location.reload(); 
			}

		});
	});
	//################################## editar serie ##################################//
	$('#form_series_update').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax.php',
			type : "POST",
			async: true,
			data : $('#form_series_update').serialize(),

			success: function(response)
			{ 
			 $('#cargando').modal('hide');
			 $('#exito').modal('show'); 		            
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
			  $('#cargando').modal('hide');
              $('#error').modal('show');
              location.reload(); 
			}

		});
	});
	//################################## nuevo serie ##################################//
	$('#form_series').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax.php',
			type : "POST",
			async: true,
			data : $('#form_series').serialize(),

			success: function(response)
			{ 
			 $('#cargando').modal('hide');
			 $('#exito').modal('show'); 		            
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
			  $('#cargando').modal('hide');
              $('#error').modal('show');
              location.reload(); 
			}

		});
	});
	//################################## editar usuario ##################################//
	$('#update_usuario').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax.php',
			type : "POST",
			async: true,
			data : $('#update_usuario').serialize(),

			success: function(response)
			{ 
			 $('#cargando').modal('hide');
			 $('#exito').modal('show'); 		            
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
			  $('#cargando').modal('hide');
              $('#error').modal('show');
              location.reload(); 
			}

		});
	});
	//################################## delete usuario ##################################//
	$('#delete_usuario').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax.php',
			type : "POST",
			async: true,
			data : $('#delete_usuario').serialize(),

			success: function(response)
			{ 
			 $('#cargando').modal('hide');
			 $('#exito').modal('show'); 		            
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
			  $('#cargando').modal('hide');
              $('#error').modal('show');
              location.reload(); 
			}

		});
	});

	//################################## nuevo usuario ##################################//
	$('#form_usuario').submit(function(e)
	{
		$('#cargando').modal('show');
		e.preventDefault();
		$.ajax({
			url  :  base_url+'/assets/ajax/ajax.php',
			type : "POST",
			async: true,
			data : $('#form_usuario').serialize(),

			success: function(response)
			{ 
			 $('#cargando').modal('hide');
			 $('#exito').modal('show'); 		            
             console.log(response);
             location.reload(); 
			},
			error: function(response)
			{
			  $('#cargando').modal('hide');
              $('#error').modal('show');
              location.reload(); 
			}

		});
	});
});/*fin ready*/


	
/****************************funciones*************************************/
function delusuario()
{
	$('#deleteUsuario').modal('show');

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

function ediusuario()
{
	$('#updateUsuario').modal('show');

	var arr = [];

	$('#dataTable-1 > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			$('#id_update').val(arr[1]);
			$('#nombre_update').val(arr[2]);
			$('#apellido_update').val(arr[3]);
			$('#user_update').val(arr[4]);
			
		});
}

function ediserie()
{
	$('#updateSerie').modal('show');

	var arr = [];

	$('#dataTable-1 > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			$('#update_id_serie').val(arr[1]);
			$('#update_serie').val(arr[4]);
			$('#update_correlativo').val(arr[5]);
			
		});
}


function delserie()
{
	$('#deleteSerie').modal('show');

	var arr = [];

	$('#dataTable-1 > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			$('#delete_id_serie').val(arr[1]);
			
			
		});
}

function delserieusuario()
{
	$('#deleteSerieUsuario').modal('show');

	var arr = [];

	$('#dataTable-1 > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			$('#delete_id_serie_usuario').val(arr[1]);
			
			
		});
}

/////////////////////almacenes


function edialmacen()
{
	$('#editarAlmacen').modal('show');

	var arr = [];

	$('#dataTable-1 > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			/*$('#update_id_almacen').val(arr[1]);
			$('#update_nombre').val(arr[2]);
			$('#update_direccion').val(arr[3]);*/

			var id = arr[1];
			var action = 'buscar_almacenx';
			$.ajax({
				  	url: base_url+'/assets/ajax/ajax.php',
				  	type: "POST",
				  	async: true,
				  	data: {action:action,id:id},

				  	success: function(response)
				  	{
				  		console.log(response);
				  		var data = $.parseJSON(response);
				  		$('#update_id_almacen').val(data.id);
						$('#update_nombre').val(data.nombre);
						$('#update_direccion').val(data.direccion);
						$('#update_codlocal').val(data.codlocal);
						
						$('#update_ubigeo').val(data.ubigeo).attr('selected', 'selected');
			           
				  	},
				  	error: function(response)
				  	{
				  		console.log(response);
				  	}
		  	 });

			
		});
}


function delalmacen()
{
	$('#deleteAlmacen').modal('show');

	var arr = [];

	$('#dataTable-1 > tbody > tr').click(function()
		{
			arr = $(this).find('td').map(function()
				{
					return this.innerHTML;
				}).get();
			$('#delete_id_almacen').val(arr[1]);
			
			
		});
}

function enviacorreo(id,cl)
{
	swal.fire(
	   {
	   	title: "Enviando Correo...",
		text: "Por favor espere",
		imageUrl: base_url+'/assets/js/mail_success.gif',
		showConfirmButton: false,
		allowOutsideClick: false
		});

	    var id = id;
	    var cl = cl;
	    var action = 'enviarCorreo';

		$.ajax({
		url: base_url+'/assets/ajax/ajax_correos.php',
		type: "POST",
	  	async: true,
	  	data: {action:action,id:id,cl:cl},

		success: function (response)
		{ 
			var data = $.parseJSON(response);
			console.log(data.mensaje);
			swal.fire({
        	 icon: "success",
        	 title: data.mensaje,
        	 showConfirmButton: true,
         	 confirmButtonText: "Cerrar"

		     });

		},
		error: function (response) { console.log(response); }
		});


}



function cargarDatosini(top1) {
    var action = 'Datosini'; // Define el parámetro 'action'
    var num = top1;

    $.ajax({
        url: base_url + '/assets/ajax/ajax.php', // Ruta al archivo PHP
        type: "POST",
        async: true,
        dataType: 'json', // Especifica que la respuesta será JSON
        data: { action: action, num: num }, // Enviar los datos al servidor
        success: function (response) {
            const tbody = $('#top-products');
            tbody.empty(); // Limpiar la tabla
            
            // Iterar sobre los datos y agregar filas dinámicamente
            response.forEach(item => {
                // Usa template literals para interpolar las variables
                const row = `
                    <tr>
                        <td>${item.idproducto}</td>
                        <td>${item.nombre}</td>
                        <td>${item.cantidad}</td>
                    </tr>`;
                tbody.append(row); // Agregar la fila a la tabla
            });

            console.log("Datos cargados exitosamente:", response);
        },
        error: function (error) {
            console.error("Error en la solicitud:", error);
        }
    });
}


function cargarDatosini1(top2) {
    var action = 'Datosini1'; // Define el parámetro 'action'
    var num = top2;

    $.ajax({
        url: base_url + '/assets/ajax/ajax.php', // Ruta al archivo PHP
        type: "POST",
        async: true,
        dataType: 'json', // Especifica que la respuesta será JSON
        data: { action: action, num: num }, // Enviar los datos al servidor
        success: function (response) {
            const tbody = $('#top-customers');
            tbody.empty(); // Limpiar la tabla
            
            // Iterar sobre los datos y agregar filas dinámicamente
            response.forEach(item => {
                // Usa template literals para interpolar las variables
                const row = `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.nombre}</td>
                        <td>${item.total}</td>
                    </tr>`;
                tbody.append(row); // Agregar la fila a la tabla
            });

            console.log("Datos cargados exitosamente:", response);
        },
        error: function (error) {
            console.error("Error en la solicitud:", error);
        }
    });
}


