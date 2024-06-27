<?php
include_once('../../action/checklogin.php');
$admin_id = $_SESSION['admin_id'];
require '../../action/conn.php';

$getDelData = mysqli_query($mysqli,"select title,id,thumbnail,delId,delTime from blogsTrash");
?>
<html>
  <head>
     <meta name="viewport" content="width=device-width" />
     <title>Trash Blogs</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@100;200;300;400;500;600;700;00;900&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  </head>
  <body>
    <script>
      let adminId = '<?php echo $admin_id; ?>';
    </script>
    
    <?php include "header.php"; ?>
    
    <div class="trashBlogs pt-2">
      <h2 class="text-center fw-bold mt-2" style="font-family: arial;">Deleted Blogs</h2>
      <p class="text-center text-danger mb-4">
        Blogs in the trash auto-delete after 30 days
      </p>
      <div class="blogCards d-flex flex-wrap justify-content-center gap-3">
        
        <?php
        if(mysqli_num_rows($getDelData)>0){
          while($blog = mysqli_fetch_assoc($getDelData)){
            $formattedDelData = date('M d, Y', strtotime($blog['delTime']));
          ?>
          
           <div class="card mx-auto shadow border-2 border-dark-subtle blogCard blogNo<?php echo $blog['delId'];?>" style="width: 18rem;">
          <img src="../../blogs/thumbnails/<?php echo $blog['thumbnail'];?>" class="card-img-top" alt="<?php echo substr($blog['thumbnail'],0,20);?>">
          <div class="card-body">
          <h5 class="card-title"><?php echo $blog['title']; ?></h5>
          <div class="restoreDiv text-center">
          <button href="#" class="btn btn-outline-success restore mt-2" data-delId="<?php echo $blog['delId'];?>">Restore &nbsp;<i class="fas fa-undo"></i></button>
          </div>
        </div>
          <div class="card-footer text-danger text-center">
            <i class="fas fa-trash"></i>&nbsp; <?php echo $formattedDelData;?>
          </div>
        </div>
          
          <?php
          }
        }else{
          echo "<div class='text-center my-3'>No blogs in trash &nbsp;<i class='fas fa-trash'></i></div>";
        }
        ?>
        
       
      </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
      $('.restore').click(function(){
        let icon;
        let thisDelId = $(this).attr('data-delId');
        
        $.ajax({
          url:'restore_blog.php',
          type:'post',
          dataType:'json',
          data:'del_id='+thisDelId,
          success: function(delData){
            if(delData['status']=='success'){
              icon='success';
              $('.blogNo'+thisDelId).remove()
              if($('.blogCards').find('.blogCard').length === 0){
                $('.blogCards').html("<div class='text-center text-success my-3'>All blogs restored &nbsp;<i class='fas fa-check'></i> <br> <a href='../blog' class='btn btn-outline-primary my-4'>Return to control panel &nbsp;<i class='fas fa-reply'></i></a></div>")
              }
            }else{
              icon='error';
            }
              Toast.fire({
                icon:icon,
                title:delData['result']
              })
          },
          error: function(delErr){
            console.log(delErr)
            Toast.fire({
              icon:'error',
              title:'Error! Can\'t restore blog.'
            })
          }
        })
      })
    </script>
  </body>
</html>