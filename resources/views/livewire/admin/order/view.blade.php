<div class="modal-dialog">
    <form wire:submit.prevent="edit">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('bap.payment_details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('bap.close') }}"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    Payment date: {{$order->date}}<br/>
                    Payment status: {{$order->status}} 
                            (@if($order->meta)

                            @if($order->meta->payment_period)
                            {{ ucfirst($order->meta->payment_period) }}
                            @endif
                            @if($order->meta->payment_subscription)
                            recuring payment
                            @endif
                            @if($order->meta->payment_period == "" && $order->meta->payment_subscription == "")
                            <strong class="font-bold">Paid</strong>
                            @endif
                            @endif)<br>
                    Payment total: @if($order->meta)
                            @switch(strtolower($order->meta->payment_currency))
                            @case('usd')
                            $
                            @break

                            @default
                            $
                            @endswitch
                            {{ $order->meta->payment_total }}
                            @endif
                            <br/>
                            {{$payments->payment_method}}
                    Customer Email: 
                            @if ($payments->receipt_email)
                                {{$payments->receipt_email}}
                            @else
                            {{$customers->email}}
                            @endif<br/>
                    @if ($payments->description)
                    Description: {{$payments->description}}<br/>    
                    @endif
                    @if($subscriptions)
                        <p><strong>Subscription details</strong><br>
                        Subscription: {{ $subscriptions->id}}<br>
                        Created at: {{Carbon\Carbon::parse($subscriptions->created)->format("Y-m-d H:i:s")}}<br>
                        Current period start: {{Carbon\Carbon::parse($subscriptions->current_period_start)->format("Y-m-d H:i:s")}}<br>
                        Current period end: {{Carbon\Carbon::parse($subscriptions->current_period_end)->format("Y-m-d H:i:s")}}<br>
                        @if ($subscriptions->canceled_at)
                            Canceled at: {{Carbon\Carbon::parse($subscriptions->canceled_at)->format("Y-m-d H:i:s")}}<br>
                            @if ($subscriptions->cancellation_details->comment)
                                Comment: {{$subscriptions->cancellation_details->comment}}<br>
                            @endif
                            @if ($subscriptions->cancellation_details->reason)
                                Reason: {{$subscriptions->cancellation_details->reason}}
                            @endif
                        @endif
                        </p>
                    @endif        
                    
                    <p><a href="{{$payments->metadata->entry_url}}" target="_blank">View entry details</a></p>        
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('bap.close') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('bap.edit') }}</button>
            </div>
        </div>
    </form>
</div>

