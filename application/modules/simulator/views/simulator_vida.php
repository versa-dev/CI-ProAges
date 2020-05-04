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
           <td class="totales" style="width:33%"><b style="color:#547EBD !important">INGRESO TOTAL:</b></td>

           <td style="text-align:right; width:34%">
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
           <!--<td style="vertical-align:middle"><input type="button" value="Guardar Meta" class="pull-right btn-save-meta" style="margin-top:10px;" onclick="save();"/></td>-->

        </tr>
        
    </table>    

        <table width="100%">
                <tr>
           <td>
           <input type="hidden" name="periodo" id="periodo" value="12">
           <label>Primas afectas iniciales anuales (venta inicial):</label></td>
           <td>
           <input type="text" class="input-small" name="primasAfectasInicialesUbicar" id="primasAfectasInicialesUbicar" value="<?php if( isset( $data->primasAfectasInicialesUbicar ) ) echo $data->primasAfectasInicialesUbicar; else if( isset( $data->primasnetasiniciales ) ) echo $data->primasnetasiniciales; else echo 0; ?>">
           <input type="hidden" class="input-small" name="primasnetasiniciales" id="primasnetasiniciales" value="<?php if( isset( $data->primasnetasiniciales ) ) echo $data->primasnetasiniciales; else if( isset( $data->primasAfectasInicialesUbicar ) ) echo $data->primasAfectasInicialesUbicar; else echo 0; ?>">
           </td>		   
           <td><label>% de acotamiento:</label></td>
           <td><input type="text" class="input-small" name="porAcotamiento" id="porAcotamiento" value="<?php if( isset( $data->porAcotamiento ) ) echo $data->porAcotamiento; else echo 0; ?>"></td>
        </tr>
       
        <tr>

           <td><label>Prima promedio:</label></td>

           <td>

              <input type="text" class="input-small" name="primas_promedio" id="primas_promedio" value="<?php if( isset( $data->primaspromedio ) ) echo $data->primaspromedio; elseif( isset( $data->primas_promedio ) ) echo $data->primas_promedio; else echo 0; ?>">

           </td>
           
           <td><label>No. de Negocios PAI:</label></td>

           <td> <input type="text" readonly="readonly" class="input-small" name="noNegocios" id="noNegocios" value="<?php if( isset( $data->noNegocios ) ) echo $data->noNegocios; else echo 0; ?>">
</td>
          
        </tr>
        </table>

    <table class="table table-bordered">
		
        <tr onClick="ShowHideRow(1)" style="cursor: pointer;">
        	<td colspan="4" style="background-color:#CCCCCC"><h6>Primer trimestre: <span style="color:rgb(32,135,201)"><span id="showRow1" style="text-align:right;">Mostrar</span> simulador <span id="Arrow1">&darr;</span></span></h6></td>
        </tr>
        
        <tr onClick="ShowHideRow(1)" style="cursor: pointer;">
           <td><p>Primas del trimestre:</p>
           <input type="hidden" name="simulatorprimasprimertrimestre" id="simulatorprimasprimertrimestre" value="<?php if( isset( $data->simulatorprimasprimertrimestre ) ) echo $data->simulatorprimasprimertrimestre; else echo 0; ?>" />
           </td>
           <td><div id="simulator-primas-primer-trimestre">$ <?php if( isset( $data->simulatorprimasprimertrimestre ) ) echo $data->simulatorprimasprimertrimestre; else echo 0; ?></div></td>		   
           <td><p>Ingresos del trimestre</p><input type="hidden" name="simulatoringresosprimertrimestre" id="simulatoringresosprimertrimestre" value="<?php if( isset( $data->simulatoringresosprimertrimestre ) ) echo $data->simulatoringresosprimertrimestre; else echo 0; ?>" /></td>
           <td><div id="simulator-ingresos-primer-trimestre">$ <?php if( isset( $data->simulatoringresosprimertrimestre ) ) echo $data->simulatoringresosprimertrimestre; else echo 0; ?></div></td>
        </tr>
        <tr id="row1" style="display:none">
        <td colspan="4">

<!--INICIO SIMULADOR PRIMER TRIMESTRE-->
        <table width="100%">

        <tr>
           
           <td><label>No. de Negocios PAI:</label></td>

           <td colspan="3"> <input type="text" readonly="readonly" class="input-small" name="noNegocios_1" id="noNegocios_1" value="<?php if( isset( $data->noNegocios_1 ) ) echo $data->noNegocios_1; else echo 0; ?>">
