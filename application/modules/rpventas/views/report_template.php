<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="row-fluid sortable">
    <div class="box span12">      
        <div class="box-content"> <!-- To tweak left padding -->
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

            <?php echo $sub_page_content; ?>

        </div>
    </div>
    
</div><!--/span-->
</div><!--/row-->
