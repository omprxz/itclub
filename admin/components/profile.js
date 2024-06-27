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
$(".profilepic").on("change", function(event) {
  const file = event.target.files[0];
  const image = $('.profilepic-prev');
  const reader = new FileReader();
  reader.onload = function(e) {
    image.attr('src', e.target.result);
  };
  reader.readAsDataURL(file);
})

$(document).ready(function() {
  const bioTextarea = $('#bio');
  const countSpan = $('#count');
  const remainingSpan = $('#remaining');
  function updateRemain() {
    const maxLength = 900;
    const currentLength = bioTextarea.val().length;
    const remaining = maxLength - currentLength;

    countSpan.text(remaining);

    if (remaining < 20) {
      remainingSpan.css('color', 'red');
    } else {
      remainingSpan.css('color', '');
    }

    if (currentLength > maxLength) {
      bioTextarea.val(bioTextarea.val().substring(0, maxLength));
      countSpan.text(0);
    }
  }
  updateRemain();
  bioTextarea.on('input', updateRemain);
  $('#edit').click(function() {
    $('.profile-form input[type="text"],.profile-form input[type="email"],.profile-form input[type="file"],.profile-form textarea').removeAttr('disabled');
    $('#edit').hide()
    $('#update').show()
    $('.label-pp').css('visibility', 'visible')
    $('.profilepic-note').show()
    $('#remaining').show()
    $('#remove-pp').attr('disabled','disabled')
    $('#profilepic-prev').addClass('cur-pointer')
  })
  $('#update').click(function() {
    //let updatedData = {};
    let updatedData = new FormData();
    const newName = $('#name').val();
    if (newName !== currentName) {
      updatedData.append('name', newName);
    }

    const newEmail = $('#email').val();
    if (newEmail !== currentEmail) {
      //updatedData.email = newEmail;
      updatedData.append('email', newEmail);
    }

    const newUsername = $('#username').val();
    if (newUsername !== currentUsername) {
      updatedData.append('username', newUsername);
    }

    const newDesignation = $('#designation').val();
    if (newDesignation !== currentDesignation) {
      updatedData.append('designation', newDesignation);
    }

    const newBio = $('#bio').val();
    if (newBio !== currentBio) {
      updatedData.append('bio', newBio);
    }

    const profilePic = document.getElementById('profilepic').files[0];
    if (profilePic) {
      updatedData.append('profilepic', profilePic);
    }
    // Check if required fields are empty
    const requiredFields = [newName,
      newEmail,
      newUsername];
    const fieldNames = ['Name',
      'Email',
      'Username'];
    let emptyField = '';
    for (let i = 0; i < requiredFields.length; i++) {
      if (requiredFields[i].trim() === '') {
        emptyField = fieldNames[i];
        break;
      }
    }

    if (emptyField !== '') {
      Toast.fire({
        icon: 'error',
        title: `${emptyField} cannot be empty`
      });
    } else {
      let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if(emailRegex.test($('#email').val())){
      updatedData.append('previousProfilepic',previousProfilepic)
      $.ajax({
        url: 'update_profile.php',
        type: 'POST',
        dataType:'json',
        data: updatedData,
        processData: false,
        contentType: false,
        beforeSend: function(){
          $('#update').html('<div style="width:25px;height:25px;" class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>')
           $('#update').attr('disabled','disabled')
        },
        success: function(data) {
          $('#update').text('Update Details')
          $('#update').removeAttr('disabled')
          console.log(data)
          let icon;
          if (data['status'] === 'failed') {
            icon = 'error';
          } else {
            icon = 'success';
            $('.profile-form input[type="text"],.profile-form input[type="email"],.profile-form input[type="file"],.profile-form textarea').attr('disabled', 'disabled');
            $('#update').hide();
            $('#edit').show();
            $('.label-pp').css('visibility', 'hidden');
            $('.profilepic-note').hide();
            $('#remaining').hide();
            $('#remove-pp').removeAttr('disabled')
            $('#profilepic-prev').removeClass('cur-pointer')
          }
          Toast.fire({
            icon: icon,
            title: data.result
          });

        },
        error: function(err) {
          $('#update').text('Update Details')
          $('#update').removeAttr('disabled')
          console.log(err)
          Toast.fire({
            icon: 'error',
            title: 'Something went wrong'
          });
        }
      });
      }else{
        Toast.fire({
          icon:'error',
          title:"Invalid email"
        })
      }
    }
  });
  $('#remove-pp').click(function(){
    let remIcon;
    $.ajax({
      url:'remove_pp.php',
      type:'post',
      data:'remove=1',
      dataType:'json',
      beforeSend: function(){
        $('#remove-pp').html('<div style="width:25px;height:25px;" class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>')
        $('#remove-pp').attr('disabled','disabled')
      },
      success:function(remData){
        $('#remove-pp').text('Remove Profile Pic')
        $('#remove-pp').removeAttr('disabled')
        if(remData['status']=='success'){
          remIcon='success'
          $('.profilepic-prev').attr('src','images/admins/profilepic.png');
        }else{
          remIcon='error'
        }
        Toast.fire({
          icon:remIcon,
          title:remData['result']
        })
      },
      error:function(remErr){
        $('#remove-pp').text('Remove Profile Pic')
        $('#remove-pp').removeAttr('disabled')
        Toast.fire({
          icon:'error',
          title:"Error "+ remErr
        })
      }
    })
  })
  $('.changePassword').click(function () {
    var currentPass = $('#currentPass').val();
    var newPass1 = $('#newPass1').val();
    var newPass2 = $('#newPass2').val();

    if (currentPass === '' || newPass1 === '' || newPass2 === '') {
      Toast.fire({
        icon: 'error',
        title: 'Please fill in all fields!'
      });
      return;
    }

    if (newPass1 !== newPass2) {
      Toast.fire({
        icon: 'error',
        title: 'New passwords do not match!'
      });
      return;
    }

    $.ajax({
      type: 'POST',
      url: 'change_password.php',
      data: {
        currentPass: currentPass,
        newPass: newPass1
      },
      dataType:'json',
      success: function (cpData) {
        if (cpData['status'] === 'success') {
          Toast.fire({
            icon: 'success',
            title: cpData['result']
          })
            $('#changePasswordDiv').modal('hide');
          document.getElementById('changePasswordForm').reset()
        } else {
          Toast.fire({
            icon: 'error',
            title: cpData['result']
          });
        }
      },
      error: function () {
        Toast.fire({
          icon: 'error',
          title: 'Something Went Wrong!'
        });
      }
    });
  });

  $('.logoutBtn').click(function () {
    Swal.fire({
  title: "Are you sure to logout?",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#d63030",
  cancelButtonColor: "#767676",
  confirmButtonText: "Yes, Logout!"
}).then((result) => {
  if (result.isConfirmed) {
    window.location.href = "logout.php";
  }
});
  })
});
