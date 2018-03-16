<?php


add_shortcode('gf-edit-entries', 'gf_edit_entries_shortcode');

function gf_edit_entries_shortcode() {

	
	if($_POST['screen_mode'] == ''){

	?>

	  <form method="post" action="?page=gf_entries&view=entry&id=2&lid=361&orderby=ASC&filter&paged=1">
	    <input type="hidden" name="new_note" value="">
	    <input type="hidden" name="bulk_action" value="">
	    <input type="hidden" name="note" value="">
	    <input type="hidden" name="print_notes" value="print_notes">
	    
	    <input type="hidden" name="screen_mode" value="edit">

	    <input type="hidden" name="entry_id" value="361">
	    <input type="hidden" name="action" value="">
	    <input type="hidden" name="id" value="2">
	    <input type="hidden" name="lid" value="361">

	    <input type="submit" value="Enviar">


	  </form>


	<?php
	}else{

		require_once ( get_home_path_() ."wp-content/plugins/frontend-gf/includes/gravity/entry_detail.php");
		require_once( GFCommon::get_base_path() . '/includes/locking/locking.php' );
		GFEntryDetail::lead_detail_page();
	}
	
}

//Esta funcion esta tomada del core de WP
function get_current_screen_() {
    global $current_screen;
 
    if ( ! isset( $current_screen ) )
        return null;
 
    return $current_screen;
}