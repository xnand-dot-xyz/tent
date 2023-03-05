<?php
  require_once "../config/config.php";
  require_once "../utilities/dom.php";
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
<?php require_once "../utilities/link.php" ?>

<?php
  if ($json) {
    echo "<h1>";
    echo "<a href=\"" . convert_link("https://" . urlencode($_GET["artist"]) . ".bandcamp.com") . "\">" . htmlspecialchars($json->artist) . "</a>: ";
    echo htmlspecialchars($json->current->title);
    echo "</h1>";

    echo "<div class=\"subpage\">";

    $image = "https://f4.bcbits.com/img/" . $json->art_id . "_10.jpg";

    $about = $json->current->about;
    $description = $additional->inAlbum->albumRelease[0]->additionalProperty;
    if ($description) $description = current(array_filter($description, fn($property) => $property->name === 'digital_release_description'));
    if ($description) $description = $description->value;
    $text = $about ?? $description;

    echo_sidebar($image, $text);

    echo "<div class=\"tracks\">";

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
      echo $track->title;
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

    $lyrics = $json->current->lyrics;
    if ($lyrics) echo "<p>" . nl2br($json->current->lyrics) . "</p>";

    echo "</div>";

    $image = $document->find(".bio-pic a")->attr("href");
    if (isset($additional->publisher->description))
      $text = $additional->publisher->description;
    else
      $text = null;
    $links = $document->find("#band-links li a");

    echo_sidebar($image, $text, $links);

    echo "</div>";
  } else {
    echo "<span>No results.</span>";
  };
?>

<?php require_once "../elements/footer.php" ?>
