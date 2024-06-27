<?php
include('action/conn.php');
include("action/generate_dataset.php");

$file_path = 'admin/site_stats.json';
$site_stats = file_exists($file_path) ? json_decode(file_get_contents($file_path), true) : ["page_views" => 0, "itclub_ai" => 0];
$site_stats["itclub_ai"]++;
file_put_contents($file_path, json_encode($site_stats));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ask IT Club AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="components/itclub_ai.css">
    <link rel="stylesheet" href="common.css">
</head>
<body>
  
    <?php include("header.html"); ?>
    
    <img src="img/itclublogo.webp" alt="" class="chat-app-prevImg" />
    <div class="chat-app">
        <div class="chat-app-main">
            <div class="chat-app-chats">
            </div>
            <div class="chat-app-msg-div">
              <div class="chat-app-msg">
                <textarea class="chat-app-prompt" oninput="auto_grow(this)" rows=1 placeholder="Ask me about IT Club..."></textarea>
                <button class="chat-app-voice">
                    <i class="fas fa-microphone"></i>
                </button>
                <button class="chat-app-send">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        <div class="chat-app-footer">
            Created & Trained by Omprakash. <br>
        </div>
            </div>
        </div>
        
    </div>
    
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
   <!-- <script src="eruda.js"></script> -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js"></script>
    <script src="components/itclub_ai.js"></script>
     <script type="importmap">
      {
      "imports": {
        "@google/generative-ai": "https://esm.run/@google/generative-ai"
      }
    }
  </script>
  <script type="module">
      import { GoogleGenerativeAI, HarmCategory, HarmBlockThreshold, } from "@google/generative-ai";
var dataset, prompt, promptDataset, msgId = 0, chatid, serverMsgId, promptHistory = [];
let markingP = "🖋️";
$(document).ready(function() {
  const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 2000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter',
      Swal.stopTimer)
    toast.addEventListener('mouseleave',
      Swal.resumeTimer)
  }
})

  $.ajax({
    url: "components/dataset.txt",
    method: "post",
    dataType: "text",
    async: false,
    success: function(data) {
      dataset = data;
    },
    error: function(xhr, status, error) {
      console.error("Error loading dataset:", status, error);
      dataset=9999
    }
  });
  
  const API_KEY = "AIzaSyDUeSSKD2IarvZElf-Dv8nHfxoKJPNk80w" ;
  const genAI = new GoogleGenerativeAI(API_KEY);

 const promptDataset = `You are the Ask IT Club AI, designed to provide insightful and elegant answers based on unstructured raw data about the IT Club. You are created, built, trained and designed by Omprakash aka Om from CSE (2022-25). Your responses should relate each question to the provided data, maintaining a descriptive yet concise style. And if user asks anything about New Government Polytechnic Patna 800013 or NGP Patna or NGP or NGPP then give them information from your knowledge database or internet about this college ( New Government Polytechnic Patna - 800013 ). If the data is insufficient for a question then find it from your knowledge database, or apologize and state that the AI isn't trained enough for such queries.
Raw Data: ${dataset}
Instructions (MUST & IMOORTANT):
1. Analyze the entire data for each question.
2. Craft responses in an elegant and slightly varied manner, avoiding word-for-word repetition from the raw data.
3. Must use HTML tags such as <p>, <b>, <u>, <a>, <ul>, <ol>, <li>, <mark>, <i>, <table> etc., for formatting responses.
4. Keep the responses of best appropriate length.
5. Include an apology with an appropriate HTML-formatted message when the question is out of given data or not related to given data. - "Sorry, IT Club AI isn't trained enough to answer this type of question."
6. Replace all double asterik "**" or "*" with html bold tags (<b>) of response.
7. If any kind of list or items numbering then use html ul, ol tags.
8. Replace normal markdowns with html tags and beautify response with html tags.
9. If anyone asks anything about who build you or trained you then tell about Om (from CSE 2022-25) and he is also a 3rd induction website maintaining member in itclub as name of Omprakash Kumar. his roll no is 2.
10. Must provide the output or response in html tags format not in markdown.
11. If users asks for any kind of images like gallery, members, notices, events or blogs then find from dataset give it in 'img' tag or if asked about any members, events, notices or blogs then just try to include respective images.
`;

 const model = genAI.getGenerativeModel({
      model: "gemini-1.5-flash",
      systemInstruction: promptDataset
    });
    
  const generationConfig = {
  temperature: 1,
  topP: 0.95,
  topK: 64,
  maxOutputTokens: 8192,
  responseMimeType: "text/plain",
};


