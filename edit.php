<?php
require('dbconnect.php');

$errors = [];

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if(!$id){
    die("無効なIDです");
}

$sql_select = "SELECT * FROM todos WHERE id= ?";
$stmt = $db->prepare($sql_select);

if(!$stmt){
    die($db->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$todo = $result->fetch_assoc();

if (!$todo) {
    die("タスクが見つかりません。");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $start_date = filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_SPECIAL_CHARS);
    $end_date = filter_input(INPUT_POST,'end_date', FILTER_SANITIZE_SPECIAL_CHARS);
    $task = filter_input(INPUT_POST, 'task', FILTER_SANITIZE_SPECIAL_CHARS);
    $task_detail = filter_input(INPUT_POST, 'task_detail', FILTER_SANITIZE_SPECIAL_CHARS);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
}

if(empty($start_date) || empty($end_date) || empty($task) || empty($task_detail) || empty($status)) {
    $errors[]= "すべての項目を入力してください";
}

if (empty($errors)) {
    $sql_update = "UPDATE todos SET start_date = ?, end_date = ?, task = ?, task_detail = ?, status = ? WHERE id = ?;";
    $stmt = $db->prepare($sql_update);

    if (!$stmt) {
        die($db->error);
    }

    $stmt->bind_param("sssssi", $start_date, $end_date, $task, $task_detail, $status, $id);
    if ($stmt->execute()) {
        header('Location: edit_complete.php');
        exit;
    } else {
        $errors[] = $stmt->error;
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タスク管理アプリ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">タスク管理アプリ</a>
        </nav>
    </header>
    <main>
        <div class="register">
            <h1>タスク編集</h1>
            <form action="edit.php?id=<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>" method="post">
                <label for="start_date">開始日</label>
                <input type="date" name="start_date" id="start_date" value="<?php echo htmlspecialchars($todo['start_date'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <label for="end_date">終了日</label>
                <input type="date" name="end_date" id="end_date" value="<?php echo htmlspecialchars($todo['end_date'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <label for="task">タスク名</label>
                <input type="text" name="task" id="task" value="<?php echo htmlspecialchars($todo['task'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <label for="task_detail">タスク詳細</label>
                <input type="text" name="task_detail" id="task_detail" value="<?php echo htmlspecialchars($todo['task_detail'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <label for="status">ステータス</label>
                <select name="status" id="status" required>
                    <option value="未着手" <?php echo ($todo['status'] === "未着手") ? 'selected' : ''; ?>>未着手</option>
                    <option value="進行中" <?php echo ($todo['status'] === "進行中") ? 'selected' : ''; ?>>進行中</option>
                    <option value="完了" <?php echo ($todo['status'] === "完了") ? 'selected' : ''; ?>>完了</option>
                </select>

                <button type="submit" onclick="return confirm('更新してよろしいですか？');">更新する</button>
            </form>
        </div>
    </main>
</body>