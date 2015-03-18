// global. currently active menu item 
var current_item = 0;

// few settings
var section_hide_time = 800;
var section_show_time = 800;

// var etatImmediateChange = 0;
// var etatDelayedChange = 0;
// var immediateChange = [];
// var delayedChange = [];

var password;
var lang;
var size;

// jQuery stuff
jQuery(document).ready(function($) {
    //test pour connaitre l'url du fichier php appelant
    caller = window.location.pathname;
    //alert('caller:' + caller);
    //si l'url vient du fichier de test, chargement de la carte du monde
    if(caller == "/jwreading/www/index_test.php"){
        if ($(window).width() < 768) { size = 540; }
        else { size = 960; }

        $('#map-continents').cssMap({
            'size' : size,
            'onClick' : function(e){
                // var id = e.attr("class");
                // alert(id);                
                $(".select-utc").find('option').removeAttr("selected"); //annulation sélection utc
                //$('#select-phrase').empty();                
            },
            // 'onLoad' : function(e){
            //     $timezone = $('#hiddenTimeZone').val();
            //     $('.' + $timezone + ' map').removeClass('active-region');
            //     var element = $('.' + $timezone);
            //     var myClasses = element.classList;
            //     alert(element + " " + myClasses.length + " " + myClasses[0]);
            // },
            agentsListId : '#addresses'
        });

        $('#account-map-continents').cssMap({
            'size' : size,
            'onClick' : function(e){
                var zone = e.children('a').attr('id');
                var len = zone.length - 3;
                zone = zone.substr(0, len);
                // alert("zone : " + zone);
				$('#hiddenTimeDisplayed').val(0);
                $('#hiddenTimeZone').val(zone);
                $(".account-select-utc").find('option').removeAttr("selected"); //annulation sélection utc

                //$('#select-phrase').empty();
            },
            // 'onLoad' : function(e){
                // var element = $('.' + $timezone + ' account-map');
                // var myClasses = element.classList;
                // alert(element + " " + myClasses.length + " " + myClasses[0]);
            // },
            agentsListId : '#account-addresses'
        });
    }

	lang = $('html').attr('lang');
    $('.loader').hide(); //div pour l'animation "loading" après soumission du formulaire
    if ($("#content").height() <= $(window).height())
    {
        var speed = 1000;
        var offset = $("#content").offset(); //position de la boite "content"
        var offsetWidth = $("#content").outerWidth();
        var offsetRight = offset.left + offsetWidth;
        $("#content").hide();
        $("#topbar").offset({left: offset.left, top: offset.top});
        $("#topbar").animate({width: offsetWidth}, speed,
                function() {
                    $("#rightbar").offset({top: offset.top, left: offsetRight - 1});
                    var offsetHeight = $("#content").outerHeight();
                    $("#rightbar").animate({height: offsetHeight + 1}, speed,
                            function() {
                                offsetRight = $(window).width() - offsetRight;
                                $("#bottombar").css({top: offsetHeight + offset.top - 1, right: offsetRight});
                                $("#bottombar").animate({width: offsetWidth}, speed,
                                        function() {
                                            var offsetBottom = $(window).height() - offsetHeight - offset.top - 1;
                                            //console.log("total height : " + $(window).height() + " offset top : " + offset.top + " offsetBottom : " + offsetBottom);
                                            $("#leftbar").css({top: "auto", bottom: offsetBottom, left: offset.left});
                                            $("#leftbar").animate({height: offsetHeight}, speed,
                                                    function() {
                                                        $("#content").fadeIn(speed);
                                                        $("#topbar").fadeOut(speed);
                                                        $("#leftbar").fadeOut(speed);
                                                        $("#bottombar").fadeOut(speed);
                                                        $("#rightbar").fadeOut(speed);
                                                    });
                                        });
                            });
                });
    }
    else
    {
        var speed = 1500;
        var offset = $("#content").offset(); //position de la boite "content"
        var offsetWidth = $("#content").outerWidth();
        var offsetRight = offset.left + offsetWidth;
        var offsetHeight = $("#content").outerHeight();
        $("#content").hide();
        $("#topbar").offset({left: offset.left, top: offset.top});
        $("#topbar").animate({width: offsetWidth}, speed);

        var offsetBottom = $(window).height() - offsetHeight - offset.top;
        //console.log("total height : " + $(window).height() + " offset top : " + offset.top + " offsetBottom : " + offsetBottom);
        $("#leftbar").css({top: "auto", bottom: offsetBottom, left: offset.left});
        $("#leftbar").animate({height: offsetHeight}, speed,
                function() {
                    $("#content").fadeIn(speed);
                    $("#topbar").fadeOut(speed);
                    $("#leftbar").fadeOut(speed);
                });

    }

    // Switch section
    $("a", '.menu').click(function()
    {
        if (!$(this).hasClass('active')) {
            current_item = this;
            // close all visible divs with the class of .section
            $('.section:visible').fadeOut(section_hide_time, function() {
                //console.log("id menu : " + $(current_item).attr('href'));
                $('a', '.mainmenu').removeClass('active');
                $(current_item).addClass('active');
                var new_section = $($(current_item).attr('href'));
                new_section.fadeIn(section_show_time);
            });
        }
        if ($(this).attr('href') == "#subscribe") {
            $("#subscribeFirst").show();
            $("#subscribeSecond").hide();
            $("#subscribeThird").hide();
			$('#subscribeConfirm').hide();
        }
        else if ($(this).attr('href') == "#login") {
            $("#contentLogin").show("slow");
        }
        else if ($(this).attr('href') == "#logout") {
            $('.section:visible').fadeOut(section_hide_time, function() {
                $('a', '.mainmenu').removeClass('active');
                $('#login').addClass('active');
                $('#accountFirst').hide();
                $('#accountSecond').hide();
                $('#accountThird').hide();
                $('#messageConfirm').hide();
                $('#menuLogin').attr('href', '#login');
                $('ul.displayNone').css('display', 'none');
                $('#login').fadeIn(section_show_time);

                //clean every input
                $(':input').val('');
                $('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');

				$.ajax({
					type: "POST",
					url: "ajax/logout.php",
					data: "",
					success: function(msg) {
					}
				});
            });
        }
        return false
    });

    $("#subscribeSecond").hide();
    $("#subscribeThird").hide();

    $("#subscribeFirstBtn").click(function(e)
    {
        var email = $('#email').val();
        e.preventDefault();
        $("#emailValidate").slideUp(200);
        $("#emailValidate").empty();
        //appel d'une fonction ajax($.post) avec callback(result) pour vérifier si l'adresse email existe déjà
        $.post('ajax/checkEmail.php', {'email': email}, function(data) {
        }).done(function(result) {
            result = result.trim();
            //console.log('resultat lu dans #subscribeFirstBtn : ' + result);
            if (result == '1')
            {
                $("#emailValidate").hide();
                if(lang == 'fr') $("#emailValidate").append('Cette adresse email est déjà enregistrée. Si vous avez perdu votre mot de passe, vous pouvez utiliser la rubrique "Mot de passe oublié" dans le menu de connexion ou le formulaire de contact pour me signaler un problème&nbsp;&nbsp;');
                else $("#emailValidate").append('This email address is already used. If you have forgotten your password, please use the link "Forgotten password" in the login menu or the contact form to inform me about a problem&nbsp;&nbsp;');
				$("#emailValidate").slideDown(400);

            }
            else if ($.trim(email).length == 0) {
                $("#emailValidate").hide();
                if(lang == 'fr') $("#emailValidate").append("Merci d'entrer une adresse email&nbsp;&nbsp;");
				else $("#emailValidate").append("Please enter an email address&nbsp;&nbsp;");
                $("#emailValidate").slideDown(400);
            }
            else if (validateEmail(email) == false) {
                $("#emailValidate").hide();
                if(lang == 'fr') $("#emailValidate").append('Adresse email non valide&nbsp;&nbsp;');
				else $("#emailValidate").append('This email address is not valid&nbsp;&nbsp;');
                $("#emailValidate").slideDown(400);
            }

            else {
                $("#subscribeFirst").hide();
                $("#subscribeThird").hide();
                $("#subscribeSecond").show();
                $("input[type=radio]").hide();
                $("#formValidate").empty();
                $(".tip").show();

                $("input[type=checkbox].subscribeChk").each(
                    function() {
                        if($(this).is(":checked")){
                            var id = $(this).attr("id");
                            var lastChar = id.substr(id.length - 1);
                            $("#radio" + lastChar).show();
                        }
                        // $(this).attr('checked', false);
                        // if (this.id == "toggleDailyText")
                        //     $(this).prop('checked', true);
                    });

                $("input[type=checkbox].subscribeChk").click(function() {
                    $("input[type=checkbox].subscribeChk").each(
                            function() {
                                var id = $(this).attr("id");
                                var lastChar = id.substr(id.length - 1);
                                //console.log('checkbox ' + id);
                                if ($(this).is(":checked"))
                                {
                                    $("#radio" + lastChar).show();
                                }
                                else
                                {
                                    $("#radio" + lastChar).hide();
                                    $("#radio" + lastChar).attr('checked', false);
                                }
                            });
                });
            }
        });
    });

    /************ à enlever en V2 **********/
    $("#backBtn").click(function()
    {
        $("#subscribeFirst").show();
        $("#subscribeSecond").hide();
        $("#subscribeThird").hide();
        return false;
    });
    /***************************************/

    $("#backToFirstBtn").click(function()
    {
        $("#subscribeFirst").show();
        $("#subscribeSecond").hide();
        $("#subscribeThird").hide();
        $(".tip").show();
        return false;
    });

    $("#backToSecondBtn").click(function()
    {
        $("#subscribeFirst").hide();
        $("#subscribeSecond").show();
        $("#subscribeThird").hide();
        $(".tip").show();
        return false;
    });

    /*popover*/
    $('body').on('click', function(e) {
        $('[data-toggle=popover]').each(function() {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });

    $('.close').each(function() {
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });

    var showPopover = function() {
        $(this).popover('show');
    };

    $('[data-toggle=popover]').popover({
        trigger: 'manual'
    }).click(showPopover).hover(showPopover);

    $("input[type=radio]").click(function(e)
    {
        $("#formValidate").slideUp(200);
        $("#formValidate").empty();
    });

    $("#subscribeSecondBtn").click(function(e)
    {
        e.preventDefault();
        $("#formValidate").slideUp(200);
        $("#formValidate").empty();
        var count = $("input[type=radio][class=radioReading]:checked").length;
        //console.log('count : ' + count);
        if (count != 1)
        {
            $("#formValidate").hide();
            if(lang == 'fr') $("#formValidate").append("Merci de choisir au moins un jour et de le valider en tant que premier jour&nbsp;&nbsp;");
            else $("#formValidate").append("Please choose at least one day et select it as first day&nbsp;&nbsp;");
			$("#formValidate").slideDown(400);
        }
        else {
            $("#subscribeFirst").hide();
            $("#subscribeThird").show();
            $("#subscribeSecond").hide();
            $("#formValidate").empty();
            $(".tip").show(); 
            if($("#select-phrase").is(':empty')) { $("#tip2").show(); }
            else { $("#tip2").hide(); }
        }
    });
	
	$("#subscribeThirdBtn").click(function(e)
	{
		e.preventDefault();

        if($('#subscribeChosenTime').val() != 0) {
            $('#loaderSubscribe').show();               
            $('#subscribeThird').hide();
            $("#messageConfirm").show(2000, function() {
                var data=$("#subscribeForm").serializeArray();
                data.push({name: "lang", value: lang});

                $.ajax({
                    type: 'POST',
                    url: 'ajax/saveData.php',
                    data: $.param(data),
                    dataType: 'json'})
                .done(function(result) {
                    $('.section:visible').hide();
                    $('a', '.mainmenu').removeClass( 'active' );
                    $('#subscribe').addClass( 'active' );
                    $('#subscribe').show();
                    $('#subscribeFirst').hide();
                    $('#subscribeSecond').hide();
                    $('#subscribeThird').hide();
                    $('#subscribeConfirm').show();
                    $('#messageConfirm').hide();
                    $("#messageConfirm").empty();
                    $('#loaderSubscribe').hide();
                    if(result[0] == "OK"){
                        var name = result[1];
                        var mail = result[2];
                        var jours = result[3];
                        var comment = result[4];
                        var pass = result[5];
                        if(lang == 'fr') {
                            $('#messageConfirm').append('<p style=\'text-align:center\'><br>Merci ' + name + ' pour votre inscription. Vous allez recevoir un email de confirmation dans quelques instants à l\'adresse <b>' + mail + '</b>. Si ce n\'est pas le cas, veuillez vérifier le dossier "Indésirables" (aussi appelé "Spams") de votre boite mail.<br><br>Vous avez choisi de recevoir la lecture de la Bible chaque ' + jours +'. Le mot de passe pour accéder à votre espace personnel sur le site est <b>' + pass + '</b><br><br>Vous pourrez changer tous les paramètres (adresse mail, mot de passe, jours de réception, premier jour de votre semaine de lecture) dans cet espace personnel. En cas de question, n\'hésitez pas utiliser le formulaire de contact.<br><br>Bonne lecture et à bientôt.<br><br>L\'administrateur JW Reading</p>');
                        }
                        else {
                            $('#messageConfirm').append('<p style=\'text-align:center\'><br>Thank you ' + name + ' for your registration. You will receive in a few minutes a confirmation email at <b>' + mail + '</b>. If it is not the case, please check the "Spams" folder in your email account.<br><br>You chose to receive the Bible reading every ' + jours +'. Your password to access your account on the website is <b>' + pass + '</b><br><br>You will be able to modify every parameter (email address, password, reception days, first day of your reading\'s week) in your account. If you have any question, don\'t hesitate to use the contact form.<br><br>Have a nice Bible reading and we hope to see you soon.<br><br>The JW Reading administrator</p>');
                        }
                    }
                    else {
                        if(lang == 'fr') $("#messageConfirm").append('<p style="text-align:center"><br>Aïe, problème<br><br>Apparemment, il y a eu un problème d\'enregistrement en base de données lors de votre inscription. Contactez-moi grâce au formulaire de contact pour me le signaler.<br><br> Désolé pour cet incident.<br><br>L\'administrateur JW Reading</p>');
                        else $("#messageConfirm").append('<p style="text-align:center"><br>Ouch, problem<br><br>Apparently, there has been a problem during the registration process in the database. Please, inform me about this through the contact form.<br><br> Sorry for this incident.<br><br>The JW Reading administrator</p>');
                    }                            
                    if(lang == 'fr') $("#messageConfirm").append('<p style="text-align:center"><br><button class="btn btn-lg btn-default">Retour à l\'accueil</button></p>');
                    else $("#messageConfirm").append('<p style="text-align:center"><br><button id="backToAccount" class="btn btn-lg btn-default">Go back to home page</button></p>');
                    $('#messageConfirm').slideDown(400);
                })
                .fail(function(err,  status, xhr) {
                    // console.log( "error " + status + " " + xhr.ResponseText);
                })
                .always(function() {
                    //clean every input
                    $(':input').val('');
                    $('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                })
            });
        }
        else
        {
            $("#timezoneAccountValidate").hide();
            if(lang == 'fr') $("#timezoneAccountValidate").append("Merci de choisir un horaire d'envoi dans le tableau des fuseaux&nbsp;&nbsp;");
            else $("#timezoneAccountValidate").append("Please choose a sending time in the timezones table&nbsp;&nbsp;");
            $("#timezoneAccountValidate").slideDown(400);
        }

	});
	
	$(".loginForm").keyup(function(event){
		if(event.keyCode == 13){
			$("#loginBtn").click();
		}
	});

    $("#loginBtn").click(function(e)
    {
        $("#loginValidate").slideUp(400);
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "ajax/login.php",
            data: "loginInput=" + $("#loginInput").val() + "&pwdInput=" + $("#pwdInput").val(),
            success: function(msg) {
                //if (msg == '1' || msg == '2')
                if (msg == '1')
                {
					$('#msg').val(msg);
                    $('#loginForm').submit();
                }
                else if (msg == '0')
                {
                    if(lang == 'fr') $("#loginValidate").html("Le mot de passe est incorrect");
					else $("#loginValidate").html("The password is wrong");
                    $("#loginValidate").slideDown(400);
                }
                else if (msg == '-1')
                {
                    if(lang == 'fr') $("#loginValidate").html("Cette adresse email n'est pas enregistrée. Vous pouvez vous créer un compte avec le menu \"Inscription\"");
                    else $("#loginValidate").html("This email address is not registered as an account. You can create one with the \"Subscribe\" menu");
					$("#loginValidate").slideDown(400);
                }
                else
                {
                    if(lang == 'fr') $("#loginValidate").html("Adresse email ou mot de passe incorrect");
                    else $("#loginValidate").html("The email address or the password is wrong");
					$("#loginValidate").slideDown(400);
                }
            }
        });
    });	

   //  $(".accountInput").on('input', function() {
   //      etatImmediateChange = 1;
   //      $('#accountSecondBtn').prop('disabled', false);
   //  });

   //  $(".accountSwitch").on('click', function() {
   //      $('#accountSecondBtn').prop('disabled', false);
   //      if ($(this).attr("id") == 'toggleAccountDailyText') {
   //          etatImmediateChange = 1;
   //      }
   //      else {
   //          //etatDelayedChange = 1;
			// etatImmediateChange = 1;
   //      }
   //  });

    $("#accountSecond").hide();
    $("#accountThird").hide();

    $("#accountFirstBtn").click(function(e)
    {
        var email = $('#emailAccount').val();
        var accountId = $('#accountId').val();
        e.preventDefault();
        $("#emailAccountValidate").slideUp(200);
        $("#emailAccountValidate").empty();
        //appel d'une fonction ajax($.post) avec callback(result) pour vérifier si l'adresse email existe déjà
        $.post('ajax/checkEmail.php', {'email': email, 'account': accountId}, function(data) {
        }).done(function(result) {
            result = result.trim();
            if (result == '1')
            {
                $("#emailAccountValidate").hide();
				if(lang == 'fr') $("#emailAccountValidate").append('Cette adresse email est déjà enregistrée. Si vous avez perdu votre mot de passe, vous pouvez utiliser la rubrique "Mot de passe oublié" dans le menu de connexion ou le formulaire de contact pour me signaler un problème&nbsp;&nbsp;');
                else $("#emailAccountValidate").append('This email address is already used. If you have forgotten your password, please use the link "Forgotten password" in the login menu or the contact form to inform me about a problem&nbsp;&nbsp;');
                $("#emailAccountValidate").slideDown(400);

            }
            else if ($.trim(email).length == 0) {
                $("#emailAccountValidate").hide();
                if(lang == 'fr') $("#emailAccountValidate").append("Merci d'entrer une adresse email&nbsp;&nbsp;");
				else $("#emailAccountValidate").append("Please enter an email address&nbsp;&nbsp;");
                $("#emailAccountValidate").slideDown(400);
            }
            else if (validateEmail(email) == false) {
                $("#emailAccountValidate").hide();
				if(lang == 'fr') $("#emailAccountValidate").append('Adresse email non valide&nbsp;&nbsp;');
				else $("#emailAccountValidate").append('This email address is not valid&nbsp;&nbsp;');
                $("#emailAccountValidate").slideDown(400);
            }
            else {
                $("#accountFirst").hide();
                $("#accountSecond").show();
                $('#accountThird').hide();
                $("input[type=radio]").hide();
                $("#formAccountValidate").empty();

                $("input[type=checkbox].accountSwitch").each(
                        function() {
                            $(this).prop('checked', false);
                            if (this.id == "toggleAccountDailyText")
                                $(this).prop('checked', true);
                        });
                if ($("#hiddenAccount1").val() == 1) {
                    $("#toggleAccountDay1").prop('checked', true);
                    $("#radioAccount1").show();
                }
                if ($("#hiddenAccount2").val() == 1) {
                    $("#toggleAccountDay2").prop('checked', true);
                    $("#radioAccount2").show();
                }
                if ($("#hiddenAccount3").val() == 1) {
                    $("#toggleAccountDay3").prop('checked', true);
                    $("#radioAccount3").show();
                }
                if ($("#hiddenAccount4").val() == 1) {
                    $("#toggleAccountDay4").prop('checked', true);
                    $("#radioAccount4").show();
                }
                if ($("#hiddenAccount5").val() == 1) {
                    $("#toggleAccountDay5").prop('checked', true);
                    $("#radioAccount5").show();
                }
                if ($("#hiddenAccount6").val() == 1) {
                    $("#toggleAccountDay6").prop('checked', true);
                    $("#radioAccount6").show();
                }
                if ($("#hiddenAccount7").val() == 1) {
                    $("#toggleAccountDay7").prop('checked', true);
                    $("#radioAccount7").show();
                }
                if ($("#hiddenAccountDailyComment").val() == 0) {
                    $("#toggleAccountDailyText").prop('checked', false);
                }
                
                //reading lang
                if ($("#hiddenReadingLang").val() == "fr") {
                    $("#radioAccountLangReadingFr").prop('checked', true);
                }
                else if ($("#hiddenReadingLang").val() == "en") {
                    $("#radioAccountLangReadingEn").prop('checked', true);
                }
                else if ($("#hiddenReadingLang").val() == "ro") {
                    $("#radioAccountLangReadingRo").prop('checked', true);
                }

                //comment lang
                if ($("#hiddenCommentLang").val() == "fr") {
                    $("#radioAccountLangTextFr").prop('checked', true);
                }
                else if ($("#hiddenCommentLang").val() == "en") {
                    $("#radioAccountLangTextEn").prop('checked', true);
                }
                else if ($("#hiddenCommentLang").val() == "ro") {
                    $("#radioAccountLangTextRo").prop('checked', true);
                }
                //console.log('toggleAccountDailyText : ' + $("#hiddenAccountDailyComment").val());
                var firstDay = $("#hiddenAccountFirstDay").val();
                $('#radioAccount' + firstDay).attr('checked', true);

                $("input[type=radio].accountSwitch").click(function() {
                    var id = $(this).attr("id");
                    var lastChar = id.substr(id.length - 1);
                    $("#hiddenAccountFirstDay").val(lastChar);
                });

                $("input[type=checkbox].accountSwitch").click(function() {
                    $("input[type=checkbox].accountSwitch").each(
                            function() {
                                var id = $(this).attr("id");
                                var lastChar = id.substr(id.length - 1);
                                //console.log('checkbox ' + id);
                                if ($(this).is(":checked"))
                                {
                                    $("#radioAccount" + lastChar).show();
                                    if($(this).attr("id")== 'toggleAccountDailyText')
                                    {
                                        $('#hiddenAccountDailyComment').val(1);
                                    }
                                    else {
                                        $('#hiddenAccount' + lastChar).val(1);
                                    }
                                }
                                else
                                {
                                    if($(this).attr("id")== 'toggleAccountDailyText')
                                    {
                                        $('#hiddenAccountDailyComment').val(0);
                                    }
                                    else {
                                        $("#radioAccount" + lastChar).hide();
                                        $("#radioAccount" + lastChar).attr('checked', false);
                                        $('#hiddenAccount' + lastChar).val(0);
                                    }
                                }
                            });
                });
            }
        });
    });

    $("#accountSecondBtn").click(function(e)
    {
        e.preventDefault();
        $("#formAccountValidate").slideUp(200);
        $("#formAccountValidate").empty();
        var count = $("input[type=radio][class=accountSwitch]:checked").length;
        //console.log('count : ' + count);
        if (count != 1)
        {
            $("#formAccountValidate").hide();
            if(lang == 'fr') $("#formAccountValidate").append("Merci de choisir au moins un jour et de le valider en tant que premier jour&nbsp;&nbsp;");
			else $("#formAccountValidate").append("Please choose at least one day et select it as first day&nbsp;&nbsp;");
            $("#formAccountValidate").slideDown(400);
        }
        else {
            $('#accountSecond').hide();
            var timezone = $('#hiddenTimeZone').val(); 
            $('#' + timezone + '-li').parent().addClass('active-region'); 
            $('#accountThird').show();
        }
    });

    $(document).on('click','#backToAccount',function()
    {
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
    });

    $("#accountThirdBtn").click(function(e) {
        e.preventDefault();
        if($('#hiddenTimeDisplayed').val() != 0) {
            $('#loaderAccount').show();               
            $('#accountThird').hide();
            $("#messageAccountConfirm").show(2000, function() {
                var form=$("#accountForm");
                $.post('ajax/saveData.php', form.serialize(), function(data) {
                    }).done(function(result) {
                        result = result.trim();
                        $('.section:visible').hide();
                        $('a', '.mainmenu').removeClass( 'active' );
                        $('#account').addClass( 'active' );
                        $('#account').show();
                        $('#accountFirst').hide();
                        $('#accountSecond').hide();
                        $('#accountThird').hide();
                        $('#accountChangeConfirm').show();
                        $('#messageAccountConfirm').hide();
                        $('#menuLogin').attr('href', '#account');
                        $('ul.displayNone').css('display','block');
                        $("#messageAccountConfirm").empty();
                        $('#loaderAccount').hide();
                        if(result == "OK"){
                            if(lang == 'fr') $("#messageAccountConfirm").append('<p style="text-align:center"><br>Vos modifications ont bien été enregistrées</p>');
                            else $("#messageAccountConfirm").append('<p style="text-align:center"><br>Your modifications have been saved</p>');                    
                        }
                        else {
                            if(lang == 'fr') $("#messageAccountConfirm").append('<p style="text-align:center"><br>Aïe, problème<br><br>Apparemment, il y a eu un problème d\'enregistrement en base de données et certaines des modifications n\'ont pas été prises en compte. Contactez-moi grâce au formulaire de contact pour me le signaler.<br><br> Désolé pour cet incident.<br><br>L\'administrateur JW Reading</p>');
                            else $("#messageAccountConfirm").append('<p style="text-align:center"><br>Ouch, problem<br><br>Apparently, there has been a problem during the registration process in the database. Please, inform me about this through the contact form.<br><br> Sorry for this incident.<br><br>The JW Reading administrator</p>');
                        }                            
                        if(lang == 'fr') $("#messageAccountConfirm").append('<p style="text-align:center"><br><button id="backToAccount" class="btn btn-lg btn-default">Revenir à mon compte</button></p>');
                        else $("#messageAccountConfirm").append('<p style="text-align:center"><br><button id="backToAccount" class="btn btn-lg btn-default">Go back to my account</button></p>');
                        $('#messageAccountConfirm').slideDown(400);
                    }); 
            });
        }
        else
        {
            $("#timezoneAccountValidate").hide();
            if(lang == 'fr') $("#timezoneAccountValidate").append("Merci de choisir un horaire d'envoi dans le tableau des fuseaux&nbsp;&nbsp;");
            else $("#timezoneAccountValidate").append("Please choose a sending time in the timezones table&nbsp;&nbsp;");
            $("#timezoneAccountValidate").slideDown(400);
        }
    });

    $("#backToAccountFirstBtn").click(function()
    {
        $("#accountFirst").show();
        $("#accountSecond").hide();
        $('#accountThird').hide();
        return false;
    });

    $("#backToAccountSecondBtn").click(function()
    {
        $("#accountFirst").hide();
        $("#accountSecond").show();
        $('#accountThird').hide();
        return false;
    });

    $(".labelList").click(function(e){ 
        if($(this).attr("data-element") == "radiosAccountLangReading")  {   
            $('#hiddenReadingLang').val($(this).attr("data-value"));
        }
        else if ($(this).attr("data-element") == "radiosAccountLangText") {
            $('#hiddenCommentLang').val($(this).attr("data-value"));
        }
    });

    $('.passConfirm').hide();
    $('#cancelChangePassword').hide();
    if ($(window).width() < 500)
    {
        if(lang == 'fr') {
			$("#changePassword").html("Modifier");
			$('#recordChangePassword').html("Enregistrer");
			$("#passConfirmTitle").html('<br><br>Confirmation du mot de passe<button id="cancelChangePassword" class="btn btn-sm pull-right chgPassword">Annuler</button>');
		}
		else {
			$("#changePassword").html("Change");
			$('#recordChangePassword').html("Save");
			$("#passConfirmTitle").html('<br><br>Password confirm<button id="cancelChangePassword" class="btn btn-sm pull-right chgPassword">Cancel</button>');
        }
	}
    else
    {
		if(lang == 'fr') {
			$("#changePassword").html("Modifier le mot de passe");
			$('#recordChangePassword').html("Enregistrer la modification");
			$("#passConfirmTitle").html('<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Confirmation du mot de passe<button id="cancelChangePassword" class="btn btn-sm pull-right chgPassword">Annuler la modification</button>');
		}
		else {
			$("#changePassword").html("Change password");
			$('#recordChangePassword').html("Save change");
			$("#passConfirmTitle").html('<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password confirm<button id="cancelChangePassword" class="btn btn-sm pull-right chgPassword">Cancel change</button>');
        }
    }
    $("#changePassword").click(function()
    {
		$('#divPassAccountConfirm').width($('#divPassAccount').width());
		$('#cancelChangePassword').offset({ left: 5 });	
        $('.passConfirm').slideDown(400);
        $('#cancelChangePassword').width($('#recordChangePassword').width() + 5);
        $('#cancelChangePassword').offset({top: $('#cancelChangePassword').offset().top - 5});
        $('#cancelChangePassword').slideDown(400);
        password = $('#passAccount').val();
        $('#passAccountConfirm').val('');
        $('#passAccount').val('');
        $('#passAccountConfirm').removeAttr('readonly');
        $('#passAccount').removeAttr('readonly');
    });

    $('#cancelChangePassword').click(function(e) {
        e.preventDefault();
        //alert("resolution:"+$(window).width()+" x "+$(window).height());
        $('.passConfirm').slideUp(400);
        $('#cancelChangePassword').offset({top: $('#cancelChangePassword').offset().top + 5});
        $('#cancelChangePassword').slideUp(400);
        $("#changePasswordValidate").slideUp(400);
        $('#passAccountConfirm').val(password);
        $('#passAccount').val(password);
        $('#passAccountConfirm').prop("readonly", true);
        $('#passAccount').prop("readonly", true);
    });

    $('#recordChangePassword').click(function() {
        if ($('#passAccount').val() == '') {
            $("#changePasswordValidate").hide();
            $("#changePasswordValidate").empty();
            if(lang == 'fr') $("#changePasswordValidate").append("Le mot de passe ne peut pas être vide&nbsp;&nbsp;");
			else $("#changePasswordValidate").append("The password cannot be empty&nbsp;&nbsp;");
            $("#changePasswordValidate").slideDown(400);
        }
        else if ($('#passAccount').val().length < 4) {
            $("#changePasswordValidate").hide();
            $("#changePasswordValidate").empty();
            if(lang == 'fr') $("#changePasswordValidate").append("Le mot de passe doit avoir au moins 4 caractères&nbsp;&nbsp;");
			else $("#changePasswordValidate").append("The password needs to be at leat 4 characters long&nbsp;&nbsp;");
            $("#changePasswordValidate").slideDown(400);
        }
        else if ($('#passAccount').val() != $('#passAccountConfirm').val()) {
            $("#changePasswordValidate").hide();
            $("#changePasswordValidate").empty();
            if(lang == 'fr') $("#changePasswordValidate").append("Les mots de passe ne correspondent pas&nbsp;&nbsp;");
			else $("#changePasswordValidate").append("Both password fields do not match&nbsp;&nbsp;");
            $("#changePasswordValidate").slideDown(400);
        }
        else {
            var id = $('#accountId').val();
            var pass = $('#passAccount').val();
            $.post('ajax/changePassword.php', {'pass': pass, 'id': id}, function(data) {
            }).done(function(result) {
                result = result.trim();
                if (result == '0') {
                    $("#changePasswordValidate").hide();
                    $("#changePasswordValidate").empty();
                    if(lang == 'fr') $("#changePasswordValidate").append("Malheureusement, la modification du mot de passe a échoué. Veuillez réessayer ultérieurement");
                    else $("#changePasswordValidate").append("Unfortunately, the password modification failed. Try again later");
					$("#changePasswordValidate").slideDown(400);
                }
                else {
                    var res = result.split('OK.');
                    $('.passConfirm').slideUp(400);
                    $('#cancelChangePassword').offset({top: $('#cancelChangePassword').offset().top + 5});
                    $('#cancelChangePassword').slideUp(400);
                    password = res[1];
                    $('#passAccount').val(password);
                    $('#passAccountConfirm').prop("readonly", true);
                    $('#passAccount').prop("readonly", true);
                    $("#changePasswordValidate").hide();
                    $("#changePasswordValidate").empty();
                    if(lang == 'fr') $("#changePasswordValidate").append("Le mot de passe a bien été modifié&nbsp;&nbsp;");
					else $("#changePasswordValidate").append("The password has been modified&nbsp;&nbsp;");
                    $("#changePasswordValidate").slideDown(400);
                }
            });
        }
    });
	
	$('#sendContactForm').click(function(e) {
        e.preventDefault();
		$("#emailContactValidate").hide();
		$("#msgContactValidate").hide();
		
		var email = $('#emailContact').val();
		var msg = $('#msgContact').val();
		if (validateEmail(email) == false) {
			$("#emailContactValidate").hide();
			if(lang == 'fr') $("#emailContactValidate").html('Adresse email non valide&nbsp;&nbsp;');
			else $("#emailContactValidate").html('This email address is not valid&nbsp;&nbsp;');
			$("#emailContactValidate").slideDown(400);
		}
		else if (!$.trim(msg)) {
			$("#msgContactValidate").hide();
			if(lang == 'fr') $("#msgContactValidate").html('Message vide. Impossible d\'envoyer&nbsp;&nbsp;');
			else $("#msgContactValidate").html('Empty message. Can\'t be sent&nbsp;&nbsp;');
			$("#msgContactValidate").slideDown(400);
		}
		else $('#contactForm').submit();
	});
	
	$('#backContact').click(function(e) {
        e.preventDefault();
		$('#contactForm').show();
		$('#confirmContact').hide();
	});
	
	$('#forgottenDiv').hide();
	$('#forgottenPass').click(function(e) {
        e.preventDefault();
		$("#forgottenValidate").hide();
		$('#forgottenInput').val($('#loginInput').val());
		$('#loginDiv').hide();
		$('#forgottenDiv').show();
	});
	
	$("#backToLogin").click(function(e)
    {
		e.preventDefault();		
		$('#forgottenDiv').hide();
		$('#loginDiv').show();
	});
	
	$("#forgottenBtn").click(function(e)
	{
		e.preventDefault();
		var email = $('#forgottenInput').val();
		if (validateEmail(email) == false)
		{
			$("#forgottenValidate").hide();
			if(lang == 'fr') $("#forgottenValidate").append('Adresse email non valide&nbsp;&nbsp;');
			else $("#forgottenValidate").append('This email address is not valid&nbsp;&nbsp;');
			$("#forgottenValidate").slideDown(400);
		}

		else {
			$.post('ajax/sendNewPassword.php', {'email': email, 'lang': lang}, function(data) {
			}).done(function(result) {
				result = result.trim();
				if(result == "OK"){
					$("#forgottenValidate").hide();
					if(lang == 'fr') $("#forgottenValidate").html("Un nouveau mot de passe a été envoyé à votre adresse mail&nbsp;&nbsp;");
					else $("#forgottenValidate").html("A new password has been sent to your email address&nbsp;&nbsp;");
					$("#forgottenValidate").slideDown(400);
				}
				else {
					$("#forgottenValidate").hide();
					if(lang == 'fr') $("#forgottenValidate").html("Malheureusement, la génération du mot de passe a échoué. Veuillez réessayer ultérieurement");
					else $("#forgottenValidate").html("Unfortunately, the password generation failed. Try again later");
					$("#forgottenValidate").slideDown(400);
				}
			});
		}
	});
	
	$(".linkLang").click(function()
	{
		var linkLang = $(this).text().toLowerCase();
		window.location.replace("../" + linkLang);
	});

    $('.select-utc').change(function() {
        $('.list-header').hide();
        $('.list-utc').hide();
        $('.list-expl').hide();
        $('#subscribeChosenTime').val(this.value);
        $('#tip2').hide();
        if(lang == 'fr') $('#select-phrase').text('Vous avez sélectionné de recevoir votre email à ' + $('select').children(':selected').text() + "h chaque jour pour le fuseau horaire UTC " + $(this).data('utc'));
        else  $('#select-phrase').text('You have chosen to receive your email everyday at ' + $('select').children(':selected').text() + ".00 according to your timezone");
    });

    $('.account-select-utc').change(function() {
        $('.list-header').hide();
        $('.list-utc').hide();
        $('.list-expl').hide();
        $('#hiddenTimeId').val(this.value);
        $('#hiddenTimeDisplayed').val($('select').children(':selected').text());
        $('#hiddenTimeCities').val($(this).data('cities'));
        $('#hiddenTimeUTC').val($(this).data('utc'));
        $('#iddenTimeZone').val($(this).data('zone'));
        $('#tipAccount').hide();
        if(lang == 'fr') $('#account-select-phrase').text('Vous avez sélectionné de recevoir votre email à ' + $('select').children(':selected').text() + "h chaque jour pour le fuseau horaire UTC " + $(this).data('utc'));
        else  $('#account-select-phrase').text('You have chosen to receive your email everyday at ' + $('select').children(':selected').text() + ".00 according to your timezone");
    });
	
	$(".questionTitle").click(function() {
		var str = $(this).attr('id');
		var pattern = /[0-9]+/;
		var id = str.match(pattern);		
		$("#answer"+id).toggle("slow");
	});

    $(".closeTip").click(function() {      
        $(".tip").slideUp(400);
    });
		
    function validateEmail(sEmail) {
        var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        if (filter.test(sEmail)) {
            return true;
        }
        else {
            return false;
        }
    }
});