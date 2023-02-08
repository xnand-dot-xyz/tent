<?php
  require "../modules/querypath/src/qp.php";

  $document = htmlqp(file_get_contents("https://" . urlencode($_GET["artist"]) . ".bandcamp.com/album/" . urlencode($_GET["name"])));

  $title = $document->find("h2")->text();
?>

<?php include "../elements/header.php" ?>
<?php include "../utilities/link.php" ?>

<?php
  // ...
?>

<?php include "../elements/footer.php" ?>
