<?php
include 'data.inc.php';
include 'functions.inc.php';

$orders = array(500, 510, 520, 530, 540);
$selectedOrder = 520;

$subtotal = ($quantity1 * $price1)
    + ($quantity2 * $price2)
    + ($quantity3 * $price3)
    + ($quantity4 * $price4);
$shipping = ($subtotal > 10000) ? 100 : 200;
$grandTotal = $subtotal + $shipping;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CISC3003 Practice 09 After PHP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="crm-page">
    <div class="app-shell">
        <?php include 'header.inc.php'; ?>

        <div class="workspace-shell">
            <?php include 'left.inc.php'; ?>

            <main class="main-content">
                <header class="page-heading">
                    <h2>Order Summaries</h2>
                    <p>Examine your customer orders</p>
                </header>

                <section class="dashboard-grid">
                    <h3 class="visually-hidden">Orders Dashboard</h3>
                    <article class="panel orders-panel">
                        <div class="panel-heading">
                            <h3>My Orders</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="orders-list">
                                <?php for ($i = 0; $i < count($orders); $i++) { ?>
                                <li>
                                    <a href="#"<?php if ($orders[$i] === $selectedOrder) { echo ' class="active"'; } ?>>
                                        Order #<?php echo $orders[$i]; ?>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </article>

                    <article class="panel details-panel">
                        <div class="panel-heading">
                            <h3>Selected Order: #<?php echo $selectedOrder; ?></h3>
                        </div>
                        <div class="panel-body">
                            <table class="orders-table">
                                <caption>Customer: <strong>Mount Royal University</strong></caption>
                                <thead>
                                    <tr>
                                        <th>Cover</th>
                                        <th>Title</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    outputOrderRow($file1, $title1, $quantity1, $price1);
                                    outputOrderRow($file2, $title2, $quantity2, $price2);
                                    outputOrderRow($file3, $title3, $quantity3, $price3);
                                    outputOrderRow($file4, $title4, $quantity4, $price4);
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr class="totals">
                                        <td colspan="4">Subtotal</td>
                                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                                    </tr>
                                    <tr class="totals">
                                        <td colspan="4">Shipping</td>
                                        <td>$<?php echo number_format($shipping, 2); ?></td>
                                    </tr>
                                    <tr class="grandtotals">
                                        <td colspan="4">Grand Total</td>
                                        <td>$<?php echo number_format($grandTotal, 2); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </article>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
