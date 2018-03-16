<?php

function admin_page(){
	//echo(get_template_directory_child_path());
	
	
	
	?>
	<div class="wrap">
		<h1>Generar Shorcode Formulario:</h1>
	</div>
	<script type="text/javascript">
	
	//Variables globales*****************************************************
	//guarda el json devueto por con los campos del formulario
	var json_obj = '';
	//formulario seleccionado
	var idformulario = '';	
	//**********************************************************************
	
	
	
	//On ready
	jQuery(document).ready(function($){
		mostrar_checks_campos();
	});
	
	//muestra por pantalla los checks de los campos del formulario seleccionado
	function mostrar_checks_campos(){
		jQuery('#ul_paso2').html('');
		jQuery('#shortcode').html('');
		
		add_checkbox('ul_paso2','campo','-1','Todos');
		
		jQuery('#ul_paso2' ).append('<li></li>');
		jQuery('#ul_paso2' ).append('<li></li>');
		
		jQuery.each(json_obj, function(index, value) {
			
			add_checkbox('ul_paso2','campo',index,value);
			//console.log(index + ' - ' + value);
		})
		
	
	}

	//adjunta un nuevo check box a un ul
	function add_checkbox(id_container,name,value,text){
		//alert('hola');
		jQuery('#' + id_container ).append('<li><input type="checkbox" name="' + name + '" value="' + value + '"/> ' + text + '</li>');
		
		//var arr_campos = json_to_array(json_campos);
		
		
	}
	
	//de json a object (no se usa)
	function json_to_array(str_json){
		var arr = [];
		for (elem in str_json) {
		   arr.push(str_json[elem]);
		}
		//console.log(arr);
		return arr;
		
	}
	
	//Ajax que devuelve un object con los campos de un formulario concreto
	function obtener_campos(id_formulario){
		
			idformulario = id_formulario;
			
			var parametros = {
					"id_formulario" : id_formulario,
					"ajax" : 1,
					"action" : 'gf_names_fields'
					};
			jQuery.ajax({
					data:  parametros,
					type : 'POST',
					url:   '<?php echo(get_site_url());?>/wp-admin/admin-ajax.php',
					beforeSend: function () {
							//jQuery("#resultado").html("Procesando, espere por favor...");
					},
					success:  function (response) {
							//alert(response.indexOf("{")); 
							//algunos worpress meten un caracter raro antes del echo(). Lo limpiamos
							//var response_clean = response.substring(response.indexOf("{"), response.length);
							//alert(response_clean);
							json_obj = parseJSON(response);
							//alert('bien ajax!!!');
							mostrar_checks_campos();						
							//jQuery("#resultado").html(response);
							jQuery('#paso2').show();
					}
			});
	}

	function parseJSON(data) {
		return window.JSON && window.JSON.parse ? window.JSON.parse( data ) : (new Function("return " + data))(); 
	}

	
	//genera y muestra el shorcode en la cajita
	function generar_shorcode(){
		var str = '[gf_entries_form id_formulario="' + idformulario + '" campos="';
		var aux = '';
		var solocampos = '';
		jQuery('input:checked').each(function() {			
			str = str + aux + jQuery(this).val();
			solocampos = solocampos + aux + jQuery(this).val();
			aux = ',';
			//console.log(jQuery(this).val());
		});
		
		
		
		str = str + '"]'
		
		//alert(str);
		
		str = str + '<br><br>';
				
		var crypt_id = stringToHex(des('dj7zN8d7H', idformulario + ';' + solocampos, 1, 0));		
		
		jQuery('#shortcode').html(str);
		
		str = '<?php echo(get_site_url());?>/ver-entradas-inscripcion/?id=' + crypt_id;
		
		jQuery('#shortcode_link').html(str);
		
		
		
		return
	}
	</script>
	
	<style>
		body {
			font-size: 18px;
		}
		ul {
			list-style: none;
		}
		.caja {
			clear:both;
			font-size: 22px;
			padding: 10px;
			border: 1px solid #ccc;
			margin-top: 30px;
		}
		li {
			padding: 8px;
			cursor:pointer;
			border-bottom: 1px solid #ccc;
		}
		
		a {
			cursor: pointer;
		}
		
		button {
			padding: 8px;
		}
	
	</style>
</head>

<div id="paso1" style="float:left;width:50%; padding: 20px;">
	<h3>Paso1 - Elegir formulario:</h3>
	<form name="paso1" id="paso1">
		<ul>
<?php

$formularios = json_decode(gfap_names_forms(), true );

foreach($formularios as $key=>$value){
	echo('<li><a herf="javascript:void(null);" onclick="obtener_campos(' . $key . ');">' . $value . '</a></li>');
}

?>
		</ul>
	</form>
</div>

<div id="paso2" style="display:none;float:left;width:40%; padding: 20px;">
	<h3>Paso 2 - Elegir campos:</h3>
	<form name="paso2" id="paso2">
		<ul id="ul_paso2">
		</ul>
		<button class="btn btn-default" type="button" onclick="generar_shorcode();">Generar shortcode</button>
	</form>
</div>

<div id="shortcode" class="caja"></div>
<div id="shortcode_link" class="caja"></div>
<?php
}
?>