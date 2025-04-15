<?php
session_start();
require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

function getDayOfWeek($date)
{
    return date('l', strtotime($date));
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="robots" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="W3crm:Customer Relationship Management Admin Bootstrap 5 Template">
    <meta property="og:title" content="W3crm:Customer Relationship Management Admin Bootstrap 5 Template">
    <meta property="og:description" content="W3crm:Customer Relationship Management Admin Bootstrap 5 Template">
    <meta property="og:image" content="https://w3crm.dexignzone.com/xhtml/social-image.png">
    <meta name="format-detection" content="telephone=no">
    <title>Calendar</title>
    <link rel="shortcut icon" type="image/png" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link href="vendor/fullcalendar/css/main.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <title><?php echo $site; ?> - Manage-Calendar</title>
    <style>
        .fc-daygrid-day.highlight-day {
            background-color: red !important;
        }

        .fc-h-event,
        .fc-v-event {
            background: red;
            border-radius: .42rem;
        }

        .fc-daygrid-day.highlight-day .fc-daygrid-day-top {
            color: white !important;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <?php require_once 'include/header-link.php'; ?>
    <link href="assets/css/module-css/sidebar.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <?php
    if (empty($_SESSION['_id'])) {
        echo "<script>swal.fire({text: 'Kindly login to proceed!',type: 'error',icon: 'error'}).then(function(){window.location = 'login.php';});</script>";
    } else {
        $id = $_SESSION['_id'];
    ?>
        <div class="boxed_wrapper ltr">

            <?php require_once 'include/header.php';
            if (isset($_POST['submit'])) {

                $name = addslashes(trim($_POST['name']));
                $phone = addslashes(trim($_POST['phone']));
                $mail = addslashes(trim($_POST['mail']));
                $category = addslashes(trim($_POST['category']));
                $slot = addslashes(trim($_POST['slot']));
                $description = addslashes(trim($_POST['description']));
                $date = addslashes(trim($_POST['date']));
                $date_c = date('Y-m-d H:i:s');
                $status = "Pending";
                $selectedDay = getDayOfWeek($date);

                $slotResult = mysqli_query($conn, "SELECT slot_timing, no_of_people FROM appointment_master WHERE slot_timing = '$slot' AND weekday = '$selectedDay'");
                $slotInfo = mysqli_fetch_assoc($slotResult);
                $no_of_people = $slotInfo['no_of_people'];

                $res2 = mysqli_query($conn, "SELECT COUNT(appoint_id) AS slot_count FROM appointment 
                WHERE appoint_date = '$date' AND appoint_status = '$status' AND 
                appoint_slot_time = '$slot'");

                $res2 = mysqli_fetch_assoc($res2);

                if ($res2['slot_count'] >= $no_of_people) {
                    echo "<script>alert('You have exceeded the maximum limit of slots. You can only allocate up to 4 slots!');</script>";
                } else {
                    if (mysqli_query($conn, "INSERT INTO appointment (appoint_name, appoint_mobile, appoint_email, 
                    appoint_category, appoint_description, appoint_slot_time, appoint_status, appoint_created_by,
                    appoint_created_on, appoint_date) VALUES ('$name', '$phone', '$mail', '$category', 
                    '$description', '$slot', '$status', '$username', '$date_c', '$date')")) {
                        echo "<script>alert('Slot added successfully!');window.location = 'calendar-users.php';</script>";
                    } else {
                        echo "<script>alert('Unable to process your request!');</script>";
                    }
                }
            }
            ?>

            <section class="sidebar-page-container pt_10 pb_100 border-top">
                <div class="auto-container">
                    <div class="row clearfix">
                        <div class="col-lg-9 col-md-12 col-sm-12 content-side pl_60">
                            <div class="blog-standard-content">
                                <div class="news-block-three">
                                    <div class="inner-box">
                                    </div>
                                </div>
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div id="external-events"></div>
                                                    <div id="calendar" class="app-fullcalendar"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <?php require_once 'include/footer.php'; ?>

        </div>

        <div id="calendarModal" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form method="POST">
                    <div class="row">
                        <h3 class="col-5">Manage Slot</h3>
                        <h5 id="selectedDateDisplay" class="text-primary col-7 text-end"></h5>
                    </div>
                    <div id="slotdata2" class="border p-4">

                    </div>
                    <input type="hidden" id="selectedDate" name="date" />

                </form>
            </div>
        </div>

    <?php
    }
    require_once 'include/footer-js.php';
    ?>

    <script>
        function closeModal() {
            document.getElementById('eventModal').classList.remove('active');
        }

        document.getElementById('modalOverlay').addEventListener('click', closeModal);

        function openModal() {
            document.getElementById('eventModal').classList.add('active');
        }
    </script>

    <script src="vendor/global/global.min.js"></script>
    <script src="vendor/moment/moment.min.js"></script>

    <script src="vendor/fullcalendar/js/main.min.js"></script>
    <script src="js/plugins-init/fullcalendar-init2.js"></script>
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>

    <script src="js/custom.js"></script>
    <script src="js/deznav-init.js"></script>

</body>

</html>