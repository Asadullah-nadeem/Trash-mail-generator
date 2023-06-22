<!DOCTYPE html>
<html>
<head>
    <title>Trash Mail Generator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Trash Mail Generator</h1>
    <div class="mt-4">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="emailInput" class="form-label">Enter Your Email</label>
                <input type="email" class="form-control" id="emailInput" name="emailInput" required>
            </div>
            <div class="mb-3">
                <label for="timerSelect" class="form-label">Select Timer</label>
                <select class="form-select" id="timerSelect" name="timerSelect">
                    <option value="5">5 minutes</option>
                    <option value="10">10 minutes</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Generate Email</button>
        </form>

        <?php
        // Establish database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "a";

        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['emailInput'];
            $timer = $_POST['timerSelect'];

            // Insert email into database
            $sql = "INSERT INTO emails (email, created_at, expiration_time) 
                    VALUES ('$email', NOW(), DATE_ADD(NOW(), INTERVAL $timer MINUTE))";

            if (mysqli_query($conn, $sql)) {
                echo "<div class='alert alert-success' role='alert'>
                        Email generated successfully: $email. It will expire in $timer minutes.
                      </div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>
                        Error: " . $sql . "<br>" . mysqli_error($conn) .
                    "</div>";
            }
        }

        // Retrieve emails from the database
        $selectSql = "SELECT * FROM emails ORDER BY created_at DESC";
        $result = mysqli_query($conn, $selectSql);

        if (mysqli_num_rows($result) > 0) {
            echo "<h3>Generated Emails:</h3>";
            echo "<ul>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li>" . $row['email'] . " (Created at: " . $row['created_at'] . ", Expires at: " . $row['expiration_time'] . ")</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No emails generated yet.</p>";
        }

        // Close database connection
        mysqli_close($conn);
        ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
