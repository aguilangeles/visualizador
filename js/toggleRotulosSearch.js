function toggleRotulosSearch()
{
    var rotuloDesc = $("select[id*='rotulo']").find(':selected').text();
    rotuloId = $("select[id*='rotulo']").find(':selected').val();
    if (rotuloId != "0")
    {
        $("#first-search").slideToggle();
        $("#second-rotulos-search").slideToggle();
        $.ajax({url: "/rotuloses/getRotulos",
//        $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/searchRotulos/searchRotulos",
            context: document.body,
            type: "POST",
            data: "rotulo_id=" + rotuloId,
            dataType: "text",
            success: function(data) {
                $("#MetaRotulosCarats").empty();
                $("#MetaRotulosCarats").append(data);
            }
        });
    }
    else
    {
        alert("Debe seleccionar un rótulo.");
    }
}


