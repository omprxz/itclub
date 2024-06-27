<?php
include_once('../action/checklogin.php');
require('../action/conn.php');
$admin_id = $_SESSION['admin_id'];
$query = "SELECT admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $result->free_result();
}

$fetchLevel = mysqli_query($mysqli, "select admin_id,admin_name,admin_level from adminCreds order by admin_level desc");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Admin Levels</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" />

</head>
<body>
  <?php include 'header.php'; ?>
  <section class="main">
    <h1 class="text-center my-3 mb-1 fw-bold">Admins</h1>
     <?php
if($admin_level < 7){
  echo "<p class='my-2' style='color:red;font-size:16px;text-align:center;'>This is not for you.</p>";
}
?>
    <div class="adminsList">
      <div class="admin d-flex fles-row flex-nowrap justify-content-around align-items-center fw-bold text-decoration-underline my-2 mb-4">
        <div>Admin Name</div>
        <div>Level</div>
      </div>
      
      <?php
      while($fetchLevels = mysqli_fetch_assoc($fetchLevel)){
        ?>
     <div class="admin d-flex fles-row flex-nowrap justify-content-around align-items-center my-3">
        <div>
          <?php echo $fetchLevels['admin_name']; ?>
        </div>
        <div>
          <select class="form-select levels" data-admin-id="<?php echo $fetchLevels['admin_id']; ?>">
            <?php 
            $totalAdminLevels = 7;
            for($level=1;$level<= $totalAdminLevels;$level++){
              $isLevel = "";
              if($fetchLevels['admin_level']==$level){
                $isLevel = "selected";
              }
              
              echo "<option value='$level' $isLevel>$level</option>";
              
            }
            
            ?>
            </select>
        </div>
      </div>
        
        <?php
      }
      ?>
      
    </div>
  </section>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
   const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter',
                    Swal.stopTimer)
                toast.addEventListener('mouseleave',
                    Swal.resumeTimer)
            }
        })
  
    $(function(){
  $('.levels').on('change', function() {
    let admin_id = $(this).data("admin-id");
    console.log(admin_id);
    admin_level = $(this).val()
    // Sending AJAX request
    $.ajax({
      url: 'changeAdminDets.php',
      method: 'POST',
      dataType:'json',
      data: {
        admin_id: admin_id,
        action: 'changeLevel',
        new_admin_level: admin_level
      },
      success: function(response) {
        if(response['status'] == 'success'){
          Toast.fire({
            icon:'success',
            title:response['message']
          })
        }else{
          Toast.fire({
            icon:'error',
            title:response['message']
          })
        }
      },
      error: function(xhr, status, error) {
        console.error(status + ": " + error);
      }
    });
  });
});
  </script>
</body>
</html>