<?php
  function get_mime_type($name) {
    $type = json_decode(file_get_contents("../modules/mime-db/db.json"));

    foreach ($type as $key => $value) {
      if (isset($value->extensions) && in_array(pathinfo($name, PATHINFO_EXTENSION), $value->extensions)) {
        $type = $key;
        break;
      };
    };

    return $type;
  };
?>
