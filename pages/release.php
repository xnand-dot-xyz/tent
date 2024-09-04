<?php require_once "../utilities/index.php" ?>

<?php
  require_once "../config/config.php";
  require_once "../modules/querypath/src/qp.php";

  $ch = curl_init("https://" . urlencode($_GET["artist"]) . ".bandcamp.com/" . urlencode($_GET["type"]) . "/" . urlencode($_GET["name"]));

  if (isset($config["identity"]))
    curl_setopt($ch, CURLOPT_COOKIE, "identity=" . $config["identity"]);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $document = htmlqp(encode_document(curl_exec($ch)));
  $json = json_decode($document->find("script[data-tralbum]")->attr("data-tralbum"));
  $additional = json_decode($document->find("script[type=\"application/ld+json\"]")->text());

  $title = $json->current->title;
?>

<?php require_once "../elements/header.php" ?>
<?php require_once "../elements/sidebar.php" ?>
<?php require_once "../elements/item.php" ?>

<?php
  if ($json) {
    echo "<h1>";
    echo "<a href=\"" . convert_link("https://" . urlencode($_GET["artist"]) . ".bandcamp.com") . "\">" . htmlspecialchars($json->artist) . "</a>: ";
    echo htmlspecialchars($json->current->title);
    echo "</h1>";

    echo "<div class=\"subpage\">";

    $image = "https://f4.bcbits.com/img/" . $json->art_id . "_4.jpg";

    $about = $json->current->about;

    if (property_exists($additional, "inAlbum")) {
      $description = $additional->inAlbum->albumRelease[0]->additionalProperty;
      if ($description) $description = current(array_filter($description, fn($property) => $property->name === 'digital_release_description'));
      if ($description) $description = $description->value;
    };

    $text = $about ?? $description ?? null;

    echo_sidebar($image, $text);

    echo "<div class=\"tracks\">";

    echo "<details" . (isset($_COOKIE["details"]) && !in_array("tracklist", json_decode($_COOKIE["details"])) ? "" : " open") . ">";
    echo "<summary>Tracklist</summary>";
    echo "<table>";

    foreach ($json->trackinfo as $track) {
      $link = $track->title_link;
      if ($link) {
        $link = prefix_link($link, "artist");
        $link = convert_link($link);
      };

      $duration = round($track->duration);
      if ($duration)
        $duration = floor($duration / 60) . ":" . sprintf("%02d", $duration % 60);
      else
        $duration = null;

      echo "<tr>";
      echo "<td>" . ($track->track_num ?? 1) . ".</td>";
      echo "<td>";
      if ($link) echo "<a href=\"" . $link . "\">";
      echo htmlspecialchars($track->title);
      if ($link) echo "</a>";
      echo "</td>";
      echo "<td>" . $duration . "</td>";
      echo "</tr>";

      if ($track->file) {
        $file = $track->file;
        $file = get_mangled_object_vars($file);
        $file = end($file);

        echo "<tr>";
        echo "<td></td>";
        echo "<td colspan=\"2\">";
        echo "<audio src=\"" . convert_link($file) . "\" controls preload=\"none\"></audio>";
        echo "</td>";
        echo "</tr>";
      };
    };

    echo "</table>";
    echo "</details>";

    if (property_exists($json->current, "lyrics"))
      $lyrics = $json->current->lyrics;

    if (isset($lyrics)) {
      echo "<details" . (isset($_COOKIE["details"]) && in_array("lyrics", json_decode($_COOKIE["details"])) ? " open" : "") . ">";
      echo "<summary>Lyrics</summary>";
      echo "<p>" . nl2br(htmlspecialchars($lyrics)) . "</p>";
      echo "</details>";
    };

    echo "<details" . (isset($_COOKIE["details"]) && in_array("license", json_decode($_COOKIE["details"])) ? " open" : "") . ">";
    echo "<summary>License</summary>";

    if ($additional->copyrightNotice === "All Rights Reserved") {
      echo "All rights reserved.";
    } elseif ($additional->copyrightNotice === "Various") {
      echo "License varies by track. See the invidual track pages for details.";
    } else {
      $license = str_replace(
        ["Attribution", "No-Derivatives", "Non-Commercial", "Share-Alike"],
        ["BY", "ND", "NC", "SA"],
        str_replace(" ", "-", $additional->copyrightNotice)
      );

      echo "CC " . $license . " 3.0. ";
      echo "<a href=\"https://creativecommons.org/licenses/" . strtolower($license) . "/3.0/\">";
      echo "See the Creative Commons website for details.";
      echo "</a>";
    };

    echo "</details>";

    $tags = $additional->keywords;

    echo "<details" . (isset($_COOKIE["details"]) && in_array("tags", json_decode($_COOKIE["details"])) ? " open" : "") . ">";
    echo "<summary>Tags</summary>";
    echo "<ul>";

    foreach ($tags as $tag) {
      echo "<li>";
      echo "<a href=\"" . convert_link("https://bandcamp.com/discover/" . strtolower(htmlspecialchars(str_replace(" ", "-", $tag)))) . "\">";
      echo htmlspecialchars($tag);
      echo "</a>";
      echo "</li>";
    };

    echo "</ul>";
    echo "</details>";

    $recommendations = $document->find(".recommended-album");

    echo "<details" . (isset($_COOKIE["details"]) && in_array("recommendations", json_decode($_COOKIE["details"])) ? " open" : "") . ">";
    echo "<summary>Recommendations</summary>";
    echo "<div class=\"results\">";

    foreach ($recommendations as $recommendation) {
      $link = convert_link($recommendation->find(".album-link")->attr("href"));
      $image = convert_link(resize_link($recommendation->find("img")->attr("src"), 3));
      $text = $recommendation->attr("data-albumtitle");
      $description = "by " . $recommendation->attr("data-artist");

      echo_item($link, $image, $text, $description);
    };

    echo "</div>";
    echo "</details>";

    echo "</div>";

    $image = $document->find(".bio-pic a")->attr("href");
    $image = resize_link($image, 4);

    if (isset($additional->publisher->description))
      $text = $additional->publisher->description;
    else
      $text = null;
    $links = $document->find("#band-links li a");

    echo_sidebar($image, $text, $links);

    echo "</div>";
  } else {
    echo_error_message();
  };
?>

<?php require_once "../elements/footer.php" ?>
