<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Student;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;

class FirstSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- Seeder User
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'password' => Hash::make('admin'),   
            'role' => 'admin'         
        ]);
        User::create([
            'name' => 'Tenizen Bank',
            'username' => 'tenizen',
            'password' => Hash::make('bank'),
            'role' => 'bank'           
        ]);
        User::create([
            'name' => 'Tenizen Mart',
            'username' => 'kantin',
            'password' => Hash::make('kantin'),
            'role' => 'kantin'            
        ]);
        User::create([
            'name' => 'Naila',
            'username' => 'Naila',
            'password' => Hash::make('naila'),
            'role' => 'siswa'          
        ]);

        // --- Seeder Student
        Student::create([
            'user_id' => 4,
            'nis' => '12345',
            'classroom' => 'XII RPL'
        ]);

        // --- Seeder Category
        Category::create(['name' => 'Minuman']);
        Category::create(['name' => 'Makanan']);
        Category::create(['name' => 'Snack']);

        // --- Seeder Product
        Product::create([
            'name' => 'Lemon Ice Tea',	
            'price'	=> 5000,
            'stock'	=> 80,
            'photo'	=> 'images/lemontea.jpeg',
            'description'	=> 'Lemon Ice Tea',
            'category_id' => 1,
            'stand' => 2
        ]);

        Product::create([
            'name' => 'Churros',	
            'price'	=> 10000,
            'stock'	=> 50,
            'photo'	=> 'images/churros.jpeg',
            'description'	=> 'Churros',
            'category_id' => 2,
            'stand' => 1
        ]);
        
        Product::create([
            'name' => 'Pempek',	
            'price'	=> 3000,
            'stock'	=> 50,
            'photo'	=> 'images/pempek.jpeg',
            'description'	=> 'Pempek',
            'category_id' => 3,
            'stand' => 1
        ]);

        // --- Seeder Wallet
        Wallet::create([
            'user_id' => 4,
            'credit' => 100000,
            'description' => 'Top up saldo'
        ]);

        Wallet::create([
            'user_id' => 4,
            'credit' => 15000,
            'description' => 'Biaya pembukaan tabungan'
        ]);

        

        // --- Seeder Transaction
        Transaction::create([
            'user_id' => 4,
            'product_id' => 1,
            'status' => 'di keranjang',
            'order_id' => 'INV_12345',
            'price' => 5000,
            'quantity' => 1
        ]);

        Transaction::create([
            'user_id' => 4,
            'product_id' => 1,
            'status' => 'di keranjang',
            'order_id' => 'INV_12345',
            'price' => 10000,
            'quantity' => 1
        ]);

        Transaction::create([
            'user_id' => 4,
            'product_id' => 3,
            'status' => 'di keranjang',
            'order_id' => 'INV_12345',
            'price' => 3000,
            'quantity' => 2
        ]);

        $transactions = Transaction::where('order_id', 'INV_12345')->get();

        $total_debit = 0;

        foreach($transactions as $transaction){
            $total_price = $transaction->price * $transaction->quantity;

            $total_debit += $total_price;
        }

        Wallet::create([
            'user_id' => 4,
            'debit' => $total_debit,
            'description' => 'Pembelian Produk'
        ]);

        foreach($transactions as $transaction){
            Transaction::find($transaction->id)->update([
                'status' => 'dibayar'
            ]);
        }

        foreach($transactions as $transaction){
            Transaction::find($transaction->id)->update([
                'status' => 'diambil'
            ]);
        }
    }
}
