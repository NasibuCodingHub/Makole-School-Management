
<?php include("../assets/noSessionRedirect.php"); ?>

<?php include("./verifyRoleRedirect.php"); ?>

<?php 

    // session_start();
 include('database.php');
 error_reporting(0);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="shortcut icon" href="./images/1.jpg">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    

    <style>
        body{overflow: hidden;}
        header{position: relative;}
        .exam{
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 80vh;
            width: 80%;
            margin: auto;
        }
        .btn {
  background-color: DodgerBlue;
  border: none;
  color: white;
  padding: 10px 20px;
  cursor: pointer;
  font-size: 10px;
  border-radius: 3px;
  text-decoration: none;
}





body {
    -ms-overflow-style: none;  /* Internet Explorer 10+ */
    scrollbar-width: none;  /* Firefox */
}
body::-webkit-scrollbar { 
    display: none;  /* Safari and Chrome */
}

/* Darker background on mouse-over */
.btn:hover {
  background-color: RoyalBlue;
}

.btn a{
    color: white;
    font-size: 20px;
}
#myInput {
  background-image: url('search.svg'); /* Add a search icon to input */
  background-position: 5px 2px; /* Position the search icon */
  background-repeat: no-repeat; /* Do not repeat the icon image */
  width: 80%; /* Full-width */
  font-size: 16px; /* Increase font-size */
  padding: 12px 20px 12px 40px; /* Add some padding */
  border: 1px solid #ddd; /* Add a grey border */
  margin-bottom: 12px; /* Add some space below the input */
  margin-top: 30px;
  margin-left: 150px;
}

#myTable {
  border-collapse: collapse; /* Collapse borders */
  width: 80%; /* Full-width */
  border: 1px solid #ddd; /* Add a grey border */
  font-size: 18px; /* Increase font-size */
  border: #bdc3c7 1px solid;
  margin-left: 150px;
}

#myTable th, #myTable td {
  text-align: left; /* Left-align text */
  padding: 10px; /* Add padding */
}

#myTable tr {
  /* Add a bottom border to all table rows */
  border-bottom: 1px solid #ddd;
}
#myTable th, #myTable td.theme-toggler {
  text-align: left; /* Left-align text */
  padding: 10px; /* Add padding */
  background-color: black;
  color: white;
}

#myTable tr.theme-toggler {
  /* Add a bottom border to all table rows */
  border-bottom: 1px solid #ddd;
  background-color: #363949;
}

#myTable tr.header, #myTable tr:hover{

 
 
}
 #myTable tr:hover{
    background-color: #779ca6;
 }
@media only screen and (max-width: 768px){
    #myTable {
        width: 100%;
        margin: 0%;
        font-size: 12px;
        flex: auto;
        position: absolute;
    }
    #myInput{
        width: 100%;
        margin: 0%;
    }
}

        
    </style>
</head>
<body style="overflow-y: scroll;">
    <header>
        <div class="logo">
            <img src="./images/1.jpg" alt="">
            <h2>M<span class="danger">P</span>S</h2>
        </div>
        <div class="navbar">
            <a href="index.php">
                <span class="material-icons-sharp">home</span>
                <h3>Home</h3>
            </a>
            <a href="timetable.php" onclick="timeTableAll()">
                <span class="material-icons-sharp">today</span>
                <h3>Time Table</h3>
            </a> 
            <a href="exam.php">
                <span class="material-icons-sharp">grid_view</span>
                <h3>Examination</h3>
            </a>
            <a href="workspace.php" class="active">
                <span class="material-icons-sharp">description</span>
                <h3>Workspace</h3>
            </a>
            <a href="password.php">
                <span class="material-icons-sharp">password</span>
                <h3>Change Password</h3>
            </a>
            <a href="logout.php">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div id="profile-btn" style="display: none;">
            <span class="material-icons-sharp">person</span>
        </div>
        <div class="theme-toggler">
            <span class="material-icons-sharp active">light_mode</span>
            <span class="material-icons-sharp">dark_mode</span>
        </div>
    </header>

    <div class="alert-container">
        <div id="alertBox" class="alert-box"></div>
    </div>

   <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for Subject.">
   <div class="scrollableBox">
    <table id="myTable">
        <tr class="header">
            <th>Subject</th>
            <th>Title</th>
            <th>Downloads</th>
            <th>Date</th>
            <th>Upload WORK</th>
        </tr>

        <?php
        $id = $_SESSION['uid'];

        $sql  = "SELECT * FROM students WHERE id='$id'";
        $result2 = mysqli_query($conn, $sql);
        $row = $result2->fetch_assoc();
        $class = $row['class'];

        $query = "SELECT * FROM notes WHERE class='$class' ORDER BY s_no DESC";
        $result = mysqli_query($conn, $query);
        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {

                $dateDB = $rows['timestamp'];
                $formattedDate = date("d-m-Y", strtotime($dateDB));

                echo "<tr>";
                echo "<td>" . $rows['subject'] . "</td>";
                echo "<td>" . $rows['title'] . "</td>";
                echo "<td><button class='btn'><a href='../notesUploads/" . $rows['file'] . "' download='" . $rows['file'] . "'>Download</a></button></td>";
                echo "<td>" . $formattedDate . "</td>";
                echo "<td>
                        <form action='upload_homework.php' method='POST' enctype='multipart/form-data' class='upload-form'>
                        <input type='hidden' name='note_id' value='" . $rows['subject'] . "'>
                        <input type='file' name='homework_file' id='fileInput_" . $rows['s_no'] . "' class='file-input' required onchange='updateFileName(this)'>
                        <label for='fileInput_" . $rows['s_no'] . "' class='file-label'>Choose File</label>
                        <button type='submit' name='upload' class='upload-btn'>Upload</button>
                        <span id='fileName_" . $rows['s_no'] . "' class='file-name'>   No file chosen</span>
                        </form>
                      </td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
</div>

<style>
    /* Styling the form elements */
    .upload-form {
        display: flex;
        align-items: center;
    }

    /* Hide the default file input */
    .file-input {
        display: none;
    }

    /* Style the custom file input label */
    .file-label {
        background-color: #007bff;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 10px;
        transition: background-color 0.3s ease;
    }

    .file-label:hover {
        background-color: #0056b3;
    }

    /* Style the upload button */
    .upload-btn {
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .upload-btn:hover {
        background-color: #218838;
    }
</style>

<br><br><br>
    <!-- <script src="timeTable.js"></script> -->
    <script src="app.js"></script>

    <script>

        document.addEventListener('DOMContentLoaded', (event) => {
            <?php if (isset($_SESSION['alert'])): ?>
                swal({
                    title: "<?php echo ucfirst($_SESSION['alert_type']); ?>",
                    text: "<?php echo $_SESSION['alert']; ?>",
                    icon: "<?php echo $_SESSION['alert_type']; ?>",
                    button: 'OK'
                }).then(() => {
                    <?php
                    // Unset the alert session variables after showing the alert
                    unset($_SESSION['alert']);
                    unset($_SESSION['alert_type']);
                    ?>
                });
            <?php endif; ?>
        });



        function updateFileName(input) {
    var fileName = input.files[0].name;
    var fileDisplay = document.getElementById('fileName_' + input.id.split('_')[1]);
    fileDisplay.textContent = fileName;
}
    </script>
   

</body>
</html>