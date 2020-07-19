@extends('layout')

@section('content')
    <div class="v-page-wrap has-right-sidebar has-one-sidebar page-products">
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <div class="js-block-inner">
                        <div class="js-block-search">
                            <ul class="nav nav-tabs nav-tabs-default">
                                <li class="nav-item">
                                    @if(isset($post['name']))
                                        @if($post['name'] == 'asc')
                                            <a href="/sort?name=desc" class="nav-link active show js-sort">Отсортировать по названию (по убыванию)</a>
                                        @else
                                            <a href="/sort?name=asc" class="nav-link active show js-sort">Отсортировать по названию (по возрастанию)</a>
                                        @endif
                                    @else
                                        <a href="/sort?name=asc" class="nav-link js-sort">Отсортировать по названию (по возрастанию)</a>
                                    @endif
                                </li>
                                <li class="nav-item">
                                    @if(isset($post['price']))
                                        @if($post['price'] == 'asc')
                                            <a href="/sort?price=desc" class="nav-link active show js-sort">Отсортировать по цене (по убыванию)</a>
                                        @else
                                            <a href="/sort?price=asc" class="nav-link active show js-sort">Отсортировать по цене (по возрастанию)</a>
                                        @endif
                                    @else
                                        <a href="/sort?price=asc" class="nav-link js-sort">Отсортировать по цене (по возрастанию)</a>
                                    @endif
                                </li>
                            </ul>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">Наименование</th>
                                    <th scope="col">Цена</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($product))
                                    @foreach($product as $item)
                                        <tr>
                                            <td>{{ $item->title }}</td>
                                            <td>
                                                {{ \App\components\Helper::getPrice($item->price) }}
                                            </td>
                                            <td>
                                                @if(array_key_exists($item->id,$basket))
                                                    <a href="/basket/{{$item->id}}" type="button" class="btn btn-primary btn-sm in-basket js-set-basket">Удалить</a>
                                                @else
                                                    <a href="/basket/{{$item->id}}" type="button" class="btn btn-primary btn-sm js-set-basket">В корзину</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">По вашему запросу ничего не найдено</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            {{$product->appends($post)->render()}}
                        </div>
                    </div>
                </div>
                <!--Sidebar-->
                @include('site._sidebar')
                <!--End Sidebar-->
            </div>
        </div>
    </div>
@endsection
