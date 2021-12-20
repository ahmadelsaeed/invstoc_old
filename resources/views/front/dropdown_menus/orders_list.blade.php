<label class="link-anew-groub dropdown orders_list_btn"
       data-toggle="dropdown" data-target=".orders_list_div"
       title="Orders List">
    <i class="fa fa-shopping-bag" aria-hidden="true"></i>
</label>

<div class="box-hermonic dropdown-menu stop_close_dropdown">
    <i class="fa fa-sort-asc right_m" aria-hidden="true"></i>
    <div class="col-md-12">
        <h1 class="left_sidebar_header your_orders_list_header">
            {{show_content($user_homepage_keywords,"your_orders_list")}}
            (<span class="orders_list_items">0</span>)
        </h1>
    </div>

    <ul class="work-shop orders_list_ul sidebar_ul_height scroll">


    </ul>

    <textarea class="form-control orders_list_post_text hide_div"
              placeholder="{{show_content($post_keywords,"write_your_post_label")}}"
              style="resize: vertical;border: 1px solid cadetblue;" cols="3" rows="3"></textarea>
    <button class="btn btn-info make_orders_list_post" disabled>{{show_content($post_keywords,"post_btn")}}</button>

</div>

