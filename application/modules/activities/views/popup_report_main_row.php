                        <td style="width:80px;">
                            <div>
                                <?php       
                                    if($value->comments)
                                    {
                                        echo '<img src="'.base_url().'ot/assets/images/comment.png" title="'.$value->comments.'" width="12" height="12"/>';
                                    }
                                    if($value->work_order_status_id == 9 || $value->work_order_status_id == 5)
                                    {
                                        $result =  abs(strtotime($value->creation_date) - strtotime(date("Y-m-d H:i:s")));                                
                                        $date_diff =  floor($result/(60*60*24));
                                        if($date_diff>$value->duration)
                                        {
                                            echo '<div class="bullet_red"></div>';
                                        }  
                                        if($date_diff>($value->duration)/2 && $date_diff <= $value->duration)
                                        {
                                             echo '<div class="bullet_yellow"></div>'; 
                                        }
                                        if($date_diff <= ($value->duration)/2)
                                        {
                                            echo '<div class="bullet_green"></div>';
                                        } 
                                    } 
                                    if($value->work_order_status_id == 6)
                                    {
                                             echo '<div class="bullet_black"></div>';                                

                                    }                                                     
                                ?>
                            </div>
                        </td>
                        <td style="width:100px;">
<div>
<?php 
if ( $this->access_update)
	echo anchor('ot/ver_ot/' . $value->work_order_id, $value->work_order_uid, array('target' => '_blank', 'title' => 'Ver OT'));
else
	echo $value->work_order_uid;
?>
</div></td>
                        <td style="width:110px;"><div><?php echo $value->creation_date;?></div></td>
                        <td style="width:90px;"><div><?php echo $value->work_order_status_name;?></div></td>
                        <td style="width:90px;"><div>
                        <?php if ($value->work_order_status_id == 7) echo $value->policies_uid; else echo '-';?>
						</div></td>
                        <td style="width:90px;"><div><?php echo $value->policies_name;?></div></td>
                        <td style="width:90px;"><div><?php echo $value->products_name;?></div></td>
                        <?php if ($gmm != '2') { ?>
                        <td style="width:90px;"><div><?php echo $value->policies_period;?></div></td>
                         <?php } ?>
                        <td style="width:90px;"><div><?php echo $value->payment_intervals_name;?></div></td>
                        <td style="width:90px;"><div><?php echo $value->payment_methods_name;?></div></td>
                        <td style="width:90px;"><div><?php echo $value->currencies_name;?></div></td>
                        <td style="width:90px;">
						<div>$<?php echo number_format($value->adjusted_prima, 2);?>
						</div></td>
                        <td>
<?php if ( $this->access_ot_update && ($value->is_ntuable) ) : ?>
                          <img style="cursor: pointer" class="mark-ntu ot-action" id="mark_ntu-<?php echo $value->work_order_id . '-' . $gmm . '-' . $is_poliza . '-' .  $value->user_id ?>" alt="Marcar como NTU" title="Marcar como NTU" src="<?php echo base_url()?>images/small-red-x.png" />
                          <img style="cursor: pointer" class="mark-pagada ot-action" id="mark_pagada-<?php echo $value->work_order_id . '-' . $gmm . '-' . $is_poliza . '-' .  $value->user_id ?>" alt="Marcar como pagada" title="Marcar como pagada" src="<?php echo base_url()?>images/coin_stacks_copper_edit.png" />
<?php endif;?>
						</td>
<?php
// Make sure UTF-8 w/o BOM àù
?>
