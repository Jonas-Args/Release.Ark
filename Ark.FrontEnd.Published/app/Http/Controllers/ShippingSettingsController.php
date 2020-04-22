<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\ShippingFeeType;
use App\PackagingType;


class ShippingSettingsController extends Controller
{
    public function shipping_settings(Request $request)
    {
        return view('shipping.index');
    }

    public function shipping_fee_type(Request $request)
    {
        $ShippingFeeType = ShippingFeeType::with('packagingType')->get();

        return $ShippingFeeType->toArray();
    }

    public function packaging_type(Request $request)
    {
        $PackagingType = PackagingType::all();

        return $PackagingType->toArray();
    }

    public function shipping_fee_type_update(Request $request)
    {
        $ShippingFeeType = ShippingFeeType::where('id', '=', $request->id)->first();
        if ($ShippingFeeType != null) {
            $ShippingFeeType->range_from = $request->range_from;
            $ShippingFeeType->range_to = $request->range_to;
            $ShippingFeeType->packaging_type_id = $request->packaging_type_id;
            $ShippingFeeType->region = $request->region;
            $ShippingFeeType->save();
        }
        else {
            $ShippingFeeType = new ShippingFeeType;
            $ShippingFeeType->range_from = $request->range_from;
            $ShippingFeeType->range_to = $request->range_to;
            $ShippingFeeType->packaging_type_id = $request->packaging_type_id;
            $ShippingFeeType->region = $request->region;
            $ShippingFeeType->save();
        }

        return response("OK", 200)->header('Content-Type', 'text/plain');
    }

    public function packaging_type_update(Request $request)
    {
        $PackagingType = PackagingType::where('id', '=', $request->id)->first();
        if ($PackagingType != null) {
            $PackagingType->name = $request->name;
            $PackagingType->unit_price = $request->unit_price;
            $PackagingType->packaging_name = $request->packaging_name;
            $PackagingType->length = $request->length;
            $PackagingType->width = $request->width;
            $PackagingType->height = $request->height;
            $PackagingType->weight = $request->weight;
            $PackagingType->save();
        }
        else {
            $PackagingType = new PackagingType;
            $PackagingType->name = $request->name;
            $PackagingType->unit_price = $request->unit_price;
            $PackagingType->packaging_name = $request->packaging_name;
            $PackagingType->length = $request->length;
            $PackagingType->width = $request->width;
            $PackagingType->height = $request->height;
            $PackagingType->weight = $request->weight;
            $PackagingType->save();
        }

        return response("OK", 200)->header('Content-Type', 'text/plain');
    }

    public function shipping_fee_type_remove(Request $request)
    {
        ShippingFeeType::destroy($request->id);
        return response("OK", 200)->header('Content-Type', 'text/plain');
    }

    public function packaging_type_remove(Request $request)
    {
        PackagingType::destroy($request->id);
        return response("OK", 200)->header('Content-Type', 'text/plain');
    }
}