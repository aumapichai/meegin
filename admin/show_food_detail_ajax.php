<?php
session_start();
include("../conn.php");
if (isset($_POST['getDetailFood'])) {
    $referenceIdS = $conn->real_escape_string($_POST['referenceIdS']);
    $response = '';

    $queryPaymentType = $conn->query("SELECT * FROM paymet_history WHERE food_reference = '$referenceIdS'");
    $rowPaymentType = $queryPaymentType->fetch_assoc();
    $payment_type = $rowPaymentType['payment_type'];
    $cashIn = 0;
    $cashHidden = '';
    $promtPayHidden = '';
    if ($payment_type == "cash") {
        $cashIn = $rowPaymentType['cash'];
        $promtPayHidden = ' hidden';
    } else {
        $cashHidden = ' hidden';
    }

    $query = mysqli_query($conn, "SELECT * FROM orderdetail WHERE id_reference = '" . $referenceIdS . "' AND problem_status = 'no problem' LIMIT 1");
    $numm = mysqli_num_rows($query);
    $order_id = "";
    $date = "";
    $table_number = "";
    if ($numm > 0) {
        while ($row = mysqli_fetch_array($query)) {
            $order_id = $row['id_reference'];
            $date = date("d/m/Y H:i น.", strtotime($row['created_at']));
            $table_number = $row['table_number'];
        }
    }

    $response .= '<div class="d-flex flex-column justify-content-start">
                <span class="date-order-1">วันที่: ' . $date . '</span>
                <span class="text-number-table" style="font-weight: 700;">โต๊ะ ' . $table_number . '</span>
            </div>
            <div class="row px-0 py-1 mt-2" style="border: 1px solid #007bff; border-radius: 3px 3px 0 0; font-size: 14px; font-weight: 700; background-color: #cce5ff;">
                <div class="col-6">
                    <div class="d-flex justify-content-start">
                        <span class="text-list-food-1">รายการ</span>
                    </div>

                </div>
                <div class="col-2">
                    <div class="d-flex justify-content-center">
                        <span class="text-list-food-1">จำนวน</span>
                    </div>

                </div>
                <div class="col-2">
                    <div class="d-flex justify-content-center">
                        <span class="text-list-food-1">ราคา</span>
                    </div>

                </div>
                <div class="col-2">
                    <div class="d-flex justify-content-end">
                        <span class="text-list-food-1">รวม</span>
                    </div>

                </div>
            </div>
            <div class="row px-0" style="border-bottom: 1px solid #007bff; border-right: 1px solid #007bff; border-left: 1px solid #007bff; font-size: 14px; color: #2d2d2d;">';
    $total = 0;
    $queryFood = mysqli_query($conn, "SELECT DISTINCT foods.food_name AS nameFood FROM orderdetail JOIN foods ON (orderdetail.food_id = foods.food_id) WHERE orderdetail.id_reference = '" . $referenceIdS . "' AND orderdetail.problem_status = 'no problem'");
    $queryNumItem = mysqli_num_rows($queryFood);
    while ($row2 = mysqli_fetch_array($queryFood)) {
        $food_name = $row2['nameFood'];
        $queryFoodId = mysqli_query($conn, "SELECT food_id FROM foods WHERE food_name = '" . $food_name . "'");
        $rowFoodId = mysqli_fetch_array($queryFoodId);
        $food_id = $rowFoodId['food_id'];
        $queryQuanity = mysqli_query($conn, "SELECT SUM(quanity) AS orderQuanity FROM orderdetail WHERE food_id = '" . $food_id . "' AND id_reference = '" . $referenceIdS . "' AND problem_status = 'no problem'");
        $rowQunity = mysqli_fetch_array($queryQuanity);
        $queryPrice = mysqli_query($conn, "SELECT DISTINCT price_discount AS orderPice FROM orderdetail WHERE food_id = '" . $food_id . "' AND id_reference = '" . $referenceIdS . "' AND problem_status = 'no problem'");
        $rowPrice = mysqli_fetch_array($queryPrice);
        $price = $rowPrice['orderPice'];
        $quanity = $rowQunity['orderQuanity'];
        // $status = $row2['orderStatus'];

        $total += $price * $quanity;
        $response .= '
                    <div class="col-6">
                        <div class="d-flex justify-content-start">
                            <span class="text-list-food-2">' . $food_name . '</span>
                        </div>

                    </div>
                    <div class="col-2">
                        <div class="d-flex justify-content-center">
                            <span class="text-list-food-2">' . $quanity . '</span>
                        </div>

                    </div>
                    <div class="col-2">
                        <div class="d-flex justify-content-center">
                            <span class="text-list-food-2">' . $price . '</span>
                        </div>

                    </div>
                    <div class="col-2">
                        <div class="d-flex justify-content-end align-items-center">
                            <span class="text-list-food-2">' . $quanity * $price . '</span>
                        </div>

                    </div>';
    }
    $response .= '</div>
    <div class="row px-0 py-1" style="border-bottom: 1px solid #007bff; border-left: 1px solid #007bff; border-right: 1px solid #007bff; border-radius: 0 0 3px 3px; font-size: 14px; font-weight: 700;">
    <div class="col-6">
        <div class="d-flex justify-content-start">
            <span class="text-list-food-1">รวม' . ' ' . $queryNumItem . ' ' . ' รายการ</span>
        </div>
    </div>
    <div class="col-6">
        <div class="d-flex justify-content-end">
            <span class="text-list-food-1">' . number_format($total) . '</span>
        </div>
    </div>
   
</div>
    <div class="row px-0 py-1">
    <div class="col-6" ' . $cashHidden . '>
    <div class="d-flex justify-content-start">
        <span class="text-list-food-1">เงินสด</span>
    </div>
</div>
<div class="col-6" ' . $cashHidden . '>
    <div class="d-flex justify-content-end">
        <span class="text-list-food-1">' . number_format($cashIn, 2) . '</span>
    </div>
</div>
<div class="col-6" ' . $cashHidden . '>
    <div class="d-flex justify-content-start">
        <span class="text-list-food-1">เงินทอน</span>
    </div>
</div>
<div class="col-6" ' . $cashHidden . '>
    <div class="d-flex justify-content-end">
        <span class="text-list-food-1">' . number_format($cashIn - $total, 2) . '</span>
    </div>
</div>


<div class="col-6" ' . $promtPayHidden . '>
    <div class="d-flex justify-content-start">
        <span class="text-list-food-1">พร้อมเพย์</span>
    </div>
</div>
<div class="col-6" ' . $promtPayHidden . '>
    <div class="d-flex justify-content-end">
        <span class="text-list-food-1">' . number_format($total, 2) . '</span>
    </div>
</div>

<div class="col-12">
    <div class="d-flex justify-content-start">
        <span class="text-list-food-1">***ราคารวม Vat 7% แล้ว</span>
    </div>
</div>
 <input type="hidden" value="' . $cashIn . '" id="cash_amount_DB"">
    </div>';
    exit($response);
}
