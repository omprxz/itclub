const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter',
      Swal.stopTimer)
    toast.addEventListener('mouseleave',
      Swal.resumeTimer)
  }
})
let isValid = true;
function validateForm() {
  const form = document.getElementById("eventForm");
  for (let i = 0; i < form.elements.length; i++) {
    const element = form.elements[i];

    if (element.hasAttribute("required") && element.value.trim() === "") {
      isValid = false;
    } else {
      isValid = true;
    }
  }
  return isValid;
}
$(".thumbnail").on("change", function(event) {
  const file = event.target.files[0];
  const image = $('.thumbnail-prev');

  const reader = new FileReader();
  reader.onload = function(e) {
    image.attr('src', e.target.result);
  };

  reader.readAsDataURL(file);

  $('.thumbnail-prev').on('error', function() {
    $(".thumbnail-prev").attr('src', '../../blogs/thumbnails/thumbnail.png');
  })
})

$(".createEvent").on('click', function() {
  validateForm()
  if (isValid && contentEditor.getHTMLCode() != "") {
    event.preventDefault();
    
      let eventData = new FormData(document.getElementById('eventForm'))
      eventData.append('event_description', contentEditor.getHTMLCode())
      console.log(eventData);
      let createInt;
      $.ajax({
        url: '../event/createEvent_action.php',
        type: 'POST',
        data: eventData,
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: function() {
          let dot = 1;
          function creating() {
            if (dot == 1) {
              $('.createEvent').text('Creating.')
            } else if (dot == 2) {
              $('.createEvent').text('Creating..')
            } else {
              $('.createEvent').text('Creating...')
              dot = 0;
            }
            dot++
            return dot;
          }
          createInt = setInterval(creating, 500);
        },
        xhr: function() {
          const xhr = new window.XMLHttpRequest();

          xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
              const percentComplete = (e.loaded / e.total) * 100;
              inPercent = percentComplete+"%";
              $('.uploadStatusDiv').show()
              $('.uploadStatus').css('width',inPercent);
              $('.uploadStatus').text(inPercent);
            }
          },
            false);

          return xhr;
        },
        complete: function() {
          $('.uploadStatusDiv').hide()
          clearInterval(createInt)
        },
        success: function(data) {
          console.log(data);

          if (data['status'] == 'success') {
            $('.createEvent').text('Created')
              Toast.fire({
                icon: 'success',
                title: data['result']
              })
              //Send notifications
             notifTitle = eventData.get('event_title');
              notifMessage = "New Event at IT Club";
              notifUrl = "https://itclub.000.pe/event.php?event_id="+data["event_id"];
              notifIcon = "https://itclub.000.pe/img/itclublogo-min.png";
              notifActionTitle = "View Event";
              notifActionUrl = notifUrl;
              
              $.ajax({
            type: 'POST',
            url: '../../action/send-notification.php',
            data: {
                title: notifTitle,
                message: notifMessage,
                url: notifUrl,
                icon: notifIcon,
                action1: {
                    title: notifActionTitle,
                    url: notifActionUrl
                }
            },
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                console.error(error);
          }
        });
          } else {
            Toast.fire({
              icon: 'error',
              title: data['result']
            })
          }

          setTimeout(function() {
            $('.createEvent').text('Create Event')}, 500);
        },
        error: function(error) {
          console.error('Error during upload:', error);
          Toast.fire({
            icon: 'error',
            title: 'Something went wrong!. Sorry.'
          })
          $('.createEvent').text('ERROR !')
          setTimeout(function() {
            $('.createEvent').text('Create Event')}, 500);
          // Handle error cases
        }
      })
      
    } else {
      Toast.fire({
        icon: 'error',
        title: 'Some required fields missing!'
      })
      if ($(".title").val() == "") {
        $(".title").focus()
      } else if (contentEditor.getHTMLCode() == "") {
        contentEditor.focus()
      } else {
        console.log("Some required fields mandatory");
      }
    }
  })