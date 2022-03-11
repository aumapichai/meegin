let tableNum = null;
let tableNumTranf = null;
let zoneNumTranf = null;
let table_check_order = null;
let zone_check_order = null;
let typePay = null;
let total_amount_money_pay = null;
$(document).ready(function () {
  checkOrderChang1();
  chekTableReference1();
  checkStatusOrder1();

  checkStustCancel1();
  checkStustFinish1();

  notificaton_1();

  //เปลี่ยนแผนผัง
  $("#change_img_diagram").change(function () {
    var fd = new FormData();
    var files = $("#change_img_diagram")[0].files;

    // Check file selected or not
    if (files.length > 0) {
      fd.append("upload-img", files[0]);
      $.ajax({
        url: "sell.php",
        type: "post",
        data: fd,
        contentType: false,
        processData: false,
        success: function (e) {
          $("#img_diagram").attr("src", e);
        },
      });
    }
  });

  //พิมพ์ QRcode
  $(".btn-print-QRcode").click(function () {
    let tableNumberQRcode = $.trim($(".number_table").text());
    printExternal("QRcode_print.php?tableN=" + tableNumberQRcode);
  });

  //print bill

  //ล้างรายการอาหารทั้งหมด
  $(".btn-clear-list-add-order").click(function () {
    let numItem = parseInt($.trim($(".numListFoodAddOrder").text()));
    if (numItem != 0) {
      Swal.fire({
        title: "ลบรายการอาหารทั้งหมด!",
        text: "คุณต้องการลบรายการอาหารทั้งหมดจริงหรือไม่?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "ยืนยัน",
        cancelButtonText: "ยกเลิก",
      }).then((result) => {
        if (result.isConfirmed) {
          DeleteAllIncart();
        }
      });
    }
  });

  //ยกเลิกเพิ่มรายการอาหาร
  $(".btn-cancel-add-order").click(function () {
    $.ajax({
      url: "sell.php",
      method: "POST",
      dataType: "text",
      data: {
        checkItemInCart: 1,
      },
      success: function (e) {
        if (parseInt(e) != 0) {
          Swal.fire({
            title: "ยืนยัน",
            text: "ลบราการอาหารทั้งหมดที่เลือกไว้!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก",
          }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                url: "sell.php",
                method: "POST",
                dataType: "text",
                data: {
                  deleteAll: 1,
                },
                success: function (e) {
                  numItemsAddOrder();
                  getFoodInCartModal($(".table_number_addOrder").val());
                  $(".click_cancel_hidden").click();
                },
              });
            }
          });
        } else {
          $(".click_cancel_hidden").click();
        }
      },
    });
  });

  //นับจำนวนรายการอาหารทั้ง สั่งจากพนักงานเอง
  numItemsAddOrder();

  //เพิ่ม Order
  $(".btn-category-nav").first().addClass("add-order-active");
  let categoryIdFirst = $(".btn-category-nav").first().attr("data-name");
  let category = $(".btn-category-nav");

  //ปิด modal
  // $(".btn-cancel-add-order").click(function () {});

  getFoodFromType(categoryIdFirst);

  category.click(function () {
    $(this)
      .addClass("add-order-active")
      .siblings()
      .removeClass("add-order-active");
    let categoryIdNew = $(this).attr("data-name");
    getFoodFromType(categoryIdNew);
  });

  //ดึง zone number เริ่มต้นมา
  let btnZone = $(".btn_zone_table");
  btnZone.first().addClass("active");
  let zoneNum = btnZone.first().val();
  getTableFromZone(zoneNum);
  zone_check_order = zoneNum;

  //
  $("#table_number_slecte").val(zoneNum);

  //เปลี่ยน zone number โดยการคลิก
  $(".btn_zone_main").click(function () {
    $(this).children().addClass("active");
    $(this).siblings().children().removeClass("active");
    zoneNum = $(this).children().val();
    zone_check_order = $(this).children().val();
    getTableFromZone(zoneNum);
    $("#table_number_slecte").val(zoneNum);
  });

  //ค่าโซนเริ่มต้น ย้ายโต๊ะ
  $(".btn_zone_transfer_table_main").first().children().addClass("active");
  zoneNumTranf = $(".btn_zone_transfer_table_main").first().children().val();
  getAllTableTranf(zoneNumTranf, $("#table_number_slecte").val());
  //คลิกเลือกโซน ย้านโต๊ะ
  $(".btn_zone_transfer_table_main").click(function () {
    tableNumTranf = $("#table_number_slecte").val();
    zoneNumTranf = $(this).children().val();
    getAllTableTranf(zoneNumTranf, tableNumTranf);
    $(this).children().addClass("active");
    $(this).siblings().children().removeClass("active");
  });

  //ยืนยันการย้ายโต๊ะ
  $(".btn-confirm-transfer-table").click(function () {
    let tableOld = $("#table_number_slecte").val();
    let tableNew = $("#table_number_trasf_new").val();
    $.ajax({
      url: "sell.php",
      method: "POST",
      dataType: "text",
      data: {
        trasfTable: 1,
        tableNumOld: tableOld,
        tableNumNew: tableNew,
      },
      success: function (e) {
        $(".btn-cancel-traf-table").click();
        getTableFromZone(e, tableNew);
        $(".btn_zone_main").children().removeClass("active");
        let i = parseInt(e) + 1;
        $(".btn_zone_main:nth-child(" + i + ")")
          .children()
          .addClass("active");
      },
    });
  });

  //เลือกประเภทการจ่ายเงิน
  typePay = $(".type_payment").val();
  $(".type_payment").change(function () {
    let amount_total = $("#aumount_money").val();
    typePay = $(this).val();
    if (typePay == "cash") {
      cashPayment(amount_total);
      $(".btn-payment-money").text("ชำระ");
      $(".btn-colse-check-out").attr("hidden", false);
      $(".btn-payment-money").attr("disabled", true);
      $("#show_type_money").show();
    } else if (typePay == "promptpay") {
      qrCodePay(amount_total);
      $(".btn-payment-money").text("ชำระแล้ว");
      $(".btn-colse-check-out").attr("hidden", true);
      $(".btn-payment-money").attr("disabled", false);
      $("#show_type_money").hide();
      // typePay = "cash";
    }
  });
  $(".btn-payment-money").click(function () {
    let amount = $("#aumount_money").val();
    let cashIn = $("#cash_in").val();
    let changeMoney = $("#money_change_in").val();
    let tableN = tableNum;
    if (typePay == "cash") {
      $(".btn-colse-check-out").click();
      $.ajax({
        url: "sell.php",
        method: "POST",
        dataType: "text",
        data: {
          chekBill: 1,
          table_number3: tableN,
          amount: amount,
          cashAmount: cashIn,
        },
        success: function (e) {
          Swal.fire({
            position: "top",
            title: `เงินทอน ${changeMoney} บาท`,
          }).then((result) => {
            if (result.isConfirmed) {
              printExternal(
                "chekbill_print.php?idReference=" + e + "&cash=" + cashIn
              );
            } else {
              printExternal(
                "chekbill_print.php?idReference=" + e + "&cash=" + cashIn
              );
            }
          });
          getTableFromZone(zone_check_order, tableN);
        },
      });
    } else if (typePay == "promptpay") {
      $(".btn-colse-check-out").click();
      Swal.fire({
        title: "ชำระเงิน",
        text: "คุณได้เช็คการชำระเงินจากลูกค้าแล้วใช่ไหม?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "ยืนยัน",
        cancelButtonText: "ยกเลิก",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "sell.php",
            method: "POST",
            dataType: "text",
            data: {
              chekBillQRcode: 1,
              tableNumber: tableN,
              amount: amount,
            },
            success: function (e) {
              printExternal("chekbill_print.php?idReference=" + e);
              getTableFromZone(zone_check_order, tableN);

              $("#btn-reset-type-payment").click();
              typePay = "cash";
              $(".btn-payment-money").text("ชำระ");
              $(".btn-colse-check-out").attr("hidden", false);
              $(".btn-payment-money").attr("disabled", true);
            },
          });
        }
      });
    }
  });
});

