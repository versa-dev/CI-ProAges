<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
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
?>
<div class="box">

 <div class="box-content">
	
    
    
    
    <table class="table table-bordered">
		
        <thead>
            <tr>
                <th>Mes</th>
                <th>Vida</th>
                <th>GMM</th>
                <th>Autos</th>
            </tr>
        </thead>
        
       
       	<tbody>
        <?php if( !empty( $data ) ): foreach( $data as $value ): ?>
          
            <tr>
                <td><?php echo  $value['month'] ?></td>
                <td><div id="<?php echo $value['id'] ?>-1" class="value"><?php echo  $value['vida'] ?></div><div id="value-input-<?php echo $value['id'] ?>-1" class="input"><input type="text" value="<?php echo  $value['vida'] ?>"  onblur="save( '<?php echo $value['id'] ?>-1', this.value )"/></div></td>
                <td><div id="<?php echo $value['id'] ?>-2" class="value"><?php echo  $value['gmm'] ?></div><div id="value-input-<?php echo $value['id'] ?>-2" class="input"><input type="text" value="<?php echo  $value['gmm'] ?>"  onblur="save( '<?php echo $value['id'] ?>-2', this.value )"/></div></td>
                <td><div id="<?php echo $value['id'] ?>-3" class="value"><?php echo  $value['autos'] ?></div><div id="value-input-<?php echo $value['id'] ?>-3" class="input"><input type="text" value="<?php echo  $value['autos'] ?>" onblur="save( '<?php echo $value['id'] ?>-3', this.value )" /></div></td>
            </tr>
        
        <?php endforeach; endif; ?>
        
        </tbody>
        
       
     </table>
	
    
        
 </div>    
</div>