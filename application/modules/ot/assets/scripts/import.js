var dataform;

$(document).ready(function() { 
	var months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
	
	//functions
	$(function(){
		var imports = {
			init: function(){
				this.cacheDom();
				this.binder();
				this.render();
			},
			showLoadingScreen: function(){
				swal("Importando archivo, por favor espere...", {
					icon: Config.base_url()+'ot/assets/images/loading.gif',
					button: false,
					closeOnClickOutside: false,
				});
	        },
			cacheDom: function() {
				this.$formDelete = $("#import-delete");
				this.$product = $("#product-type-delete");
				this.$selectedMonth =this.$formDelete.find("#month-delete");
				this.$month = $('#month-delete').find('option:selected').val();
				this.$selectedYear = this.$formDelete.find("#year-delete");
				this.$btnDelete = this.$formDelete.find("#delete-submit");
				this.$importPayment = $('#formfile');
				this.$importFile = $('#import-form');
				this.$btnImport = this.$importFile.find("#btnImport");
				this.$dialog = $("#dialog-form");
				this.$control = $("#control").val(this.id);
				this.$btnOpen = $(".create-user");
			},
			binder: function(){
				this.$btnDelete.on('click', this.deletePayments.bind(this));
				this.$btnOpen.on('click', this.openDialog.bind(this));
				this.$btnImport.on('click', this.showLoadingScreen.bind(this));
				//this.$importPayment.on('submit',this.importPayments.bind(this));
			},
			render: function(){
				this.$dialog.dialog({
					autoOpen: false,
					height: 600,
					width: 800,
					modal: true,
					buttons: {
						Cerrar: function() {
							$( this ).dialog( "close" );
							$.ajax({

								url: Config.base_url()+'ot/getSelectAgents.html',
								type: "POST",
								cache: false,
								async: false,
								success: function(data){
									var option = this.$control.val();

									option = option.split('-');

									$( '.options-'+option[1] ).html(data);
								}
							});
						}
					}
				});
			},
			deletePayments: function(){
				swal({
					title: "¿Esta seguro que desea eliminar estos pagos?",
					text: "Una vez realizada la operación, no se podrá deshacer.",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {
						url = Config.base_url() + 'ot/delete_payments.html';
						$.ajax({
							url: url,
							type: 'POST',
							data: $( "#import-delete").serialize(),
							dataType : 'json',
							beforeSend: function(){
								$( "#delete-submit").hide();
							},
							success: function(response){
								console.log(this.$month);
								$( "#delete-submit").show();
								switch (response) {
									case '-1':
									swal ('No se pudo borrar los pagos. Informe a su administrador.');
									break;
									case '-2':
									swal ('Ocurrio un error, no se pudo borrar los pagos, consulte a su administrador.');
									break;
									case '0':
									swal ('No hay pagos para el mes - año - producto seleccionados.');
									break;
									default:
									swal("La operación se ha realizado con exito!", {
										icon: "success",
									});
									break;
								}
							}
						});
					}
				});
				return false;
				},
			importPayments: function(){
				$.ajax({
					url:Config.base_url() +"ot/import_payments",
					method:"POST",
					data:new FormData(this),
					contentType:false,
					cache:false,
					processData:false,
					beforeSend:function(){
						this.$btnImport.html('Importando...');
					},
					success:function(data)
					{
						this.$importPayment.reset();
						this.$btnImport.attr('disabled', false);
						this.$btnImport.html('Importar');
						swal("La importación se ha realizado con exito!", {
							icon: "success",
						});
					}
				})
				return false;
			},
			openDialog: function() {
				this.render();
				this.$dialog.dialog( "open" );
			}
		};
		imports.init();
	});

	function load_sheet_data(dataform){
		return $.ajax({
			url:Config.base_url() +"ot/read_sheet_name",
			method:"POST",
			datatype: 'json',
			contentType:false,
			cache:false,
			processData:false,
			data: dataform
		}).then(function(result){
			debugger
			var json = JSON.parse(result);
			
			if(json.status === "success"){
				$('#loader_' + json.sheet_name).text('');
				$('#loader_' + json.sheet_name).append('<div class="progress progress-success active"><div class="bar" style="width: 100%">✔ Datos cargados correctamente</div></div>');
			}
			else
			{
				$('#loader_' + json.sheet_name).text('');
				$('#loader_' + json.sheet_name).append('<div class="progress progress-danger active"><div class="bar" style="width: 100%">Hubo un problema con al importar datos</div></div>');
			}
			
		});
	}
	function func_vinculate_selo(){
		return $.ajax({
			url: Config.base_url() + 'ot/vinculate_selo_agents',
			type:'POST',
			datatype: 'json',
			success:function(result){
				$('#loader_func_agents').text('');
				$('#loader_func_agents').append('<div class="progress progress-success active"><div class="bar" style="width: 100%">✔ Agentes vinculados correctamente</div></div>');
			},
			error: function(xhr, status, error) {
				console.log(error);
			}
		});
	}
	function func_fix_agents(){
		return $.ajax({
			url: Config.base_url() + 'ot/fix_agents_uids',
			type:'POST',
			datatype: 'json',
			success:function(result){
				$('#loader_func_uids').text('');
				$('#loader_func_uids').append('<div class="progress progress-success active"><div class="bar" style="width: 100%">✔ Claves cambiadas correctamente</div></div>');
			},
			error: function(xhr, status, error) {
				console.log(error);
			}
		});
	}
	function func_lost_data(){
		return $.ajax({
			url: Config.base_url() + 'ot/get_lost_data',
			type:'POST',
			datatype: 'json',
			success:function(result){				
				array = JSON.parse(result);
				
				if(array.length != 0){
					$("#errors_import").append("<p class='text-error'><i class='icon-exclamation-sign'></i> Los siguientes valores no pudieron ser importados correctamente:</p>");
				}
				$.each(array, function(key, value) {
  					$.each(value, function(key_arr, value_arr) {
  	
					    if (key_arr == 'extras'){
					    	$.each(value.extras, function(key_extra, val_extra){
					        	$("#errors_import").append("<h6><p>" + key_extra + " : " + val_extra + "</p></h6>");
					      	});
					    }
    					else{
     						$("#errors_import").append("<h6><p>" + key_arr + " : " + value_arr + "</p></h6>");
    					}
  					});
  					$('#errors_import').append('<h6><hr style="width:100%;"></h6>');
				});
			},
			error: function(xhr, status, error) {
				console.log(error);
			}
		});
	}
	function func_last_settings()
	{
		return $.ajax({
			url: Config.base_url() + 'ot/exec_last_funct_termometro',
			type:'POST',
			datatype: 'json',
			success:function(result){
				$('#loader_func_final').text('');
				$('#loader_func_final').append('<div class="progress progress-success active"><div class="bar" style="width: 100%">✔ Ultimos ajustes completos</div></div>');
			},
			error: function(xhr, status, error) {
				console.log(error);
			}
		});
	}
	function load_final_functionality()
	{
		$.when(
			func_vinculate_selo()
		).then(
			function(){
				$.when(func_fix_agents()).then(function(){
					$.when(func_lost_data()).then(function(){
						func_last_settings();
					});
				});
			}
		);
	}

	//funtionality of components
	$("#form-test").on('submit', function(event){
		var dataform = new FormData(this);
		var array;
		event.preventDefault();
		$("#btnImport_selo").prop('disabled',true);
		$("#btnImport_selo").text('Cargando...');
		$('#btnImport_selo').removeAttr('data-toggle');
		$('#body_table tr').remove();

		$.when(
			$.ajax({
				url: Config.base_url() + 'ot/get_sheets_names',
				type:'POST',
				datatype: 'json',
				data: dataform,
				contentType:false,
				cache:false,
				processData:false,
				success:function(result){
					array = JSON.parse(result);
					console.log(array);
					$.each(array, function (i, sheet) {
						$('#body_table').append('<tr><td>'+sheet+'</td><td id="loader_'+ sheet +'"<div class="progress progress-striped active"><div class="bar" style="width: 100%;">Cargando datos de la pagina ...</div></div></td></tr>');
	                });
	                $('#body_table').append('<tr><td>Vincular datos de SELO con agentes</td><td id="loader_func_agents"<div class="progress progress-striped active"><div class="bar" style="width: 100%;">Vinculando datos a agentes ...</div></div></td></tr>');
	                $('#body_table').append('<tr><td>Revisando cambios de claves de agentes</td><td id="loader_func_uids"<div class="progress progress-striped active"><div class="bar" style="width: 100%;">Revisando cambios en claves de agentes ...</div></div></td></tr>');
	                $('#body_table').append('<tr><td>Ajustes Finales</td><td id="loader_func_final"<div class="progress progress-striped active"><div class="bar" style="width: 100%;">Realizando ajustes finales ...</div></div></td></tr>');

				},
				error: function(xhr, status, error) {
					console.log(error);
				}
			})
		).then(
			function(){
				if($.inArray("produccion_",array)!=-1)
				{
					var remove = "produccion_";
					array.splice( $.inArray(remove,array) ,1 );
				}
				if($.inArray('produccion_sa',array)!=-1)
				{
					var remove = "produccion_sa";
					array.splice( $.inArray(remove,array) ,1 );
				}
				if($.inArray('conservacion_',array)!=-1)
				{
					var remove = "conservacion_";
					array.splice( $.inArray(remove,array) ,1 );
				}
				var num_sheets = array.length;
				var num_sheets_complete = 0;
				dataform.append('name','produccion_');
				$.when(load_sheet_data(dataform))
				.then(
					function()
					{
						dataform.append('name','produccion_sa');
						dataform.delete('index');
						$.when(load_sheet_data(dataform))
						.then(
							function()
							{
								dataform.append('name','conservacion_');
								dataform.delete('index');
								$.when(load_sheet_data(dataform)).then(
									function()
									{
										if(num_sheets == 0){
											load_final_functionality();
											$("#btnImport_selo").prop('enabled',true);
											$("#btnImport_selo").prop('disabled',false);
											$("#btnImport_selo").text('Cargar');
										}else{
											$.each(array, function (i, sheet)
											{
												dataform.append('name',sheet);
												dataform.delete('index');
												$.when(
													load_sheet_data(dataform))
												.then(
													function()
													{
														num_sheets_complete = num_sheets_complete + 1;
														if(num_sheets_complete == num_sheets){
															load_final_functionality();
														    $("#btnImport_selo").prop('enabled',true);
															$("#btnImport_selo").prop('disabled',false);
															$("#btnImport_selo").text('Cargar');
														}
														
													}
												);
											});
										}
									}
								);
							}
						);
					}
				);
			}
		);
		
	});

	$( "#dialog-form" ).dialog({
  		autoOpen: false,
  		height: 600,
  		width: 800,
  		modal: true,
  		buttons: {
  			Cerrar: function() {
  				$( this ).dialog( "close" );
  				$.ajax({

  					url:  Config.base_url()+'ot/getSelectAgents.html',
  					type: "POST",
  					cache: false,
  					async: false,
  					success: function(data){
  						var option = $( '#control' ).val();

  						option = option.split('-');

  						$( '.options-'+option[1] ).html(data);
  					}
  				});
  			}
  		}
  	});

  	/*

	$("#formfile").on('submit',function(event){
		event.preventDefault();
		dataform = new FormData(this);
		var array;
		
		$("#btnImport").prop('disabled',true);
		$("#btnImport").text('Cargando...');
		$('#body_table_import tr').removeAttr('data-toggle');
		$.ajax({
			url: Config.base_url() + 'ot/get_date_import',
			type:'POST',
			datatype: 'json',
			data: dataform,
			contentType:false,
			cache:false,
			processData:false,
			success:function(result){
				array = JSON.parse(result);
				$('#content_collapse').append('<div class="control-group"><div class="alert alert-info">Se encontró la siguiente fecha de importación, presione "Pre importar" para continuar.</div><label class="control-label text-error" for="month_year">Del mes / año: &nbsp;</label><div class="controls"><select name="month_delete" id="month-delete" class="required" style="width: 8em"><option >'+months[parseInt(array['month'])-1]+'</option></select><select name="year_delete" id="year-delete" class="required" style="width: 8em"><option>'+array['year']+'</option></select></div></div>');
				$('#content_collapse').append('<div class="form-actions"><button id="send_pre_import" type="button" class="btn btn-primary" data-toggle="collapse" data-target="#table_collapse_preview" onclick="javascript: preimport()">Pre Importar</button><input type="button" class="btn" onclick="javascript: history.back()" value="Cancelar"></div>')
			},
			error: function(xhr, status, error) {
				console.log(error);
			}
		});
		
	});
	*/
	$("#formfile").on('submit',function(event){
		event.preventDefault();
		dataform = new FormData(this);
		$("#btnImport").prop('disabled',true);
		$("#btnImport").text('Cargando...');
		$('#body_table_import tr').removeAttr('data-toggle');
		$.ajax({
			url: Config.base_url() + 'ot/get_date_import',
			type:'POST',
			datatype: 'json',
			data: dataform,
			contentType:false,
			cache:false,
			processData:false
		}).done(function(result){
			array = JSON.parse(result);
			str = '<div class="control-group">';
			str = str+'<div class="alert alert-info">Se encontró la siguiente fecha de importación, presione "Pre importar" para continuar.</div>';
			str = str+'<label class="control-label text-error" for="month_year">Del mes / año: &nbsp;</label>';
			str = str+'<div class="controls">';
			str = str+'		<select name="month_delete" id="month-delete" class="required" style="width: 8em">';
			str = str+'			<option >'+months[parseInt(array['month'])-1]+'</option>';
			str = str+'		</select>';
			str = str+'		<select name="year_delete" id="year-delete" class="required" style="width: 8em">';
			str = str+'			<option>'+array['year']+'</option>';
			str = str+'		</select>';
			str = str+'</div>';
			str = str+'</div>';
			str = str+'<div class="form-actions">';
			str = str+'		<button id="send_pre_import" type="button" class="btn btn-primary" data-toggle="collapse" data-target="#table_collapse_preview" onclick="javascript: preimport()">Pre Importar</button>';
			str = str+'		<input type="button" class="btn" onclick="javascript: history.back()" value="Cancelar">';
			str = str+'</div>';
			$('#content_collapse').append(str);
			//$('#content_collapse').append('<div class="control-group"><div class="alert alert-info">Se encontró la siguiente fecha de importación, presione "Pre importar" para continuar.</div><label class="control-label text-error" for="month_year">Del mes / año: &nbsp;</label><div class="controls"><select name="month_delete" id="month-delete" class="required" style="width: 8em"><option >'+months[parseInt(array['month'])-1]+'</option></select><select name="year_delete" id="year-delete" class="required" style="width: 8em"><option>'+array['year']+'</option></select></div></div>');
			//$('#content_collapse').append('<div class="form-actions"><button id="send_pre_import" type="button" class="btn btn-primary" data-toggle="collapse" data-target="#table_collapse_preview" onclick="javascript: preimport()">Pre Importar</button><input type="button" class="btn" onclick="javascript: history.back()" value="Cancelar"></div>')

		}).fail(function(error){
			console.log(error);
		});
	});

});