function getTableFromZone(zoneNum, tableNumCheck = "") {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      getTableZone: 1,
      zoneId: zoneNum,
    },
    success: function (e) {
      $("#show_list_table").html(e);
      if (tableNumCheck == "") {
        tableNum = $(".btn_table_main").first().children().val();
        table_check_order = $(".btn_table_main").first().children().val();
      } else {
        tableNum = tableNumCheck;
      }
      getAllDetailOrder(tableNum);
      $(".btn_table_main").click(function () {
        tableNum = $(this).children().val();
        table_check_order = $(this).children().val();
        $(this).children().css({ border: "4px solid #6f42c1" });
        $(this).siblings().children().css({ border: "none" });
        getAllDetailOrder(tableNum);
      });
    },
  });
}

function getAllDetailOrder(tableNum) {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      getDetailOrder: 1,
      tableNum: tableNum,
    },
    success: function (e) {
      $("#show_detail_order").html(e);
      total_amount_money_pay = $(".input_price_total_class").val();
      cashPayment(total_amount_money_pay);
      $("#aumount_money").val($(".input_price_total_class").val());
      let i = 1;
      $(".btn_table_main").each(function () {
        let tableVal = $(this).children().val();
        if (tableVal == tableNum) {
          return false;
        }
        i++;
      });
      $(".btn_table_main:nth-child(" + i + ")")
        .children()
        .css({
          border: "4px solid #6f42c1",
        });
      //เพิ่ม Order Staff
      $(".btn-add-order-staff").click(function () {
        DeleteAllIncart();
        let tableNumberAddOrder = $(this).attr("data-id");
        $(".table_number_addOrder").val(tableNumberAddOrder);
        $(".text-talble-number-selected").html(tableNumberAddOrder);
      });

      //QRcode
      $(".btn_show_QRcode").click(function () {
        tableNum = $(this).attr("data-table-number");
        $(".number_table").html(tableNum);
        getQRcode(tableNum);
      });
      //ยกเลิกโต๊ะ
      $(".btn-cancel-table").click(function () {
        let tableNumber = $(this).attr("data-id");
        Swal.fire({
          title: "คุณต้องการยกเลิกจริงไหม?",
          text: "ยกเลิกโต๊ะ!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "sell.php",
              method: "POST",
              dataType: "text",
              data: {
                cancelTable: 1,
                tableNumber: tableNumber,
              },
              success: function (e) {
                let split = e.split(",");
                zoneNum = split[0];
                tableNum = split[1];
                getTableFromZone(zoneNum, tableNum);
                // getAllDetailOrder(tableNum);
              },
            });
          }
        });
      });
      //ย้ายโต๊ะ
      $(".btn-click-transfer-table").click(function () {
        tableNum = $(this).attr("data-id");
        $("#table_number_slecte").val(tableNum);
        getAllTableTranf(zoneNumTranf, tableNum);
      });

      //check bill
      $(".check_bill_modal").click(function () {
        $("#table_number_chekc_bill").html($(this).attr("data-id"));
        $("#aumount_money").val();
      });

      //print bill
      $(".btn-print-bill").click(function () {
        let tableNumBill = $(this).attr("data-id");
        printExternal("bill_print.php?tableN=" + tableNumBill);
      });

      //ยกเลิกอาหาร
      $(".btn-cancel-cashier").click(function () {
        let orderid = $(this).val();
        Swal.fire({
          title: "ยกเลิกอาหาร",
          text: "คุณแน่ใจนะว่าจะยกเลิกรายการที่เลือก!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "sell.php",
              method: "POST",
              dataType: "text",
              data: {
                cancelfood: 1,
                orDetailId: orderid,
              },
              success: function (e) {
                Swal.fire({
                  position: "top-end",
                  icon: "success",
                  title: "ยกเลิกอาหารสำเร็จ",
                  showConfirmButton: false,
                  timer: 1500,
                });
                getAllTableTranf(zoneNumTranf, tableNum);
              },
            });
          }
        });
      });
    },
  });
}

