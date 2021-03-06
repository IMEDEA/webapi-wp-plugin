<?php
function imedea_project_list( $atts ){
	// get attibutes and set defaults
	extract(shortcode_atts(array(
		'page_id' => $_GET["page_id"],
		'type' => $_GET["type"],
		'research_unit_id' => $_GET["research_unit_id"],
		'year' => $_GET["year"],
		'search' => $_GET["search"],
		'title'=> $_GET["title"],
		'person_id' => $_GET["person_id"],
		'showall' => $_GET["showall"],
	), $atts));
	
	$iapi_plugin_options = get_option('iapi_plugin_options');
	$webapi_url = $iapi_plugin_options['webapi_url'];
	//$page_detail_id = $iapi_plugin_options['prj_detail_page_id'];
	$page_length = $iapi_plugin_options['webapi_pagination_length'];
	
	ob_start();
	
	/* search pannel */
	project_filter_pannel($webapi_url, $type, $research_unit_id, $year, $title);
	
	/* show applied filters */
	show_project_filters($person_id);
	
	
	$count = get_projects($webapi_url, $page_length, $research_unit_id, $year, $type, $title, $person_id, $showall);
	
	/* Pagination */
	pagination ($page_length, $type, $count, $research_unit_id, $year, $title, $person_id, null);
	
	return ob_get_clean();
	
}

function get_projects($webapi_url, $page_length, $research_unit_id, $year, $type, $title, $person_id, $showall){
	$count = getJSONData($webapi_url."/projects".
		"?research_unit_id=".$research_unit_id.
		"&type=".$type.			
		"&name=".$title.
		"&person_id=".$person_id.
		"&showall=".$showall)['count'];

	
	
	echo "<ul class='project_list'>";
	$projects = getJSONData($webapi_url."/projects".
		"?limit=".$page_length."&offset=".$_GET["api_offset"].
		"&research_unit_id=".$research_unit_id.
		"&type=".$type.		
		"&name=".$title.
		"&person_id=".$person_id.
		"&showall=".$showall)['data'];
	foreach ($projects as $pr){
		$code = clearCTA($pr['code']);
		$acronym = clearCTA($pr['acronym']);			
	
		echo "<li class='project_list_item'>";
		//if ( !empty($pr['related_logo']) ){
			echo "<div class='project_list_image_container'><img src='".$pr['related_logo']."' class='project_list_image'></div>";	
		//}
		echo "<div class='project_list_title' onclick=\"toggle_div_by_id('project_list_expanded_".$pr['id']."');\">".$pr['name']."  </div>";
		echo "<div class='project_list_expanded' id='project_list_expanded_".$pr['id']."' style='display:none;'>";		
		
		echo "<div class='project_list_code'>".$code."</div>";
		echo "<div class='project_list_acronym'>".$acronym."</div>";
		echo "<div class='project_list_duration'><span class='dashicons dashicons-clock'></span> Duraci??n: ".$pr['duration']."</div>";
		echo "<div class='project_list_description'>".$pr['description']."</div>";		
		echo "<div class='project_list_related_groups' title='Grupo(s) de investigaci??n'><span class='dashicons dashicons-image-filter'></span> ";
		$ru_count = sizeof($pr['related_research_units']);
		for ($i = 0; $i < $ru_count; $i++) {
			$ru = $pr['related_research_units'][$i];
			echo "<span class='project_list_related_ru'>".$ru['name_es']."</span>";
			if ($i < $ru_count -1 ){
				echo ", ";
			}
		}
		echo "</div>";
		echo "<div class='project_list_related_people' title='Participantes'><span class='dashicons dashicons-groups'></span> Participantes: ";
		$member_count = sizeof($pr['related_people']);
		for ($i=0; $i < $member_count; $i++) {
			echo "<span class='project_list_related_person'>".nameSurname($pr['related_people'][$i]['name'])."</span>";
			if ($i < $member_count -1 ){
				echo ", ";
			}
		}		
		echo "<div class='project_list_funder' title='Financiador'><span class='dashicons dashicons-money-alt'></span> ";
		echo $pr['type']. " financiado por: ".$pr['funder']."</div>";
		if ( !empty($pr['url']) ) {
			$link = $pr['url'];
			echo "<div class='project_list_link'><span class='dashicons dashicons-admin-links'></span><a href='".$link."'> ".$link."</a></div>";
		}
		echo "<div class='project_list_related_publications' title='Publicaciones'><span class='dashicons dashicons-media-document'></span> <a href='?page_id=435&project_id=".$pr['id']."'>Publicaciones relacionadas</a></div>";
		echo "</div>";
		echo "</div>";
		echo "</li>";
	}
	echo "</ul>";
	
	return $count;
}


function project_filter_pannel($webapi_url, $type, $research_unit_id, $year, $title){
	echo "<div id='project_filter_pannel' class='project_filter_pannel'>";
	echo "<form action='' method='GET' name='project_filter_form'>";
	echo "<input type='hidden' name='page_id' value=".$_GET['page_id'].">";
	echo "<input type='hidden' name='type' value=".$type.">";
	//echo comboProject();
	echo "<input type='text' name='title' value='".$title."' id='f_pr_title'>";
	echo "<button id='project_filter_btn'>";
	echo "<span class='dashicons dashicons-search'></span>Buscar";
	echo "</button id='project_filter_btn'>";
	//echo "<input type='submit' name='project_filter_btn' id='project_filter_btn' value='Buscar'>";
	echo "</form>";
	echo "</div>";
};

function comboProject($showall=false, $selected='', $style_id='f_project_combo') {
	$iapi_plugin_options = get_option('iapi_plugin_options');
	$webapi_url = $iapi_plugin_options['webapi_url'];
	
	$projects = getJSONData($webapi_url."/projects")['data'];
	
	echo "<select name='f_project' id='".$style_id."'>";
	foreach ($projects as $pr){
		echo "<option value='".$pr['id']."' ".selectOptionSelected($pr['id'], $selected).">".$pr['acronym']."</option>";
	}
	echo "</select>";	
}

/*
 * Display filters applied to search
 */

function show_project_filters($person_id){
	echo "<div class='project_filter_display'>";	
	if ($person_id) {
		echo "<span class='project_search_filter'>Proyectos de ".getStaffName($person_id)."</span>";
	}
    echo "</div>";
}

?>