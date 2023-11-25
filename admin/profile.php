<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

require('../action/conn.php');
$admin_id=$_SESSION["admin_id"];
$query = "SELECT admin_name, admin_profilepic, admin_email, admin_username, admin_designation, admin_bio, admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_name = $row['admin_name'];
    $admin_profilepic = $row['admin_profilepic'];
    $admin_email = $row['admin_email'];
    $admin_username = $row['admin_username'];
    $admin_designation = $row['admin_designation'];
    $admin_bio = $row['admin_bio'];
    $admin_level = $row['admin_level'];

    $result->free_result();
}
?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile - <?php echo $admin_name;?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
<style>
  .label-pp{
    position: absolute;
    top: 70%;
    left: 70%;
    visibility: hidden;
  }
  .profilepic-note{
    display: none;
  }
  .update{
    display: none;
  }
  .edit{
    display: block;
  }
  #remaining{
    display: none;
  }
</style>
</head>
<body>
  <script>
    let currentName = `<?php echo $admin_name; ?>`;
    let currentEmail = `<?php echo $admin_email; ?>`;
    let currentUsername = `<?php echo $admin_username; ?>`;
    let currentDesignation = `<?php echo $admin_designation; ?>`;
    let currentBio = `<?php echo $admin_bio; ?>`;
    let previousProfilepic = `<?php echo $admin_profilepic; ?>`;
  </script>
  
  <?php include 'header.php'; ?>
  
  <section class="container">
    <form class="profile-form d-flex flex-column align-items-center" method="post" action="" enctype="multipart/form-data">
      <div class="profile-img position-relative rounded-circle mt-4" style="width:120px;height:120px">
        <img src="images/admins/<?php echo $admin_profilepic;?>" alt="<?php echo $admin_name;?>" class="rounded-circle profilepic-prev" style="width:100%;height:100%">
        <input type="file" class="profilepic" style="display:none;" name="profilepic" id="profilepic" accept="image/*" disabled>
        <label for="profilepic" class="label-pp d-flex flex-column text-center rounded-circle bg-warning">
          <i class="fas fa-pen d-flex align-items-center justify-content-center" style="width:30px;height:30px"></i>
        </label>
      </div>
        <label for="profilepic" class="text-center profilepic-note" style="margin-top:5px;color:red;font-size:13px;">Upload square image for better view (Max size 500 KB)</label>
      <div class="remove-ppDiv text-center mb-0 mt-2">
        <button type="button" id="remove-pp" class="remove-pp btn btn-outline-danger rounded">Remove Profile Pic</button>
      </div>
      <div class="mt-2" style="width:100%;">
        <label for="name" class="fw-bold">Name: </label>
        <input class="form-control" type="text" placeholder="Change Name" value="<?php echo $admin_name;?>" id="name" disabled required>
      </div>
      <div class="mt-2" style="width:100%;">
        <label for="email" class="fw-bold">Email: </label>
        <input class="form-control" type="email" value="<?php echo $admin_email;?>" id="email" placeholder="Change Email" disabled required>
      </div>
      <div class="mt-2" style="width:100%;">
        <label for="username" class="fw-bold">Username: </label>
        <input class="form-control" type="text" value="<?php echo $admin_username;?>" id="username" placeholder="Change Username" disabled required>
      </div>
      <div class="mt-2" style="width:100%;">
        <label for="designation" class="fw-bold">Designation: </label>
        <input class="form-control" type="text" value="<?php echo $admin_designation;?>" id="designation" placeholder="Change Designation" disabled>
      </div>
      <div class="mt-2" style="width:100%;">
        <label for="bio" class="fw-bold">Bio: </label>
        <textarea class="form-control" rows="4" id="bio" placeholder="Change Bio" maxlength="900" disabled><?php echo $admin_bio;?></textarea>
        <span id="remaining" class="">Characters remaining: <span id="count">900</span></span>
       </div>
      <div class="mt-2 d-flex justify-content-center" style="width:100%;">
        <input type="button" name="update" id="update" class="btn btn-success update" value="Update Details">
        <input type="button" id="edit" class="edit btn btn-primary" value="Edit Details">
      </div>
    </form>
    <hr>
    <div class="d-flex flex-row justify-content-center gap-4 my-2">
      <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#changePasswordDiv">
  Change Password
</button>
      <button class="btn btn-outline-secondary logoutBtn">Logout</button>
    </div>
    
    <div class="modal fade" id="changePasswordDiv" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Change Password</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form id="changePasswordForm" class="modal-body changePasswordForm">
        <div class="mt-2" style="width:100%;">
        <label for="name" class="fw-bold">Current Password: </label>
        <input class="form-control" type="password" placeholder="Current Password" id="currentPass" required>
      </div>
        <div class="mt-2" style="width:100%;">
        <label for="name" class="fw-bold">New Password: </label>
        <input class="form-control" type="password" placeholder="New Password" id="newPass1" required>
      </div>
        <div class="mt-2" style="width:100%;">
        <label for="name" class="fw-bold">Confirm New Password: </label>
        <input class="form-control" type="text" placeholder="Confirm New Password" id="newPass2" required>
      </div>
      </form>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-outline-success changePassword">Change</button>
      </div>
    </div>
  </div>
</div>

  </section>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="components/profile.js"></script>
</body>
</html>