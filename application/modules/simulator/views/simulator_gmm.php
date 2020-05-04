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
$uri_segments = $this->uri->rsegment_array();
$is_simulator_page = ($uri_segments[1] == 'simulator');

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
			  <p style="color:#547EBD !important; float:right" id="ingresoPromedioMensual_text">$ <?php if( isset( $data->ingresoPromedioMensual ) ) echo $data->ingresoPromedioMensual; else echo 0; ?></p>	
              <input type="hidden" name="ingresoBonoRenovacion" id="ingresoPromedioMensual" value="<?php if( isset( $data->ingresoPromedioMensual ) ) echo $data->ingresoPromedioMensual; else echo 0; ?>">

           </td>
           <!--<td style="vertical-align:middle"><input type="button" value="Guardar Meta" class="pull-right btn-save-meta" style="margin-top:10px;" onclick="save();"/></td>-->

        </tr>
        
    </table>    
    
<table width="100%">
        <tr>

           <td>
           <input type="hidden" name="periodo" id="periodo" value="12">
           <label>Primas netas iniciales anuales:</label></td>
           <td>
		   <input type="text" class="input-small" name="primasnetasiniciales" id="primasnetasiniciales" value="<?php if( isset( $data->primasnetasiniciales ) ) echo $data->primasnetasiniciales; else if( isset( $data->primasAfectasInicialesUbicar ) ) echo $data->primasAfectasInicialesUbicar; else echo 0; ?>">
           <input type="hidden" class="input-small" name="primasAfectasInicialesUbicar" id="primasAfectasInicialesUbicar" value="<?php if( isset( $data->primasAfectasInicialesUbicar ) ) echo $data->primasAfectasInicialesUbicar; else if( isset( $data->primasnetasiniciales ) ) echo $data->primasnetasiniciales; else echo 0; ?>">
		   </td>

           <td><label>% de acotamiento:</label></td>

           <td><input type="text" class="input-small" name="porAcotamiento" id="porAcotamiento" value="<?php if( isset( $data->porAcotamiento ) ) echo $data->porAcotamiento; else echo 0; ?>"></td>
           
        </tr>
       
        <tr>
           <td><label>Primas promedio</label></td>
           <td><input type="text" class="input-small" name="primaspromedio" id="primaspromedio" value="<?php if( isset( $data->primaspromedio ) ) echo $data->primaspromedio; elseif( isset( $data->primas_promedio ) ) echo $data->primas_promedio; else echo 0; ?>"></td> 
           <td><label>No Negocios:</label></td>
           <td>
<?php if ($is_simulator_page): ?>
           <input type="text" readonly="readonly" class="input-small" name="noNegocios" id="noNegocios" value="<?php if( isset( $data->noNegocios ) ) echo $data->noNegocios; else echo 0; ?>">
<?php else: ?>
           <input type="text" readonly="readonly" class="input-small" name="nonegocios" id="nonegocios" value="<?php if( isset( $data->nonegocios ) ) echo $data->nonegocios; else echo 0; ?>">
<?php endif; ?>
           </td>
        </tr>
</table>
    
    
    
    <table class="table table-bordered">
		
        
        <tr onClick="ShowHideRow(1)" style="cursor: pointer;">
        	<td colspan="4" style="background-color:#CCCCCC"><h6>Primer cuatrimestre: <span style="color:rgb(32,135,201)"><span id="showRow1" style="text-align:right;">Mostrar</span> simulador <span id="Arrow1">&darr;</span></span></h6></td>
        </tr>
        
        <tr onClick="ShowHideRow(1)" style="cursor: pointer;">
           <td><p>Primas del período:</p>

<?php
$simulatorPrimasPeriod = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);
if ( isset($data->simulatorPrimasPeriod) )
{
	if (is_object($data->simulatorPrimasPeriod))
	{
		foreach ($simulatorPrimasPeriod as $sim_key => $sim_value)
		{
			if (isset($data->simulatorPrimasPeriod->$sim_key))
				$simulatorPrimasPeriod[$sim_key] = $data->simulatorPrimasPeriod->$sim_key;		
		}
	}
	elseif (is_array($data->simulatorPrimasPeriod))
	{
		foreach ($simulatorPrimasPeriod as $sim_key => $sim_value)
		{
			if (isset($data->simulatorPrimasPeriod[$sim_key]))
				$simulatorPrimasPeriod[$sim_key] = $data->simulatorPrimasPeriod[$sim_key];		
		}
	}
}

