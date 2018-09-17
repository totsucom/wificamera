<?php
/*
    画像ファイルをGETリクエストで削除する。
    例 _delete_uploaded.php?name=upload/xxxxx.jpg ※実際のパラメーターはURLエンコードしています
    uploaded_view.phpから $.get() を通じて呼び出される。
    このモジュールは成功で 'OK'、失敗でエラーメッセージをテキスト形式で返します。
 */

if(!isset($_GET['name'])) {
    echo 'ファイルが指定されていません';
    exit;
}

$original_file = $_GET['name'];
$arr = explode('.', $original_file);
$ext = strtoupper(end($arr));

if($ext !== 'JPG' && $ext !== 'JPEG') {
    echo '削除できるのはjpegファイルのみです';
    exit;
}

if(!file_exists($original_file)) {
    echo 'OK';
    exit;
}

if(!unlink($original_file)) {
    echo '削除できませんでした';
    exit;
}

echo 'OK';
