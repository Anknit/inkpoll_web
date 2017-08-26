<?php
$userSessData = array();
if(isset($_SESSION['userId'])) {
  $userSessData = array(
    'id' => $_SESSION['userId'],
    'name' => $_SESSION['userName'],
    'email' => $_SESSION['userEmail'],
    'type' => $_SESSION['userType']
  );
}
?>
<script>
  var userSessData = <?php echo json_encode($userSessData); ?>
</script>
<?php
if($mode == 'prod') {
  ?>
  <script>
  (function(i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function() {
      (i[r].q = i[r].q || []).push(arguments)
    }, i[r].l = 1 * new Date();
    a = s.createElement(o),
    m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m)
  })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

  ga('create', 'UA-63332438-2', 'auto');
  ga('send', 'pageview');

  </script>
  <?php
}
  ?>
  <script type="application/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="application/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
  <script type="application/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-route.min.js"></script>
  <script type="application/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="application/javascript" src="app/modules/auth/index.js"></script>
  <script type="application/javascript" src="app/modules/admin/category-management/index.js"></script>
  <script type="application/javascript" src="app/app.js"></script>
  <script type="application/javascript" src="app/app.controllers.js"></script>
  <script type="application/javascript" src="app/app.services.js"></script>
  <script type="application/javascript" src="app/services/catMgr.js"></script>
  <script type="application/javascript" src="app/directives/header/header-controller.js"></script>
  <script type="application/javascript" src="app/directives/header/index.js"></script>
  <script type="application/javascript" src="app/directives/ip-fb-video/index.js"></script>
  <script type="application/javascript" src="app/directives/ip-inline-footer/index.js"></script>
  <script type="application/javascript" src="app/directives/ip-social-connect/index.js"></script>
  <script type="application/javascript" src="app/app.directives.js"></script>
