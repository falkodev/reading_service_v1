#!/usr/local/bin/php
<html>
    <head>
        <meta charset="utf-8"> 
    </head>
</html>
<?php

$ancres = "";
//déterminer quel jour PHP est aujourd'hui
$jourSemaine = date("N");


//echo "jourSemaine = $jourSemaine\r\n";
//$result = mysqli_query($mysqli, 'delete from portions where portions_next_week = 0'); //chaque nouvelle semaine, les portions de la semaine passée sont supprimées
//$result = mysqli_query($mysqli, 'update portions set portions_next_week = 0 where portions_next_week = 1');//chaque nouvelle semaine, les portions de ce qui était la semaine à venir deviennent la semaine en cou
$semaineProchaine = 8 - $jourSemaine;
//echo "semaineProchaine = $semaineProchaine<br>";
$date = date("Y/n/j", mktime(0, 0, 0, date("m"), date("j")+$semaineProchaine,  date("Y"))); //on prend le lundi de la semaine suivante pour aller consulter le site jw.org
//echo "date = $date\r\n";


$url = "http://wol.jw.org/wol/dt/".$langUrl.$date; //url du json du texte du jour (contenant le lien vers la lecture de la semaine aussi)
echo "ok url = $url\r\n";
$xpath = recupJson($url, "1");
//lecture hebdomadaire normale balise : dernière class='se' trouvée
$query = $xpath->query("//p[@class='se']/a"); 
foreach ($query as $key => $value) {
	$liste_lecture = $value->textContent;
	$link = $value->getAttribute('href');
}
// $liste_lecture = $query->item(1)->textContent;
// $link = $query->item(1)->getAttribute('href');
echo "liste_lecture: $liste_lecture /// link:$link<br>\r\n";


$remove = explode('/',$link); //$link contient un élément indiquant la langue (ex : /fr/ pour le français)
$add = array_slice($remove, 2); //cet élément (toujours en position 1) est retiré en prenant tous les éléments du tableau (obtenu à la ligne précédente par "explode") à partir de la 2e position pour obtenir le code json de la lecture en entier (portion de la semaine)
$link = implode('/',$add);//ensuite le lien est reconstitué mais sans ce premier élément (/fr/)
echo "json = $json /// link = $link<br>";
if($json == 0) //si fichier récupéré est un html
$url = "http://wol.jw.org/".$link;
else //sinon si c'est un json
$url = "http://wol.jw.org/wol/".$link;//ceci permet de se rendre sur la page contenant toute la lecture de la semaine
echo "url = $url\r\n";

$xpath = recupJson($url, "json"); //url du json de la lecture de la semaine
if($json == 0) { echo "probleme de chargement de la lecture. arret du script"; exit; }
$query = "count(//span[@id])"; //on compte le nombre de versets (les span ayant un id)
$countVerses = $xpath->evaluate($query);
echo "countVerses = $countVerses<br>\r\n"; // countParagraphs = $countParagraphs<br>";


$increment = 0;
$lecture = array();


$compteurText = 0;
$t = $xpath->query('//text()'); //contenu texte de toute la lecture de la semaine
$vs = $xpath->query('//span');//versets de la lecture de la semaine
/*$lengthSpan = $vs->length;
echo "lengthSpan = $lengthSpan<br>";*/

$versets = ""; //cumul de tous les versets parcourus (donc de toute la lecture à la fin du traitement)
$verset = "";//verset en cours
$previousDiv = "";


