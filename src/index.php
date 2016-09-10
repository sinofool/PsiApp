<?php
namespace ca\gearzero\psiapp;
require_once('Psi.php');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PsiApp</title>

<!-- Bootstrap -->
<link href="css/bootstrap.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<nav class="navbar navbar-default">
  <div class="container-fluid"> 
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#defaultNavbar1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
      <a class="navbar-brand" href="#">PsiApp</a>
    </div>
    <!-- /.navbar-collapse --> 
  </div>
  <!-- /.container-fluid --> 
</nav>
<div class="container">
    <?php
    $PSI = new Psi();
    foreach ($PSI->get_apps() as $item) {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $item ?></h3>
            </div>
            <div class="panel-body">
                <?php echo nl2br($PSI->get_app_info($item)) ?>
            </div>
            <div class="panel-footer">
                <div class="btn-toolbar" role="toolbar">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary" <?php echo $PSI->get_app_link($item, Psi::IOS_NAME)?>>iOS</button>
                        <button type="button" class="btn btn-success" <?php echo $PSI->get_app_link($item, Psi::ANDROID_NAME)?>>Android</button>
                        <button type="button" class="btn btn-info" <?php echo $PSI->get_app_link($item, Psi::UWP_NAME)?>>UWP</button>
                        <button type="button" class="btn btn-warning" <?php echo $PSI->get_app_link($item, Psi::MACOS_NAME)?>>macOS</button>
                        <button type="button" class="btn btn-danger" <?php echo $PSI->get_app_link($item, Psi::WINDOWS_NAME)?>>Windows</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
  </div>
  <hr>
  <div class="row">
    <div class="text-center col-md-6 col-md-offset-3">
      <p>Powered By <a href="https://psiapp.gearzero.ca" >PsiApp</a></p>
    </div>
  </div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>

<script src="js/psi.js"></script>
</body>
</html>
