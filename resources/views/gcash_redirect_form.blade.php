<form id="gcashPaymentForm" action="{{ route('pay.gcash.link') }}" method="POST">
    @csrf
    @foreach($data as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
</form>
<script>
    document.getElementById('gcashPaymentForm').submit();
</script>
