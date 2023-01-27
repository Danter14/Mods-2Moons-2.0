window.onload = function(){
    var onglets = document.getElementById("onglets");
    var contenus = document.getElementById("contenus");

    var liOnglet = onglets.getElementsByTagName("li");
    var liContenu = contenus.getElementsByClassName("onglet_content");

    liOnglet[0].className = "actif_tab";
    liContenu[0].className += " actif";

    for (var i = 0; i < liOnglet.length; i++){
        liOnglet[i].num = i;

        liOnglet[i].addEventListener("click", function(){
        
            for (var j = 0; j < liOnglet.length; j++){
                liOnglet[j].className="";
                liContenu[j].className="onglet_content";
            }

            liOnglet[this.num].className ="actif_tab";
            liContenu[this.num].className +=" actif";
        });
    }

    $(document).on('submit','#addCategorie',function(e)
    {
        e.preventDefault();
        var catId = $("input[name=categorie_add]").val();
        addonCategorie(catId);
    });

    $(document).on('submit','#editCategorie',function(e)
    {
        e.preventDefault();
        const formData = $(this).serialize();
        editCategorie(formData);
    });

    $(document).on('submit','#addQuest',function(e)
    {
        e.preventDefault();
        const formData = $(this).serialize();
        addonQuest(formData);
    });

    $(document).on('submit','#editQuest',function(e)
    {
        e.preventDefault();
        const formData = $(this).serialize();
        editQuest(formData);
    });
}

/**
 * Totooltip personaliser
 */
function NotifyBoxAdm(color_alert, text) {
	tip = $('#tooltip')
	tip.html(text).addClass('notify').addClass(color_alert).css({
		left : (($(window).width() - $('#leftmenu').width()) / 2 - tip.outerWidth() / 2) + $('#leftmenu').width(),
	}).show();
	window.setTimeout(function(){tip.fadeOut(1000, function() {tip.removeClass('notify')})}, 2000);
}

/**
 * Ajout d'une nouvelle catégorie
 */
function addonCategorie(catId) {
    $.ajax({
        type: "POST",
        url: "admin.php?page=quests&action=add_categorie",
        data: "categorie_add="+catId,
        success: function (data) {
            data = jQuery.parseJSON(data);
            NotifyBoxAdm(data.alert, data.message);
            if(catId-1 == 0) {
                $(".categories_tab").after(data.content);
            } else {
                $(".cat"+(catId-1)).after(data.content);
            }
        },
        error: function(error) {
            alert(error.responseText);
        }
    });
}

/**
 * Ouverture de la modal pour l'édition de la catégorie
 */
function modalCategorie(idCat) {
    $('body').append('<div style="" id="loadingDiv"><div class="loader">Loading...</div></div>');
    $.ajax({
        type:"GET",
        data : "idCat="+idCat,
        url:"admin.php?page=quests&action=modal_categorie",
        success: function(data) {
            data = jQuery.parseJSON(data);
            $( "#loadingDiv" ).fadeOut(500, function() {
                $( "#loadingDiv" ).remove();
            });
            $(".modal_popup").addClass("active");
            $("#hidden_cat").val(data.id);
            $("#form_cat_id").val(data.catId);
            $("#test_cat").fadeIn(1000).html("Edition de la catégorie "+data.name);
        },
        error: function(msg) {
            $(".modal-body").addClass("tableau_msg_erreur").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).html('<div style="margin-right:auto; margin-left:auto; text-align:center">Impossible de charger cette page</div>');
            $( "#loadingDiv" ).fadeOut(500, function() {
                $( "#loadingDiv" ).remove();
            });
        },
    });
}

/**
 * Fermeture de la modal
 */
function modalCategorieClose() {
    $(".modal_popup").removeClass("active");
}

/**
 * Edition de la catégorie
 */
function editCategorie(params) {
    $.ajax({
        type: "POST",
        url: "admin.php?page=quests",
        data: params,
        success: function(data) {
            data = jQuery.parseJSON(data);
            NotifyBoxAdm(data.alert, data.message);
            if(data.alert == "success") {
                setTimeout(function(){
                    location.reload();
                },3000);
            }
        },
        error: function(error) {
            alert(error.responseText);
        }
    });
}

