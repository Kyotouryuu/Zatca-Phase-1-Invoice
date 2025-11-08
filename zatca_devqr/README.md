ZATCA Phase 1 Dev QR Invoice (Isolated)
=======================================

This folder contains a minimal, self-contained set of files to render a ZATCA Phase 1 compliant QR for a simple developer invoice page and a QR preview page, mirroring the feature available at `/dev/qr` (alias for `/dev/zatca-qr`) in this project.

Included
--------
- Routes:
  - `routes/zatca_devqr.php` (closure routes for:
    - `/dev/zatca-qr` and alias `/dev/qr`
    - `/dev/zatca-qr-2` and alias `/dev/qr2`
  )
- Views:
  - `resources/views/zatca/dev_invoice.blade.php`
  - `resources/views/zatca/qr_preview.blade.php`
- Services (TLV + Base64 encoder for ZATCA QR):
  - `app/Services/Zatca/Tag.php`
  - `app/Services/Zatca/Helpers/QRCodeGenerator.php`

How to integrate into another Laravel project
---------------------------------------------
1) Copy files to the same relative paths:
   - Copy `app/Services/Zatca/Tag.php` to your app at `app/Services/Zatca/Tag.php`
   - Copy `app/Services/Zatca/Helpers/QRCodeGenerator.php` to `app/Services/Zatca/Helpers/QRCodeGenerator.php`
   - Copy the Blade views to `resources/views/zatca/`:
     - `resources/views/zatca/dev_invoice.blade.php`
     - `resources/views/zatca/qr_preview.blade.php`
   - Add the routes from `routes/zatca_devqr.php` into your `routes/web.php` (or require this file from there).

2) Dependencies:
   - Barcode rendering (DNS2D):
     - Install `milon/barcode` if not present:
       - `composer require milon/barcode`
     - In Blade we call: `DNS2D::getBarcodePNG($b64, 'QRCODE', 8, 8, [0,0,0])`
   - Carbon for timestamps:
     - Laravel includes Carbon by default; if needed: `composer require nesbot/carbon`

3) Try it:
   - Visit `/dev/qr` → redirects to `/dev/zatca-qr` with a default invoice that includes a ZATCA Phase 1 QR.
   - Optional: override values via query params on `/dev/zatca-qr`:
     - `seller`, `vat`, `vat_amount` (or `vat_total`), `grand_total` (or `total`), `subtotal`, `ts`
   - For a second sample invoice, use `/dev/qr2` → `/dev/zatca-qr-2`.

Notes
-----
- The QR payload is the Base64 of TLV-encoded tags per ZATCA Phase 1:
  1) Seller name
  2) VAT number
  3) Timestamp (ISO8601)
  4) Total including VAT
  5) VAT amount
- The examples here don’t require any ZATCA Phase 2/UBL signing pieces; they only demonstrate Phase 1 QR generation and display.


