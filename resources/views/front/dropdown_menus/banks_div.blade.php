
<label class="link-anew-groub dropdown" data-toggle="dropdown"
       data-target=".banks_div" title="{{show_content($user_homepage_keywords,"bank_accounts_header")}}">
    <i class="fa fa-cc-visa" aria-hidden="true"></i>
</label>


<div class="box-hermonic cash-back dropdown-menu stop_close_dropdown">
    <i class="fa fa-sort-asc right_m" aria-hidden="true"></i>
    <div class="col-md-12">
        <h1>{{show_content($user_homepage_keywords,"bank_accounts_header")}}</h1>
    </div>

    <div class="col-md-12">
        <?php foreach($banks->slider1->imgs as $key => $bank_img): ?>

        <div class="col-md-6">
            <img src="{{get_image_or_default($bank_img->path)}}" width="100%" height="90px">
        </div>

        <?php endforeach; ?>
    </div>

</div>