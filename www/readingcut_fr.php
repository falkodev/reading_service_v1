#!/usr/local/bin/php
<html>
    <head>
        <meta charset="utf-8"> 
    </head>
</html>
<?php

$temps_deb = microtime(true);
//$path = dirname(dirname(dirname(__FILE__))); //definition du dossier racine (donc /www/ dans la config actuelle)

//paramètres de langue

// $lang = "/en";
// $langUrl = "r1/lp-e/";
// $langResearchesTitle = "Publications researches";
// //$langPath = $path."/www/portions_en_test";
// $langPath = "portions_en";
// $langReadingSentence = "This week's reading is";
// $langPortionSentence = "Today's portion goes from";
// $langPortionTo = "to";
// $langChapter = "Chapter";

// $lang = "/fr";
// $langUrl = "r30/lp-f/";
// $langResearchesTitle = "Recherches de publications";
// //$langPath = $path."/www/portions_fr_test";
// $langPath = "portions_fr";
// $langReadingSentence = "La lecture pour cette semaine est";
// $langPortionSentence = "La portion pour aujourd'hui va de";
// $langPortionTo = "à";
// $langChapter = "Chapitre";

$lang = "/ro";
$langUrl = "r34/lp-m/";
$langResearchesTitle = "Cercetare de publicaţii";
//$langPath = $path."/www/portions_ro";
$langPath = "portions_ro";
$langReadingSentence = "Citirea pentru această săptămână este";
$langPortionSentence = "Partea care este de citit pentru astăzi este de la";
$langPortionTo = "la";
$langChapter = "Capitol";

//fin paramètres de langue

//appel du fichier de traitement
include('readingcut.php');

$temps_fin = microtime(true);
echo "<br>duree execution: ".round($temps_fin - $temps_deb)." secondes<br><br><br>";

?>

