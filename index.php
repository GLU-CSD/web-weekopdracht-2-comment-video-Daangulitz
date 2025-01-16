<?php
include("config.php");
include("reactions.php");
include("Videos.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postArray = [
        'id' => $_POST['id'] ?? '',
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

$stmt = $pdo->query("SELECT url FROM videos");
$videos = $stmt->fetchAll(PDO::FETCH_COLUMN);
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
     <div id="video-container">
        <iframe id="video-player" src="<?php echo htmlspecialchars($videos[0]); ?>" allowfullscreen></iframe>
    </div>
    <button id="next-button">Next Video</button>
    <script>
        const videos = <?php echo json_encode($videos); ?>;
        let currentIndex = 0;

        document.getElementById('next-button').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % videos.length; 
            const videoPlayer = document.getElementById('video-player');
            videoPlayer.src = videos[currentIndex];
        });
    </script>
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
