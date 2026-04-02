@extends('layouts.page-app')
@section("content")

<meta name="_token" content="{{csrf_token()}}" />
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Facebook Subscription</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$client_id); ?>">Properties</a></li>
                            <li class="breadcrumb-item active">Facebook Subscription</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <?php if(!empty($subscription)){ ?>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan='2'>
                                                    <h5>Subscribed Facebook Page</h5>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>Page ID</td>
                                                <td><?php echo $subscription->page_id; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Page Name</td>
                                                <td><?php echo $subscription->page_name; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Last Subscribed On</td>
                                                <td><?php echo $subscription->created_at; ?></td>
                                            </tr>
                                        </table>
                                <?php } ?>
                            </div>
                            <div class="col-md-4 mx-auto">
                                <ul id="list"></ul>
                                <button onclick="myFacebookLogin()" class="btn btn-primary">Login with Facebook</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
  // Global variables to track SDK state
  var fbSDKLoaded = false;
  var fbSDKInitialized = false;
  var fbLoginPending = false;

  // Load Facebook SDK
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.onload = function() {
       fbSDKLoaded = true;
       console.log('Facebook SDK loaded');
     };
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

  // Initialize Facebook SDK once loaded
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '904530936725782',
      xfbml      : true,
      version    : 'v22.0',
      status     : true, // check login status
      cookie     : true, // enable cookies
      autoLogAppEvents : true
    });
    
    fbSDKInitialized = true;
    console.log('Facebook SDK initialized');
    
    // Execute any pending login request
    if (fbLoginPending) {
      fbLoginPending = false;
      executeFacebookLogin();
    }
  };

  // Function to check and wait for SDK initialization
  function ensureFBInit(callback) {
    var checkCount = 0;
    var maxChecks = 50; // 5 seconds maximum wait
    
    function checkInit() {
      checkCount++;
      
      if (fbSDKInitialized && typeof FB !== 'undefined') {
        console.log('FB is ready, executing callback');
        callback();
      } else if (checkCount < maxChecks) {
        console.log('Waiting for FB initialization... attempt ' + checkCount);
        setTimeout(checkInit, 100);
      } else {
        console.error('Facebook SDK failed to initialize after ' + (maxChecks * 100) + 'ms');
        alert('Facebook login is not available at the moment. Please refresh the page and try again.');
      }
    }
    
    // Start checking
    setTimeout(checkInit, 100);
  }

  // Main login function
  function myFacebookLogin() {
    if (!fbSDKLoaded) {
      console.log('SDK not loaded yet, waiting...');
      alert('Please wait for Facebook to load...');
      return;
    }
    
    if (!fbSDKInitialized) {
      console.log('SDK not initialized, queueing login request');
      fbLoginPending = true;
      ensureFBInit(function() {
        executeFacebookLogin();
      });
    } else {
      executeFacebookLogin();
    }
  }

  // Actual login execution
  function executeFacebookLogin() {
    console.log('Executing Facebook login...');
    
    FB.getLoginStatus(function(response) {
      console.log('Login status:', response.status);
      
      if (response.status === 'connected') {
        // Already logged in, get pages
        getFacebookPages(response.authResponse.accessToken);
      } else {
        // Need to login
        FB.login(function(loginResponse){
          if (loginResponse.authResponse) {
            console.log('Successfully logged in', loginResponse);
            getFacebookPages(loginResponse.authResponse.accessToken);
          } else {
            console.log('User cancelled login or did not fully authorize.');
            alert("Login cancelled or permissions not granted.");
          }
        }, {
          scope: 'pages_manage_metadata, leads_retrieval, pages_read_engagement, pages_manage_ads, pages_show_list',
          return_scopes: true,
          auth_type: 'rerequest'
        });
      }
    });
  }

  // Function to get user's pages
  function getFacebookPages(userAccessToken) {
    console.log('Getting Facebook pages...');
    
    FB.api('/me/accounts', {"limit":"200"}, function(response) {
      if (response && !response.error) {
        console.log('Successfully retrieved pages', response);
        displayPagesList(response.data, userAccessToken);
      } else {
        console.error('Error getting pages:', response.error);
        alert("Error retrieving pages: " + (response.error ? response.error.message : 'Unknown error'));
      }
    });
  }

  // Display pages list
  function displayPagesList(pages, userAccessToken) {
    var ul = document.getElementById('list');
    if (!ul) {
      console.error('Element with id "list" not found');
      return;
    }
    
    ul.innerHTML = ''; // Clear existing list
    
    // Add heading
    var heading = document.createElement('h3');
    heading.innerHTML = "Click on a page to subscribe it:";
    heading.style.marginBottom = '15px';
    ul.appendChild(heading);
    
    if (pages.length === 0) {
      var noPages = document.createElement('p');
      noPages.innerHTML = "No Facebook pages found. Make sure you're an admin of at least one page.";
      noPages.style.color = 'red';
      ul.appendChild(noPages);
      return;
    }
    
    for (var i = 0, len = pages.length; i < len; i++) {
      var page = pages[i];
      var li = document.createElement('li');
      li.style.marginBottom = '8px';
      
      var button = document.createElement('button');
      button.innerHTML = page.name + ' (' + page.id + ')';
      button.style.cssText = 'cursor: pointer; padding: 8px 12px; background: #4267B2; color: white; border: none; border-radius: 4px; width: 100%; text-align: left;';
      button.onclick = (function(pageId, pageToken, pageName, userToken) {
        return function() {
          subscribeApp(pageId, userToken, pageName);
        };
      })(page.id, page.access_token, page.name, userAccessToken);
      
      li.appendChild(button);
      ul.appendChild(li);
    }
  }

  // Function to get long-lived token and subscribe page
  async function subscribeApp(page_id, user_access_token, page_name) {
    console.log('Processing page: ' + page_name);
    
    try {
      // Get long-lived user token
      const longLivedUserToken = await getLongLivedToken(user_access_token);
      
      // Get page token from long-lived user token
      const page_access_token = await getPageAccessToken(longLivedUserToken, page_id);
      
      console.log('Subscribing page to app! ' + page_id);
      
      // Subscribe the page
      FB.api(
        '/' + page_id + '/subscribed_apps',
        'post',
        {access_token: page_access_token, subscribed_fields: ['feed', 'leadgen']},
        function(response) {
          if (response && !response.error) {
            // Send to server
            sendTokenToServer(page_access_token, page_id, page_name, longLivedUserToken);
          } else {
            console.error('Subscription error:', response.error);
            alert("Error subscribing page: " + (response.error ? response.error.message : 'Unknown error'));
          }
        }
      );
    } catch (error) {
      console.error('Error in subscribeApp:', error);
      alert("Error processing page. Please try again.");
    }
  }

  // Function to get long-lived token
  function getLongLivedToken(shortLivedToken) {
    return new Promise((resolve, reject) => {
      FB.api(
        '/oauth/access_token',
        'get',
        {
          grant_type: 'fb_exchange_token',
          client_id: '904530936725782', // ADD YOUR APP ID
          client_secret: 'ec87fa0f8adf8337e4fa7f1c9b09343b', // ADD YOUR APP SECRET
          fb_exchange_token: shortLivedToken
        },
        function(response) {
          if (!response || response.error) {
            console.error('Error getting long-lived token:', response.error);
            reject(response.error);
          } else {
            console.log('Long-lived token obtained');
            resolve(response.access_token);
          }
        }
      );
    });
  }

  // Function to get page access token
  function getPageAccessToken(userAccessToken, pageId) {
    return new Promise((resolve, reject) => {
      FB.api(
        '/' + pageId,
        'get',
        {
          fields: 'access_token',
          access_token: userAccessToken
        },
        function(response) {
          if (!response || response.error) {
            console.error('Error getting page token:', response.error);
            reject(response.error);
          } else {
            console.log('Page token obtained');
            resolve(response.access_token);
          }
        }
      );
    });
  }

  // Function to send token to server
  function sendTokenToServer(page_access_token, page_id, page_name, long_lived_user_token) {
    let client_id = '<?php echo $client_id; ?>';
    
    $.ajaxSetup({
      headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") }
    });
    
    $.ajax({
      url: "/facebook-auth/<?php echo $client_id; ?>",
      method: "POST",
      dataType: "json",
      data: { 
        page_access_token: page_access_token, 
        page_id: page_id, 
        page_name: page_name, 
        client_id: client_id,
        long_lived_user_token: long_lived_user_token
      },
      success: function(result) {
        if (result.status) {
          alert("✅ Page '" + page_name + "' has been subscribed successfully!");
          // Optional: Remove the button for this page
          var buttons = document.querySelectorAll('button');
          buttons.forEach(function(button) {
            if (button.innerHTML.includes(page_id)) {
              button.style.background = '#4CAF50';
              button.innerHTML = '✅ ' + button.innerHTML;
              button.disabled = true;
            }
          });
        } else {
          alert("Error: " + (result.message || "Unknown error occurred"));
        }
      },
      error: function(xhr, status, error) {
        console.error('Server error:', error);
        alert("Error saving token. Please try again.");
      }
    });
  }

  // Add a button to trigger login (example HTML)
  // <button onclick="myFacebookLogin()" style="padding: 10px 20px; background: #4267B2; color: white; border: none; border-radius: 4px; cursor: pointer;">
  //   Connect Facebook Pages
  // </button>
</script>
@endsection
