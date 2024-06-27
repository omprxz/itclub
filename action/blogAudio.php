<?php
require('conn.php');
$response = [];
$response['status'] = 'error';
$response['message'] = 'No reason';

function saveAudio($mysqli, $audioUrl, $blogId) {
  
    $destinationFolder = '../blogs/audio/blogs/';
    $destinationFile = $destinationFolder . $blogId . '.mp3';

    $ch = curl_init($audioUrl);
    $fp = fopen($destinationFile, 'w');

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    if (file_exists($destinationFile)) {
        return "Audio file saved successfully.";
    } else {
        return "Error saving audio file.";
    }
}

function convertToSpeech($mysqli, $blog_Id) {
    $query = "SELECT content FROM blogs WHERE id = $blog_Id";
    $result = mysqli_query($mysqli, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $blogContent = $row['content'];
        $blogContent = html_entity_decode($blogContent);
        $speakableText = strip_tags($blogContent);
        return $speakableText;
    } else {
        $response['status'] = 'error';
        $response['message'] = "Error: " . mysqli_error($mysqli);
        echo(json_encode($response));
        exit();
    }
}

if ($_POST['action'] == 'checkAudio') {
  $blogid = $_POST['blogid'];
  $audioPath = "../blogs/audio/blogs/$blogid.mp3";
  if (file_exists($audioPath)) {
    $response['status'] = 'success';
    $response['message'] = '';
    $response['filename'] = "$blogid.mp3";
    echo(json_encode($response));
    exit();
  } else {
    $chkAud = mysqli_query($mysqli, "select * from blogAudioStatus where blogid = '$blogid' order by timestamp desc limit 1");
    if (mysqli_num_rows($chkAud) > 0) {
      while ($row = mysqli_fetch_assoc($chkAud)) {
        $genid = $row['genid'];
        $audioId = $row['audioid'];
        $status = $row['status'];
        $audiourl = $row['audiourl'];
      }
      if ($status == 'processing') {
        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => "https://large-text-to-speech.p.rapidapi.com/tts?id=$audioId",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: large-text-to-speech.p.rapidapi.com",
		"X-RapidAPI-Key: 1d66fdaccamsh21e456368563e8cp18ac22jsnd3f8b6ee1313",
		"content-type: application/json"
	],
        ]);
        $resp = curl_exec($curl);
        $resp = json_decode($resp, true);
        $err = json_decode(curl_error($curl), true);
        curl_close($curl);
        if ($err) {
          $response['status'] = 'error';
          $response['message'] = "cURL Error #:" . $err;
          echo(json_encode($response));
          exit();
        } else {
          /*{
            "id": "",
            "status": "success",
            "url": "",
            "job_time": 0
          } */
          if ($resp['status'] == 'success') {
            $audUrl = $resp['url'];
            saveAudio($mysqli, $audUrl,$blogid);
            $updSql = mysqli_query($mysqli, "update blogAudioStatus set status = 'success', audiourl = '$audUrl'  where audioid = '$audioId'");
            $response['status'] = 'success';
            $response['filename'] = "$blogid.mp3";
            echo(json_encode($response));
            exit();
          } else {
            $response['status'] = 'processing';
            $response['message'] = '';
            $response['id'] = $resp['id'];
            $response['eta'] = $resp['eta'];
            echo(json_encode($response));
            exit();
          }
        }

      } elseif ($status == 'success') {
         saveAudio($mysqli, $audiourl,$blogid);
        $response['status'] = 'success';
        $response['filename'] = "$blogid.mp3";
        echo(json_encode($response));
        exit();
      }

    } else {
      
      $speakableText = convertToSpeech($mysqli, $blogid);
      //Request Api
      
$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://large-text-to-speech.p.rapidapi.com/tts",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => json_encode([
		'text' => $speakableText
	]),
	CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: large-text-to-speech.p.rapidapi.com",
		"X-RapidAPI-Key: 1d66fdaccamsh21e456368563e8cp18ac22jsnd3f8b6ee1313",
		"content-type: application/json"
	],
]);

$resp = curl_exec($curl);
$resp = json_decode($resp, true);
$err = json_decode(curl_error($curl), true);

curl_close($curl);
      
      if ($err) {
        $response['status'] = 'error';
        $response['message'] = "cURL Error #:" . $err;
        echo(json_encode($response));
        exit();
      } else {
        if ($resp['status'] == 'success') {
          $audio_id=$resp['id'];
           $insSql = mysqli_query($mysqli, "insert into blogAudioStatus (blogid,audioid,status,audiourl) values ('$blogid','$audio_id','success', '$audiourl')");
          saveAudio($mysqli, $resp['url'],$blogid);
          $response['status'] = 'success';
          $response['filename'] = "$blogid.mp3";
          echo(json_encode($response));
          exit();
        } elseif($resp['status'] == 'processing') {
          $audio_id=$resp['id'];
           $insSql = mysqli_query($mysqli, "insert into blogAudioStatus (blogid,audioid,status) values ('$blogid','$audio_id','processing')");
          $response['status'] = 'processing';
          $response['message'] = '';
          $response['id'] = $resp['id'];
          $response['eta'] = $resp['eta'];
          echo(json_encode($response));
          exit();
        }
      }

    }
  }

} elseif($_POST['action'] == 'saveAudio') {
  $audio_url = $_POST['audioUrl'];
  $blogid = $_POST['blogid'];
  saveAudio($mysqli, $audio_url, $blogid);
  
  $response['status'] = 'success';
     $insSql = mysqli_query($mysqli, "insert into blogAudioStatus (blogid,audioid,status,audiourl) values ('$blogid','$audio_id','success','$audiourl')");
    $response['filename'] = "$blogid.mp3";
     $audio_id=$resp['id'];
    echo(json_encode($response));
    exit();
}else{
  $response['status'] = 'error';
$response['message'] = 'Action not defined!';
echo(json_encode($response));
exit();
}

?>