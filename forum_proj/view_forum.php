<?php
require_once 'config.php';
require_once 'helpers/rep_count.php';
require_once 'helpers/utils.php';

session_start();

?>

<html>
<head>
    <title>KU Forums</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/<?php echo getSheet(); ?>" id="main-style">
</head>
<body>

<?php
include 'head.php';
?>
<div class="root-cont">
<div id="threads">
<!-- Show the threads -->
<?php

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    echo "<h2 class='top-title' id='forum-title'>" . getForumName($conn, $_GET["id"]) . "</h2>\n";
    $stmt = $conn->prepare("SELECT * FROM threads WHERE forum_id=? ORDER BY isSticky='N'");
    $stmt->bindParam(1, $for_id);
    
    $for_id = $_GET["id"];
    $stmt->execute();

    ?>
    
    <a class="forum-link" href="create_thread.php?forum_id=<?php echo $_GET["id"]; ?>">Create Thread</a>
    <p style="text-align: center;"><b>Threads:</b></p>
    <?php
    while ($row = $stmt->fetch()) {
        /*
        if ($row["isSticky"] == "Y") {
            echo "<a class='big-but thread-sticky'>\n";
        } else {
            echo "<a class='big-but'>\n";
        }
         */
?>
            <div class="forum-cont">
            <a class="big-but" href="view_thread.php?id=<?php echo $row["tid"]; ?>&page=1"><?php echo $row["title"]; ?></a>
        <?php if ($row["isSticky"] == "Y") { ?>
            <p class="sticky-circle" style="display: inline;">[Sticky]</p>
            <!-- <img class="sticky-circle" src="site_images/sticky.png"> -->
        <?php
    }echo "(" . getReplyCount($conn, $row["tid"]) . " replies)"; ?>
            </div>
    <?php
    }

    unset($stmt);

}?>
</div>
</div>
<?php include 'foot.php'; ?>
</body>
</html>
