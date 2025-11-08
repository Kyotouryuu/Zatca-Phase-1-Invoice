<?php

use Illuminate\Support\Facades\Route;

// Public temporary Phase 1 QR preview (no auth)
Route::get('/dev/zatca-qr', function (\Illuminate\Http\Request $request) {
    $seller = (string) ($request->query('seller') ?? 'السادة جامعة حائل');
    $vat = (string) ($request->query('vat') ?? '301035002700003');
    // Corrected amounts
    // New invoice defaults
    $vatAmount = (string) ($request->query('vat_amount') ?? $request->query('vat_total') ?? '12939.00');
    $grandTotal = (string) ($request->query('grand_total') ?? $request->query('total') ?? '99199.00');
    $subtotal = (string) ($request->query('subtotal') ?? (string) ((float)$grandTotal - (float)$vatAmount));
    // Normalize to 2 decimal digits for consistency
    $subtotal = number_format((float) $subtotal, 2, '.', '');
    $vatAmount = number_format((float) $vatAmount, 2, '.', '');
    $grandTotal = number_format((float) $grandTotal, 2, '.', '');
    $timestamp = (string) ($request->query('ts') ?? \Carbon\Carbon::now('UTC')->toIso8601String());

    $tags = [
        new \App\Services\Zatca\Tag(1, $seller),
        new \App\Services\Zatca\Tag(2, $vat),
        new \App\Services\Zatca\Tag(3, $timestamp),
        // ZATCA Phase 1 requires total including VAT, and VAT amount
        new \App\Services\Zatca\Tag(4, $grandTotal),
        new \App\Services\Zatca\Tag(5, $vatAmount),
    ];

    $b64 = \App\Services\Zatca\Helpers\QRCodeGenerator::createFromTags($tags)->encodeBase64();

    $items = [
        ['name' => 'تأجير شاشات الكترونية حديثة جديدة لمساحة 100متر موزعة 9 أجزاء مع تصميم الإطار لكل شاشة والتمديدات والبرمجة والاشراف احتياج معرض كلية الهندسة بجامعة حائل', 'quantity' => 9],
    ];

    $lineDefaults = [
        'sub_sku' => '',
        'brand' => '',
        'cat_code' => '',
        'product_custom_fields' => '',
        'product_description' => '',
        'sell_line_note' => '',
        'lot_number_label' => '',
        'lot_number' => '',
        'product_expiry' => '',
        'product_expiry_label' => '',
        'variation' => '',
        'product_variation' => '',
        'warranty_name' => '',
        'warranty_exp_date' => '',
        'warranty_description' => '',
        'base_unit_multiplier' => '',
        'units' => '',
        'base_unit_name' => '',
        'orig_quantity' => '',
        'base_unit_price' => '0.00',
        'unit_price_before_discount' => '0.00',
        'total_line_discount' => '0.00',
        'line_total' => '0.00',
        'modifiers' => [],
    ];

    $lines = array_map(function ($it) use ($lineDefaults) {
        return array_merge($lineDefaults, [
            'name' => $it['name'],
            'quantity' => $it['quantity'],
            'orig_quantity' => $it['quantity'],
        ]);
    }, $items);

    $receipt_details = (object) [
        'invoice_no' => \Carbon\Carbon::now('UTC')->format('YmdHis'),
        'invoice_no_prefix' => 'رقم الفاتورة',
        'invoice_date' => \Carbon\Carbon::now('UTC')->format('Y-m-d H:i'),
        'display_name' => $seller,
        'address' => null,
        'contact' => null,
        'website' => null,
        'location_custom_fields' => null,
        'tax_label1' => 'الرقم الضريبي',
        'tax_info1' => $vat,
        'header_text' => null,
        'letter_head' => null,
        'sales_person_label' => null,
        'sales_person' => null,
        'commission_agent_label' => null,
        'commission_agent' => null,
        'customer_info' => null,
        'client_id_label' => null,
        'client_id' => null,
        'customer_tax_label' => null,
        'customer_tax_number' => null,
        'customer_custom_fields' => null,
        'total_quantity_label' => null,
        'total_quantity' => null,
        'total_items_label' => null,
        'total_items' => null,
        'hide_price' => true,
        'show_base_unit_details' => false,
        'show_barcode' => false,
        'show_qr_code' => true,
        'qr_code_text' => $b64,
        'additional_notes' => null,
        'lines' => $lines,
        'taxes' => null,
        'subtotal_exc_tax' => $subtotal,
        'total' => $grandTotal,
        'payments' => null,
        'total_paid' => null,
        'total_due_label' => null,
        'total_due' => null,
        'all_bal_label' => null,
        'all_due' => null,
    ];

    // Render a simple dev invoice (avoids dependency on full slim2 internals)
    return view('zatca.dev_invoice', [
        'seller' => $seller,
        'vat' => $vat,
        'subtotal' => $subtotal,
        'vat_amount' => $vatAmount,
        'grand_total' => $grandTotal,
        'items' => $items,
        'qr_b64' => $b64,
        'invoice_no' => $receipt_details->invoice_no,
        'invoice_date' => $receipt_details->invoice_date,
        'notes' => null,
    ]);
})->name('zatca.qr.preview');

