<?php

function _get_target_name(string $str): string
{
   $arr = explode("/", $str);
   array_pop($arr);
   return end($arr);
}

function _get_target_genres($genres)
{
   $comic_genres    = [];
   foreach ($genres as $genre) {
      if (isset($genre->plaintext)) {
         $g = explode(":", $genre->plaintext);
         $comic_genres = replace(trim(end($g)), ", ", ",");
      }
   }
   return $comic_genres;
}

function _komikcast_com($comic_data, $comic_url, $web_source)
{
   $ci = get_instance();
   $main_selector  =   "div.bigcontent";

   $comic_slug = _get_target_name($comic_url);
   $name = replace($comic_slug, "-", " ");

   $cover          =   $comic_data->find("$main_selector div.thumb img")[0];
   // $name           =   $comic_data->find("$main_selector div.infox h1")[0];
   $description    =   $comic_data->find("div.desc p")[0];
   $genres         =   $comic_data->find("$main_selector div.spe a");
   $status         =   $comic_data->find("$main_selector div.spe span")[1];
   $status         =   (trim(_filterString(":", $status->plaintext)) == "Ongoing") ?  "1"  :  "0";
   $author         =   $comic_data->find("$main_selector div.spe span")[3] ?? "None";
   $realesed       =   $comic_data->find("$main_selector div.spe span")[2] ?? 0;
   $type           =   $comic_data->find("$main_selector div.spe span")[4] ?? "None";
   $score          =   $comic_data->find("$main_selector div.rating strong")[0] ?? 0;

   // $comic_genres = _get_target_genres($genres);

   $comic_genres    = [];
   foreach ($genres as $genre) {
      if (isset($genre->plaintext)) {
         $comic_genres[] = $genre->plaintext;
      }
   }
   array_pop($comic_genres);
   $comic_genres = join(",", $comic_genres);

   $ux_rate = trim(_filterString(":", $score->plaintext ?? "0"));
   $arrx  = explode(" ", $ux_rate);
   $rating = end($arrx);

   // var_dump($comic_genres);
   // die;

   $comic["comic_cover"]         = $cover->getAttribute("src");
   $comic["comic_name"]          = _filterString("Komik ", $name ?? "None");
   $comic["comic_desc"]          = $description->plaintext ?? "None";
   $comic["comic_author"]        = trim(_filterString(":", $author->plaintext ?? "None"));
   $comic["comic_status"]        = $status;
   $comic["comic_rating"]        = $rating;
   $comic["comic_genre"]         = $comic_genres;
   $comic["comic_slug"]          = strtolower($comic_slug);
   $comic["comic_type"]          = trim(_filterString(":", $type->plaintext ?? "comic"));
   $comic["comic_url_source"]    = $comic_url;
   $comic["comic_web_source"]    = 'komikcast.com';
   $comic["comic_posted_by"]     = _getUserData()["email"];
   $comic["comic_date"]          = time();
   $comic["comic_update"]        = time();
   $comic["comic_active"]        = 0;
   $comic["comic_released"]      = time();
	$comic["comic_chapters"]      = $ci->chapter_model->get_total_chapter($comic["comic_slug"]);
	// var_dump($comic);die;
   // var_dump($comic);

   // die;
   return $comic;
}






function _kiryuu_co($comic_data, $comic_url, $web_source)
{
   $ci = get_instance();

   $comic_slug = _get_target_name($comic_url);
   $name = replace($comic_slug, "-", " ");

   $cover          =   $comic_data->find("div.bigcontent div.thumb img")[0];
   $description    =   $comic_data->find("div.bigcontent div.infox div.desc div span p")[0];
   $genres         =   $comic_data->find("div.bigcontent div.infox div.spe span a")[0];
   $status         =   $comic_data->find("div.bigcontent div.infox div.spe span")[1];
   $status         =   (trim(_filterString(":", $status->plaintext)) == "Ongoing") ?  "1"  :  "0";
   $author         =   $comic_data->find("div.bigcontent div.infox div.spe span")[3] ?? "None";
   $realesed       =   $comic_data->find("div.bigcontent div.infox div.spe span")[2] ?? 0;
   $type           =   $comic_data->find("div.bigcontent div.infox div.spe span")[4] ?? "None";
   $score          =   $comic_data->find("div.rating strong")[0] ?? 0;
   
   $comic_genres = _get_target_genres($genres);

   $ux_rate = trim(_filterString(":", $score->plaintext ?? "0"));
   $arrx  = explode(" ", $ux_rate);
   $rating = end($arrx);

   $comic["comic_cover"]         = $cover->getAttribute("src");
   $comic["comic_name"]          = _filterString("Komik ", $name ?? "None");
   $comic["comic_desc"]          = $description->plaintext ?? "None";
   $comic["comic_author"]        = trim(_filterString(":", $author->plaintext ?? "None"));
   $comic["comic_status"]        = $status;
   $comic["comic_rating"]        = $rating;
   $comic["comic_genre"]         = $comic_genres;
   $comic["comic_slug"]          = strtolower($comic_slug);
   $comic["comic_type"]          = trim(_filterString(":", $type->plaintext ?? "comic"));
   $comic["comic_url_source"]    = $comic_url;
   $comic["comic_web_source"]    = 'kiryuu.co';
   $comic["comic_posted_by"]     = _getUserData()["email"];
   $comic["comic_date"]          =   time();
   $comic["comic_update"]        =   time();
   $comic["comic_active"]        = 0;
   $comic["comic_released"]      =  time();
   $comic["comic_chapters"]      = $ci->chapter_model->get_total_chapter($comic["comic_slug"]);
   // var_dump($comic);die;
   return $comic;
}




