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
?>  <input type="hidden" id="saves" name="saves" value="<?php if( !empty( $data ) ) echo 1; else echo 0; ?>" />    

 <div class="box-content">
	
    <!--style="margin-top:400px; position:fixed; z-index:1000; background:#F9F9F9; color:#fff; width:38%;"-->
    <table class="table-totals" style="background:#F9F9F9; color:#fff; width:73%;position:fixed !important;
	bottom:0px;">
    
     <tr>
           <td class="totales" style="width:650px;"><b style="color:#547EBD !important">INGRESO TOTAL:</b></td>

           <td style="text-align:right">
              <p style="color:#547EBD !important; float:right" id="ingresoTotal_text">$ <?php if( isset( $data->ingresoTotal ) ) echo $data->ingresoTotal; else echo 0; ?></p>	
              <input type="hidden" name="ingresoBonoRenovacion" id="ingresoTotal" value="<?php if( isset( $data->ingresoTotal ) ) echo $data->ingresoTotal; else echo 0; ?>">
           </td>
            <td style="vertical-align:middle"><input type="button" id="open_meta" value="Ver distribución anual" class="pull-right btn-save-meta" style="margin-top:10px;" /></td>
          

        </tr>

        <tr>

            <td class="totales"><b style="color:#547EBD !important">INGRESO PROMEDIO MENSUAL:</b></td>

           <td style="text-align:right">
			  <p style="color:#547EBD !important; float:right" id="inresoPromedioMensual_text">$ <?php if( isset( $data->ingresoBonoRenovacion ) ) echo $data->ingresoBonoRenovacion; else echo 0; ?></p>	
              <input type="hidden" name="ingresoBonoRenovacion" id="inresoPromedioMensual" value="<?php if( isset( $data->ingresoBonoRenovacion ) ) echo $data->ingresoBonoRenovacion; else echo 0; ?>">

           </td>
           <td style="vertical-align:middle"><input type="button" value="Guardar Meta" class="pull-right btn-save-meta" style="margin-top:10px;" onclick="save();"/></td>

        </tr>
        
    </table>    
    
    
    
    
    
    <table class="table table-bordered">
		
        <tr>
        	<td colspan="4"><h6>Primer trimestre:</h6></td>
        </tr>
        
        <tr>
           <td><p>Primas del trimestre:</p>
           <input type="hidden" name="simulatorprimasprimertrimestre" id="simulatorprimasprimertrimestre" value="<?php if( isset( $data->simulatorprimasprimertrimestre ) ) echo $data->simulatorprimasprimertrimestre; else echo 0; ?>" />
           </td>
           <td><div id="simulator-primas-primer-trimestre">$ <?php if( isset( $data->simulatorprimasprimertrimestre ) ) echo $data->simulatorprimasprimertrimestre; else echo 0; ?></div></td>		   
           <td><p>Ingresos del trimestre</p><input type="hidden" name="simulatoringresosprimertrimestre" id="simulatoringresosprimertrimestre" value="<?php if( isset( $data->simulatoringresosprimertrimestre ) ) echo $data->simulatoringresosprimertrimestre; else echo 0; ?>" /></td>
           <td><div id="simulator-ingresos-primer-trimestre">$ <?php if( isset( $data->simulatoringresosprimertrimestre ) ) echo $data->simulatoringresosprimertrimestre; else echo 0; ?></div></td>
        </tr>
        
        <tr>
        	<td colspan="4"><h6>Segundo trimestre:</h6></td>
        </tr>
        
        <tr>
           <td><p>Primas del trimestre:</p><input type="hidden" name="simulatorprimassegundotrimestre" id="simulatorprimassegundotrimestre" value="<?php if( isset( $data->simulatorprimassegundotrimestre ) ) echo $data->simulatorprimassegundotrimestre; else echo 0; ?>" /></td>
           <td><div id="simulator-primas-segundo-trimestre">$ <?php if( isset( $data->simulatorprimassegundotrimestre ) ) echo $data->simulatorprimassegundotrimestre; else echo 0; ?></div></td>		   
           <td><p>Ingresos del trimestre</p><input type="hidden" name="simulatoringresossegundotrimestre" id="simulatoringresossegundotrimestre" value="<?php if( isset( $data->simulatoringresossegundotrimestre ) ) echo $data->simulatoringresossegundotrimestre; else echo 0; ?>" /></td>
           <td><div id="simulator-ingresos-segundo-trimestre">$ <?php if( isset( $data->simulatoringresossegundotrimestre ) ) echo $data->simulatoringresossegundotrimestre; else echo 0; ?></div></td>
        </tr>
        
        <tr>
        	<td colspan="4"><h6>Tercer trimestre:</h6></td>
        </tr>
        
        <tr>
           <td><p>Primas del trimestre:</p><input type="hidden" name="simulatorprimastercertrimestre" id="simulatorprimastercertrimestre" value="<?php if( isset( $data->simulatorprimastercertrimestre ) ) echo $data->simulatorprimastercertrimestre; else echo 0; ?>" /></td>
           <td><div id="simulator-primas-tercer-trimestre">$ <?php if( isset( $data->simulatorprimastercertrimestre ) ) echo $data->simulatorprimastercertrimestre; else echo 0; ?></div></td>		   
           <td><p>Ingresos del trimestre</p><input type="hidden" name="simulatoringresostercertrimestre" id="simulatoringresostercertrimestre" value="<?php if( isset( $data->simulatoringresostercertrimestre ) ) echo $data->simulatoringresostercertrimestre; else echo 0; ?>" /></td>
           <td><div id="simulator-ingresos-tercer-trimestre">$ <?php if( isset( $data->simulatoringresostercertrimestre ) ) echo $data->simulatoringresostercertrimestre; else echo 0; ?></div></td>
        </tr>
        
        <tr>
           <td><br></td>
           <td><br></td>
           <td><br></td>
           <td><br></td>
        </tr>
        
		
        <!--<tr>
           <td><label>Periodo:</label></td>
           <td><select name="periodo" id="periodo" class="input-small">

                 <option value="4" <?php if( isset( $data->periodo ) and $data->periodo == 4 ) echo 'selected="selected"'; ?>>CUATRIMESTRAL</option>

                 <option value="12" <?php if( isset( $data->periodo ) and $data->periodo == 12 ) echo 'selected="selected"'; ?>>ANUAL</option>

              </select></td>           
           <td><br></td>
           <td><br></td>
        </tr>
        
        
        <tr>
           <td><br></td>
           <td><br></td>           
           <td><br></td>
           <td><br></td>
        </tr>-->
        

        <tr>           		   
           <td>
           <input type="hidden" name="periodo" id="periodo" value="12">
           <label>Primas netas iniciales anuales:</label></td>
           <td>
              <input type="text" class="input-small" name="primasnetasiniciales" id="primasnetasiniciales" value="<?php if( isset( $data->primasnetasiniciales ) ) echo $data->primasnetasiniciales; else if( isset( $data->primasAfectasInicialesUbicar ) ) echo $data->primasAfectasInicialesUbicar; else echo 0; ?>">
           </td>       
           <td><label>Primas de carteras:</label></td>
           <td><input type="text" class="input-small" name="primasdecarteras" id="primasdecarteras" value="<?php if( isset( $data->primasdecarteras ) ) echo $data->primasdecarteras; else echo 0; ?>"></td>    
        </tr>

        <tr>
           <td><label>Primas promedio</label></td>
           <td><input type="text" class="input-small" name="primaspromedio" id="primaspromedio" value="<?php if( isset( $data->primaspromedio ) ) echo $data->primaspromedio; elseif( isset( $data->primas_promedio ) ) echo $data->primas_promedio; else echo 0; ?>"></td> 
           <td><label>No Negocios:</label></td>
           <td><input type="text" readonly="readonly" class="input-small" name="nonegocios" id="nonegocios" value="<?php if( isset( $data->nonegocios ) ) echo $data->nonegocios; else echo 0; ?>"></td>
        </tr>

        <tr>
           <td><label style="color:#547EBD !important">Primas totales:</label></td>		
           <td>			  
              <p style="color:#547EBD !important; float:right" id="primastotales_text">$ <?php if( isset( $data->primastotales ) ) echo $data->primastotales; else echo 0; ?></p>	
              <input type="hidden" name="primastotales" id="primastotales" value="<?php if( isset( $data->primastotales ) ) echo $data->primastotales; else echo 0; ?>">
           </td>
           <td><br></td>
           <td><br></td>                          
        </tr>
  		
        <tr>
           <td><br></td>
           <td><br></td>           
           <td><br></td>
           <td><br></td>
        </tr>
        
        
       
        <tr>
           <td colspan="4" align="left"><b style="color:#547EBD !important">COMISIONES</b></td>
        </tr>
        
        <tr>
           <td><label>% de comisión:</label></td>
           <td>
              <input type="text" class="input-small" name="comisionVentaInicial" id="comisionVentaInicial" value="<?php if( isset( $data->comisionVentaInicial ) ) echo $data->comisionVentaInicial; else echo 0; ?>">
           </td>
           <td><label style="color:#547EBD !important">Ingreso por comisiones:</label></td>
           <td>			  
              <p style="color:#547EBD !important; float:right" id="ingresoComisionesVentaInicial_text">$ <?php if( isset( $data->ingresoComisionesVentaInicial ) ) echo $data->ingresoComisionesVentaInicial; else echo 0; ?></p>	
              <input type="hidden" name="ingresoComisionesVentaInicial" id="ingresoComisionesVentaInicial" value="<?php if( isset( $data->ingresoComisionesVentaInicial ) ) echo $data->ingresoComisionesVentaInicial; else echo 0; ?>">           
           </td>
        </tr>
        
        
        <tr>
		   <td colspan="4" align="left"><b style="color:#547EBD !important">BONO INICIAL</b></td>
        </tr>

        <tr>
           <td><label>% de bono aplicado:</label></td>
           <td>
              <input type="text" class="input-small" name="bonoAplicado" id="bonoAplicado" value="<?php if( isset( $data->bonoAplicado ) ) echo $data->bonoAplicado; else echo 0; ?>" readonly="readonly">
           </td>
           <td><label style="color:#547EBD !important">Ingreso por inicial:</label></td>
           <td>			  
              <p style="color:#547EBD !important; float:right" id="ingresoBonoInicial_text">$ <?php if( isset( $data->ingresoBonoInicial ) ) echo $data->ingresoBonoInicial; else echo 0; ?></p>	
              <input type="hidden" name="ingresoBonoInicial" id="ingresoBonoInicial" value="<?php if( isset( $data->ingresoBonoInicial ) ) echo $data->ingresoBonoInicial; else echo 0; ?>">
                         </td>     
        </tr>
		
        <tr>
           <td><br></td>
           <td><br></td>           
           <td><br></td>
           <td><br></td>
        </tr>
                

        <tr>

           <td colspan="2" align="left"><b style="color:#547EBD !important">BONO DE CARTERA</b></td>
		   <td><br></td>
           <td><br></td>
        </tr>

        <tr>

           <td><label>% de incremento en primas netas pagadas:</label></td>

           <td>
			  <select name="porincrementoenprimas" id="porincrementoenprimas" class="input-small">
              		<option value="">Seleccione</option>
                    <option value="0" <?php if( isset( $data->porincrementoenprimas ) and $data->porincrementoenprimas == 0 ) echo 'selected="selected"'?>>0</option>
                    <option value="5" <?php if( isset( $data->porincrementoenprimas ) and $data->porincrementoenprimas == 5 ) echo 'selected="selected"'?>>5</option>
                    <option value="10" <?php if( isset( $data->porincrementoenprimas ) and $data->porincrementoenprimas == 10 ) echo 'selected="selected"'?>>10</option>
                    
              </select>
              
           </td>
           
           <td><label>% de bono ganado:</label></td>

           <td><input readonly="readonly" type="text" name="porbonoganado" id="porbonoganado" value="<?php if( isset( $data->porbonoganado ) ) echo $data->porbonoganado; else echo 0; ?>"></td>

        </tr>

        <tr>

           <td><label style="color:#547EBD !important">Ingreso por bono de cartera:</label></td>

           <td>
			  
              <p style="color:#547EBD !important; float:right" id="ingresoBonoCartera_text">$ <?php if( isset( $data->ingresoBonoCartera ) ) echo $data->ingresoBonoCartera; else echo 0; ?></p>	
              <input type="hidden" name="ingresoBonoCartera" id="ingresoBonoCartera" value="<?php if( isset( $data->ingresoBonoCartera ) ) echo $data->ingresoBonoCartera; else echo 0; ?>" >

           </td>
           
           <td><br></td>

           <td><br></td>

        </tr>
       
     </table>
	
     
    
        
 </div>    