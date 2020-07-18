<?php

    use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

    Breadcrumbs::for('home', function ($trail) {
        $trail->push('Главная', route('home'));
    });

    Breadcrumbs::for('search', function ($trail) {
        $trail->parent('home');
        $trail->push('Поиск', route('post.search'));
    });

    Breadcrumbs::for('login', function ($trail) {
        $trail->parent('home');
        $trail->push('Вход', route('login.form'));
    });

    Breadcrumbs::for('register', function ($trail) {
        $trail->parent('home');
        $trail->push('Регистрация', route('register.form'));
    });

    Breadcrumbs::for('profile', function ($trail) {
        $trail->parent('home');
        $trail->push('Профиль', route('profile.show'));
    });

    Breadcrumbs::for('category', function ($trail, $category) {
        $trail->parent('home');
        $trail->push($category->title, route('category.show', $category->slug));
    });

    Breadcrumbs::for('tag', function ($trail, $tag) {
        $trail->parent('home');
        $trail->push($tag->title, route('tag.show', $tag->slug));
    });
    Breadcrumbs::for('author', function ($trail, $author) {
        $trail->parent('home');
        $trail->push($author->name, route('author.show', $author->id));
    });

    Breadcrumbs::for('post', function ($trail, $post) {
        $trail->parent('category', $post->category);
        $trail->push($post->title, route('post.show', $post->slug));
    });
