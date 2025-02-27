<?php
require('dbconnect.php');

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$id) {
    echo 'タスクが正しく指定されていません';
    exit();
}

$sql_delete = "delete from todos where id=?";
$stmt = $db->prepare($sql_delete);
if (!$stmt) {
    die($db->error);
}

$stmt->bind_param('i', $id);
$success = $stmt->execute();
if (!$success) {
    die($db->error);
}

header('Location: delete_complete.php');
?>