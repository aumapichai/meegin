<?php
session_start();
include("../conn.php");
if (isset($_SESSION['user_id']) && isset($_SESSION['type'])) {
    if ($_SESSION['type'] == "kitchen") {
    } else {
        header("Location: ../index.php");
    }
} else {
    header("Location: ../index.php");
}

if (isset($_POST['getAllFood'])) {
    $respone = '';
    $category = $conn->real_escape_string($_POST['category']);
    $searchName = $conn->real_escape_string($_POST['searchName']);
    if ($category == "" && $searchName == "") {
        $sql = "SELECT * FROM foods WHERE NOT food_deleted = 'deleted' ORDER BY food_id DESC";
    }
    if ($category != "" && $searchName == "") {
        $sql = "SELECT * FROM foods WHERE NOT food_deleted = 'deleted' AND category_id = '" . $category . "' ORDER BY food_id DESC";
    }
    if ($category == "" && $searchName != "") {
        $sql = "SELECT * FROM foods WHERE NOT food_deleted = 'deleted' AND food_name LIKE '%" . $searchName . "%' ORDER BY food_id DESC";
    }
    if ($category != "" && $searchName != "") {
        $sql = "SELECT * FROM foods WHERE NOT food_deleted = 'deleted' AND category_id = '" . $category . "' AND food_name LIKE '%" . $searchName . "%' ORDER BY food_id DESC";
    }
    $query = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($query)) {
        $food_id = $row['food_id'];
        $category_id = $row['category_id'];
        $food_name = $row['food_name'];
        $price = $row['price'];
        $food_img = trim($row['food_img']);
        $food_status = $row['food_status'];

        $queryCategoryName = mysqli_query($conn, "SELECT * FROM category WHERE category_id = '$category_id'");
        $rowCategoryName = mysqli_fetch_array($queryCategoryName);
        $food_category = $rowCategoryName['category_name'];

        $bg_mine = '';
        if ($food_status == 'หมด') {
            $bg_mine = ' text-danger';
        }

        $respone .= '<div class="d-flex align-items-center px-2 py-3"
        style="box-shadow: 0 1px 0 0 #dcdcdc;">
        <div class="col-2">
            <div class="d-flex">
                <img src="../food_img/' . $food_img . '" class="img-list-food-2"
                    alt="">
            </div>
        </div>
        <div class="col-2">
            <div class="d-flex">
                <span class="text-title-discription">
                    ' . $food_category . '
                </span>
            </div>
        </div>
        <div class="col-3">
            <div class="d-flex">
                <span class="text-title-discription">
                    ' . $food_name . '
                </span>
            </div>
        </div>
        <div class="col-1">
            <div class="d-flex justify-content-center">
                <span class="text-title-discription">
                    ฿' . number_format($price) . '
                </span>
            </div>
        </div>
        <div class="col-2">
            <div class="d-flex justify-content-center">
                <span
                    class="text-title-discription ' . $bg_mine . '">
                    ' . $food_status . '
                </span>
            </div>
        </div>
        <div class="col-2">
            <div class="d-flex justify-content-center">
                <select class="custom-select selected_status_food" data-id="' . $food_id . '" id="selected_status_food"
                    style="width: 90px;">
                    <option selected>แก้ไข</option>
                    <option value="แสดง">แสดง</option>
                    <option value="หมด">หมด</option>
                    
                </select>
            </div>
        </div>
    </div>';
    }

    exit($respone);
}
if (isset($_POST['foodId'])) {
    $foodId = $conn->real_escape_string($_POST['foodId']);
    mysqli_query($conn, "UPDATE foods SET food_status = 'หมด' WHERE food_id = '$foodId'");
    exit;
}
if (isset($_POST['showFood2'])) {
    $foodId = $conn->real_escape_string($_POST['foodId2']);
    mysqli_query($conn, "UPDATE foods SET food_status = 'แสดง' WHERE food_id = '$foodId'");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $name_head = "พ่อครัว";
    include("head.php");

    ?>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <?php
        $active = "จัดการอาหาร";
        include("menu.php");
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="d-flex justify-content-start" style="background: #ffac33;">
                <span class="system-rest-1">เมนูอาหาร</span>
            </div>
            <section class="content ">
                <div class="container-fluid">

                    <div class="row">

                        <div class="col-12">

                            <div class="d-flex justify-content-between align-items-center bg-mine-2">
                                <span class="text-maname-food-1">
                                    จัดการอาหาร
                                </span>
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control mr-2" name="search" id="search" placeholder="ค้นหา..." aria-label="search" aria-describedby="addon-wrapping" autocomplete="off" style="width: 250px;">
                                    <select class="form-control" id="category_selected" style="width: 200px;">
                                        <option value="">ทั้งหมด</option>
                                        <?php
                                        $queryCategory = mysqli_query($conn, "SELECT * FROM category");
                                        while ($row = mysqli_fetch_array($queryCategory)) {
                                            $category_id = $row['category_id'];
                                            $category_name = $row['category_name'];
                                        ?>
                                            <option value="<?php echo $category_id; ?>"><?php echo $category_name; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>


                                </div>
                            </div>

                            <div class="d-flex border-mine-3 mb-3">
                                <div class="col-12 p-0">
                                    <div class="d-flex p-2" style="box-shadow: 0 2px 0 0 #3f51b5;">
                                        <div class="col-2">
                                            <div class="d-flex">
                                                <span class="text-title-header">
                                                    รูปภาพ
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex">
                                                <span class="text-title-header">
                                                    หมวดหมู่
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="d-flex">
                                                <span class="text-title-header">
                                                    ชื่ออาหาร
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-title-header">
                                                    ราคา
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-title-header">
                                                    สถานะ
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-title-header">
                                                    แก้ไข
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="show_all_food">

                                    </div>




                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </section>
        </div>


    </div>
    <?php include("link_bottom.php") ?>

    <script src="js/manage_food.js"></script>
    <script src="js/logout.js"></script>
</body>

</html>