function _mock($comic_data, $comic_url, $web_source)
{
   $ci = get_instance();
   $main_selector  =   "div.bigcontent";


   $comic_slug = _get_target_name($comic_url);
   $name = replace($comic_slug, "-", " ");

   $cover          =   $comic_data->find("$main_selector div.thumb img")[0];
   // $name           =   $comic_data->find("$main_selector div.infox h1")[0];
   $description    =   $comic_data->find(".comic-desc")[0];
   $genres         =   $comic_data->find("$main_selector div.comic-spec span a");
   $status         =   "0";
   $author         =   $comic_data->find("$main_selector div.comic-spec span")[1] ?? "None";
   $realesed       =   $comic_data->find("$main_selector div.comic-spec span")[4] ?? 0;
   $type           =   $comic_data->find("$main_selector div.comic-spec span")[3] ?? "None";
   $score          =   $comic_data->find("$main_selector div.rtx p")[0] ?? 0;

   $comic_genres    = _get_target_genres($genres);

   $ux_rate = trim(_filterString(":", $score->plaintext ?? "0"));
   $arrx  = explode(" ", $ux_rate);
   $rating = end($arrx);

   $comic["comic_cover"]         = $cover->getAttribute("src");
   $comic["comic_name"]          = _filterString("Komik ", $name ?? "None");
   $comic["comic_desc"]          = trim($description->plaintext ?? "None");
   $comic["comic_author"]        = trim(_filterString(":", $author->plaintext ?? "None"));
   $comic["comic_status"]        = $status;
   $comic["comic_rating"]        = $rating;
   $comic["comic_genre"]         = $comic_genres;
   $comic["comic_slug"]          = strtolower($comic_slug);
   $comic["comic_type"]          = trim(_filterString(":", $type->plaintext ?? "comic"));
   $comic["comic_url_source"]    = $comic_url;
   $comic["comic_web_source"]    = $web_source;
   $comic["comic_posted_by"]     = _getUserData()["email"];
   $comic["comic_date"]          =   time();
   $comic["comic_update"]        =   time();
   $comic["comic_active"]        = 0;
   $comic["comic_chapters"]      = $ci->chapter_model->get_total_chapter($comic["comic_slug"]);
   // var_dump($comic);die;
   return $comic;
}






























// function collect_comic_info($comic_data, $comic_url, $web_source)
// {
//    $ci = get_instance();
//    $_data =  $ci->db->get_where("_komik_ws", ["ws_komik_name" => $web_source])->row_array();
//    var_dump($_data);
//    $cover_path    =  $_data["ws_komik_selector_cover"];
//    $name_path     =  $_data["ws_komik_selector_title"];
//    $desc_path     =  $_data["ws_komik_selector_desc"];
//    $author_path   =  $_data["ws_komik_selector_author"];
//    $type_path     =  $_data["ws_komik_selector_type"];
//    $rating_path   =  $_data["ws_komik_selector_rating"];
//    $genre_path    =  $_data["ws_komik_selector_genre"];
//    $status_path   =  $_data["ws_komik_selector_status"];
//    $release_path  =  $_data["ws_komik_selector_released"];

//    $cover          =   $comic_data->find($cover_path)[0];
//    $name           =   $comic_data->find($name_path)[0];
//    $description    =   $comic_data->find($desc_path)[0];
//    $genres         =   $comic_data->find($genre_path);
//    $status         =   $comic_data->find($status_path);
//    $author         =   $comic_data->find($author_path)[0] ?? $comic_data;
//    $realesed       =   $comic_data->find($release_path)[0] ?? $comic_data;
//    $type           =   $comic_data->find($type_path)[0] ?? $comic_data;
//    $score          =   $comic_data->find($rating_path)[0] ?? $comic_data;


//    if (trim(_filterString(":", $status->plaintext ?? "ongoing")) == "Ongoing") {
//       $status = "1";
//    } else {
//       $status = "0";
//    }

//    $m_genre = [];
//    foreach ($genres as $g) {
//       $m_genre[] = trim($g->plaintext);
//    }
//    $genre = join("|", $m_genre);


//    // var_dump($cover_path);
//    // var_dump($type);
//    $comic["comic_cover"]         = $cover->getAttribute("src");
//    $comic["comic_name"]          = $name->plaintext;
//    $comic["comic_desc"]          = $description->plaintext ?? "None";
//    $comic["comic_author"]        = $author->plaintext;
//    $comic["comic_status"]        = $status;
//    $comic["comic_rating"]        = _rpc($score->plaintext);
//    $comic["comic_genre"]         = $genre;
//    $comic["comic_slug"]          = replace($name);
//    $comic["comic_type"]          = trim($type->plaintext);
//    $comic["comic_url_source"]    = $comic_url;
//    $comic["comic_web_source"]    = $web_source;
//    $comic["comic_posted_by"]     = _getUserData()["email"];
//    $comic["comic_date"]          =   time();
//    $comic["comic_update"]        =   time();
//    $comic["comic_active"]        = 0;
//    $comic["comic_chapters"]      = $ci->chapter_model->get_total_chapter($comic["comic_slug"]);
//    var_dump($comic);

//    die;
//    return $comic;
// }


// function _rpc($s){
//    $x = explode(" ", $s);
//    return end($x);
// }
