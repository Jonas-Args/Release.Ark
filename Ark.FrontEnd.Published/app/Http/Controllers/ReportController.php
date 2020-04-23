<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Seller;
use App\User;

class ReportController extends Controller
{
    public function stock_report(Request $request)
    {
        if ($request->has('category_id')) {
            $products = Product::where('category_id', $request->category_id)->get();
        }
        else {
            $products = Product::all();
        }
        return view('reports.stock_report', compact('products'));
    }

    public function in_house_sale_report(Request $request)
    {
        if ($request->has('category_id')) {
            $products = Product::where('category_id', $request->category_id)->orderBy('num_of_sale', 'desc')->get();
        }
        else {
            $products = Product::orderBy('num_of_sale', 'desc')->get();
        }
        return view('reports.in_house_sale_report', compact('products'));
    }

    public function seller_report(Request $request)
    {
        if ($request->has('verification_status')) {
            $sellers = Seller::where('verification_status', $request->verification_status)->get();
        }
        else {
            $sellers = Seller::all();
        }
        return view('reports.seller_report', compact('sellers'));
    }

    public function seller_sale_report(Request $request)
    {
        if ($request->has('verification_status')) {
            $sellers = Seller::where('verification_status', $request->verification_status)->get();
        }
        else {
            $sellers = Seller::all();
        }
        return view('reports.seller_sale_report', compact('sellers'));
    }

    public function mem_sale_report(Request $request)
    {
        if ($request->has('verification_status')) {
            $sellers = Seller::where('verification_status', $request->verification_status)->get();
        }
        else {
            $sellers = Seller::all();
        }
        return view('reports.package_sales_report');
    }
    public function mambership_sale_report()
    {
        $url = 'http://localhost:55006/api/AdminAccess/GetBusinessPackageSales';
        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => "Content-type: application/json"
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $_r = json_decode($result);

        return $_r->getBusinessPackageSales;
    }

    public function mambership_list_report()
    {
        $url = 'http://localhost:55006/api/AdminAccess/GetUserBusinessPackageSales';
        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => "Content-type: application/json"
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $_r = json_decode($result);

        return $_r->getUserBusinessPackageSales;
    }
    public function wish_report(Request $request)
    {
        if ($request->has('category_id')) {
            $products = Product::where('category_id', $request->category_id)->get();
        }
        else {
            $products = Product::all();
        }
        return view('reports.wish_report', compact('products'));
    }
}
