<?php
function requireToVar($file){
    ob_start();
    require($file);
    return ob_get_clean();
}
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

$env=requireToVar("../.env");
$host=get_string_between($env,"DB_HOST="," DB_PORT=");
$username=get_string_between($env,"DB_USERNAME="," DB_PASSWORD=");
$userpassword=get_string_between($env,"DB_PASSWORD="," DB_STRICT_MODE=");
$db=get_string_between($env,"DB_DATABASE="," DB_USERNAME=");
error_reporting(0);
/*  $connect = mysqli_connect($host, $username, $userpassword, $db);   */
$connect = mysqli_connect("localhost", "root", "", "mate"); 
 mysqli_set_charset($connect,"utf8");
 $number = count($_POST["link"]);  
 if($number > 0)  
 {


	 
      for($i=0; $i<$number; $i++)  
      {  
           if(trim($_POST["link"][$i] != ''))  
           {
$id=$_POST["id"];
$links2 = $_POST['link'][$i];
$aprrove=$_POST['approve'];
	   if ($tarhely !="ERROR")
{
if(!empty($_POST["episode"][$i])) {
$sqlevad="
INSERT INTO `seasons` 
(title,title_id,number) 
SELECT '".$_POST["evad"][$i].".évad','$id','".$_POST["evad"][$i]."' FROM `episodes` 
WHERE NOT EXISTS 
(SELECT * FROM `seasons` WHERE 
title_id='$id' AND number='".$_POST["evad"][$i]."') LIMIT 1;
";
$sqlepizod="
INSERT INTO `episodes` 
(title,title_id,season_id,season_number,episode_number) 
SELECT '-','$id',(SELECT id FROM seasons WHERE number='".$_POST["evad"][$i]."' AND title_id='$id'),'".$_POST["evad"][$i]."','".$_POST["episode"][$i]."' FROM `episodes` 
WHERE NOT EXISTS 
(SELECT * FROM `episodes` WHERE 
title_id='$id' AND season_number='".$_POST["evad"][$i]."' 
AND episode_number='".$_POST["episode"][$i]."') LIMIT 1;
";
}

          $sql = "
				INSERT INTO `links` (approved,title_id,season,episode,url,quality,label,user_name,type,created_at) 
SELECT 

				'$aprrove',
				'$id',
				'".mysqli_real_escape_string($connect, $_POST["evad"][$i])."',
				'".mysqli_real_escape_string($connect, $_POST["episode"][$i])."',
				'".mysqli_real_escape_string($connect, $links2)."',
				'".mysqli_real_escape_string($connect, $_POST["minoseg"][$i])."',
				'".mysqli_real_escape_string($connect, $_POST["hang"][$i])."',
				'".mysqli_real_escape_string($connect, $_POST["bekuldoneve"])."',
				'".mysqli_real_escape_string($connect, "external")."',
				'".date("Y/m/d")."'
				
 FROM `links` 
WHERE NOT EXISTS (SELECT * FROM `links` 
      WHERE title_id='".$id."' AND url='".$links2."') 
LIMIT 1"; 
$sql2="UPDATE titles SET updated_at='".date("Y.m.d. H:i",time())."' ,links_quality='".$_POST["minoseg"][0]."',links_language='".$_POST["hang"][0]."' WHERE id='".$id."'";
	 			if(!empty($_POST["episode"][$i])) {
				mysqli_query($connect, $sqlevad); 
				mysqli_query($connect, $sqlepizod);  }
                mysqli_query($connect, $sql);  
				mysqli_query($connect, $sql2);   
echo "Köszönjük a fáradozásod, a videó hamarosan le lesz ellenőrizve, és ha megfelel minden pontnak, publikálásra kerül..";	  
				}else {
	
	echo "	A megadott szerver nem szerepel az oldal adabázisában! Kérjük erről értesíts minket facebookon vagy e-mailben.";
}
           }  
      } 
}  
 else  
 {  
      echo "Kérlek add meg a mezőket";  
 }  
 ?> 
   