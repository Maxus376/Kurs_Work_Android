<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;

class DeliverManager extends Controller
{
    function getDelivery(Request $request)
    {
        $delivery = Orders::where("deliver_email", $request->email)
            ->where("status", "Доставляется")->orderBy("id", "DESC")->get();
        return $delivery;
    }

    function markStatus(Request $request, $status)
    {
        $order = Orders::where("id", $request->order_id)->first();
        $order->status = $status;
        if ($order->save()) {
            return "success";
        }
        return "error";
    }

    function markStatusSuccess(Request $request)
    {
        return $this->markStatus($request, "Доставлено");
    }

    function markStatusFailed(Request $request)
    {
        return $this->markStatus($request, "Отменено");
    }
}
