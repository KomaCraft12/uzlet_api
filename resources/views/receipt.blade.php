<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .total-row {
            font-weight: bold;
            font-size: 1.2em;
        }

        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .footer {
            margin-top: 20px;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Store Name</h2>
        <p>Cím: Példa utca 1, Budapest<br>Adószám: 12345678-1-23</p>
        <hr>
    </div>

    <table>
        <thead>
            <tr>
                <th>Termék</th>
                <th>Mennyiség</th>
                <th>Egységár</th>
                <th>Akciós ár</th>
                <th>Ár</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['quantity'] }}</td>
                <td>{{ number_format($product['unit_price'], 0, ',', ' ') }} HUF</td>
                <td>
                    @if (isset($product['discount_price']))
                        {{ number_format($product['discount_price'], 0, ',', ' ') }} HUF
                    @else
                        -
                    @endif
                </td>
                <td>{{ number_format($product['total_price'], 0, ',', ' ') }} HUF</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4">Kupon:</td>
                <td>{{ $coupon['code'] }} -  {{ $coupon['discount_type'] ? $coupon['discount_value'] . ' %' : $coupon['discount_value'] . ' HUF' }} 
                    ( {{ $save }} HUF )</td>
            </tr>
            <tr class="total-row">
                <td colspan="4">Végösszeg:</td>
                <td>{{ number_format($total_price, 0, ',', ' ') }} HUF</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Köszönjük a vásárlást!</p>
        <p>Dátum: {{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}</p>
    </div>
</body>

</html>
