<!DOCTYPE html>
<html lang = "ja">

<head>
<meta charset="UTF-8">
</head>

<?php
$delete = $_POST['delete'];//POSTのデータを変数$deleteに格納
$edit = $_POST['edit'];//POSTのデータを変数$editに格納
$textbox = $_POST['textbox'];//POSTのデータを変数$editnumに格納
$pass1 = $_POST['pass1'];//パスワード
$pass2 = $_POST['pass2'];//パスワード
$pass3 = $_POST['pass3'];//パスワード
$name = $_POST['name']; //POSTのデータを変数nameに格納
$comment = $_POST['comment']; //POSTのデータを変数$commentに格納
$time = date('Y')."年".date('m月d日 H:i:s');
$filename = 'mission_2-1_tatami.txt'; //新規のtxtファイルを開く
$num = count(file($filename));//$num ＝ count関数を使って配列の個数を数える(ファイルを1行ごとに配列に入れる）
$num++;//投稿番号を取得（インクリメント演算子$num++は、$num = $num + 1と同じ意味）



//編集対象番号に番号が入力されたら & パスワードが一致したら
if(!empty($_POST['edit'])){
	$editCon = file('mission_2-1_tatami.txt');
	$fp = fopen('mission_2-1_tatami.txt', 'a');//ファイルをオープン
		for($j=0; $j<count($editCon); $j++){//ループ
		$editline = explode("<>", $editCon[$j]);//投稿番号を取得(<>で区切った文字列を配列にする)
			if($editline[0] == $edit && $editline[4] == $pass3){//投稿番号($editline[0])が編集したい番号($edit)と一致したら
				$edit_name = $editline[1];
				$edit_comment = $editline[2];
			}elseif($editline[0] == $edit && $editline[4] != $pass3){
				echo "パスワードが違います。";
			}
		}
	fclose($fp);
}

//名前とコメントの処理
if(isset($_POST['name']) && isset($_POST['comment'])){ //もしPOST(変数)にnameとcomment(値)があれば

	//(編集モード)テキストボックスに値があったら & パスワードが入力されたら 
	if(!empty($_POST['textbox']) && isset($_POST['pass1'])){
		if(file_exists($filename)){//もしファイルがあったら
		$editCon = file('mission_2-1_tatami.txt');
		$fp = fopen('mission_2-1_tatami_a.txt', 'a');//ファイルをオープン
			foreach($editCon as $array){//ループ
			$editline = explode("<>", $array);
				if($editline[0] != $textbox){//投稿番号がテキストボックスの値と一致しなかったら(編集モード)
						$fp = fopen('mission_2-1_tatami_a.txt', 'a');//ファイルをオープン
						fwrite($fp, $array);//入力フォームから送信された値と差し替える(上書き)
						fclose($fp);
						
				}else{//投稿番号がテキストボックスの値と一致したら
					$fb = $textbox."<>".$_POST['name']."<>".$_POST['comment']."<>".$time."<>".$_POST['pass1']."<>"."\n";
		  			$fp = fopen('mission_2-1_tatami_a.txt', 'a'); //ファイルをオープン
					fwrite($fp, $fb);
					fclose($fp);
		                }
		         }
		}
	 rename('mission_2-1_tatami_a.txt','mission_2-1_tatami.txt');//ファイルを移動する
	}
	elseif(empty($_POST['textbox']) && isset($_POST['pass1']) && $editline[4] != $pass3){//テキストボックスが空（新規投稿）& パスワードが一致しなかったら
		echo "パスワードが違います。";
 	}
	else{//テキストボックスが空のとき⇒新規投稿扱い
		if(isset($_POST['pass1'])){//パスワードがあったら
		$fa = $num."<>".$_POST['name']."<>".$_POST['comment']."<>".$time."<>".$_POST['pass1']."<>"."\n";
		$fp = fopen('mission_2-1_tatami.txt', 'a'); //ファイルをオープン
		fwrite($fp, $fa);
		fclose($fp);
		}
	}
}

//削除対象番号に番号が入力されたら
if(isset($_POST['delete'])){
	$delCon = file('mission_2-1_tatami.txt');//file関数を使って、ファイルの内容行ごとにを配列に格納
	$fp = fopen('mission_2-1_tatami_a.txt', 'a'); //ファイルをオープン
		for($i=0; $i<count($delCon); $i++){//count($delCon) = 配列の中に何個項目入ってんのー？
		$delline = explode("<>", $delCon[$i]);//<>で区切った文字列を配列にする
			if($delline[0] == $delete){//投稿番号($delline[0])が消したい番号($delete)と一致したら
				if($delline[4] == $pass2){//パスワードが一致したら
					//消す
				}
				else{//パスワードが一致しなかったら
					fwrite($fp, $delCon[$i]);//書き込む
					echo "パスワードが違います。";
				}
			}else{//一致しなかったら書き込む
	  			fwrite($fp, $delCon[$i]);//書き込む　fwrite(書き込む所,書き込む中身)
			}
		}
	fclose($fp);
rename('mission_2-1_tatami_a.txt','mission_2-1_tatami.txt');//ファイルを移動する
}

?>

<body>

<form action = "mission_2-5.php" method = "post">
<input type = "text" name = "name" placeholder = "名前" value = "<?php echo $edit_name; ?>" size = "20"/><br>
<input type = "text" name = "comment" placeholder = "コメント" value = "<?php echo $edit_comment; ?>" size = "20"/><br>
<input type = "password" name = "pass1" placeholder = "パスワード" size = "20"/>
<input type = "submit" value = "送信" size = "20"/>
<input type = "hidden" name = "textbox" value = "<?php if(!empty($_POST['edit'])){ echo $_POST['edit'];} ?>"size = "20"/>
</form>
<br>
<form action = "mission_2-5.php" method = "post">
<input type = "text" name = "delete" placeholder = "削除対象番号" size = "20"/><br>
<input type = "password" name = "pass2" placeholder = "パスワード" size = "20"/>
<input type = "submit" value = "削除" size = "20"/>
</form>
<br>
<form action = "mission_2-5.php" method ="post">
<input type = "text" name = "edit" placeholder = "編集対象番号" size = "20"/><br>
<input type = "password" name = "pass3" placeholder = "パスワード" size = "20"/>
<input type = "submit" value = "編集" size = "20"/>
</form>
</body>

<?php
//ブラウザに表示
if(file_exists($filename)){
	//file関数を使って、ファイルの内容を配列に格納
	$array = file("mission_2-1_tatami.txt");
 
	//配列のデータをループで処理(表示)
	foreach($array as $value){//foreach($配列 as $一時格納する変数(仮変数))
	
	$array_disp = $array = explode("<>",$value);//文字列を分割 explode("区切り文字","文字列")
	echo $array_disp[0]." ".$array_disp[1]." ".$array_disp[2]." ".$array_disp[3]."<br>\n";// 改行しながら値を表示
	}
}
?>

</html>