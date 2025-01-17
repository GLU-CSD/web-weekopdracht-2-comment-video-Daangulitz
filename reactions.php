<?php
class Reactions
{
    static function setReaction($postArray, $videoId)
    {
        global $con;
        $array = [];

        if (!empty($postArray)) {
            if (isset($postArray['name']) && $postArray['name'] != '') {
                $name = stripslashes(trim($postArray['name']));
            } else {
                $array['error'][] = "Name not set or empty.";
            }
            if (isset($postArray['email']) && filter_var($postArray['email'], FILTER_VALIDATE_EMAIL)) {
                $email = stripslashes(trim($postArray['email']));
            } else {
                $array['error'][] = "Invalid email format.";
            }
            if (isset($postArray['message']) && $postArray['message'] != '') {
                $message = stripslashes(trim($postArray['message']));
            } else {
                $array['error'][] = "Message not set or empty.";
            }
            if (empty($array['error'])) {

                $srqry = $con->prepare("INSERT INTO reactions (id, name, email, message, video_id) VALUES (?, ?, ?, ?, ?)");
                if ($srqry === false) {
                    $array['error'][] = "SQL error: " . mysqli_error($con);
                } else {
                    $srqry->bind_param('sssss', $id, $name, $email, $message, $videoId);
                    if ($srqry->execute() === false) {
                        $array['error'][] = "Execution error: " . mysqli_error($con);
                    } else {
                        $array['succes'] = "Reaction saved successfully.";
                    }
                    $srqry->close();
                }
            }
        }
        return $array;
    }

    static function getReactions($videoId)
    {
        global $con;
        $array = [];
        $grqry = $con->prepare("SELECT name, email, message FROM reactions WHERE video_id = ? ORDER BY date_added DESC");
        if ($grqry === false) {
            prettyDump(mysqli_error($con));
        } else {
            $grqry->bind_param('s', $videoId);
            $grqry->execute();
            $grqry->bind_result($name, $email, $message);
            while ($grqry->fetch()) {
                $array[] = [
                    'name' => $name,
                    'email' => $email,
                    'message' => $message
                ];
            }
            $grqry->close();
        }
        return $array;
    }

    function prettyDump($message)
    {
        echo "<pre style='color: red;'>" . htmlspecialchars($message) . "</pre>";
    }
}
