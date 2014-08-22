var docLevel1Id;
var docLevel2Id;
var docLevel3Id;
var docLevel4Id;
var searchType;
var groupType;
var rotuloId;
var rotate_angle = 0;
var ultAnchoImg;


function SearchGralDocs(page, docType)
{
    var Docs = [];
    var doc = $("#searchField").val();
    if (page == null)
    {
        page = 1;
    }
    if ($("#searchGeneralType_0").attr("checked"))
    {
        searchType = "Exacta";
    }
    else
    {
        searchType = "Parecida";
    }
    $("#filterRestict :checked").each(function() {
        Docs.push($(this).val());
    });
    if (Docs == "") {
        alert("Debe seleccionar al menos un tipo documento.");
    }
    else if (doc == "") {
        alert("La búsqueda no se puede realizar, si el campo 'Buscar', está vacío.");
    }
    else {
        $("#resultsGeneral").empty().html('<img src="../images/ajax-loader.gif" />');
        $.ajax({url: "/generaldoc/searchGeneralDoc",
            context: document.body,
            type: "POST",
            data: "page=" + page + "&docs=" + Docs + "&field=" + doc + "&searchType=" + searchType + "&docType=" + docType,
            dataType: "text",
            success: function(data) {
                $("#resultsGeneral").empty();
                $("#resultsGeneral").append(data);
			$('#box-table-a-childRow td').hide();
			$("#box-table-a").tablesorter({
			      widthFixed: false,
			      sortReset: true,
			      sortRestart: true
			}
			).tablesorterPager({
			      container: '.pager'
			      , ajaxUrl: null
			      , customAjaxUrl: function(table, url) {
				    return url;
			      }
			      , ajaxProcessing: function(ajax) {
				    if (ajax && ajax.hasOwnProperty('data')) {
					  // return [ "data", "total_rows" ]; 
					  return [ajax.data, ajax.total_rows];
				    }

			      }
			      , output: ' {page}/{totalPages}'
			      , updateArrows: false
			      , page: 0
			      , size: 10
			      , fixedHeight: false
			      , savePages: false
			      , storageKey: 'tablesorter-pager'
			      , removeRows: false
			      , positionFixed: false

			});
			$('#box-table-a').trigger("update");
		  }
	    });
    }
}


