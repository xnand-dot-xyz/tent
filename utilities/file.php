<?php
  function get_mime_type($name) {
    $extension = pathinfo($name, PATHINFO_EXTENSION);

    $type = [
      "css" => "text/css",
      "js" => "text/javascript",
      "svg" => "image/svg+xml"
    ][$extension];

    return $type;
  };

  function get_placeholder() {
    if (
      isset($_COOKIE["images"]) &&
      ($_COOKIE["images"] === "disabled" ||
      ($_COOKIE["images"] !== "enabled" && (isset($_SERVER["HTTP_SAVE_DATA"]) && $_SERVER["HTTP_SAVE_DATA"] === "on")))
    ) {
      // Base64-encoded gray (50% opacity) PNG pixel
      return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNsqAcAAYUBAdpOiIkAAAAASUVORK5CYII=";
    };
  };

  function proxy_file($ch) {
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) {
      echo $data;
      return strlen($data);
    });

    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    header("Content-Type: " . ($contentType ?: "application/octet-stream"));

    curl_exec($ch);
  };
?>
