@if ($message = Session::get('success'))
<div class="alert alert-primary alert-dismissible fade show" role="alert">
    <strong>{{$message}}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@elseif ($message = Session::get('warning'))

@elseif ($message = Session::get('info'))

@elseif ($message = Session::get('static'))

@elseif ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    Check the following errors :
    @foreach ($errors->all() as $error)
    <br><strong>{{ $error }}</strong>
    @endforeach
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@elseif ($message = Session::get('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- FOR JAVASCRIPT --}}

<div id="alert-success" class="alert alert-primary alert-dismissible fade show d-none" role="alert">
    <strong id="alert-success-message"></strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="document.getElementById('alert-success').classList.add('d-none')"></button>
</div>

<div id="alert-danger" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
    <strong id="alert-danger-message"></strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="document.getElementById('alert-danger').classList.add('d-none')"></button>
</div>