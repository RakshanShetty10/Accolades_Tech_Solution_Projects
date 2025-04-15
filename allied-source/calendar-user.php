<?php
session_start();
require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

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
    <title><?php echo $site_full; ?> - Calendar</title>
    <link rel="shortcut icon" type="image/png" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link href="vendor/fullcalendar/css/main.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="assets/css/module-css/sidebar.css" rel="stylesheet">
    <?php require_once 'include/header-link.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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


        #slotDropdown {
            display: block !important;
            visibility: visible !important;
            position: static !important;
            opacity: 1 !important;
            width: auto !important;
            height: auto !important;
            z-index: 1000;
            background-color: white;
            border: 1px solid #ccc;
            padding: 5px;
        }

        #slotDropdown option {
            display: block !important;
            visibility: visible !important;
            color: #000;
            background-color: white;
        }

        select[name="category"] {
            display: block !important;
            visibility: visible !important;
            position: static !important;
            opacity: 1 !important;
            width: 100% !important;
            height: auto !important;
            z-index: 1000;
            background-color: white;
            border: 1px solid #ccc;
            padding: 5px;
            font-size: 16px;
            color: #000;
        }

        select[name="category"] option {
            display: block !important;
            visibility: visible !important;
            color: #000;
            background-color: white;
        }
    </style>
</head>

<body data-typography="poppins" data-theme-version="light" data-layout="vertical" data-nav-headerbg="black" data-headerbg="color_1">

    <?php
    if (empty($_SESSION['_id'])) {
        echo "<script>swal.fire({text: 'Kindly login to proceed!',type: 'error',icon: 'error'}).then(function(){window.location = 'login.php';});</script>";
    } else {
        $id = $_SESSION['_id'];
    ?>
        <div class="boxed_wrapper ltr">

            <?php require_once 'include/header.php';
            $practitioner_sql = "Select * from practitioner where practitioner_id = '$id'";
            $practitioner_result = mysqli_query($conn, $practitioner_sql);
            $practitioner_row = mysqli_fetch_assoc($practitioner_result);

            if (empty($practitioner_row['practitioner_change_of_name'])) {

                $practitioner_name = $practitioner_row['practitioner_title'] . ' ' . $practitioner_row['practitioner_name'];
            } else {
                $practitioner_name = $practitioner_row['practitioner_title'] . ' ' . $practitioner_row['practitioner_change_of_name'];
            }

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

                $timestamp = strtotime($date);
                $selectedDay = date('l', $timestamp); // Get the day of the week

                $slotResult = mysqli_query($conn, "SELECT slot_timing, no_of_people FROM appointment_master WHERE weekday = '$selectedDay'");
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
                    '$description', '$slot', '$status', '$practitioner_name', '$date_c', '$date')")) {

                        echo "<script>Swal.fire({text: 'Slot added successfully!', icon: 'success'}).then(function(){window.location = 'calendar-user.php';});</script>";
                    } else {
                        echo "<script>Swal.fire({text: 'Unable to process your request!', icon: 'error'});</script>";
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
                                        <div class="lower-content">
                                            <!-- <div class="widget-title mb_10">
                                                <li><a href="calendar-booked.php" style="display: inline-block; padding: 10px 20px; background-color: #2c2c2c; color: white; border-radius: 5px; text-decoration: none;">Booked Slot</a></li>
                                            </div> -->
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
                                            <div id="modalOverlay" class="modal" style="display:none;"></div>
                                            <div id="calendarModal" class="modal" style="display:none;">
                                                <div class="modal-content">
                                                    <span class="close">&times;</span>
                                                    <form method="POST">
                                                        <div class="row">
                                                            <h3 class="col-5">Manage Slot</h3>
                                                            <h5 id="selectedDateDisplay" class="text-primary col-7 text-end"></h5>
                                                        </div>
                                                        <div id="slotdata" class=""></div>
                                                        <div class="col-xl-12 mb-3">
                                                            <label class="form-label">Slot Timing<span class="text-danger">*</span></label>
                                                            <select class="form-control" name="slot" id="slotDropdown"></select>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-xl-6">
                                                                <label class="form-label">Name<span class="text-danger">*</span></label>
                                                                <input autocomplete='off' type="text" class="form-control" required name="name" value="<?php echo $practitioner_name ?>" title="Enter valid name">
                                                            </div>
                                                            <div class="col-xl-6">
                                                                <label class="form-label">Phone<span class="text-danger">*</span></label>
                                                                <input autocomplete='off' type="text" class="form-control" pattern="[0-9]{6,13}" title="Accept 6 to 13 digit number" maxlength="13" name="phone" value="<?php echo $practitioner_row['practitioner_mobile_number'] ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-xl-6">
                                                                <label class="form-label">Mail<span class="text-danger">*</span></label>
                                                                <input autocomplete='off' type="email" class="form-control" required name="mail" value="<?php echo $practitioner_row['practitioner_email_id'] ?>" title="Enter valid name">
                                                            </div>
                                                            <div class="col-xl-6">
                                                                <label class="form-label">Category<span class="text-danger">*</span></label>
                                                                <select class="form-control" required name="category">
                                                                    <option value="">Choose</option>
                                                                    <option value="Registration">Registration</option>
                                                                    <option value="Renewal">Renewal</option>
                                                                    <option value="Noc">Noc</option>
                                                                    <option value="Duplicate Registration">Duplicate Registration</option>
                                                                    <option value="Good Standing Certificate">Good Standing Certificate</option>
                                                                    <option value="Change of Name">Change of Name</option>
                                                                    <option value="Miscellaneous">Miscellaneous</option>
                                                                    <option value="Restoration">Restoration</option>
                                                                    <option value="Smart Card">Smart Card</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-12 mb-4">
                                                            <label class="form-label">Description</label>
                                                            <textarea autocomplete='off' rows="6" class="form-control" name="description"></textarea>
                                                        </div>
                                                        <input type="hidden" id="selectedDate" name="date" />
                                                        <div class="col-xl-12">
                                                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
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