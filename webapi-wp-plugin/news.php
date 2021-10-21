<?php

// TO-DO: All


/* deprecated
function imedea_news_list( $atts ){

  // get attibutes and set defaults
       extract(shortcode_atts(array(
           'page_id' => $_GET["page_id"],
               'type' => $_GET["api_type"],
               'research_unit_id' => '',
               'year' => $_POST["year"],
               'title' => $_POST["title"],
               'journal' => $_POST["journal"],
               'person_id' => $_GET["person_id"],
               'show_pub_list' => $_GET["show_pub_list"]

      ), $atts));

   ob_start();

   if ($show_pub_list == "false"){

   }else{

       $page_detail_id = 1539;

       $txt_type = "";

       if ($type== "" || $type=="news"){$type="news";$txt_type="Noticias";
       }else if ($type=="press"){$txt_type="Notas de prensa";
       }else if ($type=="day_to_day"){$txt_type="IMEDEA día a día";
       }

       echo "<div style='width:100%; text-align:right;'>
           <a class='new_list_type_sec' href='https://130.206.32.250/test/?page_id=".$page_id."&api_type=news&person_id=".$person_id."'>Noticias</a> | 
           <a class='new_list_type_sec' href='https://130.206.32.250/test/?page_id=".$page_id."&api_type=press&person_id=".$person_id."'>Notas de prensa</a> | 
           <a class='new_list_type_sec' href='https://130.206.32.250/test/?page_id=".$page_id."&api_type=day_to_day&person_id=".$person_id."'>IMEDEA día a día</a></div>";
         
         echo "<br/>";

         echo "<span class='new_list_type' style='font-size:30px;font-weight:bold'>".$txt_type."</span>";

       if ($type== "" || $type=="news"){
           $count = do_shortcode("[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/".$type."?research_unit_id=".$research_unit_id."&year=".$year."&title=".$title."&journal=".$journal." ]{count}[/jsoncontentimporter]");			
       }else if ($type=="press"){
           $count = do_shortcode("[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/".$type."?research_unit_id=".$research_unit_id."&year=".$year."&title=".$title."&publisher=".$journal."]{count}[/jsoncontentimporter]");			
       }else if ($type=="day_to_day"){
         $count = do_shortcode("[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/".$type."?research_unit_id=".$research_unit_id."&year=".$year."&title=".$title."&publisher=".$journal."]{count}[/jsoncontentimporter]");	
       }
         
         echo "&nbsp;(".$count.")";
         echo "<br/>";

         $j = 0;
         $top = 10;
         $min = 0;
         $max = intdiv($count,20);

         if ($max>=1 && $max <$top){
           for ($i=$min;$i<=$max;$i++){
             $j=$i+1;
             if ($i==$_GET["api_offset"]){
             echo "<a href='https://130.206.32.250/test/?page_id=".$page_id."&api_offset=".$i."' style='font-weight:bold; font-size:20px;'>".$j."</a> | ";
             }else{
             echo "<a href='https://130.206.32.250/test/?page_id=".$page_id."&api_offset=".$i."'>".$j."</a> | ";
           }
         }
       }else if ($max>=0 && $max >=$top ){

           if ($_GET["api_offset"]!=''){				    	

             if ($_GET["api_offset"]<$top){
               $min = 0;
               $top = 10;
             }else{
               $min = $_GET["api_offset"] - 9;
               $top = $_GET["api_offset"] + 1;
               $low = $min-1;
             echo "<a href='https://130.206.32.250/test/?page_id=".$page_id."&api_offset=".$low."'><< </a>";				    						    	
             }
         }else{
           $min = 0;
         }


           for ($i=$min;$i<$top;$i++){
             $j=$i+1;
             if ($i==$_GET["api_offset"]){
             echo "<a href='https://130.206.32.250/test/?page_id=".$page_id."&api_offset=".$i."' style='font-weight:bold; font-size:20px;'>".$j."</a> | ";
             }else{
             echo "<a href='https://130.206.32.250/test/?page_id=".$page_id."&api_offset=".$i."'>".$j."</a> | ";
           }
         }
         echo "<a href='https://130.206.32.250/test/?page_id=".$page_id."&api_offset=".$top."'> >></a>";
       }
     


         echo "<br/><br/>";
         $str_shortcode = '		    		
             <script>
             function transform_date(_text){
               var _date = new Date(_text.substr(0,4),_text.substr(5,2),_text.substr(8,2));

             var dd = _date.getDate();

             var mm = _date.getMonth(); 
             var yyyy = _date.getFullYear();

             if(dd<10) 
             {
                 dd="0"+dd;
             } 

             if(mm<10) 
             {
                 mm="0"+mm;
             } 

               document.write(dd + "/" + mm + "/" + yyyy);
             }

           </script>
         ';


       if ($type== "" || $type=="news"){
         $str_shortcode .='[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/'.$type.'?limit=20&offset='.$_GET["api_offset"].'&research_unit_id='.$research_unit_id.'&year='.$year.'&title='.$title.'&journal='.$journal.' basenode=data]
           <a href="?page_id='.$page_detail_id.'&news_id={id}&api_type='.$type.'">
           <table>
             <tr>
               <td><img src="{image}" width="150px" style="padding:20px;"></td>
               <td class="new_list_date" style="padding:20px;"><script> transform_date("{date}");</script><br/>
               <span class="new_list_title"><b>{title_es}</b></span>
               </td>
             </tr>
           </table>
           </a><hr />[/jsoncontentimporter]';
         echo do_shortcode($str_shortcode);
       }else if ($type=="press"){
         $str_shortcode .='[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/'.$type.'?limit=20&offset='.$_GET["api_offset"].'&research_unit_id='.$research_unit_id.'&year='.$year.'&title='.$title.'&journal='.$journal.'&person_id='.$person_id.' basenode=data]
           <a href="?page_id='.$page_detail_id.'&news_id={id}&api_type='.$type.'">
           <table>
             <tr>
               <td><img src="{image}" width="250px" style="padding:20px;"></td>
               <td class="new_list_date" style="padding:20px;"><script> transform_date("{date}");</script><br/>
               <span class="new_list_title"><b>{title_es}</b></span>
               </td>
             </tr>
           </table>
           </a><hr />[/jsoncontentimporter]';
         echo do_shortcode($str_shortcode);			
       }else if ($type=="day_to_day"){
         $str_shortcode .='[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/'.$type.'?limit=20&offset='.$_GET["api_offset"].'&research_unit_id='.$research_unit_id.'&year='.$year.'&title='.$title.'&journal='.$journal.'&person_id='.$person_id.' basenode=data]
           <a href="?page_id='.$page_detail_id.'&news_id={id}&api_type='.$type.'">
           <table>
             <tr>
               <td><img src="{image}" width="250px" style="padding:20px;"></td>
               <td class="new_list_date" style="padding:20px;"><script> transform_date("{date}");</script><br/>
               <span class="new_list_title"><b>{title_es}</b></span>
               </td>
             </tr>
           </table>
           </a><hr />[/jsoncontentimporter]';
         echo do_shortcode($str_shortcode);
       }
   }

   return ob_get_clean();

}
*/

