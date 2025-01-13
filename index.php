<?php
include("config.php");
include("reactions.php");

$getReactions = Reactions::getReactions();
//uncomment de volgende regel om te kijken hoe de array van je reactions eruit ziet
// echo "<pre>".var_dump($getReactions)."</pre>";

if(!empty($_POST)){

    //dit is een voorbeeld array.  Deze waardes moeten erin staan.
    $postArray = [
        'name' => "Ieniminie",
        'email' => "ieniminie@sesamstraat.nl",
        'message' => "Geweldig dit"
    ];

    $setReaction = Reactions::setReaction($postArray);

    if(isset($setReaction['error']) && $setReaction['error'] != ''){
        prettyDump($setReaction['error']);
    }
    

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youtube remake</title>
</head>
<body>
    <iframe width="560" height="315" src="https://www.youtube.com/embed/MfuLcO8clh0?si=3ytUpY3wqN6kQfwB" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

    <h2>Hieronder komen reacties</h2>
    <p>Maak hier je eigen pagina van aan de hand van de opdracht</p>
</body>
</html>

<?php
$con->close();
?>