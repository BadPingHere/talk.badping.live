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
  if (empty($shout_err) && empty($shout_err)) {
    # Prepare an insert statement
    $sql = "INSERT INTO chatbox_v2(user_id, username, message, ip_addr, message_time) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      # Bind varibales to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "sssss", $param_user_id, $param_username, $param_shout, $param_ip_addr, $param_message_time);

      # Set parameters
      $param_username = htmlspecialchars($_SESSION["username"]);
      $param_shout = $shout;
      $param_user_id = $_SESSION["id"];
      $param_message_time = date('H:i');
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
<!DOCTYPE HTML>
<html lang="en">
  <head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.1/vue.min.js" data-cfasync="false"></script>
    <meta charset="UTF-8">
    <title>ShoutBox</title>
    <style type="text/css">
      svg:not(:root).svg-inline--fa {
        overflow: visible;
      }

      .svg-inline--fa {
        display: inline-block;
        font-size: inherit;
        height: 1em;
        overflow: visible;
        vertical-align: -.125em;
      }

      .svg-inline--fa.fa-lg {
        vertical-align: -.225em;
      }

      .svg-inline--fa.fa-w-1 {
        width: 0.0625em;
      }

      .svg-inline--fa.fa-w-2 {
        width: 0.125em;
      }

      .svg-inline--fa.fa-w-3 {
        width: 0.1875em;
      }

      .svg-inline--fa.fa-w-4 {
        width: 0.25em;
      }

      .svg-inline--fa.fa-w-5 {
        width: 0.3125em;
      }

      .svg-inline--fa.fa-w-6 {
        width: 0.375em;
      }

      .svg-inline--fa.fa-w-7 {
        width: 0.4375em;
      }

      .svg-inline--fa.fa-w-8 {
        width: 0.5em;
      }

      .svg-inline--fa.fa-w-9 {
        width: 0.5625em;
      }

      .svg-inline--fa.fa-w-10 {
        width: 0.625em;
      }

      .svg-inline--fa.fa-w-11 {
        width: 0.6875em;
      }

      .svg-inline--fa.fa-w-12 {
        width: 0.75em;
      }

      .svg-inline--fa.fa-w-13 {
        width: 0.8125em;
      }

      .svg-inline--fa.fa-w-14 {
        width: 0.875em;
      }

      .svg-inline--fa.fa-w-15 {
        width: 0.9375em;
      }

      .svg-inline--fa.fa-w-16 {
        width: 1em;
      }

      .svg-inline--fa.fa-w-17 {
        width: 1.0625em;
      }

      .svg-inline--fa.fa-w-18 {
        width: 1.125em;
      }

      .svg-inline--fa.fa-w-19 {
        width: 1.1875em;
      }

      .svg-inline--fa.fa-w-20 {
        width: 1.25em;
      }

      .svg-inline--fa.fa-pull-left {
        margin-right: .3em;
        width: auto;
      }

      .svg-inline--fa.fa-pull-right {
        margin-left: .3em;
        width: auto;
      }

      .svg-inline--fa.fa-border {
        height: 1.5em;
      }

      .svg-inline--fa.fa-li {
        width: 2em;
      }

      .svg-inline--fa.fa-fw {
        width: 1.25em;
      }

      .fa-layers svg.svg-inline--fa {
        bottom: 0;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        top: 0;
      }

      .fa-layers {
        display: inline-block;
        height: 1em;
        position: relative;
        text-align: center;
        vertical-align: -.125em;
        width: 1em;
      }

      .fa-layers svg.svg-inline--fa {
        -webkit-transform-origin: center center;
        transform-origin: center center;
      }

      .fa-layers-text,
      .fa-layers-counter {
        display: inline-block;
        position: absolute;
        text-align: center;
      }

      .fa-layers-text {
        left: 50%;
        top: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        -webkit-transform-origin: center center;
        transform-origin: center center;
      }

      .fa-layers-counter {
        background-color: #ff253a;
        border-radius: 1em;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        color: #fff;
        height: 1.5em;
        line-height: 1;
        max-width: 5em;
        min-width: 1.5em;
        overflow: hidden;
        padding: .25em;
        right: 0;
        text-overflow: ellipsis;
        top: 0;
        -webkit-transform: scale(0.25);
        transform: scale(0.25);
        -webkit-transform-origin: top right;
        transform-origin: top right;
      }

      .fa-layers-bottom-right {
        bottom: 0;
        right: 0;
        top: auto;
        -webkit-transform: scale(0.25);
        transform: scale(0.25);
        -webkit-transform-origin: bottom right;
        transform-origin: bottom right;
      }

      .fa-layers-bottom-left {
        bottom: 0;
        left: 0;
        right: auto;
        top: auto;
        -webkit-transform: scale(0.25);
        transform: scale(0.25);
        -webkit-transform-origin: bottom left;
        transform-origin: bottom left;
      }

      .fa-layers-top-right {
        right: 0;
        top: 0;
        -webkit-transform: scale(0.25);
        transform: scale(0.25);
        -webkit-transform-origin: top right;
        transform-origin: top right;
      }

      .fa-layers-top-left {
        left: 0;
        right: auto;
        top: 0;
        -webkit-transform: scale(0.25);
        transform: scale(0.25);
        -webkit-transform-origin: top left;
        transform-origin: top left;
      }

      .fa-lg {
        font-size: 1.33333em;
        line-height: 0.75em;
        vertical-align: -.0667em;
      }

      .fa-xs {
        font-size: .75em;
      }

      .fa-sm {
        font-size: .875em;
      }

      .fa-1x {
        font-size: 1em;
      }

      .fa-2x {
        font-size: 2em;
      }

      .fa-3x {
        font-size: 3em;
      }

      .fa-4x {
        font-size: 4em;
      }

      .fa-5x {
        font-size: 5em;
      }

      .fa-6x {
        font-size: 6em;
      }

      .fa-7x {
        font-size: 7em;
      }

      .fa-8x {
        font-size: 8em;
      }

      .fa-9x {
        font-size: 9em;
      }

      .fa-10x {
        font-size: 10em;
      }

      .fa-fw {
        text-align: center;
        width: 1.25em;
      }

      .fa-ul {
        list-style-type: none;
        margin-left: 2.5em;
        padding-left: 0;
      }

      .fa-ul>li {
        position: relative;
      }

      .fa-li {
        left: -2em;
        position: absolute;
        text-align: center;
        width: 2em;
        line-height: inherit;
      }

      .fa-border {
        border: solid 0.08em #eee;
        border-radius: .1em;
        padding: .2em .25em .15em;
      }

      .fa-pull-left {
        float: left;
      }

      .fa-pull-right {
        float: right;
      }

      .fa.fa-pull-left,
      .fas.fa-pull-left,
      .far.fa-pull-left,
      .fal.fa-pull-left,
      .fab.fa-pull-left {
        margin-right: .3em;
      }

      .fa.fa-pull-right,
      .fas.fa-pull-right,
      .far.fa-pull-right,
      .fal.fa-pull-right,
      .fab.fa-pull-right {
        margin-left: .3em;
      }

      .fa-spin {
        -webkit-animation: fa-spin 2s infinite linear;
        animation: fa-spin 2s infinite linear;
      }

      .fa-pulse {
        -webkit-animation: fa-spin 1s infinite steps(8);
        animation: fa-spin 1s infinite steps(8);
      }

      @-webkit-keyframes fa-spin {
        0% {
          -webkit-transform: rotate(0deg);
          transform: rotate(0deg);
        }

        100% {
          -webkit-transform: rotate(360deg);
          transform: rotate(360deg);
        }
      }

      @keyframes fa-spin {
        0% {
          -webkit-transform: rotate(0deg);
          transform: rotate(0deg);
        }

        100% {
          -webkit-transform: rotate(360deg);
          transform: rotate(360deg);
        }
      }

      .fa-rotate-90 {
        -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=1)";
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg);
      }

      .fa-rotate-180 {
        -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=2)";
        -webkit-transform: rotate(180deg);
        transform: rotate(180deg);
      }

      .fa-rotate-270 {
        -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=3)";
        -webkit-transform: rotate(270deg);
        transform: rotate(270deg);
      }

      .fa-flip-horizontal {
        -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0, mirror=1)";
        -webkit-transform: scale(-1, 1);
        transform: scale(-1, 1);
      }

      .fa-flip-vertical {
        -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=2, mirror=1)";
        -webkit-transform: scale(1, -1);
        transform: scale(1, -1);
      }

      .fa-flip-horizontal.fa-flip-vertical {
        -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=2, mirror=1)";
        -webkit-transform: scale(-1, -1);
        transform: scale(-1, -1);
      }

      :root .fa-rotate-90,
      :root .fa-rotate-180,
      :root .fa-rotate-270,
      :root .fa-flip-horizontal,
      :root .fa-flip-vertical {
        -webkit-filter: none;
        filter: none;
      }

      .fa-stack {
        display: inline-block;
        height: 2em;
        position: relative;
        width: 2.5em;
      }

      .fa-stack-1x,
      .fa-stack-2x {
        bottom: 0;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        top: 0;
      }

      .svg-inline--fa.fa-stack-1x {
        height: 1em;
        width: 1.25em;
      }

      .svg-inline--fa.fa-stack-2x {
        height: 2em;
        width: 2.5em;
      }

      .fa-inverse {
        color: #fff;
      }

      .sr-only {
        border: 0;
        clip: rect(0, 0, 0, 0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
      }

      .sr-only-focusable:active,
      .sr-only-focusable:focus {
        clip: auto;
        height: auto;
        margin: 0;
        overflow: visible;
        position: static;
        width: auto;
      }
    </style>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="https://talk.badping.live/assets/images/favicon.png">
    <link rel="image_src" href="https://talk.badping.live/assets/images/favicon.png">
    <link rel="stylesheet" type="text/css" title="Main" media="screen,print" href="https://talk.badping.live/ipb_styles.css">
    <meta name="theme-color" content="#0f5579">
    <meta property="og:title" content="ShoutBox">
    <meta property="og:site_name" content="ShoutBox">
    <meta property="og:type" content="article">
    <meta name="description" content="This is my chat box website, where I am creating a somewhat anonymous way to talk..">
    <meta property="og:description" content="This is my chat box website, where I am creating a somewhat anonymous way to talk.">
    <meta name="keywords" content="chatbox, shoutbox, badpinghere">
    <meta name="identifier-url" content="https://talk.badping.live">
    <meta property="og:url" content="https://talk.badping.live">
    <meta property="og:image" content="https://talk.badping.live/assets/images/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link id="ipsCanonical" rel="canonical" href="https://talk.badping.live">
  </head>
  <body id="ipboard_body" onpageshow="event.persisted &amp;&amp; (function(){var allInstances = CKEDITOR.instances, editor, doc;for ( var i in allInstances ){	editor = allInstances[ i ];	doc = editor.document;	if ( doc )	{		doc.$.designMode = &quot;off&quot;;		doc.$.designMode = &quot;on&quot;;	}}})();">
    <div id="ipbwrapper">
      <div id="branding">
        <div class="wrapper"></div>
      </div>
      <div class="wrapper">
        <div class="content" id="mainContent">
          <div id="content" class="clearfix">
            <div>
                <a href="/login/">Account</a>
            </div>
            <noscript>
              <div class='message error'>
                <strong>Javascript Disabled Detected</strong>
                <p>You currently have javascript disabled. Several functions may not work. Please re-enable javascript to access full functionality.</p>
              </div>
              <br />
            </noscript>
            <div id="board_index" class="ipsLayout ipsLayout_withright ipsLayout_largeright clearfix ">
              <div id="categories" class="ipsLayout_content clearfix">
                <div class="category_block block_wrap">
                  <h3 class="maintitle">
                    <span class="title"> Shoutbox </span>
                  </h3>
                  <div id="shoutbox-announcement-text" style="background: rgb(21, 21, 21); padding: 5px;">
                    <p>
                      Welcome to <strong class="bbc">Ping's Shoutbox</strong>. Please read <a href="https://talk.badping.live/rules.html">forum rules </a> &amp; have a good time!
                    </p>
                  </div>
                  <div id="shoutbox">
                    <!---->
                    <div class="shoutbox-modals">
                      <!---->
                      <!---->
                      <!---->
                      <!---->
                      <!---->
                    </div>
                    <div class="shoutbox-content">
                      <div class="channels">
                        <div data-v-18aeaae6="" tabindex="-1" class="v-context context-classic" style="display: none;"></div>
                        <div>
                          <div class="channel channel-item draggable-channel tabbed" draggable="false">
                            <span>
                              <span class="channel-icon">
                                <svg aria-hidden="true" data-prefix="fas" data-icon="comments" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="icon svg-inline--fa fa-comments fa-w-18">
                                  <path fill="currentColor" d="M416 192c0-88.4-93.1-160-208-160S0 103.6 0 192c0 34.3 14.1 65.9 38 92-13.4 30.2-35.5 54.2-35.8 54.5-2.2 2.3-2.8 5.7-1.5 8.7S4.8 352 8 352c36.6 0 66.9-12.3 88.7-25 32.2 15.7 70.3 25 111.3 25 114.9 0 208-71.6 208-160zm122 220c23.9-26 38-57.7 38-92 0-66.9-53.5-124.2-129.3-148.1.9 6.6 1.3 13.3 1.3 20.1 0 105.9-107.7 192-240 192-10.8 0-21.3-.8-31.7-1.9C207.8 439.6 281.8 480 368 480c41 0 79.1-9.2 111.3-25 21.8 12.7 52.1 25 88.7 25 3.2 0 6.1-1.9 7.3-4.8 1.3-2.9.7-6.3-1.5-8.7-.3-.3-22.4-24.2-35.8-54.5z" class=""></path>
                                </svg>
                              </span>
                              <span>general</span>
                            </span>
                            <!---->
                          </div>
                        </div>
                      </div>
                      <div class="messages">
                        <div data-v-18aeaae6="" tabindex="-1" class="v-context context-classic" style="display: none;"></div>
                        <div data-v-18aeaae6="" tabindex="-1" class="v-context context-classic" style="display: none;"></div>
                        <?php
                            require_once "./config.php";
                			$query = mysqli_query($link, "SELECT * from chatbox_v2 order by message_id desc");
                			while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                					Print '<div class="entry">';
                					Print '<div class="field cell-author">';
                					Print '<img src="https://talk.badping.live/login/img/blank-avatar.jpg" class="item-sb-img" style="margin-left:5px;margin-top:-2px;" width="24" height="24">';
                					Print '<svg aria-hidden="true" data-prefix="fal" data-icon="at" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon tagging svg-inline--fa fa-at fa-w-16">';
                					Print '<path fill="currentColor" d="M256 8C118.941 8 8 118.919 8 256c0 137.058 110.919 248 248 248 52.925 0 104.68-17.078 147.092-48.319 5.501-4.052 6.423-11.924 2.095-17.211l-5.074-6.198c-4.018-4.909-11.193-5.883-16.307-2.129C346.93 457.208 301.974 472 256 472c-119.373 0-216-96.607-216-216 0-119.375 96.607-216 216-216 118.445 0 216 80.024 216 200 0 72.873-52.819 108.241-116.065 108.241-19.734 0-23.695-10.816-19.503-33.868l32.07-164.071c1.449-7.411-4.226-14.302-11.777-14.302h-12.421a12 12 0 0 0-11.781 9.718c-2.294 11.846-2.86 13.464-3.861 25.647-11.729-27.078-38.639-43.023-73.375-43.023-68.044 0-133.176 62.95-133.176 157.027 0 61.587 33.915 98.354 90.723 98.354 39.729 0 70.601-24.278 86.633-46.982-1.211 27.786 17.455 42.213 45.975 42.213C453.089 378.954 504 321.729 504 240 504 103.814 393.863 8 256 8zm-37.92 342.627c-36.681 0-58.58-25.108-58.58-67.166 0-74.69 50.765-121.545 97.217-121.545 38.857 0 58.102 27.79 58.102 65.735 0 58.133-38.369 122.976-96.739 122.976z" class=""></path>';
                					Print '</svg>';
                					Print '<a href="/user/'. $row['user_id'] . '" target="_blank" rel="noopener">';
                					Print '<span style="color: #FFFFFF;">' . $row['username'] . '</span>';
                					Print '</a>';
                					Print '</div>';
                					Print '<div class="field cell-message">';
                					Print '<span>' . $row['message'] . '</span>';
                					Print '</div>';
                					Print '<div class="field cell-date">';
                					Print '<span>'. $row['message_time'] . "</span>";
                					Print '</div>';
                					Print '</div>';
                			}
                		?>
                      </div>
                      <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
                      <div class="participate">
                        <input type="text" id="shout" name="shout" type="shout" placeholder="Type to shout...">
                            <input type="submit" name="submit" value="Send">
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <link href="https://talk.badping.live/app.5f0fff2f.css" rel="stylesheet" type="text/css">
              <!--<script src="https://talk.badping.live/app.734783f3.js" data-cfasync="false"></script>-->
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </body>
</html>