// shortcode []
function imedea_news_list_squares( $atts ){

  // get attibutes and set defaults
       extract(shortcode_atts(array(
           'page_id' => $_GET["page_id"],
               'type' => $_GET["api_type"],
               'research_unit_id' => '',
               'year' => $_POST["year"],
               'title' => $_POST["title"],
               'journal' => $_POST["journal"],
               'person_id' => $_GET["person_id"],
               'show_pub_list' => $_GET["show_pub_list"]

      ), $atts));

   ob_start();

   if ($show_pub_list == "false"){

   }else{

       $page_detail_id = 1539;

       $txt_type = "";
       if ($type== "" || $type=="news"){$type="news";$txt_type="Noticias";
       }else if ($type=="press"){$txt_type="Notas de prensa";
       }else if ($type=="day_to_day"){$txt_type="IMEDEA día a día";
       }


         $str_first_shortcode = '                    
                   <script>
                       function transform_date(_text){
                           var _date = new Date(_text.substr(0,4),_text.substr(5,2),_text.substr(8,2));

                           var dd = _date.getDate();

                           var mm = _date.getMonth(); 
                           var yyyy = _date.getFullYear();

                           if(dd<10) 
                           {
                               dd="0"+dd;
                           } 

                           if(mm<10) 
                           {
                               mm="0"+mm;
                           } 

                           document.write(dd + "/" + mm + "/" + yyyy);
                       }
                   </script>
               ';              


               $str_first_shortcode .='[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/'.$type.'?limit=1&offset=0&research_unit_id='.$research_unit_id.'&sticky=false&year='.$year.'&title='.$title.' basenode=data]
                   <a href="?page_id='.$page_detail_id.'&news_id={id}&api_type='.$type.'">
                     <div style="border:1px solid #EEE;  width:100%; text-align:center;margin-right:0px; margin-top:-225px;z-index:0;overflow:hidden; background-image:url({image}); background-size:cover;min-height:600px;width:100%;">
                         <div class="new_list_date" style="padding:20px; background-color:rgba(0,0,0,0.5); width:60%; margin-top:350px; text-align:left;">
                             <script> transform_date("{date}");</script><br/>
                             <span class="new_list_title" style="font-size:30px;"><b>{title_es}</b></span>
                         </div>                              
                   </div>
                   </a>
                   [/jsoncontentimporter]';
               echo do_shortcode($str_first_shortcode);


       echo "<div style='width:100%; text-align:right; margin-top:25px;'>
           <a class='new_list_type_sec' href='https://130.206.32.250/test/?page_id=".$page_id."&api_type=news&person_id=".$person_id."'>Noticias</a> | 
           <a class='new_list_type_sec' href='https://130.206.32.250/test/?page_id=".$page_id."&api_type=press&person_id=".$person_id."'>Notas de prensa</a> | 
           <a class='new_list_type_sec' href='https://130.206.32.250/test/?page_id=".$page_id."&api_type=day_to_day&person_id=".$person_id."'>IMEDEA día a día</a></div>";
         
         echo "<br/>";

         

       if ($type== "" || $type=="news"){
           $count = do_shortcode("[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/".$type."?research_unit_id=".$research_unit_id."&sticky=false&year=".$year."&title=".$title."&journal=".$journal." ]{count}[/jsoncontentimporter]");			
       }else if ($type=="press"){
           $count = do_shortcode("[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/".$type."?research_unit_id=".$research_unit_id."&sticky=false&year=".$year."&title=".$title."&publisher=".$journal."]{count}[/jsoncontentimporter]");			
       }else if ($type=="day_to_day"){
         $count = do_shortcode("[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/".$type."?research_unit_id=".$research_unit_id."&sticky=false&year=".$year."&title=".$title."&publisher=".$journal."]{count}[/jsoncontentimporter]");	
       }
         
         echo "<h3 class='new_list_type'>".$txt_type."</h3>";

         $j = 0;
         $top = 10;
         $min = 0;
         $max = intdiv($count,20);

         if ($max>=1 && $max <$top){
           for ($i=$min;$i<=$max;$i++){
             $j=$i+1;
             if ($i==$_GET["api_offset"]){
             echo "<a href='https://130.206.32.250/test/?page_id=".$page_id."&api_offset=".$i."' style='font-weight:bold; font-size:20px;'>".$j."</a> | ";
             }else{
             echo "<a href='https://130.206.32.250/test/?page_id=".$page_id."&api_offset=".$i."'>".$j."</a> | ";
           }
         }
       }else if ($max>=0 && $max >=$top ){

           if ($_GET["api_offset"]!=''){				    	

             if ($_GET["api_offset"]<$top){
               $min = 0;
               $top = 10;
             }else{
               $min = $_GET["api_offset"] - 9;
               $top = $_GET["api_offset"] + 1;
               $low = $min-1;
             echo "<a href='https://130.206.32.250/test/?page_id=".$page_id."&api_offset=".$low."'><< </a>";				    						    	
             }
         }else{
           $min = 0;
         }


           for ($i=$min;$i<$top;$i++){
             $j=$i+1;
             if ($i==$_GET["api_offset"]){
             echo "<a href='https://130.206.32.250/test/?page_id=".$page_id."&api_offset=".$i."' style='font-weight:bold; font-size:20px;'>".$j."</a> | ";
             }else{
             echo "<a href='https://130.206.32.250/test/?page_id=".$page_id."&api_offset=".$i."'>".$j."</a> | ";
           }
         }
         echo "<a href='https://130.206.32.250/test/?page_id=".$page_id."&api_offset=".$top."'> >></a>";
       }
     


         echo "<br/><br/>";



       if ($type== "" || $type=="news"){
         $str_shortcode .='[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/'.$type.'?limit=20&offset='.$_GET["api_offset"].'&research_unit_id='.$research_unit_id.'&sticky=false&year='.$year.'&title='.$title.'&journal='.$journal.' basenode=data]
           <a href="?page_id='.$page_detail_id.'&news_id={id}&api_type='.$type.'">
           <div style="position:relative; float:left; text-align:center; margin-right:40px; margin-top:40px; border:1px solid #EEE; border-bottom:4px solid #CCC;border-right:4px solid #CCC;  width:30%; height:450px;">
             <div style=" overflow:hidden; background-image:url({image}); background-size:cover;min-height:250px;">
             </div>
             <div class="new_list_date" style="padding:20px;">
               <script> transform_date("{date}");</script><br/>
               <span class="new_list_title"><b>{title_es}</b></span>
             </div>
           </div>							
           </a>[/jsoncontentimporter]';
         echo do_shortcode($str_shortcode);
       }else if ($type=="press"){
         $str_shortcode .='[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/'.$type.'?limit=20&offset='.$_GET["api_offset"].'&research_unit_id='.$research_unit_id.'&sticky=false&year='.$year.'&title='.$title.'&journal='.$journal.' basenode=data]
           <a href="?page_id='.$page_detail_id.'&news_id={id}&api_type='.$type.'">
           <div style="position:relative; float:left; text-align:center; margin-right:40px; margin-top:40px; border:1px solid #EEE; border-bottom:4px solid #CCC;border-right:4px solid #CCC;  width:30%; height:450px;">
             <div style=" overflow:hidden; background-image:url({image}); background-size:cover;min-height:250px;">
             </div>
             <div class="new_list_date" style="padding:20px;">
               <script> transform_date("{date}");</script><br/>
               <span class="new_list_title"><b>{title_es}</b></span>
             </div>
           </div>	
           </a>[/jsoncontentimporter]';
         echo do_shortcode($str_shortcode);			
       }else if ($type=="day_to_day"){
         $str_shortcode .='[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/'.$type.'?limit=20&offset='.$_GET["api_offset"].'&research_unit_id='.$research_unit_id.'&sticky=false&year='.$year.'&title='.$title.'&journal='.$journal.' basenode=data]
           <a href="?page_id='.$page_detail_id.'&news_id={id}&api_type='.$type.'">
           <div style="position:relative; float:left; text-align:center; margin-right:40px; margin-top:40px; border:1px solid #EEE; border-bottom:4px solid #CCC;border-right:4px solid #CCC;  width:30%; height:450px;">
             <div style=" overflow:hidden; background-image:url({image}); background-size:cover;min-height:250px;">
             </div>
             <div class="new_list_date" style="padding:20px;">
               <script> transform_date("{date}");</script><br/>
               <span class="new_list_title"><b>{title_es}</b></span>
             </div>
           </div>	
           </a>[/jsoncontentimporter]';
         echo do_shortcode($str_shortcode);
       }
   }


   return ob_get_clean();

}