$simulatorIngresosPeriod = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);
if ( isset($data->simulatorIngresosPeriod) )
{
	if (is_object($data->simulatorIngresosPeriod))
	{
		foreach ($simulatorIngresosPeriod as $sim_key => $sim_value)
		{
			if (isset($data->simulatorIngresosPeriod->$sim_key))
				$simulatorIngresosPeriod[$sim_key] = $data->simulatorIngresosPeriod->$sim_key;		
		}
	}
	elseif (is_array($data->simulatorIngresosPeriod))
	{
		foreach ($simulatorIngresosPeriod as $sim_key => $sim_value)
		{
			if (isset($data->simulatorIngresosPeriod[$sim_key]))
				$simulatorIngresosPeriod[$sim_key] = $data->simulatorIngresosPeriod[$sim_key];		
		}
	}
}
?>

           <input type="hidden" name="simulatorPrimasPeriod[1]" id="simulatorPrimasPeriod[1]" value="<?php echo $simulatorPrimasPeriod[1]; ?>" />
           </td>
           <td><div id="simulatorPrimasPeriod_text[1]">$ <?php echo $simulatorPrimasPeriod[1]; ?></div></td>		   
           <td><p>Ingresos del período</p><input type="hidden" name="simulatorIngresosPeriod[1]" id="simulatorIngresosPeriod[1]" value="<?php echo $simulatorIngresosPeriod[1]; ?>" /></td>
           <td><div id="simulatorIngresosPeriod_text[1]">$ <?php echo $simulatorIngresosPeriod[1]; ?></div></td>
        </tr>
        <tr id="row1" style="display:none">
        <td colspan="4">
        <?php showSimulator($is_simulator_page, 1, $data); ?>
        </td>
        </tr>
        
        <tr onClick="ShowHideRow(2)" style="cursor: pointer;">
        	<td colspan="4" style="background-color:#CCCCCC"><h6>Segundo cuatrimestre: <span style="color:rgb(32,135,201)"><span id="showRow2" style="text-align:right;">Mostrar</span> simulador <span id="Arrow2">&darr;</span></span></h6></td>
        </tr>
        
        <tr onClick="ShowHideRow(2)" style="cursor: pointer;">
           <td><p>Primas del período:</p>
           <input type="hidden" name="simulatorPrimasPeriod[2]" id="simulatorPrimasPeriod[2]" value="<?php echo $simulatorPrimasPeriod[2]; ?>" />
           </td>
           <td><div id="simulatorPrimasPeriod_text[2]">$ <?php echo $simulatorPrimasPeriod[2]; ?></div></td>		   
           <td><p>Ingresos del período</p><input type="hidden" name="simulatorIngresosPeriod[2]" id="simulatorIngresosPeriod[2]" value="<?php echo $simulatorIngresosPeriod[2]; ?>" /></td>
           <td><div id="simulatorIngresosPeriod_text[2]">$ <?php echo $simulatorIngresosPeriod[2]; ?></div></td>
        </tr>
        <tr id="row2" style="display:none">
        <td colspan="4">
        <?php showSimulator($is_simulator_page, 2, $data); ?>
        </td>
        </tr>
        
        <tr onClick="ShowHideRow(3)" style="cursor: pointer;">
        	<td colspan="4" style="background-color:#CCCCCC"><h6>Tercer cuatrimestre: <span style="color:rgb(32,135,201)"><span id="showRow3" style="text-align:right;">Mostrar</span> simulador <span id="Arrow3">&darr;</span></span></h6></td>
        </tr>
        
        <tr onClick="ShowHideRow(3)" style="cursor: pointer;">
           <td><p>Primas del período:</p>
           <input type="hidden" name="simulatorPrimasPeriod[3]" id="simulatorPrimasPeriod[3]" value="<?php echo $simulatorPrimasPeriod[3]; ?>" />
           </td>
           <td><div id="simulatorPrimasPeriod_text[3]">$ <?php echo $simulatorPrimasPeriod[3]; ?></div></td>		   
           <td><p>Ingresos del período</p><input type="hidden" name="simulatorIngresosPeriod[3]" id="simulatorIngresosPeriod[3]" value="<?php echo $simulatorIngresosPeriod[3]; ?>" /></td>
           <td><div id="simulatorIngresosPeriod_text[3]">$ <?php echo $simulatorIngresosPeriod[3]; ?></div></td>
        </tr>
        <tr id="row3" style="display:none">
        <td colspan="4">
        <?php showSimulator($is_simulator_page, 3, $data); ?>
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
        
       
     </table>
	
     
    
        
 </div> 

