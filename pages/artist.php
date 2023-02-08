<?php
  require "../modules/querypath/src/qp.php";

  $document = htmlqp(file_get_contents("https://" . urlencode($_GET["name"]) . ".bandcamp.com"));

  $title = $document->find("#band-name-location .title")->text();
?>

<?php include "../elements/header.php" ?>

<?php
  echo "<div class=\"results\">";

  foreach ($document->find(".artists-grid li, #music-grid li, #discography li") as $release) {
    $title = preg_split("/\n[\n\s]+/", trim($release->find(".artists-grid-name, .title, .trackTitle")->text()));

    $image = $release->find("img");
    if ($image->hasAttr("data-original"))
      $image = $image->attr("data-original");
    else
      $image = $image->attr("src");
    $image = "image.php?file=" . basename($image);

    $link = $release->find("a")->attr("href");
    if (!filter_var($link, FILTER_VALIDATE_URL)) {
      $link = "https://" . urlencode($_GET["name"]) . ".bandcamp.com" . $link;
    };
    if (!parse_url($link, PHP_URL_PATH)) {
      $domain = explode(".", parse_url($link, PHP_URL_HOST));
      if (end($domain) === "com" && prev($domain) === "bandcamp")
        $link = "artist.php?name=" . prev($domain);
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

  echo "</div>";
?>

<?php include "../elements/footer.php" ?>