function imedea_first_sticky_news_list( $atts ){

  // get attibutes and set defaults
       extract(shortcode_atts(array(
           'page_id' => $_GET["page_id"],
               'type' => $_GET["api_type"],
               'research_unit_id' => '',
               'year' => $_POST["year"],
               'title' => $_POST["title"],
               'journal' => $_POST["journal"],
               'person_id' => $_GET["person_id"],
               'show_pub_list' => $_GET["show_pub_list"]

      ), $atts));

   ob_start();

   if ($show_pub_list == "false"){

   }else{

       $page_detail_id = 1539;

       $txt_type = "";

       if ($type== "" || $type=="news"){$type="news";$txt_type="";}
       
         
         echo "<br/>";


         $str_shortcode = '		    		
             <script>
             function transform_date(_text){
               var _date = new Date(_text.substr(0,4),_text.substr(5,2),_text.substr(8,2));

             var dd = _date.getDate();

             var mm = _date.getMonth(); 
             var yyyy = _date.getFullYear();

             if(dd<10) 
             {
                 dd="0"+dd;
             } 

             if(mm<10) 
             {
                 mm="0"+mm;
             } 

               document.write(dd + "/" + mm + "/" + yyyy);
             }

             function cutString(_text,limit){
               document.write(_text.substring(0,limit)+" [...]");		    				
             }

           </script>
         ';

       if ($type== "" || $type=="news"){
         $str_shortcode .='[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/'.$type.'?limit=1&offset=0&sticky=true basenode=data]
           <a href="?page_id='.$page_detail_id.'&news_id={id}&api_type='.$type.'">
             <div style="height:500px; overflow:hidden;">
               <img src="{image}" style="min-width:500px; ">
             </div>
             <div style="position:relative; margin:-250px 0px 0px 0px; border:0px solid #f00; background-color:rgba(0,0,0,0.5); width:75%; padding:25px;">
               <span class="snew_list_date" style="color:#FFF;"> <script> transform_date("{date}");</script></span><br/>
               <span class="fsnew_list_title" style="color:#FFF; font-size:25px;"><b>{title_es}</b></span><br/>
               <span class="fsnew_list_content" style="color:#FFF; font-size:15px;"><script>cutString("{content_es}",75);</script></span>
             </div>
           </a>[/jsoncontentimporter]';
         echo do_shortcode($str_shortcode);
       }
   }


   return ob_get_clean();

}

