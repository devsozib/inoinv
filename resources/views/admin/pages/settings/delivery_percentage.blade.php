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
                        <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#myModal">Add Delivery Percentage</button>
                        <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <form action="{{route('delivery_percentage.store')}}" method="post">
                                @csrf
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="myModalLabel">Add Delivery Percentage</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <label for="min_amount" class="form-label">Minimun Amount</label>
                                                <input type="text" class="form-control" id="min_amount" name="min_amount" placeholder="Amount">
                                            </div>
                                            <div>
                                                <label for="charge_percentage" class="form-label">Charge Deduct Percentage</label>
                                                <input type="text" class="form-control" id="charge_percentage" name="charge_percentage" placeholder="Percentage">
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
                            <h4 class="card-title mb-0 flex-grow-1">Delivery Percentage List</h4>
                        </div>

                        <div class="card-body">
                            <table class="table table-stripedw-100">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Minimum Amount</th>
                                        <th scope="col">Percentage</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $delivery)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td>{{$delivery->min_amount}}</td>
                                        <td>{{$delivery->charge_percentage}}</td>
                                        <td>{{$delivery->status == '1' ? 'Active' : 'Deactive'}}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#ctegory{{$delivery->id}}">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            <div id="ctegory{{$delivery->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <form action="{{ route('delivery_percentage.update', $delivery->id) }}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="myModalLabel">Update Delivery Percentage</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div>
                                                                    <label for="min_amount" class="form-label">Minimun Amount</label>
                                                                    <input type="text" class="form-control" id="min_amount" name="min_amount"value="{{$delivery->min_amount}}" placeholder="Delivery Charge">
                                                                </div>
                                                                <div>
                                                                    <label for="charge_percentage" class="form-label">Charge Deduct Percentage</label>
                                                                    <input type="text" class="form-control" value="{{ $delivery->charge_percentage }}" id="charge_percentage" name="charge_percentage" placeholder="Percentage">
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