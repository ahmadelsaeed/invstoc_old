
<?php if(isset($all_brokers) && is_array($all_brokers) && count($all_brokers)): ?>
    @include("blocks.brokers_filter_block")

<?php else: ?>

<?php if(is_object($current_user)): ?>

    <?php if(isset($load_user_dna) && $load_user_dna): ?>
        @include("blocks.user_right_top_block")
    <?php endif; ?>

    @include("blocks.activities_dropdown_block")
<?php endif; ?>

<?php endif; ?>


<?php if(isset($get_ads['homepage_left_script1'])): ?>
<div class="ui-block remove_back_color">
    {!! get_adv($get_ads['homepage_left_script1']->all(),"295px","auto") !!}
</div>
<?php endif; ?>

<?php if(isset($get_ads['homepage_left_script2'])): ?>
<div class="ui-block remove_back_color">
    {!! get_adv($get_ads['homepage_left_script2']->all(),"295px","auto") !!}
</div>
<?php endif; ?>


<?php if(isset($get_ads['homepage_body1'])): ?>
<div class="ui-block remove_back_color">
    {!! get_adv($get_ads['homepage_body1']->all(),"295px","120px") !!}
</div>
<?php endif; ?>


<?php if(isset($relted_article)): ?>

<div class="ui-block">
	<div class="ui-block-title">
		<h6 class="title">{{show_content($user_homepage_keywords ,"related_article")}} </h6>
	</div>
	<!-- W-Blog-Posts -->

	<ul class="widget w-blog-posts">
		<?php foreach($relted_article as $key => $art_obj):
				$link = url("articles/$art_obj->page_id");
		 ?>
			<li>
				<article class="hentry post">
					<a href="{{$link}}" class="h4">
					 {!! split_word_into_chars_ar_without_more_link($art_obj->page_title,70," ...") !!}
					</a>
				</article>
			</li>
		<?php endforeach; ?>
	</ul>
	<!-- .. end W-Blog-Posts -->
</div>
<?php endif; ?>


<?php if(isset($relted_news)): ?>

<div class="ui-block">
	<div class="ui-block-title">
		<h6 class="title">{{show_content($user_homepage_keywords ,"related_news")}} </h6>
	</div>
	<!-- W-Blog-Posts -->

	<ul class="widget w-blog-posts">
		<?php foreach($relted_news as $key => $news_obj):
				$link = url("news/$news_obj->page_id");
		 ?>
			<li>
				<article class="hentry post">
					<a href="{{$link}}" class="h4">
					 {!! split_word_into_chars_ar_without_more_link($news_obj->page_title,70," ...") !!}
					</a>
				</article>
			</li>
		<?php endforeach; ?>
	</ul>
	<!-- .. end W-Blog-Posts -->
</div>
<?php endif; ?>
