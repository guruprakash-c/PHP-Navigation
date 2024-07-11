<?php 
namespace PageEntities{
	use PageProps as PP;
	
	require_once 'models/HitsDO.php';

	final class PageEntity{
		private $dbCon = null;
		function __construct(){
			$this->dbCon = mysqli_connect('localhost', 'root', '','blogdbn');
		}
		public function GetHits($paging=FALSE, $pageSize=NULL, $pageNumber=NULL){
			$hitResponse = array(
				'hits' => array(
					'data' => array(),
					'pages' => ''
				),
				'statusCode' => 0,
				'statusMessage' => ''
			);
			try{
				if($this->dbCon){
					$sql = "SELECT DISTINCT `hitId` AS ID, `hitSlug` AS SID, `hitSrc` AS Sourc, `hitCount` AS Hits, `hitOn` AS Dated FROM `tbl_hits` ORDER BY `hitId` ";
					$record_per_page = $page = NULL;
					$pagination = '<nav aria-label="Hits Navigation"><ul class="pagination justify-content-center">';
					if(boolval($paging)){
						$record_per_page = !empty($pageSize) ? intval($pageSize) : 5;
						$page = !empty($pageNumber) ? intval($pageNumber) : 1;
						$start_from = ($page - 1)*$record_per_page;
						$sql .= " LIMIT $start_from, $record_per_page";
						$page_query = "SELECT * FROM `tbl_hits` ORDER BY `hitOn` DESC ";  
						
						$page_result = mysqli_query($this->dbCon, $page_query);  
						$total_records = mysqli_num_rows($page_result);  
						$total_pages = ceil($total_records/$record_per_page);  
						$pagination .= '<li class="page-item'.((($page - 1) == 0) ? ' disabled' : ($page - 1)).'"><a class="page-link page-prev" href="javascript:void(0);" data-page="'.((($page - 1) == 0) ? 1 : ($page - 1)).'">Previous</a></li>';
						 for($i=1; $i<=$total_pages; $i++)  
						 {   
						      $pagination .= '<li class="page-item'.(($i == $page) ? ' active" aria-current="page"' : '"').'><a class="page-link" href="javascript:void(0);" data-page="'.$i.'">'.$i.'</a></li>';
						 }  
						$pagination .= '<li class="page-item'.((($page + 1) < ($total_pages+1)) ? ($page + 1) : ' disabled').'"><a class="page-link page-next" href="javascript:void(0);" data-page="'.((($page + 1) < $total_pages) ? ($page + 1) : $total_pages).'">Next</a></li>';
						$pagination .= ($page != $total_pages) ? '<li class="page-item"><span class="page-link text-dark">'.('page <strong>'.$page.'</strong> of '.$total_pages).'</span></li>' : '';
					}
					$pagination .= '</ul></nav>';
					$result = mysqli_query($this->dbCon, $sql);
					$resultCount = intval(mysqli_num_rows($result));
					if ($resultCount > 0) {
					  while($row = mysqli_fetch_assoc($result)) {
					  	$hits = new PP\HitsDO();
					  	$hits->id = intval($row["ID"]);
					  	$hits->slug = trim($row["SID"]);
					  	$hits->date = date('F d, Y', strtotime($row["Dated"]));
					  	$hits->source = trim(ucwords($row["Sourc"]));
					  	$hits->hits = intval($row["Hits"]);
					  	array_push($hitResponse['hits']['data'], $hits);
					  }
					  if(boolval($paging)) $hitResponse['hits']['pages'] = $pagination;
					  $hitResponse['statusCode'] = 200;
					  if($resultCount > 1) 
					  	$hitResponse['statusMessage'] = intval($resultCount).' Hits found!';
					  else 
					  	$hitResponse['statusMessage'] = '1 Hit found!';
					}else{
						$hitResponse['statusCode'] = 200;
						$hitResponse['statusMessage'] = 'No Hits found!';
					}
				}elseif(!$this->dbCon){
					$hitResponse['statusCode'] = 500;
					$hitResponse['statusMessage'] = mysqli_connect_error();
				}
			}catch(\Exception $err){
				$hitResponse['statusCode'] = 500;
				$hitResponse['statusMessage'] = $err->getMessage();
			}
			finally{
				mysqli_close($this->dbCon);
			}
			return $hitResponse;
		}
		function __destruct(){
            $this->dbCon = NULL;
        }
	}
}