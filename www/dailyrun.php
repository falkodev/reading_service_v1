<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport"    content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author"      content="Sergey Pozhilov (GetTemplate.com)">
	
	<title>JW Reading</title>
</head>
<body>
<?php
//echo "entree dans le script";

//connexion BD
include("../app/utils.php");

//echo "bd ok<br>";
$today=date("Y/m/j");
//texte du jour
$url_fr = "http://wol.jw.org/fr/wol/dt/r30/lp-f/".$today;
$url_en = "http://wol.jw.org/en/wol/dt/r1/lp-e/".$today;
$url_ro = "http://wol.jw.org/ro/wol/dt/r34/lp-m/".$today; 

$arr_fr = getDailyText($url_fr); 
$arr_en = getDailyText($url_en); 
$arr_ro = getDailyText($url_ro);

$daily_text_fr = $arr_fr[0]; 
$daily_comment_fr = $arr_fr[1]; 
echo "daily_text_fr = $daily_text_fr <br>daily_comment_fr = $daily_comment_fr<br><br> ";

$daily_text_en = $arr_en[0]; 
$daily_comment_en = $arr_en[1];
echo "daily_text_en = $daily_text_en <br>daily_comment_en = $daily_comment_en<br><br> ";

$daily_text_ro = $arr_ro[0]; 
$daily_comment_ro = $arr_ro[1];
echo "daily_text_ro = $daily_text_ro <br>daily_comment_ro = $daily_comment_ro<br><br> ";

