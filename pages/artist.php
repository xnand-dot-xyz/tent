<?php
  include "../utilities/dom.php";
  require "../modules/querypath/src/qp.php";

  $document = htmlqp(encode_document(file_get_contents("https://" . urlencode($_GET["name"]) . ".bandcamp.com/music")));

  $title = $document->find("#band-name-location .title")->text();
?>

<?php include "../elements/header.php" ?>
<?php include "../elements/item.php" ?>
<?php include "../elements/sidebar.php" ?>
<?php include "../utilities/link.php" ?>

<?php
  if ($document->find("#band-name-location .title")->length)
    echo "<h1>" . htmlspecialchars($document->find("#band-name-location .title")->text()) . "</h1>";

  echo "<div class=\"page\">";

  echo "<div class=\"results\">";

  $releases = $document->find("#music-grid li");

  foreach ($releases as $release) {
    $title = preg_split("/\n[\n\s]+/", trim($release->find(".title")->text()));

    unset($text);
    if ($title[1]) $text = "by " . htmlspecialchars($title[1]);

    $image = $release->find("img");
    $image = $image->hasAttr("data-original") ? $image->attr("data-original") : $image->attr("src");
    $image = convert_link($image);

    $link = $release->find("a")->attr("href");
    $link = prefix_link($link, "name");
    $link = convert_link($link);

    echo_item($link, $image, htmlspecialchars($title[0]), $text);
  };

  if (!$releases->length)
    echo "<span>No results.</span>";

  echo "</div>";

  $image = $document->find(".bio-pic a")->attr("href");
  $description = $document->find("meta[property=\"og:description\"]")->attr("content");
  $links = $document->find("#band-links li a");

  echo_sidebar($image, $description, $links);

  echo "</div>";
?>

<?php include "../elements/footer.php" ?>
