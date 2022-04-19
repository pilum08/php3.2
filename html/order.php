<?php
try {
    $pdo = new \PDO('mysql:host=localhost;dbname=loftdb;charset=utf8mb4', 'root', '');
} catch (\Exception $e) {
    echo $e->getMessage();
}
$data = [];
if (empty($_GET['name']) || empty($_GET['email'])) {
    echo 'Заполните обязательные поля';
} else {
    $data['name'] = $_GET['name'];
    $data['email'] = $_GET['email'];
    $data['phone'] = $_GET['phone'];
    $data['address'] = $_GET['street'] . ' ' . $_GET['home'] . ' ' . $_GET['part'] . ' ' . $_GET['appt'] . ' ' . $_GET['floor'];
}
$ordersCount = 1;
$createdAt = date('Y-m-d H:i:s');

$query = "SELECT `email` FROM `users` WHERE `email` = :email";
$stat = $pdo->prepare($query);
$stat->execute(['email' => $data['email']]);
$res = $stat->fetchAll(PDO::FETCH_ASSOC);

if ($res) {
    $address = $data['address'];
    $ordersID = uniqid();
   $getNumber=$pdo->prepare('SELECT `orders_count` FROM `users` WHERE `email` = :email');
   $getNumber->execute(['email' => $data['email']]);
   $orderNumber=$getNumber->fetchColumn();
    $query = "INSERT INTO orders(user_id, address, created_at) VALUES (:user_id, :address, :created_at);
    UPDATE users SET orders_count = orders_count +1 WHERE email = :email";
    $stat = $pdo->prepare($query);
    $stat->execute(['user_id' => $ordersID,
     'address' => $data['address'],
      'created_at' => $createdAt,
       'email' => $data['email']]);
    $stat->fetchAll(PDO::FETCH_ASSOC);
    echo "Спасибо, ваш заказ будет доставлен по адресу: $address<br>
Номер вашего заказа: #$ordersID <br>
Это ваш $orderNumber-й заказ!";
} else {
    $ordersID = uniqid();
    $address = $data['address'];
    $query = "INSERT INTO users(email, `name`) VALUES (:email, :name);
    UPDATE users SET orders_count = 1 WHERE email = :email;
    INSERT INTO orders(user_id, address, created_at) VALUES (:user_id, :address, :created_at)";
    $stat = $pdo->prepare($query);
    $stat->execute(['email' => $data['email'],
     'name' => $data['name'],
      'user_id' => $ordersID,
       'address' => $data['address'],
        'created_at' => $createdAt,
         'email' => $data['email']]);
    $stat->fetchAll(PDO::FETCH_ASSOC);
    echo "Спасибо, ваш заказ будет доставлен по адресу: $address<br>
Номер вашего заказа: #$ordersID <br>
Это ваш 1-й заказ!"; 
}