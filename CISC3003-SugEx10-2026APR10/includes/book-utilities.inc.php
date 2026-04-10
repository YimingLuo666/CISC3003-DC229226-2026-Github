<?php

function normalizeText($value)
{
    $value = trim((string) $value);

    if ($value === '') {
        return '';
    }

    if (preg_match('//u', $value)) {
        return $value;
    }

    $windowsByteMap = [
        "\x8A" => 'Š',
        "\x8C" => 'Ś',
        "\x8D" => 'Ť',
        "\x8E" => 'Ž',
        "\x9A" => 'š',
        "\x9C" => 'ś',
        "\x9D" => 'ť',
        "\x9E" => 'ž',
        "\x9F" => 'Ÿ',
    ];

    $value = strtr($value, $windowsByteMap);

    if (preg_match('//u', $value)) {
        return $value;
    }

    if (function_exists('iconv')) {
        $encodings = ['Windows-1250', 'ISO-8859-2', 'Windows-1252', 'ISO-8859-1'];

        foreach ($encodings as $encoding) {
            $converted = @iconv($encoding, 'UTF-8//IGNORE', $value);

            if ($converted !== false && $converted !== '') {
                return $converted;
            }
        }
    }

    return $value;
}

function readCustomers($filename)
{
    $customers = [];

    if (!is_readable($filename)) {
        return $customers;
    }

    $handle = fopen($filename, 'r');

    if ($handle === false) {
        return $customers;
    }

    while (($line = fgets($handle)) !== false) {
        $line = trim($line);

        if ($line === '') {
            continue;
        }

        $fields = array_map('normalizeText', explode(';', $line));

        if (count($fields) < 12) {
            continue;
        }

        $customers[] = [
            'id' => (int) $fields[0],
            'first_name' => $fields[1],
            'last_name' => $fields[2],
            'email' => $fields[3],
            'university' => $fields[4],
            'address' => $fields[5],
            'city' => $fields[6],
            'state' => $fields[7],
            'country' => $fields[8],
            'postal' => $fields[9],
            'phone' => $fields[10],
            'sales' => preg_replace('/\s+/', '', $fields[11]),
        ];
    }

    fclose($handle);

    return $customers;
}

function readOrders($customer, $filename)
{
    $orders = [];
    $customerId = is_array($customer) ? ($customer['id'] ?? null) : $customer;

    if ($customerId === null || !is_readable($filename)) {
        return $orders;
    }

    $handle = fopen($filename, 'r');

    if ($handle === false) {
        return $orders;
    }

    while (($line = fgets($handle)) !== false) {
        $line = trim($line);

        if ($line === '') {
            continue;
        }

        $fields = str_getcsv($line, ',', '"', '\\');

        if (count($fields) < 5) {
            continue;
        }

        $orderCustomerId = (int) trim($fields[1]);

        if ($orderCustomerId !== (int) $customerId) {
            continue;
        }

        $isbn = normalizeText($fields[2]);
        $category = normalizeText($fields[count($fields) - 1]);
        $title = normalizeText(implode(',', array_slice($fields, 3, -1)));
        $coverFile = __DIR__ . '/../images/tinysquare/' . $isbn . '.jpg';

        $orders[] = [
            'order_id' => (int) trim($fields[0]),
            'customer_id' => $orderCustomerId,
            'isbn' => $isbn,
            'title' => $title,
            'category' => $category,
            'cover' => 'images/tinysquare/' . (file_exists($coverFile) ? $isbn : 'missing') . '.jpg',
        ];
    }

    fclose($handle);

    return $orders;
}

?>
