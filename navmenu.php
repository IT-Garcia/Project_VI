<nav>
      <ul>
        <li><button id="nav_button" onclick="window.location.href='index.php';">
            <img src="icons/lift.png" alt="Home"></button>
        </li>
        <li><a href="about.php">About</a></li>
        <li><a href="https://github.com/users/IT-Garcia/projects/1" target="_blank">Project Plan & Status</a></li>
        <li><a href="bhlogbook.php">Brandon's Logbook</a></li>
        <li><a href="itglogbook.php">Isai's Logbook</a></li>
        <li><a href="charts.php">Statistics</a></li>
        <?php
        session_start();
          if(isset($_SESSION["loggedin"])){
            echo "<li><a href='elevator.php'>Elevator Interface</a></li>";
          }else{
            // echo "<li>User not logged in</li>";
          }
        ?>
      </ul>
</nav>


