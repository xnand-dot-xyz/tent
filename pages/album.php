<?php
  require "../modules/querypath/src/qp.php";

  $document = htmlqp(file_get_contents("https://" . urlencode($_GET["artist"]) . ".bandcamp.com/album/" . urlencode($_GET["name"])));

  $title = $document->find("h2")->text();
?>

<?php include "../elements/header.php" ?>
<?php include "../elements/sidebar.php" ?>
<?php include "../utilities/link.php" ?>

<?php
  echo "<h1>";
  echo htmlspecialchars($document->find("h2")->next()->find("a")->text()) . ": ";
  echo htmlspecialchars($document->find("h2")->text());
  echo "</h1>";

  echo "<div class=\"subpage\">";

  $image = $document->find("#tralbumArt a")->attr("href");
  $text = $document->find(".tralbum-about")->text();

  echo_sidebar($image, $text);

  echo "<div class=\"tracks\">";

  echo "<table>";

  $tracks = $document->find(".track_list .track_row_view");

  foreach ($tracks as $track) {
    $link = $track->find(".title a")->attr("href");
    $link = prefix_link($link, "artist");
    $link = convert_link($link);

    echo "<tr>";
    echo "<td>" . $track->find(".track_number")->text() . "</td>";
    echo "<td><a href=\"" . $link . "\">" . $track->find(".track-title")->text() . "</a></td>";
    echo "<td>" . $track->find(".time")->text() . "</td>";
    echo "</tr>";
  };

  echo "</table>";

  echo "</div>";

  $image = $document->find(".bio-pic a")->attr("href");
  $text = $document->find("#bio-text")->text();
  $links = $document->find("#band-links li a");

  echo_sidebar($image, $text, $links);

  echo "</div>";
?>

<?php include "../elements/footer.php" ?>
