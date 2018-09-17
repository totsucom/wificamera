<!-- アップロードされた写真を表示、ダウンロード、削除できるアルバムページ -->
<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="utf-8"/>
  <meta name="viewport" content="width=320, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no"><!--スマホ タブレット対応-->
  <meta http-equiv="X-UA-Compatible" content="IE=11" /><!-- IEバージョン制限を引き上げる -->
  <script src="../jquery/1.9.1/jquery.min.js"></script>
  <style>
    body{
      width: 100%;
      text-align: center;
    }
    a img{ border-style: none; } /*IEの場合リンク枠で画像が囲まれてダサい*/
    .frame{
      width: 450px;
      display: inline-block;
    }
    ul {
      list-style: none;
    }
  	div.left{
	    text-align: left;
	    float: left;
	  }
	  div.right{
    	text-align: right;
	  }
    .clear-element {
      clear: both;
    }
    img.tool-icon{
      width: 30px;
      margin-left:10px;
      margin-bottom: 30px;
    }
    img.delete{
      width: 30px;
      margin-left:10px;
      margin-bottom: 5px;
    }
    button.delete{
      margin-left:10px;
      margin-bottom: 5px;
    }
  </style>
</head>
<body>
  <div class="frame">
  <div class="left" style="margin-right: 30px;"><a href="../index.html"><img src="../sozai/home24.png"></a></div>
  <div class="left"><a href="javascript:refresh();"><img src="../sozai/reload.png"></a></div>
  <div class="right"><a href="./index.php"><img src="../sozai/camera.png"></a></div>
  <h2>アルバム</h2>
<?php
  date_default_timezone_set('Asia/Tokyo'); //表示時間ズレ対応

  //ファイル一覧と最終更新時間を配列に記憶
  $dir = './uploaded';
  $files = array();
  if ($handle = opendir($dir)) {
      while (false !== ($file = readdir($handle))) {
          if ($file != "." && $file != "..") {
              $files[filemtime($dir.'/'.$file)] = $dir.'/'.$file;
          }
      }
      closedir($handle);
  }
  //更新日付の新しい順にソート
  krsort($files);

  //一覧画面のHtmlを生成
  echo "<ul>\r\n";
  foreach($files as $mtime => $file) {
    echo '<li>';

    //echo htmlspecialchars($file),'<br>';
    echo '<br>',date("Y/m/d H:i", $mtime),'<br>';
    echo '<div class="left"><a href="',$file,'"><img src="./_thumbnail.php?name=',urlencode($file),'"></a></div>';
    echo '<a href="',$file,'" download="',basename($file),"\" onclick=\"javascript: downloadFile('",$file,"','",basename($file),"'); return false;\"><img class=\"tool-icon\" src=\"../sozai/download-button.png\"></a><br>";
    echo '<img class="delete" src="../sozai/delete.png">';
    echo '<button class="delete" data-path="',$file,'" style="display:none;">削除する</button>';
    echo '<div class="tool_msg"></div>';
    echo '<div class="clear-element"></div>';

    echo "</li>\r\n";
  }
  echo "</ul>\r\n";
?>
</div>

<script>
  $(function(){

    //ゴミ箱アイコンをクリックすると
    $('img.delete').on('click', function(e){
      //「削除する」ボタンが現れる
      $(this).next('button.delete').toggle();
    });

    //「削除する」ボタンをクリックすると
    $('button.delete').on('click', function(e){
      var li = $(this).parent('li');
      var tool_msg = $(this).next('div.tool_msg');
      tool_msg.html('削除中...');

      //getリクエストでファイルの削除依頼をする
      $.get("./_delete_uploaded.php", { name: $(this).attr('data-path') },
        function(data){
          //削除結果を画面に表示
          tool_msg.html(data);
          if(data === 'OK'){
            //成功したら画面上でも消す（この場合はすぐに消えるので、削除結果は見えない）
            li.remove();
          }
        });
    });
  });

  //更新アイコンをクリックした。F5と同じ
  function refresh() {
	  window.location.reload();
  }

  //ダウンロードアイコンをクリックしたら、画像ファイルをダウンロードさせる
  function downloadFile(url, filename) {
    "use strict";

    // XMLHttpRequestオブジェクトを作成する
    var xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);
    xhr.responseType = "blob"; // Blobオブジェクトとしてダウンロードする
    xhr.onload = function (oEvent) {
      // ダウンロード完了後の処理を定義する
      var blob = xhr.response;
      if (window.navigator.msSaveBlob) {
        // IEとEdge
        window.navigator.msSaveBlob(blob, filename);
      }
      else {
        // それ以外のブラウザ
        // Blobオブジェクトを指すURLオブジェクトを作る
        var objectURL = window.URL.createObjectURL(blob);
        // リンク（<a>要素）を生成し、JavaScriptからクリックする
        var link = document.createElement("a");
        document.body.appendChild(link);
        link.href = objectURL;
        link.download = filename;
        link.click();
        document.body.removeChild(link);
      }
    };
    // XMLHttpRequestオブジェクトの通信を開始する
    xhr.send();
  }
</script>
</body>
</html>