<?php

    include("../conn.php");

    $category = $conn->real_escape_string($_POST['add_food_category']);
    $foodName = $conn->real_escape_string($_POST['add_food_name']);
    $foodPrice = $conn->real_escape_string($_POST['add_food_price']);
    $foodStatus = $conn->real_escape_string($_POST['add_food_status']);

    $filename = $_FILES["food_pictuer"]["name"];
    $fileTmpename = $_FILES["food_pictuer"]["tmp_name"];
    $fileExt = explode(".",$filename);
    $fileAcExt = strtolower(end($fileExt));
    $newFilename = time().".".$fileAcExt;
    $fileDes = '../food_img/'.$newFilename;

    move_uploaded_file($fileTmpename, $fileDes);

    mysqli_query($conn, "INSERT INTO foods (food_category, food_name, price, food_img, food_status) VALUES ('$category', '$foodName', '".(int)$foodPrice."', ' $newFilename', '$foodStatus')");
    header("Location: products.php");


?>