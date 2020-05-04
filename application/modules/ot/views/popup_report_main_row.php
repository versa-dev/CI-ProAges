                        <td style="width:80px;">
                            <div>
                                <?php       
                                    if($value['general'][0]->comments)
                                    {
                                        echo '<img src="'.base_url().'ot/assets/images/comment.png" title="'.$value['general'][0]->comments.'" width="12" height="12"/>';
                                    }
                                
                                    if($value['general'][0]->work_order_status_id == 9 || $value['general'][0]->work_order_status_id == 5)
                                    {
                                        $result =  abs(strtotime($value['general'][0]->creation_date) - strtotime(date("Y-m-d H:i:s")));                                
                                        $date_diff =  floor($result/(60*60*24));

                                        if($date_diff>$value['general'][0]->duration)
                                        {
                                            echo '<div class="bullet_red"></div>';
                                        }  

                                        if($date_diff>($value['general'][0]->duration)/2 && $date_diff <= $value['general'][0]->duration)
                                        {
                                             echo '<div class="bullet_yellow"></div>'; 
                                        }

                                        if($date_diff <= ($value['general'][0]->duration)/2)
                                        {
                                            echo '<div class="bullet_green"></div>';
                                        } 
                                    } 

                                    if($value['general'][0]->work_order_status_id == 6)
                                    {
                                    
                                             echo '<div class="bullet_black"></div>';                                
                                     
                                    }                                                     
                                ?>
                            </div>
                        </td>
                        <td style="width:100px;">
<div>
<?php
if ( $access_update)
	echo anchor('ot/ver_ot/' . $value['general'][0]->work_order_id, $value['general'][0]->work_order_uid, array('target' => '_blank', 'title' => 'Ver OT'));
//	echo '<a href="'.   . '" target="_blank" title="Ver OT">' . $value['general'][0]->work_order_uid . '</a>'
else
	echo $value['general'][0]->work_order_uid;
?>
</div></td>
                        <td style="width:110px;"><div><?php echo $value['general'][0]->creation_date;?></div></td>
                        <td class="ot-status-container" style="width:90px;"><div class="ot-status"><?php echo $value['general'][0]->work_order_status_name;?></div></td>
                        <?php if($is_poliza == 'yes'){ ?>
                        <td style="width:90px;"><div><?php echo $value['general'][0]->policies_uid;?></div></td>
                        <?php }?>
                        <td style="width:90px;"><div><?php echo $value['general'][0]->policies_name;?></div></td>
                        
                        <td style="width:90px;"><div><?php echo $value['general'][0]->products_name;?></div></td>
                        <?php if($gmm !== '2') { ?>
                        <td style="width:90px;"><div><?php echo $value['general'][0]->policies_period;?></div></td>
                         <?php } ?>
                        <td style="width:90px;"><div><?php echo $value['general'][0]->payment_intervals_name;?></div></td>
                        
                        <td style="width:90px;"><div><?php echo $value['general'][0]->payment_methods_name;?></div></td>
                        <td style="width:90px;"><div><?php echo $value['general'][0]->currencies_name;?></div></td>
                        <td style="width:90px;">
						<div>$<?php echo number_format($value['general'][0]->adjusted_prima, 2);?>
						</div>
                        </td>
                        <td style="width:90px;">
						<div>$<?php echo number_format($value['general'][0]->adjusted_allocated_prime, 2);?>
						</div>
                        </td>
                        <td style="width:90px;">
						<div>$<?php echo number_format($value['general'][0]->adjusted_bonus_prime, 2);?>
						</div>
                        </td>
                        <td>
<?php if ( $access_update && ($value['general'][0]->is_ntuable) ) : ?>
                          <img style="cursor: pointer" class="mark-ntu ot-action" id="mark_ntu-<?php echo $value['general'][0]->work_order_id . '-' . $gmm . '-' . $is_poliza . '-' . $value['general'][0]->user_id ?>" alt="Marcar como NTU" title="Marcar como NTU" src="<?php echo base_url()?>images/small-red-x.png" />
                          <img style="cursor: pointer" class="mark-pagada ot-action" id="mark_pagada-<?php echo $value['general'][0]->work_order_id . '-' . $gmm . '-' . $is_poliza . '-' . $value['general'][0]->user_id ?>" alt="Marcar como pagada" title="Marcar como pagada" src="<?php echo base_url()?>images/coin_stacks_copper_edit.png" />
<?php endif;?>
						</td>
<?php
// Make sure UTF-8 w/o BOM àù
?>