foreach($vs as $keySpan => $versetSpan)
{
	//$versetParcours = verset en cours
	//$versetNext =  prochain verset
	$versetParcours = $versetSpan->textContent;
	$versetNext = $vs->item($keySpan + 1)->textContent;
	//echo "verset actuel= $versetParcours /// prochain verset = $versetNext ";

	//$text = portion du verset (plusieurs portions par verset donc obligé de boucler)
	//bcl sur $compteurText tant que $text != versetNext
	$text = $t->item($compteurText)->textContent;
	$verset = "";
	while($text != $versetNext) 
	{ 
		$verset .= $text;
		$compteurText++;
		$text = $t->item($compteurText)->textContent;
	}

	//ici, on est dans un numéro de verset (1ere portion d'un verset)
	if($text == $versetNext){		
		$numero = $versetSpan->getAttribute('id');
		$expl = explode("_",$numero);
		$numeroChapitre = $expl[1];
		$numeroVerset = $expl[2];
		if($numeroVerset == 1) { $verset = $langChapter." ".$verset; }

		$class = $versetSpan->getAttribute('class');//identification de sa classe pour savoir s'il possède un lien sur le numéro de verset (et donc une recherche à effectuer)
		$ancre = ""; //remise à 0 de l'ancre, ainsi s'il n'y a pas de lien href, l'ancre envoyée sera vide
		if(substr($class,3,2) == "dx") //si lien "dx", verset avec lien recherches
		{
			getResearch(); //recherche de publications associées au verset en cours
		}

		//versetSpan parentNode puis encore parentNode puis getAttribute id pour id du div de paragraphe (avec differentiel currentDiv et previousDiv)
		$paragraphe = $versetSpan->parentNode;
		$div = $paragraphe->parentNode;
		$currentDiv = $div->getAttribute('id');
		if($previousDiv != $currentDiv){
			$previousDiv = $currentDiv;
			$verset = "<br><br>\r\n\r\n".$verset;
		}		
		//echo " numero $text<br>";
	}

	$verset .= "<input type='hidden' value='".$numeroChapitre.":".$numeroVerset."'>"; //ajout d'un champ hidden qui servira au découpage en portions plus loin, pour savoir les versets inclus dans les portions découpées
	//cumul des versets pour decoupage futur en associant verset et ancre
	$lecture[$increment][0] = $verset;
	$lecture[$increment][1] = $ancre;
	$increment++;
	$versets .= $verset;
	//echo "$verset<br>";
}

echo "<br>ancres = $ancres";

$header = '<html><body style="color:#505D6E;">
<p style="background:#505D6E;font-size:16px;padding:20px;padding-bottom:30px;color:#FFFFFF;line-height:32px;font-family:Helvetica, Arial, sans-serif;font-weight:normal;">
<a href="http://www.jwreading.com" style="color: #FFFFFF;text-decoration:none">
<img src="http://www.jwreading.com/assets/images/book.png" style="width: 32px; height: 32px;"/>&nbsp;&nbsp;&nbsp;&nbsp;JW Reading - Portion du jour</a></p>
<div style="color:#505D6E;font-size:14px;font-family:Helvetica, Arial, sans-serif;font-weight:normal;" class="dailyrun"><br>';

$footer = '</div><br><p style="background:#505D6E;padding:10px;color:#FFFFFF;font-family:Helvetica, Arial, sans-serif;font-weight:normal;font-size:14px;"><br>
<a href="http://www.jwreading.com/login" style="color: #FFFFFF">Connexion compte personnel</a>
<br><br>JW Reading - 2014</p></body></html>';

$versets .= "<br><br>\r\n\r\n<b>/////////// $langResearchesTitle ///////////</b><br><br>\r\n\r\n$ancres\r\n<br><br>";
$versets = $header."<b>$langReadingSentence $liste_lecture.</b><br><br>\r\n\r\n".$versets.$footer;

//structure : portions_next_week->$semaineSuivante ///	portions_type->1 à 7 /// portions_day->ex : si portions_type=3 les valeurs possibles seront 1, 2 ou 3 /// portions_content -> contenu portion
$versets = mb_convert_encoding($versets, 'HTML-ENTITIES', "UTF-8");
$versets = str_replace("+.",".",$versets);
$versets = str_replace("*.",".",$versets);
$versets = str_replace("+,",",",$versets);
$versets = str_replace("*,",",",$versets);
$versets = str_replace("+ ;"," ;",$versets);
$versets = str_replace("* ;"," ;",$versets);
$versets = str_replace("+","",$versets);
$versets = str_replace("*","",$versets);
$semaine = date("W") + 1;

$nomFichier = $langPath."/".$semaine."dbr11.html";
echo "<br>nom fichier : $nomFichier<br>";
file_put_contents($nomFichier, utf8_encode($versets));

