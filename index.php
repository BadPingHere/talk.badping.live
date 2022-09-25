<?php
# Initialize the session
session_start();
# Include connection
require_once "login/config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shout = $_POST["shout"];
    $time = date('H:i');
    # Checks if user is logged in
    $username = htmlspecialchars($_SESSION["username"]);;
    if (empty($username)) {
        $error = 'You are not logged in. You can log in <a href="/login">here</a>.'; // shows if user is not logged in
        exit;
    }
    $sql = "INSERT INTO chatbox(username, message, trn_date) VALUES ('$username','$shout', '$time')";
    # debug code
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = "Shout text: $shout , Time:$time, Username:$username";
        echo("<script>console.log('PHP: " . $data . "');</script>");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="icon" href="/assets/images/favicon.png">
    <link rel="stylesheet" href="/assets/css/topcorner.css">
    <style>.alert {
    padding: 0.5rem;
    }</style>
    <meta name="description" content="This is my chat box website, where I am creating a somewhat anonymous way to talk." />
    <title>Chatbox</title>
</head>
<body> 
        <?php
        if (!empty($error)) {
          echo "<div class='alert alert-danger'>" . $error . "</div>";
        }
        ?>
    <div class="topcorner">
        <a href="/login/">Account</a>
    </div>
    <div class="form">
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <div class="shout-box-text">
                <div class="shoutarea wrapShout" id="shoutarea" style="height:400px;">

                </div>
            </div>
        <textarea type="text" name="shout" class="editShout" id="shout" autocomplete="off"></textarea>
        <br>
        <button type="submit" name="submit" type="button">Shout</button>
        </form>
    </div>
</body>
</html>