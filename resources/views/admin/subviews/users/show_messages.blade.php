@extends('admin.main_layout')

@section('subview')

    <script src="{{url("/public_html/ckeditor/adapters/jquery.js")}}" type="text/javascript"></script>

    <div class="panel panel-info">
        <div class="panel-heading">
            Messages For User: {{$user->first_name . ' ' . $user->last_name}}
        </div>
        <div class="panel-body">
            @foreach($chats as $message)
                @if($message->from_user_id == $user->user_id)
                    <div class="alert alert-info">
                        <strong>
                            {{$user->first_name}}:
                        </strong>
                        {!! strip_tags($message->message) !!}
                    </div>
                @else
                    <div class="alert alert-warning">
                        <strong>
                            Invstoc:
                        </strong>
                        {!! strip_tags($message->message) !!}
                    </div>
                @endif
                @endforeach
        </div>
    </div>


@endsection
