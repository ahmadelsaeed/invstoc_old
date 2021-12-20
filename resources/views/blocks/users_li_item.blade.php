<li>
    <a href="{{url("user/posts/all/$user_obj->user_id")}}">
        <img src="{{get_image_or_default($user_obj->logo_path)}}" width="50" height="50" class="img-circle">
    </a>
    <a href="{{url("user/posts/all/$user_obj->user_id")}}">
        {{$user_obj->full_name}}
    </a>
</li>