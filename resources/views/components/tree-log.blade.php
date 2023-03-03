<div>
    @foreach ($array as $key => $value )

    <details class="tree-nav__item is-expandable">
        <summary class="tree-nav__item-title">{{ $key }}</summary>
        @if (is_array($value))
        <x-tree-log :array="$value" />
        @else
        <div class="tree-nav__item">
            @if (is_array($value))
            @if (!is_numeric($key))
            {{ $key }}
            @endif
            <x-tree-log :array="$value" />
            @else
            @endif
        </div>
        @endif
    </details>
    <div class="tree-nav__item">

    </div>

    </details>
    @endforeach
</div>