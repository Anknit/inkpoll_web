<?php
    session_start();
    require_once __DIR__.'/server/OperateDB/DbMgrInterface.php';
    require_once __DIR__.'/ogtags.php';
?>
    <html data-ng-app="app" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible">
        <meta name="theme-color" content="#222">
        <title>Inkpoll</title>
        <base href="/feeddasm/" />
        <?php
    getogtags();
    ?>
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
            <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
            <link type="text/css" rel="stylesheet" href="styles/style.css" />
            <link type="text/css" rel="stylesheet" href="styles/polllist.css" />
            <link rel="icon" href="images/favicon.ico" />
    </head>

    <body class="container-fluid">
        <header class="row" data-ng-controller="headerCtrl as head">
            <nav class="navbar navbar-inverse navbar-fixed-top navbar-theme">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                        <a class="navbar-brand" href="./" target="_self">
                            <img class="brand-image" alt="Inkpoll" src="images/logo-v2-120.png" />
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="main-navbar">
                        <ul class="nav navbar-nav hidden-xs cat-nav-bar">
                            <li data-ng-repeat="item in head.pollCatArr">
                                <a data-ng-href="{{'polls/' + item.catName}}" data-ng-bind="item.catName" data-ng-class="{'active':activeCat == item.catName}"></a>
                            </li>
                            <!--
                        <li><a href="./">Home</a></li>
                        <li><a href="categories">Categories</a></li>
                        <li><a href="pollcreater" data-auth-user-only>Creator</a></li>
-->
                        </ul>
                        <form class="navbar-form navbar-right">
                            <button data-ng-if="!user.name" type="button" class="btn btn-link" data-ng-click="head.showAuthModal('login')">
                            Login
                        </button>
                            <button data-ng-if="!user.name" type="button" class="btn btn-warning" data-ng-click="head.showAuthModal('signup')">
                            Sign up
                        </button>
                            <a data-ng-if="user.name" role="button" class="btn btn-success" href="pollcreater">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;Create
                            </a>
                            <div class="pull-left">
                                <div class="dropdown" data-ng-if="user.name">
                                    <button class="btn btn-link dropdown-toggle" type="button" id="userdropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="color:#fff;text-decoration:none;">
                                        <span data-ng-bind="'Hi, ' + user.name.split(' ')[0] + ' '"></span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="userdropdown" style="position:relative; float:none;">
                                        <li><a data-ng-href="{{'users/' + user.id +'/' + user.name.split(' ').join('-')}}">My Profile</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a role="button" data-ng-click="head.logout()">Logout</a></li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.navbar-collapse -->
                <!-- /.container-fluid -->
            </nav>
            <div class="visible-xs col-xs-12 cat-list-banner">
                <ul class="list-inline">
                    <li data-ng-repeat="item in head.pollCatArr">
                        <a data-ng-href="{{'polls/' + item.catName}}" data-ng-bind="item.catName" data-ng-class="{'active':activeCat == item.catName}"></a>
                    </li>
                </ul>
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" id="auth-modal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" data-ng-show="head.authmode == 'login'">Login</h4>
                            <h4 class="modal-title" data-ng-show="head.authmode == 'signup'">Sign up</h4>
                            <h4 class="modal-title" data-ng-show="head.authmode == 'forgotpswd'">Reset Password</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row" data-ng-hide="head.authmode=='forgotpswd'">
                                <div class="col-xs-12">
                                    <h4 data-ng-show="head.authmode== 'login'">Connect with social account</h4>
                                    <h4 data-ng-show="head.authmode== 'signup'">Signup with social account</h4>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6 form-group">
                                            <div class="fb-login-button" data-display="popup" data-size="large" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false"></div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 form-group">
                                            <div id="google-signin"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-ng-show="head.authmode=='login'">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4>Login with email address</h4>
                                        <div class="form">
                                            <div class="form-group">
                                                <label class="control-label">Email</label>
                                                <input type="email" class="form-control" placeholder="Email address" data-ng-model="head.authvars.login.email" />
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Password</label>
                                                <input type="password" class="form-control" placeholder="Password" data-ng-model="head.authvars.login.password" />
                                            </div>
                                            <div class="form-group">
                                                <a role="button" data-ng-click="head.authmode = 'forgotpswd'">
                                                    <span class="text-muted">Forgot Password ?</span>
                                                </a>
                                            </div>
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <label>
                                                <input type="checkbox" data-ng-model="head.authvars.login.remember" />
                                                Remember me
                                            </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-info" data-ng-click="head.authaction.login()">Login</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4>New to <span class="text-brand-inline">Inkpoll</span>. <a role="button" data-ng-click="head.authmode='signup'"><span class="text-info">Create Account</span></a></h4>
                                    </div>
                                </div>
                            </div>
                            <div data-ng-show="head.authmode=='signup'">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4>Signup with email address</h4>
                                        <div class="form">
                                            <div class="form-group">
                                                <label class="control-label">Email</label>
                                                <input type="email" class="form-control" placeholder="Email address" data-ng-model="head.authvars.signup.email" />
                                            </div>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-info" data-ng-click="head.authaction.signup()">Signup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4>Already have an account. <a role="button" data-ng-click="head.authmode='login'"><span class="text-info">Login</span></a></h4>
                                    </div>
                                </div>
                            </div>
                            <div data-ng-show="head.authmode=='forgotpswd'">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4>Reset you password</h4>
                                        <div class="form">
                                            <div class="form-group">
                                                <label class="control-label">Email</label>
                                                <input type="email" class="form-control" placeholder="Email address" data-ng-model="head.authvars.forgot.email" />
                                            </div>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-info" data-ng-click="head.authaction.forgotpswd()">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4>Back to <a role="button" data-ng-click="head.authmode='login'"><span class="text-info">Login</span></a></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </header>
        <div id="fb-root"></div>

        <div class="row" style="padding-top:20px;">
            <div class="col-xs-12 col-md-8">
                <div class="row" data-ng-view></div>
            </div>
            <div class="col-xs-12 col-md-4 side-banner-container">
                <div class="row" style="margin:0px;" data-ng-hide="hideAboutDesc" data-ng-cloak>
                    <div class="col-xs-12 side-banner-section">
                        <p class="home-description text-justified">
                            Inkpoll is a community-powered platform, where you can vote for interesting polls or can create a poll and share it with your friends.
                        </p>
                        <h4>
                            Have something interesting to ask, create your own poll in seconds.
                        </h4>
                        <a auth-user-only class="btn btn-success text-center" href="pollcreater">Create Poll</a>
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
        <script>
            <?php
            if(isset($_SESSION['userId'])) {
                $responseArray = array(
                    'id' => $_SESSION['userId'],
                    'name' => $_SESSION['userName'],
                    'email' => $_SESSION['userEmail'],
                    'type' => $_SESSION['userType']
                );
        ?>
            var userSessData = <?php echo json_encode($responseArray); ?>
            <?php
            }
        ?>

        </script>
        <!--
        <script>
              (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

                  ga('create', 'UA-63332438-2', 'auto');
                  ga('send', 'pageview');
        </script>
-->
        <script type="application/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="application/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
        <script type="application/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-route.min.js"></script>
        <script type="application/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="application/javascript" src="app/app.js"></script>
        <script type="application/javascript" src="app/app.controllers.js"></script>
        <script type="application/javascript" src="app/app.services.js"></script>
        <script type="application/javascript" src="app/app.directives.js"></script>
    </body>

    </html>
