<?php
  require "modules/querypath/src/qp.php";

  /* TODO: UTF-8 titles don't seem to be decoded correctly.
           An example can be seen with "Die Tür Ist Zu" on the SWANS page. */
  $document = htmlqp(file_get_contents("https://" . urlencode($_GET["name"]) . ".bandcamp.com"));

  $title = $document->find("#band-name-location .title")->text();
?>

<?php include "elements/header.php" ?>

<?php
  echo "<div class=\"results\">";

  foreach ($document->find("#music-grid li") as $release) {
    $title = preg_split("/\n[\n\s]+/", trim($release->find(".title")->text()));

    $image = $release->find("img");
    if ($image->hasAttr("data-original"))
      $image = $image->attr("data-original");
    else
      $image = $image->attr("src");
    $image = "image.php?file=" . basename($image);

    echo "<a href=\"https://" . urlencode($_GET["name"]) . ".bandcamp.com" . $release->find("a")->attr("href") . "\">";
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

  echo "</div>";
?>

<?php include "elements/footer.php" ?>
