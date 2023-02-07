<?php
  $document = new DOMDocument();
  $document->loadHTML(file_get_contents($_GET["url"]));

  $title = htmlspecialchars($document->getElementsByTagName("meta")->item(4)->getAttribute("content"));
?>

<?php include "elements/header.php" ?>

<?php include "elements/footer.php" ?>
