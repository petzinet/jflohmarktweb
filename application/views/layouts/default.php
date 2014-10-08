<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="./favicon.ico">
        <title>Registrierung Flohmarkt der KitaBü</title>
        <link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url();?>css/custom.css" rel="stylesheet">
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <div class="container">
            <div class="header">
                <ul class="nav nav-pills pull-right">
                    <!--li class="active"><a href="/">Registrierung</a></li>
                    <li><a href="#">Impressum</a></li-->
                    <li><a href="http://foerderverein-kitabue.de">foerderverein-kitabue.de</a></li>
                </ul>
                <h3 class="text-muted">Flohmarkt des Fördervereins der KitaBü</h3>
            </div>

            <?php echo $template['body']; ?>

            <div class="footer">
                <!--p>&copy; Company 2014</p-->
            </div>

        </div>
    </body>
</html>
