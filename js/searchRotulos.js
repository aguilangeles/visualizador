var docLevel1Id;
var docLevel2Id;
var docLevel3Id;
var docLevel4Id;
var searchType;
var groupType;
var rotuloId;
var rotate_angle = 0;
var ultAnchoImg;

function SearchRotulos(page)
{
    if (page == null)
    {
        page = 1;
    }
    if ($("#searchRotType_0").attr("checked"))
    {
        searchType = "Exacta";
    }
    else
    {
        searchType = "Parecida";
    }
    $("#resultsRotulos").empty().html('<img src="../images/ajax-loader.gif" />');
    var dataCMeta = $("input[id='CMETA_']").map(function() {
        return $(this).val();
    }).get();
    var CmetaFields = '';
    for (i in dataCMeta)
    {
        CmetaFields = CmetaFields + "&CMETA_[" + i + "]=" + dataCMeta[i];
    }
    $.ajax({url: "/searchByRotulo/searchByRotulo",
        context: document.body,
        type: "POST",
        data: "page=" + page + "&rotulo=" + rotuloId + CmetaFields + "&searchType=" + searchType,
        dataType: "text",
        success: function(data) {
            $("#resultsRotulos").empty();
            $("#resultsRotulos").append(data);
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


