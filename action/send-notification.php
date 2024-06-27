<?php
function sendication($title, $message, $url, $icon, $actionTitle, $actionUrl) {
  $apiKey = '29a6c1fb1bec7259deb5c0ec3331946c';
  $curlUrl = "https://api.pushalert.co/rest/v1/send";

  // POST variables
  $post_vars = array(
    "icon" => $icon,
    "title" => $title,
    "message" => $message,
    "url" => $url,
    "action1" => json_encode(array("title" => "$actionTitle", "url" => "$actionUrl"))
  );

  $headers = array();
  $headers[] = "Authorization: api_key=" . $apiKey;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $curlUrl);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_vars));
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);

  //echo("Title : $title, Msg: $message, Icon: $icon, Url: $url");
  $output = json_decode($result, true);
  //print_r($output);
  if ($output["success"]) {
    echo $output["id"]; // Sent notification ID
  } else {
    // Handle errors, if any
    echo json_encode(array("error" => "Failed to send ication."));
  }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = isset($_POST["title"]) ? $_POST["title"] : "";
  $message = isset($_POST["message"]) ? $_POST["message"] : "";
  $url = isset($_POST["url"]) ? $_POST["url"] : "";
  $icon = isset($_POST["icon"]) ? $_POST["icon"] : "";
  $actionTitle = isset($_POST["action1"]["title"]) ? $_POST["action1"]["title"] : "";
  $actionUrl = isset($_POST["action1"]["url"]) ? $_POST["action1"]["url"] : "";

  sendication($title, $message, $url, $icon, $actionTitle, $actionUrl);
}
?>