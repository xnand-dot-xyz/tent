<?php
  $image = file_get_contents("https://f4.bcbits.com/img/" . urlencode($_GET["file"]));

  $mime = new finfo(FILEINFO_MIME_TYPE);
  $mime = $mime->buffer($image);

  header("Content-Type: " . $mime);
  echo $image;
?>
