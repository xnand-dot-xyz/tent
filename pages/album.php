<?php
  require "../modules/querypath/src/qp.php";

  $document = htmlqp(file_get_contents("https://" . urlencode($_GET["artist"]) . ".bandcamp.com/album/" . urlencode($_GET["name"])));
  $json = json_decode($document->find("script[data-tralbum]")->attr("data-tralbum"));

  $title = $json->current->title;
?>

<?php include "../elements/header.php" ?>
<?php include "../elements/sidebar.php" ?>
<?php include "../utilities/link.php" ?>

<?php
  echo "<h1>";
  echo htmlspecialchars($json->artist) . ": ";
  echo htmlspecialchars($json->current->title);
  echo "</h1>";

  echo "<div class=\"subpage\">";

  $image = "https://f4.bcbits.com/img/" . $json->art_id . "_10.jpg";
  $text = $json->current->about;

  echo_sidebar($image, $text);

  echo "<div class=\"tracks\">";

  echo "<table>";

  foreach ($json->trackinfo as $track) {
    $link = $track->title_link;
    $link = prefix_link($link, "artist");
    $link = convert_link($link);

    $duration = round($track->duration);
    $duration = floor($duration / 60) . ":" . sprintf("%02d", $duration % 60);

    echo "<tr>";
    echo "<td>" . $track->track_num . ".</td>";
    echo "<td><a href=\"" . $link . "\">" . $track->title . "</a></td>";
    echo "<td>" . $duration . "</td>";
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
