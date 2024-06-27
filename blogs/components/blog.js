let eta;
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
const blogContent = $(".blog-content").html();

$('.blog-suggestion-controls button').on('click', function() {
  if (!$(this).hasClass('active')) {
    let prevActive = $('.blog-suggestion-controls button.active');
    prevActive.removeClass('active');

    $(this).addClass('active');
    let direction = $(this).index() > prevActive.index() ? 'left': 'right';

    let backgroundMovement = direction === 'left' ? '100%': '-100%';
    let prevBackgroundMovement = direction === 'left' ? '-100%': '100%';

    prevActive.css('background-position', '0 0').animate({
      'background-position': prevBackgroundMovement + ' 0'
    }, 500);

    $(this).css('background-position', backgroundMovement + ' 0').animate({
      'background-position': '0 0'
    }, 500);

    $('.blog-suggestions > div').removeClass('active');
    let targetDiv = $(this).hasClass('suggest-related') ? '.related-blogs':
    $(this).hasClass('suggest-byauthor') ? '.byauthor-blogs': '.popular-blogs';
    $(targetDiv).addClass('active').css('left', '0').animate({
      'opacity': 1
    }, 500);
  }
});
function adjustSuggestionsHeight(tar, pad) {
  h = $(tar).height()
  $('.blog-suggestions').animate({
    height: (h+pad) + 'px'
  },
    500);
}

$(document).ready(function() {
  $.ajax({
    url: 'blog_suggestions.php',
    type: 'get',
    dataType: 'json',
    data: 'blogid='+blogid+'&authorid='+authorid+'&popular=1',
    beforeSend: function() {
      $('.related-blogs').html('<div class="loadingSuggestion"><p class="fa-beat-fade"><i class="fa-brands fa-searchengin"></i></p></div>');
      $('.byauthor-blogs').html('<div class="loadingSuggestion"><p class="fa-beat-fade"><i class="fa-brands fa-searchengin"></i></p></div>');
      $('.popular-blogs').html('<div class="loadingSuggestion"><p class="fa-beat-fade"><i class="fa-brands fa-searchengin"></i></p></div>');
    },
    success: function(data) {
      if (data['relatedStatus'] == 1) {
        $('.related-blogs').html('')
        for (let i = 0; i < data.related.length; i++) {
          let relatedHTML = `<div class="related-blog">
          <div class="related-blogThumbnail-div">
          <img src="thumbnails/${data['related'][i]['thumbnail']}" alt="Thumbnail" class="related-blogThumbnail">
          </div>
          <div class="related-blogDeails">
          <p class="related-blogTitle"><a href="blog.php?blogid=${data['related'][i]['id']}">${data['related'][i]['title'].substring(0, 75)}</a></p><p class="related-blogDate">${new Date(data['related'][i]['publishTime']).toLocaleDateString('en-US', {
            day: 'numeric', month: 'short', year: 'numeric'
          }).replace(/(\d+)\/(\d+)\/(\d+)/, "$2 $1, $3")}</p></div></div>`
          $('.related-blogs').append(relatedHTML);
        }
      } else {
        $('.related-blogs').html('<div class="emptySuggestion"><p>No related blogs found.</p></div>');
      }

      if (data['byauthorStatus'] == 1) {
        $('.byauthor-blogs').html('')
        for (let i = 0; i < data.byauthor.length; i++) {
          let byauthorHTML = `<div class="byauthor-blog">
          <div class="byauthor-blogThumbnail-div">
          <img src="thumbnails/${data['byauthor'][i]['thumbnail']}" alt="Thumbnail" class="byauthor-blogThumbnail">
          </div>
          <div class="byauthor-blogDeails">
          <p class="byauthor-blogTitle"><a href="blog.php?blogid=${data['byauthor'][i]['id']}">${data['byauthor'][i]['title'].substring(0, 75)}</a></p><p class="byauthor-blogDate">${new Date(data['byauthor'][i]['publishTime']).toLocaleDateString('en-US', {
            day: 'numeric', month: 'short', year: 'numeric'
          }).replace(/(\d+)\/(\d+)\/(\d+)/, "$2 $1, $3")}</p></div></div>`
          $('.byauthor-blogs').append(byauthorHTML);
        }
      } else {
        $('.byauthor-blogs').html('<div class="emptySuggestion"><p>No more blogs by this author.</p></div>');
      }

      if (data['popularStatus'] == 1) {
        $('.popular-blogs').html('')
        for (let i = 0; i < data.popular.length; i++) {
          let popularHTML = `<div class="popular-blog">
          <div class="popular-blogThumbnail-div">
          <img src="thumbnails/${data['popular'][i]['thumbnail']}" alt="Thumbnail" class="popular-blogThumbnail">
          </div>
          <div class="popular-blogDeails">
          <p class="popular-blogTitle"><a href="blog.php?blogid=${data['popular'][i]['id']}">${data['popular'][i]['title'].substring(0, 75)}</a></p><p class="popular-blogDate">${new Date(data['popular'][i]['publishTime']).toLocaleDateString('en-US', {
            day: 'numeric', month: 'short', year: 'numeric'
          }).replace(/(\d+)\/(\d+)\/(\d+)/, "$2 $1, $3")}</p></div></div>`
          $('.popular-blogs').append(popularHTML);
        }
      } else {
        $('.popular-blogs').html('<div class="emptySuggestion"><p>No popular blog.</p></div>');
      }

      adjustSuggestionsHeight($('.blog-suggestions .active'), 20)

      $('.blog-suggestion-controls button').on('click', function() {
        if ($(this).hasClass('suggest-related')) {
          adjustSuggestionsHeight('.related-blogs', 20)
        } else if ($(this).hasClass('suggest-byauthor')) {
          adjustSuggestionsHeight('.byauthor-blogs', 20)
        } else if ($(this).hasClass('suggest-popular')) {
          adjustSuggestionsHeight('.popular-blogs', 20)
        }
      })
    },
    error: function(err) {
      $('.related-blogs + .byauthor-blogs').html('<div class="errorSuggestion"><p><i class="fa-solid fa-times"></i> &nbsp;Cfetch\'t fetch</p></div>');
    }
  })
})

