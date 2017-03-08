# MODX + Minishop2 
## Складской учет
###Задача:

На странице списка товаров менеджер магазина должен видеть фактическое наличие товара на складе и кол-во товара в резерве. 

### Алгоритм
* Клиент оформил заказ - товар списался. 
* Менеджер поменял статус заказа **В обработке** - резерв изменился.
* Менеджер поменял статус заказа **Выполнен** - резерв вернулся к прежнему значению.
* Заказ **Отменен** - резерв и склад приняли исходные значения.
* Клиент не может оформить заказ, если товара в корзине больше, чем на складе.

### Дополнение

Из коробки в Minishop2 нельзя просто взять и добавить новые свойства к товару (Склад и Резерв). Для решения я использовал готовый плагин [msFieldsManager](https://modstore.pro/packages/utilities/msfieldsmanager). Если нет желания покупать, можно написать самому через кастомный плагин, там ничего сложного. 
