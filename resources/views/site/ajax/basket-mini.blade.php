<?php
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
