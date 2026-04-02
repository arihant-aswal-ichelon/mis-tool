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
  window.fbAsyncInit = function() {
    FB.init({
        appId      : '990471978938397',
        xfbml      : true,
        version    : 'v22.0'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

  function subscribeApp(page_id, page_access_token, page_name) {
    let client_id = '<?php echo $client_id; ?>';
    console.log('Subscribing page to app! ' + page_id);
    FB.api(
      '/' + page_id + '/subscribed_apps',
      'post',
      {access_token: page_access_token, subscribed_fields: ['feed', 'leadgen']},
      function(response) {
            // ajax start
            $.ajaxSetup({
                headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") }
            });
            $.ajax({
                url: "/facebook-auth/<?php echo $client_id; ?>",
                method: "POST",
                dataType: "json",
                data: { page_access_token, page_id, page_name, client_id},
                success: function(result) {
                    if (result.status) {
                        alert("Page has been subscribed successfully. Click on another page to subscribe that too.");
                        window.location.reload();
                    }
                }
            });
            // ajax end
            console.log('Successfully subscribed page', response);
      }
    );
  }
    
  // Only works after `FB.init` is calleds
  function myFacebookLogin() {
    FB.login(function(response){
      console.log('Successfully logged in', response);
      FB.api('/me/accounts',{"limit":"200"}, function(response) {
        console.log('Successfully retrieved pages', response);
        var pages = response.data;
        var ul = document.getElementById('list');
        for (var i = 0, len = pages.length; i < len; i++) {
          var page = pages[i];
          var li = document.createElement('li');
          var a = document.createElement('a');
          a.href = "#";
          a.onclick = subscribeApp.bind(this, page.id, page.access_token, page.name);
          a.innerHTML = page.name;
          li.appendChild(a);
          ul.appendChild(li);
        }
        var heading = document.createElement('h3');
        heading.innerHTML = "Click on page to subscribe it.";
        ul.prepend(heading);
      });
    }, {scope: 'pages_manage_metadata, leads_retrieval, instagram_basic, pages_read_engagement, instagram_manage_insights, pages_manage_ads, instagram_manage_events, whatsapp_business_management'});
  }
</script>
@endsection
