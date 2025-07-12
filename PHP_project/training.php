<?php
// Enable error reporting for debugging during development
error_reporting(E_ALL); // Report all PHP errors
ini_set('display_errors', 1); // Display errors to browser
ini_set('display_startup_errors', 1);

session_start();
?>
<!DOCTYPE html>
<html lang='en-GB'>
<head>
    <title>Training Sessions Booking System</title>
    <style>
        :root {
            --color1: rgb(255, 227, 170);
            --color2: rgb(244, 189, 72);
            --color3: rgb(145, 104, 55);
            --color4: rgb(57, 57, 21);
            --gradient1: linear-gradient(60deg, rgb(236, 161, 57) 45%, rgb(236, 209, 172), rgb(239, 150, 27));
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: var(--gradient1);
            padding: 20px;
            min-height: 100vh;
        }

        h1, h2 {
            color: var(--color4);
            text-align: center;
            margin-bottom: 1em;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
            background-color: var(--color1);
            border: 2px solid var(--color4);
            table-layout:fixed;
        }

        th, td {
            border: 1px solid var(--color4);
            padding: 12px;
            text-align: left;
        }

        td { 
            overflow: hidden; 
            text-overflow: ellipsis; 
            word-wrap: break-word;
        }

        th {
            background-color: var(--color2);
            color: var(--color4);
        }

        tr:nth-child(even) {
            background-color: rgba(244, 189, 72, 0.3);
        }

        .error-box {
            background-color: #ffeeee;
            border: 2px solid #ffcccc;
            padding: 15px;
            margin: 20px 0;
            border-radius: 1em;
            color: #dc3545;
        }

        .success {
            color: #28a745;
            background-color: #eeffee;
            border: 2px solid #ccffcc;
            padding: 15px;
            margin: 20px 0;
            border-radius: 1em;
        }

        form {
            background-color: var(--color1);
            padding: 20px;
            border-radius: 1em;
            border: 2px solid var(--color4);
            margin: 20px 0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: var(--color4);
        }

        select, input {
            width: 100%;
            padding: 10px;
            border: 2px solid var(--color4);
            border-radius: 0.5em;
            background-color: white;
        }

        select:disabled {
            background-color: #e9ecef;
            color: #6c757d;
        }

        button {
            background-color: var(--color2);
            color: var(--color4);
            padding: 10px 20px;
            border: 2px solid var(--color4);
            border-radius: 1em;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: var(--color3);
            color: white;
        }

        /* Container for the main content */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 1em;
            border: 2px solid var(--color4);
        }
    </style>
