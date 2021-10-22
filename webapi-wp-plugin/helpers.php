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
	if ( $opt == $val) {
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
		$str .= "<option value='".$opt[$option_key]."' ".selectOptionSelected($opt[$option_key], $option_value).">".clearCTA($opt[$option_text])."</option>";
	}
	$str .= "</select>";
	return $str;
}

/*
 * Return combo field html for Year selection
 */

function yearCombo($name, $f_type, $f_class, $start_year, $end_year, $value = ''){
	if ( $end_year == null || empty($end_year) ) { $end_year= date("Y"); }
	$html = "<select name='".$name."' id='".$f_type."' class='".$f_class."'>";
	$html .= "<option value='' ".selectOptionSelected('', $value).">&nbsp;</option>";
	for ($y = $end_year; $y >= $start_year; $y-- ){
		$html .= "<option value='".$y."' ".selectOptionSelected($y, $value).">".$y."</option>";
	}
	$html .= "</select>";
	return $html;
}


/*
 * Truncate author list length
 * $max: max number of authors (0 do not shorts the list)
 * $separator: can be ; or ,
 * $swap: displays I. Surname instead of Surname, I. or vice versa.
 */
function fancyAuthors($authors, $max, $separator = ';', $swap = true) {
	$author_list = explode($separator, $authors);
	$number_of_authors = count($author_list);
	$newauthors = "";
	for ( $i = 0; $i < min($max, $number_of_authors); $i++){
		$cn = $sn = "";
		if (';' == $separator){
			$name_parts = explode(',', $author_list[$i]);
			if (count($name_parts) > 1) {
				$cn = $name_parts[1];
			}
			$sn = $name_parts[0];
		} else {
			$cn = $author_list[$i]; //do nothing
		}
		$newauthors .= trim($cn." ".$sn);
		if ($i < min($max, $number_of_authors)-1) $newauthors .= ", ";
	}
	if ($max < $number_of_authors) { 
		$newauthors .= " et al."; 
	} else { 
		$newauthors .= "."; 
	}
	return $newauthors;
}

/*
 * Truncate string by length
 */
function truncate($string, $len, $hard=false) 
{        
     if(!$len || $len>strlen($string))
          return $string;
     $string = substr($string,0,$len);
     return $hard?$string:(substr($string,0,strrpos($string,' ')).' ...');
}

/*
 * Sanitize DOI
 */
function sanitizeDOI($doi) {
	$newdoi = str_replace("https", "http", $doi);
	$newdoi = str_replace("http://dx.doi.org/", "", $newdoi);
	return str_replace("http://doi.org/", "", $newdoi);
}

/*
 * Change string "Surname, Name" to "Name Surname"
 */

function nameSurname($surname_name) {
	$name_parts = explode(',', $surname_name);
	if ( sizeof($name_parts) >= 2 ) {
		$new_name = trim($name_parts[1]) . " " . trim($name_parts[0]) ;
	} else if ( sizeof($name_parts) == 1 ){
		$new_name = $name_parts[0];
	} else {
		$new_name = '';
	}
	return $new_name;
}

/**
 * Clear CTA references at the end os string
 */
function clearCTA($text){
	if (stripos($text, '(CTA') > 0){
		$text = substr($text, 0, stripos($text, '(CTA'));
	} else if (stripos($text, ' CTA') > 0){
		$text = substr($text, 0, stripos($text, ' CTA'));
	} else if (stripos($text, ' (') > 0){
		$text = substr($text, 0, stripos($text, ' ('));
	}
	return $text;
}


?>