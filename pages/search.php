<?php
  $title = htmlspecialchars($_GET["query"]);
?>

<?php include "../elements/header.php" ?>
<?php include "../utilities/link.php" ?>

<?php
  echo "<h1>Search: “" . htmlspecialchars($_GET["query"]) . "”</h1>";

  $ch = curl_init("https://bandcamp.com/api/bcsearch_public_api/1/autocomplete_elastic");

  curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "search_text" => $_GET["query"],
    "search_filter" => "",
    "full_page" => true
  ]));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $results = json_decode(curl_exec($ch))->auto->results;

  echo "<div class=\"results\">";

  foreach ($results as $result) {
    $link = $result->item_url_path ?? $result->item_url_root;
    $link = convert_link($link);

    echo "<a href=\"" . $link . "\">";
    echo "<img src=\"" . convert_link($result->img) . "\">";
    echo "<span>";
    echo htmlspecialchars($result->name);

    unset($text);

    switch ($result->type) {
      case "a":
        $text = "by " . htmlspecialchars($result->band_name);
        break;

      case "t":
        $text = "by " . htmlspecialchars($result->band_name);
        $text .= "<br>";
        $text .= "on " . htmlspecialchars($result->album_name ?? $result->name);
        break;
    };

    if (isset($text))
      echo "<br><small>" . $text . "</small>";

    echo "</span>";
    echo "</a>";
  };

  if (empty($results))
    echo "<span>No results.</span>";

  echo "</div>";
?>

<?php include "../elements/footer.php" ?>
