<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <title>axlle.com</title>
    <meta name="keywords" content="axlle.com" />
    <meta name="description" content="axlle.com">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" type="image/png" href="/img/favicon.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Play:400,700&display=swap&subset=cyrillic" rel="stylesheet">
    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
<header id="header" class="header-effect-shrink" data-plugin-options="{'stickyEnabled': true, 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': true, 'stickyStartAt': 120}">
    <div class="header-body">
        <div class="header-container container">
            <div class="header-row">
                <div class="header-column justify-content-start">
                    <div class="header-logo">
                        <a href="/">
                            <img alt="Vertex" width="130" src="img/logo.png">
                        </a>
                    </div>
                </div>
                <div class="header-column justify-content-end">
                    <div class="header-nav header-nav-light-dropdown">
                        <div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1">
                            <nav class="collapse">
                                <ul class="nav flex-column flex-lg-row" id="mainNav">
                                    <li class="dropdown dropdown-mega">
                                        <a class="dropdown-item active" href="/">
                                            Главная
                                        </a>
                                    </li>
                                    <li class="dropdown dropdown-mega">
                                        <a class="dropdown-item" href="#">
                                            Пункт
                                        </a>
                                    </li>
                                    <li class="dropdown">
                                        <a class="dropdown-item" href="#">
                                            Пункт
                                        </a>
                                    </li>
                                    <li class="dropdown">
                                        <a class="dropdown-item" href="#">
                                            Страницы
                                        </a>
                                    </li>
                                    <li class="dropdown dropdown-mega dropdown-mega-signin ml-lg-3">
                                        @if(URL::current() != route('order'))
                                            <a class="dropdown-item pl-lg-4" href="#">Корзина</a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <div class="dropdown-mega-content">
                                                        <div class="row">
                                                            <div class="col basket-top js-basket-mini-inner">
                                                                <?php
                                                                $basketItem = \App\Basket::getBasket();
                                                                $totalPrice = 0;
                                                                ?>
                                                                @if(count($basketItem))
                                                                    <ul class="v-list-v2">
                                                                        @foreach($basketItem as $value)
                                                                            <li class="product-basket-mini">
                                                                                <span class="product-title">{{ $value['title'] }}</span>
                                                                                <span class="product-title">{{ \App\components\Helper::getPrice($value['price']) }}</span>
                                                                            </li>
                                                                            <?php $totalPrice += $value['price']?>
                                                                        @endforeach
                                                                    </ul>
                                                                    <span>Итого: {{ \App\components\Helper::getPrice($totalPrice) }}</span>
                                                                    <a href="{{ route('order') }}" class="btn btn-sm btn-outline-success">Перейти в корзину</a>
                                                                @else
                                                                    <span>Ваша корзина пуста</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        @endif
                                    </li>
                                    @if(Auth::check())
                                        <li>
                                            <a class="profile pl-lg-4 {{ (URL::current() == route('profile.show')) ? 'active' : '' }}" href="{{route('profile.show')}}">Профиль</a>
                                        </li>
                                        <li>
                                            <a class="profile pl-lg-4" href="{{route('logout')}}">Выход</a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="profile pl-lg-4 {{ (URL::current() == route('login.form')) ? 'active' : '' }}" href="{{route('login.form')}}">Вход</a>
                                        </li>
                                        <li>
                                            <a class="profile pl-lg-4 {{ (URL::current() == route('register.form')) ? 'active' : '' }}" href="{{route('register.form')}}">Регистрация</a>
                                        </li>
                                    @endif
                                    @if(Auth::check() && Auth::user()->is_admin)
                                        <li>
                                            <a class="profile pl-lg-4" href="{{route('index')}}">Админ</a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                        <button class="header-btn-collapse-nav ml-3" data-toggle="collapse" data-target=".header-nav-main nav">
                                <span class="hamburguer">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </span>
                            <span class="close">
                                    <span></span>
                                    <span></span>
                                </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div role="main" class="main">
    @if(session('status'))
        <section class="alert-message">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-success">
                            {{session('status')}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    @yield('content')

    <div class="footer-wrap">
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <section class="widget">
                            <img alt="" src="/img/logo-white.png" style="height: 40px; margin-bottom: 20px;">
                            <p class="pull-bottom-small">
                                Разнообразный и богатый опыт постоянный количественный рост и сфера нашей активности требуют от нас анализа новых предложений. Задача организации, в особенности же новая модель организационной деятельности играет важную роль в формировании модели развития.                            </p>
                            <p>
                                <a href="#">Читать →</a>
                            </p>
                        </section>
                    </div>
                    <div class="col-sm-3">
                        <section class="widget v-recent-entry-widget">
                            <div class="widget-heading">
                                <h4>Категории</h4>
                                <div class="horizontal-break"></div>
                            </div>
                            <ul>
                            </ul>
                        </section>
                    </div>
                    <div class="col-sm-3">
                        <section class="widget v-recent-entry-widget">
                            <div class="widget-heading">
                                <h4>Последние статьи</h4>
                                <div class="horizontal-break"></div>
                            </div>
                            <ul>
                            </ul>
                        </section>
                    </div>
                    <div class="col-sm-3">
                        <section class="widget v-recent-entry-widget">
                            <div class="widget-heading">
                                <h4>Популярные статьи</h4>
                                <div class="horizontal-break"></div>
                            </div>
                            <ul>
                            </ul>
                        </section>
                    </div>
                </div>
            </div>
        </footer>

        <div class="copyright">
            <div class="container">
                <p>© Copyright {{date('Y')}} by axlle.com. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</div>
<script src="/js/site.js"></script>
<script src="/js/main.js"></script>
</body>
</html>
