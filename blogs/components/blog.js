$('.blog-suggestion-controls button').on('click', function() {
  if (!$(this).hasClass('active')) {
    let prevActive = $('.blog-suggestion-controls button.active');
    prevActive.removeClass('active');

    $(this).addClass('active');
    let direction = $(this).index() > prevActive.index() ? 'left' : 'right';

    let backgroundMovement = direction === 'left' ? '100%' : '-100%';
    let prevBackgroundMovement = direction === 'left' ? '-100%' : '100%';

    prevActive.css('background-position', '0 0').animate({
      'background-position': prevBackgroundMovement + ' 0'
    }, 500);

    $(this).css('background-position', backgroundMovement + ' 0').animate({
      'background-position': '0 0'
    }, 500);

    $('.blog-suggestions > div').removeClass('active');
    let targetDiv = $(this).hasClass('suggest-related') ? '.related-blogs' :
      $(this).hasClass('suggest-byauthor') ? '.byauthor-blogs' : '.popular-blogs';
    $(targetDiv).addClass('active').css('left', '0').animate({ 'opacity': 1 }, 500);
  }
});

function adjustSuggestionsHeight(tar,pad) {
 h = $(tar).height()
 $('.blog-suggestions').animate({
  height: (h+pad) + 'px'
}, 500);
}

$(document).ready(function(){
   $.ajax({
    url:'blog_suggestions.php',
    type:'get',
    dataType:'json',
    data:'blogid='+blogid+'&authorid='+authorid+'&popular=1',
    beforeSend:function(){
      $('.related-blogs').html('<div class="loadingSuggestion"><p class="fa-beat-fade"><i class="fa-brands fa-searchengin"></i></p></div>');
      $('.byauthor-blogs').html('<div class="loadingSuggestion"><p class="fa-beat-fade"><i class="fa-brands fa-searchengin"></i></p></div>');
      $('.popular-blogs').html('<div class="loadingSuggestion"><p class="fa-beat-fade"><i class="fa-brands fa-searchengin"></i></p></div>');
    },
    success:function(data){
      if(data['relatedStatus']==1){
        $('.related-blogs').html('')
        for (let i = 0; i < data.related.length; i++) {
       let relatedHTML = `<div class="related-blog">
	        <div class="related-blogThumbnail-div">
	          <img src="thumbnails/${data['related'][i]['thumbnail']}" alt="Thumbnail" class="related-blogThumbnail">
	        </div>
	        <div class="related-blogDeails">
	          <p class="related-blogTitle"><a href="blog.php?blogid=${data['related'][i]['id']}">${data['related'][i]['title'].substring(0,75)}</a></p><p class="related-blogDate">${new Date(data['related'][i]['publishTime']).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' }).replace(/(\d+)\/(\d+)\/(\d+)/, "$2 $1, $3")}</p></div></div>`
          $('.related-blogs').append(relatedHTML);
        }
      }else{
        $('.related-blogs').html('<div class="emptySuggestion"><p>No related blogs found.</p></div>');
      }
      
      if(data['byauthorStatus']==1){
        $('.byauthor-blogs').html('')
        for (let i = 0; i < data.byauthor.length; i++) {
       let byauthorHTML = `<div class="byauthor-blog">
	        <div class="byauthor-blogThumbnail-div">
	          <img src="thumbnails/${data['byauthor'][i]['thumbnail']}" alt="Thumbnail" class="byauthor-blogThumbnail">
	        </div>
	        <div class="byauthor-blogDeails">
	          <p class="byauthor-blogTitle"><a href="blog.php?blogid=${data['byauthor'][i]['id']}">${data['byauthor'][i]['title'].substring(0,75)}</a></p><p class="byauthor-blogDate">${new Date(data['byauthor'][i]['publishTime']).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' }).replace(/(\d+)\/(\d+)\/(\d+)/, "$2 $1, $3")}</p></div></div>`
          $('.byauthor-blogs').append(byauthorHTML);
        }
      }else{
        $('.byauthor-blogs').html('<div class="emptySuggestion"><p>No more blogs by this author.</p></div>');
      }
      
      if(data['popularStatus']==1){
        $('.popular-blogs').html('')
        for (let i = 0; i < data.popular.length; i++) {
       let popularHTML = `<div class="popular-blog">
	        <div class="popular-blogThumbnail-div">
	          <img src="thumbnails/${data['popular'][i]['thumbnail']}" alt="Thumbnail" class="popular-blogThumbnail">
	        </div>
	        <div class="popular-blogDeails">
	          <p class="popular-blogTitle"><a href="blog.php?blogid=${data['popular'][i]['id']}">${data['popular'][i]['title'].substring(0,75)}</a></p><p class="popular-blogDate">${new Date(data['popular'][i]['publishTime']).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' }).replace(/(\d+)\/(\d+)\/(\d+)/, "$2 $1, $3")}</p></div></div>`
          $('.popular-blogs').append(popularHTML);
        }
      }else{
        $('.popular-blogs').html('<div class="emptySuggestion"><p>No popular blog.</p></div>');
      }
      
      adjustSuggestionsHeight($('.blog-suggestions .active'),50)
      
      $('.blog-suggestion-controls button').on('click', function() {
        if($(this).hasClass('suggest-related')){
        adjustSuggestionsHeight('.related-blogs',50)
        }
        else if($(this).hasClass('suggest-byauthor')){
        adjustSuggestionsHeight('.byauthor-blogs',50)
        }
        else if($(this).hasClass('suggest-popular')){
        adjustSuggestionsHeight('.popular-blogs',50)
        }
      })
    },
    error:function(err){
      $('.related-blogs + .byauthor-blogs').html('<div class="errorSuggestion"><p><i class="fa-solid fa-times"></i> &nbsp;Cfetch\'t fetch</p></div>');
    }
 })
 })

// Function to set a cookie
function setCookie(name, value, days) {
  const expires = new Date();
  expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
  document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
}

// Function to get a cookie value by name
function getCookie(name) {
  const keyValue = document.cookie.match(`(^|;) ?${name}=([^;]*)(;|$)`);
  return keyValue ? keyValue[2] : null;
}

if (!getCookie('like-status')) {
  setCookie('like-status', 'unliked', 30);
}

if (getCookie('like-status') === 'unliked') {
  $('.like').removeClass('liked');
  $('.like i').addClass('far').removeClass('fas');
  $('.likestatus').text('Like');
} else if (getCookie('like-status') === 'liked') {
  $('.like').addClass('liked');
  $('.like i').addClass('fas').removeClass('far');
  $('.likestatus').text('Liked');
}

$('.like').on('click', function() {
  let curLike = parseInt($('.likecount').text());

  if ($('.like').hasClass('liked')) {
    $.ajax({
      url: 'like.php',
      type: 'post',
      data: 'action=unlike&id=' + blogid,
      success: function() {
        setCookie('like-status', 'unliked', 30);
        $('.like i').addClass('far').removeClass('fas');
        $('.like').removeClass('liked');
        $('.likestatus').text('Like');
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
        setCookie('like-status', 'liked', 30);
        $('.like i').addClass('fas').removeClass('far');
        $('.like').addClass('liked');
        $('.likestatus').text('Liked');
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
  setTimeout(function(){
    e.target.classList.remove('animate');
  },700);
};
var classname = document.getElementsByClassName("confetti-button");
for (var i = 0; i < classname.length; i++) {
  classname[i].addEventListener('click', animateButton, false);
}