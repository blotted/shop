<?php
function clear_str($data) {
    global $link;
    return mysqli_real_escape_string($link, trim(strip_tags($data)));
}

function clear_int($data) {
    return abs((int)$data);
}

function add_item_to_catalog($title, $author, $pubyear, $price) {
    global $link;
    $sql = "INSERT INTO catalog(title, author, pubyear, price) VALUES (?, ?, ?, ?)";
    
    if (!$stmt = mysqli_prepare($link, $sql)) {
        return false;
    }
    mysqli_stmt_bind_param($stmt, "ssii", $title, $author, $pubyear, $price);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return true;
}

function select_all_items() {
    global $link;
    $sql = "SELECT id, title, author, pubyear, price FROM catalog";
    if (!$result = mysqli_query($link, $sql)) {
        return false;
    }
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $items;
}

function save_basket() {
    global $basket;
    $basket = base64_encode(serialize($basket));
    setcookie('basket', $basket, time()+3600);
}

function basket_init() {
    global $basket, $count;
    if (!isset($_COOKIE['basket'])) {
        $basket = array('orderid' => uniqid());
        save_basket();
    } else {
        $basket = unserialize(base64_decode($_COOKIE['basket']));
        $count = count($basket) - 1;
    }
}

function add_to_basket($id, $q) {
    global $basket;
    $basket[$id] = $q;
    save_basket();
}

function my_basket() {
    global $link, $basket;
    $goods = array_keys($basket);
    array_shift($goods);
    if (count($goods)) {
        $ids = implode(",", $goods);
    } else {
        $ids = 0;
    }
    $sql = "SELECT id, author, title, pubyear, price FROM catalog WHERE id IN ($ids)";
    if (!$result = mysqli_query($link, $sql)) {
        return false;
    }
    $items = result_to_array($result);
    mysqli_free_result($result);
    return $items;
}

function result_to_array($data) {
    global $basket;
    $arr = [];
    while ($row = mysqli_fetch_assoc($data)) {
        $row['quantity'] = $basket[$row['id']];
        $arr[] = $row;
    }
    return $arr;
}

function delete_from_basket($id) {
    global $basket;
    unset($basket[$id]);
    save_basket();
}

function save_order($dt) {
    global $link, $basket;
    $goods = my_basket();
    $stmt = mysqli_stmt_init($link);
    $sql = "INSERT INTO orders (title, author, pubyear, price, quantity, orderid, datetime) VALUES (?, ?, ?, ?, ?, ?, ?)";
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return false;
    }
    foreach ($goods as $item) {
        mysqli_stmt_bind_param($stmt, 'ssiiisi', $item['title'], $item['author'], $item['pubyear'], $item['price'], $item['quantity'], $basket['orderid'], $dt);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    setcookie('basket', '', time()-3600);
    return true;
}

function get_orders() {
    global $link;
    if (!is_file(ORDERS_LOG)) {
        return false;
    }
    $orders = file(ORDERS_LOG);
    $allorders = array();
    
    foreach ($orders as $order) {
        list($name, $email, $phone, $address, $orderid, $date) = explode('|', $order);
        $orderinfo = array();
        $orderinfo['name'] = $name;
        $orderinfo['email'] = $email;
        $orderinfo['phone'] = $phone;
        $orderinfo['address'] = $address;
        $orderinfo['orderid'] = $orderid;
        $orderinfo['dt'] = $date;
        
        $sql = "SELECT title, author, pubyear, price, quantity FROM orders WHERE orderid = '$orderid'";
    
        if (!$result = mysqli_query($link, $sql)) {
            return false;
        }
        $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        $orderinfo['goods'] = $items;
        $allorders[] = $orderinfo;
    }
    return $allorders;
}
