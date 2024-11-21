<?php require_once "../utilities/index.php" ?>

<?php
  require_once "../config/config.php";

  if (isset($_GET["host"]) && $_GET["host"])
    $host = urlencode($_GET["artist"]);
  else
    $host = urlencode($_GET["artist"]) . ".bandcamp.com";

  $ch = curl_init("https://" . $host . "/" . urlencode($_GET["type"]) . "/" . urlencode($_GET["name"]));

  if (isset($config["identity"]))
    curl_setopt($ch, CURLOPT_COOKIE, "identity=" . $config["identity"]);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $document = new DOMXPath(encode_document(curl_exec($ch)));
  $json = json_decode($document->evaluate("//script[@data-tralbum]")->item(0)->getAttribute("data-tralbum"));
  $additional = json_decode($document->evaluate("//script[@type=\"application/ld+json\"]")->item(0)->textContent);

  if ($json)
    $title = $json->current->title;
?>

<?php require_once "../elements/header.php" ?>
<?php require_once "../elements/sidebar.php" ?>
<?php require_once "../elements/item.php" ?>

<?php
  echo_design_style($document);

  if ($json) {
    echo "<h1>";
    echo htmlspecialchars($json->current->title) . " ";

    $album = $document->evaluate("//span[@class=\"fromAlbum\"]")->item(0);
    if ($album) {
      echo "from <a href=\"" . convert_bandcamp_link(prefix_link("/" . $album->parentElement->getAttribute("href"), "artist")) . "\">";
      echo htmlspecialchars($album->textContent);
      echo "</a> ";
    };

    echo "by <a href=\"" . convert_bandcamp_link(prefix_link("/", "artist")) . "\">" . htmlspecialchars($json->artist) . "</a>";
    echo "</h1>";

    echo "<div class=\"subpage\">";

    if (isset($json->art_id))
      $image = "https://f4.bcbits.com/img/" . $json->art_id . "_4.jpg";
    else
      $image = null;

    $about = $json->current->about;

    if (property_exists($additional, "inAlbum")) {
      $description = $additional->inAlbum->albumRelease[0]->additionalProperty;
      if ($description) $description = current(array_filter($description, fn($property) => $property->name === 'digital_release_description'));
      if ($description) $description = $description->value;
    };

    $text = $about ?? $description ?? null;

    echo_sidebar($image, $text);

    echo "<div class=\"tracks\">";

    if (count($json->trackinfo)) {
      echo "<details" . (isset($_COOKIE["details"]) && !in_array("tracklist", json_decode($_COOKIE["details"])) ? "" : " open") . ">";
      echo "<summary>Tracklist</summary>";
      echo "<table>";

      foreach ($json->trackinfo as $track) {
        $link = $track->title_link;
        if ($link) {
          $link = prefix_link($link, "artist");
          $link = convert_bandcamp_link($link);
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
          echo "<audio src=\"" . convert_bandcamp_link($file) . "\" controls preload=\"none\"></audio>";
          echo "</td>";
          echo "</tr>";
        };
      };

      echo "</table>";
      echo "</details>";
    };

    $videos = array_filter($json->trackinfo, fn($track) => $track->video_mobile_url);

    if ($videos) {
      echo "<details" . (isset($_COOKIE["details"]) && in_array("videos", json_decode($_COOKIE["details"])) ? " open" : "") . ">";
      echo "<summary>Videos</summary>";

      foreach ($videos as $video) {
        $src = convert_bandcamp_link("https://bandcamp.23video.com" . $video->video_mobile_url);
        $poster = convert_bandcamp_link("https://bandcamp.23video.com/" . $video->video_poster_url);

        echo "<video src=\"" . $src . "\" poster=\"" . $poster . "\" controls preload=\"none\"></video>";
      };

      echo "</details>";
    };

    if (property_exists($json->current, "lyrics"))
      $lyrics = $json->current->lyrics;

    if (isset($lyrics)) {
      echo "<details" . (isset($_COOKIE["details"]) && in_array("lyrics", json_decode($_COOKIE["details"])) ? " open" : "") . ">";
      echo "<summary>Lyrics</summary>";
      echo "<p>" . nl2br(htmlspecialchars($lyrics)) . "</p>";
      echo "</details>";
    };

    echo "<details" . (isset($_COOKIE["details"]) && in_array("credits", json_decode($_COOKIE["details"])) ? " open" : "") . ">";
    echo "<summary>Credits</summary>";

    $credits = $document->evaluate("//div[contains(@class, \"tralbum-credits\")]")->item(0);
    $from = $document->evaluate("./a[@href]", $credits)->item(0);
    if ($from)
      $from->removeAttribute("href");
    echo $document->document->saveHTML($credits);

    echo "</details>";

    if (property_exists($additional, "copyrightNotice")) {
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
    };

    $tags = $additional->keywords;

    echo "<details" . (isset($_COOKIE["details"]) && in_array("tags", json_decode($_COOKIE["details"])) ? " open" : "") . ">";
    echo "<summary>Tags</summary>";
    echo "<ul>";

    foreach ($tags as $tag) {
      echo "<li>";
      echo "<a href=\"" . convert_bandcamp_link("https://bandcamp.com/discover/" . strtolower(htmlspecialchars(str_replace(" ", "-", $tag)))) . "\">";
      echo htmlspecialchars($tag);
      echo "</a>";
      echo "</li>";
    };

    echo "</ul>";
    echo "</details>";

    $recommendations = $document->evaluate("//li[contains(@class, \"recommended-album\")]");

    if ($recommendations->count()) {
      echo "<details" . (isset($_COOKIE["details"]) && in_array("recommendations", json_decode($_COOKIE["details"])) ? " open" : "") . ">";
      echo "<summary>Recommendations</summary>";
      echo "<div class=\"results\">";

      foreach ($recommendations as $recommendation) {
        $link = convert_bandcamp_link($document->evaluate(".//a[@class=\"album-link\"]", $recommendation)->item(0)->getAttribute("href"));
        $image = convert_bandcamp_link(resize_link($document->evaluate(".//img", $recommendation)->item(0)->getAttribute("src"), 3));
        $text = $recommendation->getAttribute("data-albumtitle");
        $description = "by " . $recommendation->getAttribute("data-artist");

        echo_item($link, $image, $text, $description);
      };

      echo "</div>";
      echo "</details>";
    };

    echo "</div>";

    if (isset($additional->publisher->image)) {
      $image = $additional->publisher->image;
      $image = resize_link($image, 4);
    } else
      $image = null;

    if (isset($additional->publisher->description))
      $text = $additional->publisher->description;
    else
      $text = null;

    $links = $document->evaluate("//ol[@id=\"band-links\"]//a");

    echo_sidebar($image, $text, $links);

    echo "</div>";
  } else {
    echo_error_message();
  };
?>

<?php require_once "../elements/footer.php" ?>
