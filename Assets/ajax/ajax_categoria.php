<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();

if($_POST['action'] == 'cargarDatos')
{
	$data= Array();

	$draw = $_POST['draw'];
	$row = $_POST['start'];
	$rowperpage = $_POST['length']; // Rows display per page
	$columnIndex = $_POST['order'][0]['column']; // Column index
	$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
	$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
	$searchValue = $_POST['search']['value']; // Search value

	## Search 
	$searchQuery = " ";
		
	if($searchValue != ''){
		$searchQuery .= " and ( nombre LIKE '%".$_POST['search']['value']."%' ";
		$searchQuery .=" OR nombre LIKE '%".$_POST['search']['value']."%' ) ";
	//$searchQuery .=" OR txtCOD_ARTICULO IN (SELECT cod_articulo FROM articulo_serie WHERE serie LIKE '%".$_POST['search']['value']."%' OR lote LIKE '%".$_POST['search']['value']."%' ) ";

	//txtCOD_ARTICULO IN (SELECT cod_articulo FROM articulo_serie WHERE serie LIKE '%F0011%')
	}
    $query = "SELECT * FROM tbl_categorias WHERE empresa = $_SESSION[id_empresa]";
	$stmt = $connect->prepare($query);
	$stmt->execute();
	$totalRecords=$stmt->rowCount();


	$query1 = "SELECT * FROM tbl_categorias WHERE empresa = $_SESSION[id_empresa] ".$searchQuery;
	$stmt1 = $connect->prepare($query1);
	$stmt1->execute();
	$totalRecordwithFilter=$stmt1->rowCount();

	// Consulta para obtener las categorías
	$query2 = "SELECT id,nombre,cuenta_compra,cuenta_venta,estado FROM tbl_categorias WHERE empresa = $_SESSION[id_empresa] ".$searchQuery." limit ".$row.",".$rowperpage;
	$stmt2 = $connect->prepare($query2);
	$stmt2->execute();
	// Obtener los resultados y convertirlos en un array
	$categorias = $stmt2->fetchAll(PDO::FETCH_OBJ);
    
    foreach($categorias as  $categorias)
    {
    	$botones =  '<button class="btn btn-warning rounded-circle" onclick="openModalEdit()"><i class="fe fe-edit"></i></button>
                     <button class="btn btn-danger rounded-circle" onclick="openModalDel()"><i class="fe fe-trash-2"></i></button>';

       if($categorias->estado == '1')
       {
       	$estado_color = 'success';
       	$estado_det   = 'activo';
       }
       else
       {
		$estado_color = 'danger';
       	$estado_det   = 'inactivo';
       }

        $botones2 = '<h4><span class="badge badge-'.$estado_color.'">'.$estado_det.'</span></h4>';

    	$data[]=array(
           "0" => $botones,
           "1" => $categorias->id,
           "2" => $categorias->nombre,
           "3" => $categorias->cuenta_compra,
           "4" => $categorias->cuenta_venta,
           "5" => $botones2,

    	);
    }

    
    $jsondata = array(
    	"draw"=>intval($draw),
        "recordsTotal"    =>$totalRecords,
        "recordsFiltered" =>$totalRecordwithFilter,
        "data" => $data);

	// Devolver los datos en formato JSON
	header('Content-Type: application/json');
	echo json_encode($jsondata);
exit;
}

//####################################CREAR CLIENTE####################################////

if($_POST['action'] == 'addCategoria')
{
	
	$nombre 	  = $_POST['categoria'];
	
	$empresa      = $_POST['empresa'];
	
   	$query=$connect->prepare("INSERT INTO tbl_categorias(nombre,empresa,cuenta_venta,cuenta_compra) VALUES (?,?,?,?);");
	$resultado=$query->execute([$nombre,$empresa,$_POST['cuenta_venta'],$_POST['cuenta_compra']]);


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
if($_POST['action'] == 'ediCategoria')
{
	$nombre = $_POST['update_nombre'];
	$id     = $_POST['update_id'];

	$query=$connect->prepare("UPDATE tbl_categorias SET nombre=?,cuenta_venta=?,cuenta_compra=? WHERE id = ?");
	$resultado = $query->execute([$nombre,$_POST['update_cuenta_venta'],$_POST['update_cuenta_compra'],$id]);

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
if($_POST['action'] == 'delCategoria')
{
	
	$id     = $_POST['delete_id'];

	$query=$connect->prepare("UPDATE tbl_categorias SET estado=? WHERE id = ?");
	$resultado = $query->execute(['0',$id]);

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