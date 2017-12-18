<?php
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$sql = "SELECT * FROM tbl_toilet";

try {
    $pdo = new PDO($dsn, $url['user'], $url['pass']);
    $stmt = $pdo->query($sql);
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        $date = $row["date"];
        $place = $row["place"];
        $status = $row["status"];
    }

} catch (PDOException $e) {
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage()); 

}

$pdo = null;
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html lang=ja>
    <head>
        <meta charset="utf-8">
        <title>現在の使用状況</title>
    </head>

    <body onload="get_status();">
    <p><h1>1F</h1>現在の使用状況：</p>
    <p><img id="1F" name="1F" ></p>
    <!--<?php var_export($status); ?> -->
    <hr>
    <p><h1>2F</h1>現在の使用状況：現在サービス利用ができません。</p>
    <script>
        var status = '<?php var_export($status); ?>'
        function get_status(){
                if ( status == "false" ) {
                    document.getElementById("1F").src="image/available.png";
                    document.getElementById("1F").alt="空室";
                } else {
                    document.getElementById("1F").src="image/unavailable.png";
                    document.getElementById("1F").alt="使用中";
                }
        }
    </script>
    </body>
    
</html>