<li>
    <h4 class="text-center">{{$workshops_li}}</h4>
</li>
<?php foreach ($all_workshops as $key => $workshop_obj): ?>
@include("front.subviews.groups_workshops.workshop_li")
<?php endforeach; ?>
