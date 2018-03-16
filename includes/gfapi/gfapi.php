<?php

// funciones api wordpress 

function gfapi_names_fields($id_formulario){
	
	//$str = 'a:2:{i:0;a:5:{s:18:"Nombre y Apellidos";s:8:"Prueba 1";s:16:"Fecha Nacimiento";s:10:"20/12/1980";s:4:"Edad";s:2:"34";s:7:"NIF/NIE";s:8:"02911885";s:10:"Sexo (H/M)";s:1:"H";}i:1;a:5:{s:18:"Nombre y Apellidos";s:8:"Prueba 2";s:16:"Fecha Nacimiento";s:10:"20/12/1980";s:4:"Edad";s:2:"34";s:7:"NIF/NIE";s:8:"02911887";s:10:"Sexo (H/M)";s:1:"M";}}';
	
	//echo('aasfas');
	//print_r(unserialize($str));
	//die();
	
	//si es de un post por ejemplo ajax, el id vien por post
	if($_POST['ajax'] == 1){
		$id_formulario = $_POST['id_formulario'];
	}
	
	
	$form = GFAPI::get_form($id_formulario);
	
	//echo('<textarea>' . json_encode($form) . '</textarea>');
	//var_dump($form); 
	//die();

	//calalulamos el array asociativo id => nombre_campo
	$nombre_campos = Array();

	//Este campo (date_created) no viene definidoen los datos del formulario, por eso lo ponemos a mano
	$nombre_campos['date_created'] = 'FECHA ALTA';
	$nombre_campos['payment_status'] = 'PAGO ESTATUS';
	$nombre_campos['payment_date'] = 'PAGO FECHA';
	$nombre_campos['transaction_id'] = 'PAGO ID';
	$nombre_campos['payment_amount'] = 'PAGO CANTIDAD';
	$nombre_campos['payment_method'] = 'PAGO METODO';
	$nombre_campos['id'] = 'ID INSCRIPCION';
	
	foreach($form['fields'] as $obj_campo){
		//quitamos de la lista los campos html
		if($obj_campo->type != 'html' && $obj_campo->type != 'page'){
			if($obj_campo->adminLabel != ''){
				//si esta definido admin label lo ponemos
				$nombre_campos[$obj_campo->id] = substr($obj_campo->adminLabel,0,50);
			}else{
				$nombre_campos[$obj_campo->id] = substr($obj_campo->label,0,50);
			}
		}
	}
	//print_r($nombre_campos);
	//die();
	if($_POST['ajax'] == 1){
		//header('Content-Type: application/json');
		//echo(substr(trim(json_encode($nombre_campos)), 1, strlen($body)));
		//$pos = strpos($key, '{');
		//die('Posicion de la llave: ' . $pos);
		echo(json_encode($nombre_campos)) ;
	}else{
		return $nombre_campos;
	}
	wp_die();
}


function gfap_entries_form_ajax(){

	//die('id formulario: ' . $id_formulario);

	//echo(json_encode(GFAPI::get_entries($id_formulario)));

	//die();
	$entrada_data   = Array();
	$contador = 0;

	$id_formulario = $_GET['id_formulario'];

	$search_criteria['status'] = 'active';
	$sorting = array( 'key' => 'date_created', 'direction' => 'ASC', 'is_numeric' => true );
	$paging = array( 'offset' => 0, 'page_size' => 10000 );
	$total_count = 10;

	$entradas = GFAPI::get_entries($id_formulario,$search_criteria,$sorting, $paging , $total_count);

	
	//print_r($entradas);
	//print_r(json_encode($entradas));
	//die();
	
	//recorre entradas
		
	foreach($entradas as $entrada){
		$entrada_data = Array();
		//recorre campos
		$contador_campos_compuestos = 0;
		foreach ($entrada as $key => $val) {
						
			$key_value = $key;
			
			//cuando son campos compuestos se nombrarn por ejemplo 33.1, 33.2 ... de ahi el buscar el punto en el nombre del campo
			$pos = strpos($key, '.');			
						
			$indice_subcampo = 0;
			
			if ($pos !== false) {//es campo compuesto

				$key_value = explode('.',$key)[0] + 0;
				$indice_subcampo = explode('.',$key)[1] + 0;				
				//$key_value = substr($key, 0, $pos) + 0;
				$contador_campos_compuestos += 1;
			}
			
			
			//comprobamos si el value del campo es un json de valorres
			$pos = strpos($val, ':{s:');
			$separador = ' | ';
			
			
			//el valor del campo es tipo objeto serializado como por ejemplo las Listas que pueden tener numerosas filas
			$str_unserialize = '<div style="height:140px; overflow-y: scroll;">';
			
			if ($pos !== false) {
				//deserializamos y lo ponemos en un string
				$elementos_lista = unserialize($val);
				//recorremos el array
				foreach ($elementos_lista as $elemento) {
					
					$aux2 = '';
					//recorremos el elemento
					foreach ($elemento as $elemento_key => $elemento_val) {						
						$str_unserialize = $str_unserialize . $aux2 . $elemento_key . ' : ' . $elemento_val ;
						$aux2 = ', ';						
					}
					$str_unserialize = $str_unserialize . $separador;
							
					//Array ( [Nombre y Apellidos] => Prueba 1 [Fecha Nacimiento] => 20/12/1980 [Edad] => 34 [NIF/NIE] => 02911885 [Sexo (H/M)] => H
				}
				
				$val = $str_unserialize . '</div>';
				
			}
			
			
			
			//echo('key: ' . $key);
			//echo('<br>');
			//echo(' key_value: ' . $key_value);
			//echo('<br>');
			//echo(' value: ' . $val);
			//echo('<br>');

			$aux = ' | ';
			if(is_int($key_value)){
				//$entrada_data[$campos[$key]] = $val;
				//si el indice ya tiene valores se concatena la barrita divisora
				if($entrada_data[$key_value] != '' && $val != ''){
					$entrada_data[$key_value] .= $aux . $val;
				}else{
					$entrada_data[$key_value] .= $val;
				}
			}
			if($key == 'id'){
				$entrada_data[$key] = $val;	
			}
			if($key == 'date_created'){
				$entrada_data[$key] = $val;	
			}
			if($key == 'payment_status'){
				$entrada_data[$key] = $val;	
			}
			if($key == 'payment_date'){
				$entrada_data[$key] = $val;	
			}
			if($key == 'transaction_id'){
				$entrada_data[$key] = $val;	
			}
			if($key == 'payment_amount'){
				$entrada_data[$key] = $val;	
			}
			if($key == 'payment_method'){
				$entrada_data[$key] = $val;	
			}
		}
		
		$entradas_data[$contador] = $entrada_data;
		$contador += 1;
	}



	header('Content-Type: application/json');
	echo (json_encode(array("customData" => $entradas_data)));
	//die();
	wp_die();
}

function gfap_names_forms(){

	$forms = GFAPI::get_forms();
	//var_dump($formularios);
	//die();
	
	$formularios_nombres = Array();
	
	
	$forms = GFAPI::get_forms();
	
    foreach ($forms as $form) {
        $form_id = $form['id'];
        $form = GFAPI::get_form( $form_id );
        $form_title = $form['title'];
        $formularios_nombres[$form_id] = $form_title;
    }

	return json_encode($formularios_nombres);

	wp_die();
}

function gfap_name_form($id_formulario){

	$form = GFAPI::get_form($id_formulario);
	//var_dump($formularios);
	//die();
	
	return $form['title'];
}
?>