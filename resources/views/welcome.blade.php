@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body">
                    Your Application's Landing Page.

                    <form method="POST" action="{{url('/try_upload')}}" enctype="multipart/form-data">

                        {{csrf_field()}}

                        <input type="text" name="title[]" id="">
                        <input type="text" name="alt[]" id="">
                        <input type="file" name="file[]" >
                        <input type="file" name="file[]" >
                        <button type="submit" name="submit">Submit</button>

                    </form>

                    <form method="POST" action="{{url('/try_filter')}}" enctype="multipart/form-data">

                        {{csrf_field()}}

                        <input type="text" name="title" id="">
                        <?php if ($errors->has("title")): ?>
                            <li>{{$errors->first("title")}}</li>
                        <?php endif ?>
                        <button type="submit" name="submit">Submit</button>

                        <?php //if (count($errors)): ?>
                            <?php //foreach ($errors->all() as $key => $item): ?>
{{--                            <li>{{$item}}</li>--}}
                            <?php //endforeach; ?>
                        <?php //endif ?>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
