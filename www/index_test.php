<?php 
ini_set('session.gc_maxlifetime', 1800); 
session_unset();     // unset $_SESSION variable for the run-time 
session_start(); 
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
include("../app/utils.php");
$tab_lang = include('../app/lang/fr.php');

/*$uri = $_SERVER['REQUEST_URI'];
//echo "<script>alert('uri: $uri');</script>";

//router*/
$menu = "home";
//echo "<script>alert('menu: $menu');</script>";
/*
if($uri == '/' || $uri == '' || $uri == '/index.php') {$tab_lang=getLang();}
else if($uri == '/fr/' || $uri == '/fr') $tab_lang = include('../app/lang/fr.php');
else if($uri == '/en/' || $uri == '/en') $tab_lang = include('../app/lang/en.php');
else if($uri == '/ro/' || $uri == '/ro') $tab_lang = include('../app/lang/ro.php');
else if($uri == '/login' || $uri == '/subscribe' || $uri == '/faq' || $uri == '/contact')
{
    $tab_lang=getLang();
    $menu = trim(str_replace("/","",$uri));
}
else if($uri == '/unsubscribe')
{
    header('Location: /unsubscribe.php');exit();
}
else if($uri == '/test')
{
    header('Location: /index_test.php');exit();
}
//else if($uri == '/jwreading/www/') {$tab_lang=getLang();} //special localhost 
//else header('Location: /error.php');
else { header("HTTP/1.0 404 Not Found");header('Location: /error.php'.$uri);exit();}
//fin router*/


//affichage de la page demandée (par défaut #home)
echo '<style type="text/css">#home{display:block;}</style>';
?>
<!DOCTYPE html>
<html lang="<?=__('language')?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport"    content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author"      content="Sergey Pozhilov (GetTemplate.com)">
    
    <title>JW Reading</title>
    <!--<link rel="shortcut icon" href="assets/images/gt_favicon.png">-->
    <link rel="icon" href="assets/images/book.png">
    
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="assets/css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- Fonts -->
    <link href="assets/css/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Wire+One' rel='stylesheet' type='text/css'>
     <!-- World map css -->
    <link rel="stylesheet" type="text/css" media="screen,projection" href="assets/css/cssmap-continents/cssmap-continents.css" />
    <!-- Custom styles -->
    <link rel="stylesheet" href="assets/css/magister.css">
   
    <!-- js scripts -->
    <script src="assets/js/jquery-1.11.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/modernizr.custom.72241.js"></script>
    <!-- World map js-->
    <script type="text/javascript" src="http://cssmapsplugin.com/4/jquery.cssmap.js"></script> 
    <!-- // <script src="assets/js/jquery.cssmap.js"></script> -->
    <!-- Custom template scripts -->
    <script src="assets/js/magister.js"></script>

    
    <script>  
    var lang = $('html').attr('lang');
    
    function emailSent()
    {
        $('.section:visible').hide();
        $('a', '.mainmenu').removeClass( 'active' );
        $('#contact').addClass( 'active' );
        $('#contact').show();
        $('#contactForm').hide();
        $('#confirmContact').show();
        //console.log();
    }
    </script>
</head>

<!-- use "theme-invert" class on bright backgrounds, also try "text-shadows" class -->
<body class="text-shadows">
    <!-- <section class="section"> -->
        <!-- <div id="loader" style="position: fixed; top:0; left:0; width:100%; height: 100%; background: url('assets/images/ajax-loader.gif') no-repeat center center #505D6E"><h1 class="title text-center">Enregistrement en base de données</h1></div> -->        
    <!-- </section> -->
<nav class="mainmenu menu">
    <div class="container">
        <div class="dropdown">
            <button type="button" class="navbar-toggle" data-toggle="dropdown"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                <li><a href="#home" <?if($menu=="home"){?>class="active"<?}?>><?=__('menu_home')?></a></li>
                <!--<li><a href="#info"><?=__('menu_info')?></a></li>-->
                <li><a href="#subscribe" <?if($menu=="subscribe"){?>class="active"<?}?>><?=__('menu_subscribe')?></a></li>
                <li><a href="#faq" <?if($menu=="faq"){?>class="active"<?}?>><?=__('menu_faq')?></a></li>
            </ul>
            <ul class="dropdown-menu" id="menu2" role="menu" aria-labelledby="dLabel">
                <!--<li><a href="#faq"><?=__('menu_faq')?></a></li>-->
                <li><a href="#contact" <?if($menu=="contact"){?>class="active"<?}?>><?=__('menu_contact')?></a></li>
                <li><a href="#login" class="menu-orange" id="menuLogin" <?if($menu=="login"){?>class="active"<?}?>><?=__('menu_login')?></a></li> 
                <ul class="displayNone menu-ul">
                    <!--<li class="menu-bullet"><a href="#changePass" class="menu-orange menu-height">Modifier mot de passe</a></li>-->
                    <li class="menu-bullet"><a href="#logout" class="menu-orange menu-height" id="logout"><?=__('menu_logout')?></a></li> 
                </ul>
            </ul>
        </div>
    </div>
</nav>


<!-- Main (Home) section -->
<section class="section background" id="home"><!-- bg -->
    <div class="container">
        <div class="row">
            <div id="topbar"></div>
            <div id="rightbar"></div>
            <div id="bottombar"></div>
            <div id="leftbar"></div>
             <div class="col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1 col-sm-8 col-sm-offset-4 text-center contentBox" id="content">
                <div id="selectedLang">
                    <?php if(__('language') == 'fr') echo 'FR&nbsp;|&nbsp;<a class="linkLang">EN</a>&nbsp;|&nbsp;<a class="linkLang">RO</a>'; 
                    else if(__('language') == 'ro') echo '<a class="linkLang">FR</a>&nbsp;|&nbsp;<a class="linkLang">EN</a>&nbsp;|&nbsp;RO'; 
                    else echo '<a class="linkLang">FR</a>&nbsp;|&nbsp;EN&nbsp;|&nbsp;<a class="linkLang">RO</a>';?>
                </div>
                <!-- Site Title, your name, HELLO msg, etc. -->
                <h1 class="title">JW Reading</h1>
                <h2 class="subtitle"><?=__('home_text_1')?><br><?=__('home_text_2')?></h2>
                    <p class="menu">
                        <a href="#faq" class="btn btn-default btn-lg"><?=__('home_btn_info')?></a>&nbsp;&nbsp;
                        <a href="#subscribe" class="btn btn-default btn-lg"><?=__('home_btn_subscribe')?></a>
                    </p>
             </div> <!-- /col -->                 
        </div> <!-- /row -->
    </div>
</section>
    
<!-- Second (Fonctionnement) section -->
<!--<section class="section" id="info">
    <div class="container">
        <h2 class="text-center title"><?=__('info_title')?></h2>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 text-center">  
                <p><?=__('info_content')?></p>
            </div>
        </div>
    </div>
</section>-->

