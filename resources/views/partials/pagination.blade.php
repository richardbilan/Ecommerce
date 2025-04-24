@if ($orders->hasPages())
    <div class="pagination-wrapper">
        {{ $orders->appends(request()->except('page'))->links() }}
    </div>
@endif
