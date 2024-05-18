<?php
session_start();
include '../connection/connection.php';

$conn = connection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$rows = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO pendingappointments (patient_PatientID, Client_Name, Services, Dentist, Day, TIme, Status, Message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('isssssss', $patientID, $Client_Name, $Services, $Dentist, $Day, $Time, $Status, $Message);
    
    $Time = isset($_POST["Time"]) ? $_POST["Time"] : '';
    $Time = date("h:i A", strtotime($Time));
    
    $patientID = isset($_SESSION["PatientID"]) ? $_SESSION["PatientID"] : '';
    $Client_Name = isset($_POST["Client_Name"]) ? $_POST["Client_Name"] : '';
    $Services = isset($_POST["services"]) ? $_POST["services"] : '';
    $Dentist = isset($_POST["dentist"]) ? $_POST["dentist"] : '';
    $Day = isset($_POST["Day"]) ? $_POST["Day"] : '';
    $Status = "Pending";
    $Message = isset($_POST["message"]) ? $_POST["message"] : '';
    
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

if(isset($_GET['cancel'])) {
    $cancelID = $_GET['cancel'];
    
    $Message = isset($_GET["message"]) ? $_GET["message"] : '';
    
    $updateStmt = $conn->prepare("UPDATE pendingappointments SET Status = 'Cancelled', Message = ? WHERE PendingID = ?");
    $updateStmt->bind_param("si", $Message, $cancelID);
    
    if($updateStmt->execute()) {
        header("Location: record.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}





if (isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: login.php");
    exit;
}

$patientID = isset($_SESSION["PatientID"]) ? $_SESSION["PatientID"] : '';
$result = $conn->query("SELECT PendingID, Client_Name, Services, Dentist, Day, Time, Status FROM pendingappointments WHERE patient_PatientID = $patientID");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url(estitik.jpg);
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: black;
        }

        .navbar-brand img {
            width: 100px;
            margin-right: 5px;
        }

        .navbar-brand {
            font-size: 20px;
            color: white !important;
        }

        .offcanvas-body {
            color: white;
        }

        .containerTable {
            margin-top: 300px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        h2 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }

        .table {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            color: white;
        }

        .table th,
        .table td {
            border: none;
            vertical-align: middle;
        }

        .table th {
            font-weight: bold;
          
        }

        .table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

   
       .nav-link{
            color: white;
       }

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="logooo-removebg-preview.png" alt="Logo" class="d-inline-block align-top">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvasLg" aria-controls="navbarOffcanvasLg" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon bg-white"></span>
        </button>
        <div class="offcanvas offcanvas-end bg-black" tabindex="-1" id="navbarOffcanvasLg" aria-labelledby="navbarOffcanvasLgLabel">
            <div class="offcanvas-header">
                <h1 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Infiniteeth</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0" style="margin-top: 30px;">
                    <li class="nav-item" style="margin-right: 65px;">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item" style="margin-right: 65px;">
                        <a class="nav-link" href="appointment.php?logout=true">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>


<div class="containerTable">
    <h2>Appointment Details</h2>
    <div class="row row-cols-1 row-cols-md-2 g-4">
    <?php foreach ($rows as $row): ?>
    <div class="col">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Appointment ID: <?php echo $row['PendingID']; ?></h5>
                <p class="card-text">Fullname: <?php echo $row['Client_Name']; ?></p>
                <p class="card-text">Services: <?php echo $row['Services']; ?></p>
                <p class="card-text">Day: <?php echo $row['Day']; ?></p>
                <p class="card-text">Time: <?php echo date("h:i A", strtotime($row['Time'])); ?></p>
                <p class="card-text">Status: <?php echo $row['Status']; ?></p>
                <div class="mb-3">
                            <label for="message_<?php echo $row['PendingID']; ?>" class="form-label">Message:</label>
                            <textarea class="form-control" id="message_<?php echo $row['PendingID']; ?>" name="message_<?php echo $row['PendingID']; ?>" rows="3"></textarea>
                            <input type="hidden" name="message_<?php echo $row['PendingID']; ?>" value="" id="message_<?php echo $row['PendingID']; ?>">

                        </div>
                        <?php if ($row['Status'] == 'Pending'): ?>
                            <button onclick="cancelAppointment(<?php echo $row['PendingID']; ?>)" class="btn btn-danger">Cancel Appointment</button>
                        <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script>
   function cancelAppointment(appointmentID) {
    var message = document.getElementById("message_" + appointmentID).value;
    if (message.trim() === "") {
        alert("Please leave a message before canceling the appointment.");
        return;
    }
    var confirmation = confirm("Are you sure you want to cancel this appointment?");
    if (confirmation) {
        window.location.href = "?cancel=" + appointmentID + "&message=" + encodeURIComponent(message);
    }
}

</script>
    </div>
</div>



<?php 
if (isset($_SESSION['PatientID'])) {
    $patientID = $_SESSION['PatientID'];
    echo "<p id='patientID'>Patient ID: $patientID</p>";
}
?>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    function confirmAppointment() {
        alert("Schedule for your appointment has been confirmed!");
        document.getElementById("appointmentForm").submit();
    }
</script>

</body>
</html>