<?php

namespace App\Http\Livewire\Admin\Order;

use App\Models\Order;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Edit extends Component
{
    use LivewireAlert;
    public $order;
    public $status;
    public $payment_total;
    
    public function mount(Order $order)
    {
        if(!auth()->user()->can('admin_order_edit')) {
            return abort(403);
        }
        $this->order = $order;
        $this->status=$order->status;
        $this->payment_total=$order->meta->payment_total;
    }

    public function edit()
    {
        if(!auth()->user()->can('admin_order_edit')) {
            return abort(403);
        }

        $this->emitTo(\App\Http\Livewire\Admin\Order\Index::getName(), 'updateList');
        $this->emit('hideModal');

        $this->alert('success', __('bap.edited'));
    }

    public function render()
    {
        if(!auth()->user()->can('admin_order_edit')) {
            return abort(403);
        }

        return view('livewire.admin.order.edit');
    }
}
