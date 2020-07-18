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
                $.growl.notice({title: 'Корзина', message: 'Товар успешно добавлен'});
            }
        });
    });
}

function sendForm(){
    $('.js-form-submit').on('click',function (e) {
        let form = $(this).closest('form');
        let action = form.attr('data-action');
        if(action){
            e.preventDefault();
            let formBlock = $('.ja-form-block');
            let pre = form.find('.spinner-bottom');
            pre.addClass('active');
            emptyError(form);
            let data = form.serialize();
            $.ajax({
                url : action,
                type: 'POST',
                dataType: 'json',
                processData: false,
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(response) {
                if(response.error){
                    $.each(response.error, function(key, value) {
                        let input = form.find('[name ^= '+key+']');
                        input.addClass('error-fild');
                        let div = input.closest('div');
                        let block = div.find('.invalid-feedback');
                        let message = '';
                        $.each(value, function(key1, value2) {
                            message += value2+'</br>';
                        });
                        block.html(message);
                        block.show();
                    });
                    // $.growl.error({title: 'Проверьте все поля', message: ''});
                }
                if(response.message){
                    $.growl.error({title: 'Ошибка', message: response.message});
                }
                if(response.success){
                    if(response.redirect){
                        // window.location.replace(response.redirect);
                        location.href = response.redirect;
                    }else{
                        if(response.html){
                            let inner = $(response.html).find('.card');
                            formBlock.html(inner);
                            reloadHtml();
                            selectizedReload();
                            reloadDatepicker();
                        }
                        if(response.url){
                            setLocation(response.url);
                        }
                    }
                    // $.growl.notice({title: 'Элемент', message: 'Успешное сохранение'});
                }
                pre.removeClass('active');
            });
        }
    })
}

function deleteButton() {
    $('.js-button-delete').on('click',function (e) {
        let url = $(this).attr('href');
        let title = $(this).attr('title');
        e.preventDefault();
        let isTrue = confirm('Вы уверены, что хотите '+title+'?');
        if(isTrue){
            let button = $(this);
            let group = button.closest('div').data('jsGroup');
            let div = button.closest('div');
            let row = button.closest('tr');
            let id = button.data('userId');
            let data = {
                'user_id' : id,
            }
            let pre = row.find('.js-preloader');
            button.hide();
            pre.show();
            $.ajax({
                url : url,
                type: 'POST',
                dataType: 'json',
                // processData: false,
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(response) {
                if(response.message){
                    $.growl.error({title: 'Ошибка', message: response.message});
                    button.show();
                }
                if(response.success){
                    if(group){
                        let buttons = row.find('[class ^= js-button-user]');
                        buttons.each(function () {
                            $(this).toggleClass('active');
                        })
                    }else {
                        row.remove();
                    }
                    // $.growl.notice({title: 'Элемент', message: title+' - прошло успешно'});
                }
                pre.hide();
                button.show();
            });
            // location.href = url;
        }
    })
}

function toggleButton() {
    $('.js-button-toggle').on('click',function (e) {
        let url = $(this).data('jsHref');
        let title = $(this).attr('title');
        let button = $(this);
        let group = button.closest('div').data('jsGroup');
        let div = button.closest('div');
        let row = button.closest('tr');
        let id = button.data('userId');
        let data = {
            'user_id' : id,
        }
        let pre = row.find('.js-preloader');
        button.hide();
        pre.show();
        $.ajax({
            url : url,
            type: 'POST',
            dataType: 'json',
            // processData: false,
            data: data,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function(response) {
            if(response.message){
                $.growl.error({title: 'Ошибка', message: response.message});
                button.show();
            }
            if(response.success){
                if(group){
                    button.show();
                    pre.hide();
                }else {
                    row.remove();
                }
                // $.growl.notice({title: 'Элемент', message: title+' - прошло успешно'});
            }

        });
    })
}

function savePlatformButton() {
    $('.js-button-save').on('click',function (e) {
        e.preventDefault();
        let button = $(this);
        let row = $(this).closest('tr');
        let form = row.find('form');
        let data = form.serialize();
        let pre = row.find('.spinner-bottom');
        let buttonInner = button.find('span');
        pre.addClass('active');
        $.ajax({
            url : '/admin/platforms/edit-platform',
            type: 'POST',
            dataType: 'json',
            processData: false,
            data: data,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function(response) {
            if(response.message){
                $.growl.error({title: 'Ошибка', message: response.message});
            }
            if(response.success){
                // $.growl.notice({title: 'Элемент', message: 'Изменения сохранены'});
            }
            if(response.status == 1){
                buttonInner.text('Выключить');
                button.removeClass('disable');
            }
            if(response.status == 0){
                buttonInner.text('Включить');
                button.addClass('disable');
            }
            pre.removeClass('active');
        });

    });
}

function selectizedReload() {
    $('.selectized').selectize({
        create: false,
    });
}

function sendFormPlacement(){
    $('.js-form-placement-submit').on('click',function (e) {
        let buttonSave = $(this);
        let row = $(this).closest('tr');
        let form = row.find('form');
        let action = form.attr('data-action');
        if(action){
            e.preventDefault();
            let formBlock = $('.ja-form-block');
            let pre = row.find('.js-preloader');
            buttonSave.hide();
            pre.show();
            emptyError(row);
            let data = form.serialize();
            $.ajax({
                url : action,
                type: 'POST',
                dataType: 'json',
                processData: false,
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(response) {
                if(response.error){
                    $.each(response.error, function(key, value) {
                        let input = row.find('[name = '+key+']');
                        input.addClass('error-fild');
                        let div = input.closest('td');
                        let block = div.find('.invalid-feedback');
                        let message = '';
                        $.each(value, function(key1, value2) {
                            message += value2+'</br>';
                        });
                        block.html(message);
                        block.show();
                    });
                    // $.growl.error({title: 'Проверьте все поля', message: ''});
                }
                if(response.message){
                    $.growl.error({title: 'Ошибка', message: response.message});
                }
                if(response.success){
                    // $.growl.notice({title: 'Элемент', message: 'Успешное сохранение'});
                }
                buttonSave.show();
                pre.hide();
            });
        }
    })
}

function sendSearchPlacements(){
    $('.js-form-submit-search').on('click',function (e) {
        let form = $(this).closest('form');
        let checker = false;
        form.find ('input, textarea, select').each(function() {
            if($(this).val() != ''){
                checker = true;
            }
        });
        if(!checker){
            $.growl.error({title: 'Фильтр', message: 'Выберете как минимум 1 поле'});
            return false;
        }
        let action = form.attr('data-action');
        if(action){
            e.preventDefault();
            let formBlock = $(this).closest('.js-search-general').find('.js-search-block');
            let pre = form.find('.spinner-bottom');
            pre.addClass('active');
            let data = form.serialize();
            $.ajax({
                url : action,
                type: 'POST',
                dataType: 'json',
                processData: false,
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(response) {
                if(response.success){
                    if(response.html){
                        let inner = $(response.html).find('.js-search-result');
                        formBlock.html(inner);
                        // $('.js-select2').select2();
                        selectizedReload();
                        sendFormPlacement();
                        openCategory();
                        toggleButton();
                        openCategoryPlacement();
                        closeCategoryPlacement();
                        myTablesorter();
                    }
                    if(response.url){
                        setLocation(response.url);
                    }
                    // $.growl.notice({title: 'Фильтр', message: 'Результат сформирован'});
                }
                pre.removeClass('active');
            });
        }
    })
}

function sendResetSearchPlacements(){
    $('.js-form-submit-reset').on('click',function (e) {
        let form = $(this).closest('form');
        let inputs = form.find('input');
        inputs.attr('checked', false);
        let action = form.attr('data-action');
        if(action){
            e.preventDefault();
            let formBlock = $(this).closest('.js-search-general').find('.js-search-block');
            let pre = form.find('.spinner-bottom-reset');
            pre.addClass('active');
            let data = form.serialize();
            $.ajax({
                url : action,
                type: 'POST',
                dataType: 'json',
                processData: false,
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(response) {
                if(response.success){
                    if(response.html){
                        let inner = $(response.html).find('.js-search-result');
                        formBlock.html(inner);
                        // $('.js-select2').select2();
                        selectizedReload();
                        sendFormPlacement();
                        openCategory();
                        toggleButton();
                        openCategoryPlacement();
                        closeCategoryPlacement();
                        myTablesorter();
                    }
                    if(response.url){
                        setLocation(response.url);
                    }
                    // $.growl.notice({title: 'Фильтр', message: 'Результат сформирован'});
                }
                pre.removeClass('active');
            });
        }
    })
}

function reloadHtml(){
    paymentDocDelete();
    sendFormWithFile();
    sendForm();
    changeTypeWallet();
    deleteButton();
    changeFildsError();
    $('.js-select2').select2();
    searchInput();
    inputPercent();
    inputCpm();
    clickPlacementLabel();
}

// function loadFormCampaigns() {
//     $('.js-campaigns-link').on('click', function (e) {
//         e.preventDefault();
//         let modal = $('.js-modal-general');
//         let pre = modal.find('.preloader');
//         $('body').addClass('modal-open');
//         modal.addClass('show');
//         modal.show();
//         let url = $(this).data('campaignsLink');
//         let array = url.split('?');
//         $.ajax({
//             url : array[0],
//             type: 'POST',
//             dataType: 'json',
//             // processData: false,
//             data: array[1],
//             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         }).done(function(response) {
//             if(response.message){
//                 $.growl.error({title: 'Ошибка', message: response.message});
//             }
//             if(response.success){
//                 if(response.html){
//                     let block = modal.find('.js-modal-body');
//                     block.html(response.html);
//                     pre.hide();
//                     selectizedReload();
//                 }
//             }
//         });
//     });
// }

function loadFormCampaigns() {
    $('.js-campaigns-link').on('click', function (e) {
        e.preventDefault();
        let modal = $('.js-modal-general');
        let pre = modal.find('.preloader');
        let url = $(this).data('campaignsLink');
        let array = url.split('?');
        pre.show();
        $.ajax({
            url : array[0],
            type: 'POST',
            dataType: 'json',
            data: array[1],
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function(response) {
            if(response.message){
                $.growl.error({title: 'Ошибка', message: response.message});
            }
            if(response.success){
                if(response.html){
                    let block = modal.find('.js-modal-body');
                    block.html(response.html);
                    pre.hide();
                    saveFormCampaigns();
                }
            }
        });
    });
}

function saveFormCampaigns(){
    $('.js-form-campaigns').find('input:text,textarea').each(function() {
        $(this).focusout(function(){
            sendInputCampaigns($(this));
        });
    });
}

function closeModalCampaigns(){
    $('.js-modal-close').on('click', function (e) {
        e.preventDefault();
        let modal = $('.js-modal-general');
        let form = modal.find('.js-modal-body');
        let pre = modal.find('.preloader');
        $('body').removeClass('modal-open');
        modal.removeClass('show');
        modal.hide();
        form.empty();
        pre.show();
    });
}

function sendFormCampaigns(){
    $('.js-modal-form-submit').on('click', function (e) {
        e.preventDefault();
        let block = $(this).closest('.js-modal-general');
        let form = block.find('form');
        let action = form.attr('action');
        if(action){
            let pre = block.find('.spinner-bottom');
            pre.addClass('active');
            let data = form.serialize();
            $.ajax({
                url : action,
                type: 'POST',
                dataType: 'json',
                processData: false,
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(response) {
                if(response.message){
                    $.growl.error({title: 'Ошибка', message: response.message});
                }
                if(response.success){
                    // $.growl.notice({title: 'Элемент', message: 'Успешное сохранение'});
                }
                pre.removeClass('active');
            });
        }
    });
}

function sendInputCampaigns(input){

    let block = input.closest('.js-form-campaigns');
    let data = {};
    block.find ('input, textarea, select').each(function() {
        data[this.name] = $(this).val();
    });
    emptyError(block);
    $.ajax({
        url : '/admin/campaigns/save-campaigns-in-placement',
        type: 'POST',
        dataType: 'json',
        data: data,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    }).done(function(response) {
        if(response.message){
            $.growl.error({title: 'Ошибка', message: response.message});
        }
        if(response.success){
            // $.growl.notice({title: 'Элемент', message: 'Успешное сохранение'});
        }
        if(response.error){
            $.each(response.error, function(key, value) {
                let input = block.find('[name ^= '+key+']');
                input.addClass('error-fild');
                let div = input.closest('div');
                let invalid = div.find('.invalid-feedback');
                let message = '';
                $.each(value, function(key1, value2) {
                    message += value2+'</br>';
                });
                invalid.html(message);
                invalid.show();
            });
            // $.growl.error({title: 'Проверьте все поля', message: ''});
        }
    });
}


function loadAdvertiserPlacement(){
    $('.js-placement-banner').on('change', function (e) {
        let block = $('.js-advertiser-block');
        let pre = block.closest('.parent-preloader').find('.preloader');
        pre.show()
        let data = {'ad_type_id':$(this).val()}
        $.ajax({
            url : '/admin/placements/change-banner',
            type: 'POST',
            dataType: 'json',
            // processData: false,
            data: data,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function(response) {
            if(response.message){
                $.growl.error({title: 'Ошибка', message: response.message});
            }
            if(response.success){
                block.empty();
                if(response.html){
                    block.html(response.html);
                }
                // $.growl.notice({title: 'Рекламодатели', message: 'Сформированы'});
                pre.hide();
            }
        });
    });
}

function sendFormPayment(){
    $('.js-form-payment-submit').on('click',function (e) {
        let buttonSave = $(this);
        let row = $(this).closest('tr');
        let form = row.find('form');
        let action = form.attr('data-action');
        if(action){
            e.preventDefault();
            let formBlock = $('.ja-form-block');
            let pre = row.find('.js-preloader');
            buttonSave.hide();
            pre.show();
            emptyError(row);
            let data = form.serialize();
            $.ajax({
                url : action,
                type: 'POST',
                dataType: 'json',
                processData: false,
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(response) {
                if(response.error){
                    $.each(response.error, function(key, value) {
                        let input = row.find('[name = '+key+']');
                        input.addClass('error-fild');
                        let div = input.closest('td');
                        let block = div.find('.invalid-feedback');
                        let message = '';
                        $.each(value, function(key1, value2) {
                            message += value2+'</br>';
                        });
                        block.html(message);
                        block.show();
                    });
                    // $.growl.error({title: 'Проверьте все поля', message: ''});
                }
                if(response.message){
                    $.growl.error({title: 'Ошибка', message: response.message});
                }
                if(response.success){
                    // $.growl.notice({title: 'Элемент', message: 'Успешное сохранение'});
                }
                buttonSave.show();
                pre.hide();
            });
        }
    })
}

function clickTabPayment(){
    $('.js-payment-tab').on('click',function (e){
        let id = $(this).attr('href').replace(/#/g, '');
        let url = window.location.href.split('?');
        setLocation(url[0]+'?id='+id);
    });
}

function reloadDatepicker(){
    $('.datepicker-payment').datepicker( {
        language: 'ru'
    });
}

function sendFormWithFile(){
    $('.js-form-file-submit').on('click',function (e) {
        let form = $(this).closest('form');
        let action = form.attr('data-action');
        if(action){
            e.preventDefault();
            let formBlock = $('.ja-form-block');
            let pre = form.find('.spinner-bottom');
            pre.addClass('active');
            emptyError(form);
            let data = new FormData(form[0]);
            // let data = form.serialize();
            $.ajax({
                url : action,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                cache : false,
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(response) {
                if(response.error){
                    $.each(response.error, function(key, value) {
                        let input = form.find('[name = '+key+']');
                        input.addClass('error-fild');
                        let div = input.closest('div');
                        let block = div.find('.invalid-feedback');
                        let message = '';
                        $.each(value, function(key1, value2) {
                            message += value2+'</br>';
                        });
                        block.html(message);
                        block.show();
                    });
                    // $.growl.error({title: 'Проверьте все поля', message: ''});
                }
                if(response.message){
                    $.growl.error({title: 'Ошибка', message: response.message});
                }
                if(response.success){
                    if(response.redirect){
                        // window.location.replace(response.redirect);
                        location.href = response.redirect;
                    }else{
                        if(response.html){
                            let inner = $(response.html).find('.card');
                            formBlock.html(inner);
                            reloadHtml();
                            selectizedReload();
                            reloadDatepicker();
                        }
                        if(response.url){
                            setLocation(response.url);
                        }
                    }

                    // $.growl.notice({title: 'Элемент', message: 'Успешное сохранение'});
                }
                pre.removeClass('active');
            });
        }
    })
}

function paymentDocDelete(){
    $('.payment-doc-delete').on('click',function (e) {
        e.preventDefault();
        let link = $(this).attr('href');
        let id = $(this).data('paymentId');
        let action = $(this).data('action');
        let block = $(this).closest('li');
        let data = {'link':link,'id':id};
        $.ajax({
            url : action,
            type: 'POST',
            dataType: 'json',
            data: data,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function(response) {
            if(response.message){
                $.growl.error({title: 'Ошибка', message: response.message});
            }
            if(response.success){
                block.remove();
                // $.growl.notice({title: 'Элемент', message: 'Успешное удаление'});
            }
        });
    });
}

function sendSearchPayment(){
    $('.js-form-submit-search-payment').on('click',function (e) {
        let form = $(this).closest('form');
        let id = form.find('[name = id]').val();
        let checker = false;
        form.find('input, textarea, select').each(function() {
            if($(this).val() != ''){
                checker = true;
            }
        });
        if(!checker){
            $.growl.error({title: 'Фильтр', message: 'Выберете как минимум 1 поле'});
            return false;
        }
        let action = form.attr('data-action');
        if(action){
            e.preventDefault();
            let formBlock = $(this).closest('.js-search-general-'+id).find('.js-search-block-'+id);
            let pre = form.find('.spinner-bottom');
            pre.addClass('active');
            let data = form.serialize();
            $.ajax({
                url : action,
                type: 'POST',
                dataType: 'json',
                processData: false,
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(response) {
                if(response.success){
                    if(response.html){
                        let inner = $(response.html).find('.js-search-result-'+id);
                        formBlock.html(inner);
                        // $('.js-select2').select2();
                        selectizedReload();
                        sendFormPlacement();
                    }
                    if(response.url){
                        setLocation(response.url);
                    }
                    // $.growl.notice({title: 'Фильтр', message: 'Результат сформирован'});
                }
                pre.removeClass('active');
            });
        }
    })
}

function sendResetSearchPayment(){
    $('.js-form-submit-reset-payment').on('click',function (e) {
        let form = $(this).closest('form');
        let id = form.find('[name = id]').val();
        let inputs = form.find('input');
        inputs.attr('checked', false);
        let action = form.attr('data-action');
        if(action){
            e.preventDefault();
            let formBlock = $(this).closest('.js-search-general-'+id).find('.js-search-block-'+id);
            let pre = form.find('.spinner-bottom-reset');
            pre.addClass('active');
            let data = form.serialize();
            $.ajax({
                url : action,
                type: 'POST',
                dataType: 'json',
                processData: false,
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(response) {
                if(response.success){
                    if(response.html){
                        let inner = $(response.html).find('.js-search-result-'+id);
                        formBlock.html(inner);
                    }
                    if(response.url){
                        setLocation(response.url);
                    }
                    // $.growl.notice({title: 'Фильтр', message: 'Результат сформирован'});
                }
                pre.removeClass('active');
            });
        }
    })
}

function searchInput(){
    $(".search-input").on("input", function () {
        var query = $(this).val().trim().toLowerCase();
        $(this).parent().find(".custom-control-label").each(function () {
            if ($(this).text().trim().toLowerCase().indexOf(query) == -1) {
                $(this).closest('.custom-checkbox').hide();
            } else {
                $(this).closest('.custom-checkbox').show();
            }
        });
    });
}

function openCategory(){
    $('.platform-category').on('click',function (e){
        e.preventDefault();
        let ul = $(this).closest('ul');
        let li = ul.find('.category-hidden');
        $.each(li, function() {
            if($(this).hasClass('show')){
                $(this).removeClass('show');
            }else {
                $(this).addClass('show');
            }
        });
    });
}

function openCategoryPlacement(){
    $('.placement-category').on('click',function (e){
        e.preventDefault();
        let ul = $(this).closest('ul');
        let li = ul.find('.category-hidden');
        let close = ul.find('.placement-category-close');
        $.each(li, function() {
            if($(this).hasClass('show')){
                $(this).removeClass('show');
            }else {
                $(this).addClass('show');
            }
        });
        close.show();
        $(this).hide();
    });
}

function closeCategoryPlacement(){
    $('.placement-category-close').on('click',function (e){
        e.preventDefault();
        let ul = $(this).closest('ul');
        let li = ul.find('.category-hidden');
        let open = ul.find('.placement-category');
        $.each(li, function() {
            if($(this).hasClass('show')){
                $(this).removeClass('show');
            }else {
                $(this).addClass('show');
            }
        });
        open.show();
        $(this).hide();
    });
}

function inputCpm(){
    $('.js-input-cpm').on('input keyup',function (e){
        e.preventDefault();
        let block = $(this).closest('.js-percent-block');
        let secondInput = block.find('.js-input-percent');
        if($(this).val()){
            secondInput.val('');
            emptyError(block);
        }
    });
}

function inputPercent(){
    $('.js-input-percent').on('input keyup',function (e){
        e.preventDefault();
        let block = $(this).closest('.js-percent-block');
        let secondInput = block.find('.js-input-cpm');
        if($(this).val()){
            secondInput.val('');
            emptyError(block);
        }
    });
}

function clickPlacementLabel(){
    $('.placement-label').on('click',function (e){
        let block = $(this).closest('.js-advertiser-general-block');
        let form = block.find('.js-modal-body');
        form.empty();
    });
}

function sendSortPlacement(){
    $('.js-sort-placement').on('click',function (e) {
        let form = $('.filter-block').find('form');
        let action = form.attr('data-action');
        let searchBlock = $(this).closest('.js-search-block');
        let pre = $(this).closest('th').find('.js-sort-placement-hidden');
        let span = $(this).find('.sort-button');
        if(action){
            pre.show();
            $(this).hide();
            let input = $(this).closest('th').find('input');
            let tr = $(this).closest('tr');
            let inputs = tr.find('input');
            inputs.not(input).attr('checked', false);
            let name = input.attr('name');
            let value = input.prop('checked');
            let sort = 'asc';
            if(value){
                sort = 'desc';
            }
            let data = new FormData(form[0]);
            data.append(name, sort);
            $.ajax({
                url : action,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                cache : false,
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(response) {
                if(response.success){
                    if(response.html){
                        let inner = $(response.html).find('.js-search-result');
                        searchBlock.html(inner);
                        // $('.js-select2').select2();
                        selectizedReload();
                        sendFormPlacement();
                        openCategory();
                        toggleButton();
                        openCategoryPlacement();
                        closeCategoryPlacement();
                        sendSortPlacement();
                    }
                    if(response.url){
                        setLocation(response.url);
                    }
                    // $.growl.notice({title: 'Фильтр', message: 'Результат сформирован'});
                }
                // pre.removeClass('active');
            });
        }
    })
}

function sendSearchPlacementInput(){
    $('.js-search-placement-input').on('submit',function (e) {
        e.preventDefault();
        let form = $(this);
        let action = $(this).attr('data-action');
        let searchBlock = $('.js-search-block');
        let pre = '';
        if(action){
            // pre.show();
            let data = form.serialize();
            $.ajax({
                url : action,
                type: 'POST',
                dataType: 'json',
                processData: false,
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(response) {
                if(response.success){
                    if(response.html){
                        let inner = $(response.html).find('.js-search-result');
                        searchBlock.html(inner);
                        // $('.js-select2').select2();
                        selectizedReload();
                        sendFormPlacement();
                        openCategory();
                        toggleButton();
                        openCategoryPlacement();
                        closeCategoryPlacement();
                        sendSortPlacement();
                    }
                    if(response.url){
                        setLocation(response.url);
                    }
                    // $.growl.notice({title: 'Фильтр', message: 'Результат сформирован'});
                }
                // pre.removeClass('active');
            });
        }
        return false;
    })
}

function myTablesorter(){
    $('.tablesorter').tablesorter();
}

function tableSearch(){
    $('.input-search').keyup(function(){
        let input = $(this);
        $.each($('.js-search-result tbody tr'), function() {
            if ($(this).text().toLowerCase().indexOf(input.val().toLowerCase()) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
}

function showTestDate(){
    let value = $('.datepicker-search-payment').datepicker('getFormattedDate');
    $('#datepicker-search-payment').val(value);
}

$(document).ready(function () {
    setSort();
    setBasket();
});

