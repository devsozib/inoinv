@extends('admin.layout.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            @if(session('sweet_alert'))
                <script>
                    Swal.fire({
                        icon: '{{ session('sweet_alert.type') }}',
                        title: '{{ session('sweet_alert.title') }}',
                        text: '{{ session('sweet_alert.text') }}',
                    });
                </script>
            @endif
            <div class="row">
                <div class="col">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#myModal">Add Delivery Charge</button>
                        <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <form action="{{route('delivery_charges.store')}}" method="post">
                                @csrf
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="myModalLabel">Add Delivery Charge</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <label for="basiInput" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="Delivery Charge" name="name" placeholder="Name">
                                            </div>
                                            <div>
                                                <label for="basiInput" class="form-label">Amount</label>
                                                <input type="text" class="form-control" id="Delivery Charge" name="amount" placeholder="Amount">
                                            </div>                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary ">Save</button>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Delivery Charge List</h4>
                        </div>

                        <div class="card-body">
                            <table class="table table-stripedw-100">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $delivery)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td>{{$delivery->name}}</td>
                                        <td>{{$delivery->amount}}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#ctegory{{$delivery->id}}">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            <div id="ctegory{{$delivery->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <form action="{{ route('delivery_charges.update', $delivery->id) }}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="myModalLabel">Add Delivery Charge</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div>
                                                                    <label for="basiInput" class="form-label">Delivery Charge</label>
                                                                    <input type="text" class="form-control" id="Delivery Charge" name="name"value="{{$delivery->name}}" placeholder="Delivery Charge">
                                                                </div>
                                                                <div>
                                                                    <label for="basiInput" class="form-label">Amount</label>
                                                                    <input type="text" class="form-control" value="{{ $delivery->amount }}" id="Delivery Charge" name="amount" placeholder="Amount">
                                                                </div> 
                                                                
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary ">Save</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </form>
                                            </div>


                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection