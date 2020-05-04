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



// Render table for user
function renderTable( $data = array(), $access_update = false, $access_delete = false ){

	if( empty( $data ) ) return false;
	
	
	$table = null;
	
	foreach( $data as $value ){ 
	
    
    
    		$table .= '<tr>
							<td class="center">'. $value['clave'] .'</td>
							<td class="center">'. $value['national'] .'</td>
							<td class="center">'. $value['provincial'] .'</td>
							<td class="center">'. $value['manager_id'] .'</td>
							<td class="center">';
							
							if( !empty( $value['company_name'] ) )
								$table .= $value['company_name'];
							
							else 
								$table .= $value['name'] . ' ' . $value['lastnames'];
								
			$table .=	 '</td>';
							
				
				
			$table .=	   '<td class="center">'. $value['email'] .'</td>
							<td class="center">'. $value['tipo'] .'</td>
							<td class="center">'. $value['date'] .'</td>
							<td class="center">'. $value['last_updated'] .'</td><td>';
				
				if( $access_update == true )	
					$table .= ' <a class="btn btn-info" href="'. base_url() .'usuarios/update/'. $value['id'] .'.html" title="Editar Usuario">
									<i class="icon-edit icon-white"></i>            
								</a>';	
				
				if( $access_delete == true )
				 	$table .= '<a class="btn btn-danger" href="'. base_url() .'usuarios/delete/'. $value['id'] .'.html" title="Eliminar Usuario">
									<i class="icon-trash icon-white"></i> 
								</a>';
				
					
			$table .='</td></tr>';
    
    
	
	}
	
	
	return $table;
}


?>