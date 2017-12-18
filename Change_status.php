<?php
header('Content-Type: text/html; charset=UTF-8');


// ステータスがPOSTされたときに、以下を実行する
if ((isset($_POST["status"]) && ($_POST["status"] != ""))) {
    $status = $_POST["status"];	//POSTのデータを変数$statusに格納
    $place = $_POST["place"];	//placeのデータを変数$statusに格納

    /* データベースにPDO形式で接続するための変数を定義 */
    $url = parse_url(getenv('DATABASE_URL'));
    $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
    $sql = 'UPDATE tbl_toilet SET status = ? WHERE place = ?';
    try {
        /* リクエストから得たスーパーグローバル変数をチェックするなどの処理 */
        /* mb_substrはなぜかherokuで適用されないので保留 */
        /* ステータス */
        if( get_magic_quotes_gpc() ) { $status = stripslashes("$status"); } 	//クォートをエスケープする
        $status = htmlspecialchars ($status); 	                                //HTMLタグを禁止する
        //$status = mb_substr ($status, 0, 5, 'UTF-8'); 		                    //データを5文字でカット
        /* 場所 */
        if( get_magic_quotes_gpc() ) { $place = stripslashes("$place"); } 	    //クォートをエスケープする
        $place = htmlspecialchars ($place); 	                                //HTMLタグを禁止する
        //$place = mb_substr ($place, 0, 2, 'UTF-8'); 		                    //データを2文字でカット
            
        // データベースに接続・更新
        $pdo = new PDO($dsn, $url['user'], $url['pass']);
        $stmt = $pdo -> prepare('UPDATE tbl_toilet SET status = ? WHERE place = ?');
        $stmt->bindValue(1, $status, PDO::PARAM_INT);
        $stmt->bindParam(2, $place, PDO::PARAM_STR);
        $stmt->execute();
        /* 本当は戻り値で条件判別できるらしいのだが、調べてない。。。。とりあえず */
        $result = "成功しました";

    } catch (PDOException $e) {

        // エラーが発生した場合は「500 Internal Server Error」でテキストとして表示して終了する
        // - もし手抜きしたくない場合は普通にHTMLの表示を継続する
        // - ここではエラー内容を表示しているが， 実際の商用環境ではログファイルに記録して， Webブラウザには出さないほうが望ましい
        //header('Content-Type: text/plain; charset=UTF-8', true, 500);
        //exit($e->getMessage()); 
        $result =  "失敗しました";

    } finally {
        // 接続を閉じる
        $pdo = null;
    }
}
        // Webブラウザにこれから表示するものがUTF-8で書かれたHTMLであることを伝える
        // (これか <meta charset="utf-8"> の最低限どちらか1つがあればいい． 両方あっても良い．)
        header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>

<html lang=ja>
    <head>
        <meta charset="utf-8">
        <title>ステータスの変更</title>
    </head>
    <body>
        <p><?php echo $result ?></p>
        <p><button onclick="history.back()">戻る</button></p>
    </body>
</html>