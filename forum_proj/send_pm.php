<?php

require_once "config.php";

session_start();

// disallow if not logged in

if (empty($_SESSION["uid"])) {
    header("location: index.php");
    exit;
}


$recipient = "";
$message = "";
$subject = "";

# for replying - fill the fields
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["recipient"])) {

    $recipient = trim($_GET["recipient"]);
    $subject = trim($_GET["subject"]);

    if (isset($_GET["reply"]) && ($_GET["reply"] == "true")) {
        $subject = "Re: " . $subject;
    }

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["recipient"]) || empty($_POST["subject"]) || empty($_POST["message"])) {
        header("Refresh:0");
    }

    $recipient = trim($_POST["recipient"]);
    $message = trim($_POST["message"]);
    $subject = trim($_POST["subject"]);

    # first, search if the user exists.
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bindParam(1, $recipient);

    if (!$stmt->execute()) {
        print "Database error.";
    } else {

        $row = $stmt->fetch();
        if (count($row) == 0) {
            print "No such user.";
            exit;
        } else {

            $uid_2 = $row["uid"];
            $uid_1 = $_SESSION["uid"];
            $tstamp = date("Y-m-d H:i:s");
            $stmt2 = $conn->prepare("INSERT INTO messages (uid_1, uid_2, title, body, send_date) VALUES (?, ?, ?, ?, ?)");

            $stmt2->bindParam(1, $uid_1);
            $stmt2->bindParam(2, $uid_2);
            $stmt2->bindParam(3, $subject);
            $stmt2->bindParam(4, $message);
            $stmt2->bindParam(5, $tstamp);

            if ($stmt2->execute()) {
                print "Message sent successfully.";
            }

            unset($stmt);
            unset($stmt2);
        }
    }
}


?>

<html>
<head>
    <meta charset="utf-8">
    <title>PM</title>
    <link id="main-style" rel="stylesheet" type="text/css" href="styles/<?php echo getSheet(); ?>">
</head>
<body>

<?php
include 'head.php';
?>

<div class="root-cont">
<form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> id="pm-form">
    <div>
    <label class="form-label">Recipient:</label>
    <input type="text" name="recipient" class="form-field" value="<?php echo $recipient ?>">
    </div>
    
    <div>
    <label class="form-label">Subject:</label>
    <input type="text" name="subject" class="form-field" value="<?php echo $subject; ?>">
    </div>
    
    <div>
    <label class="form-label"><strong>Message:</strong></label>
    <textarea class="form-body" name="message" rows="10" cols="50"></textarea>
    </div>

    <div>
    <input type="submit" class="form=button" value="Send">
    </div>
</form>
</div>
</body>
</html> 
