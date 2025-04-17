<?php
session_start();
require_once '../../config/connection.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$type = isset($_GET['type']) ? (int)$_GET['type'] : 0;
$response = "";

$encodes = base64_encode($id);
$param = urlencode($encodes);

if ($id > 0) {

    $resEducation = mysqli_query($conn, "SELECT education_id, education_name, 
        education_year_of_passing, education_month_of_passing, college_id, 
        university_id, subject_id, course_name, registration_date FROM education_information  
    WHERE practitioner_id = '$id' AND education_status = 'Active' ORDER BY education_id DESC");
    if (mysqli_num_rows($resEducation) > 0) {

        while ($rowEducation = mysqli_fetch_assoc($resEducation)) {

            $response .= "<form class='row mb-5 p-4'
                                style='background-color: #f5f5f5;' method='POST'
                                enctype='multipart/form-data' action='?source=$param&active=education'>
                                <div class='col-xl-4 mb-3'>
                                    <label class='form-label'>Degree<span class='text-danger'>*</span></label>
                                    <select class='form-control' name='edu_degree' required onchange='add_edu_info_edit(\"$rowEducation[education_id]\", this)'>";
                                    
                                    if($type==1){                                        
                                        $response .= "<option value=''>Choose</option>
                                        <option value='BDS'";
                                        if($rowEducation['education_name']=='BDS'){
                                            $response .= " selected";
                                        }
                                        $response .= ">BDS</option>
                                            <option value='MDS'";
                                            if($rowEducation['education_name']=='MDS'){
                                                $response .= " selected";
                                            }
                                        $response .= ">MDS</option>";
                                    }
                                    
                                    $response .= "<option value='Other'";
                                    if($rowEducation['education_name']=='Other'){
                                        $response .= " selected";
                                    }
                                    $response .= ">Other</option>";

                                    $response .= "<option value='DH'";
                                    if($rowEducation['education_name']=='DH'){
                                        $response .= " selected";
                                    }
                                    $response .= ">DH</option>";

                                    $response .= "<option value='DM'";
                                    if($rowEducation['education_name']=='DM'){
                                        $response .= " selected";
                                    }
                                    $response .= ">DM</option>";

                                    $response .= "<option value='DORA'";
                                    if($rowEducation['education_name']=='DORA'){
                                        $response .= " selected";
                                    }
                                    $response .= ">DORA</option>";

                                    $response .= "<option value='Diploma'";
                                    if($rowEducation['education_name']=='Diploma'){
                                        $response .= " selected";
                                    }
                                    $response .= ">Diploma</option>";

                                    $response .= "<option value='PG Diploma'";
                                    if($rowEducation['education_name']=='PG Diploma'){
                                        $response .= " selected";
                                    }
                                    $response .= ">PG Diploma</option>

                                    </select>
                                    <div class='invalid-feedback'>
                                        Please choose a valid degree.
                                    </div>
                                </div>

                                <div class='col-xl-4 mb-3' id='add_edu_subject_container_edit$rowEducation[education_id]'";
                                if($rowEducation['education_name']!='MDS'){
                                    $response .= " style='display:none'";
                                }
                                
                                $response .= ">
                                    <label class='form-label'>Subject<span class='text-danger'>*</span></label>
                                    <select id='add_edu_subject_edit$rowEducation[education_id]'
                                        class='form-control' name='edu_subject'";
                                        if ($rowEducation['education_name'] == 'MDS') {
                                            $response .= " required";
                                        }
                                        
                                        $response .= ">
                                            <option value=''>Choose</option>";
                                                $resSub = mysqli_query($conn, "SELECT subject_id, subject_name FROM subject_master WHERE subject_status = 'Active'");
                                                if (mysqli_num_rows($resSub) > 0) {
                                                    while ($rowSub = mysqli_fetch_assoc($resSub)) {
                                                        $response .= "<option ";
                                                        if ($rowSub['subject_id'] == $rowEducation['subject_id']) {
                                                            $response .= 'selected';
                                                        };
                                                        $response .= " value='$rowSub[subject_id]'>$rowSub[subject_name]</option>";
                                                    }
                                                }
                                $response .= "
                                    </select>
                                    <div class='invalid-feedback'>
                                        Please enter a valid subject.
                                    </div>
                                </div>

                                <div class='col-xl-4 mb-3' id='add_edu_date_container_edit$rowEducation[education_id]'";
                                if($rowEducation['education_name']!='MDS'){
                                    $response .= " style='display:none'";
                                }
                                
                                $response .= ">
                                    <label class='form-label'>Registration Date<span class='text-danger'>*</span></label>
                                    <input autocomplete='off' type='date' id='add_edu_date_edit$rowEducation[education_id]'
                                        value='$rowEducation[registration_date]' max='".date('Y-m-d')."' class='form-control'
                                        name='edu_date' ";
                                        if ($rowEducation['education_name'] == 'MDS') {
                                                $response .= " required";
                                        }
                                        $response .=">
                                    <div class='invalid-feedback'>
                                        Please choose a valid date.
                                    </div>
                                </div>

                                <div class='col-xl-4 mb-3' id='add_course_name_container_edit_other_$rowEducation[education_id]'";
                                    if($rowEducation['education_name']!='Other'){
                                        $response .= "style=' display:none'";
                                    }
                                    $response .= "><label class='form-label'>Course Name<span class='text-danger'>*</span></label>
                                        <input autocomplete='off' type='text' id='add_course_name_edit_other_$rowEducation[education_id]'
                                            value='$rowEducation[course_name]' class='form-control' name='edu_course'";
                                            if ($rowEducation['education_name'] == 'Other') {
                                                $response .= " required";
                                            }
                                    $response .=">
                                    <div class='invalid-feedback'>
                                        Please enter a valid course name.
                                    </div>
                                </div>
                                <div class='col-xl-4 mb-3' id='add_course_name_container_edit_diploma_$rowEducation[education_id]'";
                                    if($rowEducation['education_name']!='Diploma'){
                                        $response .= "style=' display:none'";
                                    }
                                    $response .= "><label class='form-label'>Course Name<span class='text-danger'>*</span></label>
                                        <input autocomplete='off' type='text' id='add_course_name_edit_diploma_$rowEducation[education_id]'
                                            value='$rowEducation[course_name]' class='form-control' name='edu_course'";
                                            if ($rowEducation['education_name'] == 'Diploma') {
                                                $response .= " required";
                                            }
                                    $response .=">
                                    <div class='invalid-feedback'>
                                        Please enter a valid course name.
                                    </div>
                                </div>
                                <div class='col-xl-4 mb-3' id='add_course_name_container_edit_dh_$rowEducation[education_id]'";
                                    if($rowEducation['education_name']!='DH'){
                                        $response .= "style=' display:none'";
                                    }
                                    $response .= "><label class='form-label'>Course Name<span class='text-danger'>*</span></label>
                                        <input autocomplete='off' type='text' id='add_course_name_edit_dh_$rowEducation[education_id]'
                                            value='$rowEducation[course_name]' class='form-control' name='edu_course'";
                                            if ($rowEducation['education_name'] == 'DH') {
                                                $response .= " required";
                                            }
                                    $response .=">
                                    <div class='invalid-feedback'>
                                        Please enter a valid course name.
                                    </div>
                                </div>

                                <div class='col-xl-4 mb-3' id='add_course_name_container_edit_dm_$rowEducation[education_id]'";
                                    if($rowEducation['education_name']!='DM'){
                                        $response .= "style=' display:none'";
                                    }
                                    $response .= "><label class='form-label'>Course Name<span class='text-danger'>*</span></label>
                                        <input autocomplete='off' type='text' id='add_course_name_edit_dm_$rowEducation[education_id]'
                                            value='$rowEducation[course_name]' class='form-control' name='edu_course'";
                                            if ($rowEducation['education_name'] == 'DM') {
                                                $response .= " required";
                                            }
                                    $response .=">
                                    <div class='invalid-feedback'>
                                        Please enter a valid course name.
                                    </div>
                                </div>

                                <div class='col-xl-4 mb-3' id='add_course_name_container_edit_dora_$rowEducation[education_id]'";
                                    if($rowEducation['education_name']!='DORA'){
                                        $response .= "style=' display:none'";
                                    }
                                    $response .= "><label class='form-label'>Course Name<span class='text-danger'>*</span></label>
                                        <input autocomplete='off' type='text' id='add_course_name_edit_dora_$rowEducation[education_id]'
                                            value='$rowEducation[course_name]' class='form-control' name='edu_course'";
                                            if ($rowEducation['education_name'] == 'DORA') {
                                                $response .= " required";
                                            }
                                    $response .=">
                                    <div class='invalid-feedback'>
                                        Please enter a valid course name.
                                    </div>
                                </div>
                                
                                 <div class='col-xl-4 mb-3' id='add_course_name_container_edit_pg_diploma_$rowEducation[education_id]'";
                                    if($rowEducation['education_name']!='PG Diploma'){
                                        $response .= "style=' display:none'";
                                    }
                                    $response .= "><label class='form-label'>Course Name<span class='text-danger'>*</span></label>
                                        <input autocomplete='off' type='text' id='add_course_name_edit_pg_diploma_$rowEducation[education_id]'
                                            value='$rowEducation[course_name]' class='form-control' name='edu_course'";
                                            if ($rowEducation['education_name'] == 'PG Diploma') {
                                                $response .= " required";
                                            }
                                    $response .=">
                                    <div class='invalid-feedback'>
                                        Please enter a valid course name.
                                    </div>
                                </div>
                                
                                <div class='col-xl-4 mb-3'>
                                    <label class='form-label'>Month of
                                        Passing<span class='text-danger'>
                                            *</span></label>
                                    <select class='form-control' name='edu_month' required>
                                        <option value=''>Choose</option>
                                        <option";
                                        if ($rowEducation['education_month_of_passing'] == 'January') {
                                            $response .= ' selected';
                                        }
                                        $response .= " value='January'>January</option>
                                            <option";
                                            if ($rowEducation['education_month_of_passing'] == 'February') {
                                                $response .= ' selected';
                                            }
                                            $response .= " value='February'>February</option>
                                            <option";
                                            if ($rowEducation['education_month_of_passing'] == 'March') {
                                                $response .= ' selected';
                                            }
                                            $response .= " value='March'>March</option>
                                                <option";
                                            if ($rowEducation['education_month_of_passing'] == 'April') {
                                                $response .= ' selected';
                                            }
                                            $response .=" value='April'>April</option>
                                                <option";
                                                if ($rowEducation['education_month_of_passing'] == 'May') {
                                                    $response .= ' selected';
                                                }
                                                $response .= " value='May'>May</option>
                                                    <option";
                                                    if ($rowEducation['education_month_of_passing'] == 'June') {
                                                        $response .= ' selected';
                                                    } 
                                                $response .= " value='June'>June</option>
                                                    <option";
                                                    if ($rowEducation['education_month_of_passing'] == 'July') {
                                                        $response .= ' selected';
                                                    }
                                                $response .= " value='July'>July</option>
                                                    <option";
                                                if ($rowEducation['education_month_of_passing'] == 'August') {
                                                    $response .= ' selected';
                                                }
                                                
                                                $response .= " value='August'>August</option>
                                                    <option";
                                                if ($rowEducation['education_month_of_passing'] == 'September') {
                                                    $response .= ' selected';
                                                } 
                                                $response .= " value='September'>September</option>
                                                    <option";
                                                    if ($rowEducation['education_month_of_passing'] == 'October') {
                                                        $response .= ' selected';
                                                    } 
                                                    $response .= " value='October'>October</option>
                                                    <option";
                                                    if ($rowEducation['education_month_of_passing'] == 'November') {
                                                        $response .= ' selected';
                                                    }
                                                    $response .= " value='November'>November</option>
                                                    <option";
                                                    if ($rowEducation['education_month_of_passing'] == 'December') {
                                                        $response .= ' selected';
                                                    }
                                                    $response .= " value='December'>December</option>
                                                </select>
                                    <div class='invalid-feedback'>
                                        Please choose a valid month.
                                    </div>
                                </div>

                                <div class='col-xl-4 mb-3'>
                                    <label class='form-label'>Year of
                                        Passing<span class='text-danger'>
                                            *</span></label>
                                    <select class='form-control' name='edu_year' required>
                                        <option value=''>Choose</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2025') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2025'>2025</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2024') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2024'>2024</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2023') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2023'>2023</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2022') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2022'>2022</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2021') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2021'>2021</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2020') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2020'>2020</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2019') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2019'>2019</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2018') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2018'>2018</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2017') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2017'>2017</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2016') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2016'>2016</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2015') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2015'>2015</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2014') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2014'>2014</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2013') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2013'>2013</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2012') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2012'>2012</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2011') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2011'>2011</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2010') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2010'>2010</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2009') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2009'>2009</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2008') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2008'>2008</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2007') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2007'>2007</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2006') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2006'>2006</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2005') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2005'>2005</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2004') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2004'>2004</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2003') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2003'>2003</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2002') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2002'>2002</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2001') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2001'>2001</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '2000') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='2000'>2000</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1999') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1999'>1999</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1998') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1998'>1998</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1997') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1997'>1997</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1996') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1996'>1996</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1995') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1995'>1995</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1994') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1994'>1994</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1993') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1993'>1993</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1992') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1992'>1992</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1991') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1991'>1991</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1990') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1990'>1990</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1989') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1989'>1989</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1988') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1988'>1988</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1987') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1987'>1987</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1986') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1986'>1986</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1985') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1985'>1985</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1984') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1984'>1984</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1983') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1983'>1983</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1982') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1982'>1982</option>

                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1981') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1981'>1981</option>


                                        <option"; 
                                        if ($rowEducation['education_year_of_passing'] == '1980') {
                                            $response .= ' selected';
                                        }
                                        $response.=" value='1980'>1980</option>";

                                        if ( $rowEducation['education_year_of_passing'] == '1980' || $rowEducation['education_year_of_passing'] == '1981' || $rowEducation['education_year_of_passing'] == '1982' || $rowEducation['education_year_of_passing'] == '1983' || $rowEducation['education_year_of_passing'] == '1984' || $rowEducation['education_year_of_passing'] == '1985' || $rowEducation['education_year_of_passing'] == '1986' || $rowEducation['education_year_of_passing'] == '1987' || $rowEducation['education_year_of_passing'] == '1988' || $rowEducation['education_year_of_passing'] == '1989' || $rowEducation['education_year_of_passing'] == '1990' || $rowEducation['education_year_of_passing'] == '1991' || $rowEducation['education_year_of_passing'] == '1992' || $rowEducation['education_year_of_passing'] == '1993' || $rowEducation['education_year_of_passing'] == '1994' || $rowEducation['education_year_of_passing'] == '1995' || $rowEducation['education_year_of_passing'] == '1996' || $rowEducation['education_year_of_passing'] == '1997' || $rowEducation['education_year_of_passing'] == '1998' || $rowEducation['education_year_of_passing'] == '1999' || $rowEducation['education_year_of_passing'] == '2000' || $rowEducation['education_year_of_passing'] == '2001' || $rowEducation['education_year_of_passing'] == '2002' || $rowEducation['education_year_of_passing'] == '2003' || $rowEducation['education_year_of_passing'] == '2004' || $rowEducation['education_year_of_passing'] == '2005' || $rowEducation['education_year_of_passing'] == '2006' || $rowEducation['education_year_of_passing'] == '2007' || $rowEducation['education_year_of_passing'] == '2008' || $rowEducation['education_year_of_passing'] == '2009' || $rowEducation['education_year_of_passing'] == '2010' || $rowEducation['education_year_of_passing'] == '2011' || $rowEducation['education_year_of_passing'] == '2012' || $rowEducation['education_year_of_passing'] == '2013' || $rowEducation['education_year_of_passing'] == '2014' || $rowEducation['education_year_of_passing'] == '2015' || $rowEducation['education_year_of_passing'] == '2016' || $rowEducation['education_year_of_passing'] == '2017' || $rowEducation['education_year_of_passing'] == '2018' || $rowEducation['education_year_of_passing'] == '2019' || $rowEducation['education_year_of_passing'] == '2020' || $rowEducation['education_year_of_passing'] == '2021' || $rowEducation['education_year_of_passing'] == '2022' || $rowEducation['education_year_of_passing'] == '2023' || $rowEducation['education_year_of_passing'] == '2024' || $rowEducation['education_year_of_passing'] == '2025') {
                                            
                                        } else{
                                            
                                            $response .= "<option selected value='$rowEducation[education_year_of_passing]'>$rowEducation[education_year_of_passing]</option>"; 
                                        }
                                    
                        $response .= "</select>

                                    <div class='invalid-feedback'>
                                        Please enter a valid year.
                                    </div>
                                </div>                             

                                <div class='col-xl-4 mb-3'>
                                    <label class='form-label'>University</label>
                                    <select class='form-control' name='edu_university'>
                                        <option value=''>Choose</option>";
                                            $resUni = mysqli_query($conn, "SELECT university_id, university_name FROM university_master WHERE university_status = 'Active'
                                                ORDER BY university_name ASC");
                                                if (mysqli_num_rows($resUni) > 0) {
                                                    while ($rowUni = mysqli_fetch_assoc($resUni)) {
                                                        $response .= "<option ";
                                                        if ($rowUni['university_id'] == $rowEducation['university_id']) {
                                                            $response .= ' selected';
                                                        };
                                                        $response .= " value='$rowUni[university_id]'>$rowUni[university_name]</option>";
                                                    }
                                                }
                                    $response .= "</select>
                                    <div class='invalid-feedback'>
                                        Please choose a valid university.
                                    </div>
                                </div>

                                <div class='col-xl-4 mb-1'>
                                    <label class='form-label'>College<span class='text-danger'>
                                            *</span></label>
                                    <select class='form-control' name='edu_college' required>

                                        <option value=''>Choose</option>";
                                        
                                        $resCol = mysqli_query($conn, "SELECT college_id, college_name 
                                            FROM college_master WHERE college_status = 'Active' ORDER BY college_name ASC");
                                                if (mysqli_num_rows($resCol) > 0) {
                                                    while ($rowCol = mysqli_fetch_assoc($resCol)) {
                                                        $response .= "<option value='$rowCol[college_id]'";
                                                        if ($rowCol['college_id'] == $rowEducation['college_id']) {
                                                            $response .= ' selected';
                                                        }
                                                        $response .= ">$rowCol[college_name]</option>";
                                                    }
                                                }
                        $response .= "</select>

                                    <div class='invalid-feedback'>
                                        Please choose a valid college.
                                    </div>
                                </div>

                                <input autocomplete='off' type='hidden' name='edu_id' value='$rowEducation[education_id]' />

                                <div class='col-xl-12 text-end '>
                                    <button type='submit' name='edit_education' class='btn btn-primary btn-sm'>Update</button>";
                                    if($_SESSION['user_role']=='Admin'){

                                        $response .= "<button type='submit' name='delete_education' onclick='return confirm(' Are you sure you want to delete')'
                                            class='btn btn-outline-danger btn-sm'>Delete</button>";
                                    }
                    $response .= "</div>
                            </form>";
        }
    }
}

echo $response;

mysqli_close($conn);
?>