<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();



////////////llamar datos mediante un json para editar pelicula/////////////
if($_POST['action']=='buscar_tpago')
{
   $tpago = "SELECT * FROM tbl_tipo_pago WHERE id=$_POST[id]";
   $resultado_tpago  = $connect->prepare($tpago);
   $resultado_tpago->execute();
   $row_tpago = $resultado_tpago->fetch(PDO::FETCH_ASSOC);

   $data = json_encode($row_tpago,true);

   echo $data;

   exit();

}

//####################################CREAR####################################////

if($_POST['action'] == 'addMpago')
{
	


    $query = $connect->prepare("INSERT INTO tbl_tipo_pago(nombre,mpago,cuenta,empresa) VALUES(?,?,?,?) ");
    $resultado = $query->execute([$_POST['nombre_mpago'],$_POST['mpago'],$_POST['cuenta_pago'],$_POST['empresa']]);

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
if($_POST['action'] == 'ediMpago')
{


	$query=$connect->prepare("UPDATE tbl_tipo_pago SET nombre=?, mpago=?,cuenta=? WHERE id = ?");
	 $resultado = $query->execute([$_POST['update_nombre'],$_POST['update_tpago'],$_POST['update_cuenta'],$_POST['update_id']]);

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
if($_POST['action'] == 'delmpago')
{
   $query=$connect->prepare("UPDATE tbl_tipo_pago SET estado=? WHERE id = ?");
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