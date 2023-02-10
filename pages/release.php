<?php
  include "../config/config.php";
  include "../utilities/dom.php";
  require "../modules/querypath/src/qp.php";

  $ch = curl_init("https://" . urlencode($_GET["artist"]) . ".bandcamp.com/" . urlencode($_GET["type"]) . "/" . urlencode($_GET["name"]));

  if (isset($config["identity"]))
    curl_setopt($ch, CURLOPT_COOKIE, "identity=" . $config["identity"]);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $document = htmlqp(encode_document(curl_exec($ch)));
  $json = json_decode($document->find("script[data-tralbum]")->attr("data-tralbum"));

  $title = $json->current->title;
?>

<?php include "../elements/header.php" ?>
<?php include "../elements/sidebar.php" ?>
<?php include "../utilities/link.php" ?>

<?php
  if ($json) {
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
      echo "<td>" . ($track->track_num ?? 1) . ".</td>";
      echo "<td><a href=\"" . $link . "\">" . $track->title . "</a></td>";
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

    echo "</div>";

    $image = $document->find(".bio-pic a")->attr("href");
    $text = json_decode($document->find("script[type=\"application/ld+json\"]")->text())->publisher->description;
    $links = $document->find("#band-links li a");

    echo_sidebar($image, $text, $links);

    echo "</div>";
  } else {
    echo "<span>No results.</span>";
  };
?>

<?php include "../elements/footer.php" ?>