function getAllTableTranf(zone, table) {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      getTableTranf: 1,
      zoneNum: zone,
      tableNum: table,
    },
    success: function (e) {
      $("#show_list_transfer_table").html(e);
      $(".btn-confirm-transfer-table").attr("disabled", true);
      $(".btn_tranfer_table_main").click(function () {
        $(".btn-confirm-transfer-table").attr("disabled", false);
        tableNumTranf = $(this).children().val();
        $("#table_number_trasf_new").val(tableNumTranf);
        $(this).children().addClass("bg-primary");
        $(this).siblings().children().removeClass("bg-primary");
      });
    },
  });
}

//get QRcode
function getQRcode(table) {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      getIdReferen: 1,
      tableNum: table,
    },
    success: function (e) {
      let split = e.split(",");
      let QRcode = split[0];
      let QRcodeTxt = split[1];
      $(".img_QRcode_referen").attr("src", e);
      $(".text-id-referen").html(QRcodeTxt);
    },
  });
}

function checkOrderChang1() {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      checkOrderNum1: 1,
    },
    success: function (e) {
      let numOrderFirst = parseInt(e);
      checkOrderChang2(numOrderFirst);
    },
  });
}

function checkOrderChang2(data) {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      checkOrderNum2: 1,
    },
    success: function (e) {
      let numOrderTwo = parseInt(e);
      if (data == numOrderTwo) {
        setTimeout(() => {
          checkOrderChang2(data);
        }, 1000);
      } else {
        getTableFromZone(zone_check_order, table_check_order);
        setTimeout(() => {
          checkOrderChang1();
        }, 1000);
      }
    },
  });
}

