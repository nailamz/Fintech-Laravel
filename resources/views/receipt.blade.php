<!-- @php
    function rupiah($angka){
        $hasil_rupiah = "Rp" . number_format($angka,0,',','.');
        return $hasil_rupiah;
    }
@endphp -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>e-Receipt #{{ $transactions[0]->order_id }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>
<body>
    <div id="app">
        <main class="py-4">
            <div class="card">
                <div class="card-header px-5 text-center fw-bold">
                    <h3 style="font-size: 50px">e-Receipt #{{ $transactions[0]->order_id }}</h3>
                    <span class="text-secondary" style="font-size: 30px">#{{ $transactions[0]->created_at }}</span>
                </div>
                <div class="card-body px-5">
                    @foreach ($transactions as $transaction)
                    <div class="row" style="font-size: 30px">
                        <div class="col">
                            {{ $transaction->product->name }}
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    {{ $transaction->quantity }}x
                                </div>
                                <div class="col text-end">
                                    {{ rupiah($transaction->price * $transaction->quantity) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="card-footer px-5">
                    <div style="font-size: 35px">
                        <div class="row fw-bold">   
                            <div class="col">
                                Total:
                            </div>
                            <div class="col text-end">
                                {{ rupiah($total_biaya) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        window.print();
    </script>
</body>
</html>