<?php
  $ch = curl_init("https://bandcamp.23video.com/" . urlencode($_GET["parent"]) . "/" . urlencode($_GET["child"]) . "/" . urlencode($_GET["hash"]) . "/original/thumbnail.png");

  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) {
    echo $data;
    return strlen($data);
  });

  $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
  header("Content-Type: " . ($contentType ?: "application/octet-stream"));

  curl_exec($ch);
?>
