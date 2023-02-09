<?php
  $images = [
    "https://f4.bcbits.com/img/" . urlencode($_GET["file"]),
    "https://f4.bcbits.com/img/a" . urlencode($_GET["file"])
  ];

  foreach ($images as $image) {
    $context = stream_context_create([
      "http" => [
        "ignore_errors" => true
      ]
    ]);
    $image = file_get_contents($image, false, $context);

    if (explode(" ", $http_response_header[0])[1] === "200") {
      $mime = new finfo(FILEINFO_MIME_TYPE);
      $mime = $mime->buffer($image);

      header("Content-Type: " . $mime);
      echo $image;

      break;
    };
  };
?>
