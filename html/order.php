<?php
require "../src/config.php";
require "../src/class.db.php";
require "../src/class.burger.php";

/*Задача #3.2
Скрипт должен:
Проверить, существует ли уже пользователь с таким email, если нет - создать его, если да -
    увеличить число заказов по этому email. Двух пользователей с одинаковым email быть не может.
Сохранить данные заказа - id пользователя, сделавшего заказ, дату заказа, полный адрес клиента.
Скрипт должен вывести пользователю:
Спасибо, ваш заказ будет доставлен по адресу: “тут адрес клиента”
Номер вашего заказа: #ID
Это ваш n-й заказ!
Где ID - уникальный идентификатор только что созданного заказа n - общий кол-во заказов, который сделал
    пользователь с этим email включая текущий */

$burger = new Burger();

$email = $_POST["email"];
$name = $_POST["name"];
$addressField = ["phone", "street", "home", "part", "appt", "floor"];
$address = "";

foreach ($_POST as $field => $value) {
    if ($value && in_array($field, $addressField)) {
        $address .= $value . ",";
    }
}

$data = ["address" => $address];

$user = $burger->getUserByEmail($email);

if ($user) {
    $userId = $user["id"];
    $burger->addCountOrders($user["id"]);
    $orderCount = $user["orders_count"] + 1;
} else {
    $orderCount = 1;
    $userId = $burger->createUser($email, $name);
}

$orderID = $burger->addOrder($userId, $data);

echo "Спасибо, ваш заказ будет доставлен по адресу: $address <BR>
Номер вашего заказа: $orderID <BR>
Это ваш $orderCount заказ!";