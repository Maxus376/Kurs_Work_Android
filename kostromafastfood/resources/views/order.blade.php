@extends('layout')
@section('content')
    <main class="container my-5" style="max-width: 900px">
        <div class="row">
            <div class="col-12">
                <div class="fs-5 fw-bold mb-2 text-decoration-underline">Список заказов:</div>
                <table class="table table-bordered border-secondary">
                    <thead>
                    <tr>
                        <th>Заказ №</th>
                        <th>Адрес:</th>
                        <th>Блюда:</th>
                        <th>Статус:</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{$order->id}}</td>
                            <td>{{$order->destination_address}}</td>
                            <td>
                                @foreach($order->item_details as $item_details)
                                    <div>{{$item_details->name}}</div>
                                @endforeach
                            </td>
                            <td>{{$order->status}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection