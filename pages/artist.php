<?php
  require "../modules/querypath/src/qp.php";

  $document = htmlqp(file_get_contents("https://" . urlencode($_GET["name"]) . ".bandcamp.com/music"));

  $title = $document->find("#band-name-location .title")->text();
?>

<?php include "../elements/header.php" ?>

<?php
  echo "<div class=\"results\">";

  $releases = $document->find("#music-grid li");

  foreach ($releases as $release) {
    $title = preg_split("/\n[\n\s]+/", trim($release->find(".title")->text()));

    $image = $release->find("img");
    $image = $image->hasAttr("data-original") ? $image->attr("data-original") : $image->attr("src");
    $image = "image.php?file=" . basename($image);

    $link = $release->find("a")->attr("href");
    if (!filter_var($link, FILTER_VALIDATE_URL)) {
      $link = "https://" . urlencode($_GET["name"]) . ".bandcamp.com" . $link;
    };

    echo "<a href=\"" . $link . "\">";
    echo "<div>";
    echo "<img src=\"" . $image . "\">";
    echo "<p>";
    echo htmlspecialchars($title[0]);

    if (isset($title[1])) {
      echo "<br>";
      echo "<small>";
      echo "by " . htmlspecialchars($title[1]);
      echo "</small>";
    };

    echo "</p>";
    echo "</div>";
    echo "</a>";
  };

  if (!$releases->length)
    echo "<div>No results.</div>";

  echo "</div>";
?>

<?php include "../elements/footer.php" ?>
