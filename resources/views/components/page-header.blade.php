<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">
                {{ $title }}
            </h2>
        </div>
        @canany(['InsertProduct','InsertBarcode','InsertCategory','Insert Sales','InsertSupplier','InsertUser'])
        <div class="col-auto ms-auto">
            <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="{{ $target }}" {{ $wire }}>
                <i class="fa fa-plus"></i>
            </a>
        </div>
        @endcanany
    </div>
</div>