</td>
          
        </tr>
        <tr>
           <td><label style="color:#547EBD !important">Primas afectas iniciales para bonos</label></td>		
           <td class="2">			  
              <p style="color:#547EBD !important; float:right" id="primasAfectasInicialesPagar_text_1">$ <?php if( isset( $data->primasAfectasInicialesPagar_1 ) ) echo $data->primasAfectasInicialesPagar_1; else echo 0; ?></p>	
              <input type="hidden" name="primasAfectasInicialesPagar_1" id="primasAfectasInicialesPagar_1" value="<?php if( isset( $data->primasAfectasInicialesPagar_1 ) ) echo $data->primasAfectasInicialesPagar_1; else echo 0; ?>">
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
           <td><label>Primas de renovación:</label></td>
           <td><input type="text" class="input-small" name="primasRenovacion_1" id="primasRenovacion_1" value="<?php if( isset( $data->primasRenovacion_1 ) ) echo $data->primasRenovacion_1; else echo 0; ?>"></td>           
           <td><label>% de acotamiento:</label></td>
           <td><input type="text" class="input-small" name="XAcotamiento_1" id="XAcotamiento_1" value="<?php if( isset( $data->XAcotamiento_1 ) ) echo $data->XAcotamiento_1; else echo 0; ?>"></td>
        </tr>		
        
                
        <tr>
           <td><label style="color:#547EBD !important">Primas de renovación para pagar:</label></td>
           <td>			  
              <p style="color:#547EBD !important; float:right" id="primasRenovacionPagar_text_1">$ <?php if( isset( $data->primasRenovacionPagar_1 ) ) echo $data->primasRenovacionPagar_1; else echo 0; ?></p>	
              <input type="hidden" name="primasRenovacionPagar_1" id="primasRenovacionPagar_1" value="<?php if( isset( $data->primasRenovacionPagar_1 ) ) echo $data->primasRenovacionPagar_1; else echo 0; ?>">
           </td>
           <td><label>Porcentaje de conservación</label></td>
           <td><select name="porcentajeConservacion_1" id="porcentajeConservacion_1" class="input-small">

                 <option value="0" <?php if( isset( $data->porcentajeConservacion_1 ) and $data->porcentajeConservacion_1 == 0 ) echo 'selected="selected"'; ?>>Sin base</option>

                 <option value="m89" <?php if( isset( $data->porcentajeConservacion_1 ) and $data->porcentajeConservacion_1 == "m89" ) echo 'selected="selected"'; ?>>&lt;89%</option>

                 <option value="89" <?php if( isset( $data->porcentajeConservacion_1 ) and $data->porcentajeConservacion_1 == 89 ) echo 'selected="selected"'; ?>>89%</option>

                 <option value="91" <?php if( isset( $data->porcentajeConservacion_1 ) and $data->porcentajeConservacion_1 == 91 ) echo 'selected="selected"'; ?>>91%</option>

                 <option value="93" <?php if( isset( $data->porcentajeConservacion_1 ) and $data->porcentajeConservacion_1 == 93 ) echo 'selected="selected"'; ?>>93%</option>

                 <option value="95" <?php if( isset( $data->porcentajeConservacion_1 ) and $data->porcentajeConservacion_1 == 95 ) echo 'selected="selected"'; ?>>95%</option>

              </select></td>
        </tr>
		
        <tr>

           <td><br></td>

           <td><br></td>
           
           <td><br></td>

           <td><br></td>

        </tr>
        
        
        <tr>

           <td colspan="2" align="left"><b style="color:#547EBD !important">COMISIONES</b></td>
          
           <td><br></td>

           <td><br></td> 

        </tr>
		
        
        <tr>
           <td><label>% de comisión venta inicial:</label></td>
           <td>
              <input type="text" class="input-small" name="comisionVentaInicial_1" id="comisionVentaInicial_1" value="<?php if( isset( $data->comisionVentaInicial_1 ) ) echo $data->comisionVentaInicial_1; else echo 0; ?>">
           </td>
           <td><label style="color:#547EBD !important">Ingreso por comisiones de venta inicial:</label></td>
           <td>
              <p style="color:#547EBD !important; float:right" id="ingresoComisionesVentaInicial_text_1">$ <?php if( isset( $data->ingresoComisionesVentaInicial_1 ) ) echo $data->ingresoComisionesVentaInicial_1; else echo 0; ?></p>	
              <input type="hidden" name="ingresoComisionesVentaInicial_1" id="ingresoComisionesVentaInicial_1" value="<?php if( isset( $data->ingresoComisionesVentaInicial_1 ) ) echo $data->ingresoComisionesVentaInicial_1; else echo 0; ?>">
           </td>          
        </tr>
        
        <tr>
           <td><label>% de comisión venta de renovación:</label></td>
           <td>
              <input type="text" class="input-small" name="comisionVentaRenovacion_1" id="comisionVentaRenovacion_1" value="<?php if( isset( $data->comisionVentaRenovacion_1 ) ) echo $data->comisionVentaRenovacion_1; else echo 0; ?>">
           </td>           
           <td><label style="color:#547EBD !important">Ingreso por comisiones de renovación</label></td>

           <td> <p style="color:#547EBD !important; float:right" id="ingresoComisionRenovacion_text_1">$ <?php if( isset( $data->ingresoComisionRenovacion_1 ) ) echo $data->ingresoComisionRenovacion_1; else echo 0; ?></p>	
              <input type="hidden" name="ingresoComisionRenovacion_1" id="ingresoComisionRenovacion_1" value="<?php if( isset( $data->ingresoComisionRenovacion_1 ) ) echo $data->ingresoComisionRenovacion_1; else echo 0; ?>"></td>

        </tr>
        
        <tr>
           <td><br></td>
           <td><br></td>           
           <td><br></td>
           <td><br></td>
        </tr>
        
        <tr>
           <td colspan="2" align="left"><b style="color:#547EBD !important">BONO DE PRODUCTIVIDAD</b></td>
           <td colspan="3" align="left"></td>
        </tr>
        
        <tr>           
           <td><label>% de bono aplicado:</label></td>
           <td>
              <input type="text" class="input-small" name="bonoAplicado_1" id="bonoAplicado_1" value="<?php if( isset( $data->bonoAplicado_1 ) ) echo $data->bonoAplicado_1; else echo 0; ?>" readonly="readonly">
           </td>           
           <td><label style="color:#547EBD !important">Ingreso por bono de productividad:</label></td>
           <td>
              <p style="color:#547EBD !important; float:right" id="ingresoBonoProductividad_text_1">$ <?php if( isset( $data->ingresoBonoProductividad_1 ) ) echo $data->ingresoBonoProductividad_1; else echo 0; ?></p>	
              <input type="hidden" name="ingresoBonoProductividad_1" id="ingresoBonoProductividad_1" value="<?php if( isset( $data->ingresoBonoProductividad_1 ) ) echo $data->ingresoBonoProductividad_1; else echo 0; ?>">
                         </td>
        </tr>
		
        <tr>
           <td><br></td>
           <td><br></td>           
           <td><br></td>
           <td><br></td>
        </tr>
        
        <tr>
           <td colspan="2" align="left"><b style="color:#547EBD !important">BONO DE RENOVACION</b></td>
		   <td><br></td>
           <td><br></td>
        </tr>

        <tr>

           <td><label>% de bono ganado:</label></td>

           <td>

              <input type="text" class="input-small" name="porbonoGanado_1" id="porbonoGanado_1" value="<?php if( isset( $data->porbonoGanado_1 ) ) echo $data->porbonoGanado_1; else echo 0; ?>" readonly="readonly">

           </td>
           
           <td><label style="color:#547EBD !important">Ingreso por bono de renovación:</label></td>

           <td><p style="color:#547EBD !important; float:right" id="ingresoBonoRenovacion_text_1">$ <?php if( isset( $data->ingresoBonoRenovacion_1 ) ) echo $data->ingresoBonoRenovacion_1; else echo 0; ?></p>	
              <input type="hidden" name="ingresoBonoRenovacion_1" id="ingresoBonoRenovacion_1" value="<?php if( isset( $data->ingresoBonoRenovacion_1 ) ) echo $data->ingresoBonoRenovacion_1; else echo 0; ?>" ></td>

        </tr>

        </table>
