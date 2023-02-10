<?php
  $audio = "https://t4.bcbits.com/stream/" . urlencode($_GET["directory"]) . "/" . urlencode($_GET["format"]) . "/" . urlencode($_GET["file"]) . "?token=" . urlencode($_GET["token"]);
  $audio = file_get_contents($audio);

  $mime = new finfo(FILEINFO_MIME_TYPE);
  $mime = $mime->buffer($audio);

  header("Content-Type: " . $mime);
  echo $audio;
?>
