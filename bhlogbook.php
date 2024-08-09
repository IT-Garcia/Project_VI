<!DOCTYPE html>
<html lang="en">
    <?php
        include 'head_info.php';
    ?>
    <title>Brandon's Logbook</title>
<body>
    
    <?php
      include 'navmenu.php';
    ?>
    
    <section>
        <div class="text">
            <h1>Brandon Hauck - Weekly Log Book </h1>
            <img class="portraits" src="./images/Brandon.png" alt ="Image of Brandon" title="Brandon"/>
            <h2> Week1</h2>
            <ul>
                <li>Setup team and Github repo with Isai for Project VI</li>
            </ul>
            <h2> Week2</h2>
            <ul>
                <li>Confirmed Communication between client and server</li>
                <li>Confirmed functionality of elevator system by sending multiple floor commands</li>
                <li>Created communication test plan document</li>
                <li>Created initial concept for GUI design</li>
                <li>Configured Raspberry-Pi to pull directly from project repository</li>
                <li>Setup Raspberry Pi as server</li>
                <li>Created basic project web pages and linked project plan on GitHub</li>
                <li>Updated project status report on GitHub repository</li>
            </ul>  
            <h2> Week3</h2>
            <ul>
                <li>Completed wiring diagram with illustrations for connections on all 4 elevator nodes</li>
                <li>Configured CAN messaging protocol with filters on STM32</li>
                <li>Created a test plan to confirm software and hardware functionality of elevator call system</li>
                <li>Wired the STM32 boards with push buttons and LEDs for each elevator node</li>
                <li>Tested and confirmed functionality of elevator system with newly integrated pushbuttons and LEDs</li>
                <li>Updated web server with basic styling in CSS</li>
            </ul>
            <h2> Week4</h2>
            <ul>
                <li>Corrected Elevator Push Button LED Wiring For Complete Functionality</li>
                <li>Corrected Software Logic For Floor LED Illumination </li>
                <li>Flashed all STM32 boards in elevator network with updated software</li>
                <li>Verified and Validated all LED illumination behaves as intended</li>
            </ul>      
            <h2> Week5</h2>
            <ul>
                <li>Completed all test plan documentation for Phase 1 of project</li>
                <li>Created initial technical document for finite state machine</li>
                <li>Updated all technical documents and added relevant Phase 1 files onto github repo</li>
            </ul>
            <h2> Week6</h2>
            <ul>
                <li>Conceptualized and completed a technical document describing our projects additional feature</li>
                <li>Assisted Isai with updating HTML documents for project implementation</li>
            </ul>
            <h2> Week 7</h2>
            <ul>
                <li>Developed authentication, request access, and logout php files which can read and write from a JSON file (to be implemented)</li>
                <li>Created Unified Model Language diagram which describes classes, attributes, and methods which will be implemented on the backend</li>
            </ul>
            <h2> Week 9</h2>
            <ul>
                <li>Revised UML Class diagram</li>
                <li>Developed back end features such as update functionality using transactions to prevent database corruption</li>
            </ul>
            <h2> Week 10</h2>
            <ul>
                <li>Created and tested database Create, Read, Update, Delete functionality on members page </li>
                <li>Created and tested skeleton code for web based elevator floor request buttons</li>
            </ul>     
            <h2> Week 11</h2>
            <ul>
                <li>Implemented logging of CAN communiction to database through local floor requests and recognition</li>
                <li>Implemented functions on Web Server and Raspberry-Pi which can insert and pull most recent entry in database</li>
                <li>Implemented live status reporting on elevator call button page</li>  
                <li>Implemented elevator network log history</li>
                <li>Implemented active button illumination on web server which responds to remote or local floor requests</li>                     
            </ul>
            <h2> Week 12</h2>
            <ul>
                <li>Implemented floor audio which announces the floor upon arrival</li>
                <li>Implemented sabbath mode where elevator automatically performs an elevator call sequence</li>
                <li>Integrated all functionality from week 10 to 12 onto the Raspberry-Pi for complete web and server functionality</li> 
                <li>Created and completed test plan document for phase 2</li>                                 
            </ul>   
    </section>

    <?php
      include 'footer.php';
    ?>
</body>
</html>
