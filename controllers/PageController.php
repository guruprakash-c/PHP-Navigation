<?php 
namespace PageCtrl{
    use PageEntities as PE;

    require_once "models/PageEntity.php";

    final class PageController{
        private $pg_obj;
        function __construct(){
            $this->pg_obj = new PE\PageEntity();
        }
        public function GetHits($paging=FALSE, $pageSize=NULL, $pageNumber=NULL):mixed{
            return $this->pg_obj->GetHits($paging, $pageSize, $pageNumber);
        }
        function __destruct(){
            $this->pg_obj = NULL;
        }
    }
}