$(function()
{
	$('#botoncito').on('click', function(){
		var dni = $('#dni').val();
		var tip = $('#tipo_doc').val(); 
		var url = 'assets/ajax/consulta_sunat.php';
		$('#cargando').modal('show');
		$.ajax({
		type:'POST',
		url:url,
		data:'dni='+dni+'&tip='+tip,
	
		success: function(datos_dni)
		{
		$('#cargando').modal('hide');
	    //$('#exito').modal('show'); 

       if(datos_dni!='')
       {
       	
       	var datos = eval(datos_dni);
		//alert("exito");
		if(datos[1]=='1')
		{
			 var nombre = datos[2];
			 var nom    = nombre.split(' ');
			 //$('#ruc_persona').val(datos[0]);
			 $('#nombre1').val(nom[0]);
			 $('#nombre2').val(nom[1]);
			 $('#paterno').val(datos[3]);  
             $('#materno').val(datos[4]);
             $('#razon').val(datos[3]+' '+datos[4]+' '+datos[2]);
             $('#direccion').val('-');
             $("#paterno").prop("readonly", true);
            $("#materno").prop("readonly", true);
            $("#nombre1").prop("readonly", true);
            $("#nombre2").prop("readonly", true);
            $('#razon').prop("readonly", false);
		}
		else if(datos[1]=='6')
		{

            //$('#ruc_persona').val(datos[0]); 
            $('#razon').val(datos[2]);
            $('#direccion').val(datos[6]);
            $('#distrito').val(datos[9]);
            $('#provincia').val(datos[8]);
            $('#departamento').val(datos[7]);
            $("#paterno").val('');
            $("#materno").val('');
            $("#nombre1").val('');
            $("#nombre2").val('');
            $("#paterno").prop("readonly", true);
            $("#materno").prop("readonly", true);
            $("#nombre1").prop("readonly", true);
            $("#nombre2").prop("readonly", true);
         }
       }
       else
       {
       	alert("error");
       }

		
		
		console.log(datos_dni);
		}
		


				
	});
	return false;
	});
});
