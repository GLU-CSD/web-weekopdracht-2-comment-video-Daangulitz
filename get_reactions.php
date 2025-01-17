<?php
include("config.php");
include("reactions.php");

$videoId = $_GET['video_id'] ?? '';

if ($videoId) {
    // Get reactions for the specified video ID
    $reactions = Reactions::getReactions($videoId);
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
} else {
    echo "<p>No video selected.</p>";
}

$con->close();
?>
