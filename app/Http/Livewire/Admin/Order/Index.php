<?php

namespace App\Http\Livewire\Admin\Order;

use App\Exports\OrderExport;
use App\Models\Order;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $selectedOrders = [];
    public $selectAll = false;

    public $order;
    public $search;
    public $perPage = 15;
    public $sortColumn = 'entry_id';
    public $sortDirection = 'desc';

    protected $paginationTheme = 'bootstrap';

    protected $queryString = ['search'];

    protected $listeners = [
        'updateList' => 'render'
    ];

    public function clear()
    {
        $this->search = "";
    }

    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

    public function sortByColumn($column)
    {
        if ($this->sortColumn == $column) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->reset('sortDirection');
            $this->sortColumn = $column;
        }
    }


    public function mount()
    {
        $this->search = request()->query('search', $this->search);
    }

    public function updatedSelectAll($value)
    {
        if($value) {
            $this->selectedOrders = Order::pluck('entry_id')->toArray();
        } else {
            $this->selectedOrders = [];
        }

    }

    public function updatedSelectedOrders($value)
    {
        if($this->selectAll) {
            $this->selectAll = false;
        }
    }

   
    public function exportSelectedQuery()
    {
        return Excel::download(new OrdersExport($this->selectedOrders), 'orders-'.date('Y-m-d').'.xlsx');
    }

    public function render()
    {
        if(!auth()->user()->can('admin_order_index')) {
            return abort(403);
        }
        $orders = Order::where('status','completed')->filter(['search' => $this->search])->orderBy($this->sortColumn, $this->sortDirection)->paginate($this->perPage);
        
        return view('livewire.admin.order.index', compact('orders'))->layout('layouts.admin');
    }
}
