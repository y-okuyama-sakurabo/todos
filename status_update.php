<?php
require("dbconnect.php");

if($_SERVER ['REQUEST_METHOD'] === 'POST'){
    $id = FILTER_INPUT(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $status = FILTER_INPUT(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    if($id && $status){
        $status_update = "UPDATE todos SET status = ? WHERE id = ?";
        $stmt = $db->prepare($status_update);
        if (!$stmt) {
            die($db->error);
        }
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
    }
}
?>