async function sendMsg() {
  const question = $(".chat-app-prompt").val();
 
  if (question) {
    msgId++
    if(msgId == 1){
      promptHistory.push(
       { role: "user",
        parts: [
          {text: "who is om?"},
        ],
    },
       { role: "model",
        parts: [
          {text: "member along with current website which is this IT Club official website creator"},
        ],
    }
     );
      $(".chat-app-prevImg").css("opacity", "0.3")
      $(".chat-app-chats").html(createMessageElement(question, msgId));
      $.ajax({
        url: 'action/saveaiprompts.php',
        dataType: 'json',
        type:'post',
        data: 'action=newchat&prompt='+question,
        success: function(data){
          if(data['status'] == 'success'){
            chatid = data.chatid
            serverMsgId = data.sMsgId
          }
        },
        error: function(error){
          //console.log(error)
        }
      })
    }else{
       $(".chat-app-chats").append(createMessageElement(question, msgId));
       $.ajax({
        url: 'action/saveaiprompts.php',
        dataType: 'json',
        type:'post',
        data: 'action=oldchat&chatid='+chatid+'&prompt='+question,
       success: function(data){
          if(data['status'] == 'success'){
            chatid = data.chatid
            serverMsgId = data.sMsgId
          }
        },
        error: function(error){
          //console.log(error)
        }
      })
    }
    
    var typingDots = 1;
    var typingInt = setInterval(function(){
      typingDots++
      if(typingDots>3){
        typingDots=1;
      }
      let dots = ".".repeat(typingDots)
      $("#response"+msgId).text(dots+markingP)
    }, 500)
   
    const chatSession = model.startChat({
    generationConfig,
    history: promptHistory,
  });

    $(".chat-app-send").attr("disabled", "disabled");
    $(".chat-app-send i").toggleClass("animateSend");
  setTimeout(() => {
    $(".chat-app-send i").toggleClass("animateSend");
  }, 2000)
    $(".chat-app-prompt").val("");
    const result = await chatSession.sendMessageStream(question);
    clearInterval(typingInt)
    var chunkText = '';
    $("#response"+msgId).html(chunkText);
    for await (const chunk of result.stream){
      chunkText += chunk.text()
      $("#response"+msgId).html(chunkText);
    }
    const response = await result.response;
    let answer = response.text();
    $.ajax({
      url: "action/saveaiprompts.php",
      type: "post",
      dataType:"json",
      data: {action: "saveresponse", msgId: serverMsgId,response: answer},
      error: function(err){
        //console.log(err)
      }
    })
    $(".chat-app-send").removeAttr("disabled")
    $("#msg-div-"+msgId+" .copy-response").show()
    promptHistory.push(
      {
      role: "user",
      parts: [
          {text: question},
        ],
    },
      {
      role: "model",
      parts: [
          {text: answer},
        ],
    }
    )
  }
  else{
    Toast.fire({
      icon: 'warning',
      title: 'Empty question'
    })
  }
}

function createMessageElement(userMsg, msgId) {
    var msgDiv = $("<div>").addClass("msg-div").attr("id", "msg-div-"+msgId);

    var userDiv = $("<div>").addClass("user");
    var userMsgElement = $("<p>").addClass("msg").text(userMsg);

    userDiv.append(userMsgElement);
    msgDiv.append(userDiv);

    var aiDiv = $("<div>").addClass("ai");
    var aiMsgElement = $("<p>").addClass("msg").attr("id", "response"+msgId).text("."+markingP);

    var copyResponseElement = $("<p>").addClass("copy-response");
    var copyIcon = $("<i>").addClass("far fa-copy").attr("data-clipboard-target", "#response" + msgId);
    var tooltipSpan = $("<span>").addClass("tooltiptext").text("Copy Response");

    copyResponseElement.append(copyIcon);
    copyResponseElement.append(tooltipSpan);
    copyResponseElement.hide()

    aiDiv.append(aiMsgElement);
    aiDiv.append(copyResponseElement);
    msgDiv.append(aiDiv);

    return msgDiv;
  }


$(".chat-app-send").on("click", function () {
  sendMsg()
});

});
    </script>
    <script>
     $(document).ready(function() {
    let recognition;

    $('.chat-app-voice').click(function() {
        $('.chat-app-voice i').toggleClass('fa-beat-fade');

        if (recognition && recognition.abort) {
            recognition.stop();
            recognition = null;
        } else {
            recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
            recognition.lang = 'en-US';
            recognition.interimResults = false;

            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                $('.chat-app-prompt').append(' ' + transcript);
                recognition=null
            };

            recognition.onerror = function(event) {
                console.error('Speech recognition error detected: ' + event.error);
                $('.chat-app-voice i').removeClass('fa-beat-fade');
                recognition=null
            };

            recognition.onend = function() {
                console.log('Speech recognition ended');
                $('.chat-app-voice i').removeClass('fa-beat-fade');
                recognition=null
            };

            recognition.start();
        }
    });
});
    </script>
   
</body>
</html>