<!--FIN SIMULADOR PRIMER TRIMESTRE-->



        </td>
        </tr>
        <tr onClick="ShowHideRow(2)" style="cursor: pointer;">
        	<td colspan="4" style="background-color:#CCCCCC"><h6>Segundo trimestre: <span style="color:rgb(32,135,201)"><span id="showRow2" style="text-align:right;">Mostrar</span> simulador <span id="Arrow2">&darr;</span></span></h6></td>
        </tr>
        
        <tr onClick="ShowHideRow(2)" style="cursor: pointer;">
           <td><p>Primas del trimestre:</p><input type="hidden" name="simulatorprimassegundotrimestre" id="simulatorprimassegundotrimestre" value="<?php if( isset( $data->simulatorprimassegundotrimestre ) ) echo $data->simulatorprimassegundotrimestre; else echo 0; ?>" /></td>
           <td><div id="simulator-primas-segundo-trimestre">$ <?php if( isset( $data->simulatorprimassegundotrimestre ) ) echo $data->simulatorprimassegundotrimestre; else echo 0; ?></div></td>		   
           <td><p>Ingresos del trimestre</p><input type="hidden" name="simulatoringresossegundotrimestre" id="simulatoringresossegundotrimestre" value="<?php if( isset( $data->simulatoringresossegundotrimestre ) ) echo $data->simulatoringresossegundotrimestre; else echo 0; ?>" /></td>
           <td><div id="simulator-ingresos-segundo-trimestre">$ <?php if( isset( $data->simulatoringresossegundotrimestre ) ) echo $data->simulatoringresossegundotrimestre; else echo 0; ?></div></td>
        </tr>
        <tr id="row2" style="display:none">
        <td colspan="4">

<!--INICIO SIMULADOR SEGUNDO TRIMESTRE-->
        <table width="100%">

        <tr>
           
           <td><label>No. de Negocios PAI:</label></td>

           <td colspan="3"> <input type="text" readonly="readonly" class="input-small" name="noNegocios_2" id="noNegocios_2" value="<?php if( isset( $data->noNegocios_2 ) ) echo $data->noNegocios_2; else echo 0; ?>">
