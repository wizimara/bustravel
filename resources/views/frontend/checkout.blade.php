@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')
@section('navigaton-bar')


@endsection
        @section('content')
            <div class="row">
                <div class="col-md-4 order-md-2 mb-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Your cart</span>
                    <span class="badge badge-secondary modify_link"><a href="{{route('bustravel.cart')}}">Modify Order</a></span>
                    </h4>
                    @php
                            $cart = session()->get('cart.items');
                            $total_amount = 0;
                            $reserve_fee = 0;
                            $booking_fee = 0;
                            $total_tickets = 0;
                        @endphp
                    <ul class="list-group mb-3">
                        @foreach($cart as $index=> $item)
                        @php

                                $total_amount += $item['quantity']*$item['amount'];
                                $total_tickets += $item['quantity'];
                            @endphp
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">No Of Bus Tickets</h6>
                                <small class="text-muted">tickets cost</small>
                            </div>
                            <div>
                                <h6 class="my-0">{{$total_tickets}}</h6>
                                <small class="text-muted">RWF {{$total_amount}}</small>
                            </div>
                        </li>



                        <li class="list-group-item d-flex justify-content-between bg-light">
                            <div class="text-success">
                                <h6 class="my-0">Promo code</h6>
                                <small>EXAMPLECODE</small>
                            </div>
                            <span class="text-success">-0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (RWF)</span>
                            <strong>{{$total_amount}}</strong>
                        </li>
                    </ul>
                    <form class="card p-2">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Promo code">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-secondary">Redeem</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-8 order-md-1">
                    <h4 class="mb-3">Billing address</h4>
                <form class="needs-validation" method="POST" action="{{route('bustravel.cart.pay')}}">
                    @csrf
                    <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">Passenger's Name</label>
                                <input type="text" name="first_name" class="form-control" id="firstName"  value="{{ old('first_name') ?? Auth::user()->name }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email">Email [for ticket delivery]</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="you@example.com" value="{{  old('email')}}">
                            @error('email')
                                <div class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                            </div>

                        </div>

                        <div class="mb-3">
                            <label for="address">Address</label>
                            <input type="text" name="address_1" class="form-control" id="address" placeholder="1234 Main St" value="{{ old('address_1') ?? 'kigali' }}" >
                            @error('address_1')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="country">Country</label>
                                <select class="custom-select d-block w-100" name="country" id="country" required>
                                    <option value="">Choose...</option>
                                    <option value="RW" selected>Rwanda</option>
                                </select>
                                @error('country')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state">State</label>
                                <select class="custom-select d-block w-100" name="state" id="state" required>
                                    <option value="">Choose...</option>
                                    <option value="Kigali" selected>Kigali</option>
                                    <option value="Eastern">Eastern</option>
                                    <option value="Western">Western</option>
                                </select>

                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control" id="zip" placeholder="" >

                            </div>
                        </div>
                        <hr class="mb-4">
                        <h4 class="mb-3">Ticket Delivery</h4>
                        <div class="d-block my-3">
                            <div>
                                <input  name="ticketdeliveryemail" type="checkbox" value="email" >
                                <label for="paypal">Email</label><br>
                                @error('ticketdeliveryemail')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <input  name="ticketdeliverysms" type="checkbox" value="sms"  >
                                <label >Sms [additional cost of: {{$sms_cost ?? 10}} RWF applies]</label>
                                @error('ticketdeliverysms')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <hr class="mb-4">
                        <h4 class="mb-3">Payment Details</h4>
                        <div class="d-block my-3">
                            <div class="custom-control custom-radio">
                                <input id="momo" value="mobile_money" name="payment_method" type="radio" class="custom-control-input" checked required>
                                <label class="custom-control-label" for="paypal">Mobile Money</label>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="cc-number">Phone Number</label>
                                <input name="phone_number" type="text" class="form-control" id="cc-number" placeholder="250780123123" value="{{ old('phone_number') }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <hr class="mb-4">
                        <button class="btn btn-primary btn-lg btn-block" type="submit">Pay</button>
                    </form>
                </div>
            </div>
@endsection
