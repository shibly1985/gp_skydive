 <!DOCTYPE html>
    <html>
        <head>
            <title>Display</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" type="text/css" href="<?php echo URL;?>/screen/css/style.css">
            <link rel="stylesheet" type="text/css" href="<?php echo URL;?>/screen/css/font-awesome.min.css">
            <link rel="stylesheet" type="text/css" href="<?php echo URL;?>/screen/css/bootstrap.min.css">
            <script src="<?php echo URL;?>/screen/js/bootstrap.min.js"></script>
            <script src="<?php echo URL;?>/vendors/jquery/dist/jquery.min.js"></script>
        </head>
        <body>
        <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <h1 class="text-center login-title">Sign in</h1>
                <div class="account-wall">
                    <?php
                        if(isset($_POST['login'])){
                            $uName = $_POST['uName'];
                            $uPass = $_POST['uPass'];
                            if($uName == SCREEN_LOGIN && $uPass == SCREEN_PASS){
                                $_SESSION['screen_login']  = $uName;
                                $general->redirect('?screen');
                            }
                            else{
                                echo '<p class="alert alert-danger">Invalid Username or Password.</p>';
                            }
                        }
                    ?>
                    <form class="form-signin" method="POST">
                    <input name="uName" type="text"  class="form-control" placeholder="Email" required autofocus>
                    <input name="uPass" type="password" class="form-control" placeholder="Password" required>
                    <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">
                        Sign in</button>
                    <label class="checkbox pull-left">
                        <input type="checkbox" value="remember-me">
                        Remember me
                    </label>
                    <a href="#" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>