</head>
<body>
<div class="container">
    <h1>University Training Sessions Booking</h1>

    <?php
    // Database connection parameters
    $db_hostname = "studdb.csc.liv.ac.uk"; // Database server
    $db_database = "sgefojci";
    $db_username = "sgefojci";
    $db_password = "Incorrect"; 
    $db_charset = "utf8mb4"; // Character encoding
    // Data Source Name for PDO connection
    $dsn = "mysql:host=$db_hostname;dbname=$db_database;charset=$db_charset";
    // Options for error handling and security
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    // Initialize errors array
    $errors = [];

    try {
        // Create PDO database connection
        $pdo = new PDO($dsn, $db_username, $db_password, $opt);
        // Get selected topic from POST data or set empty string
        $selected_topic = $_POST['topic'] ?? '';

        // Fetch total available slots
        $stmt = $pdo->query("SELECT SUM(available_places) AS total FROM sessions");
        $total = $stmt->fetch()['total'];

        if ($total == 0) {
            $errors[] = "All sessions are fully booked. No more places available.";
        } else {
            // Display available sessions table
            echo "<h2>Available Training Sessions</h2>";
            $stmt = $pdo->query("SELECT * FROM sessions WHERE available_places > 0 ORDER BY 
                                FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'), 
                                session_time");
            
            if ($stmt->rowCount() > 0) {
                // Start building the table
                echo "<table>";
                echo "<tr><th>Topic</th><th>Day</th><th>Time</th><th>Available Places</th></tr>";
               
                // Loop through each available session
                while ($row = $stmt->fetch()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['topic']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['day_of_week']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['session_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['available_places']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                $errors[] = "No sessions currently have available places.";
            }

            // Display booking form
            echo "<h2>Book a Session</h2>";
            echo "<form method='post' id='bookingForm'>";
            echo "<a id='form-anchor'></a>"; 

            echo "<div class='form-group'>";
            echo "<label>Training Topic</label>";
            echo "<select name='topic' id='topicSelect' onchange='this.form.submit()'>";
            echo "<option value=''>-- Select a Topic --</option>";
            $topics = $pdo->query("SELECT DISTINCT topic FROM sessions WHERE available_places > 0 ORDER BY topic")->fetchAll();
            foreach ($topics as $t) {
                $selected = ($t['topic'] == $selected_topic) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($t['topic']) . "' $selected>" .
                     htmlspecialchars($t['topic']) . "</option>";
            }
            echo "</select></div>";

            echo "<div class='form-group'>";
            echo "<label>Session Time</label>";
            echo "<select name='session_id' " . (empty($selected_topic) ? 'disabled' : '') . ">";
            if (empty($selected_topic)) {
                echo "<option value=''>-- Select a Topic First --</option>";
            } else {
                echo "<option value=''>-- Select a Session --</option>";
                $stmt = $pdo->prepare("SELECT session_id, day_of_week, session_time, available_places 
                                     FROM sessions 
                                     WHERE topic = ? AND available_places > 0 
                                     ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'), 
                                     session_time");
                $stmt->execute([$selected_topic]);
                foreach ($stmt as $s) {
                    $display = htmlspecialchars("{$s['day_of_week']}, {$s['session_time']} ({$s['available_places']} places)");
                    $selected = ($s['session_id'] == ($_POST['session_id'] ?? '')) ? 'selected' : '';
                    echo "<option value='{$s['session_id']}' $selected>$display</option>";
                }
            }
            echo "</select></div>";

            echo "<div class='form-group'>";
            echo "<label>Your Full Name</label>";
            echo "<input type='text' name='name' value='" . htmlspecialchars($_POST['name'] ?? '') . "' required>";
            echo "</div>";

            echo "<div class='form-group'>";
            echo "<label>Your Email Address</label>";
            echo "<input type='email' name='email' value='" . htmlspecialchars($_POST['email'] ?? '') . "' required>";
            echo "</div>";

            echo "<div class='form-group'>";
            echo "<button type='submit' name='book_submit'>Book Session</button>";
            echo "</div>";
            echo "</form>";
        }

        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_submit'])) {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $session_id = $_POST['session_id'] ?? '';

            // Name validation
            if (empty($name)) {
                $errors[] = "Please enter your name";
            } elseif (!preg_match("/^[A-Za-z' -]+$/", $name)) {
                $errors[] = "Name contains invalid characters";
            } elseif (preg_match("/[-']{2,}/", $name)) {
                $errors[] = "Name contains invalid sequences of hyphens or apostrophes";
            } elseif (!preg_match("/^[A-Za-z']/", $name)) {
                $errors[] = "Name must start with a letter or apostrophe";
            } elseif (preg_match("/[- ]$/", $name)) {
                $errors[] = "Name cannot end with a space or hyphen";
            }

            // Email validation
            if (empty($email)) {
                $errors[] = "Please enter your email address";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Please enter a valid email address";
            }

            // Session validation
            if (empty($session_id)) {
                $errors[] = "Please select a session";
            }

            if (empty($errors)) {
                // Begin transaction to handle concurrent bookings
                $pdo->beginTransaction();

                try {
                    // Check available places 
                    $stmt = $pdo->prepare(
                        "SELECT available_places FROM sessions 
                         WHERE session_id = :session_id 
                         FOR UPDATE"
                    );
                    $stmt->execute(['session_id' => $session_id]);
                    $available = $stmt->fetchColumn();

                    if ($available === false) {
                        throw new Exception("Session not found.");
                    }

                    if ($available > 0) {
                        // Create booking
                        $stmt = $pdo->prepare(
                            "INSERT INTO bookings (session_id, student_name, student_email)
                             VALUES (:session_id, :name, :email)"
                        );
                        $success = $stmt->execute([
                            'session_id' => $session_id,
                            'name' => $name,
                            'email' => $email
                        ]);

                        if ($success) {
                            // Update available places
                            $stmt = $pdo->prepare(
                                "UPDATE sessions SET available_places = available_places - 1 
                                 WHERE session_id = :session_id"
                            );
                            $stmt->execute(['session_id' => $session_id]);

                            $pdo->commit();
                            echo "<div class='success'><p>Booking successful! You are now registered for this session.</p></div>";

                            // Clear form values after successful submission
                            $_POST['name'] = '';
                            $_POST['email'] = '';
                            $_POST['topic'] = '';
                            $_POST['session_id'] = '';
                        } else {
                            throw new Exception("Booking failed. Please try again.");
                        }
                    } else {
                        $pdo->rollBack();
                        $errors[] = "No places available for the selected session. Please choose another session.";
                    }
                } catch (Exception $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $errors[] = "Error processing booking: " . $e->getMessage();
                }
            }
        }

        // Display errors
        if (!empty($errors)) {
            echo "<div class='error-box'><p>Please correct the following:</p><ul>";
            foreach ($errors as $error) {
                echo "<li>" . htmlspecialchars($error) . "</li>";
            }
            echo "</ul></div>";
        }

        // Display all bookings
        echo "<h2>Current Bookings</h2>";
        $stmt = $pdo->query("SELECT s.topic, s.day_of_week, s.session_time, b.student_name, b.student_email 
                             FROM bookings b 
                             JOIN sessions s ON b.session_id = s.session_id 
                             ORDER BY FIELD(s.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'), 
                             s.session_time, s.topic");

        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<tr><th>Topic</th><th>Day</th><th>Time</th><th>Name</th><th>Email</th></tr>";
            foreach ($stmt as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['topic']) . "</td>";
                echo "<td>" . htmlspecialchars($row['day_of_week']) . "</td>";
                echo "<td>" . htmlspecialchars($row['session_time']) . "</td>";
                echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['student_email']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No bookings have been made yet.</p>";
        }

        $pdo = null;
    } catch (PDOException $e) {
        $errors[] = "Database error: " . $e->getMessage();
    }
    ?>
</div>
</body>
</html>