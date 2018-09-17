<!-- 写真撮影または画像選択→プレビュー→アップロードする、カメラ機能Webページ -->
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
    div.blue {
      color: blue;
    }
    div.red {
      color: red;
    }
    div.frame {
      width: 400px;
      display: inline-block;
    }
  	div.left {
	    text-align: left;
	    float: left;
	  }
    div.right{
      text-align: right;
    }
    #preview {
      max-width: 100%;
      height: auto;
      margin-top: 40px;
    }
    div.center {
      text-align : center ;
    }

    /*ファイル選択ボタンはダサい。「写真選択または撮影する」ボタンをデコ*/
    label#label2 > input {
      display:none;
    }
    label#label2 {
      color: #FFFFFF;
      background-color: #2E64FE;
      padding: 10px;
      border: double 4px #CCCCCC;
    }

    /*送信ボタンはダサい。「アップロード！」ボタンをデコ*/
    label#label3 > input {
      display:none;
    }
    label#label3 {
      display:none;
      color: #2A1B0A;
      background-color: #FE9A2E;
      padding: 10px;
      border: double 4px #CCCCCC;
    }

  </style>
</head>
<body>
<?php
//アップロード処理
if(isset($_FILES["upfile2"])) {
  if (is_uploaded_file($_FILES["upfile2"]["tmp_name"])) {
    	if (move_uploaded_file ($_FILES["upfile2"]["tmp_name"], "./uploaded/" .date("Ymd-His") . $_FILES["upfile2"]["name"])) {
      	chmod("./uploaded/" . date("Ymd-His") . $_FILES["upfile2"]["name"], 0644);
      	echo $_FILES["upfile2"]["name"] . '<div class="blue">アップロードしました</div><hr>';
    } else {
      	echo '<div class="red">アップロードできませんでした</div><hr>';
    }
  } else {
    echo '<div class="red">アップロードできませんでした</div><hr>';
  }
}
?>

<div class="frame">
  <div class="left"><a href="../index.html"><img src="../sozai/home24.png"></a></div>
  <div class="right"><a href="./uploaded_view.php"><img src="../sozai/album.png"></a></div>
  <h2>カメラ</h2>
  </div>

  <form action="" method="POST" enctype="multipart/form-data">
    <div class="center">
      <label for="file2" id="label2">写真選択または撮影する<input type="file" name="upfile2" id="file2" accept="image/*" capture="camera" /></label>
      <label for="file3" id="label3">アップロード！<input type="submit" id="file3" value="アップロード"></label>
    </div>
  </form>
  <img src="" id="preview" style="display:none;">

<script>
$(function(){

    //ファイルが選択されたか、写真が撮影されたとき
    $('#file2').change(
        function(){
            if(!this.files.length) {
                return;
            }
            var file = $(this).prop('files')[0];
            var fr = new FileReader();
            fr.onload = function() {
                //プレビュー画像を表示
                $('#preview').attr('src', fr.result).css('display','inline');
                //アップロード！ボタンを表示
                $('#label3').show();
            }
            fr.readAsDataURL(file);
        }
    );
});
</script>
</body>
</html>