<?php

namespace App\Http\Livewire\Admin\Subscription;

use App\Models\Subscription;
// use App\Models\Order;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Stripe;

class View extends Component
{
    use LivewireAlert;
    public $subscription;
    public $status;
    public $payment_total;

    public function mount(Subscription $subscription)
    {
        if (!auth()->user()->can('admin_order_edit')) {
            return abort(403);
        }
        $this->subscription = $subscription;
    }

    public function render()
    {
        if (!auth()->user()->can('admin_subscription_create')) {
            return abort(403);
        }
        $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );

        $payments =  $stripe->paymentIntents->retrieve(
            $this->subscription->stripe_payment_id,
            []
        );
        // dd($payments);
        $subscriptions = "";
        $customers = "";

        $subscriptions = $stripe->subscriptions->retrieve(
            $this->subscription->stripe_subscription_id,
            []
        );
        $customers =  $stripe->customers->retrieve(
            $this->subscription->stripe_customer_id,
            []
        );

        return view('livewire.admin.subscription.view', compact('payments', 'subscriptions', 'customers'));
    }
}
