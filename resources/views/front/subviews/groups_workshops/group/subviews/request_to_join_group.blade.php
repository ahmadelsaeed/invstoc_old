@extends('front.subviews.groups_workshops.group.group_main_layout')

@section('group_subview')

   <div class="alert alert-info" style="text-align: center;">

       <?php if(isset($is_requested_before) && $is_requested_before == false): ?>
           <form action="{{url(strtolower(session('language_locale', 'en'))."/request_to_join_group/$group_obj->group_id")}}" method="POST">

               {{csrf_field()}}
               <button type="submit" class="btn btn-success">{{show_content($group_keywords,"request_to_join_group_btn")}}</button>

           </form>

           <?php else: ?>
           <b>
               {{show_content($group_keywords,"wait_admin_approval_info")}}
           </b>

       <?php endif; ?>


   </div>

@endsection