for($i=2;$i<8;$i++) //boucle de 2 à 7 pour envoyer les portions nécessaires pour les différents découpages possibles
{
    $div = (int)($countVerses / $i); //$i représente le nombre de jours à diviser
    $mod = $countVerses % $i;        
    //echo "div = $div /// mod = $mod<br><br>";
    $versets = "";
    $ancres = "";
    $comp = $div + $mod; //on divise en portions égales sauf pour la 1ere portion de la semaine qui a le modulo en plus
    $compteurPortions = 1;
    $first = 1;//quand $first = 1, c'est le premier verset de la portion

    for($k=0;$k<$countVerses;$k++)
    {
        $versets .= $lecture[$k][0];
        $ancres .= $lecture[$k][1];
        $chaine = explode("<input type='hidden' value='", $lecture[$k][0]);
        $substr = substr($chaine[1],0,-2);
        if($first == 1) {$premierVerset = $substr;$first=0;}
        else $dernierVerset = $substr;
        if($k == ($comp - 1)) 
        {
            $versets .= "<br><br>\r\n\r\n<b>/////////// $langResearchesTitle ///////////</b><br><br>\r\n\r\n$ancres<br><br>\r\n";
            $versets = $header."<b>$langReadingSentence $liste_lecture.<br><br>\r\n\r\n$langPortionSentence $premierVerset $langPortionTo $dernierVerset.</b><br><br>\r\n\r\n".$versets.$footer;
            $versets = mb_convert_encoding($versets, 'HTML-ENTITIES', "UTF-8");
			$versets = str_replace("+.",".",$versets);
			$versets = str_replace("*.",".",$versets);
			$versets = str_replace("+,",",",$versets);
			$versets = str_replace("*,",",",$versets);
			$versets = str_replace("+ ;"," ;",$versets);
			$versets = str_replace("* ;"," ;",$versets);
			$versets = str_replace("+","",$versets);
			$versets = str_replace("*","",$versets);
            $nomFichier = $langPath."/".$semaine."dbr".$i.$compteurPortions.".html";
            //echo "<br>nom fichier : $nomFichier ";
            file_put_contents($nomFichier, $versets);
            //file_put_contents($nomFichier, utf8_encode($versets));

            $compteurPortions++;
            $versets = "";
            $ancres = "";
            $comp = $comp + $div;
            $first = 1;
        }
        //echo "<br>i: $i k: $k premierVerset: $premierVerset dernierVerset:$dernierVerset lecture: ".$lecture[$k][0]." ancre: ".$lecture[$k][1];
    }    
}

//echo "<br>increment: $increment";
//echo "<br>versets = $versets ";


/**
* cherche le contenu de la page demandé et instancie le dom et le xpath nécessaires à l'exploration des données //nombre d'arguments variables
* arguments : 
* - lien de la page à aller chercher
* - parmètre optionnel pour préciser si on doit changer le paramètre de positionnement ("true" suffit pour appeler ce paramètre supplémentaire)
*/
function recupJson() 
{
    //variable globale pour savoir si le dom retourné sera au format json ($json == true) ou html ($json == false)
    global $json;
    global $parDebut;
    global $parFin;
    global $lang;

    $json = 1;
    $parDebut = "";
    $parFin = "";
    $forceJson = 0;
    $forceHtml = 0;
    

    ini_set('max_execution_time', 60);
    error_reporting( E_ERROR | E_USER_ERROR );
    ini_set( 'display_errors', true );
    $numargs = func_num_args(); //compter le nombre d'arguments passés

    //par défaut, le 1er argument contient le lien $lien et $pos prend 0
    $arg_list = func_get_args();
    $lien = $arg_list[0];
    $pos = 0; 
    $reference = "content";

    if ($numargs > 1) { //s'il y a plus d'un argument
        if($arg_list[1] == "1") $pos = 1; //si le 2e argument est égal à 1, le positionnement à utiliser lors de ["items"][$pos]["content"] prend 1
        elseif($arg_list[1] == "json") $forceJson = 1;
        elseif($arg_list[1] == "html") $forceHtml = 1;
    }

    //epuration du /en/ si présent
    $return = strstr($lien, $lang);
    if($return !== false)
    {
        $expl = explode ("http://wol.jw.org/$lang", $lien);
        //echo "$expl[0] /// $expl[1] /// ";
        $lien = 'http://wol.jw.org'.$expl[1];
        //echo "<br>passage pour supprimer le /en de lien /// lien = $lien ";
    }

    $portion = file_get_contents($lien); // récupére le contenu de la page  
    if($forceHtml == 1) $portion = false;
    if ($portion === false) //si échec de chargement json, on va retenter mais en html classique
    {
        if($forceJson == 1) //sauf si on veut forcer json, on va retenter l'operation
        {
            //echo "entree dans forceJson<br>";
            $i = 0;
            while($i < 10)
            {
                sleep(5);
                $portion = file_get_contents($lien);
                //echo "i = $i<br>";
                if ($portion === false) $i++;
                else
                {
                    $portion = json_decode($portion, true); //json récupéré et transformé en array
                    $var = $portion["items"][$pos][$reference]; //contenu de la portion
                    $var = mb_convert_encoding($var, 'HTML-ENTITIES', "UTF-8"); 
                    break;
                }
            }
        }
        
        //echo "<br>problème de chargement du lien $lien<br>";
        $expl = explode ('http://wol.jw.org/', $lien);
        //echo "$expl[0] /// $expl[1] /// ";
        $lien = "http://wol.jw.org".$lang."/".$expl[1]; //on ajoute le /en/ pour passer à la version html
        //echo " changement lien $lien ";
        $headers = get_headers($lien,true); //on regarde les en-têtes pour voir la redirection vers l'url "html"
        $location = $headers['location']; //on regarde où mène la redirection
        $expl = explode ('#h=', $location); //dans la page redirigée figure cette valeur "#h="; ce qui suit révèle l'id des paragraphes (en rouge sur le site web de jw) de la recherche (pour ne pas rapatrier tout un article quand seuls qq paragraphes sont concernés)
        if(isset($expl[1])) //s'il y a un bien la valeur "#h="
        {
            $exploded = $expl[1]; //on va disséquer les caractères après cette valeur "#h=" qui révélèront les id de paragraphe de la forme x:w-y:z (ce qui ns intéresse étant x et y)
            $expl = explode(':', $exploded);
            $parDebut = $expl[0]; //on chope le chiffre après "#h=" mais avant ":", ce qui sera le paragraphe de début de sélection
            $par = explode('-', $expl[1]);
            $parFin = $par[1]; //puis on chope le chiffre après le "-" mais avant le prochain ":", ce qui sera le paragraphe de fin de sélection
            //les variables $parDebut et $parFin étant globales, elles seront lues lors du parcours du xpath renvoyé par cette fonction
        }
        //echo " parDebut:$parDebut parFin:$parFin ";
        $var = file_get_contents($lien);
        $json = 0;
    }
    else
    {
        //echo "<br>fct recupJson : passage en json = 1 /// lien = $lien<br>";
        //echo $portion."<br>";
        $portion = json_decode($portion, true); //json récupéré et transformé en array
        //print_r($portion["items"][$pos]);
        $var = $portion["items"][$pos][$reference]; //contenu de la portion
        //echo "<br>lecture : $var";
        $var = mb_convert_encoding($var, 'HTML-ENTITIES', "UTF-8"); //garde le format UTF8 pour éviter d'avoir des problèmes d'accent au parsage xpath
    }
    $dom = new DomDocument;
    $dom->loadHTML($var); //on parse le contenu
    $xpath = new Domxpath($dom);

    return $xpath;  
}