// Function to set a cookie
function setCookie(name, value, days, path = '/') {
  const expires = new Date();
  expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
  document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=${path}`;
}

// Function to get a cookie value by name
function getCookie(name) {
  const keyValue = document.cookie.match(`(^|;) ?${name}=([^;]*)(;|$)`);
  return keyValue ? keyValue[2]: null;
}

if (!getCookie('like-status'+blogid)) {
  setCookie('like-status'+blogid, 'unliked', 30);
}

if (getCookie('like-status'+blogid) === 'unliked') {
  $('.like').removeClass('liked');
  $('.like i').addClass('far').removeClass('fas');
} else if (getCookie('like-status'+blogid) === 'liked') {
  $('.like').addClass('liked');
  $('.like i').addClass('fas').removeClass('far');
}

$('.like').on('click', function() {
  let curLike = parseInt($('.likecount').text());

  if ($('.like').hasClass('liked')) {
    $.ajax({
      url: 'like.php',
      type: 'post',
      data: 'action=unlike&id=' + blogid,
      success: function() {
        setCookie('like-status'+blogid, 'unliked', 30);
        $('.like i').addClass('far').removeClass('fas');
        $('.like').removeClass('liked');
        $('.likecount').text(curLike - 1);
      },
      error: function() {
        alert('Failed to like article');
      }
    });
  } else {
    $.ajax({
      url: 'like.php',
      type: 'post',
      data: 'action=like&id=' + blogid,
      success: function() {
        setCookie('like-status'+blogid, 'liked', 30);
        $('.like i').addClass('fas').removeClass('far');
        $('.like').addClass('liked');
        $('.likecount').text(curLike + 1);
      },
      error: function() {
        alert('Failed to unlike article');
      }
    });
  }
});

var animateButton = function(e) {

  e.preventDefault;
  //reset animation
  e.target.classList.remove('animate');

  e.target.classList.add('animate');
  setTimeout(function() {
    e.target.classList.remove('animate');
  },
    700);
};
var classname = document.getElementsByClassName("confetti-button");
for (var i = 0; i < classname.length; i++) {
  classname[i].addEventListener('click', animateButton, false);
}

$('.news-btn').on('click', function() {
  var name = $('.news-name').val();
  var email = $('.news-email').val();
  var messageElement = $('.news-msg');

  if (name == '') {
    messageElement.text('Please enter a valid name.')
    $('.news-msg').addClass('news-msg-error')
    $('.news-msg').removeClass('news-msg-success')
    return;
  }

  if (!isValidEmail(email)) {
    messageElement.text('Please enter a valid email address.');
    $('.news-msg').addClass('news-msg-error')
    $('.news-msg').removeClass('news-msg-success')
    return;
  }

  $.ajax({
    type: 'GET',
    url: 'subscribe.php',
    data: {
      name: name, email: email
    },
    success: function(response) {
      if (response === 'duplicate') {
        messageElement.text('Email already subscribed.');
        $('.news-msg').addClass('news-msg-error')
        $('.news-msg').removeClass('news-msg-success')
      } else if (response === 'success') {
        messageElement.text('Subscription successful!');
        $('.news-msg').addClass('news-msg-success')
        $('.news-msg').removeClass('news-msg-error')
      } else {
        messageElement.text('Subscription failed.');
        $('.news-msg').addClass('news-msg-error')
        $('.news-msg').removeClass('news-msg-success')
      }
    },
    error: function(xhr, status, error) {
      messageElement.text('Error occurred. Please try again.');
    }
  });
});

function isValidEmail(email) {
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}
function formatTime(seconds) {
  if (isNaN(seconds) || seconds < 0) {
    return "NaN";
  }

  let hours = Math.floor(seconds / 3600);
  let minutes = Math.floor((seconds % 3600) / 60);
  let remainingSeconds = Math.floor(seconds % 60);

  let formattedTime = "";

  if (hours > 0) {
    if (hours < 10) {
      formattedTime += "0" + hours + ":";
    } else {
      formattedTime += hours + ":";
    }
  }

  if (minutes >= 0) {
    if (minutes < 10) {
      formattedTime += "0" + minutes + ":";
    } else {
      formattedTime += minutes + ":";
    }
  }
  if (remainingSeconds < 10) {
    remainingSeconds = "0"+remainingSeconds;
  }

  formattedTime += remainingSeconds;

  return formattedTime.trim();
}

function handleSuccess(data) {
     $('.audio-label').hide()
  $('.blog-audio').attr('data-status', 'started')
  $('.audio-player-div').fadeIn(300)
  const audio = new Audio('audio/blogs/' + data['filename']);
  audio.addEventListener('loadeddata', function() {
  $('.blog-audio img.audio-load').fadeOut(100)
  $('.blog-audio span.icon').removeClass('loaderIcon')
  $('.blog-audio i.audio-pause').fadeIn(100)
    
    $('.audio-player-slider').attr('step', 100/audio.duration)
    audio.play();
    $('.icon').attr('data-status', 'playing')
  });

  $('.blog-audio span.icon').click(function() {
    if ($('.blog-audio span.icon').attr('data-status') == 'playing') {
      audio.pause();
      $('.blog-audio span.icon').attr('data-status', 'paused')
      $('.blog-audio i.audio-pause').fadeOut(100)
      $('.blog-audio i.audio-play').fadeIn(100)

    } else if ($('.blog-audio span.icon').attr('data-status') == 'paused') {
      audio.play();
      $('.blog-audio span.icon').attr('data-status', 'playing')
      $('.blog-audio i.audio-play').fadeOut(100)
      $('.blog-audio i.audio-pause').fadeIn(100)
    }
  })

  audio.addEventListener('timeupdate',
    function() {
      const progress = (audio.currentTime/audio.duration) * 100;
      $('.audio-player-slider').val(progress);

      let curTime, totTime;

      curTime = Number(audio.currentTime.toFixed(0))
      totTime = Number(audio.duration.toFixed(0))


      $('.audio-player-curTime').text(formatTime(curTime))
      $('.audio-player-totTime').text(formatTime(totTime))
    })

  $('.audio-player-slider').on('input',
    function() {
      if ($('.icon').attr('data-status') == 'playing') {
        audio.pause()
        $('.icon').attr('data-status', 'pausedBySlider')
        $('.blog-audio i.audio-pause').fadeOut(100)
        $('.blog-audio i.audio-play').fadeIn(100)
      }

      progress = $('.audio-player-slider').val()
      curTime = (progress * audio.duration) / 100;
      audio.currentTime = curTime;
      $('.audio-player-curTime').text(formatTime(curTime))
    })
  $('.audio-player-slider').on('change',
    function() {
      if ($('.icon').attr('data-status') == 'pausedBySlider') {
        audio.play();
        $('.icon').attr('data-status', 'playing')
        $('.blog-audio i.audio-play').fadeOut(100)
        $('.blog-audio i.audio-pause').fadeIn(100)
      }
      progress = $('.audio-player-slider').val()
      curTime = (progress * audio.duration) / 100;
      audio.currentTime = curTime;
      $('.audio-player-curTime').text(formatTime(curTime))
    })
  $('.audio-player-playback').on('change', function(){
    audio.playbackRate = $('.audio-player-playback').val()
  })

  audio.addEventListener('ended', function() {
  audio.currentTime = 0;
  audio.pause();
      $('.blog-audio span.icon').attr('data-status', 'paused')
      $('.blog-audio i.audio-pause').fadeOut(100)
      $('.blog-audio i.audio-play').fadeIn(100)
});

}
function handleError200(data) {
  Toast.fire({
    icon: 'error',
    title: data.message
  })
  $('.blog-audio i.audio-play').fadeIn(100)
  $('.audio-label').text('Error 200: '+data.message)
  $('.blog-audio img.audio-load').fadeOut(100)
  $('.blog-audio span.icon').removeClass('loaderIcon')
}
function handleError(err) {
  $('.blog-audio i.audio-play').fadeIn(100)
  $('.audio-label').text('Error #: '+err)
  $('.blog-audio img.audio-load').fadeOut(100)
  $('.blog-audio span.icon').removeClass('loaderIcon')
  console.log(err);
  Toast.fire({
    icon: 'error',
    title: 'Error: '+ err
  })

}
function checkAudioStatus() {
  $.ajax({
    url: '../action/blogAudio.php',
    type: 'post',
    dataType: 'json',
    data: 'action=checkAudio&blogid='+blogid,
    beforeSend: function() {
      $('.blog-audio i.audio-play').fadeOut(100)
      $('.blog-audio img.audio-load').fadeIn(100)
      $('.blog-audio span.icon').addClass('loaderIcon');
      $('.blog-audio').attr('data-status', 'started')
      $('.audio-label').text('Generating audio blog...')
    },
    success: function(data) {

      if (data['status'] == 'success') {
        handleSuccess(data)
      } else if (data['status'] == 'error') {
        handleError200(data)
      } else if (data['status'] == 'processing') {
        eta = Number(data['eta']);
        intTime = (eta*1000)/4;
        if (intTime < 2000) {
          setTimeout(checkAudioStatus, 1000)
        } else {
          setTimeout(checkAudioStatus, intTime)
        }
      }

    },
    error: function(err) {
      handleError(err)
    }
  })
}

//Audio
$('.blog-audio .icon').click(function() {
  let audioStatus = $('.blog-audio').attr('data-status');
  if (audioStatus == 'initial') {
    checkAudioStatus();
  }
}) 

$('.audio-player-playback').select2({
      minimumResultsForSearch: Infinity
})