</td>
          
        </tr>
        <tr>
           <td><label style="color:#547EBD !important">Primas afectas iniciales para bonos</label></td>		
           <td class="2">			  
              <p style="color:#547EBD !important; float:right" id="primasAfectasInicialesPagar_text_2">$ <?php if( isset( $data->primasAfectasInicialesPagar_2 ) ) echo $data->primasAfectasInicialesPagar_2; else echo 0; ?></p>	
              <input type="hidden" name="primasAfectasInicialesPagar_2" id="primasAfectasInicialesPagar_2" value="<?php if( isset( $data->primasAfectasInicialesPagar_2 ) ) echo $data->primasAfectasInicialesPagar_2; else echo 0; ?>">
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
           <td><label>Primas de renovación:</label></td>
           <td><input type="text" class="input-small" name="primasRenovacion_2" id="primasRenovacion_2" value="<?php if( isset( $data->primasRenovacion_2 ) ) echo $data->primasRenovacion_2; else echo 0; ?>"></td>           
           <td><label>% de acotamiento:</label></td>
           <td><input type="text" class="input-small" name="XAcotamiento_2" id="XAcotamiento_2" value="<?php if( isset( $data->XAcotamiento_2 ) ) echo $data->XAcotamiento_2; else echo 0; ?>"></td>
        </tr>		
        
                
        <tr>
           <td><label style="color:#547EBD !important">Primas de renovación para pagar:</label></td>
           <td>			  
              <p style="color:#547EBD !important; float:right" id="primasRenovacionPagar_text_2">$ <?php if( isset( $data->primasRenovacionPagar_2 ) ) echo $data->primasRenovacionPagar_2; else echo 0; ?></p>	
              <input type="hidden" name="primasRenovacionPagar_2" id="primasRenovacionPagar_2" value="<?php if( isset( $data->primasRenovacionPagar_2 ) ) echo $data->primasRenovacionPagar_2; else echo 0; ?>">
           </td>
           <td><label>Porcentaje de conservación</label></td>
           <td><select name="porcentajeConservacion_2" id="porcentajeConservacion_2" class="input-small">

                 <option value="0" <?php if( isset( $data->porcentajeConservacion_2 ) and $data->porcentajeConservacion_2 == 0 ) echo 'selected="selected"'; ?>>Sin base</option>

                 <option value="m89" <?php if( isset( $data->porcentajeConservacion_2 ) and $data->porcentajeConservacion_2 == "m89" ) echo 'selected="selected"'; ?>>&lt;89%</option>

                 <option value="89" <?php if( isset( $data->porcentajeConservacion_2 ) and $data->porcentajeConservacion_2 == 89 ) echo 'selected="selected"'; ?>>89%</option>

                 <option value="91" <?php if( isset( $data->porcentajeConservacion_2 ) and $data->porcentajeConservacion_2 == 91 ) echo 'selected="selected"'; ?>>91%</option>

                 <option value="93" <?php if( isset( $data->porcentajeConservacion_2 ) and $data->porcentajeConservacion_2 == 93 ) echo 'selected="selected"'; ?>>93%</option>

                 <option value="95" <?php if( isset( $data->porcentajeConservacion_2 ) and $data->porcentajeConservacion_2 == 95 ) echo 'selected="selected"'; ?>>95%</option>

              </select></td>
        </tr>
		
        <tr>

           <td><br></td>

           <td><br></td>
           
           <td><br></td>

           <td><br></td>

        </tr>
        
        
        <tr>

           <td colspan="2" align="left"><b style="color:#547EBD !important">COMISIONES</b></td>
          
           <td><br></td>

           <td><br></td> 

        </tr>
		
        
        <tr>
           <td><label>% de comisión venta inicial:</label></td>
           <td>
              <input type="text" class="input-small" name="comisionVentaInicial_2" id="comisionVentaInicial_2" value="<?php if( isset( $data->comisionVentaInicial_2 ) ) echo $data->comisionVentaInicial_2; else echo 0; ?>">
           </td>
           <td><label style="color:#547EBD !important">Ingreso por comisiones de venta inicial:</label></td>
           <td>
              <p style="color:#547EBD !important; float:right" id="ingresoComisionesVentaInicial_text_2">$ <?php if( isset( $data->ingresoComisionesVentaInicial_2 ) ) echo $data->ingresoComisionesVentaInicial_2; else echo 0; ?></p>	
              <input type="hidden" name="ingresoComisionesVentaInicial_2" id="ingresoComisionesVentaInicial_2" value="<?php if( isset( $data->ingresoComisionesVentaInicial_2 ) ) echo $data->ingresoComisionesVentaInicial_2; else echo 0; ?>">
           </td>          
        </tr>
        
        <tr>
           <td><label>% de comisión venta de renovación:</label></td>
           <td>
              <input type="text" class="input-small" name="comisionVentaRenovacion_2" id="comisionVentaRenovacion_2" value="<?php if( isset( $data->comisionVentaRenovacion_2 ) ) echo $data->comisionVentaRenovacion_2; else echo 0; ?>">
           </td>           
           <td><label style="color:#547EBD !important">Ingreso por comisiones de renovación</label></td>

           <td> <p style="color:#547EBD !important; float:right" id="ingresoComisionRenovacion_text_2">$ <?php if( isset( $data->ingresoComisionRenovacion_2 ) ) echo $data->ingresoComisionRenovacion_2; else echo 0; ?></p>	
              <input type="hidden" name="ingresoComisionRenovacion_2" id="ingresoComisionRenovacion_2" value="<?php if( isset( $data->ingresoComisionRenovacion_2 ) ) echo $data->ingresoComisionRenovacion_2; else echo 0; ?>"></td>

        </tr>
        
        <tr>
           <td><br></td>
           <td><br></td>           
           <td><br></td>
           <td><br></td>
        </tr>
        
        <tr>
           <td colspan="2" align="left"><b style="color:#547EBD !important">BONO DE PRODUCTIVIDAD</b></td>
           <td colspan="3" align="left"></td>
        </tr>
        
        <tr>           
           <td><label>% de bono aplicado:</label></td>
           <td>
              <input type="text" class="input-small" name="bonoAplicado_2" id="bonoAplicado_2" value="<?php if( isset( $data->bonoAplicado_2 ) ) echo $data->bonoAplicado_2; else echo 0; ?>" readonly="readonly">
           </td>           
           <td><label style="color:#547EBD !important">Ingreso por bono de productividad:</label></td>
           <td>
              <p style="color:#547EBD !important; float:right" id="ingresoBonoProductividad_text_2">$ <?php if( isset( $data->ingresoBonoProductividad_2 ) ) echo $data->ingresoBonoProductividad_2; else echo 0; ?></p>	
              <input type="hidden" name="ingresoBonoProductividad_2" id="ingresoBonoProductividad_2" value="<?php if( isset( $data->ingresoBonoProductividad_2 ) ) echo $data->ingresoBonoProductividad_2; else echo 0; ?>">
                         </td>
        </tr>
		
        <tr>
           <td><br></td>
           <td><br></td>           
           <td><br></td>
           <td><br></td>
        </tr>
        
        <tr>
           <td colspan="2" align="left"><b style="color:#547EBD !important">BONO DE RENOVACION</b></td>
		   <td><br></td>
           <td><br></td>
        </tr>

        <tr>

           <td><label>% de bono ganado:</label></td>

           <td>

              <input type="text" class="input-small" name="porbonoGanado_2" id="porbonoGanado_2" value="<?php if( isset( $data->porbonoGanado_2 ) ) echo $data->porbonoGanado_2; else echo 0; ?>" readonly="readonly">

           </td>
           
           <td><label style="color:#547EBD !important">Ingreso por bono de renovación:</label></td>

           <td><p style="color:#547EBD !important; float:right" id="ingresoBonoRenovacion_text_2">$ <?php if( isset( $data->ingresoBonoRenovacion_2 ) ) echo $data->ingresoBonoRenovacion_2; else echo 0; ?></p>	
              <input type="hidden" name="ingresoBonoRenovacion_2" id="ingresoBonoRenovacion_2" value="<?php if( isset( $data->ingresoBonoRenovacion_2 ) ) echo $data->ingresoBonoRenovacion_2; else echo 0; ?>" ></td>

        </tr>

        </table>
