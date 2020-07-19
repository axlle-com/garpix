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
