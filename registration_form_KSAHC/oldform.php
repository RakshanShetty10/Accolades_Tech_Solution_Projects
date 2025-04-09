<?php
$pageTitle = "Karnataka State Allied & Healthcare Council";

// Database connection
require_once 'config/config.php';

// Initialize error tracking
$errors = [];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate required fields
        $required_fields = [
            // 'registration_type_id' => 'Registration Type',
            'practitioner_name' => 'Full Name',
            // 'practitioner_birth_date' => 'Date of Birth',
            // 'practitioner_gender' => 'Gender',
            // 'practitioner_address_line1' => 'Address Line 1',
            // 'practitioner_mobile_number' => 'Mobile Number',
            // 'practitioner_email_id' => 'Email',
            // 'education_name' => 'Education Name',
            // 'year_of_passing' => 'Year of Passing',
            // 'month_of_passing' => 'Month of Passing',
            // 'college_id' => 'College',
            // 'university_id' => 'University'
        ];

        foreach ($required_fields as $field => $name) {
            if (empty($_POST[$field])) {
                $errors[] = "$name is required";
            }
        }

        if (empty($errors)) {
            // Personal Information
            $registration_type_id = $_POST['registration_type_id'];
            $practitioner_name = $_POST['practitioner_name'];
            $practitioner_spouse_name = $_POST['practitioner_spouse_name'] ?? '';
            $practitioner_birth_date = $_POST['practitioner_birth_date'];
            $practitioner_gender = $_POST['practitioner_gender'];
            $practitioner_address_line1 = $_POST['practitioner_address_line1'];
            $practitioner_address_line2 = $_POST['practitioner_address_line2'] ?? '';
            $practitioner_mobile_number = $_POST['practitioner_mobile_number'];
            $practitioner_email_id = $_POST['practitioner_email_id'];
            $practitioner_nationality = $_POST['practitioner_nationality'] ?? 'Indian';
            $practitioner_aadhar_number = $_POST['practitioner_aadhar_number'] ?? '';
            
            // Education Details
            $education_name = $_POST['education_name'];
            $year_of_passing = $_POST['year_of_passing'];
            $month_of_passing = $_POST['month_of_passing'];
            $college_id = $_POST['college_id'];
            $university_id = $_POST['university_id'];
            
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
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active')";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sissssssss", $registration_date, $registration_type_id, $practitioner_name, 
                            $practitioner_spouse_name, $practitioner_birth_date, $practitioner_gender, 
                            $practitioner_mobile_number, $practitioner_email_id, $practitioner_nationality, 
                            $practitioner_aadhar_number);
            
            if ($stmt->execute()) {
                $practitioner_id = $conn->insert_id;
                
                // Insert permanent address
                $sql_address = "INSERT INTO practitioner_address (practitioner_id, practitioner_address_type, 
                                                            practitioner_address_line1, practitioner_address_line2, 
                                                            practitioner_address_phoneno, practitioner_address_status) 
                            VALUES (?, 'Permanent', ?, ?, ?, 'Active')";
                
                $stmt_address = $conn->prepare($sql_address);
                $stmt_address->bind_param("isss", $practitioner_id, $practitioner_address_line1, 
                                        $practitioner_address_line2, $practitioner_mobile_number);
                $stmt_address->execute();
                
                // If same as permanent address is checked, use the same address for correspondence
                // if (isset($_POST['same_as_permanent'])) {
                //     $sql_corr_address = "INSERT INTO practitioner_address (practitioner_id, practitioner_address_type, 
                //                                                     practitioner_address_line1, practitioner_address_line2, 
                //                                                     practitioner_address_phoneno, practitioner_address_status) 
                //                     VALUES (?, 'Correspondence', ?, ?, ?, 'Active')";
                    
                //     $stmt_corr_address = $conn->prepare($sql_corr_address);
                //     $stmt_corr_address->bind_param("isss", $practitioner_id, $practitioner_address_line1, 
                //                                 $practitioner_address_line2, $practitioner_mobile_number);
                //     $stmt_corr_address->execute();
                // }
                
                // // Insert education information
                // $sql_education = "INSERT INTO education_information (practitioner_id, education_name, 
                //                                                 education_year_of_passing, education_month_of_passing, 
                //                                                 college_id, university_id, education_status) 
                //                 VALUES (?, ?, ?, ?, ?, ?, 'Active')";
                
                // $stmt_education = $conn->prepare($sql_education);
                // $stmt_education->bind_param("isssii", $practitioner_id, $education_name, 
                //                         $year_of_passing, $month_of_passing, 
                //                         $college_id, $university_id);
                // $stmt_education->execute();
                



                // Handle file uploads
                // Signature
                if(isset($_FILES['practitioner_signature']) && $_FILES['practitioner_signature']['error'] == 0) {
                    $signature_file = uniqid() . '_' . $_FILES['practitioner_signature']['name'];
                    move_uploaded_file($_FILES['practitioner_signature']['tmp_name'], 'uploads/' . $signature_file);
                    
                    $sql_update = "UPDATE practitioner SET practitioner_signature = ? WHERE practitioner_id = ?";
                    $stmt_update = $conn->prepare($sql_update);
                    $stmt_update->bind_param("si", $signature_file, $practitioner_id);
                    $stmt_update->execute();
                }
                
                // Profile image
                if(isset($_FILES['practitioner_profile_image']) && $_FILES['practitioner_profile_image']['error'] == 0) {
                    $profile_file = uniqid() . '_' . $_FILES['practitioner_profile_image']['name'];
                    move_uploaded_file($_FILES['practitioner_profile_image']['tmp_name'], 'uploads/' . $profile_file);
                    
                    $sql_update = "UPDATE practitioner SET practitioner_profile_image = ? WHERE practitioner_id = ?";
                    $stmt_update = $conn->prepare($sql_update);
                    $stmt_update->bind_param("si", $profile_file, $practitioner_id);
                    $stmt_update->execute();
                }
                
                // Redirect or show success message
                $success_message = "Registration completed successfully!";
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
   
