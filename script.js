var notices = [];
$(document).ready(function () {
  $.ajax({
    url: 'action/get_notices.php',
    method: 'GET', 
    dataType: 'json', 
    success: function (data) {
      for (let z = 0; z < data.length; z++) {
        var subArr = [data[z]['notice_title'], data[z]['notice_id'], data[z]['notice_timestamp']];
        notices.push(subArr)
      }
      var noticebox = ''
      function noticeBox_notices() {
        for (let i = 0; i < 5; i++) {
          var dateObj = new Date(notices[i][2]);
          var formattedDate = dateObj.toLocaleDateString("en-US", { day: "2-digit", month: "short", year: "numeric" });

          noticebox = '<li class="noticeList_item"><a href="notice.php?notice_id=' + notices[i][1] + '">' + notices[i][0] + ' &nbsp;(' + formattedDate + ') </a> </li>'
          $('.noticeList').append(noticebox)
        }
        if (notices.length > 5) {
          $('.notices').append('<p><a class="notices_viewallLabel" href="allnotice.php">View All</a></p>')
        }
      }
      noticeBox_notices()
    },
    error: function (xhr, status, error) {
      console.error(error);
    }
  });
});

var apiKey=['AIzaSyDK1hzKDTP06T4s8tggxChoTRXctBRNPGA']
var folderId = '1-9hrUcjGda8MOESJamKkwdE4uI6M2vZz';
var imgLinks=[];
function updateImageSrc(link) {
  const fileId = link.match(/id=([^&]+)/);
  if (fileId && fileId[1]) {
      updatedLink = 'https://drive.google.com/uc?id=' + fileId[1];
      return updatedLink
  }
}
$.ajax({
  url: `https://www.googleapis.com/drive/v3/files?q='${folderId}'+in+parents&fields=files(webContentLink)&key=${apiKey[0]}`,
  method: 'GET',
  dataType: 'json',
  success: function (data) {
    for (let i = 0; i < data.files.length; i++) {
      imgLinks.push(updateImageSrc(data.files[i].webContentLink));
      $('.slidesContainer').append('<div class="mySlides"><img src="'+imgLinks[i]+'" style="width:100%"></div>')
    }
      gsinit();
    console.log(imgLinks);
  },
  error: function (error) {
    console.error(error);
  }
});


function gsinit(){
gallerySlider = new Siema({
  selector: '.slidesContainer',
  duration: 200,
  easing: 'ease-out',
  perPage: 1,
  startIndex: 0,
  draggable: true,
  multipleDrag: true,
  threshold: 20,
  loop: true,
  rtl: false,
  onInit: () => {},
  onChange: () => {},
})
setInterval(function(){gallerySlider.next()},5000)
}
$('.prev').click(function(){
  gallerySlider.prev()
})
$('.next').click(function(){
  gallerySlider.next()
})


function esinit(){
   eventSlider = new Siema({
    selector: '.eventSlider',
  duration: 200,
  easing: 'ease-out',
  perPage: 1,
  startIndex: 0,
  draggable: true,
  multipleDrag: true,
  threshold: 20,
  loop: true,
  rtl: false,
  onInit: () => {},
  onChange: () => console.log(eventSlider.currentSlide),
  });
}
  $('.eventNext').click(function(){
    eventSlider.next()
  })
  $('.eventPrev').click(function(){
    eventSlider.prev()
  })
$(document).ready(function() {
    $.ajax({
        url: 'action/fetch_events.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                var events = response.response;
                console.log(events)
                if (events.length === 0) {
                    $('.eventSlider').html('<p style="text-align:center;color:white;">No events found</p>');
                    $('.eventPrev,.eventNext').css('display','none')
                } else {
                    
                    var eventHTML = '';
                    for (var i = 0; i < events.length; i++) {
                        var event = events[i];
                        if (event.event_imgurl=='') {
                      event.event_imgurl='defaultEventImage.webp';
                    }
                        eventHTML += '<div class="event-wrapper">';
                        eventHTML += '<div class="event-banner-image"><img src="img/events/' + event.event_imgurl + '" alt="Event" /></div>';
                        eventHTML += '<h1>' + event.event_title + '</h1>';
                        eventHTML += '<div class="event-button-wrapper">';
                        eventHTML += '<a class="btn fill" href="event.php?event_id=' + event.event_id + '">Explore Event</a>';
                        eventHTML += '</div>';
                        eventHTML += '</div>';
                    }
                    $('.eventSlider').html(eventHTML);
                    esinit();
                }
            } else {
                $('.eventSlider').html('<p style="text-align:center;color:white;">Failed to fetch events</p>');
                $('.eventPrev,.eventNext').css('display','none')
            }
        },
        error: function() {
            $('.eventSlider').html('<p style="text-align:center;color:white;">Failed to fetch events</p>');
            $('.eventPrev,.eventNext').css('display','none')
        }
    });
});