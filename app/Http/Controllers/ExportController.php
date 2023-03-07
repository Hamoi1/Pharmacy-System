<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public static function  export($Columns, $Table, $quantity = null)
    {
        $userData = [
            'Name',
            'Username',
            'Phone',
            'Email',
            'Address',
            'Created At',
            'Updated At',
            'Deleted At',
        ];
        $productData = [
            'name',
            'barcode',
            'purches_price',
            'sale_price',
            'category',
            'supplier',
            'quantity',
            'expiry_date',
            'description',
            'created_at',
            'updated_at',
        ];
        $datas = [];
        $Columns = implode(' , ', $Columns);
        if ($Table == 'users') {
            // get function ExportUser
            $datas = self::ExportUser($Columns, $quantity);
            $Columns = $userData;
        } elseif ($Table == 'products') {
            // get function ExportProduct
            $datas = self::ExportProduct($Columns, $quantity);
            $Columns = $productData;
        }
        // dd($datas);

        // get file name
        $file_name = $Table . '.csv';
        // cretae a herder of file data excel
        $file = fopen($file_name, 'w');
        fputcsv($file, $Columns);
        foreach ($datas as $data) {
            fputcsv($file, $data);
        }
        fclose($file);
        // download file
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return response()->download($file_name, $file_name, $headers);
    }
    private static function ExportUser($Columns, $quantity)
    {
        $users = [];
        // if column have address data remove address and , befor and after address
        if (strpos($Columns, 'address') !== false) {
            $Columns = str_replace(' , address', '', $Columns);
            $Columns = str_replace('address , ', '', $Columns);
        }
        if ($quantity != null) {
            $users = User::select('id', DB::raw($Columns))->with('user_details')->limit($quantity)->get();
        } else {
            $users = User::select('id', DB::raw($Columns))->with('user_details')->get();
        }
        // get address and give a users
        foreach ($users as $user) {
            $user->address = $user->user_details->address ?? '';
        }
        // remove id
        $users = $users->map(function ($user) {
            unset($user->id);
            unset($user->user_details);
            $name = $user->name;
            $username = $user->username;
            $email = $user->email;
            $phone = $user->phone;
            $address = $user->address;
            $created_at = $user->created_at ? $user->created_at->format('Y-m-d') : '';
            $updated_at = $user->updated_at ? $user->updated_at->format('Y-m-d') : '';
            $deleted_at = $user->deleted_at ? $user->deleted_at->format('Y-m-d') : '';
            return compact('name', 'username', 'email', 'phone', 'address', 'created_at', 'updated_at', 'deleted_at');
        });
        return $users->toArray();
    }

    private static function ExportProduct($Columns, $quantity)
    {
        $products = [];
        if ($quantity != null) {
            $products = Products::select('id', DB::raw($Columns))->with('category')->limit($quantity)->get();
        } else {
            $products = Products::select('id', DB::raw($Columns))->with('supplier')->get();
        }
        foreach ($products as $product) {
            $product->category = $product->category ? $product->category->category : '';
            $product->supplier = $product->supplier ? $product->supplier->name : '';
        }
        $products = $products->map(function ($product) {
            unset($product->id);
            unset($product->category_id);
            unset($product->supplier_id);
            $name = $product->name;
            $barcode = $product->barcode;
            $purches_price = $product->purches_price;
            $sale_price = $product->sale_price;
            $category = $product->category;
            $supplier = $product->supplier;
            $quantity = $product->quantity;
            $expiry_date = $product->expiry_date ? $product->expiry_date->format('Y-m-d') : '';
            $description = $product->description;
            $created_at = $product->created_at ? $product->created_at->format('Y-m-d') : '';
            $updated_at = $product->updated_at ? $product->updated_at->format('Y-m-d') : '';
            $deleted_at = $product->deleted_at ? $product->deleted_at->format('Y-m-d') : '';

            return compact('name', 'barcode', 'purches_price', 'sale_price', 'category', 'supplier', 'quantity', 'expiry_date', 'description', 'created_at', 'updated_at', 'deleted_at');
        });
        return $products->toArray();
    }
}
