<?php
  function echo_sidebar ($image, $text, $links = null) {
    echo "<div class=\"sidebar\">";

    if (isset($image)) {
      if (
        isset($_COOKIE["images"]) &&
        ($_COOKIE["images"] === "disabled" ||
        ($_COOKIE["images"] !== "enabled" && (isset($_SERVER["HTTP_SAVE_DATA"]) && $_SERVER["HTTP_SAVE_DATA"] === "on")))
      )
        $image = get_placeholder();

      echo "<a href=\"" . convert_bandcamp_link(resize_link($image, 0)) . "\">";
      echo "<img src=\"" . convert_bandcamp_link($image) . "\">";
      echo "</a>";
    };

    if (!empty($text)) echo "<p>" . nl2br(htmlspecialchars(trim($text))) . "</p>";

    if ($links && $links->length) {
      echo "<p>";
      foreach ($links as $index => $link) {
        echo "<a href=\"" . convert_bandcamp_link($link->attr("href")) . "\">" . htmlspecialchars($link->text()) . "</a>";
        if ($index !== count($links) - 1) echo "<br>";
      };
      echo "</p>";
    };

    echo "</div>";
  };
?>
