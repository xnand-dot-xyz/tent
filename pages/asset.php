<?php
  $file = "../assets/" . basename($_GET["file"]);

  if (!file_exists($file))
    return http_response_code(404);

  $data = file_get_contents($file);

  $mime = new finfo(FILEINFO_MIME_TYPE);
  $mime = $mime->buffer($data);

  switch (pathinfo($file, PATHINFO_EXTENSION)) {
    case "css":
      $mime = "text/css";
      break;
  };

  header("Content-Type: " . $mime);
  echo $data;
?>
