@extends('admin.layout.app')

@section('content')
<style>
    #draggablePanelList .panel-heading {
        cursor: move;
    }
    #draggablePanelList2 .panel-heading {
        cursor: move;
    }

    .list-unstyled {
        padding-left: 0;
        list-style: none;
    }

    .panel-info {
        border-color: #bce8f1 !important; 
    }
    .panel {
        margin-bottom: 20px;
        background-color: #fff;
        border: 2px solid transparent;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
        box-shadow: 0 1px 1px rgba(0,0,0,.05);
    }

    .panel-info > .panel-heading {
        color: #31708f;
        background-color: #d9edf7;
        border-color: #bce8f1;
    }
    .panel-heading {
        padding: 10px 15px;
        border-bottom: 1px solid transparent;
        border-bottom-color: transparent;
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
        font-size: 15px;
    }
    .panel-body {
        padding: 10px;
    }
</style>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card p-3">
                        

                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1"></h4>
                        </div>

                        <div class="card-body ">
                            <ul id="draggablePanelList" class="list-unstyled">
                                @foreach ($items as $item)
                                    <li class="panel panel-info" style="border-color:{{$item->for_book_or_product == '1' ? '#69ce9f' : ($item->for_book_or_product == '2' ? '#6982ce' : '#cb69ce')}};" data-id="{{$item->id}}">
                                        <div class="panel-heading" style="background:{{$item->for_book_or_product == '1' ? '#69ce9f' : ($item->for_book_or_product == '2' ? '#6982ce' : '#cb69ce')}}; color: #fff;">{{getArrayData(bookOrProduct(),$item->for_book_or_product)}} {{$item->item_type != '6' ? '››' : ''}} {{getArrayData(itemType(),$item->item_type)}} ›› {{$item->name}}</div>
                                        <div class="panel-body">
                                            <table style="width:100%;">
                                                <tr>
                                                    <td>
                                                        <label for="">Title</label>
                                                        <input onchange="updateTitle({{$item->id}}, this.value);" type="text" class="form-control" value="{{$item->title}}">
                                                    </td>
                                                    <td>
                                                        <label for="">More Button Title</label>
                                                        <input onchange="updateMoreButtonTitle({{$item->id}}, this.value);" type="text" class="form-control" value="{{$item->more_button_title}}">
                                                    </td>
                                                    <td>
                                                        @if($item->item_type != '6' || ($item->item_type == '6' && $item->item_id == '4'))
                                                            <label for="">View Type</label><br>
                                                            <select onchange="updateViewType({{$item->id}}, this.value)" name="" class="form-control" style="" id="">
                                                                @foreach (viewType() as $key => $type)
                                                                    <option value="{{$key}}" {{$item->view_type == $key ? 'selected' : ''}}>{{ $type}}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
   
    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha256-7s5uDGW3AHqw6xtJmNNtr+OBRJUlgkNJEo78P4b0yRw= sha512-nNo+yCHEyn0smMxSswnf/OnX6/KwJuZTlNZBjauKhTK0c+zT+q5JOCx0UFhXQ6rJR9jg6Es8gPuD2uZcYDLqSw==" crossorigin="anonymous"> -->

    @section('script')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        jQuery(function($) {
            var panelList = $('#draggablePanelList');

            panelList.sortable({
                handle: '.panel-heading', 
                update: function() {
                    $('.panel', panelList).each(function(index, elem) {
                        var $listItem = $(elem),
                            newIndex = $listItem.index();
                    });
                    updateOrder();
                }
            });
        });

        function updateOrder(){
            var eles = document.getElementsByClassName("panel");
            var ids = [];
            for(i=0; i<eles.length; i++){
                ids[`${eles[i].dataset.id}`] = i+1;
            }
            $.post('{{route('settings.update_priyority')}}', {ids:JSON.stringify(ids), _token:'{{csrf_token()}}'}, function(data){
                flashMessage(data.type,data.message);
            });
        }

        function updateViewType(id, value){
            $.post('{{route('settings.update_view_type')}}', {id:id,type:value, _token:'{{csrf_token()}}'}, function(data){
                flashMessage(data.type,data.message);
            });
        }

        function updateTitle(id, value){
            $.post('{{route('settings.update_titel')}}', {id:id,title:value, _token:'{{csrf_token()}}'}, function(data){
                flashMessage(data.type,data.message);
            });
        }

        function updateMoreButtonTitle(id, value){
            $.post('{{route('settings.update_more_button_titel')}}', {id:id,more_button_title:value, _token:'{{csrf_token()}}'}, function(data){
                flashMessage(data.type,data.message);
            });
        }

    </script>
    
    @endsection
@endsection