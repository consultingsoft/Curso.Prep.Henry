<?php
	session_name("proxydomicilios");
	session_start();
	
	$username = $_SESSION['username'];

	if(isset($_GET['u'])) $usuario = base64_decode($_GET['u']);
	if(isset($_GET['p'])) $pass = base64_decode($_GET['p']);
	if(isset($_POST['id_neg'])) $id_neg = $_POST['id_neg'];
	if(isset($_POST['obs'])) $obs = $_POST['obs'];
	
	$vec_cod = explode("*", $_POST["codigos"]);
	$vec_cant = explode("*", $_POST["cantidades"]);
	$vec_pventa = explode("*", $_POST["pventa"]);

	include("../../../conexion_tulocreas.php");
	$result = mysql_query("SELECT usuario FROM publicaciones WHERE id = '$id_neg' ORDER BY ID DESC;",$enlace);
	if(!$result) die(mysql_error());
	if(mysql_num_rows($result) > 0)
		{
		$usu_neg = mysql_result($result, 0, 'usuario') ;			
		}
	
	//$con = mysql_connect("146.255.101.177","avl","avl.2015");
	//mysql_select_db("logistica", $con);

	$usuario = $_POST['user'];
	$mail_cliente = $_POST['user'];

	//$mail_cliente ="consultingsoft.gerente@gmail.com";	
	$nombre_cliente="Marly Beltran";	
	$Direccion = "Centro Ccial La Torre, Cr 52d 77 63 Int.9905 sótano";
	$Telefono = "350 6093118";

	include("../../../conexion_tulocreas3.php");
	$result = mysql_query("insert into ped_domicilios1 (usuario, negocio, usu_neg, observacion, fecha) VALUES ('".$_POST["user"]."', '$id_neg','$usu_neg', '$obs', '".date('Ymd')."');",$enlace);
	if(!$result) die("Error MySQL: " . mysql_error($enlace));	

	$result = mysql_query("SELECT id FROM ped_domicilios1 WHERE usuario = '$usuario' ORDER BY ID DESC;",$enlace);

	$result = mysql_query("SELECT id FROM ped_domicilios1 ORDER BY ID DESC LIMIT 1;",$enlace);
	if(!$result) die(mysql_error());
	if(mysql_num_rows($result) > 0)
		{
		$id_ped = mysql_result($result, 0, 'id') ;			
		}
		
	for($s=0; $s < count($vec_cod)-1; $s++)
		{
		$result = mysql_query("insert into ped_domicilios2 (orden, codigo, cantidad, venta) VALUES ('$id_ped', '".$vec_cod[$s]."', '".$vec_cant[$s]."' , '".$vec_pventa[$s]."');",$enlace);
		if(!$result) die("Error MySQL: " . mysql_error($enlace));	


		}


	$detalle.= "<table border='1' cellspacing='0' style='font-family:arial; font-size: 14px'>
				<tr><td width='20%'><strong>codigo</strong></td> <td>producto </td> <td>Unidad</td> <td>Cantidad: </td> <td>Venta: </td>  
				</tr>";

	$result2 = mysql_query("SELECT ped.*,p.descripcion,p.unidad, p.VtaPOS FROM ped_domicilios2 ped
							LEFT JOIN ingacico_tulocreas.productos p ON p.codigo=ped.codigo
							WHERE orden ='$id_ped'  ",$enlace );
	$rows=mysql_num_rows($result2);
	$cont_items=0;	
	if($rows > 0)
        {
		while($cont_items < $rows)
			{
			$codigo = mysql_result($result2, $cont_items,"ped.codigo");
			$descripcion = mysql_result($result2, $cont_items,"p.descripcion");
			$unidad = mysql_result($result2, $cont_items,"unidad");
			$cantidad = mysql_result($result2, $cont_items,"cantidad");
			$venta = mysql_result($result2, $cont_items,"p.VtaPOS");

			$detalle.= "<tr ><td>".$codigo. "</td> <td >" . $descripcion."</td> <td >" . $unidad."</td>    
							<td>".$cantidad. "</td> <td >" .$venta."</td> 
						</tr> ";

  		    $cont_items++;
			}
		}

	$detalle.= "</table>";				

	echo "Grabación exitosa.*" ;
	//echo "Grabación exitosa.*" .  mysql_insert_id($enlace)."ID=".$id_ped."//id_neg =".$id_neg;
	//echo '<pre>'; print_r($_POST["pventa"]); echo '</pre>';
	$tarea = 'Feliz dia, Llego pedido '.'id_neg='.$id_neg.$usu_neg ;			
	
	//===============================================================================


	$email_empresa = 's.logistica@hotmail.com';
	//$email_negocio = 'loaizanelson857@gmail.com';
	$email_negocio = 'consultingsoft.gerente@gmail.com';
	$mail_cliente ="marlyb1130@gmail.com";	

	$color1 = "#00bcd4";
	$color2 = "#fdf5e6";

//	$msg = "Bienvenido(a) ".$_POST['nombre']." a Cooperaser y Tulocreas <br />Ya eres parte de esta gran familia Cooperaser. <br />La Plataforma Tulocreas, te ofrecerá las herramientas para mantenerte en contacto con esta gran comunidad.";

	$link = "https://docs.google.com/document/d/1_dSS5KDLDxABVIqCW9LhAknQ6eQq6Hv0JLbrywjmVys/edit";	

	$msg = 'Pedido de un Cliente por la Aplicación de celular <br />';
	$msg .= 'Cliente: '.$nombre_cliente.'  <br />';
	$msg .= 'Dirección: '.$Direccion.'  <br />';
	$msg .= 'Telefono: '.$Telefono.'  <br />';

//	$msg_cliente = 'Muchas gracias '.$nombre_cliente.' por contactarnos, para nosotros es un gusto poder servirle. <br />';
//	$msg_cliente .= 'Su solicitud será atendida lo antes posible. <br /><br />';
	//$msg .= "Asunto: pedido de " .$nombre_cliente." con usuario=". $usuario;

	$cuerpo = '<div style="display:block;width:90%;height:100%;background-color:'.$color1.';color:#FFFFFF;padding:2%;position:relative;left:0px;right:0px;margin:auto;;font-family:arial">';

	$cuerpo .= "© Copyright 2014 Tulocreas, All rights reserved.<br /><br />";

	$cuerpo .= '<div>
					<img src="http://www.tulocreas.com/imagenes/proxyred_small.jpg" border="0" height="30px" >
				</div><br />';

	$cuerpo .= $msg . " <br /><br />";
	$cuerpo_cliente = "mensajecliente ".$msg_cliente . " <br /><br />" ;

	$cuerpo .= '<a href="'.$link.'" style="width:200px;height:50px;display:block;position:relative:left:0px;right:0px;margin:auto;background-color:#3f51b5;color:#ffffff;text-align:center;line-height:50px;text-decoration:none;font-weight:bold" >Ver Ayuda</a>
				<br /><br />
			</div>';
	$cuerpo .= '<div style="display:block;width:90%;height:100%;background-color:'.$color2.';color:#000000;padding:2%;position:relative;left:0px;right:0px;margin:auto;font-family:arial">
			<p style="font-size:18px;">
			</p>
			<p style="font-size:12px;">
			</p>';

	$cuerpo .= '<br/>'.$detalle.'<br/>';

	$cuerpo .= '<br/>Observaciones del Cliente: '.$obs.'<br/><br/><br/>';
	
	//$cuerpo .= '<a href="http://www.proxydomicilios.com" style="width:300px;height:50px;display:block;position:relative:left:0px;right:0px;margin:auto;background-color:#3f51b5;color:#ffffff;text-align:center;line-height:50px;text-decoration:none;font-weight:bold" >Ingresar a proxydomicilios.com</a>
	$cuerpo .='</div>';

//	$cuerpo_cliente .=  $cuerpo;

	//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX			

	require("../../../PHPMailer_v5.1/class.phpmailer.php");
	$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->CharSet = 'UTF-8';
	$mail->SMTPDebug = 1; // 1 tells it to display SMTP errors and messages, 0 turns off all errors and messages, 2 prints messages only.


	//$mail->SMTPAuth = true;
	//$mail->SMTPSecure = "tls";
	//$mail->Host = "host1.bienvenidohosting.com";
	//$mail->Port = 465;
	//$mail->Username = "facturepyme@amalatam.com";
    //$mail->Password = "Guso9000";

	
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "tls";
	$mail->Host = "smtp.hostinger.co";
	$mail->Port = 587;

	$email_facturador = "ventas@amalatam.com"; //MAIL DEL SERVIDOR
	$passw_mail_fact = "Olimpo2020";
	$mail->Username = $email_facturador;  //mail hosting para enviar
	$mail->Password = $passw_mail_fact;

	$mail->From = "ventas@amalatam.com";
	$mail->FromName = "Pedido Fruver";
	$mail->AddAddress($email_negocio);

	$mail->AddAddress($email_empresa, $_POST["nom"]);
	//$mail->AddReplyTo("Email Address HERE", "Name HERE"); // Adds a "Reply-to" address. Un-comment this to use it.
	$mail->Subject = "Pedido de frutas";
	$mail->MsgHTML($cuerpo);
//	$mail->Body = $cuerpo;

//	$mail->AddAttachment("../../../imagenes/suenos1.jpg");      // attachment

	if ($mail->Send() == true) 
		{
		//echo "El mensaje ha sido enviado exitosamente, gracias por contactarnos.";
		}
	else 
		{
		echo "El mensaje no pudo ser enviado. Por favor intente mas tarde.<br />";
		//echo "Mailer error: " . $mail->ErrorInfo;
		}

//XXXXXXXXXXXXXXXXXXXX ENVIAR EL OTRO MAIL QUE VA PARA EL CLIENTE XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

$mail->From = "ventas@amalatam.com";
$mail->FromName = "Pedido Agroproxy CLIENTE";

$mail->AddAddress($mail_cliente, $_POST["nom"]);

//$mail->AddReplyTo("Email Address HERE", "Name HERE"); // Adds a "Reply-to" address. Un-comment this to use it.
$mail->Subject = "Pedido de frutas y verduras";

$mail->MsgHTML($cuerpo);
//	$mail->Body = $cuerpo;

if ($mail->Send() == true) 
	{
	echo "El Pedido ha sido enviado exitosamente, gracias por contactarnos.";
	}
else 
	{
	echo "El mensaje no pudo ser enviado. Por favor intente mas tarde.<br />";
	//echo "Mailer error: " . $mail->ErrorInfo;
	}		
?>