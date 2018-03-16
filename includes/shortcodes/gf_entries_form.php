<?php

//Shorcode que muestra el data-table con las entradas
function gf_entries_form($atts){ 

	$a = shortcode_atts( array(
        'id_formulario' => '',
        'campos' => '',
    ), $atts );

    $html_respuesta = '';
	

	//echo('Resultado: ' . $str_getparemeters);
	
	if($a['id_formulario'] != ''){		
		$id_formulario = $a['id_formulario'];
		$str_campos = $a['campos'];
	}else{
		//viene por GET
		//echo('Viene por GET');
		//en la variable id del get vienen los campos y el formulario
		$str_getparemeters = $_GET['id'];
		//lo volvemos a poner como string
		$str_getparemeters = hexToString($str_getparemeters);
		//desencriptamos
		$str_getparemeters = des ('dj7zN8d7H', $str_getparemeters, 0, 0, null);
		
		//echo('<br>');
		//echo($str_getparemeters);
		
		$id_formulario = explode(";", $str_getparemeters)[0];
		//echo('<br>');
		//echo(explode(";", $str_getparemeters)[0]);
		$str_campos = explode(";", $str_getparemeters)[1];
		//echo('<br>');
		//echo(explode(";", $str_getparemeters)[1]);
	
	}
	
	$nombre_campos = gfapi_names_fields($id_formulario);

	$campos = Array();
	$todos_campos = false;
	
	if($str_campos == -1){
		
		$todos_campos = true;
		$contador = 0;
		
		foreach($nombre_campos as $key => $val){
			$campos[$contador] = $key;
			$contador = $contador + 1;
		}
		
	}else{
		$campos = explode(",", $str_campos);
		array_unshift($campos, "date_created");
	}

	
	
	//die();
	
	//echo('<br><br>');	
	//foreach($campos as $key => $valor){
		//echo ('<li>Key: ' . $key . ' Value: ' . $valor . ' - Nombre: ' . $nombre_campos[$valor + 0] . '</li>');
	//}
	
	$numero_campos = count($campos);

	
	//print_r($nombre_campos);
	//die('hola ' . file_get_contents($url));
	
	//pintamos la tabla de resultados

	$html_respuesta .= '<script type="application/javascript">
		var id_formulario = ' . $id_formulario . ';
		
		var todos_campos = false;';
		

if($todos_campos){
		$html_respuesta .= 'var todos_campos = true;';
}


        $html_respuesta .= 'var sourceURL = \'' . get_site_url() . '/wp-admin/admin-ajax.php\';
        var dataTable;
        var oSettings;
		var id_formulario = ' . $id_formulario . ';
		
		var swiper = \'\';

		 
		jQuery(document).ready(function($)
        {
			dataTable = $(\'#table_frontendgf_entries\').dataTable({
                "ajax": {
					"url": sourceURL,
					"dataSrc": \'customData\',
					"data": {
			           "id_formulario": ' . $id_formulario . ',
			           "ajax" : 1,
			           "action": \'gf_entries_form\',
			        }
				},
				"language": {
					"url": \'' . str_replace("/includes/shortcodes","",plugin_dir_url( __FILE__ )) . 'js/data-table-Spanish.json\'
				},
				"dom": \'Bfrtip\',
				"lengthMenu": [
					[ 10, 50, 100, -1 ],
					[ \'10 filas\', \'50 filas\', \'100 filas\', \'Todo\' ]
				],
				"buttons": [
					\'pageLength\',
					\'copyHtml5\',
					\'excelHtml5\',
					\'csvHtml5\',
					\'pdfHtml5\'],
				"scrollX": false,
				"initComplete": function(settings, json) {
									//alert( \'DataTables has finished its initialisation.\' );
									init_swiper();
								},
                "columns": [';

	foreach($campos as $campo){
		if($campo == 0){
			$html_respuesta .= '{ "bVisible":    true, "bSortable": "true", "data": "' . trim($campo) . '" },';
		}else{
			$html_respuesta .= '{ "bVisible":    true, "bSortable": "true", "data": "' . trim($campo) . '" },';
		}
		
	}

            $html_respuesta .= ']
            });
			//forzamos que no muestre error
			//todos_campos = true;
			if(todos_campos){
				//cuando mostramos todos los campos hay valores que vienen vacios y no muestra alerts con warnings. Los quitamos:
				$.fn.dataTable.ext.errMode = \'none\';
			}
			
			$(\'#table_frontendgf_entries tbody\').on(\'click\', \'tr\', function () {
			  var aData = dataTable.fnGetData( this );
			  id_entrie = aData[\'id\'];
			  jQuery("#lid").val(id_entrie);
			  jQuery("#entry_id").val(id_entrie);
			  jQuery("#id").val(' . $id_formulario . ');
			  editar_entrada();
			} );
			
			function editar_entrada(){
				id_entrie = jQuery("#entry_id").val();
				jQuery.ajax({
					type: "POST",
					url: "' . get_site_url() . '/wp-admin/admin-ajax.php?view=entry&id=' . $id_formulario . '&lid=" + id_entrie + "&orderby=ASC&filter&paged=1",
					data:jQuery(\'#form_edit\').serialize(),					
					success: function(msg){
						//alert(msg);
						//$("#form-content").modal(\'hide\');	
						},
					error: function(){
						alert("failure");
					}
				});
			}			
			
			function init_swiper(){
				//alert($("#table_frontendgf_entries").outerHTML());
				
				 //var str_html = $("#table_frontendgf_entries").outerHTML();
				 //var str_html_pre = \'<div class="swiper-container"><div class="swiper-wrapper"><div class="swiper-slide" id="#swiperslide">\';		
				 //var str_html_post = \'</div><!-- wiper-slide --></div><!-- fin swiper-wrapper --><div class="swiper-scrollbar"></div></div><!-- fin //swiper-container -->\';

				//str_html = str_html_pre + str_html + str_html_post;
				
				//$("#table_frontendgf_entries").remove();
				
				//$( "#table_frontendgf_entries_filter" ).after( str_html);
				
				
				$("#table_frontendgf_entries").appendTo("#swiperslide");
				
				
								
				 
				 //$(".prueba").appendTo("#swiperslide");
				
				
				swiper = new Swiper(\'.swiper-container\', {
					scrollbar: \'.swiper-scrollbar\',
					slidesPerView: \'auto\',
					mousewheelControl: true,
					scrollbarDraggable: true,
					mousewheelSensitivity:5,
					scrollbarHide: false,
					autoHeight: true,
					freeMode: true
				});
				
				
				//setInterval(function(){swiper.updateSlidesSize();}, 8000);

				swiper.disableMousewheelControl();
				
				dataTable.on( \'search.dt\', function () {
					//dataTable//.draw();
					swiper.update(true);
					//swiper.slideTo(1);
				} );
				
			}
			
			
			
			
			
			
			
			
			var DIV = document.createElement("div"),
			outerHTML;

			if (\'outerHTML\' in DIV) {
				outerHTML = function(node) {
					return node.outerHTML;
				};
			} else {
				outerHTML = function(node) {
					var div = DIV.cloneNode();
					div.appendChild(node.cloneNode(true));
					return div.innerHTML;
				};
			}

			$.fn.outerHTML = function() {
				return this.length ? outerHTML(this[0]) : void(0);
			};
					
			
			
			
			
			
			
			
			
			
			
			
			/*
			jQuery(\'#table_frontendgf_entries tbody\').on( \'click\', \'td\', function () {
				var cell = dataTable.cell( this );
				alert(cell.data());
				cell.data( cell.data() + 1 ).draw();
				// note - call draw() to update the tables draw state with the new data
			} );
			*/
        });
		
		
        </script>
		<div class="frontendgf_formname"><h2>' . gfap_name_form($id_formulario) . '</h2></div>
		

        <div class="page">
            <section class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-globe"></span> Lista de entradas</strong></div>
                <div class="panel-body">
                    <!--<div style="width: 100%; display:inline-block;">
                        <a class="btn btn-default btn-md pull-right" style="margin-bottom:10px;" href=""><span class="glyphicon glyphicon-plus-sign"></span> Crear usuario</a>
                    </div>-->
                    <div class="table-responsive">
						<table id="table_frontendgf_entries" class="table table-striped table-bordered">
							<thead>
								<tr>';

									foreach($campos as $key => $value){
										$html_respuesta .= '<th>' . $nombre_campos[trim($value)] . '</th>';
									}

									$html_respuesta .= '
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						<div class="swiper-container">
							<div class="swiper-wrapper">
								<div class="swiper-slide" id="swiperslide"></div>
							</div>
							<div class="swiper-scrollbar"></div>
						</div>
					</div><!-- fin table-responsive -->
				</div><!-- fin panel-body -->
            </section>
        </div><!-- fin page -->
		
		<form method="post" id="form_edit" name="form_edit">
			<input type="hidden" name="new_note" value="">
			<input type="hidden" name="bulk_action" value="">
			<input type="hidden" name="note" value="">
			<input type="hidden" name="print_notes" value="print_notes">
			
			<input type="hidden" name="screen_mode" value="edit">

			<input type="hidden" name="entry_id" id="entry_id" value="">
			<input type="hidden" name="id" id="id" value="">
			<input type="hidden" name="lid" id="lid" value="">
			<input type="hidden" name="action" id="action" value="gf_edit_entrie">
		</form>

		';
	echo($html_respuesta);	
	return;
}
?>