</head>
<body>
    <div class="header">
        <img src="logo.jpg" alt="Logo">
        <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
    </div>

    <div class="main-content">
        <?php if(isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
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
                        <label for="registration_type_id" class="required">Registration Type</label>
                        <select class="form-control" id="registration_type_id" name="registration_type_id" >
                            <option value="">Select Type</option>
                            <?php
                            $reg_sql = "SELECT registration_type_id, registration_type FROM registration_type_master WHERE registration_type_status = 'Active'";
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
                        <label for="practitioner_name" class="required">Full Name</label>
                        <input type="text" class="form-control" id="practitioner_name" name="practitioner_name" >
                    </div>

                    <div class="form-group">
                        <label for="practitioner_spouse_name">Father Name</label>
                        <input type="text" class="form-control" id="practitioner_spouse_name" name="practitioner_spouse_name">
                    </div>

                    <div class="form-group">
                        <label for="practitioner_birth_date" >Date of Birth</label>
                        <input type="date" class="form-control" id="practitioner_birth_date" name="practitioner_birth_date" >
                    </div>

                    <div class="form-group">
                        <label for="practitioner_gender" >Gender</label>
                        <select class="form-control" id="practitioner_gender" name="practitioner_gender" >
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="practitioner_address_line1" class="required">Permanent Address Line 1</label>
                        <input type="text" class="form-control" id="practitioner_address_line1" name="practitioner_address_line1" >
                    </div>

                    <div class="form-group">
                        <label for="practitioner_address_line2">Permanent Address Line 2</label>
                        <input type="text" class="form-control" id="practitioner_address_line2" name="practitioner_address_line2">
                    </div>

                    <!-- <div class="form-group">
                        <label>Correspondence Address</label>
                        <div class="checkbox-container">
                            <input type="checkbox" id="same_as_permanent" name="same_as_permanent">
                            <label for="same_as_permanent">Same as Permanent Address</label>
                        </div>
                    </div> -->

                    <div class="form-group"></div><!-- Empty spacer for alignment -->

                    <div class="form-group">
                        <label for="practitioner_mobile_number" class="required">Mobile Number</label>
                        <input type="text" class="form-control" id="practitioner_mobile_number" name="practitioner_mobile_number" >
                    </div>

                    <div class="form-group">
                        <label for="practitioner_email_id" class="required">Email</label>
                        <input type="email" class="form-control" id="practitioner_email_id" name="practitioner_email_id" >
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
                        <label class="required" for="education_name"><i class="field-icon fas fa-book"></i>Education name</label>
                        <input type="text" class="form-control" id="education_name" name="education_name" >
                    </div>

                    <div class="form-group">
                        <label class="required" for="year_of_passing"><i class="field-icon fas fa-calendar-check"></i>Year of Passing</label>
                        <select class="form-control" name="year_of_passing" id="year_of_passing" >
                            <option value="">Select</option>
                            <?php 
                            for($year = 1990; $year <= date('Y'); $year++) {
                                echo "<option value='$year'>$year</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="required" for="month_of_passing"><i class="field-icon fas fa-calendar-check"></i>Month of passing</label>
                        <select class="form-control" name="month_of_passing" id="month_of_passing" >
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
                        <label class="required" for="college_id">College</label>
                        <select class="form-control" id="college_id" name="college_id" >
                            <option value="">Select College</option>
                            <?php
                            $college_sql = "SELECT college_id, college_name FROM college_master WHERE college_status = 'Active'";
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
                        <label class="required" for="university_id">University</label>
                        <select class="form-control" id="university_id" name="university_id" >
                            <option value="">Select University</option>
                            <?php
                            $university_sql = "SELECT university_id, university_name FROM university_master WHERE university_status = 'Active'";
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
        <p>&copy; <?php echo date('Y'); ?> Karnataka State Allied & Healthcare Council. All Rights Reserved.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sectionTabs = document.querySelectorAll('.section-tab');
            const formSections = document.querySelectorAll('.form-section');
            const nextButtons = document.querySelectorAll('.next-btn');
            const prevButtons = document.querySelectorAll('.prev-btn');


            
            // Same as permanent address checkbox
            // const sameAsPermanentCheckbox = document.getElementById('same_as_permanent');
            
            // Function to validate current section
            // function validateSection(sectionId) {
            //     const section = document.getElementById(sectionId);
            //     const requiredFields = section.querySelectorAll('input[required], select[required]');
            //     let isValid = true;
                
            //     requiredFields.forEach(field => {
            //         if (!field.value.trim()) {
            //             field.classList.add('error');
            //             isValid = false;
            //         } else {
            //             field.classList.remove('error');
            //         }
            //     });
                
            //     return isValid;
            // }
            
            // Function to switch sections
            function switchSection(targetId) {
                // Hide all sections
                formSections.forEach(section => {
                    section.classList.remove('active');
                });
                
                // Deactivate all tabs
                sectionTabs.forEach(tab => {
                    tab.classList.remove('active');
                });
                
                // Show target section
                document.getElementById(targetId).classList.add('active');
                
                // Activate target tab
                document.querySelector(`[data-section="${targetId}"]`).classList.add('active');
                
                // Mark previous tabs as complete
                sectionTabs.forEach(tab => {
                    const sectionId = tab.getAttribute('data-section');
                    if (sectionId === targetId) {
                        return; // Skip current tab
                    }
                    
                    const sectionIndex = parseInt(sectionId.replace('section', ''));
                    const targetIndex = parseInt(targetId.replace('section', ''));
                    
                    if (sectionIndex < targetIndex) {
                        tab.classList.add('complete');
                    }
                });
                
                // Scroll to top of the form
                document.querySelector('.form-sections').scrollIntoView({
                    behavior: 'smooth'
                });
            }
            
            // Tab click event
            sectionTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetSection = this.getAttribute('data-section');
                    const currentSection = document.querySelector('.form-section.active').id;
                    
                    // Validate current section before switching
                    if (parseInt(targetSection.replace('section', '')) > parseInt(currentSection.replace('section', ''))) {
                        if (!validateSection(currentSection)) {
                            alert('Please fill in all required fields before proceeding.');
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
                    alert('Please fill in all required fields before proceeding.');
                }
            });
            
            document.getElementById('next-to-section3').addEventListener('click', function() {
                if (validateSection('section2')) {
                    switchSection('section3');
                } else {
                    alert('Please fill in all required fields before proceeding.');
                }
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
            
            // Add input event listeners to required fields
            // document.querySelectorAll('input[required], select[required]').forEach(field => {
            //     field.addEventListener('input', function() {
            //         if (this.value.trim()) {
            //             this.classList.remove('error');
            //         } else {
            //             this.classList.add('error');
            //         }
            //     });
            // });
            
            // Form submission
            document.getElementById('submit-form').addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default form submission
                
                // Make sure all required fields are filled
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
                    alert('Please fill in all required fields');
                    
                    // Find first section with errors and switch to it
                    for (let i = 1; i <= 3; i++) {
                        const sectionId = 'section' + i;
                        const section = document.getElementById(sectionId);
                        const hasErrors = section.querySelectorAll('.error').length > 0;
                        
                        if (hasErrors) {
                            switchSection(sectionId);
                            break;
                        }
                    }
                }
            });
        });
    </script>
</body>
</html> 