function imedea_sticky_news_list( $atts ){


  // get attibutes and set defaults
       extract(shortcode_atts(array(
           'page_id' => $_GET["page_id"],
               'type' => $_GET["api_type"],
               'research_unit_id' => '',
               'year' => $_POST["year"],
               'title' => $_POST["title"],
               'journal' => $_POST["journal"],
               'person_id' => $_GET["person_id"],
               'show_pub_list' => $_GET["show_pub_list"]

      ), $atts));

   ob_start();

   if ($show_pub_list == "false"){

   }else{

       $page_detail_id = 1539;

       $txt_type = "";

       if ($type== "" || $type=="news"){$type="news";$txt_type="";}
       
         
         echo "<br/>";


         $str_shortcode = '		    		
             <script>
             function transform_date(_text){
               var _date = new Date(_text.substr(0,4),_text.substr(5,2),_text.substr(8,2));

             var dd = _date.getDate();

             var mm = _date.getMonth(); 
             var yyyy = _date.getFullYear();

             if(dd<10) 
             {
                 dd="0"+dd;
             } 

             if(mm<10) 
             {
                 mm="0"+mm;
             } 

               document.write(dd + "/" + mm + "/" + yyyy);
             }

           function cutString(_text,limit){
               document.write(_text.substring(0,limit)+" [...]");		    				
             }

           </script>
         ';


       if ($type== "" || $type=="news"){

         $str_shortcode .='[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/'.$type.'?limit=1&offset=1&sticky=true basenode=data]
           <a href="?page_id='.$page_detail_id.'&news_id={id}&api_type='.$type.'">
           <table style="margin:25px 0px 50px 0px;">
             <tr>
               <td style="background-image:url({image}); background-size:cover;min-height:200px;width:200px;"></td>
               <td class="snew_list_date" style="padding:20px;"><script> transform_date("{date}");</script><br/>
                 <span class="snew_list_title"><b>{title_es}</b></span><br/>
                 <span class="snew_list_content" style="color:#000; font-size:15px;"><script>cutString("{content_es}",100);</script></span>
               </td>
             </tr>
           </table>
           </a><hr/>[/jsoncontentimporter]';

         $str_shortcode .='[jsoncontentimporter url=https://imedea.uib-csic.es/webapi/'.$type.'?limit=1&offset=2&sticky=true basenode=data]
           <a href="?page_id='.$page_detail_id.'&news_id={id}&api_type='.$type.'">
           <table style="margin:50px 0px 50px 0px;">
             <tr>
               <td style="background-image:url({image}); background-size:cover;min-height:200px;width:200px;"></td>
               <td class="snew_list_date" style="padding:20px;"><script> transform_date("{date}");</script><br/>
               <span class="snew_list_title"><b>{title_es}</b></span><br/>
               <span class="snew_list_content" style="color:#000; font-size:15px;"><script>cutString("{content_es}",100);</script></span>

               </td>
             </tr>
           </table>
           </a>[/jsoncontentimporter]';

         echo do_shortcode($str_shortcode);
       }
   }


   return ob_get_clean();

}

