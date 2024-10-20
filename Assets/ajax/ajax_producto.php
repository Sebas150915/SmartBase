<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();

////////////carga desde excel/////////////
if($_POST['action']=='cargar_excel_producto')
{
    $mensaje['respuesta']='';
    $empresa  = $_SESSION['id_empresa'];
    $file = $_FILES['dataProductos']['name'];
    move_uploaded_file($_FILES['dataProductos']['tmp_name'],'excel/productos.xlsx');
    
    require_once('../plugins/phpexcel/Classes/PHPExcel.php');
    $inputFileType = PHPExcel_IOFactory::identify('excel/productos.xlsx');
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load('excel/productos.xlsx');
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
if($nombre!='') {
	
$marca=$sheet->getCell("B".$row)->getValue();
if($marca == '')
{
    $mensaje['error'] = 'LA MARCA NO PUEDE ESTAR VACIA';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}
$categoria=$sheet->getCell("C".$row)->getValue();
if($categoria == '')
{
    $mensaje['error'] = 'LA CATEGORIA NO PUEDE ESTAR VACIA';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}
$ctaventa=$sheet->getCell("D".$row)->getValue();
if($ctaventa == '')
{
    $mensaje['error'] = 'LA CUENTA DE VENTA NO PUEDE ESTAR VACIA';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}
$ctacompra=$sheet->getCell("E".$row)->getValue();
if($ctacompra==''){ $ctacompra='60111'; }
$factor=$sheet->getCell("F".$row)->getValue();
if($factor == '' ||  $factor<1)
{
    $mensaje['error'] = 'EL FACTOR NO PUEDE SER VACIO O MENOR A 1';
    $data = json_encode($mensaje,true);
   echo $data;
   exit();
}
$unidad=$sheet->getCell("G".$row)->getValue();
$stock=$sheet->getCell("H".$row)->getValue();
$precio=$sheet->getCell("I".$row)->getValue();
$afectacion=$sheet->getCell("J".$row)->getValue();
$estado=$sheet->getCell("K".$row)->getValue();
$sku=$sheet->getCell("L".$row)->getValue();
if($precio==''){ $precio='0'; }

$query_pro = "SELECT * FROM tbl_productos WHERE nombre ='$nombre' AND empresa = $empresa ";
$resultado_pro=$connect->prepare($query_pro);
$resultado_pro->execute(); 
$num_reg_pro=$resultado_pro->rowCount();

if($stock==''){ $stock='0'; }
	

if($num_reg_pro == 0)
{
    $query_cat = "SELECT * FROM tbl_categorias WHERE nombre ='$categoria' AND empresa = $empresa ";
    $resultado_cat=$connect->prepare($query_cat);
    $resultado_cat->execute(); 
    $row_cat = $resultado_cat->fetch(PDO::FETCH_ASSOC);
    $num_reg_cat=$resultado_cat->rowCount();
    //echo $categoria;
    if($num_reg_cat == 0)
    {
    $query=$connect->prepare("INSERT INTO tbl_categorias(nombre,empresa,cuenta_venta,cuenta_compra) VALUES (?,?,?,?);");
    $resultado=$query->execute([$categoria,$_SESSION['id_empresa'],$ctaventa,$ctacompra]);
    $lastInsertId = $connect->lastInsertId();
    }
    else
    {
      $lastInsertId =  $row_cat['id'];
     
    }
    //echo $unidad;
    $query = $connect->prepare("INSERT INTO tbl_productos(nombre,descripcion,marca,unidad,unidadu,precio_venta,afectacion,empresa,precio2,costo,por1,por2,factor,sku,imagen,venta,categoria,stock) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ");
    $resultado = $query->execute([$nombre,$nombre,'24',$unidad,$unidad,$precio,$afectacion,$_SESSION['id_empresa'],$precio,$precio,1,1,$factor,$sku,'','SI',$lastInsertId,$stock]);
    $k++;
}
else
{
    $mensaje['nopro']=$sku;
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
if($_POST['action']=='buscar_productox')
{
  $productos = "SELECT * FROM tbl_productos WHERE id=$_POST[id]";
   $resultado_productos  = $connect->prepare($productos);
   $resultado_productos->execute();
   $row_productos = $resultado_productos->fetch(PDO::FETCH_ASSOC);

   $data = json_encode($row_productos,true);

   echo $data;


   exit();

}

//####################################CREAR CLIENTE####################################////

if($_POST['action'] == 'nuevo_producto')
{
	//print_r($_POST);
	
	$imagen_nueva=$_FILES['imagen']['name']; 
    $imagen_antigua = $_POST['last_imagen'];

            if(empty($imagen_nueva))
            {
                $imagen = $imagen_antigua;
            }
            else
            {
                 move_uploaded_file($_FILES['imagen']['tmp_name'],'../images/products/'.$imagen_nueva);
                 $imagen =$imagen_nueva;
            }




    $query = $connect->prepare("INSERT INTO tbl_productos(nombre,descripcion,marca,unidad,unidadu,precio_venta,afectacion,empresa,precio2,costo,por1,por2,factor,sku,imagen,venta,categoria) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ");
    $resultado = $query->execute([$_POST['nombre'],$_POST['descripcion'],$_POST['marca'],$_POST['unidad'],$_POST['unidadu'],$_POST['precio_venta'],$_POST['afectacion'],$_POST['empresa'],$_POST['precio_venta2'],$_POST['precio_compra'],$_POST['por_gan1'],$_POST['por_gan2'],$_POST['factor'],$_POST['cod_barras'],$imagen,$_POST['dventa'],$_POST['categoria']]);

    if($resultado)
    {
    	$msg = 'exito';
    }
    else
    {
    	$msg = 'error';
    }

    echo $msg;

    exit();
}

//####################################EDITAR CLIENTE####################################////
if($_POST['action'] == 'editar_producto')
{

	$imagen_nueva=$_FILES['update_imagen']['name']; 
    $imagen_antigua = $_POST['update_last_imagen'];

            if(empty($imagen_nueva))
            {
                $imagen = $imagen_antigua;
            }
            else
            {
                 move_uploaded_file($_FILES['update_imagen']['tmp_name'],'../images/products/'.$imagen_nueva);
                 $imagen =$imagen_nueva;
            }



	$query=$connect->prepare("UPDATE tbl_productos SET nombre=?, marca=?,unidad=?,precio_venta=?,afectacion=?,descripcion =?,precio2 = ?,factor=?,por1=?,por2=?,costo=?,sku=?,imagen=?,venta=?,stock=?,categoria=?,marca=? WHERE id = ?");
	 $resultado = $query->execute([$_POST['update_nombre'],$_POST['update_marca'],$_POST['update_unidad'],$_POST['update_precio_venta'],$_POST['update_afectacion'],$_POST['update_descripcion'],$_POST['update_precio_venta2'],$_POST['update_factor'],$_POST['update_por_gan1'],$_POST['update_por_gan2'],$_POST['update_precio_compra'],$_POST['update_cod_barras'],$imagen,$_POST['update_dventa'],$_POST['update_stock1'],$_POST['update_categoria'],$_POST['update_marca'],$_POST['update_id']]);

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


//####################################ELIMINAR CATEGORIA####################################////
if($_POST['action'] == 'delete_producto')
{
   $query=$connect->prepare("UPDATE tbl_productos SET estado=? WHERE id = ?");
	$resultado = $query->execute(['0',$_POST['delete_id']]);

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