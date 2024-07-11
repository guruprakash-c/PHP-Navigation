<?php
	use PageViews as PV;

	require_once __DIR__."/views/Pages.php";

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
	$getResponse = array(
		'hits' => array(
					'data' => array(),
					'pages' => ''
				),
		'statusCode' => 0,
		'statusMessage' => ''
	);
	header('Content-Type: application/json; charset=utf-8');
	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		if(!empty($_GET['action'])){
			switch (strtolower($_GET['action'])) {
				case 'gethits':
					$paging = FALSE;
					$pageSize = $pageNumber = NULL;
					if(!empty($_GET['paging'])){
						if(intval($_GET['paging']) == 1){
							$paging = TRUE;
							$pageSize = !empty($_GET['pageSize']) ? $_GET['pageSize'] : 5;
							$pageNumber = !empty($_GET['pageNumber']) ? $_GET['pageNumber'] : 1;
						}
					}
					$result = PV\Pages::GetHits($paging,$pageSize,$pageNumber);
					$getResponse['hits']['data'] = $result['hits']['data'];
					$getResponse['hits']['pages'] = $result['hits']['pages'];
					$getResponse['statusCode'] = $result['statusCode'];
					$getResponse['statusMessage'] = $result['statusMessage'];
					echo json_encode($getResponse);
					break;
				default:
					break;
			}
		}
	}
?>