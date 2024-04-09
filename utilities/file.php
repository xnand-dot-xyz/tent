<?php
  function get_mime_type($name) {
    $extension = pathinfo($name, PATHINFO_EXTENSION);

    $type = match ($extension) {
      "css" => "text/css",
      "svg" => "image/svg+xml"
    };

    return $type;
  };
?>
