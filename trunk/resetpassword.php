<?php
require_once __DIR__.'/config.php';
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible">
    <title>Reset Password | Inkpoll</title>
    <base href="<?php echo base_href; ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="styles/style.css" />
    <link rel="icon" href="images/favicon.ico" />
</head>

<body class="container-fluid">
    <header class="row">
        <nav class="navbar navbar-inverse navbar-fixed-top navbar-theme">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="./">
                        <img class="brand-image" alt="Inkpoll" src="images/logo-v2-120.png" />
                    </a>
                </div>
            </div>
            <!-- /.navbar-collapse -->
            <!-- /.container-fluid -->
        </nav>
    </header>
    <div class="row" style="padding-top:20px;">
        <div class="col-xs-12 col-md-8">
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="text-center">Change Password</h3>
                    <form class="form-horizontal" action="accounts/changepswd" method="post" onsubmit="return validateForm()">
                        <input type="hidden" name="linkid" />
                        <input type="hidden" name="securelink" />
                        <div class="form-group">
                            <div class="col-md-3 text-right">
                                <label class="control-label">Email</label>
                            </div>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="registeremail" placeholder="Email address" maxlength="30" readonly disabled />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3 text-right">
                                <label class="control-label">New Password</label>
                            </div>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="registerpswd" placeholder="Create password" maxlength="25" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3 text-right">
                                <label class="control-label">Confirm Password</label>
                            </div>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="confirmpswd" placeholder="Re-enter password" maxlength="25" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success center-block">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4 side-banner-container">
            <div class="row" style="margin:0px;" data-ng-hide="hideAboutDesc" data-ng-cloak>
                <div class="fb-video" data-controls="false" data-href="https://www.facebook.com/inkpoll/videos/1379191825521488/" data-width="500" data-show-text="false">
                    <blockquote cite="https://www.facebook.com/inkpoll/videos/1379191825521488/" class="fb-xfbml-parse-ignore">
                        <a href="https://www.facebook.com/inkpoll/videos/1379191825521488/" style="display:none;">About Inkpoll</a>
                        <p>
                        </p>
                        <a href="https://www.facebook.com/inkpoll/" style="display:none;">Inkpoll</a></blockquote>
                </div>
            </div>
            <div class="row" style="margin:0px;">
                <div class="col-xs-12 side-banner-section">
                    <h4>Connect with Inkpoll</h4>
                    <div class="fb-page" data-href="https://www.facebook.com/inkpoll" data-width="500" data-height="250" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 menu-links">
                    <ul class="list-inline">
                        <li>
                            <a href="about">
                                <span class="text-muted">About</span>
                            </a>
                        </li>
                        <li>
                            <a href="privacy">
                                <span class="text-muted">Privacy</span>
                            </a>
                        </li>
                        <li>
                            <a href="terms">
                                <span class="text-muted">Terms</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="copyright col-xs-12">
                    <span class="text-muted"> Inkpoll &copy; 2017</span>
                </div>
            </div>
        </div>
    </div>
    <script type="application/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="application/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        var registerObj = {};
        registerObj.email = '<?php echo $userEmail;?>';
        registerObj.secureId = '<?php echo $linkId;?>';
        registerObj.link = '<?php echo $secureLink;?>';
        jQuery(function(){
            var registerForm = document.forms[0];
            jQuery(registerForm.registeremail).val(registerObj.email);
            jQuery(registerForm.linkid).val(registerObj.secureId);
            jQuery(registerForm.securelink).val(registerObj.link);
        });
        function validateForm() {
            var registerForm = document.forms[0];
            if(registerForm.registerpswd.value.trim() != registerForm.registerpswd.value.trim()) {
                alert('Passwords do not match');
                event.preventDefault();
                return false;
            } else {
                return true;
            }
        }
    </script>
</body>

</html>