function chekTableReference1() {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      checkTableRef1: 1,
    },
    success: function (e) {
      let numRefFirst = parseInt(e);
      setTimeout(() => {
        chekTableReference2(numRefFirst);
      }, 1000);
    },
  });
}

function chekTableReference2(data) {
  let dataIn = data;
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      checkTableRef2: 1,
    },
    success: function (e) {
      let numRefTwo = parseInt(e);
      if (dataIn == numRefTwo) {
        setTimeout(() => {
          chekTableReference2(dataIn);
        }, 1000);
      } else {
        getTableFromZone(zone_check_order, table_check_order);
        setTimeout(() => {
          chekTableReference1();
        }, 1000);
      }
    },
  });
}

//เช็คการเปลี่ยนแปลง status order
function checkStatusOrder1() {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      checkStatusOrder1: 1,
    },
    success: function (e) {
      let numOrDetFirst = parseInt(e);
      setTimeout(() => {
        checkStatusOrder2(numOrDetFirst);
      }, 1000);
    },
  });
}

function checkStatusOrder2(data) {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      checkStatusOrder2: 1,
    },
    success: function (e) {
      let numOrDetTwo = parseInt(e);
      if (data == numOrDetTwo) {
        setTimeout(() => {
          checkStatusOrder2(data);
        }, 1000);
      } else {
        setTimeout(() => {
          checkStatusOrder1();
        }, 1000);
        getTableFromZone(zone_check_order, table_check_order);
      }
    },
  });
}

//เช็คการเปลี่ยนแปลง สถานะอาหาร cancel ยกเลิก
function checkStustCancel1() {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      checkStatusCancel: 1,
    },
    success: function (e) {
      let numCancelFirst = parseInt(e);
      setTimeout(() => {
        checkStustCancel2(numCancelFirst);
      }, 1000);
    },
  });
}

