<?php

require_once '../../config/connection.php';

if (!empty($_GET['selectedValue']) && !empty($_GET['selectedType'])) {

    $selectedValue = $_GET['selectedValue'];
    $selectedType = $_GET['selectedType'];
    
    $result = mysqli_query($conn, "SELECT fees_name, fees_id, fees_amount 
        FROM fees_master WHERE fees_status = 'Active' 
        AND receipt_for_id = '$selectedValue' AND 
        registration_type_id = '$selectedType'");

    if (mysqli_num_rows($result) > 0) {

        while ($rowPert = mysqli_fetch_assoc($result)) {

            echo "<tr>
                    <td class='width:10%'>
                        <div class='form-check'>
                            <input onchange='updateTotalAmount()' data-amount='$rowPert[fees_amount]' class='form-check-input' type='checkbox' id='$rowPert[fees_id]' value='$rowPert[fees_id]' name='perticulars[]'>
                        </div>
                    </td>
                    <td class='width:70%'>
                        <label class='form-label' for='$rowPert[fees_id]' id='paypertilbl$rowPert[fees_id]'>
                        $rowPert[fees_name]
                        </label>
                    </td>
                    <td class='width:20%'>
                    <h6>$rowPert[fees_amount]</h6>
                    </td>
                </tr>";
        }

        echo "<tr>
                <td></td>
                <td>
                    <label class='form-check-label' id='miscellaneous'>
                    Miscellaneous
                    </label>
                </td>
                <td>
                    <input type='number' value='0' min='0' name='miscellaneousamount' id='miscellaneousamount' class='form-control' oninput='updateTotalAmount()'/>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <label class='form-check-label' id='arrears_of_annual'>
                    Arrears of Annual Fee
                    </label>
                </td>
                <td>
                    <input type='number' value='0' min='0' name='arrears_of_annualamount' id='arrears_of_annualamount' class='form-control' oninput='updateTotalAmount()'/>
                </td>
            </tr>";
    }
}

mysqli_close($conn);