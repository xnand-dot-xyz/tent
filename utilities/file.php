<?php
  function get_mime_type($name) {
    $extension = pathinfo($name, PATHINFO_EXTENSION);

    $type = match ($extension) {
      "css" => "text/css"
    };

    return $type;
  };

  function get_placeholder() {
    // Base64-encoded gray (50% opacity) PNG pixel
    return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNsqAcAAYUBAdpOiIkAAAAASUVORK5CYII=";
  }
?>