// Alias for convenience
Route::get('/dev/qr', function () {
    return redirect()->route('zatca.qr.preview');
});

// Public temporary Phase 1 QR preview (invoice 2)
Route::get('/dev/zatca-qr-2', function (\Illuminate\Http\Request $request) {
    $seller = (string) ($request->query('seller') ?? 'السادة جامعة حائل');
    $vat = (string) ($request->query('vat') ?? '301035002700003');
    $timestamp = (string) ($request->query('ts') ?? \Carbon\Carbon::now('UTC')->toIso8601String());

    // Second invoice defaults
    $vatAmount = (string) ($request->query('vat_amount') ?? '4500');
    $grandTotal = (string) ($request->query('grand_total') ?? '34500');
    $subtotal = (string) ($request->query('subtotal') ?? (string) ((float)$grandTotal - (float)$vatAmount));

    // Normalize to 2 decimals
    $subtotal = number_format((float) $subtotal, 2, '.', '');
    $vatAmount = number_format((float) $vatAmount, 2, '.', '');
    $grandTotal = number_format((float) $grandTotal, 2, '.', '');

    $tags = [
        new \App\Services\Zatca\Tag(1, $seller),
        new \App\Services\Zatca\Tag(2, $vat),
        new \App\Services\Zatca\Tag(3, $timestamp),
        new \App\Services\Zatca\Tag(4, $grandTotal),
        new \App\Services\Zatca\Tag(5, $vatAmount),
    ];
    $b64 = \App\Services\Zatca\Helpers\QRCodeGenerator::createFromTags($tags)->encodeBase64();

    $items = [
        ['name' => 'كراسي انتظار', 'quantity' => 15],
        ['name' => 'مكتب خاص صغير ذو جودة عالية', 'quantity' => 2],
        ['name' => 'كراسي محطات عمل', 'quantity' => 24],
    ];

    return view('zatca.dev_invoice', [
        'seller' => $seller,
        'vat' => $vat,
        'subtotal' => $subtotal,
        'vat_amount' => $vatAmount,
        'grand_total' => $grandTotal,
        'items' => $items,
        'qr_b64' => $b64,
        'invoice_no' => \Carbon\Carbon::now('UTC')->format('YmdHis'),
        'invoice_date' => \Carbon\Carbon::now('UTC')->format('Y-m-d H:i'),
        'notes' => null,
    ]);
})->name('zatca.qr.preview2');

// Alias for the second invoice
Route::get('/dev/qr2', function () {
    return redirect()->route('zatca.qr.preview2');
});


