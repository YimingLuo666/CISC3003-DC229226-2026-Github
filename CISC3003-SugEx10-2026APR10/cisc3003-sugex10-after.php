<?php

include 'includes/book-utilities.inc.php';

$studentId = 'DC229226';
$studentName = 'Yiming Luo';
$customers = readCustomers(__DIR__ . '/data/customers.txt');
$selectedCustomerId = filter_input(INPUT_GET, 'customer_id', FILTER_VALIDATE_INT);
$selectedCustomer = null;
$orders = [];

if ($selectedCustomerId === null && isset($_GET['customer_id'])) {
    $selectedCustomerId = filter_var($_GET['customer_id'], FILTER_VALIDATE_INT);
}

if ($selectedCustomerId === false) {
    $selectedCustomerId = null;
}

foreach ($customers as $customer) {
    if ((int) $customer['id'] === (int) $selectedCustomerId) {
        $selectedCustomer = $customer;
        break;
    }
}

if ($selectedCustomer !== null) {
    $orders = readOrders($selectedCustomer, __DIR__ . '/data/orders.txt');
}

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function customerFullName(array $customer)
{
    return trim($customer['first_name'] . ' ' . $customer['last_name']);
}

function customerAddress(array $customer)
{
    $address = $customer['address'] . ', ' . $customer['city'] . ', ' . $customer['state'] . ', ' . $customer['country'];

    if ($customer['postal'] !== '') {
        $address .= ', ' . $customer['postal'];
    }

    return $address;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo h($studentId . ' ' . $studentName); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/material.min.css">
    <link rel="stylesheet" href="css/demo-styles.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="js/material.min.js"></script>
    <script src="js/jquery.sparkline.2.1.2.js"></script>
</head>
<body>
<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <?php include 'includes/header.inc.php'; ?>
    <?php include 'includes/left-nav.inc.php'; ?>

    <main class="mdl-layout__content mdl-color--grey-50">
        <section class="page-content">
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--7-col customers-card card-lesson mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title mdl-color--orange">
                        <h2 class="mdl-card__title-text">Customers</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <table class="mdl-data-table mdl-shadow--2dp customer-table">
                            <thead>
                                <tr>
                                    <th class="mdl-data-table__cell--non-numeric">Name</th>
                                    <th class="mdl-data-table__cell--non-numeric">University</th>
                                    <th class="mdl-data-table__cell--non-numeric">City</th>
                                    <th>Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customers as $customer) : ?>
                                    <tr>
                                        <td class="mdl-data-table__cell--non-numeric">
                                            <a class="customer-link" href="cisc3003-sugex10-after.php?customer_id=<?php echo h($customer['id']); ?>">
                                                <?php echo h(customerFullName($customer)); ?>
                                            </a>
                                        </td>
                                        <td class="mdl-data-table__cell--non-numeric"><?php echo h($customer['university']); ?></td>
                                        <td class="mdl-data-table__cell--non-numeric"><?php echo h(trim($customer['city'])); ?></td>
                                        <td>
                                            <span class="inlinesparkline sales-sparkline"><?php echo h($customer['sales']); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mdl-cell mdl-cell--5-col details-column">
                    <div class="details-card card-lesson mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                            <h2 class="mdl-card__title-text">Customer Details</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <?php if ($selectedCustomer === null) : ?>
                                <p class="empty-message">Select a customer to view details.</p>
                            <?php else : ?>
                                <h3 class="customer-name"><?php echo h(customerFullName($selectedCustomer)); ?></h3>
                                <p class="customer-meta"><strong>Email:</strong> <?php echo h($selectedCustomer['email']); ?></p>
                                <p class="customer-meta"><strong>University:</strong> <?php echo h($selectedCustomer['university']); ?></p>
                                <p class="customer-meta"><strong>Address:</strong> <?php echo h(customerAddress($selectedCustomer)); ?></p>
                                <p class="customer-meta"><strong>Phone:</strong> <?php echo h($selectedCustomer['phone']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="orders-card card-lesson mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                            <h2 class="mdl-card__title-text">Order Details</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <table class="mdl-data-table mdl-shadow--2dp orders-table">
                                <thead>
                                    <tr>
                                        <th class="mdl-data-table__cell--non-numeric">Cover</th>
                                        <th class="mdl-data-table__cell--non-numeric">ISBN</th>
                                        <th class="mdl-data-table__cell--non-numeric">Title</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($selectedCustomer !== null && empty($orders)) : ?>
                                        <tr class="empty-order">
                                            <td class="mdl-data-table__cell--non-numeric" colspan="3">No orders for this customer.</td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php foreach ($orders as $order) : ?>
                                        <tr>
                                            <td class="mdl-data-table__cell--non-numeric">
                                                <img src="<?php echo h($order['cover']); ?>" alt="<?php echo h($order['title']); ?> cover">
                                            </td>
                                            <td class="mdl-data-table__cell--non-numeric"><?php echo h($order['isbn']); ?></td>
                                            <td class="mdl-data-table__cell--non-numeric"><?php echo h($order['title']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="exam-footer">
                CISC3003 Web Programming: <?php echo h($studentId); ?> <?php echo h($studentName); ?> 2026
            </footer>
        </section>
    </main>
</div>

<script>
    $(function () {
        $('.inlinesparkline').sparkline('html', {
            type: 'bar',
            barColor: '#3f63cf',
            barWidth: 4,
            barSpacing: 1,
            height: '26px',
            chartRangeMin: 0
        });
    });
</script>
</body>
</html>