<!-- Third (Inscription) section -->
<section class="section" id="subscribe">
    <div class="container">
        <h2 class="text-center title"><?=__('subscribe_title')?></h2>
        <div class="loader" id="loaderSubscribe">
            <img src='assets/images/ajax-loader.gif'>
        </div>
        <div class="row">
            <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post" id="subscribeForm">
            <!-- 1er ecran du formulaire -->
            <div class="col-sm-8 col-sm-offset-2 text-center" id="subscribeFirst">  
                <p><?=__('subscribe_text_1')?></p>
                <br>
                <p><?=__('subscribe_text_2')?></p>
                <div class='input-group'><span class='input-group-addon'><i class='fa fa-envelope'></i></span><input id="email" name="email" type='text' class='form-control' placeholder='<?=__("placeholder_email")?>'></div><div id="emailValidate" class="validate"></div><br><br>
                <p><?=__('subscribe_text_3')?></p>
                <div class='input-group'><span class='input-group-addon'><i class='fa fa-user'></i></span><input type='text' name="name" class='form-control' placeholder='<?=__("placeholder_name")?>'></div><br><br><br>
                <!--<button id="cancelFirstBtn" class="btn btn-lg subscribeCancel btn-inverse">Retour</button>-->
                <button id="subscribeFirstBtn" class="btn btn-lg btn-default"><?=__('btn_next')?></button>
            </div>
            
            <!-- 2e ecran du formulaire -->
            <div class="col-sm-8 col-sm-offset-2 text-center" id="subscribeSecond">        
                <table class="table">
                    <tbody>
                      <tr><th style="border:0;"><div><?=__('subscribe_text_4')?></div></th>
                          <th style="border:0;"><div><?=__('subscribe_text_5')?><i class="fa fa-question-circle" id="help" data-toggle="popover" data-title="<?=__('popover_title')?> <button type='button' class='close' onclick='$(&quot;#help&quot;).popover(&quot;hide&quot;);'>&times;</button>" data-placement="right auto" data-content='<?=__('popover_content')?>' data-html="true"></i></div></th>
                      </tr>
                      <tr><th style="border:0;" colspan="2"><div id="tip1" class="validate tip"><span class="closeTip"><i class="fa fa-times"></i></span><?=__('subscribe_tip_1')?></div></th></tr>
                 <tr><td>         
                <!-- lundi --> 
                <div class="onoffswitch">
                    <input type="checkbox" name="toggleDay1" class="onoffswitch-checkbox subscribeChk" id="toggleDay1" value="1">
                    <label class="onoffswitch-label" for="toggleDay1">
                        <span class="onoffswitch-inner" id="day1"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div></td><td><input type="radio" id="radio1" class="radioReading" name="firstDayRadio" value="1"></td></tr>
                <tr><td>
                <!-- mardi --> 
                <div class="onoffswitch">
                    <input type="checkbox" name="toggleDay2" class="onoffswitch-checkbox subscribeChk" id="toggleDay2" value="1">
                    <label class="onoffswitch-label" for="toggleDay2">
                        <span class="onoffswitch-inner" id="day2"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div></td><td><input type="radio" id="radio2" class="radioReading" name="firstDayRadio" value="2"></td></tr>
                 <tr><td>
                <!-- mercredi -->           
                <div class="onoffswitch">
                    <input type="checkbox" name="toggleDay3" class="onoffswitch-checkbox subscribeChk" id="toggleDay3" value="1">
                    <label class="onoffswitch-label" for="toggleDay3">
                        <span class="onoffswitch-inner" id="day3"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div></td><td><input type="radio" id="radio3" class="radioReading" name="firstDayRadio" value="3"></td></tr>
                <tr><td>
                <!-- jeudi -->           
                <div class="onoffswitch">
                    <input type="checkbox" name="toggleDay4" class="onoffswitch-checkbox subscribeChk" id="toggleDay4" value="1">
                    <label class="onoffswitch-label" for="toggleDay4">
                        <span class="onoffswitch-inner" id="day4"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div></td><td><input type="radio" id="radio4" class="radioReading" name="firstDayRadio" value="4"></td></tr>
                <tr><td>
                <!-- vendredi --> 
                <div class="onoffswitch">
                    <input type="checkbox" name="toggleDay5" class="onoffswitch-checkbox subscribeChk" id="toggleDay5" value="1">
                    <label class="onoffswitch-label" for="toggleDay5">
                        <span class="onoffswitch-inner" id="day5"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div></td><td><input type="radio" id="radio5" class="radioReading" name="firstDayRadio" value="5"></td></tr>
                <tr><td>
                <!-- samedi -->           
                <div class="onoffswitch">
                    <input type="checkbox" name="toggleDay6" class="onoffswitch-checkbox subscribeChk" id="toggleDay6" value="1">
                    <label class="onoffswitch-label" for="toggleDay6">
                        <span class="onoffswitch-inner" id="day6"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div></td><td><input type="radio" id="radio6" class="radioReading" name="firstDayRadio" value="6"></td></tr>
                <tr><td>
                <!-- dimanche --> 
                <div class="onoffswitch">
                    <input type="checkbox" name="toggleDay7" class="onoffswitch-checkbox subscribeChk" id="toggleDay7" value="1">
                    <label class="onoffswitch-label" for="toggleDay7">
                        <span class="onoffswitch-inner" id="day7"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div></td><td><input type="radio" id="radio7" name="firstDayRadio" class="radioReading" value="7"></td></tr>
                </tbody>
               </table>
                <br><br>   
                <p align="left"><?=__('daily_comment')?></p>
                <div class="onoffswitch">
                <input type="checkbox" name="toggleDailyText" class="onoffswitch-checkbox" id="toggleDailyText" checked>
                <label class="onoffswitch-label" for="toggleDailyText">
                    <span class="onoffswitch-inner" id="dailyText"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
                </div> 

                <br><div id="formValidate" class="validate"></div><br>
                
                <button id="backToFirstBtn" class="btn btn-lg subscribeBack btn-inverse"><?=__('btn_previous')?></button>
                <button id="subscribeSecondBtn" class="btn btn-lg btn-default subscribeSubmit"><?=__('btn_last')?></button>
                <!--<input type="submit" id="subscribeSecondBtn" name="subscribeSecondBtn" class="btn btn-lg subscribeSubmit btn-default" value="<?=__('btn_validation')?>"/>-->
            </div>

            <!-- 3e ecran du formulaire -->
            <div class="col-sm-8 col-sm-offset-2 text-center" id="subscribeThird">
                <div class="row" style="padding-bottom: 5px;">
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" align="left"><?=__('subscribe_reading_lang')?></div>
                    <div class="col-xs-offset-4 col-xs-4 col-sm-offset-4 col-sm-4 col-md-offset-4 col-md-4 col-lg-offset-4 col-lg-4" align="right" style="padding-right: 18px;"><?=__('subscribe_text_lang')?></div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
    					<div class="subscribeBack">
    						<div class="list"><input type="radio" id="radioLangReadingFr" class="checkList radioLangReading" name="radiosLangReading" value="fr" checked><label class="labelList" for="radioLangReadingFr" title="<?=__('lang_fr')?>"><?=__('lang_fr')?><span class="led"></span></label></div><br>
    						<div class="list"><input type="radio" id="radioLangReadingEn" class="checkList radioLangReading" name="radiosLangReading" value="en"><label class="labelList" for="radioLangReadingEn" title="<?=__('lang_en')?>"><?=__('lang_en')?><span class="led"></span></label></div><br>
                            <div class="list"><input type="radio" id="radioLangReadingRo" class="checkList radioLangReading" name="radiosLangReading" value="ro"><label class="labelList" for="radioLangReadingRo" title="<?=__('lang_ro')?>"><?=__('lang_ro')?><span class="led"></span></label></div>
    					</div>
                    </div>
                    <div class="col-sm-offset-4 col-sm-4">
    					<div class="subscribeSubmit">
    						<div class="list"><input type="radio" id="radioLangTextFr" class="checkList radioLangText" name="radiosLangText" value="fr" checked><label class="labelList" for="radioLangTextFr" title="<?=__('lang_fr')?>"><?=__('lang_fr')?><span class="led"></span></label></div><br>
    						<div class="list"><input type="radio" id="radioLangTextEn" class="checkList radioLangText" name="radiosLangText" value="en"><label class="labelList" for="radioLangTextEn" title="<?=__('lang_en')?>"><?=__('lang_en')?><span class="led"></span></label></div><br>
                            <div class="list"><input type="radio" id="radioLangTextRo" class="checkList radioLangText" name="radiosLangText" value="ro"><label class="labelList" for="radioLangTextRo" title="<?=__('lang_ro')?>"><?=__('lang_ro')?><span class="led"></span></label></div>
    					</div>
                    </div>
                </div>   
                <div class="col-sm-12 text-center">
                    <br><?=__('subscribe_time_choice')?>
                    <div id="tip2" class="validate tip" style="margin: 5px -12px;"><span class="closeTip"><i class="fa fa-times"></i></span><?=__('subscribe_tip_2')?></div>
                    <div class="worldmap" id="map-continents">
                     <ul class="continents">
                      <li class="c1"><a href="#africa"><?=__('world_map_africa')?></a></li>
                      <li class="c2"><a href="#asia"><?=__('world_map_asia')?></a></li>
                      <li class="c3"><a href="#australia-and-southern-pacific"><?=__('world_map_australia')?></a></li>
                      <li class="c4"><a href="#europe"><?=__('world_map_europe')?></a></li>
                      <li class="c5"><a href="#north-america"><?=__('world_map_north_america')?></a></li>
                      <li class="c6"><a href="#south-america"><?=__('world_map_south_america')?></a></li>
                     </ul>
                    </div>
                    <div id="addresses">
                        <?php 
                        $query = 'select time_id, time_zone, time_utc, time_cities, sending_id, sending_displayed from timezone join sending_time on time_id = timezone_id order by time_id, sending_id';
                        //echo "<br>query : ".$query;
                        $result = mysqli_query($mysqli, $query);
                        //echo "<br>result : "; print_r($result);
                        $previous_utc = "";
                        $previous_zone = "";
                        $arrClassement = array();
                        while ($row = mysqli_fetch_array($result)) {
                            if($row['time_utc'] != $previous_utc) {
                                if($previous_utc != "")  { //on va fermer un select, donc afficher les horaires dispo classés par ordre
                                    asort($arrClassement);
                                    foreach ($arrClassement as $key=>$value) {										
                                        echo "<option value='".$key."'>".$value."</option>";
                                    }
                                    $arrClassement = array();
                                    echo "</select></div></div></li>"; //pour premier passage, pas besoin de fermer le select et le li
                                }								
                                if($previous_zone != $row['time_zone']) echo "</ul><ul class='list-group'><li id='".$row['time_zone']."' class='list-expl'>".__('world_map_table')."<br><br></li><li id='".$row['time_zone']."' class='list-group-item list-header'><div class='row'><div class='col-lg-2'>".__('world_map_timezone')."</div><div class='col-lg-8'>".__('world_map_cities')."</div><div class='col-lg-2'>".__('world_map_time');
								echo "<li id='".$row['time_zone']."' class='list-group-item list-utc'><div class='row'><div class='col-lg-2'>UTC : ".$row['time_utc']."</div><div class='col-lg-8'>".$row['time_cities']."</div><div class='col-lg-2'><select class='form-control select-utc' data-utc=".$row['time_utc']."><option></option>";
                            }
                            $arrClassement[$row['sending_id']] = $row['sending_displayed'];
                            $previous_utc = $row['time_utc'];
                            $previous_zone = $row['time_zone'];      
                        }
						asort($arrClassement);
						foreach ($arrClassement as $key=>$value) {
							echo "<option value='".$key."'>".$value."</option>"; //dernier passage pour le fuseau UTC +14
						}
                        echo "</select></div></div></li></ul>";
                        ?>
                        <div id="select-phrase"></div>
                    </div>
                    <button id="backToSecondBtn" class="btn btn-lg subscribeBack btn-inverse"><?=__('btn_previous')?></button>
                    <input type="submit" id="subscribeThirdBtn" name="subscribeThirdBtn" class="btn btn-lg subscribeSubmit btn-default" value="<?=__('btn_validation')?>"/>
                </div>
            </div>
            <input type="hidden" name="subscribeChosenTime" id="subscribeChosenTime" value="66"><!-- par défaut, utc 1 et envoi à 6h -->