<!--FIN SIMULADOR SEGUNDO TRIMESTRE-->


        </td>
        </tr>
        
        <tr onClick="ShowHideRow(3)" style="cursor: pointer;">
        	<td colspan="4" style="background-color:#CCCCCC"><h6>Tercer trimestre: <span style="color:rgb(32,135,201)"><span id="showRow3" style="text-align:right;">Mostrar</span> simulador <span id="Arrow3">&darr;</span></span></h6></td>
        </tr>
        
        <tr onClick="ShowHideRow(3)" style="cursor: pointer;">
           <td><p>Primas del trimestre:</p><input type="hidden" name="simulatorprimastercertrimestre" id="simulatorprimastercertrimestre" value="<?php if( isset( $data->simulatorprimastercertrimestre ) ) echo $data->simulatorprimastercertrimestre; else echo 0; ?>" /></td>
           <td><div id="simulator-primas-tercer-trimestre">$ <?php if( isset( $data->simulatorprimastercertrimestre ) ) echo $data->simulatorprimastercertrimestre; else echo 0; ?></div></td>		   
           <td><p>Ingresos del trimestre</p><input type="hidden" name="simulatoringresostercertrimestre" id="simulatoringresostercertrimestre" value="<?php if( isset( $data->simulatoringresostercertrimestre ) ) echo $data->simulatoringresostercertrimestre; else echo 0; ?>" /></td>
           <td><div id="simulator-ingresos-tercer-trimestre">$ <?php if( isset( $data->simulatoringresostercertrimestre ) ) echo $data->simulatoringresostercertrimestre; else echo 0; ?></div></td>
        </tr>
        <tr id="row3" style="display:none">
        <td colspan="4">

<!--INICIO SIMULADOR TERCER TRIMESTRE-->
        <table width="100%">

        <tr>

           
           <td><label>No. de Negocios PAI:</label></td>

           <td colspan="3"> <input type="text" readonly="readonly" class="input-small" name="noNegocios_3" id="noNegocios_3" value="<?php if( isset( $data->noNegocios_3 ) ) echo $data->noNegocios_3; else echo 0; ?>">
