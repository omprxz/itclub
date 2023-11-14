<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}
?>

<meta name="viewport" content="width=device-width">
<link rel="icon" href="img/favicon.ico" type="image/x-icon">
<title>Extra Values</title>
<div class="text-center m-3">
  <form method="get">

<?php
require('../action/conn.php');

if(isset($_GET['update'])){
  $keys=$_GET['keys'];
  $valueInput=$mysqli -> real_escape_string($_GET['valueInput']);
  $value=$_GET['value'];
  
  $sql="update extra set $value = '$valueInput' where key1 = '$keys'";
  $qsql=mysqli_query($mysqli,$sql);
  
}
$fetchKeys="select * from extra";
$QfetchKeys=mysqli_query($mysqli,$fetchKeys);
if(mysqli_num_rows($QfetchKeys)>0){?>
<select class="keys form-control" name="keys">
<?php
  while($key2=mysqli_fetch_assoc($QfetchKeys)){
    $value1=$key2['value1'];
    ?>
  <option value="<?php echo $key2['key1']; ?>"><?php echo $key2['key1']; ?></option>
    <?php
  }
  ?>
  </select>
  <?php
}else {
  echo( 'No key');
}
?>
<br />
<label>Value 1 <input class="form-check-input value" type="radio" name="value" id="value1" value="value1" checked /></label> <br />
<label>Value 2 <input class="form-check-value value" type="radio" name="value" id="value2" value="value2" /> </label> <br />
<label>Value 3 <input class="form-check-value value" type="radio" name="value" id="value3" value="value3" /> </label> <br />
<input type="text" class="form-control my-2" name="valueInput" id="valueInput" placeholder="YYYY-MM-DD HH:MM:SS" value="<?php echo $value1; ?>" />
<button class="btn btn-dark" type="submit" name="update" class="update">Update</button>
</form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>