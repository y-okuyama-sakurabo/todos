<?php
require('dbconnect.php');

$errors = [];

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

if (empty($errors)){
$sql_insert = "INSERT INTO todos (start_date, end_date, task, task_detail, status)VALUES (?, ?, ?, ?, ?);";
$stmt = $db->prepare($sql_insert);
if (!$stmt) {
    die($db->error);
}

    $stmt->bind_param("sssss", $start_date, $end_date, $task, $task_detail, $status);
    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    }else {
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
            <h1>タスク登録</h1>
            <form action="register.php" method="post">
                <label for="start_date">開始日</label>
                <input type="date" name="start_date" id="start_date" value="<?php echo isset($start_date) ? htmlspecialchars($start_date) : ''; ?>" required>

                <label for="end_date">終了日</label>
                <input type="date" name="end_date" id="end_date" value="<?php echo isset($end_date) ? htmlspecialchars($end_date) : ''; ?>" required>

                <label for="task">タスク名</label>
                <input type="text" name="task" id="task" value="<?php echo isset($task) ? htmlspecialchars($task) : ''; ?>" required>

                <label for="task_detail">タスク詳細</label>
                <input type="text" name="task_detail" id="task_detail" value="<?php echo isset($task_detail) ? htmlspecialchars($task_detail) : ''; ?>" required>

                <label for="status">ステータス</label>
                <select name="status" id="status" required>
                    <option value="未着手" <?php echo (isset($status) && $status === "未着手") ? 'selected' : ''; ?>>未着手</option>
                    <option value="進行中" <?php echo (isset($status) && $status === "進行中") ? 'selected' : ''; ?>>進行中</option>
                    <option value="完了" <?php echo (isset($status) && $status === "完了") ? 'selected' : ''; ?>>完了</option>
                </select>

                <button type="submit">登録する</button>
            </form>
        </div>
    </main>
    <footer>
        <p class="copyright">&copy; タスク管理アプリ All rights reserved.</p>
    </footer>
</body>