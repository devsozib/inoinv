@extends('frontend.layouts.app')
@section('content')
<div id="main-module-container">
   <div class="breadcrumb-title-wrapper">
      <div class="breadcrumb-title">
         <h1 class="heading-title page-title">লেখক</h1>
      </div>
   </div>
   <div id="container" class="page-template default-template">
      <div id="content" class="container" role="main">
         <div id="main">
            <div id="main_content" class="col-sm-24">
               <div class="">
                  <div class="content" role="main">
                     <div class="row">
                        <div class="col-md-24" style="margin-bottom: 20px">
                           <div class="cls_search" style="width:400px;">
                              <form action="" class="form-inline" method="get">
                                 <div class="search-box">
                                    <input type="text" id="cat_search" placeholder="লেখক অনুসন্ধান করুন" value="" name="q" class="form-control ui-autocomplete-input" autocomplete="off" style="float: left;color:#666666">
                                    <button type="submit" style="float: left;background-color: #f23534;padding: 11px 3px 10px 14px;border-radius: 0px 5px 5px 0px;border: none" class="btn btn-default"><i class="fa fa-search" style="color: white;"></i></button>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                     <ul class="archive-product-subcategories" style="float:left;width:100%">
                        @foreach ($libWriters as $key => $value)
                            <li class="author-list product-category product col-sm-6 first">
                                <a href="{{ route('authorWiseProducts',$key) }}" class="author_name">
                                <h3>
                                    {{ $value }} 
                                </h3>
                                </a>
                            </li>
                        @endforeach
                     </ul>
                     <div class="archive-product-before-loop">
                        <div class="woocommerce-notices-wrapper"></div>
                     </div>
                     <div class="archive-product-after-loop">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <script type="text/javascript">
      jQuery(document).ready(function(){
      			var prod_cat_column =  4;
      			var span_class = "col-sm-"+(24/prod_cat_column);
      			jQuery(".archive-product-subcategories li.product-category").addClass(span_class);
      			jQuery(".archive-product-subcategories li.product-category").removeClass('first').removeClass('last');
      			jQuery(".archive-product-subcategories li.product-category").each(function(index, ele){
      				if(index%prod_cat_column==0)
      					jQuery(this).addClass("first");
      				if(index%prod_cat_column== (prod_cat_column-1))
      					jQuery(this).addClass("last");	
      			});
      		});
   </script>
</div>
@endsection