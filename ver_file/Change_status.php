<?php
header('Content-Type: text/html; charset=UTF-8');
// ステータスがPOSTされたときに、以下を実行する
echo "1F_status:" ;
echo htmlspecialchars($_POST['status']);
echo nl2br("\n"); // 改行
if ((isset($_POST["status"]) && ($_POST["status"] != ""))) {
    $status = $_POST["status"];	//POSTのデータを変数$statusに格納
    if( get_magic_quotes_gpc() ) { $status = stripslashes("$status"); } 	//クォートをエスケープする
        $status = htmlspecialchars ($status); 	//HTMLタグを禁止する
        $status = mb_substr ($status, 0, 30, 'UTF-8'); 		//長いデータを30文字でカット
        $file = fopen ("1F_status","w");  	 //書き込み用モードでデータを開く(上書き)
        //$file = fopen ("1F_status","w");  	 //書き込み用モードでデータを開く(追記)
        flock ($file, LOCK_EX); 	 //ファイルロック開始
        fputs ($file,$status);   	//書き込み処理
        flock ($file, LOCK_UN);  	  //ファイルロック解除
        fclose ($file); 		 //ファイルを閉じる
        echo "書き込みに成功しました";
    }else{
    echo "書き込みに失敗しました";
}
