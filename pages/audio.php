<?php
  require_once "../utilities/index.php";

  $ch = curl_init("https://t4.bcbits.com/stream/" . urlencode($_GET["directory"]) . "/" . urlencode($_GET["format"]) . "/" . urlencode($_GET["file"]) . "?token=" . urlencode($_GET["token"]));

  proxy_file($ch);
?>
