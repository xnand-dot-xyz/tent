<?php
  require "../modules/querypath/src/qp.php";

  $document = htmlqp(file_get_contents("https://" . urlencode($_GET["artist"]) . ".bandcamp.com/album/" . urlencode($_GET["name"])));

  $title = $document->find("h2")->text();
?>

<?php include "../elements/header.php" ?>
<?php include "../utilities/link.php" ?>

<?php
  echo "<h1>";
  echo htmlspecialchars($document->find("h2")->next()->find("a")->text()) . ": ";
  echo htmlspecialchars($document->find("h2")->text());
  echo "</h1>";

  echo "<div class=\"subpage\">";

  echo "<div class=\"sidebar\">";

  $image = $document->find("#tralbumArt a")->attr("href");
  $description = $document->find(".tralbum-about")->text();

  if (isset($image)) echo "<img src=\"" . convert_link($image) . "\">";
  if (!empty($description)) echo "<p>" . nl2br($description) . "</p>";

  echo "</div>";

  echo "<div class=\"tracks\">";

  $tracks = $document->find(".track_list .track_row_view");

  echo "<ol>";
  foreach ($tracks as $track) {
    $link = $track->find(".title a")->attr("href");
    $link = prefix_link($link, "artist");
    $link = convert_link($link);

    echo "<li>";
    echo "<a href=\"" . $link . "\">";
    echo $track->find(".track-title")->text();
    echo "</a>";
    echo "</li>";
  };
  echo "</ol>";

  echo "</div>";

  include "../elements/sidebar.php";

  echo "</div>";
?>

<?php include "../elements/footer.php" ?>
