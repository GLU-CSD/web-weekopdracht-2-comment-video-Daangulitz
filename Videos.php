<?php
class Videos
{
    function addVideo($pdo, $url) {
    try {
        $stmt = $pdo->prepare("INSERT INTO videos (url) VALUES (:url)");
        $stmt->execute(['url' => $url]);
        echo "Video added: $url<br>";
     } catch (PDOException $e) {
         if ($e->getCode() === '23000') { 
              echo "Duplicate video skipped: $url<br>";
            } else {
                echo "Error adding video: " . $e->getMessage() . "<br>";
         }
        }
    }   

    function VideoLinks(){
    $videos = [
        'https://www.youtube.com/embed/B7zDTlQP1-o?si=w2Y6tRzP-ixS8uga',
        'https://www.youtube.com/embed/8y9QnS_tMkY?si=98uGpEQHwjT0ivaj',
        'https://www.youtube.com/embed/RZ_0ImDYrPY?si=ZBSLIXk3Jsic2m7V',
        'https://www.youtube.com/embed/WDt24qzK2Ig?si=whqQy4wZJvxq-RBq',
    ];

    foreach ($videos as $video) {
        addVideo($pdo, $video);
    }
    }



}
?>