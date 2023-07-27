window.onload = function() {
    questLists(1);

    var onglets = document.getElementById("onglets");
    var contenus = document.getElementById("contents");

    var liOnglet = onglets.getElementsByTagName("li");
    var liContenu = contenus.getElementsByClassName("quest_list");

    liOnglet[0].className = "actif";

    for (var i = 0; i < liOnglet.length; i++){
        liOnglet[i].num = i;

        liOnglet[i].addEventListener("click", function(){
        
            for (var j = 0; j < liOnglet.length; j++){
                liOnglet[j].className = "";
            }

            liOnglet[this.num].className ="actif";
            var idCat = liOnglet[this.num].value;
            questLists(idCat);
        });
    }

    /** AJOUT PART 5 */
    verifQuest();
}

function questLists(idCat) {
    $.ajax({
        type: "GET",
        url: "game.php?page=questsAjax",
        data: "categorie_id="+idCat,
        success: function (data) {
            data = jQuery.parseJSON(data);
            $(".quest_list").remove("");
            $("#contents").append(data);
        },
        error: function(error) {
            alert(error.responseText);
        }
    });
}

/** Confimation de la l'acceptation de la quête PART 5 */
function NotifyBoxAdm(color_alert, text) {
	tip = $('#tooltip')
	tip.html(text).addClass('notify').addClass(color_alert).css({
		left : (($(window).width() - $('#leftmenu').width()) / 2 - tip.outerWidth() / 2) + $('#leftmenu').width(),
	}).show();
	window.setTimeout(function(){tip.fadeOut(1000, function() {tip.removeClass('notify')})}, 2000);
}

function questConfirm(idQuest) {
    $.ajax({
        type: "POST",
        url: "game.php?page=questsAjax&mode=runQuest",
        data: "quest_id="+idQuest,
        success: function (data) {
            data = jQuery.parseJSON(data);
            NotifyBoxAdm(data.error, data.message);
            $("#quest_badge_"+data.questID).removeClass("danger");
            $("#quest_badge_"+data.questID).addClass(data.badgeStyle);
            $("#quest_badge_"+data.questID).text(data.badgeContent);
            $("#quest_button_"+data.questID).remove("button");
        },
        error: function(error) {
            alert(error.responseText);
        }
    });
}

function verifQuest(){
    $.ajax({
        type: "GET",
        url: "game.php?page=questsAjax&mode=verifQuest",
        success: function (data) {
            data = jQuery.parseJSON(data);
            $.each(data, function(key, value) {
                $("#btn_quest_"+key).remove();
                $("#progresse_bar_"+key).remove();

                if(value.user_quest_users_finish < 2) {
                    if(value.user_quest_finish == true) {
                        $("#quest_button_"+key).removeClass("progresse_bar_border");
                        $("#quest_button_"+key).append("<button id='btn_quest_"+key+"' onclick='javascript:questFinish("+key+")'>Terminé</button>");
                    } else {
                        var pourcent = value.count_current_pourcent;
                        (pourcent < 50) ? colors_bar = "#AB3232" : (pourcent >= 50 && pourcent < 80) ? colors_bar = "#BC622A" : colors_bar = "#32AB32";
                        $("#quest_button_"+key).addClass("progresse_bar_border");
                        $("#quest_button_"+key).append("<div class='progresseBar' id='progresse_bar_"+key+"'></div>");
                        $("#progresse_bar_"+key).css({width: pourcent+"%", backgroundColor: colors_bar});
                        $("#progresse_bar_"+key).html("<span>"+pourcent+"%</span>");
                    }
                }
            });
        },
        error: function(error) {
            console.log(error.responseText);
        }
    });
    setTimeout(verifQuest,1000);
}

function questFinish(idQuest) {
    $.ajax({
        type: "POST",
        url: "game.php?page=questsAjax&mode=finishQuest",
        data: "quest_id="+idQuest,
        success: function (data) {
            data = jQuery.parseJSON(data);
            console.log(data);
            NotifyBoxAdm(data.error, data.message);
            $("#quest_badge_"+data.questID).removeClass("warning");
            $("#quest_badge_"+data.questID).addClass(data.badgeStyle);
            $("#quest_badge_"+data.questID).text(data.badgeContent);
            $("#quest_button_"+data.questID).remove();
        },
        error: function(error) {
            alert(error.responseText);
        }
    });
}