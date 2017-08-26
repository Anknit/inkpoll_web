<?php
require_once __DIR__.'/require.php';
require_once __DIR__.'/ogtags.php';
?>
<html data-ng-app="app" lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible">
  <meta name="theme-color" content="#222">
  <title ng-bind="Config.pageTitle"></title>
  <base href="<?php echo base_href;?>" />
  <?php
  getogtags();
  ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="styles/app.css" />
  <link rel="icon" href="images/favicon.ico" />
</head>

<body>
  <div class="ng-view"></div>
  <div id="fb-root"></div>
  <?php
  require_once __DIR__.'/scripttags.php';
  ?>
</body>
</html>
