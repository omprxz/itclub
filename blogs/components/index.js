let limit = 5;
let offset = 7;
let data;

if($('.recent-card').length < offset){
  $('.recent-more-btn').html('All Posts Loaded')
  $('.recent-more-btn').attr('disabled','disabled')
}

$('.recent-more-btn').on('click',function(){
  data = "limit="+limit+"&offset="+offset;
  btnHtml = $('.recent-more-btn').html()
  $.ajax({
    url:'get_recent.php',
    type:'get',
    dataType:'json',
    data:data,
    beforeSend: function(){
      $('.recent-more-btn').html('Loading  &nbsp;<i class="fas fa-spinner fa-spin"></i>')
    },
    success: function(res){
      $('.recent-more-btn').html(btnHtml)
      if(res['status']=='success'){
        for(let i=0;i<res['posts'].length;i++){
          posts=res['posts'][i];
          postHTML = `
          <div class="recent-card card">
          <div class="recent-img-div blog-img-div">
            <img src="thumbnails/${posts['thumbnail']}" alt="" class="recent-img blog-img">
            <div class="recent-views views">
              <span class="views-count">${posts['views']}</span> <i class="fas fa-eye"></i>
            </div>
          </div>
          <div class="recent-details-div details-div">
            <div class="recent-title title">
              <a href="blog.php?blogid=${posts['id']}">${posts['title']}</a>
            </div>
            
            <div class="recent-extrainfo extrainfo">
              <div class="recent-meta meta">
              <img src="../admin/images/admins/${posts['admin_profilepic']}" alt="${posts['admin_name']}" class="meta-profile">
              <div class="meta-details">
                <a href="author.php?author=${posts['admin_username']}" class="meta-name">${posts['admin_name']}</a>
                <p class="meta-date">${posts['pubTime']}</p>
               </div>
            </div>
              <div class="recent-link link">
              <a href="blog.php?blogid=${posts['id']}">Read More »</a>
              </div>
            </div>
          </div>
        </div>
          `;
          
          $('.recent-cards').append(postHTML);
        }
        offset += limit;
      }
      else{
        $('.recent-more-btn').html('Try again &nbsp;<i class="fas fa-exclamation-triangle"></i>')
      }
      if(res['allLoaded']==1){
        $('.recent-more-btn').html('All Posts Loaded')
        $('.recent-more-btn').attr('disabled','disabled')
      }
    },
    error: function(){
      $('.recent-more-btn').html('Try again &nbsp;<i class="fas fa-exclamation-triangle"></i>')
    }
  })
})

$(document).ready(function() {
  $('.news-btn').on('click', function() {
    var name = $('.news-name').val();
    var email = $('.news-email').val();
    var messageElement = $('.news-msg');
    
    if(name==''){
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
      data: { name: name, email: email },
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
});

function isValidEmail(email) {
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}