</td>
          
        </tr>
        <tr>
           <td><label style="color:#547EBD !important">Primas afectas iniciales para bonos</label></td>		
           <td class="2">			  
              <p style="color:#547EBD !important; float:right" id="primasAfectasInicialesPagar_text_3">$ <?php if( isset( $data->primasAfectasInicialesPagar_3 ) ) echo $data->primasAfectasInicialesPagar_3; else echo 0; ?></p>	
              <input type="hidden" name="primasAfectasInicialesPagar_3" id="primasAfectasInicialesPagar_3" value="<?php if( isset( $data->primasAfectasInicialesPagar_3 ) ) echo $data->primasAfectasInicialesPagar_3; else echo 0; ?>">
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
           <td><label>Primas de renovación:</label></td>
           <td><input type="text" class="input-small" name="primasRenovacion_3" id="primasRenovacion_3" value="<?php if( isset( $data->primasRenovacion_3 ) ) echo $data->primasRenovacion_3; else echo 0; ?>"></td>           
           <td><label>% de acotamiento:</label></td>
           <td><input type="text" class="input-small" name="XAcotamiento_3" id="XAcotamiento_3" value="<?php if( isset( $data->XAcotamiento_3 ) ) echo $data->XAcotamiento_3; else echo 0; ?>"></td>
        </tr>		
        
                
        <tr>
           <td><label style="color:#547EBD !important">Primas de renovación para pagar:</label></td>
           <td>			  
              <p style="color:#547EBD !important; float:right" id="primasRenovacionPagar_text_3">$ <?php if( isset( $data->primasRenovacionPagar_3 ) ) echo $data->primasRenovacionPagar_3; else echo 0; ?></p>	
              <input type="hidden" name="primasRenovacionPagar_3" id="primasRenovacionPagar_3" value="<?php if( isset( $data->primasRenovacionPagar_3 ) ) echo $data->primasRenovacionPagar_3; else echo 0; ?>">
           </td>
           <td><label>Porcentaje de conservación</label></td>
           <td><select name="porcentajeConservacion_3" id="porcentajeConservacion_3" class="input-small">

                 <option value="0" <?php if( isset( $data->porcentajeConservacion_3 ) and $data->porcentajeConservacion_3 == 0 ) echo 'selected="selected"'; ?>>Sin base</option>

                 <option value="m89" <?php if( isset( $data->porcentajeConservacion_3 ) and $data->porcentajeConservacion_3 == "m89" ) echo 'selected="selected"'; ?>>&lt;89%</option>

                 <option value="89" <?php if( isset( $data->porcentajeConservacion_3 ) and $data->porcentajeConservacion_3 == 89 ) echo 'selected="selected"'; ?>>89%</option>

                 <option value="91" <?php if( isset( $data->porcentajeConservacion_3 ) and $data->porcentajeConservacion_3 == 91 ) echo 'selected="selected"'; ?>>91%</option>

                 <option value="93" <?php if( isset( $data->porcentajeConservacion_3 ) and $data->porcentajeConservacion_3 == 93 ) echo 'selected="selected"'; ?>>93%</option>

                 <option value="95" <?php if( isset( $data->porcentajeConservacion_3 ) and $data->porcentajeConservacion_3 == 95 ) echo 'selected="selected"'; ?>>95%</option>

              </select></td>
        </tr>
		
        <tr>

           <td><br></td>

           <td><br></td>
           
           <td><br></td>

           <td><br></td>

        </tr>
        
        
        <tr>

           <td colspan="2" align="left"><b style="color:#547EBD !important">COMISIONES</b></td>
          
           <td><br></td>

           <td><br></td> 

        </tr>
		
        
        <tr>
           <td><label>% de comisión venta inicial:</label></td>
           <td>
              <input type="text" class="input-small" name="comisionVentaInicial_3" id="comisionVentaInicial_3" value="<?php if( isset( $data->comisionVentaInicial_3 ) ) echo $data->comisionVentaInicial_3; else echo 0; ?>">
           </td>
           <td><label style="color:#547EBD !important">Ingreso por comisiones de venta inicial:</label></td>
           <td>
              <p style="color:#547EBD !important; float:right" id="ingresoComisionesVentaInicial_text_3">$ <?php if( isset( $data->ingresoComisionesVentaInicial_3 ) ) echo $data->ingresoComisionesVentaInicial_3; else echo 0; ?></p>	
              <input type="hidden" name="ingresoComisionesVentaInicial_3" id="ingresoComisionesVentaInicial_3" value="<?php if( isset( $data->ingresoComisionesVentaInicial_3 ) ) echo $data->ingresoComisionesVentaInicial_3; else echo 0; ?>">
           </td>          
        </tr>
        
        <tr>
           <td><label>% de comisión venta de renovación:</label></td>
           <td>
              <input type="text" class="input-small" name="comisionVentaRenovacion_3" id="comisionVentaRenovacion_3" value="<?php if( isset( $data->comisionVentaRenovacion_3 ) ) echo $data->comisionVentaRenovacion_3; else echo 0; ?>">
           </td>           
           <td><label style="color:#547EBD !important">Ingreso por comisiones de renovación</label></td>

           <td> <p style="color:#547EBD !important; float:right" id="ingresoComisionRenovacion_text_3">$ <?php if( isset( $data->ingresoComisionRenovacion_3 ) ) echo $data->ingresoComisionRenovacion_3; else echo 0; ?></p>	
              <input type="hidden" name="ingresoComisionRenovacion_3" id="ingresoComisionRenovacion_3" value="<?php if( isset( $data->ingresoComisionRenovacion_3 ) ) echo $data->ingresoComisionRenovacion_3; else echo 0; ?>"></td>

        </tr>
        
        <tr>
           <td><br></td>
           <td><br></td>           
           <td><br></td>
           <td><br></td>
        </tr>
        
        <tr>
           <td colspan="2" align="left"><b style="color:#547EBD !important">BONO DE PRODUCTIVIDAD</b></td>
           <td colspan="3" align="left"></td>
        </tr>
        
        <tr>           
           <td><label>% de bono aplicado:</label></td>
           <td>
              <input type="text" class="input-small" name="bonoAplicado_3" id="bonoAplicado_3" value="<?php if( isset( $data->bonoAplicado_3 ) ) echo $data->bonoAplicado_3; else echo 0; ?>" readonly="readonly">
           </td>           
           <td><label style="color:#547EBD !important">Ingreso por bono de productividad:</label></td>
           <td>
              <p style="color:#547EBD !important; float:right" id="ingresoBonoProductividad_text_3">$ <?php if( isset( $data->ingresoBonoProductividad_3 ) ) echo $data->ingresoBonoProductividad_3; else echo 0; ?></p>	
              <input type="hidden" name="ingresoBonoProductividad_3" id="ingresoBonoProductividad_3" value="<?php if( isset( $data->ingresoBonoProductividad_3 ) ) echo $data->ingresoBonoProductividad_3; else echo 0; ?>">
                         </td>
        </tr>
		
        <tr>
           <td><br></td>
           <td><br></td>           
           <td><br></td>
           <td><br></td>
        </tr>
        
        <tr>
           <td colspan="2" align="left"><b style="color:#547EBD !important">BONO DE RENOVACION</b></td>
		   <td><br></td>
           <td><br></td>
        </tr>

        <tr>

           <td><label>% de bono ganado:</label></td>

           <td>

              <input type="text" class="input-small" name="porbonoGanado_3" id="porbonoGanado_3" value="<?php if( isset( $data->porbonoGanado_3 ) ) echo $data->porbonoGanado_3; else echo 0; ?>" readonly="readonly">

           </td>
           
           <td><label style="color:#547EBD !important">Ingreso por bono de renovación:</label></td>

           <td><p style="color:#547EBD !important; float:right" id="ingresoBonoRenovacion_text_3">$ <?php if( isset( $data->ingresoBonoRenovacion_3 ) ) echo $data->ingresoBonoRenovacion_3; else echo 0; ?></p>	
              <input type="hidden" name="ingresoBonoRenovacion_3" id="ingresoBonoRenovacion_3" value="<?php if( isset( $data->ingresoBonoRenovacion_3 ) ) echo $data->ingresoBonoRenovacion_3; else echo 0; ?>" ></td>

        </tr>

        </table>
