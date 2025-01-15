<?php
include("config.php");
include("reactions.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postArray = [
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'message' => $_POST['message'] ?? ''
    ];

    $setReaction = Reactions::setReaction($postArray);

    if (isset($setReaction['error']) && !empty($setReaction['error'])) {
        foreach ($setReaction['error'] as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }

    if (isset($setReaction['succes'])) {
        echo "<p style='color: green;'>{$setReaction['succes']}</p>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit(); 
    }
}  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youtube Remake - Reactions</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <iframe width="560" height="315" src="https://www.youtube.com/embed/MfuLcO8clh0?si=3ytUpY3wqN6kQfwB" 
        title="YouTube video player" frameborder="0" 
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
    </iframe>

    <h2>Hieronder komen reacties</h2>

    <form action="" method="POST">
        <div>
            Naam: <input type="text" name="name" value="" placeholder="Hier je Naam">
        </div>
        <div>
            Email: <input type="text" name="email" value="" placeholder="Hier je Email">
        </div>
        <div>
            <textarea name="message" cols="30" rows="10" placeholder="Schrijf hier je reactie..."></textarea>
        </div>
        <input type="submit" value="Send">
    </form>

    <h2>Reactions</h2>
    <?php
    $reactions = Reactions::getReactions();
    if (!empty($reactions)) {
        foreach ($reactions as $reaction) {
            echo "<div style='border: 1px solid #ddd; margin-bottom: 10px;'>";
            echo "<strong>Naam:</strong> " . htmlspecialchars($reaction['name']) . "<br>";
            echo "<strong>Email:</strong> " . htmlspecialchars($reaction['email']) . "<br>";
            echo "<strong>Bericht:</strong> " . htmlspecialchars($reaction['message']);
            echo "</div>";
        }
    } else {
        echo "<p>Er zijn nog geen reacties.</p>";
    }
    ?>

</body>
</html>

<?php
$con->close();

?>
