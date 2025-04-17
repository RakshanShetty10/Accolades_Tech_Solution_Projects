<?php

    require_once '../../config/connection.php';

	$id = $_POST['id'];
	$username = $_POST['username'];
	$flag = $_POST['editflag'];

	if($_POST['editstatus']){

		$status = 0;
	} else{
		
		$status = 1;
	}

	if($flag == 'Read'){

        $res = mysqli_query($conn, "SELECT permission_id FROM permission_manager
            WHERE permission_particular_id = '$id' AND permission_username = '$username'");

        if(mysqli_num_rows($res)>0){

            echo json_encode(mysqli_query($conn, "UPDATE permission_manager SET 
                permission_view_permission = '$status' WHERE permission_particular_id = '$id' AND permission_username = '$username'"));
        } else{
            echo json_encode(mysqli_query($conn, "INSERT INTO permission_manager (
                permission_particular_id, permission_username, permission_view_permission, permission_edit_permission) VALUES (
                '$id', '$username', '$status', 0)"));
        }
		
	} else if($flag == 'Write'){

        $res = mysqli_query($conn, "SELECT permission_id FROM permission_manager
            WHERE permission_particular_id = '$id' AND permission_username = '$username'");

        if(mysqli_num_rows($res)>0){

            echo json_encode(mysqli_query($conn, "UPDATE permission_manager SET 
                permission_edit_permission = '$status' WHERE permission_particular_id = '$id' AND permission_username = '$username'"));
        } else{
            echo json_encode(mysqli_query($conn, "INSERT INTO permission_manager (
                permission_particular_id, permission_username, permission_view_permission, permission_edit_permission) VALUES (
                '$id', '$username', 0, '$status')"));
        }
	} else{

		echo json_encode(false);
	}

	mysqli_close($conn);