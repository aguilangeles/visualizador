function getImageInfo(items, id)
{
    var infoId = items;
    id = infoId;
    var query = $("#query_" + id).html();

    query = encodeURIComponent(query);

    items = $("#imageData" + items).html();
    if (!$("#row" + id).hasClass('fetched'))
    {
        $("#downloading").dialog({title: "Cargando Datos"})
        $("#downloading").dialog("open");
        $("#row" + id).addClass('fetched');
        $.ajax({url: "/getImagesById/getImagesById",
            context: document.body,
            type: "POST",
            data: "items=" + items + "&infoId=" + infoId + "&query=" + query,
            dataType: "json",
            success: function(data) {
                $("#row" + id).prepend(data.html);
                $("#imageData" + id).empty();
                $("#imageData" + id).html(data.imageData);
                $("#downloading").dialog("close");
            }
        });
    }
    $("#row" + id).toggle();
}


