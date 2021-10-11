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
	
	ob_start();
	
	$txt_shortcode = '
		[jsoncontentimporter url='.$webapi_url.'/people/'.$person_id.' basenode=data]
		<div style="position:relative; margin:0px 0px 0px 0px; float:left; border:0px solid #F00; width:100%;">
		<div style="position:relative; margin:0px 0px 0px 0px; float:left; border:0px solid #F00; width:20%;">
			<img src="{photo_url_small}" width="300px" style="padding:10px; border:1px solid #CCC;" />
		</div>
		<div style="position:relative; margin:0px 0px 0px 50px; float:left; border:0px solid #F00; width:55%;">
		<table style="width:100%">
		<tr><td class="peo_det_name"style="font-size:30px;">{name}<br/></td></tr>
		<tr><td class="peo_det_department">{department_es}</td></tr>
		<tr><td class="peo_det_position">{position}</td></tr>
		<tr><td class="peo_det_phone"><b>Teléfono(s): </b>{subloop-array:phones:10}{0}{1:ifNotEmptyAddLeft:, }{/subloop-array:phones} &nbsp;&nbsp;<span class="peo_det_email"><b>email: </b><a href="mailto:{email}">{email}</a></span></td></tr>
		<tr><td class="peo_det_location"><b>Despacho: </b> {location_short_name}</td></tr>
		</table></div>
		<div style="position:relative; margin:0px 0px 0px 25px; padding:10px; float:left; border:0px solid #FF0; height:auto; background-color:#DDD;">
		<b><a href="?page_id='.$page_id.'&person_id='.$person_id.'&show_pub_list=true&show_pro_list=false">Mis Publicaciones ({publications})</a></b><br/><br/>
		<b><a href="?page_id='.$page_id.'&person_id='.$person_id.'&show_pub_list=false&show_pro_list=true">Mis Proyectos ({projects})</a></b><br/><br/>
		</div>
		</div>
		[/jsoncontentimporter]
		';
	echo do_shortcode($txt_shortcode);

	return ob_get_clean();
}

?>