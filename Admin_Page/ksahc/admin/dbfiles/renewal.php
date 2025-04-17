<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT * FROM tbl_renewal_history ORDER BY r_id ASC LIMIT 100000 OFFSET 77241");
if(mysqli_num_rows($res)>0){

    while($row = mysqli_fetch_assoc($res)){

        if(!empty($row['DD_ChequeNO']))
        {
            $ref_no = $row['DD_ChequeNO'];
        } else{
            $ref_no = $row['TransactionNo'];
        }

        $bank = '';

        $mbank = addslashes($row['Bank']);

        $bsql = "SELECT bank_id FROM bank_master WHERE 
            bank_code_ksdc = '$mbank'";
            
        $res_bank = mysqli_query($conn, $bsql);
        if(mysqli_num_rows($res_bank)>0){
            $res_bank = mysqli_fetch_assoc($res_bank);
            $bank = $res_bank['bank_id'];
        }

        $id = '';

        $res_id = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE 
            PractitionerID = '$row[PractitionerID]'");
        if(mysqli_num_rows($res_id)>0){
            $res_id = mysqli_fetch_assoc($res_id);
            $id = $res_id['practitioner_id'];
        }

        $for = '';
        if($row['PaymentFor']=='A'){
            $for = 11;
        } else if($row['PaymentFor']=='B'){
            $for = 13;
        } else if($row['PaymentFor']=='C'){
            $for = 10;
        } else if($row['PaymentFor']=='D'){
            $for = 7;
        } else if($row['PaymentFor']=='F'){
            $for = 3;
        } else if($row['PaymentFor']=='G'){
            $for = 2;
        } else if($row['PaymentFor']=='H'){
            $for = 8;
        } else if($row['PaymentFor']=='I'){
            $for = 14;
        } else if($row['PaymentFor']=='J'){
            $for = 15;
        } else if($row['PaymentFor']=='M'){
            $for = 9;
        } else if($row['PaymentFor']=='N'){
            $for = 6;
        } else if($row['PaymentFor']=='O'){
            $for = 16;
        } else if($row['PaymentFor']=='P'){
            $for = 5;
        } else if($row['PaymentFor']=='R'){
            $for = 1;
        } else if($row['PaymentFor']=='S'){
            $for = 4;
        } else if($row['PaymentFor']=='T'){
            $for = 12;
        }

        $status = '';
        if($row['Status']=='N'){
            $status = "No";
        } else if($row['Status']=='Y'){
            $status = "Yes";
        }
        
        $sql = "INSERT INTO receipt (receipt_total, receipt_date, receipt_reference_number, 
            dd_date, bank_id, account_id, receipt_for_id, receipt_type, receipt_created_on, receipt_created_by, 
            receipt_last_updated_on, receipt_last_updated_by, receipt_number, receipt_status, receipt_remit_status, 
            practitioner_id, RenewalID) VALUES ('$row[Amount]', '$row[ReceiptDate]', '$ref_no', '$row[DD_ChequeDate]', '$bank', 
            1, '$for', '$row[Type]', '$row[CreatedOn]', '$row[CreatedBy]', '$row[UpdatedOn]', '$row[UpdatedBy]', 
            '$row[ReceiptNumber]', 'Active', '$status', '$id', '$row[RenewalID]')";
            
        if(mysqli_query($conn, $sql)){
            
            // echo $row['RenewalID'];
            // echo "<br>";
            // echo $row['RenewalID'];
        } else{
            
            echo $row['r_id'];
            echo "<br>";
        }
    }
}

?>