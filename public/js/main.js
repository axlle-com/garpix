function setLocation(curLoc){
    try {
        history.pushState(null, null, curLoc);
        return;
    } catch(e) {}
    location.hash = '#' + curLoc;
}

function setSort(){
    $('.js-sort').on('click', function (e) {
        e.preventDefault();
        let action = $(this).attr('href');
        let inner = $('.js-block-inner');
        $.ajax({
            url : action,
            type: 'POST',
            dataType: 'json',
            // processData: false,
            data: {},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function(response) {
            if(response.message){
                $.growl.error({title: 'Ошибка', message: response.message});
            }
            if(response.success){
                if(response.html){
                    let search = $(response.html).find('.js-block-search');
                    inner.html(search);
                    setSort();
                    setBasket();
                }
                if(response.url){
                    setLocation(response.url);
                }
                $.growl.notice({title: 'Сортировка', message: 'Результат сформирован'});
            }
        });
    });
}

function setBasket() {
    $('.js-set-basket').on('click', function (e) {
        e.preventDefault();
        let action = $(this).attr('href');
        let inner = $('.js-basket-mini-inner');
        let button = $(this);
        $.ajax({
            url : action,
            type: 'POST',
            dataType: 'json',
            // processData: false,
            data: {},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function(response) {
            if(response.message){
                $.growl.error({title: 'Ошибка', message: response.message});
            }
            if(response.success){
                if(response.html){
                    inner.html(response.html);
                }
                if(response.create){
                    button.text('Удалить');
                    button.addClass('in-basket');
                }
                if(response.delete){
                    button.text('В корзину');
                    button.removeClass('in-basket');
                }
                $.growl.notice({title: 'Корзина', message: 'Товар успешно добавлен'});
            }
        });
    });
}

function setOrderBasket() {
    $('.js-set-order-basket').on('click', function (e) {
        e.preventDefault();
        let action = $(this).attr('href');
        let inner = $('.js-basket-order-inner');
        let button = $(this);
        $.ajax({
            url : action,
            type: 'POST',
            dataType: 'json',
            // processData: false,
            data: {},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function(response) {
            if(response.message){
                $.growl.error({title: 'Ошибка', message: response.message});
            }
            if(response.success){
                if(response.html){
                    inner.html(response.html);
                    setOrderBasket();
                }
                $.growl.notice({title: 'Корзина', message: 'Товар успешно удален'});
            }
        });
    });
}

function setApiBasket() {
    $('.js-api-basket').on('click', function (e) {
        e.preventDefault();
        let action = $(this).attr('href');
        let inner = $('.js-basket-mini-inner');
        let button = $(this);
        $.ajax({
            url : action,
            type: 'PUT',
            dataType: 'json',
            // processData: false,
            data: {},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        }).done(function(response) {
            if(response.message){
                $.growl.error({title: 'Ошибка', message: response.message});
            }
            if(response.success){
                if(response.html){
                    inner.html(response.html);
                }
                if(response.create){
                    button.text('Удалить');
                    button.addClass('in-basket');
                }
                if(response.delete){
                    button.text('В корзину');
                    button.removeClass('in-basket');
                }
                $.growl.notice({title: 'Корзина', message: 'Товар успешно добавлен'});
            }
        });
    });
}

$(document).ready(function () {
    setSort();
    setBasket();
    setOrderBasket();
    setApiBasket();
});

