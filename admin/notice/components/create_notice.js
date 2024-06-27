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
  const form = document.getElementById("noticeForm");
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

$(".createNotice").on('click', function() {
  validateForm()
  if (isValid && contentEditor.getHTMLCode() != "") {
    event.preventDefault();
    
      let noticeData = new FormData(document.getElementById('noticeForm'))
      noticeData.append('notice_description', contentEditor.getHTMLCode())
      console.log(noticeData);
      let createInt;
      $.ajax({
        url: '../notice/createNotice_action.php',
        type: 'POST',
        data: noticeData,
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: function() {
          let dot = 1;
          function creating() {
            if (dot == 1) {
              $('.createNotice').text('Creating.')
            } else if (dot == 2) {
              $('.createNotice').text('Creating..')
            } else {
              $('.createNotice').text('Creating...')
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
            $('.createNotice').text('Created')
              Toast.fire({
                icon: 'success',
                title: data['result']
              })
              
            //Send notifications
             notifTitle = noticeData.get('notice_title');
              notifMessage = "New Notice from IT Club";
              notifUrl = "https://itclub.000.pe/notice.php?notice_id="+data["notice_id"];
              notifIcon = "https://itclub.000.pe/img/itclublogo-min.png";
              notifActionTitle = "View Notice";
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
            $('.createNotice').text('Create Notice')}, 500);
        },
        error: function(error) {
          console.error('Error during upload:', error);
          Toast.fire({
            icon: 'error',
            title: 'Something went wrong!. Sorry.'
          })
          $('.createNotice').text('ERROR !')
          setTimeout(function() {
            $('.createNotice').text('Create Notice')}, 500);
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