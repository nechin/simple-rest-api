# simple-rest-api
Упрощённое REST API. MVC, реализованный через Controller, Entity, Repository, Service.

- PHP 7.4
- MySQL 8.0.20
- nginx 1.13.8

###### Endpoints:
- **/api/generate** [GET] : Создание таблиц и генерация 20 товаров
- **/api/product/all** [GET] : Получение всех товаров
- **/api/order/create** [POST] : Создание заказа. (На входе {products_ids=1,2,3}. На выходе {orderId=1})
- **/api/order/pay** [POST] : Оплата заказа (На входе {order_id=1}. На выходе {orderId=1})

Любой запрос требует указания заголовка: **Authorization: Bearer 3f16ecd8b19cd892f358c8c6f9a285a4**