<?php
  $file = "../assets/" . basename($_GET["file"]);

  if (!file_exists($file))
    return http_response_code(404);

  $data = file_get_contents($file);

  $mime = json_decode(file_get_contents("../modules/mime-db/db.json"));
  foreach ($mime as $key => $value) {
    if (isset($value->extensions) && in_array(pathinfo($_GET["file"], PATHINFO_EXTENSION), $value->extensions)) {
      $mime = $key;
      break;
    };
  };

  header("Content-Type: " . $mime);
  echo $data;
?>
