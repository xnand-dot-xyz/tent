<?php require_once "../utilities/index.php" ?>

<?php
  if (isset($_GET["host"]) && $_GET["host"])
    $host = urlencode($_GET["name"]);
  else
    $host = urlencode($_GET["name"]) . ".bandcamp.com";

  $ch = curl_init("https://" . $host . "/music");

  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $document = new DOMXPath(encode_document(curl_exec($ch)));
  $redirect = curl_getinfo($ch, CURLINFO_REDIRECT_URL);

  if ($redirect) {
    header("Location: " . convert_bandcamp_link($redirect));
    exit();
  };

  $title = $document->evaluate("//p[@id=\"band-name-location\"]//span[@class=\"title\"]")->item(0);
  if ($title)
    $title = $title->textContent;
  $items = $document->evaluate("//ol[@id=\"music-grid\"]")->item(0);
  if ($items)
    $items = json_decode($items->getAttribute("data-client-items"), true);
?>

<?php require_once "../elements/header.php" ?>
<?php require_once "../elements/item.php" ?>
<?php require_once "../elements/sidebar.php" ?>

<?php
  echo_design_style($document);

  if (echo_blacklist_message($_GET["name"]))
    return require_once "../elements/footer.php";

  if ($document->evaluate("//p[@id=\"band-name-location\"]//span[@class=\"title\"]")->count())
    echo "<h1>" . htmlspecialchars($document->evaluate("//p[@id=\"band-name-location\"]//span[@class=\"title\"]")->item(0)->textContent) . "</h1>";

  $releases = $document->evaluate("//ol[@id=\"music-grid\"]//li");

  if (!$releases->count() && !isset($items))
    echo_error_message();

  echo "<div class=\"page\">";

  echo "<div class=\"results\">";

  foreach ($releases as $release) {
    $title = preg_split("/\n[\n\s]+/", trim($document->evaluate(".//p[@class=\"title\"]", $release)->item(0)->textContent));

    unset($text);
    if (array_key_exists(1, $title)) $text = "by " . htmlspecialchars($title[1]);

    $image = $document->evaluate(".//img", $release)->item(0);
    $image = $image->hasAttribute("data-original") ? $image->getAttribute("data-original") : $image->getAttribute("src");
    $image = resize_link($image, 3);
    $image = convert_bandcamp_link($image);

    $link = $document->evaluate(".//a", $release)->item(0)->getAttribute("href");
    $link = prefix_link($link, "name");
    $link = convert_bandcamp_link($link);

    echo_item($link, $image, htmlspecialchars($title[0]), $text ?? null);
  };

  if (isset($items)) {
    foreach ($items as $item) {
      $title = $item["title"];

      unset($text);
      if (array_key_exists("artist", $item)) $text = "by " . $item["artist"];

      $image = $item["art_id"];
      $image = "https://f4.bcbits.com/img/a" . $image . "_0.jpg";
      $image = resize_link($image, 3);
      $image = convert_bandcamp_link($image);

      $link = prefix_link($item["page_url"], "name");
      $link = convert_bandcamp_link($link);

      echo_item($link, $image, htmlspecialchars($title), $text ?? null);
    };
  };

  echo "</div>";

  $image = $document->evaluate("//div[contains(@class, \"bio-pic\")]//a")->item(0);
  if ($image) {
    $image = $image->getAttribute("href");
    $image = resize_link($image, 4);
  };

  $description = $document->evaluate("//meta[@property=\"og:description\"]")->item(0);
  if ($description)
    $description = $description->getAttribute("content");
  $links = $document->evaluate("//ol[@id=\"band-links\"]//a");

  echo_sidebar($image, $description, $links);

  echo "</div>";
?>

<?php require_once "../elements/footer.php" ?>
