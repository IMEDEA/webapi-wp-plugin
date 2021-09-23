<?php

function imedea_publication_list( $atts ){
	// get attibutes and set defaults
	extract(shortcode_atts(array(
		'page_id' => $_GET["page_id"],
		'type' => $_GET["type"],
		'research_unit_id' => $_GET["research_unit_id"],
		'year' => $_GET["year"],
		'title' => $_GET["title"],
		'journal' => $_GET["journal"],
		'person_id' => $_GET["person_id"],
	), $atts));
	
	$iapi_plugin_options = get_option('iapi_plugin_options');
	$webapi_url = $iapi_plugin_options['webapi_url'];
	$page_detail_id = $ipub_plugin_options['pub_detail_page_id'];
	
	ob_start();
	
	/* type selector */
	type_selector($type, $research_unit_id, $year, $title, $person_id);
	
	/* search pannel */
	filter_pannel($webapi_url, $type, $research_unit_id, $year, $title);
	
	/* show applied filters */
	//show_filters($research_unit_id, $year, $title, $journal, $person_id);
	
	
	if ($type == 'books') {
		get_books($webapi_url, $page_detail_id, $research_unit_id, $year, $title, $person_id);
	} else if ($type == 'book_chapters') {
		get_chapters($webapi_url, $page_detail_id, $research_unit_id, $year, $title, $person_id);
	} else {
		get_articles($webapi_url, $page_detail_id, $research_unit_id, $year, $title, $journal, $person_id);
	}

	return ob_get_clean();
	
}


function get_articles($webapi_url, $page_detail_id, $research_unit_id, $year, $title, $journal, $person_id){

	$count = getJSONData($webapi_url."/articles".
		"?research_unit_id=".$research_unit_id.
		"&year=".$year.
		"&title=".$title.
		"&journal=".$journal.
		"&person_id=".$person_id)['count'];

	pagination ('articles', $count, $research_unit_id, $year, $title, $person_id);
	
	echo "<ul class='article_list'>";
	$articles = getJSONData($webapi_url."/articles".
		"?limit=20&offset=".$_GET["api_offset"].
		"&research_unit_id=".$research_unit_id.
		"&year=".$year.
		"&title=".$title.
		"&journal=".$journal.
		"&person_id=".$person_id)['data'];
	foreach ($articles as $ar){
		if( $doi=$ar['doi'] ) { $link = "https://dx.doi.org/".$doi; }
		
		echo "<li class='article_list_item'>
		<div class='article_list_title'><a href='".$link."'>".$ar['title']."</a></div>
		<span class='article_list_authors'>".$ar['authors']."</span>
		<span class='article_list_journal'>".$ar['journal']."</span>
		<span class='article_list_volume'>".$ar['volume']."</span>
		<span class='article_list_pages'>".$ar['start_page']."-".$ar['end_page']."</span>
		<span class='article_list_year'>".$ar['year']."</span></li>";
	}
	echo "</ul>";
}

