<!-- Andy Estevez -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC INC Grade Project -->
<!-- Assignment Submission Confirmation Page -->

<?php
    session_start();

    include("config.php");
    include("functions.php");

    $user_data = check_student_login($conn);

    $assignmentTitle = urldecode($_GET["aT"]);
    $assignmentID = urldecode($_GET["aID"]);
    $classID = urldecode($_GET["cID"]);
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <title>BMCC Grades Assignment Submission Confirmation Page</title>
    </head>

    <body class="assignmentConfirmBody">
        <!-- Header / Navigation Bar -->
        <nav>
            <a href="https://www.bmcc.cuny.edu" target="_blank" onclick="return confirm('This will take you to the main BMCC page')">
                <img class="BMCCLogo" src="Elements\bmcc-logo-two-line-wide-WHITE.png" alt="BMCC Logo" height="50px">
            </a>
            <div class="NavButtonsContainer" id="console">
                <button type="button" class="navButton" onclick="location.href='studentClass.php?cID=<?php echo($classID) ?>'">Return</button>
            </div>
        </nav>

        <?php 
            // Class Info Banner
            $classQuery = "SELECT * 
                            FROM classes AS c
                            LEFT JOIN stutoclassmap as scMap
                            ON $user_data[studentID] = scMap.studentID
                            LEFT JOIN faculty AS f
                            ON c.facultyID = f.facultyID
                            WHERE $classID = c.classID";

            $classResult = mysqli_query($conn, $classQuery);
            $classInfo = mysqli_fetch_assoc($classResult);

            echo("
                <div class='classInfo'>
                    <p class='classesBlockHeader'><strong>Class</strong>: $classInfo[name] // <strong>Grade</strong>: $classInfo[grade] // <strong>Faculty</strong>: $classInfo[username] // <strong>Email</strong>: $classInfo[email]</p>
                </div>
            ");

            // Confirmation Form
            echo("
                <div class='confDiv'>
                    <p class='confHeader'>Turn In Assignment</p>
                    
                    <div class='confBody'>
                        <p class='confText'>Are you sure you want to turn in the following assignment:</p>
                        <p class='confText'><strong>Title</strong>: $assignmentTitle</p>
                        <p class='confText'>... For the following class:</p>
                        <p class='confText'><strong>Class</strong>: $classInfo[name]</p>
                        <p class='confText'>This CANNOT be undone.</p>
                        <div class='confButtonHolder'>
                            <button type='button' class='confButton' onclick='location.href=\"studentClass.php?cID=$classID\"'>Cancel</button>
                            <button type='button' class='confButton' onclick='location.href=\"submitAssignment.php?aID=$assignmentID&cID=$classID\"'>Confirm</button>
                        </div>
                    </div>
                </div>
            ");
        ?>
    </body>
</html>