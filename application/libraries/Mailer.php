<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailer{
		
	var $mail;

	private $email_from;
	private $company;

    public function __construct()
    {
        require_once('PHPMailer/class.phpmailer.php');
 
        // the true param means it will throw exceptions on errors, which we need to catch
        $this->mail = new PHPMailer(); 
        $this->mail->IsSMTP(); // telling the class to use SMTP
 	    $this->mail->SMTPDebug  = 0;                     // enables SMTP debug information
        $this->mail->SMTPAuth   = false;                  // enable SMTP authentication
        $this->mail->CharSet = "utf-8";                  // 一定要設定 CharSet 才能正確處理中文
       // $this->mail->SMTPDebug  = 0;                     // enables SMTP debug information
        // $this->mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        // $this->mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
        // $this->mail->Port       = 465;                   // set the SMTP port for the GMAIL server

        $CI =& get_instance();
		$this->email_from = $CI->config->item('email_sender');
		$this->company = $CI->config->item('company_name');
		$this->mail->AddReplyTo($this->email_from, $this->company);
		$this->mail->SetFrom($this->email_from, $this->company);
//        $this->mail->AddReplyTo('info+proages@isinet.mx', 'Isinet');
//        $this->mail->SetFrom('info+proages@isinet.mx', 'Isinet');
    }

// Send Notifications emails 
    public function notifications( $notification = array(), $razon = null, $responsable = null, $from_reply_to = array() ){
		if( empty( $notification ) ) return false;

		$agentes = '';

		if( !empty( $notification[0]['agents'] ) ){

			foreach( $notification[0]['agents'] as $value ){
				if (( $notification[0]['work_order_status_id'] == 4 ) || ($notification[0]['work_order_status_id'] == 10))
					$agentes = '';
				if( !empty( $value['company_name'] ) )
					$agentes .=  $value['company_name'];
				else
					$agentes .=  $value['name'];	
		$status_name = '';
		$stat_name = '';

		if( $notification[0]['work_order_status_id'] == 5 ){
			$status_name = 'Creación';
			$stat_name = 'creada';
		}
		if( $notification[0]['work_order_status_id'] == 6 ){
			$status_name = 'Activación';
			$stat_name = 'activada';
		}
		if( $notification[0]['work_order_status_id'] == 9 ){
			$status_name = 'Desactivación';
			$stat_name = 'desactivada';
		}
		if( $notification[0]['work_order_status_id'] == 2 ){
			$status_name = 'Cancelación';
			$stat_name = 'cancelada';
		}
		if( $notification[0]['work_order_status_id'] == 7 ){
			$status_name = 'Aceptación';
			$stat_name = 'aceptada';
		}
		if( $notification[0]['work_order_status_id'] == 8 ){
			$status_name = 'Rechazo';					
			$stat_name = 'rechazada';
		}
		if( $notification[0]['work_order_status_id'] == 4 ){
			$status_name = 'Pago';					
			$stat_name = 'pagada';
		}
		if( $notification[0]['work_order_status_id'] == 10 ){
			$status_name = 'NTU';					
			$stat_name = 'marcada como NTU';
		}

		$body = '<div bgcolor="#f4f4f4">
					 <table width="650" cellspacing="0" cellpadding="0" border="0" align="center"><tbody><tr><td height="3"><img width="650" height="3" style="display:block" src="http://serviciosisinet.com/img/top.jpg"></td></tr></tbody></table>
					 <table width="650" cellspacing="0" cellpadding="0" border="0" align="center" style="border-left-width:1px;border-left-style:solid;border-left-color:rgb(197,197,197);border-right-width:1px;border-right-style:solid;border-right-color:rgb(197,197,197)"><tbody><tr><td bgcolor="#FFFFFF" align="center" height="50" style="line-height:29px"><span style="font-size:22px"><b>					
					'.$status_name.'</b></span><span style="color:rgb(25,25,25);font-family:Helvetica,arial,sans-serif;font-size:22px;font-weight:bold"> de la Orden de Trabajo '.$notification[0]['uid'].'</span></td></tr></tbody></table>
					 <table width="650" cellspacing="0" cellpadding="0" border="0" align="center" style="border-left:1px solid #c5c5c5;border-right:1px solid #c5c5c5"><tbody><tr><td bgcolor="#FFFFFF" height="20" valign="top"><img width="648" height="1" style="display:block" src="http://serviciosisinet.com/img/divider.jpg"></td>
					</tr></tbody></table>
					 <table width="650" cellspacing="0" cellpadding="0" border="0" align="center" style="border-left-width:1px;border-left-style:solid;border-left-color:rgb(197,197,197);border-right-width:1px;border-right-style:solid;border-right-color:rgb(197,197,197)">
					<tbody>
					  <tr>
						<td bgcolor="#FFFFFF" width="30">&nbsp;</td>
						<td bgcolor="#FFFFFF" align="left" style="font-family:Helvetica,arial,sans-serif;font-size:14px;line-height:15px">
							<p style="color:rgb(79,79,79)">'.$agentes.'</p>
							<p><font color="#4f4f4f">Le notificamos que la solicitud con la Orden de trabajo  '.$notification[0]['uid'].' fue '.$stat_name.'</font><font color="#4f4f4f">.</font></p>';
							if( !empty( $notification[0]['notes'] )):
								$body .= '<p><b>Razón</b>: '.$notification[0]['notes'].'</p>';
							endif;
							if( !empty( $razon ) and !empty( $responsable ) ):
							$body .= '<p><b>Razón</b>: '.$razon.'</p>
									  <p><b>Responsable</b>: '.$responsable.'</p>
									  <p><b>Comentarios:</b> '.$notification[0]['comments'].'</p> ';
							endif;

							/*
							Número de OT
							Fecha de trámite
							Ramo
							Tipo de trámite
							Sub tipo
							Producto
							Prima
							Forma de Pago
							Conducto
							Nombre del asegurado
							Póliza (si existe)
							Comentarios (si existen)*/

							$body .= ' <table width="80%" align="center" style="color:rgb(79,79,79)">
								  <tbody><tr>
									  <td colspan="2" align="center"><p><b>Detalles de la Orden de Trabajo</b></p>
									  </td>
								  </tr>
								  <tr>
									  <td width="30%"><b>Orden de Trabajo:</b></td>
									  <td width="70%"> '.$notification[0]['uid'].'</td>    
								  </tr>';
								   if( $notification[0]['creation_date'] != '0000-00-00 00:00:00'  )
								  $body .= '<tr>
									  <td><b>Fecha de tramite:</b><br><small>(año-mes-dia)</small></td>
									  <td> '.date( 'Y-m-d', strtotime($notification[0]['creation_date'] ) ).'</td>
								  </tr>';
							$body .= '<tr>
									  <td><b>Ramo:</b></td>
									  <td>'.$notification[0]['group_name'].'</td>    
								  </tr>
								   <tr>
									  <td><b>Tipo de trámite:</b></td>
									  <td>'.$notification[0]['parent_type_name']['name'].'</td>    
								  </tr>
								   <tr>
									  <td><b>Sub tipo:</b></td>
									  <td>'.$notification[0]['type_name'].'</td>    
								  </tr>';
								  
								  if( !empty( $notification[0]['policy'][0]['products'][0]['name'] ) )
								  
									  $body .= '<tr>
												  <td><b>Producto:</b></td>
												  <td>'.$notification[0]['policy'][0]['products'][0]['name'].'</td>    
												</tr>';
								  
								 if( !empty( $notification[0]['policy'][0]['prima'] ) && ($notification[0]['policy'][0]['prima'] != null) )							  
										  $body .= '<tr>
													  <td><b>Prima:</b></td>
													  <td>'.$notification[0]['policy'][0]['prima'].'</td>    
												  </tr> ';
								 if( !empty( $notification[0]['policy'][0]['payment_intervals_name'] ) )	  

								  $body .= '<tr>
										  <td><b>Forma de Pago:</b></td>
										  <td>'.$notification[0]['policy'][0]['payment_intervals_name'].'</td>    
									  </tr>';

								  if( !empty( $notification[0]['policy'][0]['payment_method_name'] ) )	  

									   $body .= '<tr>
										  <td><b>Conducto:</b></td>
										  <td>'.$notification[0]['policy'][0]['payment_method_name'].'</td>    
									  </tr>';

		if( !empty( $notification[0]['policy'][0]['name'] ) )										  
			$body .= '<tr>
				<td><b>Asegurado:</b></td>
				<td> '.$notification[0]['policy'][0]['name'].'</td>    
			</tr>';

								   if( !empty( $notification[0]['policy'][0]['uid'] ) )	  

								    $body .= '<tr>
										  <td><b>Poliza:</b></td>
										  <td> '.$notification[0]['policy'][0]['uid'].'</td>    
									  </tr>';
								  if( !empty($notification[0]['comments'] ) )	  
								   $body .= '<tr>
									  <td><b>Comentarios:</b></td>
									  <td> '.$notification[0]['comments'].'</td>    
								  </tr>';								  
							 $body .= ' </tbody></table>
						</td>
						<td bgcolor="#FFFFFF" width="30">&nbsp;</td>
					</tr>
					</tbody></table>

					 <table width="650" cellspacing="0" cellpadding="0" border="0" align="center" style="border-left:1px solid #c5c5c5;border-right:1px solid #c5c5c5"><tbody><tr> <td bgcolor="#FFFFFF" height="20" valign="top">&nbsp;</td></tr></tbody></table>
					 <table width="650" cellspacing="0" cellpadding="0" border="0" align="center"><tbody><tr><td height="22"><img width="650" height="22" style="display:block" src="http://serviciosisinet.com/img/bottom.jpg"></td></tr></tbody></table>
					<table width="650" cellspacing="0" cellpadding="0" border="0" align="center"><tbody><tr><td width="650" bgcolor="#f4f4f4" align="center"><span style="font-family:Helvetica,arial,sans-serif;font-size:11px;color:#636363;font-style:normal;line-height:16px"></span></td>
					</tr></tbody></table><div class="yj6qo"></div><div class="adL">
					</div></div>';
			//if( !empty( $value['email'] ) ){

//			if (isset($from_reply_to['from']))
//				$headers = "From: " . $from_reply_to['from'] . "\r\n";
//			else
				$headers = "From: " . $this->email_from . "\r\n";

			if (isset($from_reply_to['reply-to']))
				$headers .= "Reply-To: " . $from_reply_to['reply-to'] . "\r\n";
			else
				$headers .= "Reply-To: " . $this->email_from . "\r\n";

			if(isset($from_reply_to['bcc']))
			{
				$headers .= "Bcc: " . $from_reply_to['bcc'] . "\r\n";
				if(isset($value['email2']) && $value['email2'] != '')
				{
					$headers .= "Bcc: " . $from_reply_to['bcc'] .','.$value['email2']. "\r\n";	
				}
				else
				{
					$headers .= "Bcc: " . $from_reply_to['bcc'] ."\r\n";	
				}
			}else
			{
				if(isset($value['email2']) && $value['email2'] != '')
				{
					$headers .= "Bcc: " . $value['email2'] . "\r\n";
				}
			}
			error_log(print_r($value,true));



			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			@mail( $value['email'],  $status_name. ' de la Orden de Trabajo '.$notification[0]['uid'], $body, $headers );
			
			
			/*
						
				try{
					
					
					
					
					
					
					
					
					
					/*
					
								
					$this->mail->AddAddress( $value['email'], $agentes);
		 
					$this->mail->Subject = $status_name. ' de la Orden de Trabajo '.$notification[0]['uid'];
					$this->mail->Body    = $body;
		 
					$this->mail->Send();
						echo "Message Sent OK</p>\n";
		 
				} catch (phpmailerException $e) {
					echo $e->errorMessage(); //Pretty error messages from PHPMailer
				} catch (Exception $e) {
					echo $e->getMessage(); //Boring error messages from anything else!
				}
				
			}*/			
			
		  }
			
		}
		
		
    }
		
}

?>