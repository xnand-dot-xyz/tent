<?php
  function echo_item ($link, $image, $text, $description) {
    if (
      isset($_COOKIE["images"]) &&
      ($_COOKIE["images"] === "disabled" ||
      ($_COOKIE["images"] !== "enabled" && (isset($_SERVER["HTTP_SAVE_DATA"]) && $_SERVER["HTTP_SAVE_DATA"] === "on")))
    )
      $image = get_placeholder();

    echo "<a href=\"" . $link . "\">";
    echo "<img src=\"" . $image . "\">";
    echo "<span>";
    echo $text;

    if (isset($description)) {
      echo "<br>";
      echo "<small>";
      echo $description;
      echo "</small>";
    };

    echo "</span>";
    echo "</a>";
  };
?>
