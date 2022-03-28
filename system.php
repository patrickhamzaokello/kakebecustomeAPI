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
    <title>Delivered Orders</title>
</head>
<?php
//set headers to NOT cache a page
header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
header("Pragma: no-cache"); //HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

header("Access-Control-Allow-Origin: *");

include_once 'Includes/config/Database.php';
include_once 'Includes/TableFunctions/Order.php';

$database = new Database();
$db = $database->getConnection();


$order = new Order($db);


$result = $order->readTodaysOrders();


?>
<body>
<header>
    <nav>

        <div class="currentpage">
            <p>
                <span><a href="../index">Admin /</a></span>
                <a href="../index">pk</a>
            </p>
        </div>

    </nav>
</header>
<main>



    <div class="mainpanel">

        <div class="sectionheading">
            <h3 class="sectionlable">Orders</h3>
            <h6 class="sectionlable">Manage all orders here</h6>
        </div>


        <div class="elements">

            <div class="activities">

                <?php if ($result) : ?>

                    <div class="childrencontainer">


                        <?php
                        foreach ($result as $row) :
                            ?>

                           <?php $json = json_decode($row["shipping_address"], true);?>


                            <div class="product-card">

                                <p class="artistlable">Name <span
                                            class="ordervalue"> <?= $json['name']?> </span></p>
                                <p class="artistlable">Order No <span
                                            class="ordervalue"> KS_0<?= $row["id"]?> </span></p>
                                <p class="artistlable">Date Added <span
                                            class="ordervalue"><?= $row["created_at"] ?> </span></p>
                                <div class="addresslayout">

                                    <p class="artistlable">Address <span
                                                class="ordervalue"><?= $json['address']?> , <?=$json['city']; ?> </span></p>
                                    <p class="artistlable">Contact <span
                                                class="ordervalue"><?= $json['phone']; ?> </span></p>

                                </div>
                                <p class="artistlable">Tag <span
                                            class="ordervalue">New</span> <span
                                            class="artistlable">Status <span
                                                class="ordervalue smalltag"><?= $row["delivery_status"] ?></span> </span>
                                </p>
                                <p class="artistlable">Total Amount (UGX) <span
                                            class="ordervalue"><?= number_format($row["grand_total"]) ?> </span>
                                </p>


                            </div>

                        <?php endforeach ?>

                    </div>


                <?php else : ?>
                    No Orders Left
                <?php endif ?>


            </div>

        </div>

    </div>
</main>

</body>


</html>


