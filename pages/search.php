<?php
  $title = htmlspecialchars($_GET["query"]);
?>

<?php require_once "../elements/header.php" ?>
<?php require_once "../elements/item.php" ?>
<?php require_once "../utilities/link.php" ?>

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

    echo_item($link, convert_link(resize_link($result->img, 3)), htmlspecialchars($result->name), $text ?? null);
  };

  if (empty($results))
    echo "<span>No results.</span>";

  echo "</div>";
?>

<?php require_once "../elements/footer.php" ?>
