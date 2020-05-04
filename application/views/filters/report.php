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
$selected_period = get_filter_period();
$base_url = base_url();
?>

                                <table class="filterstable" style="width:99%;">
                                    <thead>
                                        <tr style="vertical-align: top;">
                                            <th>
                                                <?php echo $period_fields ?>
                                                <select id="periodo_form" name="periodo" style="width: 175px" title="Período">
                                                      <option value="<?php echo $selected_period ?>"></option>
                                                </select>
                                                <input type="hidden" value="<?php echo $selected_period ?>" id="periodo" name="query[periodo]" />
                                                <?php if (isset($selection_filters) && isset($selection_filters['begin']) && isset($selection_filters['end'])): ?>
                                                <input type="hidden" value="<?php echo $selection_filters['begin'] ?>" id="start-d" name="start_d" />
                                                <input type="hidden" value="<?php echo $selection_filters['end'] ?>" id="end-d" name="end_d" />
                                                <?php endif; ?>

                                            </th>
                                            <th>
                                                <input type="hidden" id="gerente_value" value="<?php echo $other_filters['gerente']; ?>" />
                                                <select id="gerente" name="query[gerente]" class="select" style="width:145px;" onchange="this.form.submit();">
                                                    <option value="">   Todos los gerentes</option>                                        
                                                    <?php if (!empty($manager)): foreach ($manager as $value): ?>

                                                    <option <?php if ($other_filters['gerente'] == $value['id']) echo 'selected="selected"' ?> value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                                                    <?php endforeach;
                                                    endif; ?>
                                                </select>
                                            </th>
                                            <th>
                                                <select id="agent" name="query[agent]" class="select2" style="width:140px;" onchange="this.form.submit();">
                                                    <option value="" <?php if (!$other_filters['agent'] || ($other_filters['agent'] == 1)) echo 'selected="selected"' ?>>Todos los agentes</option>
                                                    <option value="2" <?php if ($other_filters['agent'] == 2) echo 'selected="selected"' ?>>Cancelados</option>
                                                    <option value="3" <?php if ($other_filters['agent'] == 3) echo 'selected="selected"' ?>>Vigentes</option>
                                                </select>
                                            </th>
                                            <th>
                                                <select id="prime_type" name="query[prime_type]" class="select2" style="width:180px;" onchange="this.form.submit();">
                                                    <option value="amount" <?php if (!$other_filters['prime_type'] || ($other_filters['prime_type'] == "amount")) echo 'selected="selected"' ?>>Primas totales</option>
                                                    <option value="allocated_prime" <?php if ($other_filters['prime_type'] == "allocated_prime") echo 'selected="selected"' ?>>Primas a ubicar</option>
                                                    <option value="bonus_prime" <?php if ($other_filters['prime_type'] == "bonus_prime") echo 'selected="selected"' ?>>Primas para pago de bono</option>
                                                </select>
                                            </th>
                                            <th>
                                                <select id="generation" name="query[generacion]" class="select3" style="width:180px;" onchange="this.form.submit();">
                                                    <?php echo '<pre>'; print_r($other_filters); echo '</pre>';
                                                        //using helper
                                                        foreach ($generations_list as $key => $generation) {
                                                            if ($other_filters['generacion'] == $key) {
                                                                $select = 'selected="selected"';
                                                            } else {
                                                                $select = '';
                                                            }
                                                            echo '<option value="'.$key.'" '.$select.'>'.$generation.'</option>';
                                                        }
                                                    ?>
                                                    <!--
                                                    <option value="" <?php if (!$other_filters['generacion'] || ($other_filters['generacion'] == 1)) echo 'selected="selected"' ?>>Todas las Generaciónes</option>
                                                    <option value="3" <?php if ($other_filters['generacion'] == 3) echo 'selected="selected"' ?>>Generación 1</option>
                                                    <option value="4" <?php if ($other_filters['generacion'] == 4) echo 'selected="selected"' ?>>Generación 2</option>
                                                    <option value="5" <?php if ($other_filters['generacion'] == 5) echo 'selected="selected"' ?>>Generación 3</option>
                                                    <option value="6" <?php if ($other_filters['generacion'] == 6) echo 'selected="selected"' ?>>Generación 4</option>
                                                    <option value="2" <?php if ($other_filters['generacion'] == 2) echo 'selected="selected"' ?>>Consolidado</option>
                                                    -->
                                                </select>
                                            </th>
                                            <?php render_custom_filters() ?>
                                            <th style="white-space:nowrap;" title="Escriba el nombre del agente que desea buscar y selecciónelo de la lista que aparece. Puede buscar más posteriormente en la siguiente línea.">
                                                <textarea placeholder="AGENTES" id="agent-name" name="query[agent_name]" rows="1" class="input-xlarge select4" style="min-width: 250px; height: 2.2em"><?php echo $other_filters['agent_name']; ?></textarea>
                                            </th>
                                            <th>
                                                <i style="cursor: pointer; vertical-align: top" class="icon-filter submit-form" id="submit-form1" title="Filtrar"></i>
                                                <br />
                                                <i style="cursor: pointer;" class="icon-list-alt" id="clear-agent-filter" title="Mostrar todos los agentes"></i>
                                            </th>
                                            <th style="white-space:nowrap;" title="Escriba el número de póliza que desea buscar y selecciónelo de la lista que aparece. Puede buscar más posteriormente en la siguiente línea.">
                                                <textarea placeholder="PÓLIZAS" id="policy-num" name="query[policy_num]" rows="1" class="input-small select4" style="min-width: 8em; height: 2.2em"><?php echo $other_filters['policy_num']; ?></textarea>
                                            </th>
                                            <th>
                                                <i style="cursor: pointer; vertical-align: top" class="icon-filter submit-form" id="submit-form2" title="Filtrar"></i>
                                                <br />
                                                <i style="cursor: pointer;" class="icon-list-alt" id="clear-policy-filter" title="Ningun filtro por el número de póliza"></i>
	                                        </th>
                                            <th>&nbsp; </th>
                                            <th></th>
                                            <th width="50%" align="right" >
<?php if ($export_xls): ?>
                                                <a href="javascript:void(0);" class="download" id="export">
                                                <img src="<?php echo $base_url ?>ot/assets/images/down.png"></a>
<?php endif; ?>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
