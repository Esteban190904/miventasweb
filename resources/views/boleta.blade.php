<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mini Boleta de Compra</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f1f1f1; }
        tfoot td { font-weight: bold; }
    </style>
</head>
<body>
    <h2>Mini Boleta de Compra</h2>

    <p><strong>Cliente:</strong> {{ $nombre_cliente ?? 'Cliente' }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        @php $total = 0; @endphp
        @foreach ($carrito as $i => $item)
            @php
                $cantidad = $item->pivot->cantidad ?? 0;
                $precio = $item->precio ?? 0;
                $subtotal = $cantidad * $precio;
                $total += $subtotal;
            @endphp
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $item->nombre }}</td>
                <td>{{ $cantidad }}</td>
                <td>S/ {{ number_format($precio, 2) }}</td>
                <td>S/ {{ number_format($subtotal, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;">TOTAL</td>
                <td>S/ {{ number_format($total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <br><br>
    <p style="text-align: center;">¡Gracias por su compra!</p>
</body>
</html>
