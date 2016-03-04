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
        '/plugins/timepicker/bootstrap-timepicker.min.css',
        '/plugins/select2/select2.min.css',
        '/plugins/datatables/dataTables.bootstrap.css',
        '/plugins/adminLTE/css/AdminLTE.min.css',
        '/plugins/adminLTE/css/skin-blue.min.css',
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
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <?= $this->Html->link(
                    '<span class="logo-lg">'.$this->Html->image('logo.png', ['class' => 'logo-img']).'<b>SDD-UFG</b></span>',
                    '/',
                    [
                        'escape' => false,
                        'class' => 'logo'
                    ]
                )?>

                <nav class="navbar navbar-static-top" role="navigation">
                    <?= $this->element('user-nav-menu') ?>
                </nav>
            </header>

            <aside class="main-sidebar">
                <?= $this->element('sidebar-menu') ?>
            </aside>

            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        <?= $this->fetch('title') ?>
                    </h1>
                    <ol class="breadcrumb">
                        <?= $this->fetch('breadcrumb') ?>
                    </ol>
                </section>

                <section class="content">
                    <?= $this->Flash->render() ?>
                    <?= $this->Flash->render('auth') ?>
                    <?= $this->fetch('content') ?>
                </section>
            </div>

            <footer class="main-footer">
                <strong>Copyright &copy; 2016<?= date('Y') > 2016 ? '-'.date('Y') : '' ?> - Sistema de distribuição de disciplinas - </strong>
                <a href="#">Universidade Federal de Goiás</a>
            </footer>
        </div>

        <!-- REQUIRED JS SCRIPTS -->
        <?= $this->Html->script([
            '/plugins/jQuery/jQuery-2.1.4.min.js',
            '/plugins/bootstrap/js/bootstrap.min.js',
            '/plugins/datepicker/bootstrap-datepicker.js',
            '/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js',
            '/plugins/timepicker/bootstrap-timepicker.min.js',
            '/plugins/select2/select2.full.min.js',
            '/plugins/select2/i18n/pt-BR.js',
            '/plugins/datatables/jquery.dataTables.min.js',
            '/plugins/adminLTE/js/app.min.js',
            'sdd-ufg.js'
        ]) ?>

        <?= $this->fetch('script') ?>
    </body>
</html>
