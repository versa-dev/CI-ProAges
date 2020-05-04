<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
/*

  Author		Ulises Rodríguez
  Site:			http://www.ulisesrodriguez.com
  Twitter:		https://twitter.com/#!/isc_ulises
  Facebook:		http://www.facebook.com/ISC.Ulises
  Github:		https://github.com/ulisesrodriguez
  Email:		ing.ulisesrodriguez@gmail.com
  Skype:		systemonlinesoftware
  Location:		Guadalajara Jalisco Mexíco


 */
$this->config->set_item('profile_picture_default', '150');
$base_url = base_url();
if (isset($data) && isset($data->picture))
{
	$this->load->helper( 'user_image' );
	get_default_user_image( $data->picture );
}
$current_page = $this->uri->segment(2);
if ($current_page === FALSE)
	$current_page = 'index';
?>

<div class="row-fluid sortable">
    <div class="box span12">      
        <div class="box-content" style="padding-left: 3em"> <!-- To tweak left padding -->
            <?php // Show Messages ?>            
            <?php if (isset($message['type'])): ?>            
                <?php if ($message['type'] == true): ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <img src="<?php echo $base_url ?>images/true.png" width="20" height="20" />
                        <strong>Listo: </strong> <?php echo $message['message']; // Show Dinamical message Success  ?>
                    </div>
                <?php endif; ?>

                <?php if ($message['type'] == false): ?>
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <img src="<?php echo $base_url ?>images/false.png" width="20" height="20" />
                        <strong>Error: </strong> <?php echo $message['message']; // Show Dinamical message error  ?>
                    </div>
                <?php endif; ?>			
            <?php endif; ?> 

            <div class="row">
                <div class="span8">
                    <h3><?php if( !empty( $data->agent_name ) ) echo $data->agent_name ?></h3>
<?php 
if( !empty( $data->uids[0]['uid'] ) )
	echo $data->uids[0]['uid']. ' - '; 
else 
	echo 'Sin clave asignada - '; 
$data->generacion . ' - '; 
if( $data->connection_date != '0000-00-00' and $data->connection_date != ''): ?>
	Conectado <?php echo getFormatDate($data->connection_date) ?>
<?php else: ?>
	En proceso de conexión
<?php endif; ?> 
                </div>
                <div class="span4" style="text-align: right">
<?php if( !empty( $data->picture ) ): ?>
						<img src="<?php echo $base_url . 'usuarios/assets/profiles/' . $data->picture ?>" width="100px" />
<?php endif; ?>
<?php if( isset( $access_update_profile ) and $access_update_profile == true ): ?>
                    <br /><br /><br />
                    <a href="<?php echo $base_url ?>usuarios/editar_perfil/<?php echo $this->user_id ?>.html" class="btn btn-inverse pull-right" style="display:none;"/>Editar Perfil</a>
<?php endif; ?>
                </div>
            </div>

            <div class="row">
                <a href="<?php echo $base_url ?>agent/agent_report/<?php echo $this->user_id ?>.html" class="span4 subpage-link <?php if (($current_page == 'agent_report') || ($current_page == 'index')) echo ' subpage-link-current' ?>">DESEMPEÑO DE VENTAS</a>
                <a href="<?php echo $base_url ?>agent/agent_ot/<?php echo $this->user_id ?>.html" class="span3 subpage-link <?php if ($current_page == 'agent_ot') echo ' subpage-link-current' ?>">ÓRDENES DE TRABAJO</a>
                <a href="<?php echo $base_url ?>agent/agent_sales_activity/<?php echo $this->user_id ?>.html" class="span3 subpage-link <?php if (($current_page == 'agent_sales_activity') || ($current_page == 'create_activity') || ($current_page == 'activity_details')) echo ' subpage-link-current' ?>">ACTIVIDAD DE VENTAS</a>
                <span class="span2"></span>			
            </div>
            <br />
<?php echo $sub_page_content; ?>

        </div>
    </div>
    
</div><!--/span-->
</div><!--/row-->
<?php 
// Getting format date
function getFormatDate( $date = null ){
	 if( empty( $date ) or $date == '0000-00-00' ) return false;
	 $date = explode( '-', $date );
	 $meses= array('01'=>"Enero",'02'=>"Febrero",'03'=>"Marzo",'04'=>"Abril",'05'=>"Mayo",'06'=>"Junio",'07'=>"Julio",'08'=>"Agosto",'09'=>"Septiembre",'10'=>"Octubre",'11'=>"Noviembre",'12'=>"Diciembre");
	 return 'el '.$date[2].' de '.$meses[$date[1]].' del '.$date[0]; 
}	
?>