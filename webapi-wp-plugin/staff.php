<?php

function imedea_staff_list( $atts ){
		// get attibutes and set defaults
		extract(shortcode_atts(array(
			'page_id' => '',
			'type' => $_GET["api_type"],
			'research_unit_id' => '',
			'organizational_unit_id' =>'',
			'organizational_unit_type' => '',
			'extended' => 'True'
		), $atts));

		$iapi_plugin_options = get_option('iapi_plugin_options');
		$webapi_url = $iapi_plugin_options['webapi_url'];
		$page_detail_id = $iapi_plugin_options['staff_detail_page_id'];
		
		ob_start();

		list_staff($type, $webapi_url, $page_detail_id, $research_unit_id, $organizational_unit_id, $organizational_unit_type, $extended);

		return ob_get_clean();

}

function list_staff($type, $webapi_url, $page_detail_id, $ru_id, $ou_id, $ou_type, $extended){

	if ( $type == 'permanent' ) {
		//print $webapi_url."/people?type=permanent&department_id=".$ou_id."&department_type=".$ou_type."&research_unit_id=".$ru_id;
		$staff = getJSONData($webapi_url."/people?type=permanent&department_id=".$ou_id."&department_type=".$ou_type."&research_unit_id=".$ru_id)['data'];
		$type_class = "permanent_staff_list_item";
	} else {
		$staff = getJSONData($webapi_url."/people?type=temporal&department_id=".$ou_id."&department_type=".$ou_type."&research_unit_id=".$ru_id)['data'];
		$type_class = "hired_staff_list_item";
	}

	if ($extended=='True') {
		echo "<ul class='staff_list'>";
		foreach( $staff as $person ){
			echo "<li class='".$type_class."'>
			<div class='staff_list_photo_container'>
				<a href='?page_id=".$page_detail_id."&person_id=".$person['id']."&show_pub_list=false&show_pro_list=false'>
					<img src=".$person['photo_url_small']." class='staff_list_photo'>
				</a>
			</div>
			<div class='staff_list_name'><a href='?page_id=".$page_detail_id."&person_id=".$person['id']."&show_pub_list=false&show_pro_list=false'>".nameSurname($person['name'])."</a></div>";
			if (sizeof($person['phones']) > 0) {
				echo "<div class='staff_list_phones'><span class='dashicons dashicons-phone'></span> ";
				foreach( $person['phones'] as $p => $phone ){
					echo $phone;
					if ( isset($phones[$p + 1]) ) echo ", ";
				}
				echo "</div>"; 
			}
			echo "<div class='staff_list_email'><a href='mailto:".$person['email']."'>".$person['email']."</a></div>
			<div class='staff_list_ru'>".$person['organizational_unit_name_es']."</div>
			<div class='staff_list_room'>".$person['room']."</div>
			</li>";
		}
		echo "</ul>";
	} else {
		echo "<ul class='staff_list_simple'>";
		foreach( $staff as $person ){
			echo "<li class='".$type_class."_simple'>
			<div class='staff_list_name_simple'>
				<a href='?page_id=".$page_detail_id."&person_id=".$person['id']."&show_pub_list=false&show_pro_list=false'>".$person['name']."</a>
			</div>
			</li>";
		echo "</ul>";
		}
	}
}

/*
 * Display detail of a person identified by id.
 * Returns active staff only.
 */

function imedea_staff_detail( $atts ){
	// get attibutes and set defaults
	extract(shortcode_atts(array(
   		'page_id' => $_GET["page_id"],
   		'person_id' => $_GET["person_id"]
	), $atts));
	
	$iapi_plugin_options = get_option('iapi_plugin_options');
	$webapi_url = $iapi_plugin_options['webapi_url'];
	$pubs_page_id = $iapi_plugin_options['pub_list_page_id'];
	$prjs_page_id = $iapi_plugin_options['prj_list_page_id'];
	$news_page_id = $iapi_plugin_options['news_list_page_id'];
	
	ob_start();
	
	$pd = getJSONData($webapi_url."/people/".$person_id)['data'][0];
	echo "<div class='staff_details_container'>";
	echo "<div class='staff_details_photo_container'>";
	echo "<img src='".$pd['photo_url_small']."' class='staff_details_photo' />";
	echo "</div>";
	echo "<div class='staff_details_info_container'>";
	echo "  <div class='staff_details_name'>".$pd['name']."</div>";	
	echo "  <div class='staff_details_organizational_unit'>".$pd['department_es']."</div>";
	echo "  <div class='staff_details_research_unit'>".$pd['research_unit_name_es']."</div>";
	echo "  <div class='staff_details_position'>".$pd['position']."</div>";
	if (sizeof($pd['phones']) > 0) {
				echo "<div class='staff_details_phones'><i class='fas fa-phone'></i> ";
				foreach( $pd['phones'] as $p => $phone ){
					echo $phone;
					if ( isset($phones[$p + 1]) ) echo ", ";
				}
				echo "</div>"; 
			}
	echo "  <div class='staff_details_email'><i class='fas fa-envelope'></i> ".$pd['email']."</div>";
	if (!empty(trim($pd['location']))){
		echo "<div class='staff_details_location'><i class='fas fa-door-open'></i> ".$pd['location'].
			" (Sala: ".$pd['location_short_name'].")</div>";	
	}
	if (!empty(trim($pd['web_page']))){
		echo "<div class='staff_details_location'><i class='fas fa-link'></i> <a href='".$pd['web_page']."' title='Página web personal'>".$pd['web_page']."</a></div>";	
	}
	echo "</div>";
	echo "<div class='staff_details_related_items_container'>";
	if ($pd['publications']>0){
		echo "    <div class='staff_details_related_elements staff_details_publications'>
		<a href='?page_id=".$pubs_page_id."&person_id=".$person_id."'>
		<i class='fas fa-book'></i> Publicaciones</a></div>";
	}
	if ($pd['projects']>0){
		echo "    <div class='staff_details_related_elements staff_details_projects'>
		<a href='?page_id=".$prjs_page_id."&person_id=".$person_id."'>
		<i class='fas fa-project-diagram'></i> Proyectos</a></div>";
	}
	if($pd['news_posts']){
		echo "    <div class='staff_details_related_elements staff_details_news'>
		<a href='?page_id=".$news_page_id."&person_id=".$person_id."'>
		<i class='fas fa-quote-right'></i> Noticias</a></div>";	
	}
	echo "</div>";
	if(!empty(trim($pd['about_es']))){
		echo "<div class='staff_details_bio_container'>".$pd['about_es']."</div>" ;
	}
	echo "</div>";
	
	return ob_get_clean();
}

/**
 * Staff specific helper functions
 */

function getStaffName($person_id){
	$iapi_plugin_options = get_option('iapi_plugin_options');
	$webapi_url = $iapi_plugin_options['webapi_url'];	
	$pd = getJSONData($webapi_url."/people/".$person_id)['data'][0];
	
	return $pd['name'];
}
?>