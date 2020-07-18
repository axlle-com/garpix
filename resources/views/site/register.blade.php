@extends('layout')
@section('content')
    <section class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 text-left">
                    <h1>Регистрация</h1>
                </div>
                <div class="col-md-4">
                    {{ Breadcrumbs::render('register') }}
                </div>
            </div>
        </div>
    </section>
    @include('errors')
    <div class="v-page-wrap has-right-sidebar has-one-sidebar">
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    {{Form::open([
                            'route'	=> 'register',
                            'files'	=>	false,
                            'class'	=>	'v-signup v-register',
                    ])}}
                    <div class="form-group">
                        <h2 class="mb-2 mt-0">Регистрация</h2>
                    </div>
                    <div class="form-group">
                        <label>Имя <span class="required">*</span></label>
                        <input type="text" value="{{old('name')}}" placeholder="Имя" maxlength="100" class="form-control" name="name" id="name">
                    </div>
                    <div class="form-group">
                        <label>E-mail адрес <span class="required">*</span></label>
                        <input type="text" value="{{old('email')}}" placeholder="E-mail адрес" maxlength="100" class="form-control" name="email" id="email">
                    </div>

                    <div class="form-group">
                        <label>Пароль <span class="required">*</span></label>
                        <input name="password" type="password" placeholder="Пароль" class="form-control input-lg">
                    </div>

                    <div class="row">
                        <div class="col-4 pull-left">
                            <button type="submit" class="btn btn-primary no-margin-bottom no-margin-right">Зарегистрироваться</button>
                        </div>
                    </div>
                    <p class="text-center pull-top-small">
                        Если у вас есть аккаунт вы можете <a class="v-link" href="{{route('login')}}">авторизоваться</a>
                    </p>
                    {{Form::close()}}
                </div>
                @include('site._sidebar')
            </div>
        </div>
    </div>
@endsection
