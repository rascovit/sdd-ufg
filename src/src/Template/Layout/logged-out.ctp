<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css([
        '/plugins/bootstrap/css/bootstrap.min.css',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
        'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
        '/plugins/datepicker/datepicker3.css',
        '/plugins/adminLTE/css/AdminLTE.min.css',
        'sdd-ufg.css'
    ]) ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo"><b>SDD-UFG</b></div>

            <?= $this->Flash->render() ?>
            <?= $this->Flash->render('auth') ?>

            <div class="login-box-body">
                <?= $this->fetch('content') ?>
            </div>
        </div>

        <!-- REQUIRED JS SCRIPTS -->
        <?= $this->Html->script([
            '/plugins/jQuery/jQuery-2.1.4.min.js',
            '/plugins/bootstrap/js/bootstrap.min.js',
            '/plugins/datepicker/bootstrap-datepicker.js',
            '/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js'
        ]) ?>

        <?= $this->fetch('script') ?>
    </body>
</html>
