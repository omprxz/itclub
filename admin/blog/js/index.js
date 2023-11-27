const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 2500,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter',
      Swal.stopTimer)
    toast.addEventListener('mouseleave',
      Swal.resumeTimer)
  }
})

$(document).ready(function() {

  $('.deleteBlog').click(function() {
    let delIcon;
    let thisBlogid = $(this).attr('data-blogid');

    Swal.fire({
      title: 'Are you sure?',
      text: 'You won\'t be able to recover this blog!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      showLoaderOnConfirm: true,
      preConfirm: () => {
        return new Promise((resolve, reject) => {
          $.ajax({
            url: 'delete_blog.php',
            type: 'post',
            data: 'blogid=' + thisBlogid,
            dataType: 'json',
            success: function(delData) {
              if (delData['status'] == 'success') {
                delIcon = 'success';
                $('.blogNo'+thisBlogid).remove()
                $.ajax({
                  url:'fetchTotalStats.php',
                  type:'get',
                  data:'stats=get',
                  dataType:'json',
                  success:function(stats){
                    if(stats['status']=='success'){
                     $("#blogsCount").text(stats['blogsCount'])
                     $("#likesCount").text(stats['likesCount'])
                     $("#viewsCount").text(stats['viewsCount'])
                    }
                  }
                })
                
                if($('.blogCards').find('.blogCard').length === 0){
                  $('.blogCards').append('<p class="text-center"><i class="fas fa-sync"></i>&nbsp; Refreshing the page.Please wait...</p>')
                  setTimeout(function() {location.reload()}, 2500);
                }
                resolve(delData['result']);
              } else {
                delIcon = 'error';
                reject('Deletion failed');
              }
              Toast.fire({
                icon:delIcon,
                title:delData['result']
              })
            },
            error: function(delErr) {
              delIcon = 'error';
              reject('Deletion failed');
            }
          });
        });
      },
      allowOutsideClick: () => !Swal.isLoading()
    })
  });

  $('.blogShare').click(function() {
    let blogId = $(this).attr('data-blogid');
    let blogLink = `${window.location.protocol}//${window.location.hostname}/blogs/blog.php?blogid=${blogId}`;
    Swal.fire({
      title: '<i class="fas fa-share-alt"></i>',
      html: `<input style="font-size:14px;" class="form-control" type="text" id="blogLink" value="${blogLink}" readonly>`,
      showCancelButton: true,
      confirmButtonText: 'Copy Link',
      cancelButtonText: 'Close',
      preConfirm: () => {
        const blogLinkInput = document.getElementById('blogLink');
        blogLinkInput.select();
        document.execCommand('copy');
        return Swal.fire({
          icon: 'success',
          title: 'Copied!'
        });
      }
    });
  });

})