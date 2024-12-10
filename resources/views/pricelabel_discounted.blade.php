<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Price Label</title>
  <style>
    /*@import url('https://necolas.github.io/normalize.css/8.0.1/normalize.css');*/

    /* Alap beállítások */
    body {
      font-family: Arial, sans-serif;
      margin: 10;
      padding: 0;
    }

    /* Nyomtatásra optimalizált stílusok */
    .label {
      width: 440px;
      /*background-color: #FFCC00;*/
      background-color: white;
      border: 1px solid #000;
    }

    table {
      width: 100%;
      table-layout: fixed;
      border-collapse: collapse;
      padding: 0;
    }

    td {
      padding: 5px;
      vertical-align: top;
    }

    .large-price {
      font-size: 36px;
      font-weight: bold;
      color: #000;
    }

    .small-price {
      font-size: 14px;
      color: #000;
    }

    .product-name {
      font-size: 16px;
      font-weight: bold;
    }

    .product-description {
      font-size: 12px;
      color: #000;
    }

    .barcode {
      text-align: center;
    }

    .barcode div {
      font-size: 10pt;
      text-align: center;
    }

    .barcode img {
      width: 130%;
      height: auto;
    }

    .rotate {
      rotate: -90deg;
      -webkit-transform: rotate(-90deg);
      transform: rotate(-90deg);
      position: absolute;
      top: 47%;
      left: -24%;
      transform: translate(-50%, -50%);
    }

    /*.rotate {
      rotate: -90deg;
      -webkit-transform: rotate(-90deg);
      transform: rotate(-90deg);
      position: absolute;
      top: 0%;
      left: 5%;
      transform: translate(-55%, -40%);
    }*/
  </style>
</head>

<body>

  <div class="label">
    <table>
      <tr style="border-bottom: 1px solid #000; height: 160px">
        <td style="border-right: 1px solid #000; width: 20%; position: relative; ">
          <div class="rotate barcode">
            <img src="{{ $barcodeImage }}" alt="barcode">
            <div style="">
              <div style="margin-left: 12%">&nbsp;&nbsp;{{ $barcode }}</div>
            </div>
          </div>
        </td>
        <td style="width: 80%; position: relative">
          <!--<div style="position: absolute; bottom: 10px; right: 20px">
            <span style="font-size: 80px">{{ $price }}</span>
            <span> Ft</span>
          </div>-->
          <div style="position: absolute; top: 30px; right: 20px; text-decoration: line-through;">
            <span style="font-size: 20px;">{{ $price }}</span>
            <span> Ft</span>
          </div>
          <div style="position: absolute; bottom: 40px; right: 20px">
            <span style="font-size: 60px">
              {{ $discounted['price'] }}
            </span>
            <span> Ft</span>
          </div>
          <div style="position: absolute; bottom: 10px; left: 10px">
            <span style="font-size: 10px">
              Érvényesség: {{ $discounted["start"] }} - {{ $discounted["stop"] }}
            </span>
          </div>

        </td>
      </tr>
      <tr style="height: 50px">
        <td style="border-right: 1px solid #000; width: 20%; font-size: 10px">
          {{ $discounted["unit_price"] }} Ft / {{ $unit }}
        </td>
        <td style="width: 80%;">
          <div style="font-size: 12px; margin-bottom: 3px">{{ $product }}</div>
          <div style="font-size: 10px;">{{ $description }}</div>
        </td>
      </tr>
    </table>
  </div>

</body>

</html>