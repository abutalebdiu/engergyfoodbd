@if ($pagination->hasPages())
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-start">
            @if ($pagination->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">@lang('Previous')</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $pagination->previousPageUrl() }}" rel="prev">@lang('Previous')</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($pagination->getUrlRange(1, $pagination->lastPage()) as $page => $url)
                @if ($page == $pagination->currentPage())
                    <li class="page-item active">
                        <span class="page-link">
                            {{ $page }}
                            <span class="sr-only">(@lang('current'))</span>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($pagination->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $pagination->nextPageUrl() }}" rel="next">@lang('Next')</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">@lang('Next')</span>
                </li>
            @endif
        </ul>
    </nav>
@endif