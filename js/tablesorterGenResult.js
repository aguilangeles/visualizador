/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


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