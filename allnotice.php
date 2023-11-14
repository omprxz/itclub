<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Notices - IT Club</title>
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="common.css" type="text/css" media="all" />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>

    .notices {
      padding: 15px;
      border-bottom: 1px solid grey;
      border-radius: 25px;
    }

    .noticeLabel {
      text-align: center;
      color: var(--six-color);
      margin-bottom: 0;
      font-weight: 600;
      font-size: 25px;
    }

    .noticeList {
      list-style: none;
      line-height: 28px;
      padding: 8px 15px 8px 15px;
      margin-bottom: 5px;
    }

    .noticeList_item {
      border: 1px solid #a4a4a4a3;
      margin-bottom: 5px;
      border-radius: 2px;
      padding: 5px;
    }

    .noticeList_item a {
      color: var(--six-color);
      text-decoration: none;
    }

    .notices_viewallLabel {
      margin-top: 0;
      text-align: center;
      padding: 7px 10px;
      border: 1px solid #007ff0a3;
      border-radius: 3px;
      color: #007ff0a3;
      text-decoration: none;
    }

    p:has(> a.notices_viewallLabel) {
      text-align: center;
    }
  </style>
</head>

<body>
<?php include 'header.html'; ?>

  <div class="notices" data-aos-duration="1000" data-aos="fade-down">
    <p class="noticeLabel">All Notices</p>
    <ul class="noticeList"></ul>

  </div>

  <?php include 'footer.html'; ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
  <script>

    var notices = [];
    $(document).ready(function () {
      $.ajax({
        url: 'action/get_notices.php', // Replace with the correct path to your PHP script
        method: 'GET', // You can also use 'POST' if needed
        dataType: 'json', // Expect JSON response
        success: function (data) {
          console.log(data)
          // You can process the data and update your HTML here
          if(data.length>0){
          for (let z = 0; z < data.length; z++) {
            var subArr = [data[z]['notice_title'], data[z]['notice_id'], data[z]['notice_timestamp']];
            notices.push(subArr)
          }
          console.log(notices)
          var noticebox = ''

          function noticeBox_notices() {
            for (let i = 0; i < notices.length; i++) {
              var dateObj = new Date(notices[i][2]);
              var formattedDate = dateObj.toLocaleDateString("en-US", { day: "2-digit", month: "short", year: "numeric" });

              noticebox = '<li class="noticeList_item"><a href="notice.php?notice_id=' + notices[i][1] + '">' + notices[i][0] + ' &nbsp;(' + formattedDate + ') </a> </li>'
              $('.noticeList').append(noticebox)
            }
          }
          noticeBox_notices() }else{
          $('.notices').append('<p style="text-align:center;color:white;margin:10px;font-size:18px;">No any notice till now</p>')
      }
        },
        error: function (xhr, status, error) {
          // Handle any errors
          console.error(error);
        }
      });
    });

  </script>
</body>

</html>