<!--        <input type="hidden" name="langText" id="langText" value="<?=__('language')?>">
            <input type="hidden" name="langReading" id="langReading" value="<?=__('language')?>"> -->
            <input type="hidden" name="subscribeFormButton" id="subscribeFormButton">
            </form>
            
            <div class="col-sm-8 col-sm-offset-2 text-center" id="subscribeConfirm">  
                <div id="messageConfirm" style="text-align:left"></div>
            </div>
        </div>
    </div>
</section>
    
    <!-- Fourth (Works) section -->
<section class="section" id="faq">
    <div class="container">
        <h2 class="text-center title"><?=__('faq_title')?></h2>
            <div class="row">
                <div class="col-sm-4 col-sm-offset-2">    
                    <div class="questionTitle" id="question3"><h5><strong><i class="fa fa-play"></i> <?=__('faq_question_3_title')?></strong></h5></div>
                    <p class="questionContent" id="answer3"><?=__('faq_question_3_content')?></p> 
                </div>
                <div class="col-sm-4">
                    <div class="questionTitle" id="question4"><h5><strong><i class="fa fa-play"></i> <?=__('faq_question_4_title')?></strong></h5></div>    
                    <p class="questionContent" id="answer4"><?=__('faq_question_4_content')?></p> 
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 col-sm-offset-2">    
                    <div class="questionTitle" id="question5"><h5><strong><i class="fa fa-play"></i> <?=__('faq_question_5_title')?><br></strong></h5></div>
                    <p class="questionContent" id="answer5"><?=__('faq_question_5_content')?></p> 
                </div>
                <div class="col-sm-4">
                    <div class="questionTitle" id="question6"><h5><strong><i class="fa fa-play"></i> <?=__('faq_question_6_title')?><br></strong></h5></div>    
                    <p class="questionContent" id="answer6"><?=__('faq_question_6_content')?></p> 
                </div>
            </div>          
            <div class="row">           
                <div class="col-sm-4 col-sm-offset-2">    
                    <div class="questionTitle" id="question1"><h5><strong><i class="fa fa-play"></i> <?=__('faq_question_1_title')?><br></strong></h5></div>
                    <p class="questionContent" id="answer1"><?=__('faq_question_1_content')?></p>
                </div>
                <div class="col-sm-4">
                    <div class="questionTitle" id="question2"><h5><strong><i class="fa fa-play"></i> <?=__('faq_question_2_title')?><br></strong></h5></div>    
                    <p class="questionContent" id="answer2"><?=__('faq_question_2_content')?></p> 
                </div>
            </div>
            <div class="row">           
                <div class="col-sm-4 col-sm-offset-2">    
                    <div class="questionTitle" id="question7"><h5><strong><i class="fa fa-play"></i> <?=__('faq_question_7_title')?><br></strong></h5></div>
                    <p class="questionContent" id="answer7"><?=__('faq_question_7_content')?></p>
                </div>              
            </div>
            <div class="row">
                <div class="col-sm-10 col-sm-offset-2">
                    <h5><strong><?=__('faq_legal_title')?><br></strong></h5>    
                    <p><?=__('faq_legal_content')?></p>                 
                </div>
            </div>
    </div>
