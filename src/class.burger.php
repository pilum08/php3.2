<?php

class Burger
{
    public function  getUserByEmail( $email)
    {
        $db = DB::getInstance();
        $query = "SELECT * FROM users WHERE email = :email";
        $users = $db->fetchOne($query, __METHOD__, [":email" => $email]);
        return $users;
    }

    public function createUser(string $email, string $nameUser)
    {
        $db = DB::getInstance();
        $query = "INSERT INTO users(email, `name` ) VALUES (:email, :name)";
        $user = $db->exec(
            $query,
            __METHOD__,
            [
                ":email" => $email,
                ":name" => $nameUser
            ]);
        if (!$user) {
            return false;
        } else {
            return $db->lastInsertId();
        }
    }

    public function addOrder(int $userId, array $data)
    {
        $db = DB::getInstance();
        $query = "INSERT INTO orders(user_id, address, created_at) 
                    VALUES (:user_id, :address, :created_at)";
        $order = $db->exec(
            $query,
            __METHOD__,
            [
               ":user_id" => $userId,
               ":address" => $data["address"],
               ":created_at" => date("Y-m-d H:i:s"),
            ]);
        if (!$order) {
            return false;
        } else {
            return $db->lastInsertId();
        }
    }

    public function addCountOrders(int $userId)
    {
        $db = DB::getInstance();
        $query = "UPDATE users SET orders_count = orders_count + 1 WHERE id = $userId";
        return $db->exec($query,__METHOD__);
    }
}