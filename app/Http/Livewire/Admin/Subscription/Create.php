<?php

namespace App\Http\Livewire\Admin\Subscription;

use App\Models\Subscription;
use App\Models\Order;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
// use Livewire\WithFileUploads;
use Stripe;

class Create extends Component
{
    use LivewireAlert;
    // use WithFileUploads;
    public $entry_id;
    public $form_id;
    public $status;
    public $comments;
    public $fields;
    public $paymentSubscription;
    public $paymentCustomer;
    public $paymentTransaction;
    public $stripe_customer_id;
    public $stripe_subscription_id;
    public $stripe_payment_id;

    protected $listeners = [
        'updateList' => 'render'
    ];

    public function mount(Order $order)
    {
        if (!auth()->user()->can('admin_subscription_create')) {
            return abort(403);
        }
        //dd($order->fields);

        $this->entry_id = $order->entry_id;
        $this->form_id = $order->form_id;
        $this->fields = $order->fields;
        $this->stripe_subscription_id = $order->meta->payment_subscription;
        $this->stripe_customer_id = $order->meta->payment_customer;
        $this->stripe_payment_id = $order->meta->payment_transaction;
    }

    public function get_subscription_period_data()
    {

        return array(
            'daily'      => array(
                'name'     => 'daily',
                'interval' => 'day',
                'count'    => 1,
                'desc'     => 'Daily',
            ),
            'weekly'     => array(
                'name'     => 'weekly',
                'interval' => 'week',
                'count'    => 1,
                'desc'     => 'Weekly',
            ),
            'monthly'    => array(
                'name'     => 'monthly',
                'interval' => 'month',
                'count'    => 1,
                'desc'     => 'Monthly',
            ),
            'quarterly'  => array(
                'name'     => 'quarterly',
                'interval' => 'month',
                'count'    => 3,
                'desc'     => 'Quarterly',
            ),
            'semiyearly' => array(
                'name'     => 'semiyearly',
                'interval' => 'month',
                'count'    => 6,
                'desc'     => 'Semi-Yearly',
            ),
            'yearly'     => array(
                'name'     => 'yearly',
                'interval' => 'year',
                'count'    => 1,
                'desc'     => 'Yearly',
            ),
        );
    }

    public function create_plan($stripe, $id, $period, $args)
    {

        $name = \sprintf(
            '%s (%s %s)',
            !empty($args['name']) ? $args['name'] : $args['form_title'],
            $args['amount'],
            $period['desc']
        );

        $plan_args = array(
            'amount'         => $args['amount'],
            'interval'       => $period['interval'],
            'interval_count' => $period['count'],
            'product'        => array(
                'name' => ($name),
            ),
            'nickname'       => ($name),
            'currency'       => $args['currency'],
            'id'             => $id,
            'metadata'       => array(
                'form_name' =>  $args['form_title'],
                'form_id'   => $args['form_id'],
            ),
        );

        try {
            $plan = $stripe->plans->create($plan_args);
        } catch (\Exception $e) {
            print_r($e);
            $plan = null;
        }
        return $plan;
    }

    public function get_plan_id($stripe, $args)
    {

        $period_data = $this->get_subscription_period_data();
        $period =  $period_data['monthly'];

        //Create plan
        $slug = \preg_replace('/[^a-z0-9\-]/', '', \strtolower(\str_replace(' ', '-', $args['name'])));

        $plan_id = \sprintf(
            '%s_%s_%s',
            $slug,
            $args['amount'],
            $period['name']
        );

        try {
            $plan = $stripe->plans->retrieve($plan_id);
        } catch (\Exception $e) {
            $plan = $this->create_plan($stripe, $plan_id, $period, $args);
        }

        return isset($plan->id) ? $plan : '';
    }


    public function create()
    {
        if (!auth()->user()->can('admin_subscription_create')) {
            return abort(403);
        }

        $this->validate([
            'entry_id' => 'required',
            'form_id' => 'required',
            'status' => 'nullable',
            'comments' => 'nullable',
            'stripe_customer_id' => 'required',
            'stripe_subscription_id' => 'required',
            'stripe_payment_id' => 'nullable'
        ]);

        $subscription = new Subscription();
        $subscription->entry_id = $this->entry_id;
        $subscription->form_id = $this->form_id;
        $subscription->status = $this->status;
        $subscription->comments = $this->comments;
        $subscription->stripe_customer_id = $this->stripe_customer_id;
        $subscription->stripe_subscription_id = $this->stripe_subscription_id;
        $subscription->stripe_payment_id = $this->stripe_payment_id;

        // $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        //Retrieve subscription details
        $subscriptions = $stripe->subscriptions->retrieve(
            $this->stripe_subscription_id,
            []
        );
        // Create subscription schedule
        $subscriptionSchedules = $stripe->subscriptionSchedules->create([
            'from_subscription' => $subscriptions->id
        ]);
        // Retrieve subscription schedule details
        $subscriptionSchedules = $stripe->subscriptionSchedules->retrieve(
            $subscriptionSchedules->id,
            []
        );
        $plan = $subscriptionSchedules->phases[0]->items[0]->plan;
        $price = $subscriptionSchedules->phases[0]->items[0]->price;
        $quantity = $subscriptionSchedules->phases[0]->items[0]->quantity;

        $phase_item1 = [
            'start_date' => $subscriptionSchedules->phases[0]->start_date,
            'items' => [
                [
                    'price' => $price,
                    'plan' => $plan,
                    'quantity' => $quantity,
                ],
            ],
            'iterations' => 1,
        ];
        $phase_item2 = [
            'items' => [
                [
                    'price' => $price,
                    'plan' => $plan,
                    'quantity' => $quantity,
                ],
            ],
            'iterations' => 5,
        ];
        if (isset($this->fields["275"]["value_raw"]) && $this->fields["275"]["value_raw"] == 3) {
            $name = '5 Monthly Payments of Record Zap';
            $amount = ($this->fields["320"]["value"] * 100);
            $args = [
                'form_id'    => $subscriptions->metadata->form_id,
                'form_title' => $subscriptions->metadata->form_name,
                'amount'     => $amount,
                'currency'     => $subscriptions->currency,
                'name'   => $name,
            ];

            $plan_new = $this->get_plan_id($stripe, $args);
            $phase_item2 = [
                'items' => [
                    [
                        'plan' => $plan_new,
                        'quantity' => $quantity,
                    ],
                ],
                'iterations' => 5,
            ];
            // array_push($phases_array, $phase_item);
        }

        $stripe->subscriptionSchedules->update(
            $subscriptionSchedules->id,
            [
                'end_behavior' => 'cancel',
                'phases' => [$phase_item1, $phase_item2],
            ]
        );
        $subscription->stripe_subscription_schedule_id =  $subscriptionSchedules->id;

        $subscription->save();


        $this->emitTo(\App\Http\Livewire\Admin\Order\Index::getName(), 'updateList');
        $this->emit('hideModal');

        $this->alert('success', __('bap.created'));
    }

    public function render()
    {
        // $categories = Category::where('type', 'article')->get();
        return view('livewire.admin.subscription.create');
    }
}
