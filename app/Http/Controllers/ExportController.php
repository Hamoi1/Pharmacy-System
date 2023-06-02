<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\User;
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
        if ($Table == 'users') {
            $datas = self::ExportUser($Columns, $quantity);
            $Columns = $userData;
        } elseif ($Table == 'products') {
            $datas = self::ExportProduct($Columns, $quantity);
            $Columns = $productData;
        }
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
        $haveAddress = false;
        if (in_array('address', $Columns)) {
            $Columns = array_diff($Columns, array('address'));
            $haveAddress = true;
        }
        $Columns = implode(',', $Columns);
        $users = User::select('id', DB::raw($Columns))->with('user_details');
        if ($quantity != null) {
            $users = $users->limit($quantity)->get();
        } else {
            $users = $users->get();
        }
        // get address and give a users
        if ($haveAddress) {
            foreach ($users as $user) {
                $user->address = $user->user_details->address ?? '';
            }
        }
        // remove id
        $users = $users->map(function ($user) {
            unset($user->id);
            unset($user->user_details);
            $name = $user->name;
            $username = $user->username;
            $phone = $user->phone;
            $email = $user->email;
            $address = $user->address;
            $created_at = $user->created_at ? $user->created_at->format('Y-m-d') : '';
            $updated_at = $user->updated_at ? $user->updated_at->format('Y-m-d') : '';
            $deleted_at = $user->deleted_at ? $user->deleted_at->format('Y-m-d') : '';
            return compact('name', 'username', 'phone', 'email', 'address', 'created_at', 'updated_at', 'deleted_at');
        });
        return $users->toArray();
    }

    private static function ExportProduct($Columns, $quantity)
    {
        $products = [];
        $haveCategory = false;
        if (in_array('category_id', $Columns)) {
            $Columns = array_diff($Columns, array('category'));
            $haveCategory = true;
        }
        $havesupplier = false;
        if (in_array('supplier_id', $Columns)) {
            $Columns = array_diff($Columns, array('supplier'));
            $havesupplier = true;
        }
        $Columns = implode(',', $Columns);
        $products = Products::select('id', DB::raw($Columns))->with('category', 'supplier');
        if ($quantity != null) {
            $products =  $products->limit($quantity)->get();
        } else {
            $products = $products->get();
        }
        foreach ($products as $product) {
            if ($haveCategory) {
                $product->category = $product->category->name;
            } else {
                $product->category = '';
            }
            if ($havesupplier) {
                $product->supplier = $product->supplier->name;
            } else {
                $product->supplier = '';
            }
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
            $expiry_date = $product->expiry_date  ? $product->expiry_date : '';
            $description = $product->description;
            $created_at = $product->created_at  ? $product->created_at : '';
            $updated_at = $product->updated_at  ? $product->updated_at : '';
            $deleted_at = $product->deleted_at  ? $product->deleted_at : '';
            return compact('name', 'barcode', 'purches_price', 'sale_price', 'category', 'supplier', 'quantity', 'expiry_date', 'description', 'created_at', 'updated_at', 'deleted_at');
        });
        return $products->toArray();
    }
}
