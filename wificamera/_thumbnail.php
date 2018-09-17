<?php
/*
    画像ファイルのサムネイルを生成する。
    例 _thumbnail.php?name=upload/xxxxx.jpg
    uploaded_view.phpから <img src= > を通じて呼び出される。

    画像が増えてくると、リアルタイムでのサムネイル作成よりも、
    画像アップロード時にサムネイル画像を準備したほうがサーバー負荷が減っていいかも。
*/

//サムネイルの大きさ
$thumb_width = 320;
$thumb_height = 240;

$err = false;
if(!isset($_GET['name'])) {
    $err = true;
} else {
    $original_file = $_GET['name'];

    $arr = explode('.', $original_file);
    $ext = strtoupper(end($arr));

    //jpegファイルのみに対応
    if($ext !== 'JPG' && $ext !== 'JPEG') {
        $err = true;
    } else {
        if(!file_exists($original_file)) {
            $err = true;
        } else {
            //サムネイル生成
            list($original_width, $original_height) = getimagesize($original_file);
            $thumb_height = round( $original_height * $thumb_width / $original_width );
            $original_image = imagecreatefromjpeg($original_file);
            $thumb_image = imagecreatetruecolor($thumb_width, $thumb_height);
            imagecopyresized($thumb_image, $original_image, 0, 0, 0, 0,
                            $thumb_width, $thumb_height,
                            $original_width, $original_height);
        }
    }
}
if($err) {
    //エラーの場合は適当な無地画像を返す
    $thumb_image = imagecreatetruecolor($thumb_width, $thumb_height);
    ImageFilledRectangle(
        $thumb_image,
        0,0, thumb_width,thumb_height,
        ImageColorAllocate($thumb_image, 0xcc, 0xcc, 0xcc));
}

header ('Content-Type: image/png');
imagepng($thumb_image);

imagedestroy($original_image);
imagedestroy($thumb_image);
