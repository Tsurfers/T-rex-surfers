
<?php
    try
    {
        $db = new PDO('mysql:host=sql11.freesqldatabase.com:3306;dbname=sql11481224;charset=utf8', 'sql11481224', 'as7hUUwD7X');
        $db->query("SET NAMES 'utf8'");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
    
    function entrerScore($db, $pseudo, $score){
        $requete=$db->prepare("select count(*) as nb from pseudos where pseudo='$pseudo'");
        $requete->execute();
        $tab=$requete->fetch(PDO::FETCH_ASSOC);
        
        if ($tab['nb']==0){
            $requete=$db->prepare("insert into pseudos values ('$pseudo', '$score')");
            afficherScore($pseudo, $score);
        }
        else {
            //check if score is better
            $pre_requete=$db->prepare("select score from pseudos where pseudo='$pseudo'");
            $pre_requete->execute();
            $tab=$pre_requete->fetch(PDO::FETCH_ASSOC);

            if($tab['score'] < $score){
                $requete=$db->prepare("update pseudos set score='$score' where pseudo='$pseudo'");
                afficherScore($pseudo, $score);
            }
        }
        $requete->execute();
    }
    
    function afficherScore($pseudo, $score){
        echo "<div class='php'>".$pseudo.", your score  ".$score." has been saved.</div>";
    }
    
    function afficherTableauDesScores($db){
        $requete=$db->prepare("select * from pseudos order by score desc limit 10");
        $requete->execute();
        $tab=$requete->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table id='score-table'><th>Rank</th><th>Pseudo</th><th>Score</th>";
        $i = 1;
        foreach($tab as $value){
            if($i == 1){
                echo "<tr><td id='crown'></td><td>".$value['pseudo']."</td><td>".$value['score']."</td></tr>";
            }
            else{
                echo "<tr><td class='rank'>".$i."</td><td>".$value['pseudo']."</td><td>".$value['score']."</td></tr>";
            }
            $i++;
        }
        echo "</table>";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css.css">
    </head>
    <body>
        <div id="content">
            <div id="leftPart">
                <div id="subLeftPart">
                    <h1>T-rex Surfers</h1>
                    <?php
                        afficherTableauDesScores($db);
                    ?>
                </div>
            </div>
            <div id="rightPart">
                <div id="subRightPart">
                    <form method="post" id="login-form">
                        <label for="pseudo">Enter your pseudo: </label>   
                        <input type="text" name="pseudo" id="pseudo" required>
                        <input type="text" name="score" id="score" style="display: none;">
                        <input type="submit" id="form-submit">
                    </form>
                    <?php
                        if(isset($_POST["pseudo"])){
                            $pseudo = str_replace(' ', '', $_POST["pseudo"]);
                            if($pseudo != null && $pseudo != "" && strlen($pseudo) <= 10){
                                echo "<div class='php'>You are logged in as : <span id='playerPseudo'>$pseudo</span>. <a href=''>Sign out.</a></div>";
                                if(isset($_POST["score"])){
                                    $score = str_replace(' ', '', $_POST["score"]);
                                    if($score != null && $score != ""){
                                        entrerScore($db, $pseudo, $score);
                                    }
                                }
                            }
                            else{
                                echo "<script>alert(\"$pseudo invalid. The nickname must be a maximum of 10 fucking characters and a minimum of 1 character, spaces not included..\")</script>";
                            }
                        }  
                    ?>
                    <div id="game">
                        <div id="score-game">0</div>
                        <div id="dino"></div>
                        <button id="play-button" onclick="LaunchGame()"></button>
                    </div>
                </div>
            </div>
        </div>
        <script src="script.js"></script>
    </body>
</html>

















