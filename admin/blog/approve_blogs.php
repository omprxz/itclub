<?php
include_once('../../action/conn.php');
$postPerPage=10;
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
// Fetch specific columns from blogs
$offset = $postPerPage*($currentPage-1);

$statusSyntax = "";

if(isset($_GET['status']) && !empty($_GET['status'])){
    $selectedStatus = $_GET['status'];
    $selectedStatus=explode(',',$selectedStatus);
    $statusConditions = [];

    foreach($selectedStatus as $status){
        switch($status){
            case "approved":
                $statusConditions[] = "approved >= 1";
                break;
            case "rejected":
                $statusConditions[] = "approved <= -1";
                break;
            case "pending":
                $statusConditions[] = "approved = 0";
                break;
            case "featured":
                $statusConditions[] = "featured > 0";
                break;
            case "notfeatured":
                $statusConditions[] = "featured <= 0";
                break;
        }
    }

    if(!empty($statusConditions)){
        $statusSyntax = "WHERE " . implode(" OR ", $statusConditions);
    }
}


$blogsSql = mysqli_query($mysqli, "SELECT id, title, thumbnail, createdTime, approved, featured, adminId FROM blogs $statusSyntax order by createdTime desc limit $postPerPage offset $offset");
$selectedOptions = isset($_GET['status']) ? explode(',', $_GET['status']) : [];

function isSelected($option, $selectedOptions) {
    return in_array($option, $selectedOptions) ? 'selected' : '';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Approve Blogs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

</head>
<body>

<?php include 'header.php'; ?>

<section class="main">
  <h3 class="text-center fw-bold mt-3">Manage Blogs</h3>
  <div class="w-75 mx-auto">
    <label for="appStatus">Filters: </label>
    <select id="appStatus" name="appStatus[]" class="form-select mx-auto" multiple="">
      <optgroup label="Visibility">
      <option value="pending" <?php echo isSelected('pending', $selectedOptions); ?>>Pending</option>
      <option value="approved" <?php echo isSelected('approved', $selectedOptions); ?>>Approved</option>
      <option value="rejected" <?php echo isSelected('rejected', $selectedOptions); ?>>Rejected</option>
      </optgroup>
      <optgroup label="Featured">
      <option value="featured" <?php echo isSelected('featured', $selectedOptions); ?>>Featured</option>
      <option value="notfeatured" <?php echo isSelected('notfeatured', $selectedOptions); ?>>Not Featured</option>
      </optgroup>
    </select>
  </div>
  <?php
if($showFirstPage!=""){
  echo "<div class='text-center text-danger my-2'>$showFirstPage</div>";
}
if(mysqli_num_rows($blogsSql)>0){
?>
    <div class="cards d-flex flex-wrap justify-content-center my-3 mb-5">
      <?php
while($blogs = mysqli_fetch_assoc($blogsSql)){
  $authorName = mysqli_fetch_assoc(mysqli_query($mysqli,"select admin_name from adminCreds where admin_id = ".$blogs['adminId']))['admin_name'];
    $approvedIcon = "fa-check-circle";

    $formattedDate = date('M d, Y', strtotime($blogs['createdTime']));

    if($blogs['approved'] == 0){
        $approvedIcon = "fas fa-times-circle text-danger";
    }
    else if ($blogs['approved'] < 0) {
        $approvedIcon = "fas fa-hourglass text-warning";
        $rejClass = "disabled";
    } else {
        $approvedIcon = "fas fa-check-circle text-success";
        $appClass = "disabled";
    }
  
  ?>
  <div class="card my-2" style="width: 18rem;">
  <img src="../../blogs/thumbnails/<?php echo $blogs['thumbnail']; ?>" class="card-img-top" alt="Thumbnail Image">
  <div class="card-body">
    <h5 class="card-title mb-3"><a class="nav-link" href="edit_blog.php?blogid=<?php echo $blogs['id']; ?>"><?php echo $title = (strlen($blogs['title'])>=80) ? substr($blogs['title'],0,80)."..." : $blogs['title'] ; ?></a></h5>
    <p class="card-text mb-2">Date: <?php echo $formattedDate; ?></p>
    <p class="card-text mb-2">Author: <?php echo $authorName; ?></p>
    <p class="card-text mb-3">Status: <?php echo $statusApp = ($blogs['approved']==0) ? '<i class="fas fa-clock text-primary"></i> Pending' : (($blogs['approved'] < 0) ? '<i class="fas fa-times-circle text-danger"></i> Rejected' : '<i class="fas fa-check-circle text-success"></i> Approved'); ?></p>
    <div class="feature-div d-flex justify-content-center align-items-center gap-3 mb-3">
      <label for="">Feature: </label>
      <input type="number" class="feature-val w-25 form-control rounded" value="<?php echo $blogs['featured']; ?>" max="99999" min="-99999" data-blogid="<?php echo $blogs['id']; ?>">
    </div>
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a class="btn btn-primary w-25" href="../../blogs/blog.php?blogid=<?php echo $blogs['id']; ?>&preview=show">
          <i class="fas fa-eye"></i>
        </a>
        <button class="rejectBlog btn w-25 btn-danger <?php echo $rejClass; ?> blogAppBtn<?php echo $blogs['id']; ?>" data-blogid="<?php echo $blogs['id']; ?>">
          <i class="fas fa-times"></i>
        </button>
        <button class="approveBlog btn w-25 btn-success <?php echo $appClass; ?> blogAppBtn<?php echo $blogs['id']; ?>" data-blogid="<?php echo $blogs['id']; ?>">
          <i class="fas fa-check"></i>
        </button>
    </div>
  </div>
</div>
<?php
}
?>
</div>
<?php
  
  $totalPost = mysqli_fetch_assoc(mysqli_query($mysqli,"select count(*) as totalPost from blogs"))['totalPost'];
  if($totalPost > $postPerPage){
    
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

}
else if (mysqli_num_rows($blogsSql) == 0) {
  if($currentPage==1){
    echo '<p class="noblogs text-center text-black my-5">No blogs.</p>';
  }else{
    echo '<p class="noblogs text-center text-black my-5">You\'ve reached beyond the last page.</p>';
  }
}
else{
  echo '<p class="errblogs text-center text-danger">Something Went Wrong!</p>';
}
?>
</section>


<script src="../../eruda.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" ></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
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
$('.approveBlog, .rejectBlog').on('click', function() {
    var blogId = $(this).data('blogid');
    var status = $(this).hasClass('approveBlog') ? 'approve' : 'reject';

    $.ajax({
        url: 'change_status.php',
        method: 'POST',
        data: { blogId: blogId, status: status },
        success: function(response) {
            response = JSON.parse(response);

            if (response.status === 'success') {
                $('.blogAppBtn'+blogId).toggleClass('disabled')
                Toast.fire({
                    icon: 'success',
                    title: response.result
                });
            } else {
                Toast.fire({
                    icon: 'error',
                    title: response.result
                });
            }
        },
        error: function(xhr, status, error) {
            Toast.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Something went wrong. Please try again.'
            });
        }
    });
});

