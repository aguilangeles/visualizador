var docLevel1Id;
var docLevel2Id;
var docLevel3Id;
var docLevel4Id;
var searchType;
var groupType;
var rotuloId;
var rotate_angle = 0;
var ultAnchoImg;


function setOrder(id, order, c1, c2, c3, c4) {
    oldPos = order;
    newPos = $("#orden_" + id).val();
    if (isNaN(newPos)) {
        alert("Escriba solo numeros");
        $("#orden_" + id).val(oldPos);
    }
    else {
        if (newPos <= 0) {
            alert("La posición debe ser mayor a cero.");
            $("#orden_" + id).val(oldPos);
        }
        else {
            //mostrar el gif
            $.ajax({url: "/setOrder/setOrder",
                context: document.body,
                type: "POST",
                data: "id=" + id + "&oldPos=" + oldPos + "&newPos=" + newPos + "&c1=" + c1 + "&c2=" + c2 + "&c3=" + c3 + "&c4=" + c4,
                dataType: "text",
                
                success: function(data) {
                    //ocultar el gif
                    if (data != '')
                    {
                        alert(data);
                    }
                    else
                    {
                        alert("Refresque la búsqueda, para ver reflejados los cambios.");
                    }
                }});
        }
    }
}


