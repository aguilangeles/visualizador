/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var docLevel1Id;
var docLevel2Id;
var docLevel3Id;
var docLevel4Id;
var searchType;
var groupType;
var rotuloId;
var rotate_angle = 0;
var ultAnchoImg;
function toggleFirstSearch()
{
    $("#results").empty();
    var docLevel1 = $("select[id*='docLevel1']").find(':selected').text();
    docLevel1Id = $("select[id*='docLevel1']").find(':selected').val();
    var docLevel2 = $("select[id*='docLevel2']").find(':selected').text();
    docLevel2Id = $("select[id*='docLevel2']").find(':selected').val();
    var docLevel3 = $("select[id*='docLevel3']").find(':selected').text();
    docLevel3Id = $("select[id*='docLevel3']").find(':selected').val();
    var docLevel4 = $("select[id*='docLevel4']").find(':selected').text();
    docLevel4Id = $("select[id*='docLevel4']").find(':selected').val();
    if (docLevel1Id != "0" || docLevel2Id != "0" || docLevel3Id != "0" || docLevel4Id != "0")
    {
        $("#first-search").slideToggle();
        $("#second-search").slideToggle();
        $("#filterLevel1").empty();
        $("#filterLevel2").empty();
        $("#filterLevel3").empty();
        $("#filterLevel4").empty();
        if (docLevel1Id != "0") {
            $("#filterLevel1").append("Nivel 1 >> " + docLevel1);
            $("#filterLevel1").show();
        }
        else {
            $("#filterLevel1").hide();
        }
        if (docLevel2Id != "0") {
            $("#filterLevel2").append("Nivel 2 >> " + docLevel2);
            $("#filterLevel2").show();
        }
        else {
            $("#filterLevel2").hide();
        }
        if (docLevel3Id != "0") {
            $("#filterLevel3").append("Nivel 3 >> " + docLevel3);
            $("#filterLevel3").show();
        }
        else {
            $("#filterLevel3").hide();
        }
        if (docLevel4Id != "0") {
            $("#filterLevel4").append("Nivel 4 >> " + docLevel4);
            $("#filterLevel4").show();
        }
        else {
            $("#filterLevel4").hide();
        }
        $.ajax({url: "/searchMetaCarat/searchMetaCarat",
            context: document.body,
            type: "POST",
            data: "docLevel1=" + docLevel1Id + "&docLevel2=" + docLevel2Id + "&docLevel3=" + docLevel3Id + "&docLevel4=" + docLevel4Id,
            dataType: "text",
            success: function(data) {
                $("#filtersMeta").empty();
                $("#filtersMeta").append(data);
            }
        });
    }
    else {
        alert("Debe seleccionar al menos un documento.");
    }
}

