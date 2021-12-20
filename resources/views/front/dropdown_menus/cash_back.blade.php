<label class="link-anew-groub get_my_cash_back_accounts dropdown" data-toggle="dropdown"
       data-target=".cash_back_div" title="{{show_content($user_homepage_keywords,"cashback_header")}}">
    <i class="fa fa-money" aria-hidden="true"></i>
</label>

<div class="box-hermonic cash-back dropdown-menu stop_close_dropdown" style="width: 482px;">
    <i class="fa fa-sort-asc right_m" aria-hidden="true"></i>
    <div class="col-md-12">
        <h1>{{show_content($user_homepage_keywords,"cashback_header")}}</h1>
    </div>

    <div class="cash_back_body">

        <div class="col-md-12 add_new_account_body">

            <div class="col-md-12">
                <h4>{{show_content($user_homepage_keywords,"add_new_cashback_header")}}</h4>
            </div>

            <div class="col-md-6">
                <div class="creat-agroub">
                    <input type="text" class="form-control account_number" placeholder="{{show_content($user_homepage_keywords,"account_number_label")}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="creat-agroub">
                    <select name="page_id" class="form-control load_other_companies">

                    </select>
                </div>
            </div>
            <div class="col-md-2" style="margin-top: 10px;">
                <a class="btn btn-success add_new_account add-gorub" data-account_id="0">{{show_content($general_static_keywords,"save_btn")}}</a>
            </div>

        </div>

        <div class="box-menu-hover-2">

            <ul class="work-shop my_cash_back_accounts scroll" style="overflow-y: scroll;max-height: 200px;width: 100%">

            </ul>

            <div class="col-md-12">
                <div class="all-items">
                    <div class="col-md-4" style="margin-top: 10px;float: left;cursor: pointer;">
                        <input type="checkbox" class="form-control check_all_account_items">
                    </div>

                    <div class="col-md-4" style="margin-top: 10px;float: right;">
                        <label class="total_cash_back_balance">0.00</label>
                        <button class="btn btn-success request_to_withdraw">{{show_content($user_homepage_keywords,"withdraw_btn")}}</button>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

