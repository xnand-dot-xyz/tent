<?php
  $ch = curl_init("https://t4.bcbits.com/stream/" . urlencode($_GET["directory"]) . "/" . urlencode($_GET["format"]) . "/" . urlencode($_GET["file"]) . "?token=" . urlencode($_GET["token"]));

  curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) {
    echo $data;
    return strlen($data);
  });

  header("Content-Type: application/octet-stream");
  curl_exec($ch);
?>
