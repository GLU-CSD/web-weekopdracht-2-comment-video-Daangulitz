<?php
class Videos
{
    public static function getVideoUrls($con) {
        $result = $con->query("SELECT url FROM videos");
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    function VideoLinks($con) {
        $videos = [
            ['url' => 'https://www.youtube.com/embed/B7zDTlQP1-o?si=w2Y6tRzP-ixS8uga', 'title' => 'Video 1'],
            ['url' => 'https://www.youtube.com/embed/8y9QnS_tMkY?si=98uGpEQHwjT0ivaj', 'title' => 'Video 2'],
            ['url' => 'https://www.youtube.com/embed/RZ_0ImDYrPY?si=ZBSLIXk3Jsic2m7V', 'title' => 'Video 3'],
            ['url' => 'https://www.youtube.com/embed/WDt24qzK2Ig?si=whqQy4wZJvxq-RBq', 'title' => 'Video 4'],
        ];

   
        foreach ($videos as $video) {
            $this->addVideo($con, $video['url'], $video['title']);  
        }
    }

    function addVideo($con, $url, $title) {
        try {
        
            $stmt = $con->prepare("INSERT INTO videos (url, title) VALUES (?, ?)");
            $stmt->bind_param("ss", $url, $title); 

            $stmt->close();
        } catch (Exception $e) {
            echo "Error adding video: " . $e->getMessage() . "<br>";
        }
    }
}
?>
