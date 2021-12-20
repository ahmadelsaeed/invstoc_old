 <li class="comment-item">
						<div class="post__author author vcard inline-items">
							<img src="{{user_default_image($current_user)}}" alt="author">
				
							<div class="author-date">
								<a class="h6 post__author-name fn" href="{{url("user/posts/all/$current_user->user_id")}}">{{$current_user->full_name}}</a>
								<div class="post__date">
									<time class="published" datetime="2017-03-24T18:18">
										 {{\Carbon\Carbon::createFromTimestamp(strtotime($comment->created_at))->diffForHumans()}}
									
                                    </time>
								</div>
                            </div>

                            {{-- <div class="more comment_options_33">
                                <svg class="olymp-three-dots-icon">
                                    <use xlink:href="http://localhost/invstoc/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use>
                                </svg>
                                <ul class="more-dropdown">
                                    <li><a class="edit_comment" data-commentid="36" href="#">Edit</a></li>
                                    <li><a class="delete_comment" data-commentid="37" href="#">Delete</a></li>
                                </ul>
                            </div> --}}
				
							<a href="#" class="more">
								<svg class="olymp-three-dots-icon">
									<use xlink:href="#olymp-three-dots-icon"></use>
								</svg>
							</a>
						</div>

                        <p>{{$comment->comment}}</p>
                    </li>