<?php
# Initialize the session
session_start();

# Include connection
require_once "./config.php";

# If user is not logged in then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>" . "window.location.href='./login/login.php';" . "</script>";
  exit;
}

# Define variables and initialize with empty values
$shout_err = "";

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") { 

  # Validate shout
  if (empty(trim($_POST["shout"]))) {
    $shout_err = "Please enter a shout.";
  } else {
    $shout = trim($_POST["shout"]);
    if (strlen($shout) > 500) {
        $shout_err = "Shout cannot contain more than 500 characters.";
      }
  }

  # Check input errors before inserting data into database
  if (empty($username) && empty($shout_err)) {
    # Prepare an insert statement
    $sql = "INSERT INTO chatbox(username, message, ip_addr) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      # Bind varibales to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_shout, $param_ip_addr);

      # Set parameters
      $param_username = htmlspecialchars($_SESSION["username"]);
      $param_shout = $shout;
      $param_ip_addr = $_SERVER['REMOTE_ADDR'];

      # Execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        echo "<script>" . "window.location.href='./';" . "</script>";
        exit;
      } else {
        echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
      }

      # Close statement
      mysqli_stmt_close($stmt);
    }
  }

  # Close connection
  mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="icon" href="/assets/images/favicon.png">
    <link rel="stylesheet" href="/assets/css/topcorner.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <meta name="description" content="This is my chat box website, where I am creating a somewhat anonymous way to talk." />
    <title>Chatbox</title>
</head>
<body> 
    <div class="topcorner">
        <a href="/login/">Account</a>
    </div>
    <div class="form">
    <div style="overflow:auto;" height="50%">
	<table width="100%" border="1px">
		<tr>
			<th>Message</th>
			<th>Author</th>
			<th>Time Posted</th>
		</tr>
		<?php
            require_once "./config.php";
			$query = mysqli_query($link, "SELECT * from chatbox");
			while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
				Print "<tr>";
					Print '<td>'. $row['message'] . "</td>";
					Print '<td>'. $row['username'] . "</td>";
					Print '<td>'. $row['reg_date'] . "</td>";
			}
		?>
	</table>
	</div>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <label for="shout" class="form-label">Shout</label>
            <input type="shout" class="form-control" name="shout" id="shout" value="">
            <small class="text-danger"><?= $shout_err; ?></small>
        <br>
        <input type="submit" name="submit" value="Shout it!">
        <p>Btw, your ip is logged. so like if you dont want it be logged dont use it kthanks</p>
        </form>
    </div>
</body>
</html>