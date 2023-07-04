@extends('layout')
@section('content')
    <main class="container my-5" style="max-width: 700px">
        <div>
            <ul class="list-group">
                @forelse($orders as $order)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6">
                                <div class="fw-bold">
                                    @foreach($order->item_details as $item_details)
                                        <div>{{$item_details->name}}</div>
                                    @endforeach
                                    <br>
                                    Адрес: {{$order->destination_address}}
                                </div>
                            </div>
                            <div class="col-6">
                                <form action="{{route("order.assign")}}" method="POST">
                                    @csrf
                                    <div class="form-floating">
                                        <input name="order_id" value="{{$order->id}}" hidden>
                                        <select class="form-select" id="deliver_email" name="deliver_email"
                                                aria-label="Floating label select example">
                                            @foreach($delivers as $deliver)
                                                <option
                                                    value="{{$deliver->email}}">{{$deliver->name}}</option>
                                            @endforeach
                                        </select>
                                        <label for="deliver_email">Выберите курьера</label>
                                    </div>
                                    <input type="submit" class="btn btn-success rounded-pill" value="Закрепить">
                                </form>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item">
                        <div class="alert alert-warning">Новых заказов ещё нет</div>
                    </li>
                @endforelse
            </ul>
        </div>
    </main>
@endsection