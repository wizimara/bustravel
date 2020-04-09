@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')    


            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="h3 mb-3 font-weight-normal">Payment Processing{{$transactionId ?? '0'}}</h1>
                        <div class="card">
                            <div class="card-body">
                                
                                <ul class="list-inline">
                                    <li id="notification_title"  class="list-inline-item">please wait...</li>
                                </ul>
                                
                                <h3 id="notification_message" class="card-title"></h3>
                            </div>
                        </div>
                        
                    </div>
                </div>
                @endsection

                <script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT')}}/socket.io/socket.io.js"></script>

                <script src="{{ url('/js/app.js') }}" type="text/javascript"></script>
            
                  
            
                <script type="text/javascript">
            
                    var trans_id = "{!! $transactionId !!}";
            
                    window.Echo.private('transaction.'+trans_id)
            
                     .listen('TransactionStatusUpdated', (data) => {
            
                       console.log('status'+data.status+'');
            
                        $("#notifification_title").html('<span>'+data.status+'</span>');
                        $("#notifification_message").html(''+data.status+'');
            
                    });
            
                </script>
