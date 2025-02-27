<?php
require('dbconnect.php');

$status = $_POST['status'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$task = $_POST['task'] ?? '';
$params = [];
$types = "";

$sql_select = 'SELECT * FROM todos where 1=1';

if (!empty($status)){
    $sql_select .= ' and status = ?';
    $params[] = $status;
    $types .= 's';
}

if (!empty($start_date)){
    $sql_select .= ' and start_date >= ?';
    $params[] = $start_date;
    $types .= 's';
}

if (!empty($end_date)){
    $sql_select .= ' and end_date <= ?';
    $params[] = $end_date;
    $types .= 's';
}

if (!empty($task)){
    $sql_select .= ' and task like ?';
    $params[] = "%$task%";
    $types .= 's';
}



$stmt = $db->prepare($sql_select);
if($params){
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$todos = $result->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タスク管理アプリ</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">

</head>
<body>
    <header>
        <nav>
            <a href="index.php">タスク管理アプリ</a>
        </nav>
    </header>
    <main>
        <div class="home">
            <div class="products">
                <h1>タスク一覧</h1>
                <div class="products-ui">
                    <div class="register_area">
                        <a href="register.php" class="btn">タスク登録</a>
                    </div>
                    <div class="search_area">
                        <form method="POST" action="index.php">
                            <label for="status">ステータス:</label>
                            <select id="status" name="status">
                                <option value="">全て</option>
                                <option value="未着手" <?php echo $status === "未着手" ? 'selected' : '' ?>>未着手</option>
                                <option value="進行中" <?php echo $status === "進行中" ? 'selected' : '' ?>>進行中</option>
                                <option value="完了" <?php echo $status === "完了" ? 'selected' : '' ?>>完了</option>
                            </select>

                            <label for="task">タスク名:</label>
                            <input type="text" id="task" name="task" value="<?php echo htmlspecialchars($task, ENT_QUOTES) ?>">
                            <label for="start_date">開始日:</label>
                            <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date, ENT_QUOTES) ?>">

                            <label for="end_date">終了日:</label>
                            <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date, ENT_QUOTES) ?>">

                            <button type="submit">検索</button>
                        </form>
                    </div>
                    <div class="sort_area">
                        <label for="sort_column">並び順:</label>
                        <select id="sort_column">
                            <option value="start_date">開始日</option>
                            <option value="end_date">終了日</option>
                        </select>

                        <label for="sort_order"></label>
                        <select id="sort_order">
                            <option value="asc">古い順</option>
                            <option value="desc">新しい順</option>
                        </select>

                        <button id="sort_button">並び替え</button>
                    </div>
                </div>
            </div>
            <table class="products-table">
                <tr>
                    <th>開始日</th>
                    <th>終了日</th>
                    <th>タスク名</th>
                    <th>タスク詳細</th>
                    <th>ステータス</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
                <?php foreach ($todos as $todo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(date("Y年m月d日", strtotime($todo['start_date'])), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars(date("Y年m月d日", strtotime($todo['end_date'])), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($todo['task'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($todo['task_detail'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <select class="status-select" data-id="<?php echo $todo['id']; ?>">
                                <option value="未着手" <?php echo ($todo['status'] === "未着手") ? 'selected' : ''; ?>>未着手</option>
                                <option value="進行中" <?php echo ($todo['status'] === "進行中") ? 'selected' : ''; ?>>進行中</option>
                                <option value="完了" <?php echo ($todo['status'] === "完了") ? 'selected' : ''; ?>>完了</option>
                            </select>
                        </td>
                        <td class="edit"><a href="edit.php?id=<?php echo urlencode($todo['id']) ?>">✎</a></td>
                        <td class="delete"><a href="delete.php?id=<?php echo urlencode($todo['id']) ?>" onclick="return confirm('本当に削除しますか？');">×</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </main>
    <footer>
        <p class="copyright">&copy; 2025 タスク管理アプリ</p>
    </footer>
    <script src="js/sort.js"></script>
    <script src="js/status_update.js"></script>
</body>
</html>