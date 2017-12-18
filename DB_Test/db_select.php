<?php
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$sql = "SELECT * FROM tbl_toilet";

try {
    /* リクエストから得たスーパーグローバル変数をチェックするなどの処理 */

    // データベースに接続
    $pdo = new PDO($dsn, $url['user'], $url['pass']);

    /* データベースから値を取ってきたり， データを挿入したりする処理 */
    $stmt = $pdo->query($sql);
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        $date = $row["date"];
        $place = $row["place"];
        $status = $row["status"];
    }

} catch (PDOException $e) {

    // エラーが発生した場合は「500 Internal Server Error」でテキストとして表示して終了する
    // - もし手抜きしたくない場合は普通にHTMLの表示を継続する
    // - ここではエラー内容を表示しているが， 実際の商用環境ではログファイルに記録して， Webブラウザには出さないほうが望ましい
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage()); 

}

// 接続を閉じる
$pdo = null;

// Webブラウザにこれから表示するものがUTF-8で書かれたHTMLであることを伝える
// (これか <meta charset="utf-8"> の最低限どちらか1つがあればいい． 両方あっても良い．)
header('Content-Type: text/html; charset=utf-8');

?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <title>heroku -> postgres 接続テスト</title>
    </head>
    <body>
    <p>date:<?php echo $date ; ?></p>
    <p>place:<?php echo $place ; ?></p>
    <p>status:<?php var_export($status) ; ?></p>

    </body>
</html>