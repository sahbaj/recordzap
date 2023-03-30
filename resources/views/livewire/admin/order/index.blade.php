<div>
    <x-slot name="title">
        {{ __('bap.orders') }}
    </x-slot>
    <x-slot name="actions">
        @can('admin_order_create')
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <button onclick="Livewire.emit('showModal', 'admin.order.create')" class="btn btn-primary d-none d-sm-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    {{ __('bap.create_user') }}
                </button>
                <button onclick="Livewire.emit('showModal', 'admin.order.create')" class="btn btn-primary d-sm-none btn-icon" aria-label="Create new report">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </button>
            </div>
        </div>
        @endcan
    </x-slot>
    <x-slot name="breadcrumb">
        <ol class="breadcrumb breadcrumb-arrows" aria-label="breadcrumbs">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ __('bap.dashboard') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.order.index') }}">{{ __('bap.orders') }}</a></li>
        </ol>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('bap.orders') }}</h3>
        </div>
        <div class="card-body">
            <div class="d-flex">
                <div class="text-muted">
                    {{ __('bap.per_page') }}:
                    <div class="mx-2 d-inline-block">
                        <div class="btn-group btn-group-sm w-100">
                            <button type="button" wire:click="setPerPage(10)" class="btn @if($perPage == 10) btn-primary @endif">10</button>
                            <button type="button" wire:click="setPerPage(15)" class="btn @if($perPage == 15) btn-primary @endif">15</button>
                            <button type="button" wire:click="setPerPage(20)" class="btn @if($perPage == 20) btn-primary @endif">20</button>
                            <button type="button" wire:click="setPerPage(25)" class="btn @if($perPage == 25) btn-primary @endif">25</button>
                        </div>
                    </div>
                </div>
                <div class="ms-auto text-muted">
                    {{ __('bap.search') }}:
                    <div class="ms-2 d-inline-block">

                        <div class="input-group input-group-sm input-group-flat">
                            <input type="text" wire:model="search" class="form-control" autocomplete="off">
                            @if($search)
                            <span class="input-group-text">
                                <a href="#clear" wire:click="clear" class="link-secondary" title="" data-bs-toggle="tooltip" data-bs-original-title="Clear search"><!-- Download SVG icon from http://tabler-icons.io/i/x -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </a>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th class="w-1"><input name="selectAll" wire:model="selectAll" class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"></th>
                        <th class="w-1" wire:click="sortByColumn('entry_id')">{{ __('bap.number') }}

                            @if ($sortColumn == 'entry_id')
                            @if($sortDirection == 'asc')
                            <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-dark icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <polyline points="6 15 12 9 18 15" />
                            </svg>
                            @else
                            <!-- Download SVG icon from http://tabler-icons.io/i/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-dark icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <polyline points="6 9 12 15 18 9" />
                            </svg>

                            @endif
                            @endif
                        </th>
                        <th wire:click="sortByColumn('form_id')">form </th>
                        <th wire:click="sortByColumn('user_id')">Status</th>
                        <th>Payment Type</th>
                        <th>Total</th>
                        <th wire:click="sortByColumn('date')">{{ __('bap.created_at') }}

                            @if ($sortColumn == 'date')
                            @if($sortDirection == 'asc')
                            <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-dark icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <polyline points="6 15 12 9 18 15" />
                            </svg>
                            @else
                            <!-- Download SVG icon from http://tabler-icons.io/i/chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-dark icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <polyline points="6 9 12 15 18 9" />
                            </svg>

                            @endif
                            @endif
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select Order" value="{{ $order->entry_id }}" name="selectedOrders" wire:model="selectedOrders"></td>
                        <td>{{ $order->entry_id }}</td>
                        <td>{{ $order->order_form->post_title }}</td>
                        <td>{{ $order->status }}</td>
                        <td>
                            @if($order->meta)

                            @if($order->meta->payment_period)
                            {{ ucfirst($order->meta->payment_period) }}
                            @endif
                            @if($order->meta->payment_subscription)
                            recuring payment
                            @endif
                            @if($order->meta->payment_period == "" && $order->meta->payment_subscription == "")
                            <strong class="font-bold">Paid</strong>
                            @endif
                            @endif
                        </td>
                        <td>
                            @if($order->meta)
                            @switch(strtolower($order->meta->payment_currency))
                            @case('usd')
                            $
                            @break

                            @default
                            $
                            @endswitch
                            {{ $order->meta->payment_total }}
                            @endif
                        </td>
                        <td>{{ $order->date }}</td>
                        <td class="text-end">
                            @can('admin_subscription_create')
                                @if ($order->subscription)
                                    <button onclick="Livewire.emit('showModal', 'admin.subscription.view', '{{ json_encode($order->subscription->id) }}')" class="btn btn-success d-none d-sm-inline-block">
                                        {{ __('bap.view_subscription') }}
                                    </button>
                                @else
                                    <button onclick="Livewire.emit('showModal', 'admin.subscription.create', '{{ json_encode($order->entry_id) }}')" class="btn btn-primary d-none d-sm-inline-block">
                                        {{ __('bap.create_subscription') }}
                                    </button>
                                @endif
                            @endcan
                            @can('admin_payment_view')
                                <button onclick="Livewire.emit('showModal', 'admin.order.view', '{{ json_encode($order->entry_id) }}')" class="btn btn-secondary d-none d-sm-inline-block">
                                    {{ __('bap.view_payments') }}
                                </button>
                            @endcan
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <div>
                <div class="btn-group btn-group-sm w-100">
                    <button type="button" wire:click="exportSelectedQuery" class="btn">{{ __('bap.export') }} ({{ count($selectedOrders) }})</button>
                </div>

            </div>

            <div>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>