// shortcode []
function imedea_news_detail( $atts ){


  // get attibutes and set defaults
       extract(shortcode_atts(array(
               'type' => $_GET["api_type"],        	
           'news_id' => $_GET["news_id"]
      ), $atts));

   ob_start();

   $txt_type = "";

   if ($type== "" || $type=="news"){$type="news";$txt_type="Noticia";
   }else if ($type=="press"){$txt_type="Nota de prensa";
   }else if ($type=="day_to_day"){$txt_type="IMEDEA día a día";
   }

     echo "<span style='font-size:30px;font-weight:bold'>".$txt_type."</span>";
     echo "<br/><br/>";

     $txt_shortcode = '
       <link href="https://130.206.32.250/test/wp-content/css_/lightbox.css" rel="stylesheet" />
       <script src="https://130.206.32.250/test/wp-content/js_/lightbox.js"></script>
       
         <script>
         function call_page(id){
           if (id == 5){
             window.location.href="?page_id=804";
           }else if (id==6){
             window.location.href="?page_id=806";
           }else if (id==7){
             window.location.href="?page_id=802";
           }else if (id==8){
             window.location.href="?page_id=808";    					
           }else if (id==9){
             window.location.href="?page_id=810";    					
           }
         }


         function parse_html(_txt){
           document.write(_txt);
         }
       </script>
     ';


     if ($type=="news"){
       $txt_shortcode .= '
       [jsoncontentimporter url=https://imedea.uib-csic.es/webapi/'.$type.'/'.$news_id.']
       <div style="position:relative; margin:0px 0px 0px 0px; float:left; border:0px solid #F00; width:65%;">
       <table style="width:100%">
       <tr><td>
         <h2><span class="new_det_title">{title_es}</span></h2>
         </td></tr>
       <tr><td><span class="new_det_content">{content_es:purejsondata}</span>
         </td></tr>
     </table>
     </div>
     <div style="position:relative; margin:0px 0px 0px 25px; padding:10px; float:left; width:30%;border:0px solid #FF0; height:auto; background-color:#DDD;">
     {subloop-array:images:5}
       <a href="{0}" data-lightbox="image-1"> 
         <img src="{0}" style="margin:20px auto; display:block; width:300px;"/>
       </a> 
       <a href="{1}" data-lightbox="image-1"> 
         <img src="{1}" style="margin:5px 10px 0px 20px; width:75px;"/> 
       </a>
       <a href="{2}" data-lightbox="image-1"> 
         <img src="{2}" style="margin:5px 10px 0px 0px; width:75px;"/> 
       </a>
     {/subloop-array:images}
     
     <br/><b>Archivos relacionados</b><br/>
     {subloop-array:documents:1}
       <a href="{0}">DOC</a>{/subloop-array:documents}

     <br/><b>Personal relacionado</b><br/>
     {subloop-array:related_people:-1}<a href="?page_id=1271&person_id={related_people.id}&show_pub_list=false">{related_people.name}</a><br/>{/subloop-array:related_people}	

     {subloop-array:related_departments:-1}{related_departments.name_es:ifNotEmptyAddLeft:<b>Departamentos relacionados</b><br/>}{/subloop-array:related_departments}		

     {subloop-array:related_research_units:-1}<a href="javascript:call_page({related_research_units.id});">{related_research_units.name_es:ifNotEmptyAddLeft:<b>Grupos de investigación relacionados</b><br/>}</a><br/>{/subloop-array:related_research_units}
     </div>[/jsoncontentimporter]
     ';
     echo do_shortcode($txt_shortcode);
   }else if ($type == "press"){
     $txt_shortcode .= '
       [jsoncontentimporter url=https://imedea.uib-csic.es/webapi/news/'.$news_id.']
       <div style="position:relative; margin:0px 0px 0px 0px; float:left; border:0px solid #F00; width:65%;">
       <table style="width:100%">
       <tr><td><h2><span class="new_det_title">{title_es}</span></h2></td></tr>
       <tr><td><span class="new_det_content">{content_es:purejsondata}</span></td></tr>
     </table>
     </div>
     <div style="position:relative; margin:0px 0px 0px 25px; padding:10px; float:left; width:30%;border:0px solid #FF0; height:auto; background-color:#DDD;">
     {subloop-array:images:5}
       <a href="{0}" data-lightbox="image-1"> 
         <img src="{0}" style="margin:20px auto; display:block; width:300px;"/>
       </a> 
       <a href="{1}" data-lightbox="image-1"> 
         <img src="{1}" style="margin:5px 10px 0px 20px; width:75px;"/> 
       </a>
       <a href="{2}" data-lightbox="image-1"> 
         <img src="{2}" style="margin:5px 10px 0px 0px; width:75px;"/> 
       </a>
     {/subloop-array:images}
     <br/><br/><b>Archivos relacionados</b><br/>
     {subloop-array:documents:1}
     <a href="{0}">DOC</a>{/subloop-array:documents}
     <br/><b>Personal relacionado</b><br/>
     {subloop-array:related_people:-1}<a href="?page_id=1271&person_id={related_people.id}&show_pub_list=false">{related_people.name}</a><br/>{/subloop-array:related_people}
     <br/><b>Departamentos relacionados</b><br/>
     {subloop-array:related_departments:-1}{related_departments.name_es}<br/>{/subloop-array:related_departments}			
     <br/><b>Grupos de investigación relacionados</b><br/>								
     {subloop-array:related_research_units:-1}<a href="javascript:call_page({related_research_units.id});">{related_research_units.name_es}</a><br/>{/subloop-array:related_research_units}
     </div>[/jsoncontentimporter]
     ';
     echo do_shortcode($txt_shortcode);
   }else if ($type =="day_to_day"){
     $txt_shortcode .= '
       [jsoncontentimporter url=https://imedea.uib-csic.es/webapi/news/'.$news_id.']
       <div style="position:relative; margin:0px 0px 0px 0px; float:left; border:0px solid #F00; width:65%;">
       <table style="width:100%">
       <tr><td><h2><span class="new_det_title">{title_es}</span></h2></td></tr>
       <tr><td><span class="new_det_content">{content_es:purejsondata}</span></td></tr>
     </table>
     </div>
     <div style="position:relative; margin:0px 0px 0px 25px; padding:10px; float:left; width:30%;border:0px solid #FF0; height:auto; background-color:#DDD;">
     {subloop-array:images:1}<img src="{0}" />{/subloop-array:images}
     <br/><b>Archivos relacionados</b><br/>
     {subloop-array:documents:1}
     <a href="{0}">DOC</a>{/subloop-array:documents}
     <br/><b>Personal relacionado</b><br/>
     {subloop-array:related_people:-1}<a href="?page_id=1271&person_id={related_people.id}&show_pub_list=false">{related_people.name}</a><br/>{/subloop-array:related_people}
     <br/><b>Departamentos relacionados</b><br/>
     {subloop-array:related_departments:-1}{related_departments.name_es}<br/>{/subloop-array:related_departments}
     <br/><b>Grupos de investigación relacionados</b><br/>			
     {subloop-array:related_research_units:-1}<a href="javascript:call_page({related_research_units.id});">{related_research_units.name_es}</a><br/>{/subloop-array:related_research_units}
     </div>[/jsoncontentimporter]
     ';
     echo do_shortcode($txt_shortcode);	
   }

   return ob_get_clean();

}


add_shortcode('imedea_news_list', 'imedea_news_list');
add_shortcode('imedea_news_list_squares', 'imedea_news_list_squares');

add_shortcode('imedea_news_detail', 'imedea_news_detail');

add_shortcode('imedea_sticky_news_list', 'imedea_sticky_news_list');
add_shortcode('imedea_first_sticky_news_list', 'imedea_first_sticky_news_list');

?>