@push('title') Categorys @endpush
<div>
    <div wire:loading
        wire:target="destroy,restore">
        <div class="loading">
            <div class="loading-content">
                <div class="loading-icon">
                    <img src="{{ asset('assets/images/Spinner.gif') }}"
                        width="150px"
                        alt="">
                </div>
                <h1 class="loading-title ">
                    {!! __('header.waiting') !!}
                </h1>
            </div>
        </div>
    </div>
    <div class="{{ app()->getLocale() =='ckb' || app()->getLocale() =='ar' ? 'reverse' : '' }} px-lg-5 px-3">
        <div>
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="fw-bolder fs-1">
                            {{ __('header.Categorys') }}
                        </p>
                    </div>
                    @can('Insert Category')
                    <div class="col-auto ms-auto">
                        <a href=""
                            class="btn btn-primary  btn-primary  "
                            data-bs-toggle="modal"
                            data-bs-target="#add-update"
                            wire:click="add()">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
        @canany(['Update Category','Insert Category'])
        @include('categorys.add-update')
        @endcanany
        @can('Delete Category')
        @include('categorys.delete')
        @endcan
        <div class="row  gy-1 align-items-center ">
            <form class="col-lg-3 col-md-6 col-12">
                <div class="input-icon">
                    <input type="text"
                        class="form-control"
                        placeholder="{{ __('header.search') }}"
                        wire:model="search">
                    <span class="input-icon-addon">
                        <i class="fa fa-search"></i>
                    </span>
                </div>
            </form>
            <div class="col-lg-6 col-md-4 col-12">
                <div class="d-flex align-items-center flex wrap gap-2">
                    @can('Category Trash')
                    <div>
                        <button class=" btn pt-2"
                            wire:click="Trash">
                            <i class="fa fa-trash mx-2 mb-1"></i>
                            {{ __('header.Trash') }}
                        </button>
                    </div>
                    @if($Trashed)
                    <div>
                        <div class="dropdown">
                            <button class="btn btn-info "
                                type="button"
                                id="dropdownMenuButton1"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ __('header.actions') }}
                            </button>
                            <ul class="dropdown-menu"
                                aria-labelledby="dropdownMenuButton1">
                                <li class="dropdwon-item mt-2">
                                    <button class=" btn shadow-none text-danger"
                                        wire:click="DeleteAll">
                                        <i class="fa-solid fa-trash-can mx-2 mb-2"></i>
                                        {{ __('header.DeletedAll') }}
                                    </button>
                                </li>
                                <li class="dropdwon-item mt-2">
                                    <button class=" btn shadow-none text-success "
                                        wire:click="RestoreAll">
                                        <i class="fa-solid fa-recycle mx-2 mb-2"></i>
                                        {{ __('header.RestoreAll') }}
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif
                    @endcan
                </div>
            </div>
        </div>

        <div class="row mt-3"
            wire:loading
            wire:target="search,previousPage,nextPage,gotoPage">
            <div class="d-flex  gap-2">
                <h3>
                    {{ __('header.waiting') }}
                </h3>
                <div class="spinner-border"
                    role="status"></div>
            </div>
        </div>
        <div class="table-responsive mt-3"
            wire:loading.remove
            wire:target="search,previousPage,nextPage,gotoPage">
            <table class="table table-vcenter table-nowrap">
                <thead>
                    <tr class="">
                        <th class=" fs-4">
                            {{ __('header.name') }}
                        </th>
                        <th class=" fs-4">
                            {{ __('header.address') }}
                        </th>
                        @if ($Trashed)
                        <th class=" fs-4">
                            {{ __('header.warning') }}
                        </th>
                        @else
                        <th class=" fs-4">
                            {{ __('header.add_date') }}
                        </th>
                        @endif
                        @canany(['Update Category','Insert Category'])
                        <th class="fs-4 text-center">
                            {{ __('header.actions') }}
                        </th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ( $categorys as $category )
                    <tr class="">
                        <td>
                            <span>
                                {{ $category->name }}
                            </span>
                            <span class="badge bg-info mx-2">
                                {{ $category->products_count }}
                            </span>
                            @if ($category->create_at())
                            <span class="badge bg-warning mx-1">
                                {{ __('header.new') }}
                            </span>
                            @endif
                        </td>
                        <td>
                            <a
                                href="{{ !$category->deleted_at ? route('products' ,['category'=> $category->id,'lang'=>app()->getLocale()]) : '' }}">
                                {{ $category->slug }}
                            </a>
                        </td>
                        @if ($category->deleted_at)
                        <td>
                            <span class="badge bg-info px-2 py-2">
                                {{ __('header.DeletedAfter30Dayes' ,['date'=>  $GetTrashDate($category->deleted_at) ]) }}
                            </span>
                        </td>
                        @else
                        <td>
                            <span>
                                {{ $category->created_at->diffForHumans() }}
                            </span>
                        </td>
                        @endif
                        @canany(['Update Category','Insert Category'])
                        <td class=" col-1 text-center">
                            @if (!$Trashed)
                            @can('Update Category')
                            <a class="btn"
                                href=""
                                data-bs-toggle="modal"
                                data-bs-target="#add-update"
                                wire:click.prevent="update({{ $category->id }})">
                                <i class="fa-solid fa-edit text-primary"></i>
                            </a>
                            @endcan
                            @can('Delete Category')
                            <a class="btn"
                                href=""
                                data-bs-toggle="modal"
                                data-bs-target="#delete"
                                wire:click.prevent="$set('category_id' , {{ $category->id }})">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </a>
                            @endcan
                            @else
                            <button class="btn text-success"
                                wire:click="restore({{ $category->id }})">
                                <i class="fa-solid fa-recycle"></i>
                            </button>
                            @endif
                        </td>
                        @endcanany
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4"
                            class="text-center">
                            <h4>
                                {{ __('header.NoData') }}
                            </h4>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="my-3"
            wire:loading.remove
            wire:target="search,previousPage,nextPage,gotoPage">
            {!! $categorys->onEachSide(1)->links() !!}
        </div>
    </div>
</div>