<?php require_once "../utilities/index.php" ?>

<?php
  $title = "Settings";
?>

<?php require_once "../elements/header.php" ?>

<?php
  foreach ($_GET as $name => $value) {
    if (is_array($value))
      $value = json_encode($value);

    setcookie($name, $value);
  };

  if (count($_GET)) {
    header("Location: " . strtok($_SERVER["REQUEST_URI"], "?"));
    exit();
  };

  $settings = [
    "theme" => [
      "title" => "Theme",
      "description" => "Whether to adapt to the system theme or always use a light or dark interface.",
      "type" => "select",
      "options" => [
        "system" => "System",
        "light" => "Light",
        "dark" => "Dark"
      ]
    ],
    "design" => [
      "title" => "Design",
      "description" => "Whether to, if available, use artist-defined colors on artist and release pages.",
      "type" => "checkbox",
      "label" => "Enabled"
    ],
    "details" => [
      "title" => "Details",
      "description" => "Which of the collapsible details the release page is made up of to expand by default.",
      "type" => "multiple",
      "options" => [
        "tracklist" => "Tracklist",
        "videos" => "Videos",
        "lyrics" => "Lyrics",
        "credits" => "Credits",
        "license" => "License",
        "tags" => "Tags",
        "recommendations" => "Recommendations"
      ]
    ],
    "images" => [
      "title" => "Images",
      "description" => "If disabled, speeds up loading and saves data by not loading any images.",
      "type" => "select",
      "options" => [
        "system" => "System",
        "enabled" => "Enabled",
        "disabled" => "Disabled"
      ]
    ],
    "overflow" => [
      "title" => "Overflow",
      "description" => "Whether to allow scrolling each column of the desktop layout independently.",
      "type" => "checkbox",
      "label" => "Enabled"
    ]
  ];
?>

<h1>Settings</h1>

<form>
  <?php
    foreach ($settings as $key => $value) { ?>
      <div>
        <p><b><?= $value["title"] ?></b></p>
        <p><?= $value["description"] ?></p>
        <p>
          <?php
            switch ($value["type"]) {
              case "checkbox":
                echo "<input name=\"" . $key . "\" type=\"hidden\" value=\"off\">";
                echo "<input id=\"" . $key . "\" name=\"" . $key . "\" type=\"checkbox\"";
                if (isset($_COOKIE[$key]) && $_COOKIE[$key] === "on")
                  echo " checked";
                echo "> ";
                echo "<label for=\"" . $key . "\">" . $value["label"] . "</label>";
                break;

              case "multiple":
                echo "<select name=\"" . $key . "[]\" multiple size=\"" . count($value["options"]) . "\">";
                foreach ($value["options"] as $optionkey => $optionvalue) {
                  echo "<option value=\"" . $optionkey . "\"";
                  if (
                    (isset($_COOKIE[$key]) && in_array($optionkey, json_decode($_COOKIE[$key]))) ||
                    (!isset($_COOKIE[$key]) && $optionkey === array_key_first($value["options"]))
                  )
                    echo " selected";
                  echo ">" . $optionvalue . "</option>";
                };
                echo "</select>";
                break;

              case "select":
                echo "<select name=\"" . $key . "\">";
                foreach ($value["options"] as $optionkey => $optionvalue) {
                  echo "<option value=\"" . $optionkey . "\"";
                  if (isset($_COOKIE[$key]) && $_COOKIE[$key] === $optionkey)
                    echo " selected";
                  echo ">" . $optionvalue . "</option>";
                };
                echo "</select>";
                break;
            };
          ?>
        </p>
      </div>
    <?php };
  ?>

  <input type="submit" value="Save">
</form>

<?php require_once "../elements/footer.php" ?>
