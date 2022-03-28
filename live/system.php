<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0" /> -->
    <link rel="icon" type="image/x-icon" href="assets/z_favicon.png">

    <link rel="stylesheet" href="../css/main.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <title>Real Time Orders</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #32312f;
            font-family: "Montserrat";
        }

        .table-container {
            padding: 0 10%;
            margin: 40px auto 0;
        }

        .heading {
            font-size: 40px;
            text-align: center;
            color: #f1f1f1;
            margin-bottom: 40px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background-color: #ff1046;
        }

        .table thead tr th {
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.35px;
            color: #ffffff;
            opacity: 1;
            padding: 12px;
            vertical-align: top;
            border: 1px solid #dee2e685;
        }

        .table tbody tr td {
            font-size: 14px;
            letter-spacing: 0.35px;
            font-weight: normal;
            color: #000;
            background-color: #f2f2f2;
            padding: 8px;
            text-align: center;
            border: 1px solid #dee2e685;
        }

        .table .text_open {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 0.35px;
            color: #ff1800;
        }

        .table .text_green{
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 0.35px;
            color: green;
        }

        .table tbody tr td .btn {
            width: 130px;
            text-decoration: none;
            line-height: 35px;
            display: inline-block;
            background-color: #3c3f44;
            font-weight: 200;
            color: #ffffff;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            font-size: 14px;
            opacity: 1;
        }

        @media (max-width: 768px) {
            .table thead {
                display: none;
            }

            .table,
            .table tbody,
            .table tr,
            .table td {
                display: block;
                width: 100%;
            }

            .table tr {
                margin-bottom: 15px;
            }

            .table tbody tr td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            .table td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-weight: 600;
                font-size: 14px;
                text-align: left;
            }

        }
    </style>


</head>
<?php
//set headers to NOT cache a page
header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
header("Pragma: no-cache"); //HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

header("Access-Control-Allow-Origin: *");

include_once '../Includes/config/Database.php';
include_once '../Includes/TableFunctions/Order.php';

$database = new Database();
$db = $database->getConnection();


$order = new Order($db);


$result = $order->readTodaysOrders();


?>

<body>

<main>

    <div class="table-container">
        <h1 class="heading">Today's Orders</h1>
        <table class="table">
            <thead>
            <tr>
                <th>Order no</th>
                <th>Name</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Total Amount</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            </thead>
            <?php if ($result) : ?>

                <tbody>


                <?php
                foreach ($result as $row) :
                    ?>

                    <?php $json = json_decode($row["shipping_address"], true);
                    $phpdate = strtotime($row["created_at"]);
                    $mysqldate = date('h:i A, d/m/Y', $phpdate); ?>


                    <tr>
                        <td data-label="Order no">KS_0<?= $row["id"] ?></td>
                        <td data-label="Name"><?= $json['name'] ?> </td>
                        <td data-label="Address"><?= $json['address'] ?> , <?= $json['city']; ?></td>
                        <td data-label="Contact"><?= $json['phone']; ?></td>
                        <td data-label="Total Amount"><?= number_format($row["grand_total"]) ?></td>
                        <td data-label="Date"><?= $mysqldate ?></td>
                        <td data-label="Status">
                            <?php if ($row['delivery_status'] == "pending") : ?>
                            <span class="text_open">
                                        <?= $row['delivery_status'] ?>
                                    </span></td>
                        <?php else : ?>
                            <span class="text_green">
                                        <?= $row['delivery_status'] ?>
                                    </span></td>
                        <?php endif ?>
                    </tr>

                <?php endforeach ?>

                </tbody>
            <?php else : ?>
                No Orders Left
            <?php endif ?>
        </table>
    </div>


</main>

</body>


</html>