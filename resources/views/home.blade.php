@extends('layouts.app')
@php
function rupiah($angka)
{
    $hasil_rupiah = 'Rp' . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}
@endphp

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        @if(Auth::user()->role == 'siswa')
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">Welcome, {{ Auth::user()->name }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col d-flex justify-content-start align-items-center">
                            Saldo: {{ rupiah($saldo) }}
                        </div>
                        <div class="col d-flex justify-content-end align-items-center">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formTopUp">
                                Top Up
                            </button>

                            <form method="POST" action="{{ route('topupNow') }}">
                            @csrf
                            <div class="modal fade" id="formTopUp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-tile fs-5" id="exampleModalLabel">Insert Nominal Topup</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb3">
                                                <input type="number" min="10000" class="form-control" name="credit">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary" id="">Top Up Now</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                       
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Katalog Produk</div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($products as $key => $product)
                            <div class="col">
                                <form method="POST" action="{{ route('addToCart') }}">
                                    @csrf
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="user_id">
                                    <input type="hidden" value="{{ $product->id }}" name="product_id">
                                    <input type="hidden" value="{{ $product->price }}" name="price">
                                    <div class="card">
                                        <div class="card-header">
                                            {{ $product->name }}
                                        </div>
                                        <div class="card-body">
                                            <img src="{{ $product->photo }}">

                                            <div>{{ $product->description }}</div>

                                            <div>Rp{{ $product->price }}</div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="mb-3">
                                                <input class="form-control" type="number" name="quantity" value="0" min="0">
                                            </div>
                                            <div class="d-grip gap-2">
                                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    Cart
                </div>
                <div class="card-body">
                    <ul>
                        @foreach ($carts as $key => $cart)
                            <li>{{ $cart->product->name }} | {{ $cart->quantity }} * {{ $cart->price }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer">
                    Total: {{ $total_biaya }}
                    <form action="{{ route('payNow') }}" method="POST">
                        <div class="d-grip gap-2">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                Pay now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    Transaction History
                </div>
                <div class="card-body">
                    <ul>
                        @foreach ($transactions as $key => $transaction)
                        <div class="row">
                            <div class="col d-flex justofy-content-start align-items-center">
                                <div class="row">
                                    <div class="col">
                                        {{ $transaction[0]->order_id }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        {{ $transaction[0]->created_at }}
                                    </div>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-end align-items-center">
                            <a href="{{ route('download', ['order_id' => $transaction[0]->order_id]) }}" class="btn btn-success" target="_blank">
                                Download
                            </a>
                            </div>
                        </div>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    Mutation History
                </div>
                <div class="card-body">
                    <ul>
                        @foreach ($mutasi as $data)
                            <li>
                                {{ $data->credit ? $data->credit : 'Debit' }} | {{ $data->debit ? $data->debit : 'Kredit' }} |
                                {{ $data->description }} {{ $data->status == 'proses' ? '| proses' : ''}}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        @if (Auth::user()->role == 'bank') 
            <div class="row">
                <div class="col">
                    <div class="card bg-white shadow-sm border-0 mb-4">
                        <div class="card-header border-0">
                            Balance
                        </div>
                        <div class="card-body d-flex justify-content-between">
                            <h4 class="bi bi-credit-card"></h4>
                            <h4 class="card-text"> 
                                {{ rupiah($saldo) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card bg-white shadow-sm border-0 mb-4">
                        <div class="card-header border-0">
                            Customer Bank
                        </div>
                        <div class="card-body  d-flex justify-content-between">
                            <h4 class="bi bi-person"></h4>
                            <h4 class="card-text"> {{ ($nasabah) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card bg-white shadow-sm border-0 mb-4">
                        <div class="card-header border-0">
                            Transaction
                        </div>
                        <div class="card-body  d-flex justify-content-between">
                            <h4 class="bi bi-folder2"></h4>
                            <h4 class="card-text"> {{ ($transactions) }}</h4> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="card bg-white shadow-sm border-0 mb-4">
                        <div class="card-header border-0">
                            Request Top Up Customer
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    @foreach ($request_topup as $request )
                                            <form action="{{ route('acceptRequest') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="wallet_id" value="{{ $request->id }}"> 
                                                <div class="card bg-white shadow-sm border-0 mb-3">
                                                    <div class="card-header border-0">
                                                        {{ $request->user->name }}
                                                    </div>
                                                    <div class="card-body d-flex justify-content-between">
                                                        <div class="col my-auto">
                                                            Nominal : {{ rupiah($request->credit) }}
                                                        </div>
                                                        <div class="col text-end">
                                                            <button type="submit" class="btn btn-primary">Accept Request</button>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </form>           
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        @endif
        </div>
    </div>
</div>
@endsection
