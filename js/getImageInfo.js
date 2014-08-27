function getImageInfo(items, id)
{
      var infoId = items;
      id = infoId;
      var query = $("#query_" + id).html();

      query = encodeURIComponent(query);

      items = $("#imageData" + items).html();
      if (!$("#row" + id).hasClass('fetched'))
      {
	    $("#downloading").dialog({title: "Cargando Datos"});
	    $("#downloading").dialog("open");
	    $("#row" + id).addClass('fetched');

	    $.ajax({url: "/images/getImagesById",
		  context: document.body,
		  type: "POST",
		  data: "items=" + items + "&infoId=" + infoId + "&query=" + query,
		  dataType: "json",
		  success: function(data) {
			$("#row" + id).prepend(data.html);
			$("#imageData" + id).empty();
			$("#imageData" + id).html(data.imageData);
			$("#downloading").dialog("close");
			$("#" + id).tablesorter({
			      sortReset: true,
			      sortRestart: true
			});
		  }
	    });
      }
      $("#row" + id).toggle();

}
function seeMore(id)
{
      $("#downloading").dialog({title: "Cargando Datos"});
      $("#downloading").dialog("open");
      var query = $("#query_" + id).html();
      var imageList = $("#imageList" + id).html();
      $.ajax({url: "/more/seeMore",
	    context: document.body,
	    type: "POST",
	    data: "query=" + query + "&imageList=" + imageList,
	    dataType: "json",
	    success: function(data) {
		  $("#" + data.id).append(data.table);
		  $("#imageList" + id).empty();
		  $("#imageList" + id).html(data.imageList);
		  if (!data.hasMore) {
			$("#seeMore" + data.id).remove();
		  }
		  $("#downloading").dialog("close");
		$("#"+id).tablesorter({
			sortReset: true,
			sortRestart: true
		}
			).trigger('update'); 	
		$("#"+id).tablesorter().trigger('"appendCache"'); 	
	    }
      });
}

