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
function retVar(a){
  return a;
}
function getURLParameters() {
  var urlParams = {};
  var queryString = window.location.search.substring(1);
  var queryParams = queryString.split('&');

  queryParams.forEach(function(param) {
    var pair = param.split('=');
    if (pair.length === 2) {
      var key = decodeURIComponent(pair[0]);
      var value = decodeURIComponent(pair[1]);
      urlParams[key] = value;
    }
  });

  return urlParams;
}
function decodeHtmlEntities(encodedString) {
  return encodedString.replace(/&amp;/g,
    '&')
  .replace(/&lt;/g,
    '<')
  .replace(/&gt;/g,
    '>')
  .replace(/&quot;/g,
    '"')
  .replace(/&#039;/g,
    "'");
}
function formattedDate(dt){
  let formattedDateNow = dt.toLocaleString('sv', { ...optionsDate, hour12: false }).replace(' ', 'T');
  return formattedDateNow;
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

$(document).ready(function() {
  let urlParams = getURLParameters();
  $.ajax({
    url: 'get_notice_data.php',
    type: 'get',
    data: urlParams,
    dataType: 'json',
    success: function(fetchedNotice) {
      if(fetchedNotice['status']=='success'){
      id = retVar(fetchedNotice['notice_id']);
      thumbnailUrl = retVar(fetchedNotice['notice_imgurl'])
      titleFetched = retVar(fetchedNotice['notice_title'])
      curContent = retVar(fetchedNotice['notice_content'])
      
      $('.title').val(decodeHtmlEntities(fetchedNotice['notice_title']))
      contentEditor.setHTMLCode(decodeHtmlEntities(fetchedNotice['notice_content']))
      $('.thumbnail-prev').attr('src','../../img/notices/'+thumbnailUrl)

      }else{
        Toast.fire({
          icon:'error',
          title:fetchedNotice['result']
        })
      }
    },
    error: function(ErrorfetchedNotice) {
      console.log('Error fetching notice data.')
    }
  })
})

$(".updateNotice").on('click', function() {
  let urlParams = getURLParameters();
  if('noticeid' in urlParams){
  validateForm()
  if (isValid && contentEditor.getHTMLCode() != "") {
    event.preventDefault();
    let noticeData = new FormData()
    noticeData.append('id',id)
    
    newTitle = $('.title').val()
    if(newTitle !== titleFetched){
      noticeData.append('title',newTitle)
    }
    newContent = contentEditor.getHTMLCode()
    if(newContent!==curContent){
      noticeData.append('notice_description', contentEditor.getHTMLCode())
    }
    const thumbnailNew = document.getElementById('thumbnail').files[0];
    if (thumbnailNew) {
      noticeData.append('notice_image', thumbnailNew);
    }
    
    let updateInt;
    $.ajax({
      url: '../notice/editNotice_action.php',
      type: 'POST',
      data: noticeData,
      dataType: 'json',
      processData: false,
      contentType: false,
      beforeSend: function() {
        let dot = 1;
        function updating() {
          if (dot == 1) {
            $('.updateNotice').text('Updating.')
          } else if (dot == 2) {
            $('.updateNotice').text('Updating..')
          } else {
            $('.updateNotice').text('Updating...')
            dot = 0;
          }
          dot++
          return dot;
        }
        updateInt = setInterval(updating, 500);
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
        clearInterval(updateInt)
      },
      success: function(data) {
        if (data['status'] == 'success') {
          $('.updateNotice').text('Updated')
            Toast.fire({
              icon: 'success',
              title: data['result']})
        } else {
          Toast.fire({
            icon: 'error',
            title: data['result']
          })

        }

        setTimeout(function() {
          $('.updateNotice').text('Update Notice')}, 500);
      },
      error: function(error) {
        console.error('Error during upload:', error);
        Toast.fire({
          icon: 'error',
          title: 'Something went wrong!. Sorry.'
        })
        $('.updateNotice').text('ERROR !')
        setTimeout(function() {
          $('.updateNotice').text('Update Notice')}, 500);
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
  }else{
    console.log(urlParams)
    Toast.fire({
      icon:'error',
      title:'No notice found.'
    })
  }
})
