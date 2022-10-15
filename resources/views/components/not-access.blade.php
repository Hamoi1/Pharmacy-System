<div>
    @cannot('admin')
    <div class="my-2">
        <div class="alert alert-success" role="alert">
            <h4 class="alert-title">
                <i class="fas fa-info-circle"></i>
                {{ __('header.warning') }}
            </h4>
            <div class="text-muted">
                {{ __('header.NotAccess',['name'=> $name]) }}
            </div>
        </div>
    </div>
    @endcannot
</div>
