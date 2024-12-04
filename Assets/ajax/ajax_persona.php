<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();

////////////carga desde excel/////////////
if($_POST['action']=='cargar_excel_producto')
{
    $mensaje['respuesta']='';
    $file = $_FILES['dataClientes']['name'];
    move_uploaded_file($_FILES['dataClientes']['tmp_name'],'excel/clientes.xlsx');
    
    require_once('../plugins/phpexcel/Classes/PHPExcel.php');
    $inputFileType = PHPExcel_IOFactory::identify('excel/productos.xlsx');
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load('excel/clientes.xlsx');
    $sheet = $objPHPExcel->getSheet(0); 
    $highestRow = $sheet->getHighestRow(); 
    $highestColumn = $sheet->getHighestColumn();
    $n=0;
    
    //$xlsx = new SimpleXLSX('../../excel/productos.xlsx');
    sleep(3);//retrasamos la petición 3 segundos
    //echo $file;//devolvemos el nombre del archivo para pintar la imagen
    $num=0;
    $k = 0;
    for ($row = 2; $row <= $highestRow; $row++)
    { 
    $num++; 
    if($num>0)
    {

    //IMPORTAMOS LOS ARTICULOS
$nombre=$sheet->getCell("A".$row)->getValue();
if($nombre!='') 
{
	
$tipodoc=$sheet->getCell("B".$row)->getValue();
if($tipodoc == '')
{
  /*  $mensaje['error'] = 'EL CAMPO TIPO DOC. NO PUEDE ESTAR VACIO';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();*/
   $tipodoc = '0';
}
$numdoc=$sheet->getCell("C".$row)->getValue();
if($numdoc == '')
{
    $mensaje['error'] = 'EL NUM DOCUMENTO NO PUEDE ESTAR VACIO';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}
$correo    = $sheet->getCell("D".$row)->getValue();
$direccion = $sheet->getCell("E".$row)->getValue();
if($direccion == '')
{
    $mensaje['error'] = 'LA DIRECCION NO PUEDE ESTAR VACIA';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}
$departamento = $sheet->getCell("F".$row)->getValue();
if($departamento == '')
{
    $mensaje['error'] = 'LA DIRECCION NO PUEDE ESTAR VACIA';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}
$provincia = $sheet->getCell("G".$row)->getValue();
if($provincia == '')
{
    $mensaje['error'] = 'LA PROVINCIA NO PUEDE ESTAR VACIA';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}
$distrito = $sheet->getCell("H".$row)->getValue();
if($distrito == '')
{
    $mensaje['error'] = 'EL DISTRITO NO PUEDE ESTAR VACIO';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}
$nombres = $sheet->getCell("I".$row)->getValue();
$appaterno = $sheet->getCell("J".$row)->getValue();
$apmaterno = $sheet->getCell("K".$row)->getValue();

$empresa  = $_SESSION['id_empresa'];

$query_pro = "SELECT * FROM tbl_contribuyente WHERE num_doc ='$numdoc' AND empresa = $empresa";
$resultado_pro=$connect->prepare($query_pro);
$resultado_pro->execute(); 
$num_reg_pro=$resultado_pro->rowCount();


	

if($num_reg_pro == 0)
{

$query = $connect->prepare("INSERT INTO tbl_contribuyente(nombre_persona,ap_paterno,ap_materno,nombres,direccion_persona,distrito,provincia,departamento,tipo_doc,num_doc,correo,empresa,zona,division) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?) ");
    $resultado = $query->execute([$nombre,$appaterno,$apmaterno,$nombres,$direccion,$distrito,$provincia,$departamento,$tipodoc,$numdoc,$correo,$empresa,$_POST['zona'],$_POST['division']]);
	$k++;
}

}	

}
$mensaje['respuesta'] = 'REGISTROS PROCESADOS : '.$num.' EN TOTAL';
$mensaje['procesados']  = 'SE CARGARON        : '.$k.'   EN TOTAL';
$mensaje['noprocesados']  = 'NO SE CARGARON     : '.($num - $k).'   EN TOTAL';
}
    
 
   $data = json_encode($mensaje,true);

   echo $data;

   exit();

}



////////////llamar datos mediante un json para editar pelicula/////////////
if($_POST['action']=='busca_cli')
{
   $emp = "SELECT * FROM tbl_empresas WHERE id_empresa=$_POST[id]";
   $resultado_emp  = $connect->prepare($emp);
   $resultado_emp->execute();
   $row_emp = $resultado_emp->fetch(PDO::FETCH_ASSOC);

   $data = json_encode($row_emp,true);

   echo $data;

   exit();

}


//####################################CREAR CLIENTE####################################////

if($_POST['action'] == 'addCliente')
{
	var_dump($_POST);
	$dni 	     = $_POST['dni'];
	$tipo_doc    = $_POST['tipo_doc'];
	$razon	     = $_POST['razon'];
	$direccion   = $_POST['direccion'];
	$distrito    = $_POST['distrito']; 
	$provincia   = $_POST['provincia']; 
	$departamento   = $_POST['departamento']; 
	$correo   = $_POST['correo'];
	$empresa  = $_POST['empresa'];
	$zona  = $_POST['zona'];
	$division  = $_POST['division'];

    	$query_select = "SELECT * FROM tbl_contribuyente WHERE num_doc = '$dni' AND empresa = $empresa";
		$resultado_select=$connect->prepare($query_select);
		$resultado_select->execute();
		$num_reg_select=$resultado_select->rowCount();
        
        if($num_reg_select >= 1)
        {
          $msg = 'contribuyente ya existe';
          echo $msg;
          exit;
        }

        else
        {
        	$query=$connect->prepare("INSERT INTO tbl_contribuyente(nombre_persona,direccion_persona,distrito,provincia,departamento,tipo_doc,num_doc,correo,empresa,zona,division) VALUES (?,?,?,?,?,?,?,?,?,?,?);");
		    $resultado=$query->execute([$razon,$direccion,$distrito,$provincia,$departamento,$tipo_doc,$dni,$correo,$empresa,$zona,$division]);
        }
 	


	if($resultado)
	{
		$msg='ok';
	}
	else
	{
		$msg='error1';
	}
	echo $msg;
	exit;
}