/**
 * Supprimer une catégorie
 */
function deleteCategorie(idCat) {
    $.ajax({
        type: "POST",
        url: "admin.php?page=quests&action=delete_categorie",
        data: "id="+idCat,
        success: function(data) {
            data = jQuery.parseJSON(data);
            $('#cat_'+idCat).remove();
            NotifyBoxAdm(data.alert, data.message);
        },
        error: function(error) {
            alert(error.responseText);
        }
    });
}

/**
 * Ouverture de la modal pour l'édition d'une Quêtes
 */
function modalQuest(questID) {
    $('body').append('<div style="" id="loadingDiv"><div class="loader">Loading...</div></div>');
    $.ajax({
        type:"GET",
        data : "questID="+questID,
        url:"admin.php?page=quests&action=modal_quest",
        success: function(data) {
            data = jQuery.parseJSON(data);
            $( "#loadingDiv" ).fadeOut(500, function() {
                $( "#loadingDiv" ).remove();
            });
            $(".modal_popup_quest").addClass("active");
            $("#title_quest").fadeIn(1000).html("Edition de la quête "+data[0].quest_title);
            $("#hidden_quest").val(data[0].questsID);
            if(data[0].quest_actif == 1) $("#actif_quete_modal").prop('checked', true);
            $("#quest_categorie_modal option[value='"+data[0].questsCategories+"']").prop("selected", true);
            $("#quest_title").val(data[0].quest_title);
            $("#quest_description").val(data[0].quest_description);
            $("#quest_obj_level").val(data[0].quest_objectif_level);
            $("#quest_obj_modal option[value='"+data[0].quest_objectif+"']").prop("selected", true);
            $("#quest_reward_point").val(data[0].quest_points_reward);
            $("#quest_reward_metal").val(data[0].quest_metal_reward);
            $("#quest_reward_crystal").val(data[0].quest_crystal_reward);
            $("#quest_reward_deuterium").val(data[0].quest_deuterium_reward);
            $("#quest_reward_darkmatter").val(data[0].quest_darkmatter_reward);
            if(data[0].quest_event == 1) $("#actif_quete_event_modal").prop('checked', true);
            $("#quest_event_time").val(data[0].quest_time_finish_event);
        },
        error: function(msg) {
            $(".modal-body").addClass("tableau_msg_erreur").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).html('<div style="margin-right:auto; margin-left:auto; text-align:center">Impossible de charger cette page</div>');
            $( "#loadingDiv" ).fadeOut(500, function() {
                $( "#loadingDiv" ).remove();
            });
        },
    });
}

/**
 * Fermeture de la modal Quête
 */
function modalQuestClose() {
    $(".modal_popup_quest").removeClass("active");
}

/**
 * Ajout d'une nouvelle quête
 */
function addonQuest(params) {
    $.ajax({
        type: "POST",
        url: "admin.php?page=quests&action=add_quest",
        data: params,
        success: function (data) {
            data = jQuery.parseJSON(data);
            NotifyBoxAdm(data.alert, data.message);
            if(data.alert == "success") {
                setTimeout(function(){
                    location.reload();
                }, 3000);
            }
        },
        error: function(error) {
            alert(error.responseText);
        }
    });
}

/**
 * Edition d'une Quêtes
 */
function editQuest(params) {
    $.ajax({
        type: "POST",
        url: "admin.php?page=quests",
        data: params,
        success: function(data) {
            data = jQuery.parseJSON(data);
            NotifyBoxAdm(data.alert, data.message);
            if(data.alert == "success") {
                setTimeout(function(){
                    location.reload();
                },3000);
            }
        },
        error: function(error) {
            alert(error.responseText);
        }
    });
}

/**
 * Suppression d'une Quêtes
 */
function deleteQuest(idQuest) {
    $.ajax({
        type: "POST",
        url: "admin.php?page=quests&action=delete_quest",
        data: "id="+idQuest,
        success: function(data) {
            data = jQuery.parseJSON(data);
            $('#quest_'+idQuest).remove();
            NotifyBoxAdm(data.alert, data.message);
        },
        error: function(error) {
            alert(error.responseText);
        }
    });
}