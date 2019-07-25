<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Prueba Login Facebook</title>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script>
  const data = 'name,first_name,middle_name,last_name,address,email,gender,hometown,birthday,link,location';

  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
        var userID = response.authResponse.userID;

      // Logged into your app and Facebook.
      testAPI(userID);
    } 
    else {
      // The person is not logged into your app or we are unable to tell.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '448834859036125',
      cookie     : true,  // enable cookies to allow the server to access 
                          // the session
      xfbml      : true,  // parse social plugins on this page
      version    : 'v3.3' // The Graph API version to use for the call
    });

    // Now that we've initialized the JavaScript SDK, we call 
    // FB.getLoginStatus().  This function gets the state of the
    // person visiting this page and can return one of three states to
    // the callback you provide.  They can be:
    //
    // 1. Logged into your app ('connected')
    // 2. Logged into Facebook, but not your app ('not_authorized')
    // 3. Not logged into Facebook and can't tell if they are logged into
    //    your app or not.
    //
    // These three cases are handled in the callback function.

    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];

    if (d.getElementById(id)) return;

    js = d.createElement(s); 
    js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI(userID) {
    var check_email = false;

    FB.api('/'+userID+'/permissions', (response) => {
      check_email = checkEmailPermission(response.data);

      if(check_email){
        getUserData(userID);
      }
      else {
        alert("Debe permitir que podamos usar su email para poder iniciar sesión con Facebook");
      }
    });
    
    return check_email;
  }

  function getUserData(userID) {
    FB.api('/'+userID, {fields: data}, function(response) {
      console.log(response);
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + 
        '! <br /> Nombre: ' + response.first_name +
        '<br /> Primer apellido: ' + response.middle_name +
        '<br /> Segundo apellido: ' + response.last_name +
        '<br /> Address: ' + response.address +
        '<br /> Hometown: ' + response.hometown.name +
        '<br /> Location: ' + response.location.name +
        '<br /> Email: ' + response.email +
        '<br /> Género: ' + response.gender +
        '<br /> Día de nacimiento: ' + response.birthday +
        '<br /> Link: ' + response.link;
    });
  }

  function checkEmailPermission(permissions) {
    var emailPermissionGranted = false;

    for (permission of permissions) {
      if (permission.permission == 'email' && permission.status == 'granted') {
        emailPermissionGranted = true;
      }
    }

    return emailPermissionGranted;
  }
</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->
  <fb:login-button scope="public_profile,email,user_gender,user_birthday,user_hometown,user_link,user_location" auth_type="rerequest" onlogin="checkLoginState();">
  </fb:login-button>

  <div id="status">
  </div>

</body>
</html>