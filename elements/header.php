<?php
  require_once "../config/config.php";

  if (!isset($config["title"]))
    $config["title"] = "Tent";

  if (isset($title) && !empty($title))
    $title = $config["title"] . " · " . $title;
  else
    $title = $config["title"];

  header("Content-Security-Policy: default-src 'none'; form-action 'self'; img-src 'self' data:; media-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'");
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="icon" href="asset.php?file=favicon.svg">
    <link rel="stylesheet" href="asset.php?file=style.css">
    <script src="asset.php?file=script.js"></script>
  </head>
  <body class="
    <?php
      echo "theme-" . (isset($_COOKIE["theme"]) ? htmlspecialchars($_COOKIE["theme"]) : "system");

      if (isset($_COOKIE["overflow"]) && $_COOKIE["overflow"] === "on")
        echo " overflow";
    ?>
  ">
    <header>
      <div class="wrapper">
        <a href="." title="Tent">
          ⛺
        </a>
        <form action="search.php">
          <input name="query" placeholder="Search..." required>
          <input type="submit" value="🔍" title="Search">
        </form>
        <?php
          $link = convert_tent_link("//" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
          if ($link)
            echo "<a href=\"" . $link . "\" title=\"Bandcamp\">🔗</a>";
        ?>
        <a href="settings.php" title="Settings">
          ⚙️
        </a>
      </div>
    </header>
    <main>
      <div class="wrapper">
