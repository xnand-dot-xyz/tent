<?php require_once "../utilities/file.php" ?>

<?php
  $file = "../assets/" . basename($_GET["file"]);

  if (!file_exists($file))
    return http_response_code(404);

  $data = file_get_contents($file);
  $mime = get_mime_type($_GET["file"]);

  header("Content-Type: " . $mime);
  echo $data;
?>
