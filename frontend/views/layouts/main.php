<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>
    <div class="gtco-loader"></div>
    <div id="page">
        <!-- <div class="page-inner"> -->
        <nav class="gtco-nav bg-dark" role="navigation">
            <div class="gtco-container">

                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div id="gtco-logo"><?= Html::a('Airbender<em>.</em>', ['site/index']) ?></div>
                    </div>
                    <div class="col-xs-8 text-right menu-1">
                        <ul>
                            <li><?= Html::a('Flights', ['/flight/select-airport']) ?></li>
                            <li><?= Html::a('About', ['/site/about']) ?></li>
                            <?php if (Yii::$app->user->isGuest) { ?>
                                <li><?= Html::a('Login', ['/site/login'], ['id' => 'login-button']) ?> </li>
                            <?php } else { ?>
                                <li class="has-dropdown">
                                    <a href="#"><?= Yii::$app->user->identity->username ?></a>
                                    <ul class="dropdown">
                                        <li><?= Html::a('Profile', ['/client/index']) ?></li>
                                        <li><?= Html::a('My balance', ['/balance-req/index']) ?></li>
                                        <li><?= Html::a('My tickets', ['/ticket/index']) ?></li>
                                        <li><?= Html::a('My receipts', ['/receipt/index']) ?></li>
                                        <li><?= Html::a('Logout', ['/site/logout'], ['data-method' => 'post']) ?></li>
                                    </ul>
                                </li>
                            <?php } ?>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </nav>

        <main role="main" class="flex-shrink-0">
            <div class="container">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </main>

        <footer id="gtco-footer" role="contentinfo">
            <div class="gtco-container">
                <div class="row row-p	b-md">

                    <div class="col-md-4">
                        <div class="gtco-widget">
                            <h3>About Us</h3>
                            <p>Airbender is a university project designed to emulate a airline website where you can search and buy tickets for multiple different locations!</p>
                        </div>
                    </div>

                    <div class="col-md-2 col-md-push-1">
                        <div class="gtco-widget">
                        </div>
                    </div>


                    <div class="col-md-3 col-md-push-1">
                        <div class="gtco-widget">
                            <h3>Get In Touch</h3>
                            <ul class="gtco-quick-contact">
                                <li><a href="https://github.com/MarcoPadeiroIPL/ProjetoPLSI"><i class="icon-github"></i>Github</a></li>
                            </ul>
                        </div>
                    </div>

                </div>

                <div class="row copyright">
                    <div class="col-md-12">
                        <p class="pull-left">
                            <small class="block">Marco Padeiro, Tomás Moura e Marco Harbuzyuk. All Rights Reserved.</small>
                            <small class="block">Designed by <a href="https://freehtml5.co/" target="_blank">FreeHTML5.co</a> Demo Images: <a href="http://unsplash.com/" target="_blank">Unsplash</a></small>
                        </p>
                        <p class="pull-right">
                        <ul class="gtco-social-icons pull-right">
                            <li><a href="https://github.com/MarcoPadeiroIPL/ProjetoPLSI"><i class="icon-github"></i></a></li>
                        </ul>
                        </p>
                    </div>
                </div>

            </div>
        </footer>
    </div>
    <div class="gototop js-top">
        <a href="#" class="js-gotop"><i class="icon-arrow-up"></i></a>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage();
