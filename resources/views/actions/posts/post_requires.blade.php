<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="{{url("/public_html/jscode/config.js")}}"></script>
<!-- hidden csrf -->
<script src="{{url('/public_html/admin')}}/js/bootstrap.min.js"></script>

<input type="hidden" class="csrf_input_class" value="{{csrf_token()}}">
<!-- /hidden csrf -->
<!-- hidden base url -->
<input type="hidden" class="url_class" value="<?= url("/") ?>">
<!-- /hidden base url -->


<script src="{{url("/public_html/jscode/actions/add_post.js")}}"></script>
<script src="{{url("/public_html/jscode/actions/posts_utility.js")}}"></script>
<script src="{{url("/public_html/jscode/actions/post_actions.js")}}"></script>
<link href="{{url('/public_html/front/')}}/css/add_post.css" rel='stylesheet' type='text/css'/>