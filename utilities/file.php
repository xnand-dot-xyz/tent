<?php
  function get_mime_type($name) {
    $extension = pathinfo($name, PATHINFO_EXTENSION);

    $type = match ($extension) {
      "css" => "text/css"
    };

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
?>