$('#appStatus').on('change', function() {
    const selectedOptions = $(this).val();
    const queryParams = new URLSearchParams(window.location.search);
    queryParams.set('status', selectedOptions.join(','));
    window.location.href = `${window.location.pathname}?${queryParams.toString()}`;
});

$('.feature-val').on('change', function(){
  var blogId = $(this).data('blogid');
  featVal = $(this).val();
  if(typeof Number(featVal) == 'number'){
  if(featVal <= 99999 && featVal >= -999999){
    
    $.ajax({
      url: 'changeFeatured.php',
      type: 'get',
      dataType: 'json',
      data:  { blogId: blogId, featVal: featVal },
      beforeSend: function(){
        $(this).attr('disabled','disabled')
      },
      success: function(featData){
        $(this).removeAttr('disabled')
        if(featData['status'] == 'success'){
        Toast.fire({
          icon: 'success',
          title: featData.message
        })
        $(this).val(featData['updatedTo'])
        }else{
          Toast.fire({
            icon: 'error',
            title: featData.message
          })
        }
      },
      error: function(){
         $(this).removeAttr('disabled')
      }
      
    })
    
  }else{
    Toast.fire({
        icon: 'error',
        title: "Value must be b/w -99999 to 99999"
      });
  }
  }else{
    Toast.fire({
        icon: 'error',
        title: "Value must be a number."
      });
      console.log(typeof featVal + " " + featVal)
  }
})
</script>

</body>
</html>