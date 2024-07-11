<?php 
namespace PageViews{
    use PageCtrl as PC;

    require_once "controllers/PageController.php";

    final class Pages{
        public static function GetHits($paging=FALSE, $pageSize=NULL, $pageNumber=NULL):mixed{
            $page_obj = new PC\PageController();
            return $page_obj->GetHits($paging, $pageSize, $pageNumber);
        }
    }
}