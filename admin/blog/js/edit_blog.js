let tags = [], tag, id,pubTime,thumbnailUrl,titleFetched;
let sameThumbnail = 'yes';
const now = new Date();
const optionsDate = { timeZone: 'Asia/Kolkata' };
function retVar(v){
  return v;
}
function formattedDate(dt){
  let formattedDateNow = dt.toLocaleString('sv', { ...optionsDate, hour12: false }).replace(' ', 'T');
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

$(document).ready(function() {
  let urlParams = getURLParameters();
  $.ajax({
    url: 'get_blog_data.php',
    type: 'get',
    data: urlParams,
    dataType: 'json',
    success: function(fetchedBlog) {
      console.log(fetchedBlog)
      if(fetchedBlog['status']=='success'){
      id = retVar(fetchedBlog['id']);
      pubTime = retVar(fetchedBlog['publishTime']);
      thumbnailUrl = retVar(fetchedBlog['thumbnail'])
      titleFetched = retVar(fetchedBlog['title'])
      $('.title').val(fetchedBlog['title'])
      contentEditor.setHTMLCode(decodeHtmlEntities(fetchedBlog['content']))
      tags = fetchedBlog['tags'].split(',')
      for (let i = 0; i < tags.length; i++) {
        if (!$(".tagList").find('span').length) {
          $('.tagList').html(`<span>${tags[i]}<i class="fas fa-times"></i></span>`)
        } else {
          $('.tagList').append(`<span>${tags[i]}<i class="fas fa-times"></i></span>`)
        }
      }
      $(".thumbnail-prev").attr('src', '../../blogs/thumbnails/'+fetchedBlog['thumbnail'])
      if (fetchedBlog['visibility'] == 'schedule') {
        $('.visibility').val(fetchedBlog['visibility'])
        $('.input-sTime').css('display', 'flex');
        
        $('.sTime').val(formattedDate(fetchedBlog['publishTime']))

      } else {
        $('.visibility').val(fetchedBlog['visibility'])
        $('.sTime').val('');
        $('.input-sTime').css('display', 'none');
      }
      }else{
        Toast.fire({
          icon:'error',
          title:fetchedBlog['result']
        })
      }

    },
    error: function(ErrorfetchedBlog) {
      console.log('Error fetching blog data.')
    }
  })
})

$(".thumbnail").on("change", function(event) {
  sameThumbnail = 'no';
  const file = event.target.files[0];
  const image = $('.thumbnail-prev');
  const reader = new FileReader();
  reader.onload = function(e) {
    image.attr('src', e.target.result);
  };
  reader.readAsDataURL(file);
  $('.thumbnail-prev').on('error',function() {
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
$(".updateBlog").on('click', function() {
  let urlParams = getURLParameters();
  if('id' in urlParams){
  validateForm()
  if (isValid && contentEditor.getHTMLCode() != "") {
    event.preventDefault();
    let blogData = new FormData(document.getElementById('blogForm'))
    blogData.append('tags', tags)
    blogData.append('sameThumbnail',sameThumbnail)
    blogData.append('id',id)
    blogData.append('pubTime',pubTime)
    blogData.append('thumbnailUrl',thumbnailUrl)
    blogData.append('content', contentEditor.getHTMLCode())
    console.log(...blogData);
    let updateInt;
    $.ajax({
      url: '../blog/editblog_action.php',
      type: 'POST',
      data: blogData,
      dataType: 'json',
      processData: false,
      contentType: false,
      beforeSend: function() {
        let dot = 1;
        function updating() {
          if (dot == 1) {
            $('.updateBlog').text('Updating.')
          } else if (dot == 2) {
            $('.updateBlog').text('Updating..')
          } else {
            $('.updateBlog').text('Updating...')
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
        console.log(data);

        if (data['status'] == 'success') {
          $('.updateBlog').text('Updated')
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
          $('.updateBlog').text('Update Blog')}, 500);
      },
      error: function(error) {
        console.error('Error during upload:', error);
        Toast.fire({
          icon: 'error',
          title: 'Something went wrong!. Sorry.'
        })
        $('.updateBlog').text('ERROR !')
        setTimeout(function() {
          $('.updateBlog').text('Update Blog')}, 500);
        // Handle error cases
      }
    });
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
    Toast.fire({
      icon:'error',
      title:'No blog found.'
    })
  }
})
