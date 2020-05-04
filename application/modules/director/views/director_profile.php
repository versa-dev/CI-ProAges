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
if (isset($user['picture']) && $user['picture'])
{
	$this->load->helper( 'user_image' );
	get_default_user_image( $user['picture'] );
}
$displayed_user_name = '';
if ($user['company_name'])
	$displayed_user_name = $user['company_name'];
elseif (isset($user['name']) && isset($user['lastnames']) && $user['name'] && $user['lastnames'])
	$displayed_user_name = $user['name'] . ' ' . $user['lastnames'];

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
                    <h3><?php echo $displayed_user_name ?></h3>
                </div>
                <div class="span4" style="text-align: right">
<?php if ( isset( $user['picture'] ) ): ?>
						<img src="<?php echo $base_url . 'usuarios/assets/profiles/' . $user['picture'] ?>" width="100px" />
<?php endif; ?>
                </div>
            </div>
			
            <div class="row">
                <a id="plan-link" href="<?php echo $base_url ?>director/sales_planning.html" class="span5 subpage-link<?php if (($current_page == 'sales_planning') || ($current_page == 'index') || ($current_page == 'meta') || ($current_page == 'simulate')) echo ' subpage-link-current' ?>">PLANEACIÓN Y DESEMPEÑO DE VENTAS</a>
                <a id="activity-link" href="<?php echo $base_url ?>director/sales_activities.html" class="span3 subpage-link<?php if (($current_page == 'sales_activities') || ($current_page == 'activities')) echo ' subpage-link-current' ?>">ACTIVIDAD DE VENTAS</a>
                <a id="ot-link" href="<?php echo $base_url ?>director/ot.html" class="span4 subpage-link<?php if (($current_page == 'ot') || ($current_page == 'ot_list')) echo ' subpage-link-current' ?>">ORDENES DE TRABAJO</a>
            </div>
<?php if (($current_page == 'sales_activities') || ($current_page == 'activities')): ?>
            <div class="row">
                <a style="font-size: 1em" id="activity-link-sub-act" href="<?php echo $base_url ?>director/activities.html" class="span3 subpage-link<?php if ($current_page == 'activities') echo ' subpage-link-current' ?>">ACTIVIDADES</a>
                <a style="font-size: 1em" id="activity-link-sub-dis" href="<?php echo $base_url ?>director/sales_activities.html" class="span3 subpage-link<?php if ($current_page == 'sales_activities') echo ' subpage-link-current' ?>">DISTRIBUCIÓN</a>
            </div>
<?php endif; ?>
<?php if (($current_page == 'sales_planning') || ($current_page == 'index')):
	if (isset($page) && ($page == 'metas')):
		$current_page = 'meta';
	endif;
?>
            <div class="row">
                <a style="font-size: 1em" id="plan-link-sub-results" href="<?php echo $base_url ?>director/sales_planning.html" class="span3 subpage-link<?php if (($current_page == 'sales_planning') || ($current_page == 'index')) echo ' subpage-link-current' ?>">RESULTADOS</a>
                <a style="font-size: 1em" id="activity-link-sub-metas" href="<?php echo $base_url ?>director/sales_planning.html" class="span3 subpage-link<?php if ($current_page == 'meta') echo ' subpage-link-current' ?>">METAS</a>
            </div>
<?php endif; ?>
            <div class="row">
<?php echo $sub_page_content; ?>
            </div>
        </div>
    </div><!--/span-->
</div><!--/row-->
