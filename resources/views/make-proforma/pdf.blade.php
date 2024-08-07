<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quotation</title>
    <style>
        body {
            font-size: 12px; /* Standard font size for the document */
            font-family: 'DejaVu Sans', sans-serif; /* Standard font family */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .fw-bolder {
            font-size: 24px;
            font-weight: bolder;
        }
        .fw-bold {
            font-size: 16px;
            font-weight: bold;
        }
        .table-header {
            background-color: rgb(225, 225, 225);
            border-top: 1px solid;
            border-bottom: 1px solid;
        }
        .table-header th {
            border-top: 1px solid;
            border-bottom: 1px solid;
        }
        .table-body {
            border-bottom: 1px dotted;
        }
        .table-footer .bordered {
            background-color: lightgrey;
            border-top: 1px solid;
            border-bottom: 3px double;
        }
        .table-footer h3 {
            margin: 0;
            padding: 0;
        }
        .text-center {
            text-align: center;
        }
        .text-start {
            text-align: start;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <table>
        <tbody>
            <tr>
                <td style="width: 25%;">
                    <img style="max-height: 200px;" src="{{ public_path('storage/' . $user->business->logo) }}" alt="Business Logo" srcset="">
                </td>
                <td style="width: 50%; text-align:center; font-family: DejaVu Sans, sans-serif;">
                    <span class="fw-bolder">{{ $user->business->business_name }}</span>
                    <br>
                    <span>{{ $user->business->contact_name }}</span>
                    <br>
                    <span>{{ $user->business->address_1 }}</span>
                    <br>
                    <span>{{ $user->business->address_2 }} </span>
                    <br>
                    <span> &#x1F4F1; {{ $user->business->number }} ✉️ {{ $user->business->email }}</span>
                </td>
                <td style="width: 25%; text-align: right;">
                    <span class="fw-bolder">PROFORMA INVOICE</span>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <table>
        <tbody>
            <tr>
                <td style="width: 30%; font-family: DejaVu Sans, sans-serif;">
                    <span class="fw-bold">BILL To,
                        <br />
                        {{ $customer->company_name}}
                    </span>
                    <br />
                    <span>{{ $customer->name}} </span>
                    <br />
                    <span style="white-space: nowrap">✉️ {{ $customer->email}}</span>
                </td>
                <td style="width: 40%;">
                   
                </td>
                <td style="width: 30%; text-align: right; vertical-align: top;">
                    <span class="fw-bold">Proforma Invoice#</span>
                    &nbsp; &nbsp;
                    <span>Inv-7</span>
                    <br>
                    <span class="fw-bold">Date:</span>
                    {{ $date }}
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table-header">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 40%;" class="text-start">Description</th>
                <th style="width: 10%;" class="text-center">QTY</th>
                <th style="width: 15%;" class="text-center">PRICE</th>
                <th style="width: 15%;" class="text-right">TOTAL</th>
            </tr>
        </thead>
    </table>
    @php
        $subtotal = 0;
    @endphp
    @foreach($products as $product)
        <table class="table-body">
            <tbody>
                <tr>
                    <td style="width: 5%;" class="text-center">{{$loop->index + 1}}</td>
                    <td style="width: 40%;" class="text-start">
                        <span class="fw-bold">{{$product['product']['product_name']}}</span>
                        <br>
                        <span>{{$product['description']}}</span>
                    </td>
                    <td style="width: 10%;" class="text-center">
                        <span>{{$product['quantity']}}</span>
                        <br>
                        <span>{{$product['product']['unit']}}</span>
                    </td>
                    <td style="width: 15%;" class="text-center">&#8377;{{$product['price']}}</td>
                    @php
                        $subtotal += (int)$product['quantity'] * (int)$product['price'];
                    @endphp
                    <td style="width: 15%;" class="text-right">&#8377;{{ (int)$product['quantity'] * (int)$product['price'] }}</td>
                </tr>
            </tbody>
        </table>
    @endforeach
    <br>
    <table>
        <tbody>
            <tr>
                <td colspan="3" style="width: 50%;">&nbsp;</td>
                <td colspan="1" style="width: 10%;">&nbsp;</td>
                <td style="width: 15%; font-size: 12px;">SUB TOTAL</td>
                <td style="width: 15%;" class="text-right">&#8377;{{$subtotal}}</td>
            </tr>
            @if($charges)    
                <tr>
                    <td colspan="3" style="width: 50%;">&nbsp;</td>
                    <td colspan="1" style="width: 10%;">&nbsp;</td>
                    <td style="width: 15%; font-size: 12px; white-space: nowrap;">{{$charges['other_charge_label']}}</td>
                    <td style="width: 15%;" class="text-right">&#8377;{{$charges['other_charge_amount']}}</td>
                </tr>
                @if ($charges['is_taxable'])
                    <tr style="color: rgb(172, 171, 171)">
                        <td colspan="3" style="width: 50%;">&nbsp;</td>
                        <td colspan="1" style="width: 10%;">&nbsp;</td>
                        <td style="width: 15%; font-size: 12px;">({{$charges['gst_percentage']}}%)</td>
                        <td style="width: 15%;" class="text-right">&#8377;{{$charges['gst_amount']}}</td>
                    </tr>
                @endif
            @endif
        </tbody>
    </table>
    <table>
        <tbody>
            <tr>
                <td colspan="3" style="width: 50%;">
                </td>
                <td colspan="1" style="width: 10%;">&nbsp;</td>
                <td class="bordered fw-bold" style="width: 15%; font-size: 15px; white-space: nowrap;">TOTAL</td>
                <td style="width: 15%;" class="text-right bordered">&#8377;{{ $totalAmount + $paidAmount }} </td>
            </tr>
            <tr>
                <td colspan="3" style="width: 50%;">
                </td>
                <td colspan="1" style="width: 10%;">&nbsp;</td>
                <td class="bordered" style="width: 15%; font-size: 15px; white-space: nowrap;">Paid</td>
                <td style="width: 15%;" class="text-right bordered">&#8377;{{ $paidAmount }} </td>
            </tr>
        </tbody>
    </table>
    <table class="table-footer">
        <tbody>
            <tr>
                <td colspan="3" style="width: 50%; vertical-align: top;">
                    <h3> AMOUNT IN WORDS:</h3>
                    <span>{{ucwords($amountInWord)}} only/-</span>
                </td>
                <td colspan="1" style="width: 10%;">&nbsp;</td>
                <td class="bordered" style="width: 15%; font-size: 12px; white-space: nowrap;">Balance Due</td>
                <td style="width: 15%;" class="text-right bordered">&#8377;{{ $totalAmount }} </td>
            </tr>
        </tbody>
    </table>

    @if ($terms && count($terms) > 0)
        <h3>Terms & Conditions:</h3>
        <ul>
            @foreach ($terms as $term)
                <li>{{$term->terms}}</li>
            @endforeach
        </ul>
    @endif

    <table style="padding: 5px;">
        <tbody>
            <tr>
                <td style="width: 30%">&nbsp;</td>
                <td style="width: 30%">&nbsp;</td>
                <td style="width: 40%; text-align: right;">
                    <h3 style="white-space: nowrap;">For, {{ $user->business->business_name}}</h3>
                    <img style="max-height: 150px;" src="{{ public_path('storage/' . $user->business->signature)}}" alt="">
                    <br>
                    <span style="margein-top: 2px;font-size: 15px;">AUTHORIZED SIGNATURE</span>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
