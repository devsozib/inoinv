@extends('frontend.layouts.app')

@section('content')


    <div class="content container-fluid">

				
					<!-- Page Header -->
					<div class="page-header">
						<div class="content-page-header">
							<h5>Permission List</h5>
						</div>	
					</div>
					<!-- /Page Header -->
					
					<div class="row">
						<div class="col">
							<div class="card">
								<div class="card-header">
                  <div class="form-check form-switch form-switch-right form-switch-md d-flex justify-content-end">
                    <a href="{{ route('permission.create') }}" class="btn btn-info">Create Permissions</a>
                  </div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table class="table mb-0">
											<thead>
												<tr>
													<th>SL</th>
													<th>Name</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
                      @foreach ($permissions as $item)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>                                    
                                    <td>{{ $item->name }}</td>                     
                                     <td class="text-center">
                                        <a href="{{ route('permission.edit', $item->id) }}" class="btn btn-sm btn-primary me-1 waves-effect waves-light">
                                            <i class="fe fe-edit text-white"></i>
                                        </a>
                                        
                                        <button type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#myModal{{ $item->id }}"
                                                class="btn btn-sm btn-danger waves-effect waves-light">
                                            <i class="fe fe-trash-2 text-white"></i>
                                        </button>
                                    </td>

                                    <!-- Default Modals -->


                                    <div id="myModal{{ $item->id }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-modal="true" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="myModalLabel">Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                                </div>
                                                <div class="modal-body">
                                                  Are you sure you want to delete this Permissions:
                                                  <strong
                                                      style="color: darkorange">{{ $item->name }}</strong>
                                                  ?
                                                </div>
                                                <div class="modal-footer">

                                                    <form
                                                        action="{{ route('permission.destroy',$item->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-default">Delete</button>

                                                    </form>
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                </div>

                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div>


                                    
                                    
                                    <!-- /.modal -->


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
