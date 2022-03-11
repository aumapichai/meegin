<?php
    session_start();
    include("../conn.php");
    $tableNumber = null;
    $QRcode = null;
    if(isset($_GET['tableN'])){
        $tableNumber = $_GET['tableN'];
        $query = mysqli_query($conn, "SELECT * FROM tablezone WHERE table_number = '".$tableNumber."'");
        $row = mysqli_fetch_array($query);
        $idReference = $row['check_reference'];
        $QRcode = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=http://www.meeginpos.com/user/nextToCustomer.php?table='.$tableNumber.','.$idReference;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QRcodeNo.<?php echo $tableNumber; ?></title>
    <link rel="stylesheet" href="../bootstrap-5.0.2/css/bootstrap.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Kanit&display=swap');

    body {
        font-family: "Kanit", sans-serif;
        color:black;
    }

    .box-main-QRcode {
        width: 270px;
        padding: 10px;
    }

    .title_table {
        font-size: 24px;
        font-weight: 400;
    }

    .text-detail {
        font-size: 18px;
        font-weight: 400;
    }

    .img_QRcode {
        width: 200px;
        height: 200px;
    }
    </style>
</head>

<body>
    <div class="box-main-QRcode">
        <div class="d-flex justify-content-center w-100">
            <span class="title_table">โต๊ะ <?php echo ' '.$tableNumber; ?></span>
        </div>
        <div class="d-flex flex-column align-items-center w-100">
            <span class="text-detail">สแกนเพื่อสั่งอาหาร</span>
            <span class="text-detail">หรือเรียกพนักงงาน</span>
        </div>
        <div class="d-flex flex-column align-items-center">
            <img src="<?php echo $QRcode; ?>" class="img_QRcode" alt="" srcset="">
        </div>
        <span class="text-detail">
            Scan to order food or call staff.
        </span>

    </div>

</body>

</html>