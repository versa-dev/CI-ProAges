                        <td></td>
                        <td colspan="<?php if ($gmm != 2) echo 11; else echo 10 ?>" >
                            <div style="display: none;">
                                <span id="poliza_number"><?php echo $value->policies_uid;?></span>
                                <span id="ot_number"><?php echo $value->work_order_uid;?></span>
                                <span class="wrk_ord_ids" id="<?php echo $value->work_order_id;?>"></span>
                                <span class="poliza"><?php if ($value->work_order_status_id == 7) echo 'yes'; else echo 'no';?></span>
                                <span class="gmm"><?php echo $gmm;?></span>
                                <span class="date"><?php echo $value->creation_date;?></span>
                                <span class="status"><?php echo $value->work_order_status_name;?></span>
                                <span class="director_name" id="<?php foreach($director as $demals){echo $demals->name.',';};?>"></span>
                                <span class="agent_name"><?php echo $value->name; ?></span>
                                <span class="policies_name"><?php echo $value->policies_name; ?></span>
                                <span class="product"><?php echo $value->products_name; ?></span>
                                <span class="policies"><?php echo $value->policies_period; ?></span>
                                <span class="pament_interval"><?php echo $value->payment_intervals_name; ?></span>
                                <span class="payment_method"><?php echo $value->payment_methods_name; ?></span>
                                <span class="currencies"><?php echo $value->currencies_name; ?></span>
                                <span class="prima"><?php echo $value->prima; ?></span>
                            </div>
                            
                            <a href="javascript:" class="btn btn-link btn-hide">
                                <i class="icon-arrow-up" id="<?php echo $value->work_order_id;?>"></i>
                            </a>
                            <?php if($value->user != 0){?><a id="<?php echo $value->email; ?>" rel="<?php echo $value->name; ?>"  href="<?php echo base_url().'ot/email_popup/'?>" class="btn btn-link send_message">Enviar mensaje al cordinador</a>|<?php }?>
                            <a id="<?php echo $value->agent_user_email; ?>" rel="<?php echo $value->name; ?>" href="<?php echo base_url().'/ot/email_popup/'?>" class="btn btn-link send_message">Enviar mensaje al Agente</a>|                            
                            <a id ="<?php foreach($director as $demals){echo $demals->email.',';};?>" rel="<?php foreach($director as $demals){echo $demals->name.',';};?>" href="<?php echo base_url().'/ot/email_popup/'?>" class="btn btn-link send_message">Enviar mensaje al Director</a>                            
                        </td>
                        <td>&nbsp;</td>
<?php
// Make sure UTF-8 w/o BOM àù
?>