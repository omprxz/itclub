<?php
include_once('../../action/checklogin.php');
$admin_id = $_SESSION['admin_id'];
$postPerPage = 10;
require '../../action/conn.php';
if(isset($_GET['page'])){
   $currentPage = $_GET['page'];
}else{
   $currentPage = 1;
}
$showFirstPage="";
if($currentPage<1){
  $currentPage=1;
  $showFirstPage = "Showing first page.";
}
$stats = mysqli_query($mysqli, "select count(*) as events from events");
$stats = mysqli_fetch_assoc($stats);
$eventsCount = $stats['events'];
if ($eventsCount == '') {
  $eventsCount = 0;
}

// Fetch specific columns from blogs
$offset = $postPerPage*($currentPage-1);
$eventsSql = mysqli_query($mysqli, "SELECT event_id, event_title, event_date FROM events order by event_date desc limit $postPerPage offset $offset");
?>
<html>
<head>
  <meta name="viewport" content="width=device-width" />
<title>Events Admin Page</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Mukta:wght@100;200;300;400;500;600;700;00;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
<style>
  * {
    font-family: Mukta;
  }
  .statsLikes span,.statsViews span {
    line-height: 1.5;
    font-size: 15px;
  }
</style>
</head>
<body>
<?php include "header.php"; ?>
<section class="main">
<div class="d-flex flex-column">
<div class="stats d-flex justify-content-center gap-4 bg-dark-subtle p-2 py-3 m-3 rounded ">
<div
class="statsEvents d-flex flex-column justify-content-center align-items-center bg-black text-white p-2 rounded shadow-sm">
<span>Total Events</span>
<span id="eventsCount"><?php echo $eventsCount; ?></span>
</div>
</div>
<div class="createEvent d-flex justify-content-center gap-3">
  <a class="btn btn-outline-primary rounded d-flex align-items-center my-2" href="create_event.php">
    <i class="fas fa-plus"></i>&nbsp; Publish New Event
  </a>
</div>
<div class="eventsDiv d-flex flex-column my-2">
<h2 class="text-center fw-bold" style="font-family: arial;">My Events</h2>
<?php
if($showFirstPage!=""){
  echo "<div class='text-center text-danger'>$showFirstPage</div>";
}
if(mysqli_num_rows($eventsSql)>0){
?>
<div class="eventCards d-flex flex-column my-2 p-1 gap-3">
<?php
while($events = mysqli_fetch_assoc($eventsSql)){
  $formattedDate = date('M d, Y', strtotime($events['event_date']));
  
  ?>
 <div class="card shadow text-center eventCard eventNo<?php echo $events['event_id']; ?> mx-3 border-2">
  <div class="card-header">
    Event
  </div>
  <div class="card-body">
    <h5 class="card-title mb-3"><?php
    if(strlen($events['event_title'])>60){
        echo substr($events['event_title'],0,60)."...";
        }else{
          echo($events['event_title']);
        }
        ?></h5>
    <button class="btn btn-danger me-4" onclick="deleteEvent(<?php echo $events['event_id']; ?>)"><i class="fas fa-trash-alt"></i>&nbsp; Delete</button>
    <a href="edit_event.php?eventid=<?php echo $events['event_id']; ?>" class="btn btn-primary ms-4"><i class="fas fa-edit"></i>&nbsp; Edit</a>
  </div>
  <div class="card-footer text-muted">
    <i class="fas fa-calendar"></i>&nbsp; <?php echo $formattedDate; ?>
  </div>
</div>
<?php
}
?>
</div>
<?php
  
  $totalPost = mysqli_fetch_assoc(mysqli_query($mysqli,"select count(*) as totalPost from events"))['totalPost'];
    
    $totalPage = ceil($totalPost/$postPerPage);
    if($currentPage == 1){
      $prevDisabledOrNot = 'disabled';
    }else{
      $prevDisabledOrNot = '';
    }
    
    if($currentPage == $totalPage){
      $nextDisabledOrNot = 'disabled';
    }else{
      $nextDisabledOrNot = '';
    }
    ?>
    <nav class="mt-4 mb-2" aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <li class="page-item <?php echo $prevDisabledOrNot; ?>">
          <a class="page-link" href="?page=<?php echo $currentPage-1; ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
       </li>
       <?php
       
       for ($i = 1; $i <= $totalPage ; $i++) {
         if($currentPage==$i){
           $activeOrNot='active';
         }else{
           $activeOrNot='';
         }
          echo "<li class='page-item $activeOrNot'><a class='page-link' href='?page=$i'>$i</a></li>";
       }
       
       ?>
       <li class="page-item <?php echo $nextDisabledOrNot; ?>">
          <a class="page-link" href="?page=<?php echo $currentPage+1; ?>" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
       </li>
      </ul>
</nav>
 <?php   
  
}
else if (mysqli_num_rows($eventsSql) == 0) {
  if($currentPage==1){
    echo '<p class="noblogs text-center text-black">No events.</p>';
  }else{
    echo '<p class="noblogs text-center text-black">You\'ve reached beyond the last blog page.</p>';
  }
}
else{
  echo '<p class="errblogs text-center text-danger">Something Went Wrong!</p>';
}
?>
</div>

</div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let admin_id = '<?php echo $admin_id; ?>';
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

 function deleteEvent(id) {
    let delIcon;

    Swal.fire({
      title: 'Are you sure?',
      text: 'You won\'t be able to recover this event!',
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
            url: 'delete_event.php',
            type: 'post',
            data: 'eventid=' + id,
            dataType: 'json',
            success: function(delData) {
              if (delData['status'] == 'success') {
                delIcon = 'success';
                $('.eventNo'+id).remove()
                $.ajax({
                  url:'fetchTotalStats.php',
                  type:'get',
                  data:'stats=get',
                  dataType:'json',
                  success:function(stats){
                    if(stats['status']=='success'){
                     $("#eventsCount").text(stats['eventsCount'])
                    }
                  }
                })
                
                if($('.eventCards').find('.eventCard').length === 0){
                  $('.eventCards').append('<p class="text-center"><i class="fas fa-sync"></i>&nbsp; Refreshing the page. Please wait...</p>')
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
  }
  
</script>
</body>
</html>