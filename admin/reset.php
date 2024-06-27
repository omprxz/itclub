<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      Reset password
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@100;200;300;400;500;600;700;00;900&display=swap" rel="stylesheet">
    <style>
      *{
        font-family: 'Mukta';
      }
      body{
        margin:0;
      }
      .verify-otp,.change-password{
        display: none;
      }
    </style>
  </head>
  <body>
    <div class="container-fluid">
      <form class="resetPass d-flex flex-column justify-content-center mx-1 my-3  py-2 px-1" id="resetPass">
        <h3 class="text-center mb-3 fw-bold">
          Reset Password
        </h3>
        <input type="text" name="userid" class="form-control mb-3 userid" placeholder="Email or username" />
        <input type="number" name="otp" class="form-control mb-3 otp" placeholder="OTP" disabled>
        <input type="password" name="newpass1" class="form-control mb-3 newpass1" placeholder="New Password" disabled>
        <input type="text" name="newpass2" class="form-control mb-4 newpass2" placeholder="Confirm New Password" disabled>
        <button type="button" class="btn btn-outline-primary mx-auto px-4 action get-otp">
          Get OTP
        </button>
        <button type="button" class="btn btn-outline-primary mx-auto px-4 action verify-otp">
          Verify OTP
        </button>
        <button type="button" class="btn btn-outline-success mx-auto px-4 action change-password">
          Change Password
        </button>
        
      </form>
      <div class="links text-center">
        <a href="login.php" class="nav-link text-primary">Go to login</a>
      </div>
    </div>
    
  <script src="../eruda.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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

  let userid,otp;
  function retVar(a){
    return a;
  }
  
    $('.get-otp').click(function(){
      if($('.userid').val()!=""){
      let icon;
      $.ajax({
        url:'resetAction.php',
        type:'post',
        dataType:'json',
        data:'action=getOtp&userid='+$('.userid').val(),
        beforeSend:function(){
          $('.get-otp').html('<div style="width:25px;height:25px;" class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>')
          $('.get-otp').attr('disabled','')
        },
        success: function(otpData){
          $('.get-otp').removeAttr('disabled')
          $('.get-otp').text('Get OTP')
          if(otpData['status']=='success'){
            icon='success'
            $('.userid').attr('disabled','')
            userid=retVar($('.userid').val())
            $('.get-otp').hide('100')
            $('.verify-otp').show('100')
            $('.userid').toggleClass('bg-success-subtle border border-success-subtle')
            $('.otp').removeAttr('disabled')
          }else{
            icon='error'
          }
           Toast.fire({
              icon:icon,
              title:otpData['result']
            })
        },
        error: function(err1){
          $('.get-otp').removeAttr('disabled')
          $('.get-otp').text('Get OTP')
          console.log(err1)
          Toast.fire({
            icon:'error',
            title:'Something went wrong!'
          })
        }
      })
      }else{
        Toast.fire({
          icon:'warning',
          title:'Enter email or username'
        })
      }
    })
    
    $('.verify-otp').click(function(){
    if($('.otp').val()!=""){
      let icon;
      $.ajax({
        url:'resetAction.php',
        type:'post',
        dataType:'json',
        data:'action=verifyOtp&otp='+$('.otp').val()+'&userid='+userid,
        beforeSend:function(){
          $('.verify-otp').html('<div style="width:25px;height:25px;" class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>')
          $('.verify-otp').attr('disabled','')
        },
        success: function(verifyData){
          $('.verify-otp').removeAttr('disabled')
          $('.verify-otp').text('Verify OTP')
          if(verifyData['status']=='success'){
            icon='success'
            $('.otp').attr('disabled','')
            otp=retVar($('.otp').val())
            $('.verify-otp').hide('100')
            $('.change-password').show('100')
            $('.otp').toggleClass('bg-success-subtle border border-success-subtle')
            $('.newpass1').removeAttr('disabled')
            $('.newpass2').removeAttr('disabled')
          }else{
            icon='error'
          }
           Toast.fire({
              icon:icon,
              title:verifyData['result']
            })
        },
        error: function(err2){
          $('.verify-otp').removeAttr('disabled')
          $('.verify-otp').text('Verify OTP')
          console.log(err2)
          Toast.fire({
            icon:'error',
            title:'Something went wrong!'
          })
        }
      })
    }else{
        Toast.fire({
          icon:'warning',
          title:'Enter OTP'
        })
      }
    })
    
    $('.change-password').click(function(){
    if($('.newpass1').val()!=""){
      if($('.newpass1').val()===$('.newpass2').val()){
      let icon;
      $.ajax({
        url:'resetAction.php',
        type:'post',
        dataType:'json',
        data:'action=changePass&otp='+otp+'&userid='+userid+'&pass='+$('.newpass1').val(),
        beforeSend:function(){
          $('.change-password').html('<div style="width:25px;height:25px;" class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>')
          $('.change-password').attr('disabled','')
        },
        success: function(passData){
          $('.change-password').removeAttr('disabled')
          $('.change-password').text('Change Password')
          if(passData['status']=='success'){
            icon='success'
            $('.newpass1').attr('disabled','')
            $('.newpass2').attr('disabled','')
            pass=retVar($('.newpass1').val())
            $('.change-password').text('Changed')
            $('.change-password').attr('disabled','')
            $('.change-password').removeClass('.btn-outline-success')
            $('.change-password').addClass('btn-secondary')
            $('.newpass1').toggleClass('bg-success-subtle border border-success-subtle')
            $('.newpass2').toggleClass('bg-success-subtle border border-success-subtle')
          }else{
            icon='error'
          }
           Toast.fire({
              icon:icon,
              title:passData['result']
            })
        },
        error: function(err3){
           $('.change-password').removeAttr('disabled')
          $('.change-password').text('Change Password')
          console.log(err3)
          Toast.fire({
            icon:'error',
            title:'Something went wrong!'
          })
        }
      })
      }else{
        Toast.fire({
          icon:'warning',
          title:'New password doesn\'t match'
        })
      }
    }else{
        Toast.fire({
          icon:'warning',
          title:'Enter New Password'
        })
      }
    })
  </script>
  
  </body>
</html>