function preimport(){
	//$("#send_pre_import").prop('disabled',true);
	$("#send_pre_import").text('Pre-Importando...');
	//
	var selected = $("#product_select").val();
	var head = '';
	if (selected == 8){
		head = '<tr><th>Clave de Agente</th><th>Nombre de Agente</th><th>Folio nacional</th><th>Agente</th><th>Poliza</th><th>Año prima</th><th>Fecha de pago real</th><th>Prima </th><th>Porcentage para prima para ubicar</th><th>Porcentage para prima para pago de bono</th><th>Asegurado</th></tr>';
	}else{
		head = '<tr><th>Fecha de pago real</th><th>Nombre de Agente</th><th>Folio provincial</th><th>Agente</th><th>Poliza</th><th>Año prima</th><th>Prima</th><th>Prima a ubicar</th><th>Prima para pago de bono</th><th>Asegurado</th><th>Tipo de plan</th><th>Tipo Deducible</th></tr>';
	}
	$.ajax({
		url: Config.base_url() + 'ot/get_preview_import',
		type:'POST',
		datatype: 'json',
		data: dataform,
		contentType:false,
		cache:false,
		processData:false,
		success:function(result){
			$('#head_preview').append(head);
			$('#body_table_import_preview').append(result);
			$('#content_collapse_preview').append('<div class="form-actions" id="actions_form_import"><button id="send_import" type="button" class="btn btn-primary" data-toggle="collapse" data-target="#table_collapse_preview" onclick="javascript: import_file()">Importar</button><input type="button" class="btn" onclick="javascript: history.back()" value="Cancelar"><p id="text-status-loading" class="text-warning" hidden>Tiempo estimado de importacion: 5-10 minutos...</p><p id="text-status-success" class="text-success" hidden>Importacion completa</p><p id="text-status-incomplete" class="text-warning" hidden>Importacion completa pero con datos faltantes</p></div>')
			$("#send_pre_import").attr('disabled',true);
		},
		error: function(xhr, status, error) {
			console.log(error);
		}
	});
}