</section>

<!-- Fifth (Contact) section -->
<section class="section" id="contact">
    <div class="container">
        <h2 class="text-center title"><?=__('contact_title')?></h2>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 text-center" id="">
                <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post" id="contactForm" name="contactForm">           
                    <p><?=__('contact_name')?></p>
                    <div class='input-group'><span class='input-group-addon'><i class='fa fa-user'></i></span><input type='text' id="nameContact" name="nameContact" class='form-control' placeholder='<?=__("contact_name")?>'></div><br><br>                
                    <p><?=__('contact_email')?></p>
                    <div class='input-group'><span class='input-group-addon'><i class='fa fa-envelope'></i></span><input id="emailContact" name="emailContact" type='text' class='form-control' placeholder='<?=__("contact_email")?>'></div><div id="emailContactValidate" class="validate"></div><br><br>
                    <p><?=__('contact_msg')?></p>
                    <div class='input-group'><span class='input-group-addon'><i class='fa fa-pencil'></i></span><textarea id="msgContact" name="msgContact" rows='5' class='form-control' placeholder='<?=__("contact_msg")?>'></textarea></div><div id="msgContactValidate" class="validate"></div><br>
                    <br><button id="sendContactForm" class="btn btn-lg btn-default"><?=__('contact_btn_send')?></button>
                </form>
                <div id="confirmContact">
                    <p style="text-align:center"><?=__('contact_thanks_msg')?></p>
                    <button id="backContact" class="btn btn-lg btn-default"><?=__('btn_back')?></button>
                </div>  
            </div>   
        </div>
    </div>
</section>

<!-- Sixth (Login) section -->
<section class="section background" id="login">
    <div class="container">
        <div class="row">       
            <div class="col-sm-8 col-sm-offset-2 text-center contentBox" id="contentLogin">
            <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post" id="loginForm">
                <div id="loginDiv">
                    <h2 class="text-center title"><?=__('login_title')?></h2> 
                    <div class='input-group loginForm col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2'>
                        <span class='input-group-addon loginAddon'><i class='fa fa-user fa-2' style="width:12px;"></i></span>
                        <input id="loginInput" name="loginInput" class="loginForm form-control" type='text' placeholder='<?=__("login_placeholder_email")?>' required>
                    </div><!--<div id="emailValidate" class="validate"></div>-->

                    <div class='input-group loginForm col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2'>
                        <span class='input-group-addon loginAddon'><i class='fa fa-lock fa-2' style="width:12px;"></i></span>
                        <input id="pwdInput" name="pwdInput" class="loginForm form-control" type='password' placeholder='<?=__("login_placeholder_pwd")?>' required>
                        <span class='input-group-addon loginAddon connexionBtn' id="loginBtn"><i class='fa fa-sign-in' style="width:12px;"></i></span>
                    </div><!--<div id="pwdValidate" class="validate"></div>-->
                    <div id="loginValidate" class="validate"></div>
                    <!--<input type="hidden" name="loginFormButton" id="loginFormButton">-->
                    <br><br>
                    <a id="forgottenPass"><?=__('login_forgotten_pwd')?>?</a>
                </div>
                <input type="hidden" id="msg" name="msg">
            </form>
                <div id="forgottenDiv">
                    <h2 class="text-center title"><?=__('login_forgotten_pwd')?></h2> 
                    <?=__('login_forgotten_pwd_text')?><br><br>
                    <div class='input-group loginForm col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2'>
                        <span class='input-group-addon loginAddon'><i class='fa fa-user fa-2' style="width:12px;"></i></span>
                        <input id="forgottenInput" name="forgottenInput" class="loginForm form-control" type='text' placeholder='<?=__("login_placeholder_email")?>' required>
                        <span class='input-group-addon loginAddon connexionBtn' id="forgottenBtn"><i class='fa fa-sign-in' style="width:12px;"></i></span>
                    </div>
                    <div id="forgottenValidate" class="validate"></div><br><br>
                    <a id="backToLogin"><?=__('btn_back')?></a>
                </div>
            </div>  
        </div>
    </div>
</section>
  
