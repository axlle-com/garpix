@extends('layout')

@section('content')
    <section class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 text-left">
                    <h1>Корзина</h1>
                </div>
                <div class="col-md-4">
                    {{ Breadcrumbs::render('order') }}
                </div>
            </div>
        </div>
    </section>
    @include('errors')
    <div class="v-page-wrap has-right-sidebar has-one-sidebar page-products">
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    @if(count($orders))
                        @foreach($orders as $order)
                            <div class="order-basket">
                                <h4>Заказ № {{ $order->id }}</h4>
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">Наименование</th>
                                        <th scope="col">Цена</th>
                                    </tr>
                                    </thead>
                                    <tbody class="js-basket-order-inner">
                                    <?php
                                    $totalPrice = 0;
                                    ?>
                                    @foreach($order->baskets as $item)
                                        <tr>
                                            <td>{{ $item->product->title }}</td>
                                            <td>{{ \App\components\Helper::getPrice($item->price) }}</td>
                                        </tr>
                                        <?php $totalPrice += $item['price']?>
                                    @endforeach
                                    <tr>
                                        <td>Итого:</td>
                                        <td>{{ \App\components\Helper::getPrice($totalPrice) }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    @endif
                        <div class="order-basket">
                            <h4>Корзина</h4>
                            <div class="js-block-inner">
                                <div class="js-block-search">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th scope="col">Наименование</th>
                                            <th scope="col">Цена</th>
                                            <th scope="col"></th>
                                        </tr>
                                        </thead>
                                        <tbody class="js-basket-order-inner">
                                        @if(count($basket))
                                            <?php
                                                $totalPrice = 0;
                                            ?>
                                            @foreach($basket as $key => $item)
                                                <tr>
                                                    <td>{{ $item['title'] }}</td>
                                                    <td>{{ \App\components\Helper::getPrice($item['price']) }}</td>
                                                    <td>
                                                        <a href="/order-basket/{{$key}}" type="button" class="btn btn-primary btn-sm in-basket js-set-order-basket">Удалить</a>
                                                    </td>
                                                </tr>
                                                <?php $totalPrice += $item['price']?>
                                            @endforeach
                                            <tr>
                                                <td>Итого:</td>
                                                <td colspan="2">{{ \App\components\Helper::getPrice($totalPrice) }}</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="3">Ваша корзина пуста</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @if(count($basket))
                        <a href="{{ route('order.store') }}" class="btn btn-outline-success">Оформить</a>
                    @endif
                </div>
                <!--Sidebar-->
                @include('site._sidebar')
                <!--End Sidebar-->
            </div>
        </div>
    </div>
@endsection
