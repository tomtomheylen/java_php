<?php
if(isset($_POST['get_candles'])){
    $symbol = $_POST['symbol'];
    $interval = $_POST['interval'];
    $limit = $_POST['limit'];
    $url = "https://api.binance.com/api/v1/klines?symbol=".$symbol."&interval=".$interval."&limit=".$limit;

    $contents = json_decode(file_get_contents($url));
    echo json_encode($contents);
}
if(isset($_POST['get_price'])){
    $limit = $_POST['limit'];
    $symbol = $_POST['symbol'];
    $url = "https://api.binance.com/api/v1/trades?symbol=".$symbol."&limit=".$limit;

    $contents = json_decode(file_get_contents($url));
    echo json_encode($contents);
}
?>