<?php
  $images = [
    "https://f4.bcbits.com/img/" . urlencode($_GET["file"]),
    "https://f4.bcbits.com/img/a" . urlencode($_GET["file"])
  ];

  foreach ($images as $image) {
    $ch = curl_init($image);

    curl_setopt($ch, CURLOPT_NOBODY, true);

    curl_exec($ch);

    if (curl_getinfo($ch, CURLINFO_RESPONSE_CODE) === 200) {
      curl_setopt($ch, CURLOPT_NOBODY, false);
      curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) {
        echo $data;
        return strlen($data);
      });

      $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
      header("Content-Type: " . ($contentType ?: "application/octet-stream"));

      curl_exec($ch);

      break;
    };
  };
?>
