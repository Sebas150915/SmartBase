<?php 
require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();
//var_dump($_GET);
$op = $_GET['action'];

$nombres       = $_GET['ruc'];
$tipodoc       = $_GET['tip'];
$cargo         = $_GET['serie'];  
$numero        = $_GET['numero'];
$celular       = $_GET['fecha'];
$descripcion   = $_GET['total'];


$celular = date("d/m/Y", strtotime($celular));

switch ($op) 
{
        case 'valida_compra':
	    
		$ruc = '20493277490';
		$client_id = '8322c580-221c-44e4-a44c-60506c8046fe';
		$client_secret='KcoRl01d93Pvbk4wNB75QA==';

		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api-seguridad.sunat.gob.pe/v1/clientesextranet/'.$client_id.'/oauth2/token/',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => 'grant_type=client_credentials&scope=https%3A%2F%2Fapi.sunat.gob.pe%2Fv1%2Fcontribuyente%2Fcontribuyentes&client_id='.$client_id.'&client_secret='.$client_secret,
		CURLOPT_HTTPHEADER => array(
		'Content-Type: application/x-www-form-urlencoded',
		'Cookie: TS019e7fc2=014dc399cb7f2da4798917ed2e3602cfb864800e2c262473df6845a5a95a64b2ab6f2f3b05bf91001439b019cfeb8ccbe88ee24297'
		),
		));

		$responset = curl_exec($curl);

		curl_close($curl);

		$data = json_decode($responset);
		$token_access =  $data->access_token;
		//echo $token_access;
		////////////////////GENERAR CONSULTA ////////////////////////////////////
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.sunat.gob.pe/v1/contribuyente/contribuyentes/'.$ruc.'/validarcomprobante',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>'{
		"numRuc"       : "'.$nombres.'",
		"codComp"      : "'.$tipodoc.'",
		"numeroSerie"  : "'.$cargo.'",
		"numero"       : "'.$numero.'",
		"fechaEmision" : "'.$celular.'",
		"monto"        : "'.$descripcion.'"
		}',
		CURLOPT_HTTPHEADER => array(
		'Authorization: Bearer '. $token_access,
		'Content-Type: application/json'
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		$datos = json_decode($response,true);

		//print_r($datos);
		$rpta =  $datos['data'];

		$estadoCp = $rpta['estadoCp'];
		$estadoRuc ='';
		$condDomiRuc='';
         
		switch ($estadoCp) 
		{
		case '0': $estadoCp = 'NO EXISTE'; break;
		case '1': $estadoCp = 'ACEPTADO'; break;
		case '2': $estadoCp = 'ANULADO'; break;
		case '3': $estadoCp = 'AUTORIZADO'; break;
		case '4': $estadoCp = 'NO AUTORIZADO'; break;
		}

		if($estadoCp == 'ACEPTADO')
		{
		$estadoRuc = $rpta['estadoRuc'];

		switch ($estadoRuc) 
		{
		case '00': $estadoRuc = 'ACTIVO'; break;
		case '01': $estadoRuc = 'BAJA PROVISIONAL'; break;
		case '02': $estadoRuc = 'BAJA PROV. POR OFICIO'; break;
		case '03': $estadoRuc = 'SUSPENCION TEMPORAL'; break;
		case '10': $estadoRuc = 'BAJA DEFINITIVA'; break;
		case '11': $estadoRuc = 'BAJA DE OFICIO'; break;
		case '22': $estadoRuc = 'INHABILITADO-VENT.UNICA'; break;
		}



		$condDomiRuc = $rpta['condDomiRuc'];

		switch ($condDomiRuc) 
		{
		case '00': $condDomiRuc = 'HABIDO'; break;
		case '09': $condDomiRuc = 'PENDIENTE'; break;
		case '11': $condDomiRuc = 'POR VERIFICAR'; break;
		case '12': $condDomiRuc = 'NO HABIDO'; break;
		case '20': $condDomiRuc = 'NO HALLADO'; break;
		}
		}
		else
		{
		$estadoRuc = '';
		$condDomiRuc = '';
		}
     $data = array(
      "estadoCp"    => $estadoCp,
      "estadoRuc"   => $estadoRuc,
      "condDomiRuc" => $condDomiRuc
     );
	echo json_encode($data);
	break;
	

}





 ?>