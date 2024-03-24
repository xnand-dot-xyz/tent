<?php require_once "../utilities/link.php" ?>

<?php
  require_once "../utilities/dom.php";
  require_once "../modules/querypath/src/qp.php";

  $ch = curl_init("https://" . urlencode($_GET["name"]) . ".bandcamp.com/music");

  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $document = htmlqp(encode_document(curl_exec($ch)));
  $redirect = curl_getinfo($ch, CURLINFO_REDIRECT_URL);

  if ($redirect) {
    header("Location: " . convert_link($redirect));
    exit();
  };

  $title = $document->find("#band-name-location .title")->text();
?>

<?php require_once "../elements/header.php" ?>
<?php require_once "../elements/item.php" ?>
<?php require_once "../elements/sidebar.php" ?>

<?php
  if ($document->find("#band-name-location .title")->length)
    echo "<h1>" . htmlspecialchars($document->find("#band-name-location .title")->text()) . "</h1>";

  echo "<div class=\"page\">";

  echo "<div class=\"results\">";

  $releases = $document->find("#music-grid li");

  foreach ($releases as $release) {
    $title = preg_split("/\n[\n\s]+/", trim($release->find(".title")->text()));

    unset($text);
    if (array_key_exists(1, $title)) $text = "by " . htmlspecialchars($title[1]);

    $image = $release->find("img");
    $image = $image->hasAttr("data-original") ? $image->attr("data-original") : $image->attr("src");
    $image = resize_link($image, 3);
    $image = convert_link($image);

    $link = $release->find("a")->attr("href");
    $link = prefix_link($link, "name");
    $link = convert_link($link);

    echo_item($link, $image, htmlspecialchars($title[0]), $text ?? null);
  };

  if (!$releases->length)
    echo "<span>No results.</span>";

  echo "</div>";

  $image = $document->find(".bio-pic a")->attr("href");
  $image = resize_link($image, 4);

  $description = $document->find("meta[property=\"og:description\"]")->attr("content");
  $links = $document->find("#band-links li a");

  echo_sidebar($image, $description, $links);

  echo "</div>";
?>

<?php require_once "../elements/footer.php" ?>
