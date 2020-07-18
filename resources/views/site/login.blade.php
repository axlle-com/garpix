@extends('layout')
@section('content')
    <section class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 text-left">
                    <h1>Авторизация</h1>
                </div>
                <div class="col-md-4">
                    {{ Breadcrumbs::render('login') }}
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
                                'route'	=> 'login',
                                'files'	=>	false,
                                'class'	=>	'v-signup',
                            ])
                    }}
                        <div class="form-group">
                            <h2 class="mb-2 mt-0">Авторизация</h2>
                        </div>
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="text" value="{{old('email')}}" placeholder="email" maxlength="100" class="form-control" name="email" id="email">
                        </div>
                        <div class="form-group">
                            <label>Пароль <span class="required">*</span></label>
                            <input type="password" value="" placeholder="Пароль" maxlength="100" class="form-control" name="password" id="password">
                        </div>
                        <div class="row">
                            <div class="col pull-left">
                                <button type="submit" class="btn btn-primary no-margin-bottom no-margin-right">Вход</button>
                            </div>
                        </div>
                        <p class="text-center pull-top-small">
                            Если у вас не аккаунта вы можете <a class="v-link" href="{{route('register')}}">зарегистрироваться</a>
                        </p>
                    {{Form::close()}}
                </div>
                @include('site._sidebar')
            </div>
        </div>
    </div>
@endsection
