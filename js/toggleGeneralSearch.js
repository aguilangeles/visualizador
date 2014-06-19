var docLevel1Id;
var docLevel2Id;
var docLevel3Id;
var docLevel4Id;
var searchType;
var groupType;
var rotuloId;
var rotate_angle = 0;
var ultAnchoImg;

function toggleGeneralSearch()
{
    $("#results").empty();
    $("#first-search").slideToggle();
    $("#second-general-search").slideToggle();
    $.ajax({url: "/searchGeneral/searchGeneral",
//    $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/searchGeneral/searchGeneral",
        context: document.body,
        type: "POST",
        dataType: "text",
        success: function(data) {
            $("#filterRestict").empty();
            $("#filterRestict").append(data);
        }
    });
}
