@push('title') Logs @endpush
<div @if ($file!=[] && $file!='' ) wire:poll.10000ms @endif> <!-- every 10s page will be refresh -->
    <div wire:loading wire:target="user,action_method_select,searchByDate">
        <div class="loading">
            <div class="loading-content">
                <div class="loading-icon">
                    <img src="{{ asset('assets/images/Spinner.gif') }}" width="150px" alt="">
                </div>
                <h1 class="loading-title ">
                    {!! __('header.waiting') !!}
                </h1>
            </div>
        </div>
    </div>
    <div class=" px-lg-5 px-3">
        <div class="mt-4">
            <h1>
                {{ __('header.Logs') }}
            </h1>
        </div>
        <div class="col-12">
            <form>
                <div class="row g-2">
                    <div class="col-md-3 col-12">
                        <select name="" id="" class="form-select" wire:model="user">
                            <option value="">{{ __('header.select_user') }}</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($user_select != '' || $user_select != null)
                    <div class="col-md-3 col-6">
                        <select name="" id="" class="form-select" wire:model="action_method_select">
                            <option value="">{{ __('header.select_action') }}</option>
                            @foreach ($action_method as $action)
                            <option value="{{ $action }}">{{ $action }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-6">
                        <input type="date" class="form-control" wire:model="searchByDate">
                    </div>
                    @can('Clear Log')
                    <div class="col-md-3 col-6">
                        <button type="button" class="btn btn-danger pt-2" wire:click="clear">
                            <i class="fas fa-trash-alt mx-2 mb-1"></i>
                            {{ __('header.Clear') }}
                        </button>
                    </div>
                    @endcan
                    @endif
                </div>
            </form>
            @if ($user_select != '' || $user_select != null)
            <div class="table-responsive mt-3" wire:loading.remove wire:target="user,searchByDate,action_method_select">
                <table class="table  table-nowrap ">
                    <thead>
                        <tr class="">
                            <th class="col-1 fs-4">
                                {{ __('header.date') }}
                            </th>
                            <th class="col-1 fs-4">
                                {{ __('header.Page') }}
                            </th>
                            <th class="col-2 fs-4 text-center">
                                {{ __('header.Action') }}
                            </th>
                            <th class="col-4 fs-4">
                                {{ __('header.Old Data') }}
                            </th>
                            <th class="col-4 fs-4">
                                {{ __('header.New Data') }}
                            </th>
                            <th class="col-1 fs-4 text-center">
                                {{ __('header.delete') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($file!= [] && $file!= '')
                        @foreach ($file as $key => $item)
                        @php
                        $oldData = $item[3] !=null || $item[3] !="" ? explode(',', $item[3]) : []; // change to array by a explode function
                        $newData = $item[4] !=null || $item[4] !="" ? explode(',', $item[4]) : [];
                        @endphp
                        <tr>
                            <td>
                                {{ $item[0] }}
                            </td>
                            <td>
                                {{ $item[1] }}
                            </td>
                            <td class="text-center">
                                @php
                                $action =$item[2];
                                @endphp
                                {{ $item[2] }}
                            </td>
                            <td>
                                @forelse ($oldData as $index => $data)
                                <div class=" {{ count($oldData) !=0 && $loop->index == 0 ? '' : 'mt-1' }}  {{ count($oldData) !=0 && $newData !=[] && $newData[$index] !== $data ? 'text-white bg-info py-1 rounded px-1' : '' }}">
                                    {{ $data }}
                                </div>
                                @empty
                                <p>
                                    {{ __('header.No Data') }}
                                </p>
                                @endforelse
                            </td>
                            <td>
                                @forelse ($newData as $index => $data)
                                <div class="{{ count($oldData) !=0 && $loop->index == 0 ? '' : 'mt-1' }}   {{  count($oldData) !=0 && $oldData !=[] && $oldData[$index] !== $data ? 'bg-yellow py-1 rounded px-1' : '' }}">
                                    {{ $data }}
                                </div>
                                @empty
                                <p>
                                    {{ __('header.No Data') }}
                                </p>
                                @endforelse
                            </td>
                            @can('Delete Logs')
                            <td>
                                <a href="" class="btn btn-danger btn-sm px-2 pt-1 mt-2  rounded-2" wire:click.prevent="delete('{{ $action }}',{{ $key }})">
                                    <i class="fas fa-trash-alt mx-2"></i>
                                    {{ __('header.delete') }}
                                </a>
                            </td>
                            @endcan
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="text-center">
                                <p>
                                    {{ __('header.No Logs') }}
                                </p>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>