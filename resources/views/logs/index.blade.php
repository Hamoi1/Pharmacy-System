@push('title') Logs @endpush
<div class="{{  app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }}"> <!-- every 10s page will be refresh -->
    <div wire:loading wire:target="user,action_method_select,searchByDate,pdf,delete">
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
                        <select name="" id="" class="form-select" wire:model="user_id">
                            <option value="">{{ __('header.select_user') }}</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($user_select != '' || $user_select != null)
                    <div class="col-md-3 col-6">
                        <select name="" id="" class="form-select" wire:model="action">
                            <option value="">{{ __('header.select_action') }}</option>
                            @foreach ($action_method as $action)
                            <option value="{{ $action }}">{{ $action }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-6">
                        <input type="date" class="form-control" wire:model="searchByDate">
                    </div>
                    @can('Clear Log')
                    <div class="col-md-2 col-6">
                        <button type="button" class="btn btn-danger pt-2" wire:click="clear">
                            <i class="fas fa-trash-alt mx-2 mb-1"></i>
                            {{ __('header.Clear') }}
                        </button>
                    </div>
                    @endcan
                    @if (count($user_logs) > 0)
                    <div class="col-md-2 col-6">
                        <button type="button" class="btn btn-dark" wire:click="pdf">
                            {{ __('header.PdfConvert') }}
                        </button>
                    </div>
                    @endif
                    @endif
                </div>
            </form>
            <div class="row mt-3" wire:loading wire:target="previousPage,nextPage,gotoPage">
                <div class="d-flex  gap-2">
                    <h3>
                        {{ __('header.waiting') }}
                    </h3>
                    <div class="spinner-border" role="status"></div>
                </div>
            </div>
            <div class="table-responsive mt-3" wire:loading.remove wire:target="previousPage,nextPage,gotoPage">
                <table class="table  table-nowrap ">
                    <thead>
                        <tr class="">
                            <th class="col-1 fs-4">
                                {{ __('header.date') }}
                            </th>
                            <th class="col-1 fs-4">
                                {{ __('header.Page') }}
                            </th>
                            <th class="col-1 fs-4 text-center">
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
                        @forelse ($user_logs as $log)
                        @php
                        $log->old_data = json_decode($log->old_data);
                        $log->new_data = json_decode($log->new_data);
                        @endphp
                        <tr>
                            <td class="col-1">
                                {{ $log->created_at  }}
                            </td>
                            <td class="col-1">
                                {{ $log->page }}
                            </td>
                            <td class="col-1 text-center">
                                {{ $log->action }}
                            </td>
                            <td class="col-4 text-wrap">
                                @if (is_array($log->old_data))
                                @forelse ($log->old_data as $index => $data)
                                <div class="{{ $log->old_data != '' && $loop->index == 0 ? '' : 'mt-1' }}  {{ $log->old_data != '' && $log->new_data !=[] && $log->new_data[$index] !== $data ? 'text-white bg-info py-1 rounded px-1' : '' }} ">
                                    {{ $data }}
                                </div>
                                @empty
                                <p>
                                    {{ __('header.No Data') }}
                                </p>
                                @endforelse
                                @else
                                {{ $log->old_data }}
                                @endif
                            </td>
                            <td class="col-4 text-wrap">
                                @if (is_array($log->new_data))
                                @forelse ($log->new_data as $index => $data)
                                <div class="{{ $log->new_data != '' && $loop->index == 0 ? '' : 'mt-1' }}   {{ $log->old_data != '' && is_array($log->old_data) && $log->old_data != [] && $log->old_data[$index] !== $data ? 'bg-yellow py-1 rounded px-1' : '' }}">
                                    {{ $data }}
                                </div>
                                @empty
                                <p>
                                    {{ __('header.No Data') }}
                                </p>
                                @endforelse
                                @else
                                {{ $log->new_data }}
                                @endif
                            </td>
                            @can('Delete Logs')
                            <td>
                                <a href="" class="btn btn-danger btn-sm px-2 pt-1 mt-2  rounded-2" wire:click.prevent="delete({{ $log->id }})">
                                    <i class="fas fa-trash-alt mx-2"></i>
                                    {{ __('header.delete') }}
                                </a>
                            </td>
                            @endcan

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                {{ __('header.No Data') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($user_logs != null)
        <div class="row mt-1">
            <div class="col-12">
                {{ $user_logs->links() }}
            </div>
        </div>
        @endif
    </div>
</div>