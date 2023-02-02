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
}

function questLists(idCat) {
    $.ajax({
        type: "GET",
        url: "game.php?page=questsajax",
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