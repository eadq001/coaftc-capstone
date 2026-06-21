@use('App\QrGenerator')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product QR Codes — Page {{ $page }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 8px;
            background: #fff;
            color: #18181b;
        }
        .header { text-align: center; margin-bottom: 24px; }
        .header h1 { font-size: 10px; margin: 0 0 4px; }
        .header p { font-size: 10px; color: #71717a; margin: 0; }
        .grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 16px;
        }
        @media (max-width: 900px) { .grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 520px) { .grid { grid-template-columns: 1fr; } }
        .card {
            border: 1px solid #e4e4e7;
            border-radius: 12px;
            background: #fafafa;
            padding: 2px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            break-inside: avoid;
            font-size: 8px !important;
        }
        .qr {
            background: #fff;
            padding: 12px;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
        }
        .qr img { display: block; width: 100px; height: 100px; }
        .details { width: 100%; border-top: 1px dashed #e4e4e7; padding-top: 8px; }
        .id { font-size: 0.75rem; font-weight: 600; color: #52525b; margin: 0; }
        .name { font-size: 10px; font-weight: 600; color: #18181b; margin: 2px 0; }
        .meta { font-size: 10px; color: #71717a; margin: 1px 0; }
        .empty { text-align: center; color: #71717a; padding: 40px 0; }
        @media print {
            body { padding: 0; }
            .grid { grid-template-columns: repeat(4, 1fr); }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Product QR Codes</h1>
        <p>Page {{ $page }} &middot; {{ count($products) }} item(s) &middot; Generated {{ $generatedAt }}</p>
    </div>

    @if(count($products) === 0)
        <p class="empty">No products on this page.</p>
    @else
        <div class="grid">
            @foreach($products as $product)
                <div class="card">
                    <div class="qr">
                        <img alt="QR code for {{ $product->name }}"
                             src="data:image/png;base64,{{ QrGenerator::generate((string) $product->id, 80) }}">
                    </div>
                    <div class="details">
                        <p class="id">{{ $product->id }}</p>
                        <p class="name">{{ $product->name }}</p>
                        @if($product->category?->category_name)
                            <p class="meta">{{ $product->category->category_name }}</p>
                        @endif
                        @if($product->class)
                            <p class="meta">Class: {{ $product->class }}</p>
                        @endif
                        @if($product->size)
                            <p class="meta">{{ $product->size }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>