$jourActuel = date("N");
//echo "jourActuel = $jourActuel \r\n";
$query = "select user_id, user_first_name, user_mail, user_cycle_first_day, user_day_1, user_day_2, user_day_3, user_day_4, user_day_5, user_day_6, user_day_7, user_daily_comment, user_reading_param, user_daily_comment_param, unsubscribe_hash from user where (user_day_$jourActuel = 1 or user_daily_comment = 1) and user_active = 1" ;
//echo "query = $query \r\n";
$result = mysqli_query($mysqli, $query);
//echo "mysql query ok";
//echo "<br>result:"; //print_r($result);
$destinataires = "<br>\r\n";
foreach($result as $index)
{
	$envoi_lecture = 0;
	$envoi_texte = 0;
	$tab_lang = include('../app/lang/'.$index['user_reading_param'].'.php');
	$lang = $index['user_reading_param'];
	$langText = $index['user_daily_comment_param']; 

	$message_body = __('daily_run_msg_text_1')." ".$index['user_first_name']." ".__('daily_run_msg_text_2')."<br><br>\r\n\r\n";
	//print_r($index);
	//echo "<br><br>user id = ".$index['user_first_name']." /// user first cycle day = ".$index["user_cycle_first_day"];
	//echo "<br>";
	if($index['user_daily_comment'] == 1){
		if($langText == "fr" && !empty($daily_text_fr) && !empty($daily_comment_fr)){ 
			$message_body .= "Le texte du jour est le suivant : <br><br>\r\n\r\n<b>$daily_text_fr</b><br>\r\n$daily_comment_fr<br><br>\r\n\r\n"; 
			$envoi_texte = 1; 
		} 
		else if($langText == "en" && !empty($daily_text_en) && !empty($daily_comment_en)){ 
			$message_body .= "Today's text is the following : <br><br>\r\n\r\n<b>$daily_text_en</b><br>\r\n$daily_comment_en<br><br>\r\n\r\n"; 
			$envoi_texte = 1; 
		} 
		else if($langText == "ro" && !empty($daily_text_ro) && !empty($daily_comment_ro)){ 
			$message_body .= "Textul zilei este : <br><br>\r\n\r\n<b>$daily_text_ro</b><br>\r\n$daily_comment_ro<br><br>\r\n\r\n"; 
			$envoi_texte = 1; 
		}
	}
	
	if($index['user_day_'.$jourActuel] == 1){
		//on détermine le type de découpage à aller chercher (sur 1 jour, sur 2 jours, ...)
		$envoi_lecture = 1;
		$nbJours = 0; 
		$beforeFirstCycleDay = 0;
		$numeroPortion=0;
		for($i=1;$i<8;$i++)
		{
			if($index["user_day_$i"] == 1) 
			{
				
				$nbJours++;
				if($i < $index["user_cycle_first_day"]) $beforeFirstCycleDay = 1;
				//if(($i <= $jourActuel ) || ($i > $jourActuel && $i >= $index["user_cycle_first_day"] && $jourActuel < $index["user_cycle_first_day"])) $numeroPortion++;				
				
				//2 cas de figure : 
				//1er cas :  si le jour actuel est plus grand que le "premier" jour du cycle, il faut prendre en compte le jour actuel + les jours à la fois plus grands que le "premier" jour et plus petits que le jour actuel
				if($jourActuel > $index["user_cycle_first_day"] && $i <= $jourActuel && $i >= $index["user_cycle_first_day"]) $numeroPortion++;
				//2e cas : si le jour actuel est plus petit que le "premier" jour du cycle, il faut prendre en compte le jour actuel + les jours plus petits que le jour actuel + les jours plus grands que le "premier" jour + le "premier" jour
				elseif($jourActuel < $index["user_cycle_first_day"] && ( ($i <= $jourActuel) || ($i >= $index["user_cycle_first_day"])) ) $numeroPortion++;
			}
		}
		if($jourActuel == $index["user_cycle_first_day"]) $numeroPortion = 1;
		//on détermine si lecture semaine actuelle ou semaine prochaine
		//si user_first_cycle > today : semaineProchaine = 0
		//si user_first_cycle < today : semaineProchaine = 1
		//si user_first_cycle = today & aucun jour plus petit que user_first_cyle : semaineProchaine = 0
		//si user_first_cycle = today & au moins 1 jour plus petit que user_first_cyle : semaineProchaine = 1
		$semaineProchaine = 0;
		if($jourActuel >= $index["user_cycle_first_day"] && $beforeFirstCycleDay == 1) $semaineProchaine = 1;
		
		
		//echo "jourActuel = $jourActuel ///nb jours = $nbJours /// semaineProchaine = $semaineProchaine /// numeroPortion = $numeroPortion";
		if($semaineProchaine == 0) {$date=date("W");}
		else {$date=date("W") + 1;}
		$fichier = $date."dbr".$nbJours.$numeroPortion.".html";
		//echo " /// fichier = $fichier";
		//$contenu = file_get_contents("portions_".$lang."/".$fichier);
		////////////
		//$fichier = "46dbr51.html";
		$file = file_get_contents("portions_".$lang."/".$fichier);
		///////////
		$message_body .= __('daily_run_msg_text_3')."<a href=\"http://www.jwreading.com/portions_$lang/$fichier\">$fichier</a>.<br><br>\r\n\r\n";

		$dom = new DOMDocument();
		$dom->loadHTML($file);
	  	$xpath = new DOMXpath($dom);
	  	$content = $xpath->query('//div[@class="dailyrun"]');
		//$contenu = $content->item(0)->textContent;
		$doc = new DOMDocument();
		foreach ($content->item(0)->childNodes as $child) 
        {
        	$doc->appendChild($doc->importNode($child, true)); 
        }
        $contenu = $doc->saveHTML();
        //echo "<br>yo ".$contenu."<br>";

		//echo "<br>contenu = $contenu";
		//print_r($content->item(0));
		/*foreach($content->item(0) as $child) {
			echo "<br>child = ".$child->textContent;
		    $contenu .= $child->textContent;
		}*/

		$verses = explode("<br>", $contenu);
		foreach($verses as $verse) { //découpage par verset pour éviter d'avoir des lignes avec 998 caractères ou plus car sinon tronquées dans l'email
			//echo "<br>".$verse." !!!";
			if(strlen($verse) > 997) {
				$verse = str_replace("<input","\r\n<input",$verse); //chaque verset finissant par "<input type='hidden'>", on ajoute à cet endroit un retour chariot
				if(substr($verse,0,2) == "<b") { //si on est dans une recherche longue (une recherche commençant par une balise <b>)
					$expl = explode(". ", $verse);//on va faire des retours à la ligne à chaque fin de phrase (donc après un .) pour éviter les pbl de caractères accentués mal lus
					$verse = "";
					foreach($expl as $key=>$value)
					{
						//détection si le 1er caractère($value[0]) de la phrase est une lettre majuscule ([A-Z])
						preg_match('/[A-Z]/',$value[0], $matches);
						if($key!=0 && $matches[0]) {
							$value = ".\r\n".$value; //retour à la ligne si c'est le cas
						}
						elseif($key==0) $value = $value; //sauf pour la 1ère phrase de la recherche
						else $value = ". ".$value; //rajout du . perdu pour les versets
						$verse .= $value;
					}
				}
			}
			$verse = str_replace("<b>","\r\n<b>",$verse); //chaque recherche de verset commençant par "<b>", on met des sauts à la ligne
			$message_body .= $verse;
		}
		//$message_body .= $contenu;
	}
	if($envoi_lecture == 0 && $envoi_texte == 1) {
		sendMail($index["user_mail"], __('daily_run_subject_comment'), $message_body, __('daily_run_title_comment'), $index["user_mail"], $index["unsubscribe_hash"]);		
	}
	elseif($envoi_lecture == 1) {
		sendMail($index["user_mail"], __('daily_run_subject_reading'), $message_body, __('daily_run_title_reading'), $index["user_mail"], $index["unsubscribe_hash"]);
	}
	echo "email envoyé à ".$index["user_mail"]."\r\n";
	$destinataires .= $index["user_mail"]."<br>\r\n";
}