function checkStustCancel2(data) {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      checkStatusCancel: 1,
    },
    success: function (e) {
      let numCancelTwo = parseInt(e);
      if (data == numCancelTwo) {
        setTimeout(() => {
          checkStustCancel2(data);
        }, 1000);
      } else {
        setTimeout(() => {
          checkStustCancel1();
        }, 1000);
        getTableFromZone(zone_check_order, table_check_order);
      }
    },
  });
}

//เช็คการเปลี่ยนแปลง สถานะอาหาร finish หมด
function checkStustFinish1() {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      checkStatusFinish: 1,
    },
    success: function (e) {
      let numFinishFirst = parseInt(e);
      setTimeout(() => {
        checkStustFinish2(numFinishFirst);
      }, 1000);
    },
  });
}

function checkStustFinish2(data) {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      checkStatusFinish: 1,
    },
    success: function (e) {
      let numFinishTwo = parseInt(e);
      if (data == numFinishTwo) {
        setTimeout(() => {
          checkStustFinish2(data);
        }, 1000);
      } else {
        setTimeout(() => {
          checkStustFinish1();
        }, 1000);
        getTableFromZone(zone_check_order, table_check_order);
      }
    },
  });
}

//shwo จ่ายเงินสด
function cashPayment(amount) {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      payCash: 1,
      amount: amount,
    },
    success: function (e) {
      $(".show_type_from_seleted_payment").html(e);
      $(".btn-payment-money").attr("disabled", true);

      //ปุ่ม พอดี
      $(".btn-money-add-4").click(function () {
        $("#incom_money").val($("#money_out").val());
        $(".btn-payment-money").attr("disabled", false);
        $("#money_change").val(0);
        $("#money_change_in").val(0);
        $("#cash_in").val($("#money_out").val());
      });

      //ปุ่มล้าง
      $(".btn-clear-plus-4").click(function () {
        $("#incom_money").val(0);
        $(".btn-payment-money").attr("disabled", true);
        $("#money_change").val(0);
        $("#money_change_in").val(0);
        $("#cash_in").val($("#money_out").val());
      });

      //1 บาท
      $("#cash_money_1").click(function () {
        let This1B = $.trim($(this).text());
        let cashOld = $("#incom_money").val();
        let fullCash = $("#money_out").val();
        let inputB = parseInt(cashOld) + parseInt(This1B);
        $("#incom_money").val(inputB);
        let moneyChange = inputB - parseInt(fullCash);
        $("#money_change").val(moneyChange);

        if (moneyChange >= 0) {
          $(".btn-payment-money").attr("disabled", false);
          $("#money_change_in").val(moneyChange);
          $("#cash_in").val(inputB);
        } else {
          $(".btn-payment-money").attr("disabled", true);
        }
      });

      //5 บาท
      $("#cash_money_5").click(function () {
        let This1B = $.trim($(this).text());
        let cashOld = $("#incom_money").val();
        let fullCash = $("#money_out").val();
        let inputB = parseInt(cashOld) + parseInt(This1B);
        $("#incom_money").val(inputB);
        let moneyChange = inputB - parseInt(fullCash);
        $("#money_change").val(moneyChange);

        if (moneyChange >= 0) {
          $(".btn-payment-money").attr("disabled", false);
          $("#money_change_in").val(moneyChange);
          $("#cash_in").val(inputB);
        } else {
          $(".btn-payment-money").attr("disabled", true);
        }
      });

      //10 บาท
      $("#cash_money_10").click(function () {
        let This1B = $.trim($(this).text());
        let cashOld = $("#incom_money").val();
        let fullCash = $("#money_out").val();
        let inputB = parseInt(cashOld) + parseInt(This1B);
        $("#incom_money").val(inputB);
        let moneyChange = inputB - parseInt(fullCash);
        $("#money_change").val(moneyChange);

        if (moneyChange >= 0) {
          $(".btn-payment-money").attr("disabled", false);
          $("#money_change_in").val(moneyChange);
          $("#cash_in").val(inputB);
        } else {
          $(".btn-payment-money").attr("disabled", true);
        }
      });

      //20 บาท
      $("#cash_money_20").click(function () {
        let This1B = $.trim($(this).text());
        let cashOld = $("#incom_money").val();
        let fullCash = $("#money_out").val();
        let inputB = parseInt(cashOld) + parseInt(This1B);
        $("#incom_money").val(inputB);
        let moneyChange = inputB - parseInt(fullCash);
        $("#money_change").val(moneyChange);

        if (moneyChange >= 0) {
          $(".btn-payment-money").attr("disabled", false);
          $("#money_change_in").val(moneyChange);
          $("#cash_in").val(inputB);
        } else {
          $(".btn-payment-money").attr("disabled", true);
        }
      });

      //50 บาท
      $("#cash_money_50").click(function () {
        let This1B = $.trim($(this).text());
        let cashOld = $("#incom_money").val();
        let fullCash = $("#money_out").val();
        let inputB = parseInt(cashOld) + parseInt(This1B);
        $("#incom_money").val(inputB);
        let moneyChange = inputB - parseInt(fullCash);
        $("#money_change").val(moneyChange);

        if (moneyChange >= 0) {
          $(".btn-payment-money").attr("disabled", false);
          $("#money_change_in").val(moneyChange);
          $("#cash_in").val(inputB);
        } else {
          $(".btn-payment-money").attr("disabled", true);
        }
      });

      //100 บาท
      $("#cash_money_100").click(function () {
        let This1B = $.trim($(this).text());
        let cashOld = $("#incom_money").val();
        let fullCash = $("#money_out").val();
        let inputB = parseInt(cashOld) + parseInt(This1B);
        $("#incom_money").val(inputB);
        let moneyChange = inputB - parseInt(fullCash);
        $("#money_change").val(moneyChange);

        if (moneyChange >= 0) {
          $(".btn-payment-money").attr("disabled", false);
          $("#money_change_in").val(moneyChange);
          $("#cash_in").val(inputB);
        } else {
          $(".btn-payment-money").attr("disabled", true);
        }
      });

      //500 บาท
      $("#cash_money_500").click(function () {
        let This1B = $.trim($(this).text());
        let cashOld = $("#incom_money").val();
        let fullCash = $("#money_out").val();
        let inputB = parseInt(cashOld) + parseInt(This1B);
        $("#incom_money").val(inputB);
        let moneyChange = inputB - parseInt(fullCash);
        $("#money_change").val(moneyChange);

        if (moneyChange >= 0) {
          $(".btn-payment-money").attr("disabled", false);
          $("#money_change_in").val(moneyChange);
          $("#cash_in").val(inputB);
        } else {
          $(".btn-payment-money").attr("disabled", true);
        }
      });

      //1000 บาท
      $("#cash_money_1000").click(function () {
        let This1B = $.trim($(this).text());
        let cashOld = $("#incom_money").val();
        let fullCash = $("#money_out").val();
        let inputB = parseInt(cashOld) + parseInt(This1B);
        $("#incom_money").val(inputB);
        let moneyChange = inputB - parseInt(fullCash);
        $("#money_change").val(moneyChange);

        if (moneyChange >= 0) {
          $(".btn-payment-money").attr("disabled", false);
          $("#money_change_in").val(moneyChange);
          $("#cash_in").val(inputB);
        } else {
          $(".btn-payment-money").attr("disabled", true);
        }
      });

      $(".check_bill_modal").click(function () {
        $(".incom_money").val(0);
        $(".money_change").val(0);
      });

      $(".incom_money").focus(function () {
        $(this).val("");
      });
      let moneyAll = $(".money_out").val();
      $(".incom_money").keyup(function () {
        let moneyThis = $(this).val();
        let moneyChange = moneyThis - moneyAll;
        $(".money_change").val(moneyChange);

        if ($(".money_change").val() >= 0) {
          $(".btn-payment-money").attr("disabled", false);
          $("#cash_in").val(moneyThis);
          $("#money_change_in").val(moneyChange);
        } else {
          $(".btn-payment-money").attr("disabled", true);
        }
      });
    },
  });
}

