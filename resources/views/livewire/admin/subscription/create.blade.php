<div class="modal-dialog">
    <form wire:submit.prevent="create">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('bap.create_subscription') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('bap.close') }}"></button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="mb-3">
                    <input type="hidden" wire:model="entry_id" class="form-control @error('entry_id') is-invalid @enderror" name="entry_id" placeholder="entry_id">
                    <input type="hidden" wire:model="form_id" class="form-control @error('form_id') is-invalid @enderror" name="form_id" placeholder="form_id">
                    <input type="hidden" wire:model="stripe_subscription_id" class="form-control @error('stripe_subscription_id') is-invalid @enderror" name="stripe_subscription_id" placeholder="paymentSubscription">
                    <input type="hidden" wire:model="stripe_customer_id" class="form-control @error('stripe_customer_id') is-invalid @enderror" name="stripe_customer_id" placeholder="paymentCustomer">
                    <input type="hidden" wire:model="stripe_payment_id" class="form-control @error('stripe_payment_id') is-invalid @enderror" name="stripe_payment_id" placeholder="paymentTransaction">
                    <p><strong>Subscription ID: {{$stripe_subscription_id}}</strong></p>
                    <p><strong>Payment option: {{$fields["275"]["value_raw"]}}</strong></p>

                </div>
                <div class="mb-3">
                    <label class="form-label" for="comments">{{ __('bap.comments') }}</label>
                    <textarea wire:model="comments" class="form-control @error('comments') is-invalid @enderror" name="comments"></textarea>
                    @error('comments')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('bap.close') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('bap.create_subscription') }}</button>
            </div>
        </div>
    </form>
</div>