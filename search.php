<?php
  $title = htmlspecialchars($_GET["query"]);
?>

<?php include "elements/header.php" ?>

<?php
  $ch = curl_init("https://bandcamp.com/api/bcsearch_public_api/1/autocomplete_elastic");

  curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "search_text" => $_GET["query"],
    "search_filter" => "",
    "full_page" => true
  ]));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $results = json_decode(curl_exec($ch))->auto->results;

  echo "<div class=results>";

  foreach ($results as $result) {
    $link = $result->item_url_path ?: $result->item_url_root;

    switch ($result->type) {
      case "b":
        $domain = explode(".", parse_url($result->item_url_root, PHP_URL_HOST));
        if (end($domain) === "com" && prev($domain) === "bandcamp")
          /* TODO: Some artists and labels use custom domains for their pages.
                   Blindly sending requests to these could be a security risk.
                   Is there a good way to support these? */
          $link = "artist.php?name=" . prev($domain);
        break;
    };

    $image = $result->type === "b"
      ? $result->img
      : str_replace("/img/", "/img/a", $result->img);
    $image = "image.php?file=" . basename($image);

    echo "<a href=" . $link . ">";
    echo "<div>";
    echo "<img src=" . $image . ">";
    echo "<p>";
    echo htmlspecialchars($result->name);

    switch ($result->type) {
      case "a":
        echo "<br>";
        echo "<small>by " . htmlspecialchars($result->band_name) . "</small>";
        break;

      case "b":
        /* TODO: Some artists seem to use a different image URL scheme prefixed
                 with an `a`, similar to releases and tracks. Is it possible to
                 determine which scheme is used when? */
        break;

      case "t":
        echo "<br>";
        echo "<small>";
        echo "by " . htmlspecialchars($result->band_name);
        echo "<br>";
        echo "on " . htmlspecialchars($result->album_name ?: $result->name);
        echo "</small>";
        break;
    };

    echo "</p>";
    echo "</div>";
    echo "</a>";
  };

  echo "</div>";
?>

<?php include "elements/footer.php" ?>
