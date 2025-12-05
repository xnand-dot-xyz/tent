<?php require_once "../utilities/index.php" ?>

<?php
  if (isset($_GET["tags"]))
    $title = htmlspecialchars(str_replace([" ", "-"], [", ", " "], $_GET["tags"]));
?>

<?php require_once "../elements/header.php" ?>
<?php require_once "../elements/item.php" ?>

<?php
  echo "<h1>Discover";
  if (isset($_GET["tags"]))
    echo ": " . htmlspecialchars(str_replace([" ", "-"], [", ", " "], $_GET["tags"]));
  echo "</h1>";

  $ch = curl_init("https://bandcamp.com/api/discover/1/discover_web");

  curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "tag_norm_names" => isset($_GET["tags"]) ? explode(" ", $_GET["tags"]) : [],
    "include_result_types" => ["a", "s"],
    "cursor" => isset($_GET["cursor"]) ? $_GET["cursor"] : "*",
    "slice" => "top"
  ]));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $results = json_decode(curl_exec($ch));

  if (empty($results->results))
    echo_error_message();

  echo "<div class=\"results\">";

  foreach ($results->results as $result) {
    $link = convert_bandcamp_link($result->item_url);
    $image = convert_bandcamp_link(resize_link("https://f4.bcbits.com/img/" . $result->primary_image->image_id . ".jpg", 3));
    $text = htmlspecialchars($result->title);
    $description = "by " . htmlspecialchars($result->band_name);

    echo_item($link, $image, $text, $description);
  };

  echo "</div>";

  if ($results->cursor) {
    if (array_key_exists("QUERY_STRING", $_SERVER))
      parse_str($_SERVER["QUERY_STRING"], $query);
    else
      $query = [];

    $query["cursor"] = $results->cursor;

    echo "<p>";
    echo "<a href=\"?" . http_build_query($query) . "\">Next page →</a>";
    echo "</p>";
  };
?>

<?php require_once "../elements/footer.php" ?>
