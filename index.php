<!DOCTYPE html>
<html>
<?php
	ob_start();
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	clearstatcache();
	header("X-Robots-Tag: noindex");
	header("Referrer-Policy: no-referrer");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Expires: -1");
?>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Simple Pagination</title>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
</head>
<body>
	<header>
		<h1>Simple Pagination</h1>
	</header>
	<main>
		<section class="table-responsive" id="hitsTbl"></section>
	</main>
	<footer></footer>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			// debugger;
			load_data();
			$(document).on('click', '.page-link', function(){  
           		var page = $(this).data("page"); 
           		$('.page-prev').data('page', page - 1);
           		$('.page-next').data('page', page + 1); 
           		load_data(page);  
      		});  
		});
		function load_data(page){  
           $.ajax({  
                url:"APIPage.php",  
                method:"GET",  
                data:{
                	action:'getHits',
                	paging: 1,
                	pageSize: 5,
                	pageNumber: (typeof page != undefined && page != null && page != '') ? parseInt(page) : 1
                },  
                success:function(data){  
                	//console.log(data);
                	var returnStatus = (data['statusCode'] != null && data['statusCode'] != '') ? parseInt(data['statusCode']) : null;
                	if(returnStatus == 200){
                		if(data['hits']['data'] != null){
                			// var srNo = 1;
                			var table = '<table class="table table-sm table-striped table-bordered">';
                			var thead = table + '<thead class="table-dark"><tr><td align="center">#</td><td>Date</td><td>Source</td><td align="center">Hits</td></tr></thead>';
                			var tbody = thead+'<tbody>';
                			var tblRow = '';
                			data['hits']['data'].forEach((h)=>{
                				// console.log(h.id);
                				tblRow += '<tr>';
                				tblRow += '<td align="center" data-id="'+h.id+'" data-slug="'+h.slug+'">'+h.id+'</td>';
                				tblRow += '<td>'+h.date+'</td>';
                				tblRow += '<td>'+h.source+'</td>';
                				tblRow += '<td align="center">'+h.hits+'</td>';
                				tblRow += '</tr>';
                				// srNo++;
                			});
                			var endTBody = tbody+tblRow+'</tbody>';
                			var tfoot = endTBody+'<tfoot class="table-light"><tr><td colspan="4">'+data['hits']['pages']+'</td></tr></tfoot>';
							var endTable = tfoot+'</table>';
							$('section#hitsTbl').html(endTable);
                		}
                	}else{
                		alert(data['statusMessage']);
                	}
                },
                error:function (jqXHR, exception) {
			        var msg = '';
			        if (jqXHR.status === 0) {
			            msg = 'Not connect.\n Verify Network.';
			        } else if (jqXHR.status == 404) {
			            msg = 'Requested page not found. [404]';
			        } else if (jqXHR.status == 500) {
			            msg = 'Internal Server Error [500].';
			        } else if (exception === 'parsererror') {
			            msg = 'Requested JSON parse failed.';
			        } else if (exception === 'timeout') {
			            msg = 'Time out error.';
			        } else if (exception === 'abort') {
			            msg = 'Ajax request aborted.';
			        } else {
			            msg = 'Uncaught Error.\n' + jqXHR.responseText;
			        }
			        console.error(msg);
			        alert(msg);
			    }
           });
      	}	  
	</script>
</body>
</html>