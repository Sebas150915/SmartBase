 function cliente2()
  {
  parametro = $('#modalCliente').modal('show');
  }


  function enviacliente2(id,doc,nom,dir)
  {

  document.nueva_retencion.id_ruc.value = id;
  document.nueva_retencion.ruc_persona.value = doc;
  document.nueva_retencion.razon_social.value = nom;
  //alert(id); 
 
  //document.gre_nueva.contribuyente.value = id;
  $('#contribuyente').val(id);
  //alert(dir);
  buscardestino(id);
  
  $('#modalCliente').modal('hide');
  }



var idventadet=0;
function agregadetalleret()
{
	idventadet=idventadet+1;
	var tipodoc=$("#tipdocrel").val();
	var serie=$("#serielrel").val();
	var numero=$("#numerorel").val();
	var moneda=$("#moneda").val();
	var total=$("#totalrel").val();
	var fecha=$("#fecharel").val();

	//alert(fecha);
    if(fecha==''){ Swal.fire('NO HA INGRESADO LA FECHA'); return true; }
	if(serie==''){ Swal.fire('NO HA INGRESADO LA SERIE'); return true; }
	if(numero==''){ Swal.fire('NO HA INGRESADO EL CORRELATIVO'); return true; }
	if(total=='0.00'){ Swal.fire('FALTA EL TOTAL DEL DOCUMENTO'); return true; }

	var t = $('#tabla').DataTable();
	var tdoc;
	var porcentaje='3.00';
	var retencion;
	var sub='0.00';

	retencion=parseFloat(total*3);
	retencion=parseFloat(retencion/100);
	retencion=retencion.toFixed(2);
	sub=parseFloat(total)-parseFloat(retencion);

	t.row.add( [
		idventadet,
		tipodoc,
                serie,
                numero,
                fecha,
                moneda,
                total,
                sub,
                porcentaje,
                retencion,
        sub
            ]).draw( false );
    totales();
    $("#serielrel").val('');
    $("#numerorel").val('');
    $("#totalrel").val('');
    $("#fecharel").val('');

}

function totales()
{

            var arrayIds = new Array();
            var contador = 0;
            var ids;
            var total=0;
            var subtotal=0;
            var comision=0;	
            
            var table = $('#tabla').DataTable();
             
            table.rows().eq(0).each( function ( index ) {
                var row = table.row( index );
             
                var data = row.data();
            
            subtotal=parseFloat(subtotal)+parseFloat(data[9]);
            total=parseFloat(total)+parseFloat(data[10]);	
                
            });
                
            subtotal=parseFloat(subtotal);
            total=parseFloat(total);
            
            sub_total=subtotal.toFixed(2);
            total=total.toFixed(2);
            
            $('#op_i').val(sub_total);
            $('#importeret').val(total);
                
}


function gnota(){
	
var tipoc = $('#tip_cpe').val();
var cliente=$("#id_ruc").val();

var empresad=0;
var empresa=0;
	
if(cliente=='0'){ Swal.fire('SELECCIONE EL CLIENTE'); return true; }
	
/*	
$('#agregar').attr("disabled", true);
$('#guardar').attr("disabled", true);
$('#eliminar').attr("disabled", true);*/
	
Swal.fire('GUARDANDO INFORMACIÓN!');
	
var DATA = [];	
var idv; 
var importe=0; 
var tipogf=0; 
var exoneradof=0;
var table = $('#tabla').DataTable();

table.rows().eq(0).each(function(index)
{
	
var detalle = {};	
    var row = table.row( index );
    var data = row.data();
//console.log(data);
idv=data[0];
detalle["txtID"]=data[0];
detalle["tipodoc"]=data[1];
detalle["seriedet"]=data[2];
detalle["numerodet"]=data[3];
detalle["fechadet"]=data[4];
detalle["monedadet"]=data[5];
detalle["importe"]=data[6];
detalle["icob"]=data[7];
detalle["porcentaje"]=data[8];
detalle["percepcion"]=data[9];
detalle["neto"]=data[10];

DATA.push(detalle);	
});

$.ajax({
url: base_url+'/assets/ajax/ajax_retencion.php',
type: "post",
dataType: 'json',
data: JSON.stringify({
"tip_cpe": $("#tip_cpe").val(),
"tipodocumento": $("#tipodocumento").val(),
"serie": $("#serie").val(),
"numero": $("#numero").val(),
"fecha": $("#fecha_emision").val(),
"tasa": $("#tasa").val(),
"total": $("#importeret").val(),
"percibido": $("#op_i").val(),	
"idcliente": $("#id_ruc").val(),	
"pago": $("#pago").val(),
"moneda" : $('#moneda').val(),
"regular": $("#regular").val(),						
"detalle": DATA
  }),
						
success: function (datos) {
swal.close();
console.log(datos);
//Swal.fire(datos.msj_sunat);

var ticket = datos.lastInsertId;


Swal.fire({
title: datos.msj_sunat,
showDenyButton: true,
showCancelButton: true,
confirmButtonColor: "#3085d6",
confirmButtonText: "Imprime Formato 1",
denyButtonText: "Imprime Formato 2"
}).then((result) => {
/* Read more about isConfirmed, isDenied below */
if (result.isConfirmed) 
{
window.open(base_url+'/retencion_pdf1/'+ticket, '_blank');

    if(empresad == '21' && perfil == '3')
    {
    window.location = "retencion";
    }
    else
    {
       window.location = "retencion"; 
    }
}
else if (result.isDenied) 
{
     if(empresa == '20565728645')/*HTP LOGISTICA*/
     {
        window.open(base_url+'/retencion_pdf1/'+ticket, '_blank');
        window.location = "retencion";   
     }
     else
     {
        window.open(base_url+'/retencion_pdf1/'+ticket, '_blank');
        
            if(empresad == '21' && perfil == '3')
            {
            window.location = "retencion";
            }
            else
            {
            window.location = "retencion"; 
            }
        
        
     }
}
else
{
        if(empresad == '21' && perfil == '3')
        {
        window.location = "retencion";
        }
        else
        {
        window.location = "retencion"; 
        }
}
});


},
error: function (data) {
console.log(data);
alert('Error Al conectar la Base Datos');
//console.log(data);
}
});

}