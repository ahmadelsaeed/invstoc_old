<div class="modal fade add_order_closed_price_modal_{{$post_data->post_id}}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{show_content($post_keywords,"add_closed_price")}}</h4>
            </div>
            <div class="modal-body row">

                <div class="col-md-12">
                    <p>{{show_content($post_keywords,"expected_price_label")}} {{$post_data->expected_price}}</p>
                </div>

                <div class="col-md-12 add_order_closed_price_form">
                    <div class="col-md-6">
                        <input type="number" required min="0" step="0.000000001" class="form-control closed_price" >
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary add_order_closed_price_btn" data-postid="{{$post_data->post_id}}">{{show_content($post_keywords,"add_closed_price")}}</button>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>