function import_file(){
	$("#send_import").prop('disabled',true);
	$("#send_import").text('Importando...');
	$('#text-status-loading').show();
	$.when(execute_import()).then(function(){
		/*
		$("#send_import").prop('disabled',false);
		$("#send_import").text('Importar');
		$("#text-status").removeClass("text-warning");
		$("#text-status").addClass("text-success");
		$("#text-status").text("");
		$("#text-status").addClass("Importación completa");
		*/
	});
}

function execute_import(){
	return $.ajax({
		url: Config.base_url() + 'ot/import_file_payments',
		type:'POST',
		datatype: 'json',
		data: dataform,
		contentType:false,
		cache:false,
		processData:false,
		success:function(result){	
			if(result!="success"){
				$('#text-status-loading').hide();
				$('#text-status-incomplete').show();
				$('#alert-text-table').hide();
				$('#alert-text-warning').show();
				$('#content_collapse_preview').html("");
				$('#content_collapse_preview').append('<div class="alert alert-warning">Los siguientes registros no se pudieron vincular a un agente, vinculelos si estan presentes, de lo contrario, registrelos y vuelva a importar el archivo.</div>');
				$('#content_collapse_preview').append(result);
			}else{
				$('#text-status-loading').hide();
				$('#text-status-success').show();
			}
		},
		error: function(xhr, status, error) {
			console.log(error);
		}
	});
}

function update_payment(id_payment, select){
	var id_agent = select.value;
	$.ajax({
		url: Config.base_url() + 'ot/update_payment',
		type:'POST',
		datatype: 'json',
		data: {id_payment:id_payment, id_agent: id_agent},
		success:function(result){	
			console.log(result);
		},
		error: function(xhr, status, error) {
			console.log(error);
		}
	});
}

function reset_import(){

}

