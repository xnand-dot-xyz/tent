<?php
  require_once "../utilities/index.php";

  $ch = curl_init("https://bandcamp.23video.com/" . urlencode($_GET["parent"]) . "/" . urlencode($_GET["child"]) . "/" . urlencode($_GET["hash"]) . "/original/thumbnail.png");

  proxy_file($ch);
?>
