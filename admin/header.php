<?php
include_once('../action/checklogin.php');
require '../action/conn.php';
$admin_id=$_SESSION["admin_id"];
$query = "SELECT admin_profilepic,admin_name,admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $admin_name = $row['admin_name'];
    $admin_profilepic = $row['admin_profilepic'];
    
    $result->free_result();
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Mukta:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    body{
      margin: 0;
    }
    .nav{
      height: 45px;
      padding: 0 10px;
      border-bottom: 1px solid #1a2636;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 16px;
      background: linear-gradient(to left, #0EA9E8, #0CB4BE);
      font-family: Mukta;
    }
    .nav-left-home{
      display: flex;
      align-items: flex-start;
      gap: 5px;
    }
    .nav-left-home i{
      font-size: 19px;
    }
    .nav-right-profile{
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 17px;
      font-weight: 600;
    }
    .nav-left-home,.nav-right-profile{
      color: black;
      text-decoration: none;
    }
    .nav-right-profile img{
      width: 28px;
      height: 28px;
      border-radius: 50%;
      border: 1px solid #1a2636;
      aspect-ratio: 1/1;
      object-fit: cover;
    }
  </style>
<nav class="nav">
    <div class="nav-left">
      <a href="../admin" class="nav-left-home">
      <i class="fas fa-home"></i>
      </a>
    </div>
    <div class="nav-right">
      <a href="profile.php" class="nav-right-profile">
        <span>Hii, <?php echo $admin_name; ?></span>
        <img src="images/admins/<?php echo $admin_profilepic; ?>" alt="<?php echo $admin_name; ?>">
      </a>
    </div>
  </nav>