<!--FIN SIMULADOR TERCER TRIMESTRE-->


        </td>
        </tr>
        
        <tr onClick="ShowHideRow(4)" style="cursor: pointer;">
        	<td colspan="4" style="background-color:#CCCCCC"><h6>Cuarto trimestre: <span style="color:rgb(32,135,201)"><span id="showRow4" style="text-align:right;">Mostrar</span> simulador <span id="Arrow4">&darr;</span></span></h6></td>
        </tr>
        
        <tr onClick="ShowHideRow(4)" style="cursor: pointer;">
           <td><p>Primas del trimestre:</p><input type="hidden" name="simulatorprimascuartotrimestre" id="simulatorprimascuartotrimestre" value="<?php if( isset( $data->simulatorprimascuartotrimestre ) ) echo $data->simulatorprimascuartotrimestre; else echo 0; ?>" /></td>
           <td><div id="simulator-primas-cuarto-trimestre">$ <?php if( isset( $data->simulatorprimascuartotrimestre ) ) echo $data->simulatorprimascuartotrimestre; else echo 0; ?></div></td>		   
           <td><p>Ingresos del trimestre</p><input type="hidden" name="simulatoringresoscuartotrimestre" id="simulatoringresoscuartotrimestre" value="<?php if( isset( $data->simulatoringresoscuartotrimestre ) ) echo $data->simulatoringresoscuartotrimestre; else echo 0; ?>" /></td>
           <td><div id="simulator-ingresos-cuarto-trimestre">$ <?php if( isset( $data->simulatoringresoscuartotrimestre ) ) echo $data->simulatoringresoscuartotrimestre; else echo 0; ?></div></td>
        </tr>
        <tr id="row4" style="display:none">
        <td colspan="4">

<!--INICIO SIMULADOR CUARTO TRIMESTRE-->
        <table width="100%">

        <tr>
           
           <td><label>No. de Negocios PAI:</label></td>

           <td colspan="3"> <input type="text" readonly="readonly" class="input-small" name="noNegocios_4" id="noNegocios_4" value="<?php if( isset( $data->noNegocios_4 ) ) echo $data->noNegocios_4; else echo 0; ?>">
