<?php

require_once("includes.php");
//session_destroy();

require_once("classes/CustomException.class.php");
?>
<!DOCTYPE html>
<html>
<title>WORLD CUP 2018 GUESS THE SCORES</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/styles.css">
<body>
    <!-- Navbar (sit on top) -->
    <div class="w3-top">
        <ul class="w3-navbar" id="myNavbar">
<?php

if (!$usercookie) {
    echo<<<EOHTML
    <li onclick="document.getElementById('id01').style.display='block'"><a href="#" class="w3-padding-large">LOGIN/REGISTER</a></li>
EOHTML;
} else {
  require_once("scopeConnect.php");
}

?>
        </ul>
    </div>
<!-- First Parallax Image with Logo Text -->
<div class="bgimg-1 w3-opacity w3-display-container">
  <div class="w3-display-middle">
    <span class="w3-center w3-padding-xlarge w3-black w3-xlarge w3-wide w3-animate-opacity">WORLD CUP GUESS THE SCORES</span>
  </div>
</div>

<!-- Container (About Section) -->
<div class="w3-content w3-container w3-padding-64" id="about">
  <h3 class="w3-center">TODAYS GAMES</h3>
  <p class="w3-center">
  
    <?php
    require_once("classes/TodaysGames.class.php");
    require_once("classes/Database.class.php");
    require_once("classes/DateFormat.class.php");

    $games = new TodaysGames();
    $games->setDbObject(new Database);
    $games->setDate(date("Y-m-d"));
    $games->getTodaysGames();
    echo $games->returnTodaysGamesHtml();
    ?>
      
  </p>
</div>

<!-- Second Parallax Image with Portfolio Text -->
<div class="bgimg-2 w3-display-container">
  <div class="w3-display-middle">
    <span class="w3-xxlarge w3-text-light-grey w3-wide">CURRENT LEADERBOARD</span>
  </div>
</div>

<!-- Container (Portfolio Section) -->
<div class="w3-content w3-container w3-padding-64">
  <h3 class="w3-center">LEADERBOARD</h3>
    <?php

    require_once("classes/Database.class.php");
    require_once("classes/Leaderboard.class.php");

    try {
        $leaderboard = new Leaderboard();
        $leaderboard->setDbObject(new Database());
        $leaderboard->generateLeaderboard();
        echo $leaderboard->displayLeaderboardAsTable();
    }
    catch (CustomException $e) {
        echo $e->errorMessage();
    }   

    ?>
</div>

<!-- Third Parallax Image with Portfolio Text -->
<div class="bgimg-3 w3-display-container">
  <div class="w3-display-middle">
     <span class="w3-xxlarge w3-text-light-grey w3-wide">YOUR SCORES</span>
  </div>
</div>

    
<!-- Container (Contact Section) -->
<div class="w3-content w3-container w3-padding-64">
  <h3 class="w3-center">Your Scores</h3>
  <p class="w3-center" id='scoreEntry'>
    <form id='scores' method='POST'>
    <?php

    require_once("classes/UserScores.class.php");
    require_once("classes/User.class.php");
    require_once("classes/Database.class.php");
    require_once("classes/DateFormat.class.php");

    if ($usercookie) {
        $user = new User();
        $user->setUser($usercookie);

        $us = new UserScores();
        $us->setDbObject(new Database());
        $us->setUserObject($user);
        $us->setDateObject(new DateFormat());
        $us->getUserScores();
        echo $us->generateHtmlInput();
        echo $us->generateHtmlInputButton();
    } else {
        echo "Please login or create an account.";   
    }

    ?>
    </form>
    
    <div id='debug'></div>
  </p>
</div>
    
<div id="id01" class="w3-modal">
  <div class="w3-modal-content">
    <header class="w3-container w3-dark-grey"> 
      <span onclick="document.getElementById('id01').style.display='none'" 
      class="w3-closebtn">&times;</span>
      <h2>Please Login or Register</h2>
    </header>
    <div class="w3-container">
    <?php
        
    require_once("classes/User.class.php");
    
    $user = new User();
    echo $user->getLoginRegisterScreen();
        
    ?>
    </div>
  </div>
</div>   
    
<div id="id02" class="w3-modal">
  <div class="w3-modal-content">
    <header class="w3-container w3-dark-grey"> 
      <span onclick="document.getElementById('id02').style.display='none'" 
      class="w3-closebtn">&times;</span>
      <h2 id='modalHeader'></h2>
    </header>
    <div class="w3-container" id='matchInfoDiv'>
    </div>
  </div>
</div>     
 
<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/scripts.js"></script>
<script src='/AdminLTE-2.3.0/plugins/datatables/jquery.dataTables.min.js'></script>
<script src='/AdminLTE-2.3.0/plugins/datatables/dataTables.bootstrap.min.js'></script>
<script src='/AdminLTE-2.3.0/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js'></script>

</body>
</html>
