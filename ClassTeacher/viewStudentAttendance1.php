<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

function calculatePercentage($totalRecords, $presentRecords) {
    if ($totalRecords === 0) {
        return "N/A"; // Avoid division by zero
    }
    return round(($presentRecords / $totalRecords) * 100, 2);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/logo/attnlg.jpg" rel="icon">
    <title>Dashboard</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">

    <style>
        .rating-circle {
            width: 30px;
            height: 30px;
            border: 2px solid #000;
            background: conic-gradient(
                #00FF00 0% 0%, /* Present */
                #00FF00 0% 100%, /* Present */
                #FF0000 0% 0%, /* Absent */
                #FF0000 0% 100% /* Absent */
            );
            border-radius: 50%;
        }
    </style>

    <script>
        function calculatePercentages() {
            var table = document.getElementById("attendanceTable");
            var rows = table.getElementsByTagName("tr");

            for (var i = 1; i < rows.length; i++) { // Start from 1 to skip the table header row
                var row = rows[i];
                var statusCell = row.cells[8]; // Assuming the "Status" cell is at index 8
                var totalCell = row.cells[9]; // Assuming the "Total" cell is at index 9

                var status = statusCell.innerHTML.trim();
                var total = parseInt(totalCell.innerHTML.trim());

                if (status === "Present") {
                    var presenceCell = row.insertCell(10); // Add a new cell for presence percentage
                    presenceCell.innerHTML = "<div class='rating-circle' style='background: conic-gradient(#00FF00 0% " + calculatePercentage(total, 1) + "%, #FF0000 " + calculatePercentage(total, 1) + "% 100%)'></div>";
                } else if (status === "Absent") {
                    var absenceCell = row.insertCell(10); // Add a new cell for absence percentage
                    absenceCell.innerHTML = "<div class='rating-circle' style='background: conic-gradient(#00FF00 0% 0%, #FF0000 0% " + calculatePercentage(total, 0) + "% 100%)'></div>";
                }
            }
        }

        window.onload = calculatePercentages;
    </script>
</head>
<body id="page-top">
    <!-- Your existing HTML body ... -->
    <div id="wrapper">
    <!-- Sidebar -->
      <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
       <?php include "Includes/topbar.php";?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">View Student Attendance</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item text-light"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Student Attendance</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-success">View Student Attendance</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Select Student<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry= "SELECT * FROM tblstudents where classId = '$_SESSION[classId]' and classArmId = '$_SESSION[classArmId]' ORDER BY firstName ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;		
                        if ($num > 0){
                          echo ' <select required name="admissionNumber" class="form-control mb-3">';
                          echo'<option value="">--Select Student--</option>';
                          while ($rows = $result->fetch_assoc()){
                          echo'<option value="'.$rows['admissionNumber'].'" >'.$rows['firstName'].' '.$rows['lastName'].'</option>';
                              }
                                  echo '</select>';
                              }
                            ?>  
                        </div>
                        <div class="col-xl-6">
                        <label class="form-control-label">Type<span class="text-danger ml-2">*</span></label>
                          <select required name="type" onchange="typeDropDown(this.value)" class="form-control mb-3">
                          <option value="">--Select--</option>
                          <option value="1" >All</option>
                          <option value="2" >By Single Date</option>
                          <option value="3" >By Date Range</option>
                        </select>
                        </div>
                    </div>
                      <?php
                        echo"<div id='txtHint'></div>";
                      ?>
                    <!-- <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Select Student<span class="text-danger ml-2">*</span></label>
                        
                        </div>
                        <div class="col-xl-6">
                        <label class="form-control-label">Type<span class="text-danger ml-2">*</span></label>
                        
                        </div>
                    </div> -->
                    <button type="submit" name="view" class="btn bg-gradient-success text-light">View Attendance</button>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
                 <div class="row">
              <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-success">Class Attendance</h6>
                </div>

    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Other Name</th>
                <th>Admission No</th>
                <th>Class</th>
                <th>Class Arm</th>
                <th>Session</th>
                <th>Term</th>
                <th>Status</th>
                <th>Attendance Rating</th> <!-- New cell for attendance rating -->
                <th>Date</th>
            </tr>
        </thead>
        <tbody id="attendanceTable"> <!-- Add an ID to the table for JavaScript manipulation -->

            <?php
            if (isset($_POST['view'])) {
                $admissionNumber = $_POST['admissionNumber'];
                $type = $_POST['type'];

                $query = "";

                if ($type == "1") { // All Attendance
                    $query = "SELECT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblclass.className,
                            tblclassarms.classArmName, tblsessionterm.sessionName, tblsessionterm.termId, tblterm.termName,
                            tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber
                            FROM tblattendance
                            INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
                            INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
                            INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
                            INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
                            INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
                            WHERE tblattendance.admissionNo = '$admissionNumber' AND tblattendance.classId = '$_SESSION[classId]' AND tblattendance.classArmId = '$_SESSION[classArmId]'";
                }

                $rs = $conn->query($query);
                $num = $rs->num_rows;
                $sn = 0;
                $presenceCount = 0;
                $totalRecords = 0;

                if ($num > 0) {
                    while ($rows = $rs->fetch_assoc()) {
                        $totalRecords++;
                        if ($rows['status'] == '1') {
                            $status = "Present";
                            $presenceCount++;
                        } else {
                            $status = "Absent";
                        }

                        $presencePercentage = calculatePercentage($totalRecords, $presenceCount);
                        $absencePercentage = calculatePercentage($totalRecords, $totalRecords - $presenceCount);
                        $sn = $sn + 1;

                        echo "
                            <tr>
                                <td>" . $sn . "</td>
                                <td>" . $rows['firstName'] . "</td>
                                <td>" . $rows['lastName'] . "</td>
                                <td>" . $rows['otherName'] . "</td>
                                <td>" . $rows['admissionNumber'] . "</td>
                                <td>" . $rows['className'] . "</td>
                                <td>" . $rows['classArmName'] . "</td>
                                <td>" . $rows['sessionName'] . "</td>
                                <td>" . $rows['termName'] . "</td>
                                <td>" . $status . "</td>
                                <td><div class='rating-circle' style='background: conic-gradient(" . ($status === 'Present' ? '#00FF00 0% 100%, #FF0000 0% 0%' : '#00FF00 0% 0%, #FF0000 0% 100%') . ")'></div></td>
                                <td>" . $rows['dateTimeTaken'] . "</td>
                            </tr>";
                    }
                }
            }
            ?>
        </tbody>
    </table>
    </div>
              </div>
            </div>
            </div>
          </div>
          <!--Row-->

          <!-- Documentation Link -->
          <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
       <?php include "Includes/footer.php";?>
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
   <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>
    <!-- Your existing JavaScript imports and footer section ... -->

