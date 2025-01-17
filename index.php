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

    $currentVideoId = $_POST['video_id'] ?? '';

    $setReaction = Reactions::setReaction($postArray, $currentVideoId);

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

$videoObj = new Videos();
$videoObj->VideoLinks($con);

$result = $con->query("SELECT id, url FROM videos");

if ($result) {
    $videos = [];
    while ($row = $result->fetch_assoc()) {
        $videos[] = ['id' => $row['id'], 'url' => $row['url']];
    }
} else {
    echo "Error fetching videos: " . $con->error;
    exit();
}

if (empty($videos)) {
    echo "No videos available.";
    exit();
}

$currentVideoId = $videos[0]['id']; 

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
 
    <iframe id="video-player" width="560" height="315" src="<?php echo $videos[0]['url']; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

    <button id="next-button">Next Video</button>

    <script>
        const videos = <?php echo json_encode($videos); ?>;
        let currentIndex = 0;

        document.getElementById('next-button').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % videos.length;
            const videoPlayer = document.getElementById('video-player');
            videoPlayer.src = videos[currentIndex].url;

            document.getElementById('video-id').value = videos[currentIndex].id;

            loadComments(videos[currentIndex].id);
        });

        function loadComments(videoId) {
            const reactionsContainer = document.getElementById('reactions-container');

            reactionsContainer.innerHTML = ''; 
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "get_reactions.php?video_id=" + videoId, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    reactionsContainer.innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        loadComments(videos[0].id);
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
        <input type="hidden" name="video_id" id="video-id" value="<?php echo $videos[0]['id']; ?>">
        <input type="submit" value="Send">
    </form>

    <h2>Reactions</h2>
    <div id="reactions-container">
        
        <?php
        $reactions = Reactions::getReactions($currentVideoId);
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
    </div>
</body>
</html>

<?php
$con->close();
?>
