<?php
/**
 * Theses listing
 */


function imedea_theses_list( $atts ){
	// get attibutes and set defaults
	extract(shortcode_atts(array(
		'page_id' => $_GET["page_id"],
		'type_id' => $_GET["type_id"],
		'research_unit_id' => $_GET["research_unit_id"],
		'year' => $_GET["year"],
		'title' => $_GET["title"]
	), $atts));
	
	$iapi_plugin_options = get_option('iapi_plugin_options');
	$webapi_url = $iapi_plugin_options['webapi_url'];
	$page_length = $iapi_plugin_options['webapi_pagination_length'];
	
	ob_start();
	

	/* show applied filters */
	//show_thesis_filters($research_unit_id, $year, $title, $journal, $person_id);	

	/* search pannel */
	theses_filter_pannel($webapi_url, $type_id, $research_unit_id, $year, $title);
	
  	$count = get_theses($webapi_url, $page_length, $research_unit_id, $type_id, $year, $title);
	
	/* Pagination */
	pagination ($page_length, $type_id, $count, $research_unit_id, $year, $title, $person_id, $project_id);
	
	return ob_get_clean();
	
}

function get_theses($webapi_url, $page_length, $research_unit_id, $type_id, $year, $title) {

  $count = getJSONData($webapi_url."/theses".
  "?research_unit_id=".$research_unit_id.
  "&year=".$year.
  "&title=".$title.
  "&type_id=".$type_id)['count'];


  echo "<ul class='theses_list'>";
  $theses = getJSONData($webapi_url."/theses".
    "?limit=".$page_length."&offset=".$_GET["api_offset"].
    "&research_unit_id=".$research_unit_id.
    "&year=".$year.
    "&title=".$title.
    "&type_id=".$type_id)['data'];
	
  foreach ($theses as $th){	
	$thdate = date( 'd/m/Y', strtotime($th['date']) );
    echo "<li class='theses_list_item'>
      <div class='theses_list_type'>".$th['type']."</div>  
      <div class='theses_list_date'>".$thdate."</div>
      <div class='theses_list_title'>".$th['title']."</div>
      <div class='theses_list_author'><i class='fas fa-user-tie'></i> por ".nameSurname($th['author'])."</div>
      <div class='theses_list_codirectors'><i class='fas fa-chalkboard-teacher'></i> dirigida por: ".fancyAuthors(rtrim($th['codirectors'], ';'), 5, ';')."</div>
	  <div class='theses_list_university'><i class='fas fa-university'></i> ".$th['university']."</div>
      </li>";
  }
  echo "</ul>";

  return $count;

}

function theses_filter_pannel($webapi_url, $type_id, $research_unit_id, $year, $title){
	echo "<div id='theses_filter_pannel' class='theses_filter_pannel'>";
	echo "<form action='' method='GET' name='theses_filter_form'>";
	echo "<input type='hidden' name='page_id' value=".$_GET['page_id'].">";
	//echo "<input type='hidden' name='type' value=".$type.">";
	echo JSONCombo('type_id', 'f_thesis_type', 'f_combo', $webapi_url . '/thesis_types', 'data', 'id', $type_id, 'name');
	echo "<input type='text' name='title' value='".$title."' id='f_th_title'>";
	echo "<button id='theses_filter_btn'>";
	echo "<span class='dashicons dashicons-search'></span>Buscar";
	echo "</button>";
	echo "</form>";
	echo "</div>";
}

?>