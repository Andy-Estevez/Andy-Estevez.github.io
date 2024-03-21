<!-- Andy Estevez -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC INC Grade Project -->
<!-- Student Class Page -->

<?php
    session_start();

    include("config.php");
    include("functions.php");

    $user_data = check_student_login($conn);
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <title>BMCC Grades Student Class Viewer</title>
    </head>

    <body>
        <!-- Header / Navigation Bar -->
        <nav>
            <a href="https://www.bmcc.cuny.edu" target="_blank" onclick="return confirm('This will take you to the main BMCC page')">
                <img class="BMCCLogo" src="Elements\bmcc-logo-two-line-wide-WHITE.png" alt="BMCC Logo" height="50px">
            </a>
            <div class="NavButtonsContainer">
                <button type="button" class="navButton" onclick="location.href='studentConsole.php'">Classes</button>
                <button type="button" class="navButton" onclick="location.href='profile.php'">Profile</button>
                <button type="button" class="navButton" id="login" onclick="location.href='logout.php'">Log Out</button>
            </div>
        </nav>

        <?php
            $classID = $_GET["cID"];

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

            // Assignments
            $assignmentsQuery = "SELECT *
                                FROM stutoassignmentmap AS saMap
                                LEFT JOIN assignments AS a
                                ON saMap.assignmentID = a.assignmentID
                                WHERE saMap.studentID = $user_data[studentID] AND saMap.classID = $classID
                                ORDER BY dueDate";

            $assignmentsResult = mysqli_query($conn, $assignmentsQuery);

            if ($assignmentsResult && mysqli_num_rows($assignmentsResult) > 0) {
                while ($assignment = mysqli_fetch_assoc($assignmentsResult)) {
                    // Display Assignment Information
                    echo("
                        <div class='assignmentBlock'>
                            <h2 class='classBlockItemInfo'>$assignment[title]</h2>
                            <hr>

                            <h4 class='assignmentText'>Assignment Description:</h4>
                            <p class='assignmentText'>$assignment[description]</p>
                            <hr>

                            <p class='assignmentText'><strong>Due Date</strong>: $assignment[dueDate]</p>
                    ");

                    // Display Assignment Completion Status & Remaining Information
                    if ($assignment["completionStatus"] == 0) {
                        echo("
                                <p class='assignmentText'><strong>Status</strong>: Incomplete</p>
                                <p class='assignmentText'><strong>Grade</strong>: $assignment[grade]</p>
                                <hr>

                                <button type='button' class='assignmentButton' onclick='location.href=\"submitAssignment.php?aID=$assignment[assignmentID]&cID=$classID\"'>Mark As Complete</button>
                            </div>
                        ");
                    }
                    else if ($assignment["completionStatus"] == 1) {
                        echo("
                                <p class='assignmentText'><strong>Status</strong>: Pending</p>
                                <p class='assignmentText'><strong>Grade</strong>: $assignment[grade]</p>
                                <hr>

                                <button type='button' class='assignmentButtonDis' disabled'>Submitted</button>
                            </div>
                        ");
                    }
                    else if ($assignment["completionStatus"] == 2) {
                        echo("
                                <p class='assignmentText'><strong>Status</strong>: Complete</p>
                                <p class='assignmentText'><strong>Grade</strong>: $assignment[grade]</p>
                                <hr>

                                <button type='button' class='assignmentButtonDis' disabled'>Submitted</button>
                            </div>
                        ");
                    }
                    else {
                        die("ERROR: Invalid completion status.");
                    }
                }
            }
            else {
                echo("No assignments");
            }
        ?>
    </body>
</html>