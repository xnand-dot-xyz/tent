<?php require_once "../utilities/index.php" ?>

<?php
  if (isset($_GET["url"]))
    header("Location: " . convert_bandcamp_link($_GET["url"]));
  else
    http_response_code(400);
?>
