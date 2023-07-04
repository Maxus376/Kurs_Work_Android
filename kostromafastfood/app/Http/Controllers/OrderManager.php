<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Orders;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrderManager extends Controller
{
    function newOrders()
    {
        $orders = Orders::where("status", "Ожидание доставки")->get();
        $orders = json_decode(json_encode($orders));
        $delivers = User::where("type", "deliver")->get();
        $products = Products::get();
        foreach ($orders as $key => $order) {
            $order_item_ids = json_decode($order->items);
            foreach ($order_item_ids as $key2 => $order_item) {
                foreach ($products as $product) {
                    if ($order_item->item_id == $product->id) {
                        $orders[$key]->item_details[$key2] = $product;
                    }
                }
            }
        }
        return view("dashboard", compact("orders", "delivers"));
    }

    function assignOrder(Request $request)
    {
        $order = Orders::where("id", $request->order_id)->first();
        $order->deliver_email = $request->deliver_email;
        $order->status = "Доставляется";
        if ($order->save()) {
            return redirect(route("dashboard"))
                ->with("success", "Заказ закреплён за курьером");
        }
        return redirect(route("dashboard"))
            ->with("error", "Ошибка прикрепления заказа");
    }

    function listOrders()
    {
        $orders = Orders::orderBy("id", "DESC")->get();
        $orders = json_decode(json_encode($orders));
        $products = Products::get();
        foreach ($orders as $key => $order) {
            $order_item_ids = json_decode($order->items);
            foreach ($order_item_ids as $key2 => $order_item) {
                foreach ($products as $product) {
                    if ($order_item->item_id == $product->id) {
                        $orders[$key]->item_details[$key2] = $product;
                    }
                }
            }
        }
        return view("order", compact("orders"));
    }

    function addToCart(Request $request)
    {
        $cart = new Cart();
        $cart->item_id = $request->item_id;
        $cart->user_email = $request->user_email;
        if ($cart->save()) {
            return "success";
        }
        return "error";
    }

    function removeFromCart(Request $request)
    {
        $cart = Cart::where("item_id", $request->item_id)
            ->where("user_email", $request->user_email)->first();
        if ($cart == null) {
            return "success";
        }
        if ($cart->delete()) {
            return "success";
        }

        return "error";
    }

    function getCart(Request $request)
    {
        $item_id = array();
        $count_items = DB::select(
            "SELECT item_id, COUNT(item_id) as num_item from cart
                                           where user_email = '" . $request->user_email . "'
                                           GROUP BY item_id");
        foreach ($count_items as $key => $item) {
            $item_id[$key] = $item->item_id;
        }
        $user = User::where("email", $request->user_email)->first();
        $dis_dur = $this->calculateEstimatedTime($user);
        $products = Products::whereIn('id', $item_id)->get();
        foreach ($count_items as $item) {
            foreach ($products as $key => $product) {
                if ($item->item_id == $product->id) {
                    $products[$key]->numItem = $item->num_item;
                }
            }
        }
        $data = array();
        array_push($data, array("cart" => json_decode($products),
            "Время доставки:" => $dis_dur['duration'], "Расстояние:" => $dis_dur['distance']));
        return $data;
    }

    function confirmCart(Request $request)
    {
        $cart = Cart::select("item_id")->where("user_email", $request->user_email)->get();
        if (empty($cart->first())) {
            return "error";
        }
        $user = User::where("email", $request->user_email)->first();
        $order = new Orders();
        $order->customer_email = $request->user_email;
        $order->items = $cart;
        $order->status = "Ожидание доставки";
        $order->sum_price = 1100;
        $order->destination_address = "ул. Ленина 160Вк1, кв. 106, п.5, эт.2";
        $order->destination_lat = 57.79937;
        $order->destination_lon = 40.9568;
        if ($order->save()) {
            if ($this->clearCart($request) == "success") {
                return "success";
            }
        }

        return "error";
    }

    function clearCart(Request $request)
    {
        if (Cart::where("user_email", $request->user_email)->delete()) {
            return "success";
        }

        return "error";
    }

    function calculateEstimatedTime($user)
    {
        /*$origin_lat = 10.0274266;
        $origin_lon = 76.3058943;*/

        $origin_lat = 57.7820594; // Kostroma - Lenina 95
        $origin_lon = 40.9374502;

        //$apiURL = "https://api.nextbillion.io/distancematrix/json?origins=$origin_lat,$origin_lon&destinations=$user->destination_lat,$user->destination_lon&mode=4w&key=your-nextbillion-api-key-here"; // go to nextbillion.ai
        //$response = json_decode(Http::get($apiURL));
        $dist_dur['distance'] = "10 км";//$response->rows[0]->elements[0]->distance->value;
        $dist_dur['duration'] = "32 мин.";//$response->rows[0]->elements[0]->duration->value;

        return $dist_dur;
    }

    function getOrders(Request $request){
        $orders = Orders::where("customer_email", $request->email)->orderBy("id", "DESC")->get();
        $orders = json_decode(json_encode($orders));
        $products = Products::get();
        foreach ($orders as $key => $order) {
            $order_item_ids = json_decode($order->items);
            foreach ($order_item_ids as $key2 => $order_item) {
                foreach ($products as $product) {
                    if ($order_item->item_id == $product->id) {
                        $orders[$key]->item_details[$key2] = $product;
                    }
                }
            }
        }

        return $orders;
    }

}
