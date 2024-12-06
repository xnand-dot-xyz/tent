<?php
  require_once "../utilities/index.php";

  $ch = curl_init("https://bandcamp.23video.com/" . urlencode($_GET["parent"]) . "/" . urlencode($_GET["child"]) . "/" . urlencode($_GET["hash"]) . "/video_4k/" . urlencode($_GET["file"]));

  proxy_file($ch);
?>
