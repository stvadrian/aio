<li class="nav-item d-flex align-items-center ms-2">
    <div class="align-items-center input-group-prepend lang-menu">
        <div>
            @if ($lang == 'id')
                <img src='{{ asset('images/indonesia-circle.webp') }}' alt="ID" width="25">
            @else
                <img src='{{ asset('images/english-circle.webp') }}' alt="EN" width="25">
            @endif
        </div>
        <div class="dropdown ms-1">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                {{ Config::get('languages')[$lang] }}
            </a>
            @foreach (Config::get('languages') as $langs => $language)
                @if ($langs != App::getLocale())
                    <a href="{{ route('lang.switch', $langs) }}"
                        class="dropdown-menu lang-choice p-2 px-3">{{ $language }}</a>
                @endif
            @endforeach
        </div>
    </div>
</li>
