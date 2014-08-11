var docLevel1Id;
var docLevel2Id;
var docLevel3Id;
var docLevel4Id;
var searchType;
var groupType;
var rotuloId;
var rotate_angle = 0;
var ultAnchoImg;

function SearchDocs(page)
{
      if (checkGroupType()) {
	    if (page == null)
	    {
		  page = 1;
	    }
	    if ($("#searchType_0").attr("checked"))
	    {
		  searchType = "Exacta";
	    }
	    else
	    {
		  searchType = "Parecida";
	    }
	    groupType = ($("#groupType_1").attr("checked")) ? 'image' : 'carat';
	    $('html, body').animate({scrollTop: 0}, 'slow');
	    docLevel1Id = $("#docLevel1").val();
	    docLevel2Id = $("#docLevel2").val();
	    docLevel3Id = $("#docLevel3").val();
	    docLevel4Id = $("#docLevel4").val();
	    $("#results").empty().html('<img src="../images/ajax-loader.gif" />');
	    var dataCMeta = $("input[id='CMETA_']").map(function() {
		  return $(this).val();
	    }).get();
	    var CmetaFields = '';
	    for (i in dataCMeta)
	    {
		  CmetaFields = CmetaFields + "&CMETA_[" + i + "]=" + dataCMeta[i];
	    }
	    var dataOcrMeta = $("input[id='OCR_']").map(function() {
		  return $(this).val();
	    }).get();
	    var OcrFields = '';
	    for (i in dataOcrMeta)
	    {
		  OcrFields = OcrFields + "&OCR_[" + i + "]=" + dataOcrMeta[i];
	    }
	    $.ajax({url: "/typedocs_/searchByDocType",
		  context: document.body,
		  type: "POST",
		  data: "page=" + page + "&docLevel1=" + docLevel1Id + "&docLevel2=" + docLevel2Id + "&docLevel3=" + docLevel3Id + "&docLevel4=" + docLevel4Id + CmetaFields + OcrFields + "&searchType=" + searchType + "&groupType=" + groupType,
		  dataType: "text",
		  success: function(data) {
			$("#results").empty();
			$("#results").append(data);
			//add tlable sorted
			$("#box-table-a")
				.tablesorter({widthFixed: false, widgets: ['zebra']})
				.tablesorterPager({container: $(".pager")});
				
		  }
	    });
      }
      else {
	    alert('Si va a agrupar por imagen, debe filtrar su búsqueda con al menos 1 metadato de imagen.');
	    $("#groupType_0").attr("checked", "checked");
      }
}