$message = "Bonjour,<br><br>
Le script dailyrun.php s'est bien déroulé.
Un email a été envoyé à : $destinataires<br>
L'équipe JW Reading"; 

$to = "anthony.tarlao@gmail.com"; 
$subject = "JW Reading - Daily run"; 
//$from = "From: tarlao-anthony@bbox.fr\r\n"; 

$headers = "Content-Type: text/html; charset=iso-8859-1\r\n";
$headers .= "From:noreply@jwreading.com";

mail($to, $subject, $message, $headers);

function getDailyText($url){
	$arr = array();
	$page = file_get_contents ($url);
	$dom_object = new DomDocument;
	$dom_object->validateOnParse = true;
	$dom_object->loadHTML($page);
	$xpath = new Domxpath($dom_object);
	$daily_texts = $xpath->query("//p[@class='sa']");
	$daily_text = $daily_texts->item(0)->textContent;
	$daily_comments = $xpath->query("//p[@class='sb']");
	$daily_comment = $daily_comments->item(0)->textContent;
	//$daily_comment = preg_replace('/\.\s[a-zA-Z]/', '. <br>/[a-zA-Z]/', $daily_comment);
	$expl = explode(". ", $daily_comment);
	$daily_comment = "";
	foreach($expl as $key=>$value)
	{
		//détection si le 1er caractère($value[0]) de la phrase est un caractère non-numérique (\D)
		//preg_match('/\D/',$value[0], $matches);
		preg_match('/[A-Z]/',$value[0], $matches);
		if($key!=0 && $matches[0]) $value = ".\r\n".$value; //retour à la ligne si c'est le cas
		elseif($key==0) $value = $value; //sauf pour la 1ère phrase du texte du jour
		else $value = ". ".$value; //rajout du . perdu pour les versets
		$daily_comment .= $value;
	}
	$arr[0] = $daily_text;
	$arr[1] = $daily_comment;
	return $arr;
}
?>
</body>
</html>