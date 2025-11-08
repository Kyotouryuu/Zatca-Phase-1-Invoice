<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة - {{ $invoice_no }}</title>
    <style>
        body { font-family: Tahoma, Arial, Helvetica, sans-serif; padding: 24px; color: #000; }
        .wrap { max-width: 820px; margin: 0 auto; border: 1px solid #e5e5e5; padding: 16px; border-radius: 8px; }
        .header { border-bottom: 1px solid #ddd; padding-bottom: 12px; margin-bottom: 16px; }
        .title { font-size: 20px; font-weight: 700; margin-bottom: 6px; }
        .row { display: flex; justify-content: space-between; gap: 12px; align-items: flex-start; }
        .stack { display: flex; flex-direction: column; gap: 4px; }
        .muted { color: #666; font-size: 12px; }
        .label { color: #333; font-size: 13px; }
        .value { font-weight: 700; }
        .num { direction: ltr; unicode-bidi: embed; font-variant-numeric: tabular-nums; letter-spacing: .2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        caption.desc { caption-side: top; text-align: right; padding: 6px 0; font-size: 14px; color: #333; }
        th, td { border-bottom: 1px solid #eee; padding: 8px 6px; font-size: 14px; }
        th { text-align: right; background: #fafafa; }
        td.qty, th.qty { width: 120px; text-align: left; }
        .totals { margin-top: 12px; border-top: 1px solid #eee; padding-top: 8px; }
        .totals .row { margin: 4px 0; }
        .totals .row .label { font-weight: 600; }
        .qr { display: flex; justify-content: center; margin-top: 18px; }
        .notes { white-space: pre-wrap; margin-top: 10px; line-height: 1.7; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <div class="title">فاتورة ضريبية مبسطة</div>
        <div class="row">
            <div class="stack">
                <div class="label">رقم الفاتورة</div>
                <div class="value num">{{ $invoice_no }}</div>
            </div>
            <div class="stack">
                <div class="label">تاريخ الفاتورة</div>
                <div class="value num">{{ $invoice_date }}</div>
            </div>
            <div class="stack">
                <div class="label">البائع</div>
                <div class="value">{{ $seller }}</div>
            </div>
            <div class="stack">
                <div class="label">الرقم الضريبي</div>
                <div class="value num">{{ $vat }}</div>
            </div>
        </div>
    </div>

    <table>
        <caption class="desc">تفاصيل الأصناف</caption>
        <thead>
            <tr>
                <th>الصنف</th>
                <th class="qty">الكمية</th>
            </tr>
        </thead>
        <tbody>
        @foreach($items as $it)
            <tr>
                <td>{{ $it['name'] }}</td>
                <td class="qty num">{{ $it['quantity'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="row">
            <div class="label">المجموع قبل الضريبة</div>
            <div class="value num">{{ $subtotal }}</div>
        </div>
        <div class="row">
            <div class="label">الضريبة</div>
            <div class="value num">{{ $vat_amount }}</div>
        </div>
        <div class="row">
            <div class="label">الإجمالي شامل الضريبة</div>
            <div class="value num">{{ $grand_total }}</div>
        </div>
        @if(!empty($notes))
            <div class="notes">{{ $notes }}</div>
        @endif
    </div>

    <div class="qr">
        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($qr_b64, 'QRCODE', 8, 8, [0,0,0]) }}" width="240" height="240" alt="QR" />
    </div>
</div>
</body>
</html>