<!-- Seventh (Account) section -->
<section class="section" id="account">
    <div class="container">
        <h2 class="text-center title"><?=__('account_title')?></h2>
        <div class="loader" id="loaderAccount">
            <p><?=__('loading')?></p>
            <img src='assets/images/ajax-loader.gif'>
        </div>
        <div class="row">
            <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post" id="accountForm">
            <!-- 1er ecran du formulaire -->
            <div class="col-sm-8 col-sm-offset-2 text-center" id="accountFirst">    
                <p><?=__('account_text_1')?></p>
                <br>
                <p><?=__('account_text_2')?></p>
                <div class='input-group'><span class='input-group-addon'><i class='fa fa-envelope'></i></span><input id="emailAccount" name="emailAccount" type='text' class='form-control accountInput' placeholder='<?=__("placeholder_email")?>' value='<?php echo htmlspecialchars($_SESSION["email"]); ?>'></div><div id="emailAccountValidate" class="validate"></div><br><br>
                <p><?=__('account_text_3')?></p>
                <div class='input-group'><span class='input-group-addon'><i class='fa fa-user'></i></span><input type='text' id="nameAccount" name="nameAccount" class='form-control accountInput' placeholder='<?=__("placeholder_name")?>' value='<?php echo htmlspecialchars($_SESSION["name"]); ?>'></div><br><br>
                <p><?=__('account_text_4')?></p>
                <div class='input-group' id='divPassAccount'><span class='input-group-addon'><i class='fa fa-unlock-alt'></i></span><input type='password' id="passAccount" name="passAccount" class='form-control accountInput' readonly placeholder='<?=__("placeholder_new_pwd")?>' value='<?php echo htmlspecialchars($_SESSION["password"]); ?>'><span class="input-group-addon chgPassword" id="changePassword"><?=__('account_chg_pwd')?></span></div>
                
                <p class="passConfirm" id="passConfirmTitle"><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=__('account_confirm_pwd')?>
                <button id="cancelChangePassword" class="btn btn-sm pull-right chgPassword"><?=__('account_cancel_pwd')?></button></p>
                <div class='input-group passConfirm' id='divPassAccountConfirm'><span class='input-group-addon'><i class='fa fa-unlock-alt'></i></span><input type='password' id="passAccountConfirm" name="passAccountConfirm" class='form-control accountInput' readonly placeholder='<?=__('account_confirm_pwd')?>' value=''><span class="input-group-addon btn-orange chgPassword" id="recordChangePassword"><?=__('account_save_pwd')?></span></div>                
                <div id="changePasswordValidate" class="validate"></div>                
                <br> 
                <button id="accountFirstBtn" class="btn btn-lg btn-default"><?=__('btn_next')?></button>
            </div>
            
            <!-- 2e ecran du formulaire -->
            <div class="col-sm-8 col-sm-offset-2 text-center" id="accountSecond">
                <input type="hidden" id="hiddenAccount1" value=<?php echo $_SESSION['day1']?>>
                <input type="hidden" id="hiddenAccount2" value=<?php echo $_SESSION['day2']?>>
                <input type="hidden" id="hiddenAccount3" value=<?php echo $_SESSION['day3']?>>
                <input type="hidden" id="hiddenAccount4" value=<?php echo $_SESSION['day4']?>>
                <input type="hidden" id="hiddenAccount5" value=<?php echo $_SESSION['day5']?>>
                <input type="hidden" id="hiddenAccount6" value=<?php echo $_SESSION['day6']?>>
                <input type="hidden" id="hiddenAccount7" value=<?php echo $_SESSION['day7']?>>
                <input type="hidden" id="hiddenAccountFirstDay" value=<?php echo $_SESSION['firstDay']?>>
                <input type="hidden" id="hiddenAccountDailyComment" value=<?php echo $_SESSION['dailyComment']?>>
                
                <div id="formDelayedChangeValidate" class="validate">
                <?=__('account_delayed_chg_msg')?>
                </div>
                <table class="table">
                    <tbody>
                      <tr><th style="border:0;"><div><?=__('account_text_5')?></div></th>
                          <th style="border:0;"><div><?=__('account_text_6')?>&nbsp;&nbsp;<i class="fa fa-question-circle" id="helpAccount" data-toggle="popover" data-title="<?=__('popover_title')?> <button type='button' class='close' onclick='$(&quot;#helpAccount&quot;).popover(&quot;hide&quot;);'>&times;</button>" data-placement="right auto" data-content='<?=__('popover_content')?>' data-html="true"></i></div></th>
                      </tr>
                 <tr><td>         
                <!-- lundi -->                
                <div class="onoffswitch">
                    <input type="checkbox" name="toggleAccountDay1" class="onoffswitch-checkbox accountSwitch" id="toggleAccountDay1" value="1">
                    <label class="onoffswitch-label" for="toggleAccountDay1">
                        <span class="onoffswitch-inner" id="day1"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div></td><td><input type="radio" id="radioAccount1" name="firstDayRadio" value="1" class="accountSwitch"></td></tr>
                <tr><td>
                <!-- mardi --> 
                <div class="onoffswitch">
                    <input type="checkbox" name="toggleAccountDay2" class="onoffswitch-checkbox accountSwitch" id="toggleAccountDay2" value="1">
                    <label class="onoffswitch-label" for="toggleAccountDay2">
                        <span class="onoffswitch-inner" id="day2"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div></td><td><input type="radio" id="radioAccount2" name="firstDayRadio" value="2" class="accountSwitch"></td></tr>
                 <tr><td>
                <!-- mercredi -->           
                <div class="onoffswitch">
                    <input type="checkbox" name="toggleAccountDay3" class="onoffswitch-checkbox accountSwitch" id="toggleAccountDay3" value="1">
                    <label class="onoffswitch-label" for="toggleAccountDay3">
                        <span class="onoffswitch-inner" id="day3"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div></td><td><input type="radio" id="radioAccount3" name="firstDayRadio" value="3" class="accountSwitch"></td></tr>
                <tr><td>
                <!-- jeudi -->           
                <div class="onoffswitch">
                    <input type="checkbox" name="toggleAccountDay4" class="onoffswitch-checkbox accountSwitch" id="toggleAccountDay4" value="1">
                    <label class="onoffswitch-label" for="toggleAccountDay4">
                        <span class="onoffswitch-inner" id="day4"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div></td><td><input type="radio" id="radioAccount4" name="firstDayRadio" value="4" class="accountSwitch"></td></tr>
                <tr><td>
                <!-- vendredi --> 
                <div class="onoffswitch">
                    <input type="checkbox" name="toggleAccountDay5" class="onoffswitch-checkbox accountSwitch" id="toggleAccountDay5" value="1">
                    <label class="onoffswitch-label" for="toggleAccountDay5">
                        <span class="onoffswitch-inner" id="day5"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div></td><td><input type="radio" id="radioAccount5" name="firstDayRadio" value="5" class="accountSwitch"></td></tr>
                <tr><td>
                <!-- samedi -->           
                <div class="onoffswitch">
                    <input type="checkbox" name="toggleAccountDay6" class="onoffswitch-checkbox accountSwitch" id="toggleAccountDay6" value="1">
                    <label class="onoffswitch-label" for="toggleAccountDay6">
                        <span class="onoffswitch-inner" id="day6"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div></td><td><input type="radio" id="radioAccount6" name="firstDayRadio" value="6" class="accountSwitch"></td></tr>
                <tr><td>
                <!-- dimanche --> 
                <div class="onoffswitch">
                    <input type="checkbox" name="toggleAccountDay7" class="onoffswitch-checkbox accountSwitch" id="toggleAccountDay7" value="1">
                    <label class="onoffswitch-label" for="toggleAccountDay7">
                        <span class="onoffswitch-inner" id="day7"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div></td><td><input type="radio" id="radioAccount7" name="firstDayRadio" value="7" class="accountSwitch"></td></tr>
                </tbody>
               </table>
                <br><br>   
                <p align="left"><?=__('daily_comment')?></p>
                <div class="onoffswitch">
                <input type="checkbox" id="toggleAccountDailyText" name="toggleAccountDailyText" class="onoffswitch-checkbox accountSwitch">
                <label class="onoffswitch-label" for="toggleAccountDailyText">
                    <span class="onoffswitch-inner" id="dailyText"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
                </div> 

                <br><div id="formAccountValidate" class="validate"></div><br>
                
                <button id="backToAccountFirstBtn" class="btn btn-lg subscribeBack btn-inverse"><?=__('btn_previous')?></button>
                <button id="accountSecondBtn" class="btn btn-lg subscribeSubmit btn-default"><?=__('btn_last')?></button>
                <!-- <input type="submit" id="accountSecondBtn" name="accountSecondBtn" class="btn btn-lg subscribeSubmit btn-default" value="<?=__('btn_save')?>" disabled/> -->
            </div>

            <!-- 3e ecran du formulaire -->
            <div class="col-sm-8 col-sm-offset-2 text-center" id="accountThird">
                <input type="hidden" id="hiddenReadingLang" name="hiddenReadingLang" value=<?php echo $_SESSION['readingLang']?>>
                <input type="hidden" id="hiddenCommentLang" name="hiddenCommentLang" value=<?php echo $_SESSION['commentLang']?>>
                <input type="hidden" id="hiddenTimeDisplayed" value=<?php echo $_SESSION['time_displayed']?> name="accountTimeDisplayed">
                <input type="hidden" id="hiddenTimeCities" value="" name="accountTimeCities"> <!-- value remplie en js car pbl de virgules avec les villes -->
                <input type="hidden" id="hiddenTimeUTC" value=<?php echo $_SESSION['time_utc']?> name="accountTimeUTC">
                <input type="hidden" id="hiddenTimeZone" value=<?php echo $_SESSION['time_zone']?> name="accountTimeZone">
                <input type="hidden" id="hiddenTimeId" value=<?php echo $_SESSION['time_id']?> name="accountTimeId">

                <div class="row" style="padding-bottom: 5px;">
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" align="left"><?=__('subscribe_reading_lang')?></div>
                    <div class="col-xs-offset-4 col-xs-4 col-sm-offset-4 col-sm-4 col-md-offset-4 col-md-4 col-lg-offset-4 col-lg-4" align="right" style="padding-right: 18px;"><?=__('subscribe_text_lang')?></div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="subscribeBack">
                            <div class="list"><input type="radio" class="checkList radioLangReading" name="radiosAccountLangReading" id="radioAccountLangReadingFr" value="fr"><label class="labelList" data-value="fr" for="radioAccountLangReadingFr" data-element="radiosAccountLangReading" title="<?=__('lang_fr')?>"><?=__('lang_fr')?><span class="led"></span></label></div><br>
                            <div class="list"><input type="radio" class="checkList radioLangReading" name="radiosAccountLangReading" id="radioAccountLangReadingEn" value="en"><label class="labelList" data-value="en" for="radioAccountLangReadingEn" data-element="radiosAccountLangReading" title="<?=__('lang_en')?>"><?=__('lang_en')?><span class="led"></span></label></div><br>
                            <div class="list"><input type="radio" class="checkList radioLangReading" name="radiosAccountLangReading" id="radioAccountLangReadingRo" value="ro"><label class="labelList" data-value="ro" for="radioAccountLangReadingRo" data-element="radiosAccountLangReading" title="<?=__('lang_ro')?>"><?=__('lang_ro')?><span class="led"></span></label></div>
                        </div>
                    </div>
                    <div class="col-sm-offset-4 col-sm-4">
                        <div class="subscribeSubmit">
                            <div class="list"><input type="radio" class="checkList radioLangText" name="radiosAccountLangText" id="radioAccountLangTextFr" value="fr"><label class="labelList" data-value="fr" for="radioAccountLangTextFr" data-element="radiosAccountLangText" title="<?=__('lang_fr')?>"><?=__('lang_fr')?><span class="led"></span></label></div><br>
                            <div class="list"><input type="radio" class="checkList radioLangText" name="radiosAccountLangText" id="radioAccountLangTextEn" value="en"><label class="labelList" data-value="en" for="radioAccountLangTextEn" data-element="radiosAccountLangText" title="<?=__('lang_en')?>"><?=__('lang_en')?><span class="led"></span></label></div><br>
                            <div class="list"><input type="radio" class="checkList radioLangText" name="radiosAccountLangText" id="radioAccountLangTextRo" value="ro"><label class="labelList" data-value="ro" for="radioAccountLangTextRo" data-element="radiosAccountLangText" title="<?=__('lang_ro')?>"><?=__('lang_ro')?><span class="led"></span></label></div>
                        </div>
                    </div>
                </div>   
                <div class="col-sm-12 text-center">
                    <br><?=__('subscribe_time_choice')?>
                    <div id="tipAccount" class="validate tip" style="margin: 5px -12px;"><span class="closeTip"></span><?=__('account_tip_1').$_SESSION['time_displayed'].__('account_tip_2')?>UTC<?=$_SESSION['time_utc'].__('account_tip_3')?> (<?=$_SESSION['time_cities']?>)<br><br><?=__('account_tip_4')?></div>
                    <div class="worldmap" id="account-map-continents"> 
                     <ul class="continents">
                      <li class="c1"><a id="africa-li" href="#africa"><?=__('world_map_africa')?></a></li>
                      <li class="c2"><a id="asia-li" href="#asia"><?=__('world_map_asia')?></a></li>
                      <li class="c3"><a id="australia-and-southern-pacific-li" href="#australia-and-southern-pacific"><?=__('world_map_australia')?></a></li>
                      <li class="c4"><a id="europe-li" href="#europe"><?=__('world_map_europe')?></a></li>
                      <li class="c5"><a id="north-america-li" href="#north-america"><?=__('world_map_north_america')?></a></li>
                      <li class="c6"><a id="south-america-li" href="#south-america"><?=__('world_map_south_america')?></a></li>
                     </ul>
                    </div>
                    <div id="account-addresses"> 
                        <?php 
                        $query = 'select time_id, time_zone, time_utc, time_cities, sending_id, sending_displayed from timezone join sending_time on time_id = timezone_id order by time_id, sending_id';
                        //echo "<br>query : ".$query;
                        $result = mysqli_query($mysqli, $query);
                        //echo "<br>result : "; print_r($result);
                        $previous_utc = "";
                        $previous_zone = "";
                        $arrClassement = array();
                        while ($row = mysqli_fetch_array($result)) {
                            if($row['time_utc'] != $previous_utc) {
                                if($previous_utc != "")  { //on va fermer un select, donc afficher les horaires dispo classés par ordre
                                    asort($arrClassement);
                                    foreach ($arrClassement as $key=>$value) {                                      
                                        echo "<option value='".$key."'>".$value."</option>";
                                    }
                                    $arrClassement = array();
                                    echo "</select></div></div></li>"; //pour premier passage, pas besoin de fermer le select et le li
                                }                               
                                if($previous_zone != $row['time_zone']) 
                                    {
                                        echo "</ul><ul class='list-group'><li id='".$row['time_zone']."' class='list-expl'>".__('world_map_table')."<br><br></li>".
                                        "<li id='".$row['time_zone']."' class='list-group-item list-header'><div class='row'><div class='col-lg-2'>".__('world_map_timezone')."</div>".
                                        "<div class='col-lg-8'>".__('world_map_cities')."</div><div class='col-lg-2'>".__('world_map_time');
                                    }
                                echo "<li id='".$row['time_zone']."' class='list-group-item list-utc'>".
                                "<div class='row'><div class='col-lg-2'>UTC : ".$row['time_utc']."</div>".
                                "<div class='col-lg-8'>".$row['time_cities']."</div>".
                                "<div class='col-lg-2'><select class='form-control account-select-utc' data-utc=".$row['time_utc']." data-cities='".$row['time_cities']."' data-zone='".$row['time_zone']."'><option></option>";
                            }
                            $arrClassement[$row['sending_id']] = $row['sending_displayed'];
                            $previous_utc = $row['time_utc'];
                            $previous_zone = $row['time_zone'];      
                        }
                        asort($arrClassement);
                        foreach ($arrClassement as $key=>$value) {
                            echo "<option value='".$key."'>".$value."</option>"; //dernier passage pour le fuseau UTC +14
                        }
                        echo "</select></div></div></li></ul>";
                        ?>
                        <div id="account-select-phrase"></div>
                    </div>
                    <br><div id="timezoneAccountValidate" class="validate"></div><br>
                    <button id="backToAccountSecondBtn" class="btn btn-lg subscribeBack btn-inverse"><?=__('btn_previous')?></button>
                    <input type="button" id="accountThirdBtn" name="accountThirdBtn" class="btn btn-lg subscribeSubmit btn-default" value="<?=__('btn_save')?>"/>
                </div>
            </div>
            <input type="hidden" name="accountFormButton" id="accountFormButton">
            <!-- <input type="hidden" name="accountImmediateChange" id="accountImmediateChange">
            <input type="hidden" name="accountDelayedChange" id="accountDelayedChange"> -->
            <input type="hidden" value="<?php echo htmlspecialchars($_SESSION['id']); ?>" id="accountId" name="accountId">
            </form>
            
            <div class="col-sm-8 col-sm-offset-2 text-center" id="accountChangeConfirm">  
                <div id="messageAccountConfirm" style="text-align:center"></div>
            </div>
        </div>
    </div>
