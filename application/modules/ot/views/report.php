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
$selected_period = get_filter_period();
?>

<div>
    <ul class="breadcrumb">
        <li>
            <a href="<?php echo base_url() ?>">Admin</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="<?php echo base_url() ?>ot.html">Orden de trabajo</a> <span class="divider">/</span>
        </li>               
        <li>
            Reporte
        </li>
    </ul>
</div>

<div class="row-fluid sortable">		
    <div class="box span12">      
        <div class="box-content">
            <?php // Show Messages ?>            
            <?php if (isset($message['type'])): ?>            
                <?php if ($message['type'] == true): ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <img src="<?php echo base_url() ?>images/true.png" width="20" height="20" />
                        <strong>Listo: </strong> <?php echo $message['message']; // Show Dinamical message Success  ?>
                    </div>
                <?php endif; ?>

                <?php if ($message['type'] == false): ?>
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <img src="<?php echo base_url() ?>images/false.png" width="20" height="20" />
                        <strong>Error: </strong> <?php echo $message['message']; // Show Dinamical message error  ?>
                    </div>
                <?php endif; ?>			
            <?php endif; ?> 
<?php if ($other_filters['ramo'] != 3) echo $report_columns; ?>

            <div class="row">
                <div class="span11" style="margin-left:40px; width:95%">
                    <div class="main-container">
                        <div class="main clearfix">                               
                            <a href="javascript:void(0);" class="links-menu btn btn-link link-ramo" id="vida" <?php if ($other_filters['ramo'] == 1) echo 'style="color:#06F"' ?>>Vida</a>
                            <a href="javascript:void(0);" class="links-menu btn btn-link link-ramo" id="gmm" <?php if ($other_filters['ramo'] == 2) echo 'style="color:#06F"' ?>>GMM</a>
                            <a href="javascript:void(0);" class="links-menu btn btn-link link-ramo" id="autos" <?php if ($other_filters['ramo'] == 3) echo 'style="color:#06F"' ?>>Autos</a>

                            <p class="line">&nbsp; </p>
                           <form id="form" method="post">
                                <input type="hidden" name="query[ramo]" id="ramo" value="<?php echo $other_filters['ramo'] ?>" />

<?php echo $filter_view ?>

                            </form>

                            <?php
                            if (empty($_POST) or isset($_POST['query']['ramo']) and $_POST['query']['ramo'] != 3) { 
                                
                                if(!empty($_POST) and $_POST['query']['ramo']==1) {
                                
                                 	$this->load->view('report1', array('data' => $data, 'tata'=>$tata['query']['ramo']));
                                
                                }
                                elseif(!empty($_POST) and $_POST['query']['ramo']==2) {

                                 	$this->load->view('report2', array('data' => $data, 'tata'=>$tata['query']['ramo']));
                                     
                                }
                                else
                                   	$this->load->view('report1', array('data' => $data)) ; 
                             }
                             else
                                 	$this->load->view('report3', array('data' => $data));
                             ?>
                                                          

                        </div> <!-- #main -->
                    </div> <!-- #main-container -->
                </div>                                                                                                 	
            </div>
        </div>               
    </div>
    
</div><!--/span-->
</div><!--/row--><?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
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
$selected_period = get_filter_period();
?>

<div>
    <ul class="breadcrumb">
        <li>
            <a href="<?php echo base_url() ?>">Admin</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="<?php echo base_url() ?>ot.html">Orden de trabajo</a> <span class="divider">/</span>
        </li>               
        <li>
            Reporte
        </li>
    </ul>
</div>

<div class="row-fluid sortable">		
    <div class="box span12">      
        <div class="box-content">
            <?php // Show Messages ?>            
            <?php if (isset($message['type'])): ?>            
                <?php if ($message['type'] == true): ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <img src="<?php echo base_url() ?>images/true.png" width="20" height="20" />
                        <strong>Listo: </strong> <?php echo $message['message']; // Show Dinamical message Success  ?>
                    </div>
                <?php endif; ?>

                <?php if ($message['type'] == false): ?>
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <img src="<?php echo base_url() ?>images/false.png" width="20" height="20" />
                        <strong>Error: </strong> <?php echo $message['message']; // Show Dinamical message error  ?>
                    </div>
                <?php endif; ?>			
            <?php endif; ?> 
<?php if ($other_filters['ramo'] != 3) echo $report_columns; ?>

            <div class="row">
                <div class="span11" style="margin-left:40px; width:95%">
                    <div class="main-container">
                        <div class="main clearfix">                               
                            <a href="javascript:void(0);" class="links-menu btn btn-link link-ramo" id="vida" <?php if ($other_filters['ramo'] == 1) echo 'style="color:#06F"' ?>>Vida</a>
                            <a href="javascript:void(0);" class="links-menu btn btn-link link-ramo" id="gmm" <?php if ($other_filters['ramo'] == 2) echo 'style="color:#06F"' ?>>GMM</a>
                            <a href="javascript:void(0);" class="links-menu btn btn-link link-ramo" id="autos" <?php if ($other_filters['ramo'] == 3) echo 'style="color:#06F"' ?>>Autos</a>

                            <p class="line">&nbsp; </p>
                           <form id="form" method="post">
                                <input type="hidden" name="query[ramo]" id="ramo" value="<?php echo $other_filters['ramo'] ?>" />

<?php echo $filter_view ?>

                            </form>

                            <?php
                            print_r($data);
                            if (empty($_POST) or isset($_POST['query']['ramo']) and $_POST['query']['ramo'] != 3) { 
                                
                                if(!empty($_POST) and $_POST['query']['ramo']==1) {
                                
                                 	$this->load->view('report1', array('data' => $data, 'tata'=>$tata['query']['ramo']));
                                
                                }
                                elseif(!empty($_POST) and $_POST['query']['ramo']==2) {

                                 	$this->load->view('report2', array('data' => $data, 'tata'=>$tata['query']['ramo']));
                                     
                                }
                                else
                                   	$this->load->view('report1', array('data' => $data)) ; 
                             }
                             else
                                 	$this->load->view('report3', array('data' => $data));
                             ?>
                                                          

                        </div> <!-- #main -->
                    </div> <!-- #main-container -->
                </div>                                                                                                 	
            </div>
        </div>               
    </div>
    
</div><!--/span-->
</div><!--/row-->