function get_books($webapi_url, $page_detail_id, $research_unit_id, $year, $title, $person_id){
	
	$count = getJSONData($webapi_url."/books".
		"?research_unit_id=".$research_unit_id.
		"&year=".$year.
		"&title=".$title.
		"&person_id=".$person_id)['count'];

	pagination ('books', $count,$research_unit_id, $year, $title, $person_id);
	
	echo "<ul class='book_list'>";
	echo do_shortcode("[jsoncontentimporter url=".$webapi_url."/books".
		"?limit=20&offset=".$_GET["api_offset"].
		"&research_unit_id=".$research_unit_id.
		"&year=".$year.
		"&title=".$title.
		"&person_id=".$person_id.
		" basenode=data]<li class='book_list_item'>
		<div class='book_list_title'><a href='?page_id=".$page_detail_id."&publication_id={id}&type=books'>{title}</a></div>
		<span class='book_list_authors'>{authors}</span>
		<span class='book_list_publisher'>{publisher}</span>
		<span class='book_list_year'>{year}</span></li> [/jsoncontentimporter]");
	echo "</ul>";
}

function get_chapters($webapi_url, $page_detail_id, $research_unit_id, $year, $title, $person_id){
	
	$count = getJSONData($webapi_url."/book_chapters".
		"?research_unit_id=".$research_unit_id.
		"&year=".$year.
		"&title=".$title.
		"&person_id=".$person_id)['count'];

	pagination ('book_chapters', $count, $research_unit_id, $year, $title, $person_id);
	
	echo "<ul class='chapter_list'>";
	echo do_shortcode("[jsoncontentimporter url=".$webapi_url."/book_chapters".
		"?limit=20&offset=".$_GET["api_offset"].
		"&research_unit_id=".$research_unit_id.
		"&year=".$year.
		"&title=".$title.
		"&person_id=".$person_id.
		" basenode=data]<li class='chapter_list_item'>
		<div class='chapter_list_title'><a href='?page_id=".$page_detail_id."&publication_id={id}&type=book_chapters'>{title}</a></div>
		<span class='chapter_list_book_title'>{book_title}</span>
		<span class='chapter_list_authors'>{authors}</span>
		<span class='chapter_list_publisher'>{publisher}</span>
		<span class='chapter_list_year'>{year}</span></li> [/jsoncontentimporter]");
	echo "</ul>";
}


/*
 * Display selector for publication type
 */

function type_selector($type, $research_unit_id, $year, $title, $person_id){
	if ( $type === NULL ) { $type = 'articles'; }
	if ( get_bloginfo("language") == 'en' ) {
		$ar_txt = "Articles";
		$bk_txt = "Books";
		$ch_txt = "Book chapters";
	} else if ( get_bloginfo("language") == 'ca' ) {
		$ar_txt = "Articles";
		$bk_txt = "Llibres";
		$ch_txt = "Capítols de llibres";
	} else {
		$ar_txt = "Artículos";
		$bk_txt = "Libros";
		$ch_txt = "Capítulos de libro";
	}
	echo "<div class='publication_type_selector'>";
	echo "<a href='?page_id=".$_GET['page_id']."&type=articles&year=".$year."&title=".$title."&research_unit_id=".$research_unit_id."&person_id=".$person_id."' ".selector_selected_tag('articles', $type).">".$ar_txt."</a>";
	echo "<a href='?page_id=".$_GET['page_id']."&type=books&year=".$year."&title=".$title."&research_unit_id=".$research_unit_id."&person_id=".$person_id."' ".selector_selected_tag('books', $type).">".$bk_txt."</a>";
	echo "<a href='?page_id=".$_GET['page_id']."&type=book_chapters&year=".$year."&title=".$title."&research_unit_id=".$research_unit_id."&person_id=".$person_id."' ".selector_selected_tag('book_chapters', $type).">".$ch_txt."</a>";
    echo "</div>";
}

function selector_selected_tag ($type, $value) {
	if( $type == $value ) { return "class='publication_type_selector_selected'"; }
}


/*
 * Display filters applied to search
 */

function show_filters($research_unit_id, $year, $title, $journal, $person_id){
	echo "<div class='publication_filter_display'>";	
	/*if ($journal) { echo "<span class='search_filter'>La revista contiene = ".$title."</span>";}*/
	if ($research_unit_id) { echo "<span class='search_filter'>Del grupo de investigación  = ".$research_unit_id."</span>";}
	if ($person_id) { echo "<span class='search_filter'>Autor  = ".$person_id."</span>";}
    if ($year) { echo "<span class='search_filter'>Año = ".$year."</span>";}
	if ($title) { echo "<span class='search_filter'>El título contiene = ".$title."</span>";}
    echo "</div>";
}


/*
 * Display pagination bar
 */

function pagination ($type, $count, $research_unit_id, $year, $title, $person_id){
	
	if ( isset($_GET['limit']) )      { $limit = $_GET['limit']; } else { $limit = 20; }
	if ( isset($_GET['api_offset']) ) { $offset = $_GET['api_offset']; } else { $offset = 0; }
	$originalparams="";
	
	if( $count >= $limit ){
		echo "<div class='pagination_container'>";
		echo "<a href='".$_SERVER['PHP_SELF']."?page_id=".$_GET['page_id']."&type=$type&limit=$limit&offset=0&year=".$year."&title=".$title."&research_unit_id=".$research_unit_id."&person_id=".$person_id."'>&lt;&lt;</a>";
		$o_inicio = $offset - ( 5 * $limit );
		while ( $o_inicio < 0 ) $o_inicio += $limit;
		if ( $o_inicio + ( 10 * $limit ) <= $count ) {
			$o_final = $o_inicio + ( 10 * $limit );
		} else {
			$o_final = $count;
		}
		for ( $o = $o_inicio; $o <= $o_final; $o += $limit ){
			$p = ceil( $o / $limit ) + 1;
			if ( $o == $offset ) {
				echo "<span  class='selected_page_number'>".$p."</span>";
			} else {
				echo "<a href='".$_SERVER['PHP_SELF']."?page_id=".$_GET['page_id']."&type=$type&limit=$limit&api_offset=".$o."&year=".$year."&title=".$title."&research_unit_id=".$research_unit_id."&person_id=".$person_id."'>".$p."</a> ";
			}
		}
		echo "<a href='".$_SERVER['PHP_SELF']."?page_id=".$_GET['page_id']."&type=$type&limit=10&api_offset=".(floor($count/$limit)*$limit)."&year=".$year."&title=".$title."&research_unit_id=".$research_unit_id."&person_id=".$person_id."'>&gt;&gt;</a>";
		
		echo "<span class='total_results_count'>".$count."</span>";
		echo "</div>";
	}
	if( $count == 0 ){
		echo "<span class='no_results'>No se encuentran resultados con los criterios de búsqueda seleccionados</span>";
	}
}


/*
 * Display Filter pannel
 */

function filter_pannel($webapi_url, $type, $research_unit_id, $year, $title){
	
	echo "<button onclick='toggle_ipub_filter_pannel()' id='toggle_filter'><span class='dashicons dashicons-search'></span>Buscar</button>";
	echo "<div id='publications_filter_pannel' class='publications_filter_pannel' style='display:none;'>";
	echo "<form action='' method='GET' name='ipub_filter_form'>";
	echo "<input type='hidden' name='page_id' value=".$_GET['page_id'].">";
	echo "<input type='hidden' name='type' value=".$type.">";
	echo "<label for='title'>El título contiene</label><input type='text' name='title' value='".$title."' id='f_title'>";
	echo "<label for='year'>Del año</label><input type='text' name='year' value='".$year."' id='f_year'>";
	echo "<label for='research_unit_id'>Del grupo de investigación</label>";
	echo JSONCombo('research_unit_id', 'f_research_unit', 'f_combo', $webapi_url . '/research_units', 'data', 'id', $research_unit_id, 'name_es');
	echo "<input type='submit' name='ipub_filter_btn' value='Filtrar'>";
	echo "</form>";
	echo "</div>";
}



?>

