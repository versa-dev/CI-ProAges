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
$base_url = base_url();
$is_update_page = ($this->uri->segment(2, '') == 'update');
?>

<div>
    <ul class="breadcrumb">
        <li>
            <a href="<?php echo $base_url ?>">Admin</a> <span class="divider">/</span>
        </li>
        <li>
             <?php if ($is_update_page) echo 'Editar'; else echo 'Ver'; ?> 			 
			 la configuración del sitio web
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
          <div style="text-align: right">
            <a class="btn btn-primary" href="<?php echo site_url('settings/xrate_list') ?>">Tipos de cambio</a>
          </div>


        <?php
			$validation = validation_errors(); // Validation and upload errors if any
			if( !empty( $validation ) || (!empty( $other_errors )) ): ?>
            <div class="alert alert-error">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <strong>Error: </strong> <?php echo $validation . "\n" . implode("\n", $other_errors); ?>
            </div>
            <?php endif; ?>

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

<?php if ($data) : ?>
            <form id="form" action="<?php echo current_url() ?>" class="form-horizontal" method="post"  enctype="multipart/form-data">
                <fieldset>
<?php foreach ($data as $field_name => $field_value) : ?>
                  <div class="control-group">
                    <label class="control-label" for="<?php echo $field_name ?>"><?php echo $field_value->name ?></label>
                    <div class="controls">
<?php switch ($field_value->form_type) :
	case 'image':
		if ($is_update_page): ?>
                      <input class="input-xlarge focused <?php if (in_array('required', $field_value->validation_arr)) echo 'required' ?>" id="<?php echo $field_name ?>" name="<?php echo $field_name ?>" type="file"  title="<?php echo $field_value->tooltip ?>" />
<?php endif;
		if (($field_value->value) && is_file(FCPATH . 'images/' . $field_value->value))
		{
			$image_size = @getimagesize(FCPATH . 'images/' . $field_value->value);
			if ($image_size)
				echo '<img src="' . $base_url . 'images/' . $field_value->value . '" ' . $image_size[3] . ' />';
		}
	break;
	case 'checkbox': ?>
                      <input class="input-xlarge focused <?php if (in_array('required', $field_value->validation_arr)) echo 'required' ?>" id="<?php echo $field_name ?>" name="<?php echo $field_name ?>" type="checkbox" value="1" <?php echo set_checkbox($field_name, '1', (bool) $field_value->value); ?>  title="<?php echo $field_value->tooltip ?>"  <?php if (!$is_update_page) echo 'readonly="readonly"' ?> />
<?php 	break;
	default: ?>					  
                      <input class="input-xlarge focused <?php if (in_array('required', $field_value->validation_arr)) echo 'required' ?>" id="<?php echo $field_name ?>" name="<?php echo $field_name ?>" type="text" value="<?php echo set_value($field_name, $field_value->value) ?>" title="<?php echo $field_value->tooltip ?>"  <?php if (!$is_update_page) echo 'readonly="readonly"' ?> />
<?php endswitch; ?>	
                    </div>
                  </div>
<?php endforeach; ?>

                  <div id="actions-buttons-forms" class="form-actions">
<?php if ($is_update_page): ?>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a class="btn" href="<?php echo $base_url . 'settings/view.html' ?>">Cancelar</a>
<?php elseif ($this->access_update): ?>
                    <a class="btn btn-primary" href="<?php echo $base_url . 'settings/update.html' ?>">Editar</a>
<?php endif; ?>
                  </div>

                </fieldset>
              </form>
<?php endif; ?>
        </div>
    </div><!--/span-->

</div><!--/row-->

