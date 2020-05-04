<?php
	$base_url = base_url();
?>
<link rel="stylesheet" href="<?php echo $base_url;?>ot/assets/style/main.css">
<link href="<?php echo $base_url;?>ot/assets/style/report.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $base_url;?>ot/assets/scripts/jquery.tablesorter-2.14.5.js"></script>
<script type="text/javascript" src="<?php echo $base_url;?>ot/assets/scripts/jquery.tablesorter.widgets-2.14.5.js"></script>
<script type="text/javascript" src="<?php echo $base_url;?>termometro/assets/scripts/jquery_tablesorter_2.17.8/js/jquery.tablesorter.min.js"></script>
<script>
    $(function(){
        $("#payment-table").tablesorter();
    });
</script>
<style type="text/css">
	.payment_table th { width: 100px;}
	.payment_table td { padding: 0; margin: 0}
	.delete-cobranza, .delete-cobranza:hover {
		color: #F00;
		text-decoration: none;
	} 

	.tableFixHead tbody{
	  overflow-y: auto;
	  height: 400px;
	  table-layout: fixed;
	  
	}
	.tableFixHead tbody > tr> td{
	  padding: 8px 16px;
	}
	.tableFixHead thead > tr> th{
	  position: sticky;
	  top: 0;
	  background: #eee;
	}
	.tableFixHead tfoot > tr> th{
	  position: sticky;
	  background: #eee;
	  padding: 8px 16px;
	}



	.table-fixed thead,
	.table-fixed tfoot{
	  padding: 8px 16px;
	}

	.table-fixed tbody {
	  height: 400px;
	  overflow-y: auto;
	}

	.table-fixed thead,
	.table-fixed tbody,
	.table-fixed tfoot{
	  display: block;
	}
	td { text-align: right !important; }
	.text-left { text-align: left !important; }
	.text-right { text-align: right !important; }
</style>


<div id="wait-response" style="display: none; text-align: center">Espere usted un momento, por favor...</div>
	<table class="table table-bordered tableFixHead tablesorter" id="payment-table">
		<thead>
			<tr>
				<?php foreach($values['heads'] as $head):?>
					<th><?= $head?></th>
				<?php endforeach;?>
			</tr>
			<tr>
				<?php foreach($values['values'] as $row):?>
					<?php if(($row==end($values['values']))):?>
						<tr>
							<?php for($i=0; $i<count($row); $i++): ?>
								<?php if($i==0):?>
									<th class="text-left"> <?= $row[$i] ?> </th>
								<?php else:?>
									<th class="text-right"> <?= $row[$i] ?> </th>
								<?php endif;?>
								
							<?php endfor;?>
						</tr>
					<?php endif;?>
				<?php endforeach;?>
			</tr>
		</thead>

		<tbody>
			<?php foreach($values['values'] as $row):?>
				<?php if(!($row==end($values['values']))):?>
					<tr>
						<?php for($i=0; $i<count($row); $i++): ?>
							<?php if($i==0):?>
								<td class="text-left"> <?= $row[$i] ?> </td>
							<?php else:?>
								<td class="text-right"> <?= $row[$i] ?> </td>
							<?php endif;?>
							
						<?php endfor;?>
					</tr>
				<?php endif;?>
			<?php endforeach;?>
		</tbody>
	</table>

		
