@if ($message = Session::get('success'))
<div id="flash-success" class="alert alert-success alert-block" style="margin: 10px;">
    <button type="button" class="btn btn-sm btn-danger text-white" onclick="document.getElementById('flash-success').classList.add('d-none')">×</button>
    <strong>{{ $message }}</strong>
</div>
<script>
    setTimeout(function () {
        document.getElementById('flash-success').style.display = 'none';
    }, 3000);
</script>
@elseif ($message = Session::get('warning'))
<div id="flash-warning" class="alert alert-warning alert-block" style="margin: 10px;">
    <button type="button" class="btn btn-sm btn-danger text-white" onclick="document.getElementById('flash-warning').classList.add('d-none')">×</button>
    <strong>{{ $message }}</strong>
</div>
<script>
    setTimeout(function () {
        document.getElementById('flash-warning').style.display = 'none';
    }, 3000);
</script>
@elseif ($message = Session::get('info'))
<div id="flash-info" class="alert alert-info alert-block" style="margin: 10px;">
    <button type="button" class="btn btn-sm btn-danger text-white" onclick="document.getElementById('flash-info').classList.add('d-none')">×</button>
    <strong>{{ $message }}</strong>
</div>
<script>
    setTimeout(function () {
        document.getElementById('flash-info').style.display = 'none';
    }, 3000);
</script>
@elseif ($message = Session::get('static'))
<div id="flash-success-static" class="alert alert-success alert-important" style="margin: 10px;">
    <button type="button" class="btn btn-sm btn-danger text-white" onclick="document.getElementById('flash-success-static').classList.add('d-none')">×</button>
    <strong>{{ $message }}</strong>
</div>
@elseif ($errors->any())
<div id="flash-danger-all" class="alert alert-danger alert-block" style="margin: 10px;">
    <button type="button" class="btn btn-sm btn-danger text-white" onclick="document.getElementById('flash-danger-all').classList.add('d-none')">×</button>
    Check the following errors :
    @foreach ($errors->all() as $error)
    <br><strong>{{ $error }}</strong>
    @endforeach
</div>
<script>
    setTimeout(function () {
        document.getElementById('flash-danger-all').style.display = 'none';
    }, 3000);
</script>
@elseif ($message = Session::get('error'))
<div id="flash-danger" class="alert alert-danger alert-block" style="margin: 10px;">
    <button type="button" class="btn btn-sm btn-danger text-white" onclick="document.getElementById('flash-danger').classList.add('d-none')">×</button>
    <strong>{{ $message }}</strong>
</div>
<script>
    setTimeout(function () {
        document.getElementById('flash-danger').style.display = 'none';
    }, 3000);
</script>
@endif







{{-- FOR JAVASCRIPT --}}


<div id="alert-success" class="alert alert-success alert-block d-none" style="margin: 10px;">
    <button type="button" class="btn btn-sm btn-danger text-white" onclick="document.getElementById('alert-success').classList.add('d-none')">×</button>
    <strong id="alert-success-message"></strong>
</div>
<div id="alert-warning" class="alert alert-warning alert-block d-none" style="margin: 10px;">
    <button type="button" class="btn btn-sm btn-danger text-white" onclick="document.getElementById('alert-warning').classList.add('d-none')">×</button>
    <strong id="alert-warning-message"></strong>
</div>
<div id="alert-info" class="alert alert-info alert-block d-none" style="margin: 10px;">
    <button type="button" class="btn btn-sm btn-danger text-white" onclick="document.getElementById('alert-info').classList.add('d-none')">×</button>
    <strong id="alert-info-message"></strong>
</div>

<div id="alert-danger" class="alert alert-danger alert-block d-none" style="margin: 10px;">
    <button type="button" class="btn btn-sm btn-danger text-white" onclick="document.getElementById('alert-danger').classList.add('d-none')">×</button>
    <strong id="alert-danger-message"></strong>
</div>