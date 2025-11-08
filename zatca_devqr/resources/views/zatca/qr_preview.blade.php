<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZATCA Phase 1 QR Preview</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; padding: 24px; }
        .wrap { max-width: 720px; margin: 0 auto; }
        .card { border: 1px solid #e0e0e0; border-radius: 8px; padding: 16px; }
        .row { display: flex; gap: 16px; align-items: center; }
        .qr { width: 240px; height: 240px; display: flex; align-items: center; justify-content: center; }
        .meta { flex: 1; word-break: break-all; }
        .mono { font-family: Consolas, Monaco, monospace; font-size: 12px; background: #f7f7f7; padding: 8px; border-radius: 4px; }
        .hint { color: #666; font-size: 12px; margin-top: 8px; }
        .input { width: 100%; height: 88px; box-sizing: border-box; }
        .small { font-size: 12px; color: #444; }
    </style>
    <meta name="robots" content="noindex,nofollow">
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
</head>
<body>
<div class="wrap">
    <h2>ZATCA Phase 1 QR Preview</h2>
    <div class="card">
        <div class="row">
            <div class="qr">
                <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($b64, 'QRCODE', 8, 8, [0,0,0]) }}" alt="ZATCA QR" width="240" height="240" />
            </div>
            <div class="meta">
                <div class="small"><strong>QR encodes Base64 (TLV) payload below:</strong></div>
                <div class="mono">{{ $b64 }}</div>
                <div class="hint">You can override via query param: <code>?b64=...</code></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>