</td>
          
        </tr>
        <tr>
           <td><label style="color:#547EBD !important">Primas afectas iniciales para bonos</label></td>		
           <td class="2">			  
              <p style="color:#547EBD !important; float:right" id="primasAfectasInicialesPagar_text_4">$ <?php if( isset( $data->primasAfectasInicialesPagar_4 ) ) echo $data->primasAfectasInicialesPagar_4; else echo 0; ?></p>	
              <input type="hidden" name="primasAfectasInicialesPagar_4" id="primasAfectasInicialesPagar_4" value="<?php if( isset( $data->primasAfectasInicialesPagar_4 ) ) echo $data->primasAfectasInicialesPagar_4; else echo 0; ?>">
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
           <td><label>Primas de renovación:</label></td>
           <td><input type="text" class="input-small" name="primasRenovacion_4" id="primasRenovacion_4" value="<?php if( isset( $data->primasRenovacion_4 ) ) echo $data->primasRenovacion_4; else echo 0; ?>"></td>           
           <td><label>% de acotamiento:</label></td>
           <td><input type="text" class="input-small" name="XAcotamiento_4" id="XAcotamiento_4" value="<?php if( isset( $data->XAcotamiento_4 ) ) echo $data->XAcotamiento_4; else echo 0; ?>"></td>
        </tr>		
        
                
        <tr>
           <td><label style="color:#547EBD !important">Primas de renovación para pagar:</label></td>
           <td>			  
              <p style="color:#547EBD !important; float:right" id="primasRenovacionPagar_text_4">$ <?php if( isset( $data->primasRenovacionPagar_4 ) ) echo $data->primasRenovacionPagar_4; else echo 0; ?></p>	
              <input type="hidden" name="primasRenovacionPagar_4" id="primasRenovacionPagar_4" value="<?php if( isset( $data->primasRenovacionPagar_4 ) ) echo $data->primasRenovacionPagar_4; else echo 0; ?>">
           </td>
           <td><label>Porcentaje de conservación</label></td>
           <td><select name="porcentajeConservacion_4" id="porcentajeConservacion_4" class="input-small">

                 <option value="0" <?php if( isset( $data->porcentajeConservacion_4 ) and $data->porcentajeConservacion_4 == 0 ) echo 'selected="selected"'; ?>>Sin base</option>

                 <option value="m89" <?php if( isset( $data->porcentajeConservacion_4 ) and $data->porcentajeConservacion_4 == "m89" ) echo 'selected="selected"'; ?>>&lt;89%</option>

                 <option value="89" <?php if( isset( $data->porcentajeConservacion_4 ) and $data->porcentajeConservacion_4 == 89 ) echo 'selected="selected"'; ?>>89%</option>

                 <option value="91" <?php if( isset( $data->porcentajeConservacion_4 ) and $data->porcentajeConservacion_4 == 91 ) echo 'selected="selected"'; ?>>91%</option>

                 <option value="93" <?php if( isset( $data->porcentajeConservacion_4 ) and $data->porcentajeConservacion_4 == 93 ) echo 'selected="selected"'; ?>>93%</option>

                 <option value="95" <?php if( isset( $data->porcentajeConservacion_4 ) and $data->porcentajeConservacion_4 == 95 ) echo 'selected="selected"'; ?>>95%</option>

              </select></td>
        </tr>
		
        <tr>

           <td><br></td>

           <td><br></td>
           
           <td><br></td>

           <td><br></td>

        </tr>
        
        
        <tr>

           <td colspan="2" align="left"><b style="color:#547EBD !important">COMISIONES</b></td>
          
           <td><br></td>

           <td><br></td> 

        </tr>
		
        
        <tr>
           <td><label>% de comisión venta inicial:</label></td>
           <td>
              <input type="text" class="input-small" name="comisionVentaInicial_4" id="comisionVentaInicial_4" value="<?php if( isset( $data->comisionVentaInicial_4 ) ) echo $data->comisionVentaInicial_4; else echo 0; ?>">
           </td>
           <td><label style="color:#547EBD !important">Ingreso por comisiones de venta inicial:</label></td>
           <td>
              <p style="color:#547EBD !important; float:right" id="ingresoComisionesVentaInicial_text_4">$ <?php if( isset( $data->ingresoComisionesVentaInicial_4 ) ) echo $data->ingresoComisionesVentaInicial_4; else echo 0; ?></p>	
              <input type="hidden" name="ingresoComisionesVentaInicial_4" id="ingresoComisionesVentaInicial_4" value="<?php if( isset( $data->ingresoComisionesVentaInicial_4 ) ) echo $data->ingresoComisionesVentaInicial_4; else echo 0; ?>">
           </td>          
        </tr>
        
        <tr>
           <td><label>% de comisión venta de renovación:</label></td>
           <td>
              <input type="text" class="input-small" name="comisionVentaRenovacion_4" id="comisionVentaRenovacion_4" value="<?php if( isset( $data->comisionVentaRenovacion_4 ) ) echo $data->comisionVentaRenovacion_4; else echo 0; ?>">
           </td>           
           <td><label style="color:#547EBD !important">Ingreso por comisiones de renovación</label></td>

           <td> <p style="color:#547EBD !important; float:right" id="ingresoComisionRenovacion_text_4">$ <?php if( isset( $data->ingresoComisionRenovacion_4 ) ) echo $data->ingresoComisionRenovacion_4; else echo 0; ?></p>	
              <input type="hidden" name="ingresoComisionRenovacion_4" id="ingresoComisionRenovacion_4" value="<?php if( isset( $data->ingresoComisionRenovacion_4 ) ) echo $data->ingresoComisionRenovacion_4; else echo 0; ?>"></td>

        </tr>
        
        <tr>
           <td><br></td>
           <td><br></td>           
           <td><br></td>
           <td><br></td>
        </tr>
        
        <tr>
           <td colspan="2" align="left"><b style="color:#547EBD !important">BONO DE PRODUCTIVIDAD</b></td>
           <td colspan="3" align="left"></td>
        </tr>
        
        <tr>           
           <td><label>% de bono aplicado:</label></td>
           <td>
              <input type="text" class="input-small" name="bonoAplicado_4" id="bonoAplicado_4" value="<?php if( isset( $data->bonoAplicado_4 ) ) echo $data->bonoAplicado_4; else echo 0; ?>" readonly="readonly">
           </td>           
           <td><label style="color:#547EBD !important">Ingreso por bono de productividad:</label></td>
           <td>
              <p style="color:#547EBD !important; float:right" id="ingresoBonoProductividad_text_4">$ <?php if( isset( $data->ingresoBonoProductividad_4 ) ) echo $data->ingresoBonoProductividad_4; else echo 0; ?></p>	
              <input type="hidden" name="ingresoBonoProductividad_4" id="ingresoBonoProductividad_4" value="<?php if( isset( $data->ingresoBonoProductividad_4 ) ) echo $data->ingresoBonoProductividad_4; else echo 0; ?>">
                         </td>
        </tr>
		
        <tr>
           <td><br></td>
           <td><br></td>           
           <td><br></td>
           <td><br></td>
        </tr>
        
        <tr>
           <td colspan="2" align="left"><b style="color:#547EBD !important">BONO DE RENOVACION</b></td>
		   <td><br></td>
           <td><br></td>
        </tr>

        <tr>

           <td><label>% de bono ganado:</label></td>

           <td>

              <input type="text" class="input-small" name="porbonoGanado_4" id="porbonoGanado_4" value="<?php if( isset( $data->porbonoGanado_4 ) ) echo $data->porbonoGanado_4; else echo 0; ?>" readonly="readonly">

           </td>
           
           <td><label style="color:#547EBD !important">Ingreso por bono de renovación:</label></td>

           <td><p style="color:#547EBD !important; float:right" id="ingresoBonoRenovacion_text_4">$ <?php if( isset( $data->ingresoBonoRenovacion_4 ) ) echo $data->ingresoBonoRenovacion_4; else echo 0; ?></p>	
              <input type="hidden" name="ingresoBonoRenovacion_4" id="ingresoBonoRenovacion_4" value="<?php if( isset( $data->ingresoBonoRenovacion_4 ) ) echo $data->ingresoBonoRenovacion_4; else echo 0; ?>" ></td>

        </tr>

        </table>
<!--FIN SIMULADOR CUARTO TRIMESTRE-->


        </td>
        </tr>
        
        
        <tr>
           <td><br></td>
           <td><br></td>
           <td><br></td>
           <td><br></td>
        </tr>
        
        
        
        
        
		<!--<tr>
           <td><label>Periodo:</label></td>
           <td>
              <select name="periodo" id="periodo" class="input-small">
                 <option value="3" <?php if( isset( $data->periodo ) and $data->periodo == 3 ) echo 'selected="selected"'; ?>>TRIMESTRAL</option>
                 <option value="12" <?php if( isset( $data->periodo ) and $data->periodo == 12 ) echo 'selected="selected"'; ?>>ANUAL</option>
			  </select>
           </td>
           <td></td>
           <td></td>
        </tr>
        
        <tr>
           <td><br></td>
           <td><br></td>
           <td><br></td>
           <td><br></td>
        </tr>-->
        
       
     </table>
        
 </div>   
 