<?php 
function showSimulator($is_simulator_page, $i, $data) {
?>
<table width="100%">
        <tr>
           
           <td><label>No. de Negocios:</label></td>

           <td colspan="3"> <input type="text" readonly="readonly" class="input-small" name="noNegocios[<?php echo $i ?>]" id="noNegocios[<?php echo $i ?>]" value="<?php if ( isset($data->noNegocios) ) { $field = $data->noNegocios; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?>">
</td>
          
        </tr>
        <tr>
           <td><label style="color:#547EBD !important">Primas afectas iniciales para pagos de bonos</label></td>		
           <td>			  
              <p style="color:#547EBD !important; float:right" id="primasAfectasInicialesPagar_text[<?php echo $i ?>]">$ <?php if ( isset($data->primasAfectasInicialesPagar) ) { $field = $data->primasAfectasInicialesPagar; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0;?></p>	
              <input type="hidden" name="primasAfectasInicialesPagar[<?php echo $i ?>]" id="primasAfectasInicialesPagar[<?php echo $i ?>]" value="<?php if ( isset($data->primasAfectasInicialesPagar) ) { $field = $data->primasAfectasInicialesPagar; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?>">
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

           <td>

              <input type="text" class="input-small" name="primasRenovacion[<?php echo $i ?>]" id="primasRenovacion[<?php echo $i ?>]" value="<?php if ( isset($data->primasRenovacion) ) { $field = $data->primasRenovacion; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?>">

           </td>
		   
           <td><label>% de acotamiento:</label></td>

           <td>

              <input type="text" class="input-small" name="XAcotamiento[<?php echo $i ?>]" id="XAcotamiento[<?php echo $i ?>]" value="<?php if ( isset($data->XAcotamiento) ) { $field = $data->XAcotamiento; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?>">

           </td>
           
        </tr>
		
        
        <tr>
           <td><label style="color:#547EBD !important">Primas de renovación para pagar:</label></td>
           <td>			  
              <p style="color:#547EBD !important; float:right" id="primasRenovacionPagar_text[<?php echo $i ?>]">$ <?php if ( isset($data->primasRenovacionPagar) ) { $field = $data->primasRenovacionPagar; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?></p>	
              <input type="hidden" name="primasRenovacionPagar[<?php echo $i ?>]" id="primasRenovacionPagar[<?php echo $i ?>]" value="<?php if ( isset($data->primasRenovacionPagar) ) { $field = $data->primasRenovacionPagar; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?>">              

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
           <td colspan="2" align="left"><b style="color:#547EBD !important">COMISIONES</b></td>		
           <td><br></td>
           <td><br></td>  
        </tr>
        
         <tr>
           <td><label>% de comisión venta inicial:</label></td>
           <td>
              <input type="text" class="input-small" name="comisionVentaInicial[<?php echo $i ?>]" id="comisionVentaInicial[<?php echo $i ?>]" value="<?php if ( isset($data->comisionVentaInicial) ) { $field = $data->comisionVentaInicial; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?>">
           </td>		   
           <td><label style="color:#547EBD !important">Ingreso por comisiones:</label></td>
           <td>			  
              <p style="color:#547EBD !important; float:right" id="ingresoComisionesVentaInicial_text[<?php echo $i ?>]">$ <?php if ( isset($data->ingresoComisionesVentaInicial) ) { $field = $data->ingresoComisionesVentaInicial; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?></p>	
              <input type="hidden" name="ingresoComisionesVentaInicial[<?php echo $i ?>]" id="ingresoComisionesVentaInicial[<?php echo $i ?>]" value="<?php if ( isset($data->ingresoComisionesVentaInicial) ) { $field = $data->ingresoComisionesVentaInicial; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?>">           
           </td>  
        </tr>
        
        <tr>        	
           <td><label>% de comisión venta de renovación:</label></td>
           <td>
              <input type="text" class="input-small" name="comisionVentaRenovacion[<?php echo $i ?>]" id="comisionVentaRenovacion[<?php echo $i ?>]" value="<?php if ( isset($data->comisionVentaRenovacion) ) { $field = $data->comisionVentaRenovacion; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?>" >
           </td>   
           <td><label style="color:#547EBD !important">Ingreso por comisiones de renovación</label></td>
           <td>			  
              <p style="color:#547EBD !important; float:right" id="ingresoComisionRenovacion_text[<?php echo $i ?>]">$ <?php if ( isset($data->ingresoComisionRenovacion) ) { $field = $data->ingresoComisionRenovacion; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?></p>	
              <input type="hidden" name="ingresoComisionRenovacion[<?php echo $i ?>]" id="ingresoComisionRenovacion[<?php echo $i ?>]" value="<?php if ( isset($data->ingresoComisionRenovacion) ) { $field = $data->ingresoComisionRenovacion; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?>">              
           </td>
        </tr>        
        
         <tr>
           <td><br></td>
           <td><br></td>           
           <td><br></td>
           <td><br></td>
        </tr>
        
         <tr>
		   <td colspan="2" align="left"><b style="color:#547EBD !important">BONO DE PRIMER AÑO</b></td>
           <td><br></td>
           <td><br></td>
        </tr>

        <tr>
           <td><label>% de bono aplicado:</label></td>
           <td>
              <input type="text" class="input-small" name="bonoAplicado[<?php echo $i ?>]" id="bonoAplicado[<?php echo $i ?>]" value="<?php if ( isset($data->bonoAplicado) ) { $field = $data->bonoAplicado; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?>" readonly="readonly">
           </td>              
           <td><label style="color:#547EBD !important">Ingreso por bono de productividad:</label></td>
           <td>			  
              <p style="color:#547EBD !important; float:right" id="ingresoBonoProductividad_text[<?php echo $i ?>]">$ <?php if ( isset($data->ingresoBonoProductividad) ) { $field = $data->ingresoBonoProductividad; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?></p>	
              <input type="hidden" name="ingresoBonoProductividad[<?php echo $i ?>]" id="ingresoBonoProductividad[<?php echo $i ?>]" value="<?php if ( isset($data->ingresoBonoProductividad) ) { $field = $data->ingresoBonoProductividad; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?>">
                         </td>
        </tr>

        <tr>
           <td><br></td>
           <td><br></td>           
           <td><br></td>
           <td><br></td>
        </tr>
        
        <tr>
           <td colspan="2" align="left"><b style="color:#547EBD !important">BONO DE RENTABILIDAD DE CARTERA</b></td>
		   <td><br></td>
           <td><br></td>
        </tr>

        <tr>

           <td><label>% de siniestridad:</label></td>
           <td>
			  <select name="porsiniestridad[<?php echo $i ?>]" id="porsiniestridad[<?php echo $i ?>]" class="input-small">
              		<option value="">Seleccione</option>
                    <option value="68" <?php if ( isset($data->porsiniestridad) ) { $field = $data->porsiniestridad; if( isset( $field->$i ) and $field->$i == 68 ) echo 'selected="selected"'; } ?>>68</option>
                    <option value="64" <?php if ( isset($data->porsiniestridad) ) { $field = $data->porsiniestridad; if( isset( $field->$i ) and $field->$i == 64 ) echo 'selected="selected"'; } ?>>64</option>
                    <option value="60" <?php if ( isset($data->porsiniestridad) ) { $field = $data->porsiniestridad; if( isset( $field->$i ) and $field->$i == 60 ) echo 'selected="selected"'; } ?>>60</option>
              </select>
              
           </td>           
           <td><label>% de bono ganado:</label></td>
           <td>
<?php

if ($is_simulator_page)
{
	$b_name = "porbonoGanado_$i";
	$b_value = (isset($data->$b_name)) ? $data->$b_name : 0;
}
else
{
	$b_name = 'porbonoganado[' . $i . ']';
	if ( isset($data->porbonoganado) ) 
	{
		$field = $data->porbonoganado; 
		if( isset( $field->$i ) )
			$b_value = $field->$i;
		else
			$b_value = 0;
	} 
	else
		$b_value = 0;
}

?>
<input readonly="readonly" type="text" name="<?php echo $b_name; ?>" id="<?php echo $b_name; ?>" value="<?php echo $b_value; ?>">
           </td>
        </tr>

        <tr>

		   <td><br></td>
           <td><br></td>
           <td><label style="color:#547EBD !important">Ingreso por bono de renovación:</label></td>

           <td>
			  
              <p style="color:#547EBD !important; float:right" id="ingresoBonoRenovacion_text[<?php echo $i ?>]">$ <?php if ( isset($data->ingresoBonoRenovacion) ) { $field = $data->ingresoBonoRenovacion; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?> </p>	
              <input type="hidden" name="ingresoBonoRenovacion[<?php echo $i ?>]" id="ingresoBonoRenovacion[<?php echo $i ?>]" value="<?php if ( isset($data->ingresoBonoRenovacion) ) { $field = $data->ingresoBonoRenovacion; if( isset( $field->$i ) ) echo $field->$i; else echo 0; } else echo 0; ?>" >

           </td>
           

        </tr>
</table>
<?php	
}
?>