//show จ่าย Qrcode
function qrCodePay(amount) {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      payQRcode: 1,
      amount: amount,
    },
    success: function (e) {
      $(".show_type_from_seleted_payment").html(e);
    },
  });
}

//แสดงรายการอาหารตามประเภท add order
function getFoodFromType(e) {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      getFoodFType: 1,
      categoryId2: e,
    },
    success: function (e) {
      $("#show-food-add-order").html(e);
      getFoodInCartModal($(".table_number_addOrder").val());
      $(".btn-click-addOrder").click(function () {
        let foodId = $(this).val();
        let tableNumber4 = $(".table_number_addOrder").val();
        $.ajax({
          url: "sell.php",
          method: "POST",
          dataType: "text",
          data: {
            addOrderInCart: 1,
            food_id: foodId,
            tableNumber: tableNumber4,
          },
          success: function (e) {
            getFoodInCartModal(tableNumber4);
            numItemsAddOrder();
          },
        });
      });
    },
  });
}

//แสดงรายการอาหารที่เลือกบน Modal
function getFoodInCartModal(e) {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      getFoodCartM: 1,
      tableNumberCartEp: e,
    },
    success: function (e) {
      $(".list-food-add-order").html(e);
      //เพิ่มจำนวนรายการ
      $(".btn-add-quantity-addOrder").click(function () {
        let cartId = $(this).attr("data-id");
        let priceUnit = $(
          `.price-to-unit-food[data-id='${$(this).data("id")}']`
        );
        let input_qty_num = $(
          `.input-quantity[data-id='${$(this).data("id")}']`
        );

        let total_input = $(
          `.total_price_each_other[data-id='${$(this).data("id")}']`
        );
        let priceUnitVal = $.trim(priceUnit.text());

        if (input_qty_num.val() >= 1 && input_qty_num.val() < 10) {
          input_qty_num.val(function (i, oldval) {
            return ++oldval;
          });
        }
        let numQtyIn = input_qty_num.val();
        let totalEachOther = parseInt(priceUnitVal) * parseInt(numQtyIn);
        total_input.text(totalEachOther);

        $.ajax({
          url: "sell.php",
          method: "POST",
          dataType: "text",
          data: {
            addQty: 1,
            cartId: cartId,
            qty: numQtyIn,
          },
          success: function (e) {
            numItemsAddOrder();
            getFoodInCartModal($(".table_number_addOrder").val());
          },
        });
      });
      //ลบจำนวนรายการอาหาร
      $(".btn-minus-quantity-addOrder").click(function () {
        let cartId = $(this).attr("data-id");
        let priceUnit = $(
          `.price-to-unit-food[data-id='${$(this).data("id")}']`
        );
        let input_qty_num = $(
          `.input-quantity[data-id='${$(this).data("id")}']`
        );

        let total_input = $(
          `.total_price_each_other[data-id='${$(this).data("id")}']`
        );
        let priceUnitVal = $.trim(priceUnit.text());

        if (input_qty_num.val() > 1) {
          input_qty_num.val(function (i, oldval) {
            return --oldval;
          });
        }
        let numQtyIn = input_qty_num.val();
        let totalEachOther = parseInt(priceUnitVal) * parseInt(numQtyIn);
        total_input.text(totalEachOther);

        $.ajax({
          url: "sell.php",
          method: "POST",
          dataType: "text",
          data: {
            plusQty: 1,
            cartId: cartId,
            qty: numQtyIn,
          },
          success: function (e) {
            numItemsAddOrder();
            getFoodInCartModal($(".table_number_addOrder").val());
          },
        });
      });

      //ลบรายการอาหาร
      $(".remove_btn_add_order").click(function () {
        let cart_id = $(this).attr("data-id");
        $.ajax({
          url: "sell.php",
          method: "POST",
          dataType: "text",
          data: {
            deletItemsInCart: 1,
            cartId: cart_id,
          },
          success: function (e) {
            numItemsAddOrder();
            getFoodInCartModal($(".table_number_addOrder").val());
          },
        });
      });

      //เพิ่มรายละเอียดอาหาร
      $(".edit_btn_add_order").click(function () {
        let cartId = $(this).attr("data-id");
        Swal.fire({
          title: "กรุณาระบุรายละเอียด",
          input: "text",
          inputAttributes: {
            autocapitalize: "off",
          },
          showCancelButton: true,
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกลิก",
        }).then((result) => {
          if (result.isConfirmed) {
            let textDetail = result.value;
            $.ajax({
              url: "sell.php",
              method: "POST",
              dataType: "text",
              data: {
                addDetailCart: 1,
                cartId: cartId,
                txtDetail: textDetail,
              },
              success: function (e) {
                numItemsAddOrder();
                getFoodInCartModal($(".table_number_addOrder").val());
              },
            });
          }
        });
      });
      //ทำการเพิ่ม
      $(".btn-add-list-order").click(function () {
        let numItem = parseInt($.trim($(".numListFoodAddOrder").text()));
        if (numItem != 0) {
          Swal.fire({
            title: "ยืนยันการเพิ่มอาหาร",
            text: "คุณต้องการเพิ่มอาหารจริงหรือไม่",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก",
          }).then((result) => {
            if (result.isConfirmed) {
              let formData = $("#form-confirm-order").serialize();
              $.ajax({
                type: "POST",
                url: "insert_order.php",
                data: formData, // serializes the form's elements.
                success: function (data) {
                  getTableFromZone(zone_check_order, table_check_order);
                  $(".click_cancel_hidden").click();
                  DeleteAllIncart();
                },
              });
            }
          });
        }
      });
    },
  });
}

