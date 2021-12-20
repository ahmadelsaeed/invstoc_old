{{--  news block  --}}
<?php if(count($latest_news)): ?>
	<?php foreach($latest_news as $key => $news_obj): ?>
	 	@include("blocks.homepage_news_block")
	<?php endforeach; ?>
<?php endif; ?>


{{-- users to follow  --}}
<?php if(isset($random_users_indexes) && count($random_users_indexes)): ?>
	@include("blocks.who_to_follow_block")
<?php endif; ?>


<?php if(isset($get_ads['homepage_body2'])): ?>
	<div class="ui-block remove_back_color">
	    {!! get_adv($get_ads['homepage_body2']->all(),"295px","120px") !!}
	</div>
<?php endif; ?>

<?php if(isset($get_ads['homepage_right_script1'])): ?>
<div class="ui-block remove_back_color">
	{!! get_adv($get_ads['homepage_right_script1']->all(),"295px","120px") !!}
</div>
<?php endif; ?>
