
@extends('frontend.layouts.app') 
@section('content') 

<style>
    nav.woocommerce-MyAccount-navigation ul {
  list-style-type: none;
  padding-left: 0;
  font-size: 17px;
  line-height: 26px;
}
ol, ul {
  margin-bottom: 20px;
  padding-left: 0;
  margin-top: 0;
}

nav.woocommerce-MyAccount-navigation ul li.is-active {
  background-color: rgba(0,0,0,.1);
}
nav.woocommerce-MyAccount-navigation ul li {
  padding: 8px 20px;
  background-color: rgba(0,0,0,.05);
  border-bottom: 1px solid rgba(0,0,0,.05);
  list-style: none;
}
ul li, ol li {
  /* color: #666; */
}
ul li {
  list-style: square inside none;
}
ol li, ul li {
  line-height: 24px;
  list-style-position: inside;
}
nav.woocommerce-MyAccount-navigation ul li:not(.is-active):hover {
  background-color: rgba(0,0,0,.07);
}
nav.woocommerce-MyAccount-navigation ul li {
  padding: 8px 20px;
  background-color: rgba(0,0,0,.05);
  border-bottom: 1px solid rgba(0,0,0,.05);
  list-style: none;
}
ul li, ol li {
  /* color: #666; */
}
</style>

<div id="main-module-container">
  <div class="slideshow-wrapper main-slideshow wd_wide" style="min-height: 48px;">
    <div class="slideshow-sub-wrapper"></div>
  </div>
  <div class="breadcrumb-title-wrapper">
    <div class="breadcrumb-title">
      <h1 class="heading-title page-title">আমার অ্যাকাউন্ট</h1>
    </div>
  </div>
  <div id="container" class="page-template default-template wd_wide">
    <div id="content" class="container" role="main">
      <div id="main">
        <div id="container-main" class="col-sm-24">
          <div class="main-content">
            <article id="post-9" class="post-9 page type-page status-publish hentry">
              <div class="entry-content-post">
                <div class="woocommerce">
                  <div class="row">
                    <div class="col-sm-5">
                      @include('frontend.layouts.my_account_side_nave')
                    </div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-18 my-account-dashboard">
                        <div class="woocommerce-notices-wrapper"></div>
                        <form action="{{route('update_customer_profile')}}" method="post">
                            @csrf
                          <p class="form-row">
                            <label for="account_first_name">নাম <span class="required">*</span>
                            </label>
                            <input type="text" class="input-text" name="account_first_name" id="account_first_name" value="{{auth()->user()->name}}">
                          
                          <p class="form-row">
                            <label for="account_email">ইমেইল <span class="required">*</span>
                            </label>
                            <input type="email" class="input-text" name="account_email" id="account_email" value="{{auth()->user()->email}}">
                          </p>
                          <p class="form-row">
                            <label for="password_current">বর্তমান পাসওয়ার্ড (যদি পরিবর্তন করতে না চান তাহলে খালি রাখুন)</label>
                            <input type="password" class="input-text" name="password_current" id="password_current">
                          </p>
                          <p class="form-row">
                            <label for="password_1">নতুন পাসওয়ার্ড (যদি পরিবর্তন করতে না চান তাহলে খালি রাখুন)</label>
                            <input type="password" class="input-text" name="password" id="password_1">
                          </p>
                          <p class="form-row">
                            <label for="password_2">নতুন পাসওয়ার্ডটি আবার লিখুন </label>
                            <input type="password" class="input-text" name="confirm_password" id="password_2">
                          </p>
                          <div class="clear"></div>
                          <p>
                            <input type="submit" class="button" name="save_account_details" value="সংরক্ষন করুন ">
                          </p>
                        </form>
                    </div>
                  </div>
                </div>
              </div>
              <footer class="entry-meta"></footer>
            </article>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>















@endsection