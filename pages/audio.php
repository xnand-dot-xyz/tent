<?php
  $ch = curl_init("https://t4.bcbits.com/stream/" . urlencode($_GET["directory"]) . "/" . urlencode($_GET["format"]) . "/" . urlencode($_GET["file"]) . "?token=" . urlencode($_GET["token"]));

  curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) {
    echo $data;
    return strlen($data);
  });

  $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
  header("Content-Type: $contentType");
  curl_exec($ch);
?>
