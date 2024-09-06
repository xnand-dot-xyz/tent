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
?>

<h1>Settings</h1>

<form>
  <div>
    <p><b>Theme</b></p>
    <p>Whether to adapt to the system theme or always use a light or dark interface.</p>
    <p>
      <select name="theme">
        <?php
          foreach ([
            "system" => "System",
            "light" => "Light",
            "dark" => "Dark"
          ] as $name => $value) {
            echo "<option value=\"" . $name . "\"";
            if (isset($_COOKIE["theme"]) && $_COOKIE["theme"] === $name) echo " selected";
            echo ">" . $value . "</option>";
          };
        ?>
      </select>
    </p>
  </div>

  <div>
    <p><b>Details</b></p>
    <p>Which of the collapsible details the release page is made up of to expand by default.</p>
    <p>
      <select name="details[]" multiple>
        <?php
          foreach ([
            "tracklist" => "Tracklist",
            "lyrics" => "Lyrics",
            "license" => "License",
            "tags" => "Tags",
            "recommendations" => "Recommendations"
          ] as $name => $value) {
            echo "<option value=\"" . $name . "\"";
            if (
              (isset($_COOKIE["details"]) && in_array($name, json_decode($_COOKIE["details"]))) ||
              (!isset($_COOKIE["details"]) && $name === "tracklist")
            )
              echo " selected";
            echo ">" . $value . "</option>";
          };
        ?>
      </select>
    </p>
  </div>

  <div>
    <p><b>Images</b></p>
    <p>If disabled, speeds up loading and saves data by not loading any images.</p>
    <p>
      <select name="images">
        <?php
          foreach ([
            "system" => "System",
            "enabled" => "Enabled",
            "disabled" => "Disabled"
          ] as $name => $value) {
            echo "<option value=\"" . $name . "\"";
            if (isset($_COOKIE["images"]) && $_COOKIE["images"] === $name) echo " selected";
            echo ">" . $value . "</option>";
          };
        ?>
      </select>
    </p>
  </div>

  <input type="submit" value="Save">
</form>

<?php require_once "../elements/footer.php" ?>
