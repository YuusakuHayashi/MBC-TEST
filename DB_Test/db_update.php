<?php
$place = "1F";
$status = "true";
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$sql = 'UPDATE tbl_toilet SET status = ? WHERE place = ?';

// データベースに接続
$pdo = new PDO($dsn, $url['user'], $url['pass']);
$stmt = $pdo -> prepare('UPDATE tbl_toilet SET status = ? WHERE place = ?');
$stmt->bindValue(1, $status, PDO::PARAM_INT);
$stmt->bindParam(2, $place, PDO::PARAM_STR);
$stmt->execute();