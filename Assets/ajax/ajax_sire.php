<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();

/////////////////////////datos de empresa //////////////////////////////////
if($_POST['action'] == 'sunat_sire')
{       
   
    $periodo = $_POST['anio'].$_POST['periodo'];
    //echo $periodo;
    $empresa = $_POST['empresa'];

    $query=$connect->prepare("DELETE FROM tbl_venta_sire  WHERE movper = ? and empid=?");
            $resultado = $query->execute([$periodo,$empresa]);


    $query_data = "SELECT * FROM tbl_empresas WHERE id_empresa=$empresa";
    $resultado = $connect->prepare($query_data);
    $resultado->execute();
    $row_empresa = $resultado->fetch(PDO::FETCH_ASSOC);

    //print_r($row_empresa);

    $idgre      = $row_empresa['idgre'];
    $secretgre  = $row_empresa['secretgre'];
    $usersunat  = $row_empresa['usuariosol'];
    $passsunat  = $row_empresa['clavesol'];
    $ruc        = $row_empresa['ruc'];

    /*$idsunat='ccc596ad-d522-46da-9527-7fc14bc73f25';
    $clavesunat='isj1CcvXppxpVC0j6HI46g==';
    $username='20493223641'.'TARNORTO';
    $passol='Ajjm2123@@';  */

    $idsunat     = $idgre  ;
    $clavesunat  = $secretgre;
    $username    = $ruc.$usersunat;
    $passol      = $passsunat;    

    $clave = str_replace(" ", "+", $clavesunat);
    $clave = str_replace("+", "%2B", $clave);
    $clave = str_replace("==", "%3D%3D", $clave);
    //$clave = str_replace("/", "%2F", $clave);
    //$clave=urldecode($clave);
    $mensaje['clave']=$clave;
    $mensaje['id']=$idsunat;

    //SOPE SUNAT
    $scope='https%3A%2F%2Fapi-cpe.sunat.gob.pe';
    //SOPE SIRE
    $scope='https%3A%2F%2Fapi-sire.sunat.gob.pe';

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api-seguridad.sunat.gob.pe/v1/clientessol/'.$idsunat.'/oauth2/token/',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => 'grant_type=password&scope='.$scope.'&client_id='.$idsunat.'&client_secret='.$clave.'&username='.$username.'&password='.$passol,
    CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded',
    'Cookie: TS019e7fc2=019edc9eb82dcd8fec0a3bd848e49fb99eec6d2c3bf4b04081df2d440b003ae1fb1930ddaa2a6bc63ebbdcca3f4f2ff9c2d23a32af'
    ),
    ));

    $response = curl_exec($curl);
    //var_dump($response);
    curl_close($curl);
    $response=json_decode($response);
    $token_access=$response->access_token;

    $codOrigenEnvio='1';
    $codLibro='140000';
    $codProceso='43';
    $codTipoArchivo='0';
    $fecha_inicio='01/10/2023';
    $fecha_fin='31/10/2023';
    $pagina='1';
    $cantidadpagina='100';
    $codTipoOpe='1';
    $periodo= $periodo;


    $urlsire2='https://api-sire.sunat.gob.pe/v1/contribuyente/migeigv/libros/rvie/propuesta/web/propuesta/'.$periodo.'/comprobantes?codTipoOpe='.$codTipoOpe.'&mtoDesde=&mtoHasta=&fecEmisionIni=&fecEmisionFin=&numDocAdquiriente=&codCar=&codTipoCDP=&codInconsistencia=&page='.$pagina.'&perPage='.$cantidadpagina;

    $curl = curl_init();
        curl_setopt_array($curl, array(
    CURLOPT_URL => $urlsire2,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '. $token_access,
            'Content-Type: application/json',
            'Accept: application/json'
          ),
        ));
            
    $response2 = curl_exec($curl);
    //echo    $token_access;
    $response2=json_decode($response2,true);
     
    //var_dump($response2['registros']);
    /*curl_close($curl);
    header('Content-type: application/json');
    echo json_encode($response2);*/

    $per_pag =  $response2['paginacion']['perPage'];
    $tot_reg =  $response2['paginacion']['totalRegistros'];

    $tot_pag = ceil($tot_reg/$per_pag);
    //echo $tot_pag;

    for($i=1;$i<=$tot_pag;$i++)
    {

    /*
    $idsunat='ccc596ad-d522-46da-9527-7fc14bc73f25';
    $clavesunat='isj1CcvXppxpVC0j6HI46g==';
    $username='20493223641'.'TARNORTO';
    $passol='Ajjm2123@@';  */

    $idsunat     = $idgre  ;
    $clavesunat  = $secretgre;
    $username    = $ruc.$usersunat;
    $passol      = $passsunat;  

    $clave = str_replace(" ", "+", $clavesunat);
    $clave = str_replace("+", "%2B", $clave);
    $clave = str_replace("==", "%3D%3D", $clave);
    //$clave = str_replace("/", "%2F", $clave);
    //$clave=urldecode($clave);
    $mensaje['clave']=$clave;
    $mensaje['id']=$idsunat;

    //SOPE SUNAT
    $scope='https%3A%2F%2Fapi-cpe.sunat.gob.pe';
    //SOPE SIRE
    $scope='https%3A%2F%2Fapi-sire.sunat.gob.pe';

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api-seguridad.sunat.gob.pe/v1/clientessol/'.$idsunat.'/oauth2/token/',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => 'grant_type=password&scope='.$scope.'&client_id='.$idsunat.'&client_secret='.$clave.'&username='.$username.'&password='.$passol,
    CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded',
    'Cookie: TS019e7fc2=019edc9eb82dcd8fec0a3bd848e49fb99eec6d2c3bf4b04081df2d440b003ae1fb1930ddaa2a6bc63ebbdcca3f4f2ff9c2d23a32af'
    ),
    ));

    $response = curl_exec($curl);
    //var_dump($response);
    curl_close($curl);
    $response=json_decode($response);
    $token_access=$response->access_token;

    $codOrigenEnvio='1';
    $codLibro='140000';
    $codProceso='43';
    $codTipoArchivo='0';
    $fecha_inicio='01/10/2023';
    $fecha_fin='31/10/2023';
    $pagina=$i;
    $cantidadpagina='100';
    $codTipoOpe='1';
    $periodo=$periodo;

    $urlsire2='https://api-sire.sunat.gob.pe/v1/contribuyente/migeigv/libros/rvie/propuesta/web/propuesta/'.$periodo.'/comprobantes?codTipoOpe='.$codTipoOpe.'&mtoDesde=&mtoHasta=&fecEmisionIni=&fecEmisionFin=&numDocAdquiriente=&codCar=&codTipoCDP=&codInconsistencia=&page='.$pagina.'&perPage='.$cantidadpagina;

    $curl = curl_init();
        curl_setopt_array($curl, array(
    CURLOPT_URL => $urlsire2,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '. $token_access,
            'Content-Type: application/json',
            'Accept: application/json'
          ),
        ));
            
    $response2 = curl_exec($curl);
    //echo    $token_access;
    $response2=json_decode($response2,true);
     
    //var_dump($response2['registros']);
    /*curl_close($curl);
    header('Content-type: application/json');
    echo json_encode($response2);*/


    foreach ($response2['registros'] as $data) 
    {
        $numdoc = $data['numCDP'];
        //echo $data['fecEmision'].'-';
        $date   = $data['fecEmision'];
        $d      = explode('/',$date);
        $date   = $d[2].'-'.$d[1].'-'.$d[0];
        $numdoc = str_pad($numdoc, 8, "0", STR_PAD_LEFT);
        $movkey = $data['numRuc'].'-'.$data['codTipoCDP'].'-'.$data['numSerieCDP'].'-'.$numdoc;

         /*buscar documento*/
                $query_bpro = "SELECT * FROM tbl_venta_sire WHERE movkey = '$movkey' ";
                $resultado_bpro = $connect->prepare($query_bpro);
                $resultado_bpro->execute();
                $num_reg_bpro=$resultado_bpro->rowCount();
               echo $query_bpro.';';
                /*fin buscar producto*/
                /*producto nuevo*/
                if($num_reg_bpro == 0)
                {
                    $query=$connect->prepare("INSERT INTO tbl_venta_sire(movkey,movtip,movser,movdoc,valexp,valgrav,valexo,valina,valigv,valtot,tipope,valmon,tipdoc,numdoc,movtca,movper,movfec,empid) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
                    $resultado=$query->execute([$movkey,$data['codTipoCDP'],$data['numSerieCDP'],$numdoc,$data['mtoValFactExpo'],$data['mtoBIGravada'],$data['mtoExonerado'],$data['mtoInafecto'],$data['mtoIGV'],$data['mtoTotalCP'],'0101',$data['codMoneda'],$data['codTipoDocIdentidad'],$data['numDocIdentidad'],$data['mtoTipoCambio'],$data['perPeriodoTributario'],$date ,$_POST['empresa']]);
                }
    }
}
    exit;
}


?>