//####################################EDITAR CLIENTE####################################////
if($_POST['action'] == 'ediCliente')
{
	
	$cliente = $_POST['update_razon'];
	$correo = $_POST['update_correo'];
	$direccion = $_POST['update_direccion'];
	$departamento = $_POST['update_departamento'];
	$provincia = $_POST['update_provincia'];
	$distrito = $_POST['update_distrito'];
	$id     = $_POST['update_id'];

	$query=$connect->prepare("UPDATE tbl_contribuyente SET correo=?,direccion_persona=?,departamento=?,provincia=?,distrito=?,nombre_persona=? WHERE id_persona = ?");
	$resultado = $query->execute([$correo,$direccion,$departamento,$provincia,$distrito,$cliente,$id]);

	if($resultado)
	{
		$msg='ok';
	}
	else
	{
		$msg = 'error';
	}

	echo $msg;
	exit;


}



//####################################CREAR EMPRESA - CLIENTE####################################////

if($_POST['action'] == 'addClienteEmpresa')
{
	
	$dni 	     = $_POST['dni'];
	$tipo_doc    = $_POST['tipo_doc'];
	$razon	     = $_POST['razon'];
	$direccion   = $_POST['direccion'];
	$distrito    = $_POST['distrito']; 
	$provincia   = $_POST['provincia']; 
	$departamento   = $_POST['departamento']; 
	$correo   = $_POST['correo'];
	$empresa  = $_POST['empresa'];

    	$query_select = "SELECT * FROM tbl_empresas WHERE ruc = '$dni'";
		$resultado_select=$connect->prepare($query_select);
		$resultado_select->execute();
		$num_reg_select=$resultado_select->rowCount();
        
        if($num_reg_select >= 1)
        {
          $msg = 'contribuyente ya existe';
          echo $msg;
          exit;
        }

        else
        {
        	$query=$connect->prepare("INSERT INTO tbl_empresas(razon_social,ruc,direccion,distrito,provincia,departamento,correo) VALUES (?,?,?,?,?,?,?);");
		    $resultado=$query->execute([$razon,$dni,$direccion,$distrito,$provincia,$departamento,$correo]);
		    $lastInsertId = $connect->lastInsertId();

		     $query_alm = $connect->prepare("INSERT INTO tbl_almacen (nombre, direccion,empresa) VALUES (?,?,?) ");
		     $result_alm = $query_alm->execute(['ALMACEN DEMO','-',$lastInsertId]);
		     $lastInsertId2 = $connect->lastInsertId();

		     $query_cat = $connect->prepare("INSERT INTO tbl_categorias (nombre, cuenta_venta,cuenta_compra,empresa) VALUES (?,?,?,?) ");
		     $result_cat = $query_cat->execute(['SIN CATEGORIA','70111','60111',$lastInsertId]);


		     $query_cli = $connect->prepare("INSERT INTO tbl_contribuyente (nombre_persona, direccion_persona,tipo_doc,num_doc,empresa) VALUES (?,?,?,?,?) ");
		     $result_cli = $query_cli->execute(['CLIENTE FINAL','-','0','99999999',$lastInsertId]);

		     $query_mar = $connect->prepare("INSERT INTO tbl_marcas (nombre,empresa) VALUES (?,?) ");
		     $result_mar = $query_mar->execute(['SIN MARCA',$lastInsertId]);

		     $clave = 'admin';
		     $clave = md5($clave);
		     $query_usu = $connect->prepare("INSERT INTO tbl_usuarios (usuario,clave,nombre_personal,apellido_personal,id_empresa,almacen,perfil) VALUES (?,?,?,?,?,?,?) ");
		     $result_usu = $query_usu->execute(['admin',$clave,'ADMIN','SMART',$lastInsertId,$lastInsertId2,'1']);

		     $ruta_car = '../../sunat/'.$dni;

		     mkdir($ruta_car,0755);

		     $ruta_xml = $ruta_car.'/xml/';

		     mkdir($ruta_xml,0755);

		     $ruta_cdr = $ruta_car.'/cdr/';
		     mkdir($ruta_cdr,0755);

		     $ruta_qr = $ruta_car.'/qr/';
		     mkdir($ruta_qr,0755);

		     $ruta_pdf = $ruta_car.'/pdf/';
		     mkdir($ruta_pdf,0755);



        }
 	


	if($resultado)
	{
		$msg='ok creado con exito';
	}
	else
	{
		$msg='error1';
	}
	echo $msg;
	exit;
}


if($_POST['action']  == 'ediClienteEmpresa')
{
$query=$connect->prepare("UPDATE tbl_empresas SET direccion=?,departamento=?,provincia=?,distrito=?,correo=?, fecha_vencimiento =? WHERE id_empresa = ?");
	$resultado = $query->execute([$_POST['update_direccion'],$_POST['update_departamento'],$_POST['update_provincia'],$_POST['update_distrito'],$_POST['update_correo'],$_POST['update_fechav'],$_POST['update_id']]);

	if($resultado)
	{
		$msg='ok';
	}
	else
	{
		$msg = 'error';
	}

	echo $msg;
	exit;
}