    var docLevel1Id;
    var docLevel2Id;
    var docLevel3Id;
    var docLevel4Id;
    var searchType;
    var groupType;
    var rotuloId;
    var rotate_angle = 0;
    var ultAnchoImg;

    function showImage(id, currIndex, currSubIndex)
    {
        $("#image").html('<img src="../images/ajax-loader.gif" />');
        var imageList = $("#imageList" + id).html();
        var imageList2 = $("#imageList2" + id).html();
        rotate_angle = 0;
        $("#image").empty();
        var widthSize = ($(window).width() - 200);
        ultAnchoImg = widthSize;
        $.ajax({url: "/viewImage/viewImage",
            context: document.body,
            type: "POST",
            data: "imageList=" + imageList + "&imageList2=" + imageList2 + "&widthsize=" + widthSize + "&currIndex=" + currIndex + "&currSubIndex=" + currSubIndex + "&rotar=" + rotate_angle,
            dataType: "text",
            success: function(data) {
                $("#image").css("text-align", "center");
                $("#image").empty();
                $("#image-meta").empty();
                $("#image").children().remove();
                $("#image").append(data);
            }});
        $("#showImage").dialog({width: widthSize});
        $("#showImage").dialog({height: $(window).height() - 50});
        $("#showImage").dialog("open");
    }
    
    
    function showImageSmall(id, currIndex, currSubIndex)
    {
        $("#image").html('<img src="../images/ajax-loader.gif" />');
        var imageList = $("#imageList" + id).html();
        var imageList2 = $("#imageList2" + id).html();
        rotate_angle = 0;
        $("#image").empty();
        var widthSize = ($(window).width() - 200);
        ultAnchoImg = 500;
        $.ajax({url: "/viewImage/viewImage",
            context: document.body,
            type: "POST",
            data: "imageList=" + imageList + "&imageList2=" + imageList2 + "&widthsize=500&currIndex=" + currIndex + "&currSubIndex=" + currSubIndex + "&rotar=" + rotate_angle,
            dataType: "text",
            success: function(data) {
                $("#image").css("text-align", "center");
                $("#image").empty();
                $("#image-meta").empty();
                $("#image").children().remove();
                $("#image").append(data);
            }});
        $("#showImage").dialog({width: widthSize});
        $("#showImage").dialog({height: $(window).height() - 50});
        $("#showImage").dialog("open");
    }

    function rotarImagen(id, currIndex, currSubIndex)
    {
        $("#image").html('<img src="../images/ajax-loader.gif" />');
        var imageList = $("#imageList" + id).html();
        var imageList2 = $("#imageList2" + id).html();
        $("#image").empty();
        var widthSize = ($(window).width() - 200);
        rotate_angle = (rotate_angle >= 270) ? 0 : rotate_angle + 90;
        //$('#imgprincipal').rotate({ angle : rotate_angle });
        $.ajax({url: "/viewImage/viewImage",
            context: document.body,
            type: "POST",
            data: "imageList=" + imageList + "&imageList2=" + imageList2 + "&widthsize=" + ultAnchoImg + "&currIndex=" + currIndex + "&currSubIndex=" + currSubIndex + "&rotar=" + rotate_angle,
            dataType: "text",
            success: function(data) {
                $("#image").css("text-align", "center");
                $("#image").empty();
                $("#image-meta").empty();
                $("#image").children().remove();
                $("#image").append(data);

            }});
        $("#showImage").dialog({width: widthSize});
        $("#showImage").dialog({height: $(window).height() - 50});
        $("#showImage").dialog("open");

    }