function numItemsAddOrder() {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      getItemsAddOrder: 1,
    },
    success: function (e) {
      let num = "0";
      if (e != "") {
        num = e;
      }
      $(".numListFoodAddOrder").html(num);
    },
  });
}

//ลบรายการทั้งหมดที่อยู่ใน Cart
function DeleteAllIncart() {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      deleteAll: 1,
    },
    success: function (e) {
      numItemsAddOrder();
      getFoodInCartModal($(".table_number_addOrder").val());
    },
  });
}

//แจ้งเตือน
function notificaton_1() {
  $.ajax({
    url: "sell.php",
    method: "POST",
    dataType: "text",
    data: {
      checkNotification: 1,
    },
    success: function (e) {
      if (e != "0") {
        let split = e.split(",");
        let id = split[0];
        let table = split[1];
        let type = split[2];
        $(".btn-show-notification").click();
        let textNotification = "โต๊ะ " + table + " " + type;
        $("#notificationType").text(textNotification);
        $(".btn-dialog-cancel-null").click(function () {
          $.ajax({
            url: "sell.php",
            method: "POST",
            dataType: "text",
            data: {
              changeStatusNotification: 1,
              notificatonId: id,
            },
            success: function (e) {
              setTimeout(() => {
                notificaton_1();
              }, 1000);
            },
          });
        });
      } else {
        setTimeout(() => {
          notificaton_1();
        }, 3000);
      }
    },
  });
}

function printExternal(url) {
  var printWindow = window.open(
    url,
    "Print",
    "left=10, top=10, width=950, height=500, toolbar=0, resizable=0"
  );

  printWindow.addEventListener(
    "load",
    function () {
      if (Boolean(printWindow.chrome)) {
        printWindow.print();
        setTimeout(() => {
          printWindow.close();
        }, 500);
      } else {
        printWindow.print();
        printWindow.close();
      }
    },
    true
  );
}
