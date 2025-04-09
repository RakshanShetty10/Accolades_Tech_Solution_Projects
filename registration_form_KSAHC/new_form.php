<?php
$pageTitle = "Karnataka State Allied & Healthcare Council";

// Database connection
require_once 'config/config.php';

// Initialize error tracking
$errors = [];
$show_success_popup = false; // Flag to show the success popup

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate required fields (only Full Name is required)
        $required_fields = [
            'practitioner_name' => 'Full Name' // Only this field is required
        ];

        foreach ($required_fields as $field => $name) {
            if (empty($_POST[$field])) {
                $errors[] = "$name is required";
            }
        }

        if (empty($errors)) {
            // Personal Information
            $registration_type_id = $_POST['registration_type_id'] ?? ''; // Default to empty if not set
            $practitioner_name = $_POST['practitioner_name']; // Required field
            $practitioner_spouse_name = $_POST['practitioner_spouse_name'] ?? '';
            $practitioner_birth_date = $_POST['practitioner_birth_date'] ?? '';
            $practitioner_gender = $_POST['practitioner_gender'] ?? '';
            $practitioner_address_line1 = $_POST['practitioner_address_line1'] ?? '';
            $practitioner_address_line2 = $_POST['practitioner_address_line2'] ?? '';
            $practitioner_mobile_number = $_POST['practitioner_mobile_number'] ?? '';
            $practitioner_email_id = $_POST['practitioner_email_id'] ?? '';
            $practitioner_nationality = $_POST['practitioner_nationality'] ?? 'Indian';
            $practitioner_aadhar_number = $_POST['practitioner_aadhar_number'] ?? '';
            
            // Education Details
            $education_name = $_POST['education_name'] ?? '';
            $year_of_passing = $_POST['year_of_passing'] ?? '';
            $month_of_passing = $_POST['month_of_passing'] ?? '';
            $college_id = $_POST['college_id'] ?? '';
            $university_id = $_POST['university_id'] ?? '';
            
            // Current date for registration_date
            $registration_date = date('Y-m-d');
            
            // Create uploads directory if it doesn't exist
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true);
            }
            
            // Insert practitioner data
            $sql = "INSERT INTO practitioner (registration_date, registration_type_id, practitioner_name, 
                                            practitioner_spouse_name, practitioner_birth_date, 
                                            practitioner_gender, practitioner_mobile_number, 
                                            practitioner_email_id, practitioner_nationality, 
                                            practitioner_aadhar_number, registration_status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sissssssss", $registration_date, $registration_type_id, $practitioner_name, 
                            $practitioner_spouse_name, $practitioner_birth_date, $practitioner_gender, 
                            $practitioner_mobile_number, $practitioner_email_id, $practitioner_nationality, 
                            $practitioner_aadhar_number);
            
            if ($stmt->execute()) {
                $practitioner_id = $conn->insert_id;
                
                // Insert education information
                if (!empty($education_name) || !empty($year_of_passing) || !empty($month_of_passing) || !empty($college_id) || !empty($university_id)) {
                    $sql_education = "INSERT INTO education_information (practitioner_id, education_name, 
                                                 education_year_of_passing, education_month_of_passing,
                                                 college_id, university_id, education_status, 
                                                 education_created_on, education_created_by) 
                                     VALUES (?, ?, ?, ?, ?, ?, 'active', NOW(), 'User Registration')";
                    
                    $stmt_education = $conn->prepare($sql_education);
                    $stmt_education->bind_param("isssii", $practitioner_id, $education_name, 
                                             $year_of_passing, $month_of_passing,
                                             $college_id, $university_id);
                    
                    if (!$stmt_education->execute()) {
                        $errors[] = "Failed to save education information: " . $stmt_education->error;
                    }
                }
                
                // Insert permanent address (only if address fields are provided)
                if (!empty($practitioner_address_line1) || !empty($practitioner_address_line2) || !empty($practitioner_mobile_number)) {
                    $sql_address = "INSERT INTO practitioner_address (practitioner_id, practitioner_address_type, 
                                                              practitioner_address_line1, practitioner_address_line2, 
                                                              practitioner_address_phoneno, practitioner_address_status) 
                                  VALUES (?, 'Permanent', ?, ?, ?, 'active')";
                    
                    $stmt_address = $conn->prepare($sql_address);
                    $stmt_address->bind_param("isss", $practitioner_id, $practitioner_address_line1, 
                                            $practitioner_address_line2, $practitioner_mobile_number);
                    $stmt_address->execute();
                }
                
                // Handle file uploads
                // Define max file size (2MB = 2 * 1024 * 1024 bytes)
                $maxFileSize = 2 * 1024 * 1024; // Added 2MB limit as per previous discussion

                // Signature
                if (isset($_FILES['practitioner_signature']) && $_FILES['practitioner_signature']['error'] == 0) {
                    $signature_size = $_FILES['practitioner_signature']['size'];
                    if ($signature_size > $maxFileSize) {
                        $errors[] = "Signature file size exceeds 2MB limit.";
                    } else {
                        $signature_file = uniqid() . '_' . $_FILES['practitioner_signature']['name'];
                        if (move_uploaded_file($_FILES['practitioner_signature']['tmp_name'], 'uploads/' . $signature_file)) {
                            $sql_update = "UPDATE practitioner SET practitioner_signature = ? WHERE practitioner_id = ?";
                            $stmt_update = $conn->prepare($sql_update);
                            $stmt_update->bind_param("si", $signature_file, $practitioner_id);
                            $stmt_update->execute();
                        } else {
                            $errors[] = "Failed to upload signature file.";
                        }
                    }
                }
                
                // Profile image
                if (isset($_FILES['practitioner_profile_image']) && $_FILES['practitioner_profile_image']['error'] == 0) {
                    $profile_size = $_FILES['practitioner_profile_image']['size'];
                    if ($profile_size > $maxFileSize) {
                        $errors[] = "Profile image file size exceeds 2MB limit.";
                    } else {
                        $profile_file = uniqid() . '_' . $_FILES['practitioner_profile_image']['name'];
                        if (move_uploaded_file($_FILES['practitioner_profile_image']['tmp_name'], 'uploads/' . $profile_file)) {
                            $sql_update = "UPDATE practitioner SET practitioner_profile_image = ? WHERE practitioner_id = ?";
                            $stmt_update = $conn->prepare($sql_update);
                            $stmt_update->bind_param("si", $profile_file, $practitioner_id);
                            $stmt_update->execute();
                        } else {
                            $errors[] = "Failed to upload profile image file.";
                        }
                    }
                }
                
                // Set flag to show success popup
                if (empty($errors)) {
                    $show_success_popup = true;
                }
            } else {
                $errors[] = "Database error: " . $stmt->error;
            }
        }
    } catch (Exception $e) {
        $errors[] = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Success Popup Styles */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        
        .popup-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        .success-popup {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            width: 100%;
            position: relative;
            transform: scale(0.7);
            transition: transform 0.5s cubic-bezier(0.18, 1.25, 0.6, 1.25);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .popup-overlay.show .success-popup {
            transform: scale(1);
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background-color: #4CAF50;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: pulse 1.5s infinite, checkmark-circle 0.5s 0.2s forwards;
            opacity: 0;
        }
        
        .success-icon i {
            color: white;
            font-size: 40px;
            opacity: 0;
            animation: checkmark 0.3s 0.5s forwards;
        }
        
        .success-popup h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 24px;
            transform: translateY(20px);
            opacity: 0;
            animation: slide-up 0.5s 0.7s forwards;
        }
        
        .success-popup p {
            color: #666;
            margin-bottom: 20px;
            transform: translateY(20px);
            opacity: 0;
            animation: slide-up 0.5s 0.9s forwards;
        }
        
        .success-popup button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            transform: translateY(20px);
            opacity: 0;
            animation: slide-up 0.5s 1.1s forwards;
        }
        
        .success-popup button:hover {
            background-color: #3e8e41;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(76, 175, 80, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(76, 175, 80, 0);
            }
        }
        
        @keyframes checkmark-circle {
            0% {
                opacity: 0;
                transform: scale(0);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes checkmark {
            0% {
                opacity: 0;
                transform: scale(0);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes slide-up {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Confetti Animation */
        .confetti-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #f2d74e;
            opacity: 0.7;
        }
        
        .confetti.square {
            border-radius: 0;
        }
        
        .confetti.circle {
            border-radius: 50%;
        }
        
        .confetti.triangle {
            width: 0;
            height: 0;
            background: transparent;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-bottom: 10px solid;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="ksahc_logo.png" alt="Logo">
        <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
    </div>

    <div class="main-content">
        <?php if(!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="page-title">
            <h2>APPLICATION FOR REGISTRATION UNDER COUNCIL</h2>
            <h3>Karnataka State Allied & Healthcare Professions Council</h3>
            <p>Please fill up the following information to apply for Fresh Registration / Renewal of Registration / Duplicate Registration / Additional Registration, Karnataka</p>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <div class="form-sections">
                <div class="section-tab active" data-section="section1">
                    <i class="fas fa-user"></i> Personal Information
                </div>
                <div class="section-tab" data-section="section2">
                    <i class="fas fa-graduation-cap"></i> Education Details
                </div>
                <div class="section-tab" data-section="section3">
                    <i class="fas fa-file-upload"></i> Documents Upload
                </div>
            </div>

            <!-- Section 1: Personal Information -->
            <div id="section1" class="form-section active">
                <h3 class="section-title">Personal Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="registration_type_id">Registration Type</label> <!-- Not required -->
                        <select class="form-control" id="registration_type_id" name="registration_type_id">
                            <option value="">Select Type</option>
                            <?php
                            $reg_sql = "SELECT registration_type_id, registration_type FROM registration_type_master WHERE registration_type_status = 'active'";
                            $reg_result = mysqli_query($conn, $reg_sql);
                            if ($reg_result && mysqli_num_rows($reg_result) > 0) {
                                while($row = mysqli_fetch_assoc($reg_result)) {
                                    echo "<option value='" . $row['registration_type_id'] . "'>" . $row['registration_type'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <div class="help-text">Choose the type of registration you are applying for</div>
                    </div>

                    <div class="form-group">
                        <label for="practitioner_name" class="required">Full Name</label> <!-- Only required field -->
                        <input type="text" class="form-control" id="practitioner_name" name="practitioner_name" required>
                    </div>

                    <div class="form-group">
                        <label for="practitioner_spouse_name">Father/Spouse Name</label>
                        <input type="text" class="form-control" id="practitioner_spouse_name" name="practitioner_spouse_name">
                    </div>

                    <div class="form-group">
                        <label for="practitioner_birth_date">Date of Birth</label>
                        <input type="date" class="form-control" id="practitioner_birth_date" name="practitioner_birth_date">
                    </div>

                    <div class="form-group">
                        <label for="practitioner_gender">Gender</label>
                        <select class="form-control" id="practitioner_gender" name="practitioner_gender">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group address-field">
    <label for="practitioner_address_line1">Permanent Address Line 1</label>
    <input type="text" class="form-control" id="practitioner_address_line1" name="practitioner_address_line1">
</div>

<div class="form-group address-field">
    <label for="practitioner_address_line2">Permanent Address Line 2</label>
    <input type="text" class="form-control" id="practitioner_address_line2" name="practitioner_address_line2">
</div>

                    <!-- <div class="form-group"></div> -->

                    <div class="form-group">
                        <label for="practitioner_mobile_number">Mobile Number</label>
                        <input type="text" class="form-control" id="practitioner_mobile_number" name="practitioner_mobile_number">
                    </div>

                    <div class="form-group">
                        <label for="practitioner_email_id">Email</label>
                        <input type="email" class="form-control" id="practitioner_email_id" name="practitioner_email_id">
                    </div>

                    <div class="form-group">
                        <label for="practitioner_nationality">Nationality</label>
                        <input type="text" class="form-control" id="practitioner_nationality" name="practitioner_nationality" value="Indian">
                    </div>

                    <div class="form-group">
                        <label for="practitioner_aadhar_number">Aadhar Number</label>
                        <input type="text" class="form-control" id="practitioner_aadhar_number" name="practitioner_aadhar_number">
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" class="btn btn-prev prev-btn" disabled><i class="fas fa-arrow-left"></i> Previous</button>
                    <button type="button" class="btn next-btn" id="next-to-section2"><i class="fas fa-arrow-right"></i> Next</button>
                </div>
            </div>

            <!-- Section 2: Education Details -->
            <div id="section2" class="form-section">
                <h3 class="section-title">Education Details</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="education_name"><i class="field-icon fas fa-book"></i>Education Name</label>
                        <input type="text" class="form-control" id="education_name" name="education_name">
                    </div>

                    <div class="form-group">
                        <label for="year_of_passing"><i class="field-icon fas fa-calendar-check"></i>Year of Passing</label>
                        <select class="form-control" name="year_of_passing" id="year_of_passing">
                            <option value="">Select</option>
                            <?php 
                            for($year = 1990; $year <= date('Y'); $year++) {
                                echo "<option value='$year'>$year</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="month_of_passing"><i class="field-icon fas fa-calendar-check"></i>Month of Passing</label>
                        <select class="form-control" name="month_of_passing" id="month_of_passing">
                            <option value="">Select</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="college_id">College</label>
                        <select class="form-control" id="college_id" name="college_id">
                            <option value="">Select College</option>
                            <?php
                            $college_sql = "SELECT college_id, college_name FROM college_master WHERE college_status = 'active'";
                            $college_result = mysqli_query($conn, $college_sql);
                            if ($college_result && mysqli_num_rows($college_result) > 0) {
                                while($row = mysqli_fetch_assoc($college_result)) {
                                    echo "<option value='" . $row['college_id'] . "'>" . $row['college_name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="university_id">University</label>
                        <select class="form-control" id="university_id" name="university_id">
                            <option value="">Select University</option>
                            <?php
                            $university_sql = "SELECT university_id, university_name FROM university_master WHERE university_status = 'active'";
                            $university_result = mysqli_query($conn, $university_sql);
                            if ($university_result && mysqli_num_rows($university_result) > 0) {
                                while($row = mysqli_fetch_assoc($university_result)) {
                                    echo "<option value='" . $row['university_id'] . "'>" . $row['university_name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" class="btn btn-prev prev-btn"><i class="fas fa-arrow-left"></i> Previous</button>
                    <button type="button" class="btn next-btn" id="next-to-section3"><i class="fas fa-arrow-right"></i> Next</button>
                </div>
            </div>

            <!-- Section 3: Documents Upload -->
            <div id="section3" class="form-section">
                <h3 class="section-title">Documents Upload</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="practitioner_signature">Signature</label>
                        <input type="file" class="form-control-file" id="practitioner_signature" name="practitioner_signature">
                        <div class="help-text">Upload your signature (JPG, PNG, or PDF, max 2MB)</div>
                    </div>

                    <div class="form-group">
                        <label for="practitioner_profile_image">Profile Image</label>
                        <input type="file" class="form-control-file" id="practitioner_profile_image" name="practitioner_profile_image">
                        <div class="help-text">Upload your recent passport size photo (JPG or PNG, max 2MB)</div>
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" class="btn btn-prev prev-btn"><i class="fas fa-arrow-left"></i> Previous</button>
                    <button type="submit" class="btn btn-submit" id="submit-form"><i class="fas fa-paper-plane"></i> Submit Application</button>
                </div>
            </div>
        </form>
    </div>

    <div class="footer">
        <p>© <?php echo date('Y'); ?> Karnataka State Allied & Healthcare Council. All Rights Reserved.</p>
    </div>
    
    <!-- Success Popup -->
    <div class="popup-overlay" id="successPopup">
        <div class="success-popup">
            <div class="confetti-container" id="confettiContainer"></div>
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h3>Registration Successful!</h3>
            <p>Your application has been submitted successfully.</p>
            <button id="closePopup">OK</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sectionTabs = document.querySelectorAll('.section-tab');
            const formSections = document.querySelectorAll('.form-section');
            const nextButtons = document.querySelectorAll('.next-btn');
            const prevButtons = document.querySelectorAll('.prev-btn');
            const successPopup = document.getElementById('successPopup');
            const closePopupBtn = document.getElementById('closePopup');
            
            // Function to validate current section (only for Full Name)
            function validateSection(sectionId) {
                const section = document.getElementById(sectionId);
                const requiredFields = section.querySelectorAll('input[required], select[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('error');
                        isValid = false;
                    } else {
                        field.classList.remove('error');
                    }
                });
                
                return isValid;
            }
            
            // Function to switch sections
            function switchSection(targetId) {
                formSections.forEach(section => section.classList.remove('active'));
                sectionTabs.forEach(tab => tab.classList.remove('active'));
                document.getElementById(targetId).classList.add('active');
                document.querySelector(`[data-section="${targetId}"]`).classList.add('active');
                
                sectionTabs.forEach(tab => {
                    const sectionId = tab.getAttribute('data-section');
                    if (sectionId === targetId) return;
                    const sectionIndex = parseInt(sectionId.replace('section', ''));
                    const targetIndex = parseInt(targetId.replace('section', ''));
                    if (sectionIndex < targetIndex) tab.classList.add('complete');
                });
                
                document.querySelector('.form-sections').scrollIntoView({ behavior: 'smooth' });
            }
            
            // Tab click event
            sectionTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetSection = this.getAttribute('data-section');
                    const currentSection = document.querySelector('.form-section.active').id;
                    if (parseInt(targetSection.replace('section', '')) > parseInt(currentSection.replace('section', ''))) {
                        if (!validateSection(currentSection)) {
                            alert('Please fill in the Full Name before proceeding.');
                            return;
                        }
                    }
                    switchSection(targetSection);
                });
            });
            
            // Next button click event
            document.getElementById('next-to-section2').addEventListener('click', function() {
                if (validateSection('section1')) {
                    switchSection('section2');
                } else {
                    alert('Please fill in the Full Name before proceeding.');
                }
            });
            
            document.getElementById('next-to-section3').addEventListener('click', function() {
                switchSection('section3'); // No validation needed for section2 as no required fields
            });
            
            // Previous button click event
            prevButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const currentSection = document.querySelector('.form-section.active');
                    const currentId = currentSection.id;
                    if (currentId === 'section2') {
                        switchSection('section1');
                    } else if (currentId === 'section3') {
                        switchSection('section2');
                    }
                });
            });
            
            // Form submission
            document.getElementById('submit-form').addEventListener('click', function(e) {
                e.preventDefault();
                let isValid = true;
                const requiredFields = document.querySelectorAll('input[required], select[required]');
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('error');
                        isValid = false;
                    } else {
                        field.classList.remove('error');
                    }
                });
                
                if (isValid) {
                    document.querySelector('form').submit();
                } else {
                    alert('Please fill in the Full Name');
                    switchSection('section1'); // Switch to section1 where Full Name is
                }
            });
            
            // Close popup button
            closePopupBtn.addEventListener('click', function() {
                successPopup.classList.remove('show');
                // Redirect to new form page or refresh
                window.location.href = window.location.pathname;
            });
            
            // Show success popup if form submitted successfully
            <?php if($show_success_popup): ?>
            // Create confetti
            createConfetti();
            
            // Show popup with delay for better effect
            setTimeout(function() {
                successPopup.classList.add('show');
            }, 300);
            <?php endif; ?>
            
            // Confetti animation function
            function createConfetti() {
                const container = document.getElementById('confettiContainer');
                const colors = ['#f2d74e', '#95c3de', '#ff9a91', '#f5b400', '#2ccce4', '#ff699f', '#92e6a7', '#bb8ced'];
                const shapes = ['square', 'circle', 'triangle'];
                
                // Clear existing confetti
                container.innerHTML = '';
                
                // Create confetti pieces
                for (let i = 0; i < 150; i++) {
                    const confetti = document.createElement('div');
                    const shape = shapes[Math.floor(Math.random() * shapes.length)];
                    
                    confetti.classList.add('confetti');
                    confetti.classList.add(shape);
                    
                    // Random position
                    const left = Math.random() * 100;
                    const top = -10 - Math.random() * 100;
                    
                    // Random size between 5px and 15px
                    const size = 5 + Math.random() * 10;
                    
                    // Apply styles
                    confetti.style.left = left + '%';
                    confetti.style.top = top + 'px';
                    confetti.style.width = size + 'px';
                    confetti.style.height = size + 'px';
                    
                    // Random color
                    const color = colors[Math.floor(Math.random() * colors.length)];
                    
                    if (shape === 'triangle') {
                        confetti.style.borderBottomColor = color;// For triangle shape, set the border color
                        confetti.style.borderBottomColor = color;
                    } else {
                        // For other shapes, set the background color
                        confetti.style.backgroundColor = color;
                    }
                    
                    // Random rotation and animation duration
                    const duration = 1 + Math.random() * 3;
                    const delay = Math.random() * 2;
                    const rotation = Math.random() * 360;
                    
                    confetti.style.transform = `rotate(${rotation}deg)`;
                    confetti.style.animation = `fall ${duration}s ${delay}s linear forwards`;
                    
                    // Add to container
                    container.appendChild(confetti);
                }
                
                // Add falling animation dynamically
                const styleSheet = document.createElement('style');
                styleSheet.textContent = `
                    @keyframes fall {
                        0% {
                            top: -10px;
                            transform: translateX(0) rotate(0deg);
                            opacity: 1;
                        }
                        
                        50% {
                            transform: translateX(${Math.random() > 0.5 ? '+' : '-'}${20 + Math.random() * 30}px) rotate(${Math.random() * 360}deg);
                            opacity: 1;
                        }
                        
                        100% {
                            top: 110%;
                            transform: translateX(${Math.random() > 0.5 ? '+' : '-'}${50 + Math.random() * 100}px) rotate(${Math.random() * 720}deg);
                            opacity: 0;
                        }
                    }
                `;
                document.head.appendChild(styleSheet);
            }
        });
    </script>
</body>
</html>