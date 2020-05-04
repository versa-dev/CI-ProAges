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

<?php if ($data): ?>
            <div class="row">
                <div class="span8">
                    <h3><?php echo $data->displayed_user_name ?></h3>
                </div>
                <div class="span4" style="text-align: right">
<?php if( !empty( $data->picture ) ): ?>
						<img src="<?php echo $base_url . 'usuarios/assets/profiles/' . $data->picture ?>" width="100px" />
<?php endif; ?>
                </div>
            </div>
<?php endif; ?>
			
            <div class="row">
                <a id="ot-link" href="<?php echo $base_url ?>operations/ot/<?php echo $this->user_id ?>.html" class="span3 subpage-link <?php if (($current_page == 'ot') || ($current_page == 'index')) echo ' subpage-link-current' ?>">ÓRDENES DE TRABAJO</a>
                <a id="stats-link" href="<?php echo $base_url ?>operations/statistics/recap/<?php echo $this->user_id ?>.html" class="span3 subpage-link <?php if ($current_page == 'statistics') echo ' subpage-link-current' ?>">ESTADÍSTICA OPERATIVA</a>
            </div>

<?php echo $sub_page_content; ?>

        </div>
    </div>
    
</div><!--/span-->
</div><!--/row-->
