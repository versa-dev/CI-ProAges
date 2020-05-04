<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
/*

  Author:		Isinet
  Site::
  Twitter:
  Facebook:
  Github::
  Email::
  Skype::
  Location:		Mexíco


 */
$base_url = base_url();
?>

<div>
    <ul class="breadcrumb">
        <li>
            <a href="<?php echo $base_url ?>">Admin</a> <span class="divider">/</span>
        </li>
        <li>
             Tipos de cambio MXN / USD
        </li>
    </ul>
</div>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2></h2>
            <div class="box-icon">
            </div>
        </div>

        <div class="box-content">
            <?php if( isset( $message['type'] ) ): // Show Messages ?>
                <?php if ( $message['type']): ?>
            <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <img src="<?php echo $base_url ?>images/true.png" width="20" height="20" />
              <strong>Listo: </strong> <?php  echo $message['message']; // Show Dinamical message Success ?>
            </div>
                <?php else: ?>
                    <div class="alert alert-error">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                          <img src="<?php echo $base_url ?>images/false.png" width="20" height="20" />
                          <strong>Error: </strong> <?php  echo $message['message']; // Show Dinamical message error ?>
                    </div>
                <?php endif; ?>
			<?php endif; ?>


            <form id="rate-form" action="<?php echo current_url() ?>" class="form-horizontal" method="post">
                <fieldset>
                <div class="control-group" id="fecha-container"  >
                  <label class="control-label text-error" for="inputError">Seleccione una Fecha</label>
                  <div class="controls">
                    <div id="fechapicker"></div>
                    <label></label> <span id="rateDate"></span>
                    <input id="selectedDate" type="hidden" name="selected_date" value="<?php echo $selected_date; ?>">

                  </div>
                </div>

                </fieldset>
              </form>

              <table style="width: 50%">
                <thead>
                  <tr>
                    <th style="display: none">id</th>
                    <th style="width: 10%">&nbsp;</th>
                    <th style="text-align:left; width: 40%">Fecha</th>
                    <th style="width: 10%">&nbsp;</th>
                    <th style="text-align:left; width: 40%">Tipo de cambio</th>
                  </tr>
                </thead>

                <tbody>
<?php foreach ($data as $row) :
$style = ($row->date == $selected_date) ?  "; font-weight: bold" : "";
?>
                  <tr style="border-top: 1px solid #CCCCCC <?php echo $style ?>">
                    <td style="display: none"><?php echo $row->id; ?></td>
                    <td>&nbsp;</td>
                    <td><?php echo $row->date; ?></td>
                    <th style="width: 10%">&nbsp;</th>
                    <td><?php echo $row->rate; ?></td>
                  </tr>
<?php endforeach; ?>
                </tbody>

              </table>   
        </div>
    </div><!--/span-->

</div><!--/row-->

