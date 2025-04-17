<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT * FROM tbl_receipt_payment_details");
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        
        if(mysqli_query($conn, "INSERT INTO cashbook_number_master (cashbook_number) VALUES ('$row[ReceiptNumber]')")){

            $type = '';
            if($row['ReceiptPaymentType']=='P'){
                $type = 'Payment';
            } else if($row['ReceiptPaymentType']=='R'){
                $type = 'Receipt';
            }
            $status= 'Active';
            if($row['CancelStatus']=='Y'){
                $status = 'In-Active';
            }  

            $account = '';
            if($row['AccountCode']=='1'){
                $account = 1;
            } else if($row['AccountCode']=='2'){
                $account = 2;
            } else if($row['AccountCode']=='Syndicate Bank'){
                $account = 1;
            }

            $ledger = '';

            $ledger_res = mysqli_query($conn, "SELECT ledger_id FROM ledger_master WHERE 
                ledger_code_ksdc = '$row[LedgerCode]'");
            if(mysqli_num_rows($ledger_res)>0){
                $ledger_res = mysqli_fetch_assoc($ledger_res);
                $ledger = $ledger_res['ledger_id'];
            }
            // echo $row['id'];
            $sql = 'INSERT INTO cashbook (cashbook_number, cashbook_date, cashbook_total, 
                cashbook_remark, cashbook_created_by, cashbook_created_on, cashbook_last_updated_by, 
                cashbook_last_updated_on, cashbook_cancel_date, cashbook_cancel_reason, cashbook_status, 
                cashbook_reference_number, account_id, ledger_id, cashbook_type)
                VALUES("'.$row['ReceiptNumber'].'", "'.$row['ReceiptDate'].'", "'.$row['Amount'].'", "'.$row['Narration'].'", 
                "'.$row['Created_By'].'", "'.$row['Created_Dt'].'", "'.$row['Updated_By'].'", "'.$row['Updated_dt'].'", 
                "'.$row['canceldate'].'", "'.$row['CancelReason'].'", "'.$status.'", "'.$row['DD_ChequeNO'].'", "'.$account.'", 
                "'.$ledger.'", "'.$type.'")';
                
            if(mysqli_query($conn, $sql)){

                
            } else{
                echo 'receipt'.$row['id'];
            }
        } else{
            echo 'number'.$row['id'];
        }
    }
}

?>