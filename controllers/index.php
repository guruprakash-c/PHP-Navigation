<?php 
ob_start();
require_once '../_layouts/_headers.php';
http_response_code(403);
header('HTTP/1.0 403 Forbidden');
header('Location: ../error.php',TRUE,403);
exit;
