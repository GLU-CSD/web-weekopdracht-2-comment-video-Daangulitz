<?php
class Videos
{

    $urlsArray = [
        'https://www.youtube.com/embed/rhvF2_JkDhQ?si=hpUWsOsXMREQ0aJF',
        'https://www.youtube.com/embed/8y9QnS_tMkY?si=CuNrJPBVnw9FICTF',
        'https://example.com/php-mysql',
        'https://example.com/php-security',
        'https://example.com/php-frameworks'
    ];

    static function setVideo($postArray){
        global $con;
        $array = [];
        if (!empty($postArray)) {

            if (isset($postArray['title']) && $postArray['title'] != '') {
                $title = stripslashes(trim($postArray['title']));
            }else{
                $array['error'][] = "title not set in array";
            }
            if (isset($postArray['description']) && filter_var($postArray['description'] != '')) {
                $description = stripslashes(trim($postArray['description']));
            }else{
                $array['description'][] = '';
            }

        if (isset($postArray['url']) && is_array($postArray['url']) && count($postArray['url']) > 0) {
        $urlArray = array_map('trim', $postArray['url']); // Trim each URL
        $validUrls = [];
        }
        foreach ($urlArray as $url) {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $validUrls[] = stripslashes($url); 
        } else {
            $array['error'][] = "Invalid URL: $url";
        }
    }
    

        if (empty($validUrls)) {
        $array['error'][] = "No valid URLs provided";
        } else {
        $array['error'][] = "URL array not set or empty";
        }

            if (empty($array['error'])) {

                $svqry = $con->prepare("INSERT INTO videos (title,description,url) VALUES (?,?,?);");
                if ($svqry === false) {
                    prettyDump( mysqli_error($con) );
                }
                
                $svqry->bind_param('sss',$title,$description,$url);
                if ($svqry->execute() === false) {
                    prettyDump( mysqli_error($con) );
                }else{
                    $array['succes'] = "Video posted succesfully";
                }
            
                $svqry->close();
            }
            return $array;
        }
    }

    
    static function getVideos(){
        global $con;
        $array = [];
        $gvqry = $con->prepare("SELECT id,title,description,url FROM videos;");
        if($gvqry === false) {
            prettyDump( mysqli_error($con) );
        } else{
            $gvqry->bind_result($id,$title,$description,$url);
            if($gvqry->execute()){
                $gvqry->store_result();
                while($gvqry->fetch()){
                    $array[] = [
                        'id' => $id,
                        'title' => $title,
                        'description'=> $description,
                        'url' => $url
                    ];
                }
            }
            $gvqry->close();
        }
        return $array;
    }
}
?>