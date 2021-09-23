<?php

/*
 * Put json data from url address to array
 */
 
function getJSONData($url){
	try {
		$json = file_get_contents($url);
		$data = json_decode($json, true);
	} catch (Exception $e) {
		$data = array();
	} finally {
		return $data;
	}
}

/*
 * Return "selected" string in case of match
 * Used for combo fields
 */
 
function selectOptionSelected($opt, $val) {
	if ( $opt === $val) {
		return "selected";
	} else {
		return "";
	}
}

/*
 * Return combo field html from json data
 */
 
function JSONCombo($combo_name, $combo_id, $combo_class, $json_url, $json_base, $option_key, $option_value, $option_text){
	$str = "<select name='".$combo_name."' id='".$combo_id."' class='".$combo_class."'>";
	$str .= "<option value='' ".selectOptionSelected('', $option_value).">&nbsp;</option>";
	foreach ( getJSONData($json_url)[$json_base] as $opt) {
		$str .= "<option value='".$opt[$option_key]."' ".selectOptionSelected($opt[$option_key], $option_value).">".$opt[$option_text]."</option>";
	}
	$str .= "</select>";
	return $str;
}

?>
