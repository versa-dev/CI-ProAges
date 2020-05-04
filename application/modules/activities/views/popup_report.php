<link rel="stylesheet" href="<?php echo base_url();?>ot/assets/style/main.css">
<link href="<?php echo base_url();?>ot/assets/style/report.css" rel="stylesheet">
<script src="<?php echo base_url();?>ot/assets/scripts/report.js"></script>
<script type="text/javascript">
	$("#popup_table").tablesorter({ 
    });
</script>

<style type="text/css">
    .bullet_red{background-color: #FF3300; border-radius: 50% 50% 50% 50%; float: left;height: 10px; margin-left: 20px; margin-top: -13px;position: absolute;width: 10px;}
    .bullet_yellow{background-color: yellow; border-radius: 50% 50% 50% 50%; float: left;height: 10px; margin-left: 20px; margin-top: -13px;position: absolute;width: 10px;}
    .bullet_green{background-color: green; border-radius: 50% 50% 50% 50%; float: left;height: 10px; margin-left: 20px; margin-top: -13px;position: absolute;width: 10px;}
    .bullet_black{background-color: black; border-radius: 50% 50% 50% 50%; float: left;height: 10px; margin-left: 20px; margin-top: -13px;position: absolute;width: 10px;}
    
</style>
<?php if($values): ?>

<table border="0" cellspacing="0" cellpadding="0" class="sortable altrowstable tablesorter" id="popup_table">
    <thead>
        <tr id="popup_tr">
            <th style="width:80px;"><div></div></th>
            <th style="width:100px;"><div>OT</div></th>
            <th style="width:110px;"><div>Fecha de ingreso</div></th>
            <th style="width:90px;"><div>Estatus</div></th>
            <th style="width:90px;"><div>Poliza</div></th>
            <th style="width:90px;"><div>Asegurado</div></th>
            <th style="width:90px;"><div>Producto</div></th>
    <?php if ($gmm != '2') { ?>
            <th style="width:90px;"><div>Plazo</div></th>
    <?php } ?>
            <th style="width:90px;"><div>Forma de pago</div></th>
            <th style="width:90px;"><div>Conducto</div></th>
            <th style="width:90px;"><div>Moneda</div></th>
            <th style="width:90px;"><div>Prima</div></th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
<?php foreach($values as $key => $value): ?>
    <tr id="tr_<?php echo $key;?>" class="tr_pop_class">
<?php echo $value['main'] ?>

    </tr>
    <tr style="display: none;" id="hide_<?php echo $key;?>">
<?php echo $value['menu'] ?>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php else: ?>
No hay datos.
<?php endif;
// Make sure UTF-8 w/o BOM àù
?>
