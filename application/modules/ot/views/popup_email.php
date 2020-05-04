<link rel="stylesheet" href="<?php echo base_url(); ?>ot/assets/style/main.css">
<script src="<?php echo base_url(); ?>ot/assets/scripts/report.js"></script>

<script type="text/javascript">

    function report_popupa()
    {
      
        var id = $('#user_id').val();
         
        var poliza = $('#poliza').val();  
        
        var gmm = $('#gmm').val(); 
       
        $.post("ot/reporte_popupa",{wrk_ord_ids:id,is_poliza:poliza,gmm:gmm},function(data)
        { 
            //alert(data);
            if(data)
            {
                $.fancybox(data);
                return false;
            }
        });
    }

</script>

<div>
    <div style="float: left" class ="emal_popup_head">Mensaje a <span id="director_name"></span></div>
    <div style="float: left" class ="emal_popup_head">OT : <span id="ot_numero"></span></div>
    </br>
    <div style="float: left" class ="emal_popup_head">Date : <span id="date"></span></span></div>
    <div style="float: left" class ="emal_popup_head">Status : <span id="status"></span></div>
    <div style="float: left" class ="emal_popup_head">Poliza Numero <span id="poliza_numero"></span></div>
    <!--    <br/>
        <div style="float: left" class ="emal_popup_head"><?php echo $username ?> <span id="poliza_numero"></span></div>-->
</div>
<div style="display: none;">
    
    <span id="policies_name"></span>
    <span id="prima"></span>
    <span id="currencies"></span>
    <span id="payment_method"></span>
    <span id="pament_interval"></span>
    <span id="policies"></span>
    <span id="product"></span>
</div>

<form id="popup_email">
    <input type="hidden" name="email_address" id="email_address"/>
    <input type="hidden" name="work_order_id" id="work_ord_array"/>
    <textarea id="email_form"></textarea>
    <br/>

    <div>
        <div id="register_but">
            <input type="hidden" name="user_id" id="user_id"/>
            <input type="hidden" name="poliza" id="poliza"/>
            <input type="hidden" name="gmm" id="gmm"/>
            <a class="" onclick='report_popupa()' href="javascript:" style="text-align:center;"> <?php echo '<img src="' . base_url() . 'ot/assets/style/register_button.png"/>'; ?></a>
        </div>
        <div style="float: right;padding-right: 20px;padding-top: 15px;">            
            <input type="submit" value="" id="mail_send_button"/>
        </div>
    </div>    
</form>