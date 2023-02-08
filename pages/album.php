<?php
  require "../modules/querypath/src/qp.php";

  $document = htmlqp(file_get_contents("https://" . urlencode($_GET["artist"]) . ".bandcamp.com/album/" . urlencode($_GET["name"])));

  $title = $document->find("h2")->text();
?>

<?php include "../elements/header.php" ?>
<?php include "../utilities/link.php" ?>

<?php
  echo "<h1>";
  echo htmlspecialchars($document->find("h2")->text());
  echo "<br>";
  echo "<small>" . htmlspecialchars($document->find("h2")->next()->text()) . "</small>";
  echo "</h1>";

  // ...
?>

<?php include "../elements/footer.php" ?>
