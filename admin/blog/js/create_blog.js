let tags = [], tag;
const now = new Date();
const optionsDate = {
  timeZone: 'Asia/Kolkata'
};
function formattedDate(dt) {
  let formattedDateNow = dt.toLocaleString('sv', {
    ...optionsDate, hour12: false
  }).replace(' ', 'T');
  return formattedDateNow;
}
$('.sTime').val(formattedDate(now))

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

if (!$(".tagList").find('span').length) {
  $(".tagList").html('<p class="notags">No tags</p>')
}

$(".tagList").on('click', 'span i', function() {
  $(this).parent().remove()

  tags.splice(tags.indexOf($(this).parent().text()), 1)
  if (!$(".tagList").find('span').length) {
    $(".tagList").html('<p class="notags">No tags</p>')
  }
})

$(".addTag").on('click', function() {
  tag = $(".inpTag").val().trim()
  tagArr = tag.split(',')
  for (let i = 0; i < tagArr.length; i++) {
    if (!tags.includes(tagArr[i].trim()) && tagArr[i].trim() != "") {
      tags.push(tagArr[i].trim())
      if (!$(".tagList").find('span').length) {
        $('.tagList').html(`<span>${tagArr[i].trim()}<i class="fas fa-times"></i></span>`)
      } else {
        $('.tagList').append(`<span>${tagArr[i].trim()}<i class="fas fa-times"></i></span>`)
      }
    } else {
      if (tags.includes(tagArr[i].trim())) {
        Toast.fire({
          icon: 'warning',
          title: 'Duplicate tags',
          timer: 2000
        })
      } else if (tagArr[i].trim() == "") {
        Toast.fire({
          icon: 'error',
          title: 'Empty tag',
          timer: 2000
        })
      }
    }
  }
  $(".inpTag").val('')
  $(".inpTag").focus()
})

$(".visibility").on("change", function() {
  if ($(this).val() == 'schedule') {
    $('.input-sTime').css('display', 'flex');
  } else {
    $('.input-sTime').css('display', 'none');
  }
})

let isValid = true;
function validateForm() {
  const form = document.getElementById("blogForm");
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

$(".createBlog").on('click', function() {
  validateForm()
  if (isValid && contentEditor.getHTMLCode() != "") {
    event.preventDefault();
    let scheduledDateTime = new Date($(".sTime").val());
    let currentDateTime = new Date();

    if ($('.visibility').val() === 'schedule' && scheduledDateTime <= currentDateTime) {
      Toast.fire({
        icon: 'error',
        title: 'Scheduled time should be in the future.',
        timer: 2000
      });
     } else {
      let blogData = new FormData(document.getElementById('blogForm'))
      blogData.append('tags', tags)
      blogData.append('content', contentEditor.getHTMLCode())
      console.log(blogData);
      let createInt;
      $.ajax({
        url: '../blog/createblog_action.php',
        type: 'POST',
        data: blogData,
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: function() {
          let dot = 1;
          function creating() {
            if (dot == 1) {
              $('.createBlog').text('Creating.')
            } else if (dot == 2) {
              $('.createBlog').text('Creating..')
            } else {
              $('.createBlog').text('Creating...')
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
              $('.uploadStatus').show()
              $('.uploadStatus').val(percentComplete);
            }
          },
            false);

          return xhr;
        },
        complete: function() {
          $('.uploadStatus').hide()
          clearInterval(createInt)
        },
        success: function(data) {
          console.log(data);

          if (data['status'] == 'success') {

            $('.createBlog').text('Created')
            setTimeout(function(){
                $("#blogForm")[0].reset();
              }, 1000);
            if ($('.visibility').val() == 'public') {
                 //Send notifications
             notifTitle = blogData.get('title');
              notifMessage = "New blog released on IT Club";
              notifUrl = "https://itclub.000.pe/blogs/blog.php?blogid="+data["blogid"];
              notifIcon = "https://itclub.000.pe/img/itclublogo-min.png";
              notifActionTitle = "Read Blog";
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
        
               $.ajax({
              url:'sendSubscriptions.php',
              data:'blogid='+data['blogid']
            })
              Toast.fire({
                icon: 'success',
                title: data['result']})
            } else if ($('.visibility').val() == 'schedule') {
              Toast.fire({
                icon: 'success',
                title: data['result']})
            } else {
              Toast.fire({
                icon: 'info',
                title: data['result']})
            }
          } else {
            Toast.fire({
              icon: 'error',
              title: data['result']
            })

          }

          setTimeout(function() {
            $('.createBlog').text('Create Blog')}, 500);
        },
        error: function(error) {
          console.error('Error during upload:', error);
          Toast.fire({
            icon: 'error',
            title: 'Something went wrong!. Sorry.'
          })
          $('.createBlog').text('ERROR !')
          setTimeout(function() {
            $('.createBlog').text('Create Blog')}, 500);
          // Handle error cases
        }
      })
      }
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