<?php
switch ($modx->event->name) {
    // Вешаем слушатель на событие смены статуса заказа
    case 'msOnChangeOrderStatus':
        $modx->getService('error', 'error.modError');
        $modx->setLogLevel(modX::LOG_LEVEL_INFO);
        $modx->setLogTarget('FILE');
        //дебажим через логи ошибок.
        //$modx->log(modX::LOG_LEVEL_INFO, $order->get('id'));
    // Перебираем массив с продуктами в заказе
    foreach ($order->getMany('Products') as $item) {
        $product = $item->getOne('Product');
        
        // id статусов можно найти на странице редактирования статусов. У меня по дефолту.
        // заказ в Обработке 
        if ($status == 5) {
            $product->set('stock', $product->get('stock') - $item->get('count'));
            $product->set('reserve', $product->get('reserve') + $item->get('count'));
            $product->save();
        }
        // заказ Выполнен
        if ($status == 3 && $product->get('reserve') >= 0) {
           $product->set('reserve', $product->get('reserve') - $item->get('count'));
           $product->save();
        }
        // заказ Отменен
        if($status == 4 && $product->get('reserve') > 0) {
            $product->set('stock', $product->get('stock') + $item->get('count'));
            $product->set('reserve', $product->get('reserve') - $item->get('count'));
            $product->save();
        }
    }

    break;
    
    // Вешаем слушатель на смену кол-ва товара в карточке и корзине
    case 'msOnBeforeChangeInCart': case 'msOnChangeInCart':
        // Получаем массив корзины.
        $tmp = $cart->get();
        $id = $tmp[$key]['id'];
        
        // Получаем актуальный склад по Id товара (решение не айс, но другого пока не придумал).
        $sql = "SELECT stock FROM modx_ms2_products WHERE id = $id";
        $result = $modx->query($sql);
        $stock = $result->fetch(PDO::FETCH_ASSOC);
        
        // Пушим в массив корзины полученный склад.
        $tmp[$key]['stock'] = $stock['stock'];
        
        // Сравниваем склад и кол-во товара в корзине.
        if ($tmp[$key]['count'] > $tmp[$key]['stock']) {
            $modx->event->output('В наличии всего '.$tmp[$key]['stock'].' ед. данного товара.');
            // Кладем в корзину максимальное кол-во товара если порог по наличию превышен.
            $tmp[$key]['count'] = $tmp[$key]['stock'];
            $cart->set($tmp);
        } 
        
    break;
}