</section>

<?php
if (!empty($_POST)) {  
    //print_r($_POST);
    
    if(isset($_POST['subscribeFormButton'])) { //form d'inscription
        $user_day_1 = 0;
        $user_day_2 = 0;
        $user_day_3 = 0;
        $user_day_4 = 0;
        $user_day_5 = 0;
        $user_day_6 = 0;
        $user_day_7 = 0;
        $user_cycle_first_day = 0;
        $user_daily_comment = 0;
        $jours_reception = "";
        $user_daily_comment_param = "";
        $user_reading_param = "";

        foreach($_POST as $key=>$value)
        {
            switch($key)
            {
                case "email":
                    $user_mail = $value;
                    break;
                case "name":
                    $user_first_name = ucfirst(strtolower($value));  
                    break;
                case "firstDayRadio":
                    $user_cycle_first_day = $value;
                    break;
                case "toggleDay1":
                    $user_day_1 = $value;
                    $jours_reception = __('subscribe_mail_monday');
                    break;
                case "toggleDay2":
                    $user_day_2 = $value;
                    if($jours_reception <> "") $jours_reception .= ", ".__('subscribe_mail_tuesday');
                    else $jours_reception = __('subscribe_mail_tuesday');
                    break;
                case "toggleDay3":
                    $user_day_3 = $value;
                    if($jours_reception <> "") $jours_reception .= ", ".__('subscribe_mail_wednesday');
                    else $jours_reception = __('subscribe_mail_wednesday');
                    break;
                case "toggleDay4":
                    $user_day_4 = $value;
                    if($jours_reception <> "") $jours_reception .= ", ".__('subscribe_mail_thursday');
                    else $jours_reception = __('subscribe_mail_thursday');
                    break;
                case "toggleDay5":
                    $user_day_5 = $value;
                    if($jours_reception <> "") $jours_reception .= ", ".__('subscribe_mail_friday');
                    else $jours_reception = __('subscribe_mail_friday');
                    break;
                case "toggleDay6":
                    $user_day_6 = $value;
                    if($jours_reception <> "") $jours_reception .= ", ".__('subscribe_mail_saturday');
                    else $jours_reception = __('subscribe_mail_saturday');
                    break;
                case "toggleDay7":
                    $user_day_7 = $value;
                    if($jours_reception <> "") $jours_reception .= ", ".__('subscribe_mail_sunday');
                    else $jours_reception = __('subscribe_mail_sunday');
                    break;
                case "toggleDailyText":
                    if($value == "on") $user_daily_comment = 1;
                    break;
                /*case "langText":
                    $user_daily_comment_param = $value;
                    break;
                case "langReading":
                    $user_reading_param = $value;
                    break;*/
                case "radiosLangReading":
                    $user_reading_param = $value;
                    break;
                case "radiosLangText":
                    $user_daily_comment_param = $value;
                    break;
                case "subscribeChosenTime":
                    $user_sending_id = $value;
                    break;
            }

        }
        $user_password = generateRandomString(); 
        $hash = password_hash($user_password, PASSWORD_DEFAULT); //securité PHP5.5 : hash + salt
        //echo "<br>password : ".$user_password;
        $user_registration_day = date('Y-m-d') ;
        $unsubscribe_hash = md5($user_mail."yoplamapoule");
        $query = 'INSERT INTO user(user_first_name, user_mail, user_password, user_cycle_first_day, user_registration_day, user_day_1,  user_day_2, user_day_3, user_day_4, user_day_5, user_day_6, user_day_7, user_daily_comment, unsubscribe_hash, user_daily_comment_param, user_reading_param, user_sending_id) VALUES ("'.$user_first_name.'", "'.$user_mail.'", "'.$hash.'" , '.$user_cycle_first_day.', "'.$user_registration_day.'", '.$user_day_1.', '.$user_day_2.', '.$user_day_3.', '.$user_day_4.', '.$user_day_5.', '.$user_day_6.', '.$user_day_7.', '.$user_daily_comment.', "'.$unsubscribe_hash.'", "'.$user_daily_comment_param.'", "'.$user_reading_param.'", '.$user_sending_id.')';
        //echo "<br>query : ".$query;
        $result = mysqli_query($mysqli, $query);
        //echo "<br>result : ".$result;
        if(!$result) echo '<script type="text/javascript">showSubscribeConfirm("ko","","","","","");</script>';//$_SESSION['subscribe'] = "ko"; //header('Location: index.html?subscribe=ko');
        else {
            //ajouter image book.png à la suite du titre de l'email
            $message_body = __("subscribe_mail_thks").' '.$user_first_name.' '.__("subscribe_mail_content_1").' '.$jours_reception;
            if($user_daily_comment == 1) $message_body .= ' '.__("subscribe_mail_content_2");
              $message_body .= __("subscribe_mail_content_3").' <b>'.$user_password.'</b>'.__("subscribe_mail_content_4");
            sendMail($user_mail, __("subscribe_mail_title"), $message_body,__('subscribe_title'), $user_mail, $unsubscribe_hash);           
            echo '<script type="text/javascript">showSubscribeConfirm("ok", "'.$user_first_name.'", "'.$user_mail.'", "'.$jours_reception.'", "'.$user_daily_comment.'", "'.$user_password.'");</script>';
        }
    } //fin form d'inscription

    elseif(isset($_POST['accountFormButton'])) { //form de modification de données
        print_r($_POST);
        //$fieldsImmediateChange = "";
        //$fieldsDelayedChange = "";
        //$jours_reception = "";
        $etatImmediateChange = "";
        $etatDelayedChange = "";
        $successDelayedChange = 0;
        $successImmediateChange = 0;
        
        $user_cycle_first_day = 0;
        $user_day_1 = 0;
        $user_day_2 = 0;
        $user_day_3 = 0;
        $user_day_4 = 0;
        $user_day_5 = 0;
        $user_day_6 = 0;
        $user_day_7 = 0;
        
        $user_first_name = "";
        $user_mail = "";
        $user_daily_comment = 0;
        $radiosAccountLangReading = "";
        $radiosAccountLangText = "";
        $accountChosenTime = "";
        
        foreach($_POST as $key=>$value)
        {
            switch($key)
            {
                case "accountId":
                    $user_id = ucfirst(strtolower($value));                       
                    break; 
                case "nameAccount":
                    $user_first_name = ucfirst(strtolower($value));  
                    echo "<script>$('#nameAccount').val('$user_first_name');</script>";                
                    break;  
                case "emailAccount":
                    $user_mail = $value;
                    echo "<script>$('#emailAccount').val('$user_mail');</script>";
                    break;                                      
                case "toggleAccountDailyText":
                    if($value == "on") { 
                        $user_daily_comment = 1; 
                        echo "<script>$('#hiddenAccountDailyComment').val(1);</script>";
                    }                    
                    break;
                case "firstDayRadio":
                    $user_cycle_first_day = $value;
                    echo "<script>$('#hiddenAccountFirstDay').val($user_cycle_first_day);</script>";
                    break;
                case "toggleAccountDay1":
                    $user_day_1 = $value;
                    echo "<script>$('#hiddenAccount1').val(1);</script>";
                    break;
                case "toggleAccountDay2":
                    $user_day_2 = $value;
                    echo "<script>$('#hiddenAccount2').val(1);</script>";
                    break;
                case "toggleAccountDay3":
                    $user_day_3 = $value;
                    echo "<script>$('#hiddenAccount3').val(1);</script>";
                    break;
                case "toggleAccountDay4":
                    $user_day_4 = $value;
                    echo "<script>$('#hiddenAccount4').val(1);</script>";
                    break;
                case "toggleAccountDay5":
                    $user_day_5 = $value;
                    echo "<script>$('#hiddenAccount5').val(1);</script>";
                    break;
                case "toggleAccountDay6":
                    $user_day_6 = $value;
                    echo "<script>$('#hiddenAccount6').val(1);</script>";
                    break;
                case "toggleAccountDay7":
                    $user_day_7 = $value;
                    echo "<script>$('#hiddenAccount7').val(1);</script>";
                    break;     
                case "hiddenReadingLang":
                    $radiosAccountLangReading = $value;
                    break;
                case "hiddenCommentLang":
                    $radiosAccountLangText = $value;
                    break;
                case "accountTimeId":
                    $accountChosenTime = $value;
                    // echo "<script>$('#hiddenTimeId').val($accountChosenTime);</script>";
                    break;
                // case "accountTimeDisplayed":
                //     echo "<script>$('#hiddenTimeDisplayed').val($value);</script>";                    
                //     break; 
                // case "accountTimeCities":
                //     echo "<script>$('#hiddenTimeCities').val(".$value.");</script>";
                //     break; 
                // case "accountTimeUTC":
                //     echo "<script>$('#hiddenTimeUTC').val($value);</script>";
                //     break; 
                // case "accountTimeZone":
                //     echo "<script>$('#hiddenTimeZone').val($value);</script>";
                //     echo "<script>$.each(".$value.", function (k, v) { alert('accountTimeZone:' + v); });</script>";
                //     break;               
            }
        }
        if(!isset($_POST['toggleAccountDailyText']))
        {
            $user_daily_comment = 0;
            echo "<script>$('#hiddenAccountDailyComment').val(0);</script>";
        }
        if(!isset($_POST['toggleAccountDay1']))
        {
            $user_day_1 = 0;
            echo "<script>$('#hiddenAccount1').val(0);</script>";
        }
        if(!isset($_POST['toggleAccountDay2']))
        {
            $user_day_2 = 0;
            echo "<script>$('#hiddenAccount2').val(0);</script>";
        }
        if(!isset($_POST['toggleAccountDay3']))
        {
            $user_day_3 = 0;
            echo "<script>$('#hiddenAccount3').val(0);</script>";
        }
        if(!isset($_POST['toggleAccountDay4']))
        {
            $user_day_4 = 0;
            echo "<script>$('#hiddenAccount4').val(0);</script>";
        }
        if(!isset($_POST['toggleAccountDay5']))
        {
            $user_day_5 = 0;
            echo "<script>$('#hiddenAccount5').val(0);</script>";
        }
        if(!isset($_POST['toggleAccountDay6']))
        {
            $user_day_6 = 0;
            echo "<script>$('#hiddenAccount6').val(0);</script>";
        }
        if(!isset($_POST['toggleAccountDay7']))
        {
            $user_day_7 = 0;
            echo "<script>$('#hiddenAccount7').val(0);</script>";
        }

        $queryChange = 'UPDATE user SET user_first_name = "'.$user_first_name.'", 
        user_mail = "'.$user_mail.'", user_daily_comment = '.$user_daily_comment.', 
        user_cycle_first_day = '.$user_cycle_first_day.', user_day_1 = '.$user_day_1.', 
        user_day_2 = '.$user_day_2.', user_day_3 = '.$user_day_3.', user_day_4 = '.$user_day_4.', 
        user_day_5 = '.$user_day_5.', user_day_6 = '.$user_day_6.', user_day_7 = '.$user_day_7.', 
        user_reading_param = "'.$radiosAccountLangReading.'", user_daily_comment_param = "'.$radiosAccountLangText.'", 
        user_sending_id = '.$accountChosenTime.'  WHERE user_id = '.$user_id;
        // echo "<br>query : ".$queryChange;
        $result = mysqli_query($mysqli, $queryChange);
        if(!$result) $successImmediateChange = -1;
        else $successImmediateChange = 1;
        // echo "<br>result : ".$result;
        echo "<script type='text/javascript'>showAccountChangeConfirm($successImmediateChange,$successDelayedChange);</script>";
    }
    
    elseif(isset($_POST['msgContact'])) { //form de contact
        sendMail("contact@jwreading.com", "JW Reading - Formulaire de contact - Nouveau message de ".$_POST['nameContact']." ".$_POST['emailContact'], $_POST['msgContact'], "Message");
        echo "<script>emailSent();</script>";
    }
    elseif(isset($_POST['loginInput'])) { //form de login
        echo "<script>
        $('#hiddenTimeCities').val('".$_SESSION['time_cities']."');
        $('#login').fadeOut(section_hide_time, function() {
            $('#account').fadeIn(section_show_time);
            $('.section:visible').hide();
            $('a', '.mainmenu').removeClass('active');
            $('#account').addClass('active');
            $('#account').show();
            $('#accountFirst').show();
            $('#accountSecond').hide();
            $('#accountThird').hide();
            $('#messageConfirm').hide();
            $('#messageAccountConfirm').hide();
            $('#menuLogin').attr('href', '#account');
            $('ul.displayNone').css('display', 'block');
            if(".$_POST['msg']." == '2') {
                $('#formDelayedChangeValidate').show();
            }         
        });</script>";
    }
}

?>
</body>
</html>