/**
* trouve les recherches de publication associées au verset en cours
* argument : aucun (variables globales utilisées)
*/
function getResearch()

{
	global $versetSpan, $xpath, $numeroVerset, $numeroChapitre, $versets, $verset, $ancres, $ancre, $json, $parDebut, $parFin, $lang;

	$link = $xpath->query('./a',$versetSpan)->item(0); //recherche du lien a href               
	if($link != null)  //si une réponse est retournée, c'est qu'il existe un lien sur le numéro de verset, renvoyant à des recherches de publications
	{
		$href = $link->getAttribute('href');            
		//echo "href = $href /// ";
		$url_index = "http://wol.jw.org".$href;
		$xpath_recherche = recupJson($url_index); //url menant au(x) lien(s) de recherche d'un verset
		//echo "json = $json /// lien de recherche href = $url_index<br>";
		//recherches : class='sx'                    
		$query = $xpath_recherche->query("//p[@class='sx']");
		//print_r($query);

		foreach ( $query as $child ) 
		{
			$result = $child->textContent;
			echo "<br>result: $result ";
			$liens_recherche = $xpath_recherche->query('./a',$child);
			//print_r($liens_recherche);
			$i = 0;
			$recherche = "";
			$refVerset = "";
			foreach($liens_recherche as $lien_recherche)
			{
				$lien = $lien_recherche->getAttribute('href');
				$reference = $lien_recherche->textContent;
                //echo " lien = $lien reference:$reference ";
				$url_recherche = "http://wol.jw.org".$lien; 
                //echo " substr:".substr($lien, 0, 3)." lang:$lang ";
                if(substr($lien, 0, 3) == $lang) {
                    //echo " yes !! ";
                    $lien_new = substr($lien, 3);
                    //echo " lien_new = $lien_new ";
                    $url_recherche = "http://wol.jw.org".$lien_new;
                }
				if($i == 0) //1er lien : lien vers le verset lui mm
				{                            
					$refVerset = $numeroChapitre.":".$numeroVerset;
				}
				else //les autres liens : les recherches sur les versets
				{                           
					$xpath_verset = recupJson($url_recherche); //url de la recherche 				
					//echo "json = $json /// lien final = $url_recherche ";
					if($json == 1){ //si le xpath retourné est bien issu d'un json
						//echo "longueur tableau : ".$xpath_verset->query("//p[@class='sb']")->length." ";
                        if($xpath_verset->query("//p[@class='sb']")->length == 0) {
                            //echo " ************ WTF ??? ************ ";
                            //print_r($xpath_verset);

                            //la recherche a partiellement échoué parce que le tableau retourné est vide
                            //on retente donc en html
                            //echo " url_recherche:$url_recherche ";
                            $xpath_verset = recupJson($url_recherche, "html");
                            htmlXpath($parDebut, $parFin, $xpath_verset);
                            //echo "  ************ 2e passage => HTML  ************ ";
                        }
						$affich = "";
						//cumul des paragraphes de recherche (quand il y en a plusieurs)
						foreach($xpath_verset->query("//p[@class='sb']") as $key=>$value)
						{
							$affich .= $xpath_verset->query("//p[@class='sb']")->item($key)->textContent." "; 
						}
						echo "$affich<br>";
					}
					else // sinon c'est un xpath issu d'un html
					{
                        htmlXpath($parDebut, $parFin, $xpath_verset);
					}
					//echo "<b>$reference :</b> $affich<br>";
					if($affich != "" | $affich != null) 
					{
						$reference = mb_convert_encoding($reference, 'HTML-ENTITIES', "UTF-8");
						$reference = preg_replace( "#(^(&nbsp;|\s)+|(&nbsp;|\s)+$)#", "", $reference ); //elimination de blancs ou d'espaces éventuels en début de chaine

						$dernierCaractere = substr($reference, -1);
						if($dernierCaractere == "," | $dernierCaractere == ";") $reference = substr($reference, 0, -1); //verification si le dernier caractère n'est pas à afficher
						$recherche .= "<br><b>$reference</b> : $affich<br>";
					}
				}
				$i++;
			}
			//echo "<br>";
		}//fin foreach ( $query as $child ) 
		//echo "<br>";
		if($recherche != "") //si la recherche renvoie à une référence présente sur le site jw.org (pour éviter les publications anciennes qui ne sont pas en ligne)
		{
			//chercher le numéro de verset ayant une recherche dans le contenu du verset
			if($numeroVerset == 1) //si on est dans le 1er verset d'un chapitre, il faut prendre la valeur du chapitre pour créer l'ancre
			{
				$param = $numeroChapitre;
				//$verset = $langChapter." ".$verset;
			}
			else //sinon le numéro de verset est correct
			{
				$param = $numeroVerset;                        
			}
			$param = trim($param);
			$chaine = explode($param, $verset);
			$chaine[0] = $chaine[0]."<a id='c".$numeroChapitre."v".$numeroVerset."' href='#recherchec".$numeroChapitre."v".$numeroVerset."'>".$param."</a>";//création du lien menant vers l'ancre
			$verset = implode("",$chaine);

			$ancre = "<br>\r\n<div id='recherchec".$numeroChapitre."v".$numeroVerset."'><b><a href='#c".$numeroChapitre."v".$numeroVerset."'>$refVerset </a></b> $recherche</div>";
			$ancres .= "<br>\r\n<div id='recherchec".$numeroChapitre."v".$numeroVerset."'><b><a href='#c".$numeroChapitre."v".$numeroVerset."'>$refVerset </a></b> $recherche</div>"; //création de l'ancre et ajout de la référence et de la recherche
		}//fin if($recherche != "") 
	}//fin if($link != null)
}

/**
* parcours d'un xpath html
* argument : paragraphe début, paragraphe fin, xpath
*/
function htmlXpath($parDebut, $parFin, $xpath_verset)
{
    $nbElem = $parFin - $parDebut; //nb de paragraphes de la sélection(recherche en rouge sur le site web)
    $parcours = array(); //tableau qui recueillera les id de paragraphe à garder
    for($nb = 0;$nb <= $nbElem;$nb++)
    {
        $par = $parDebut + $nb;
        $parcours[$nb] = "p".$par; 
    }
    $pieces = $xpath_verset->query("//div[@id]"); //parcours de tous les div ayant un id
    $long = $xpath_verset->query("//div[@id]")->length;
    //echo " longueur:$long ";
    $affich = "";
    //print_r($parcours);
    foreach($pieces as $piece)
    {
        //echo " "; print_r($piece);
        $id = $piece->getAttribute('id');
        //echo " id = $id parcours = $parcours<br>";
        if(in_array($id, $parcours)) { 
            $affich .= $piece->textContent; //on garde seulement ceux correspondant ayant une valeur contenue dans le tableau $parcours (qui est la sélection des paragraphes de la recherche en cours)     
            //echo " yes !! ";
        }
    }
    echo " affich:$affich<br>";
}

?>

