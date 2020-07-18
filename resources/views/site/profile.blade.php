@extends('layout')
@section('content')
    <section class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 text-left">
                    <h1>Профиль</h1>
                </div>
                <div class="col-md-4">
                    {{ Breadcrumbs::render('profile') }}
                </div>
            </div>
        </div>
    </section>
    @include('errors')
    <div class="v-page-wrap has-right-sidebar has-one-sidebar">
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-sm-12">

                            <div class="author-info-wrap clearfix" style="margin-top: 0px;">
                                {{Form::open([
                                    'route'	=> 'profile.store',
                                    'files'	=>	true,
                                ])}}
                                    <div class="author-avatar">
                                        @if($avatar = $user->getImage())
                                            <img src="{{ $avatar }}" class="avatar photo" />
                                        @endif
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm">Имя</span>
                                        </div>
                                        <input type="text" name="name" value="{{$user->name}}" class="form-control">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm">Email</span>
                                        </div>
                                        <input type="text" name="email" value="{{$user->email}}" class="form-control">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm">пароль</span>
                                        </div>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="custom-file">
                                            <input type="file" name="avatar" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
                                            <label class="custom-file-label" for="inputGroupFile04">Выберите файл</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col pull-left">
                                            <button type="submit" class="btn btn-primary no-margin-bottom no-margin-right">Сохранить</button>
                                        </div>
                                    </div>
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                </div>
                @include('site._sidebar')
            </div>
        </div>
    </div>
@endsection
