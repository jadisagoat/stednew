
    <!-- BREADCRUMB
    ============================================= -->
    <div id="breadcrumb" class="division">
        <div class="container">
            <div class="row">

                <!-- BREADCRUMB NAV -->
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">News</li>
                        </ol>
                    </nav>
                </div> 

            </div>	 <!-- End row -->
        </div>	<!-- End container -->		
    </div>	<!-- END BREADCRUMB -->	
 
 
              
        
             
				<!-- SINGLE POST
				============================================= -->
				<section id="single-post" class="wide-80 single-post-section division">
					<div class="container">


					
						<div class="row">
							<div class="col-lg-10 offset-lg-1">
								<div class="single-post-wrapper">
  

								
									<div class="single-post-title mb-35 text-center">

								
										<h3 class="h3-md"><?php echo $news['title']; ?></h3>

								
										<!-- <div class="single-post-data">
											<p>Posted by Joel Peterson on March 15, 2020 in <a href="#"> Ideas & Opinions</a></p>
										</div> -->

									</div>	


								
									<div class="single-post-txt">

								
										<p><?php echo $news['small_description']; ?>
										</p>

									</div>


								
						 			<div class="post-inner-img">
                                     <?php   if(is_file("uploads/news/".$news['image']))
								                {   ?> 
										<img class="img-fluid" src="<?php echo base_url(); ?>uploads/news/<?php echo $news['image'] ?>" alt="blog-post-image" />
                                        <?php } ?>		
									</div>


						
									<div class="single-post-txt">
                                   <p> <?php echo $news['description']; ?></p>
								 
							
										<!-- <div class="post-inner-img">
											<div class="embed-responsive embed-responsive-16by9">
								 				<iframe width="950" height="450" src="https://www.youtube.com/embed/7e90gBu4pas" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
								 			</div>
										</div> -->

								
										<!-- <h5 class="h5-lg">Vitae massa placerat vulputate</h5>

								
										<p>Nullam non scelerisque lectus. In at mauris vel nisl convallis porta at vitae dui. Nam lacus ligula,
										   vulputate molestie bibendum quis, aliquet elementum massa. Vestibulum ut sagittis odio. Ac massa lorem. Fusce eu cursus est. Fusce non nulla vitae massa placerat vulputate vel a purus. Aliqum  mullam blandit tempor sapien gravida donec
										</p>

								
										<ul class="txt-list mb-15">
												
											<li>Vitae auctor integer congue magna at pretium cubilia laoreet augue egestas luctus donec diam. 
												Curabitur ac dapibus libero. Quisque eu tristique neque ipsum porta cubilia laoreet augue egestas
											</li>

											<li>Sagittis congue augue egestas volutpat egestas magna vitae auctor eros congue magna nihil impedit 
												ligula risus. Mauris donec ociis et magnis sapien an sapien congue tempor gravida enim ipsum porta cubilia laoreet augue egestas luctus egestas volutpat
											    egestas magna suscipit egestas magna
											</li>

										</ul>

								
										<p>Curabitur ac dapibus libero quisque eu tristique neque sellus blandit tristique justo ut aliquam.
										   Aliquam vitae at molestie nunc sapien justo, aliquet non molestie sed, venenatis nec purus. Aliquam eget lacinia tincidunt massa justo. Quisque vel laoreet turpis. Urna augue, viverra a augue eget, dictum tempor diam. Sed pulvinar consectetur nibh, vel imperdiet varius viverra. Pellentesque ac massa lorem. Fusce 
										   eu cursus est. Fusce non nulla vitae massa placerat bulum tincidunt tincidunt massa, et porttitor justo
										   viverra a augue eget, dictum tempor diam
										</p>  -->

									</div>	


									<!-- <div class="row post-share-links d-flex align-items-center">

							
										<div class="col-md-9 col-xl-8 post-tags-list">
											<span><a href="#">eTreeks News</a></span>	
											<span><a href="#">Ideas & Opinions</a></span>
											<span><a href="#">Education Process</a></span>										
										</div>

								
										<div class="col-md-3 col-xl-4 post-share-list text-right">
											<ul class="share-social-icons text-center clearfix">														
												<li><a href="#" class="share-ico ico-facebook"><i class="fab fa-facebook-square"></i></a></li>
												<li><a href="#" class="share-ico ico-twitter"><i class="fab fa-twitter"></i></a></li>
												<li><a href="#" class="share-ico ico-like"><i class="far fa-bookmark"></i></a></li>			
											</ul>
										</div>

									</div> -->


<!-- 					
									<div class="author-senoff d-flex align-items-center">
										
								
										<img src="images/review-author-7.jpg" alt="author-avatar">

							
										<div class="author-senoff-txt">

									
											<h5 class="h5-xs">Published by</h5>
											<h5 class="h5-lg">Robert Coronado</h5>
											<p>Lorem ipsum dolor sit amet, consectetur elit eiusmod tempor sapien dolore incidide undo labore 
												dolore incididunt ut labore turpis vitae raesent varius efficitur ipsum primis cubilia laoreet 
												augue egestas luctus donec diam efficitur ipsum primis in cubilia laoreet augue egestas
											</p>

									
											<div class="author-follow-btn"><a href="#">Follow</a></div>

										</div>

									</div> -->


								
									<!-- <div class="other-posts">
										<div id="op-row" class="row d-flex align-items-center">
													
										
											<div class="col-md-4">
												<div class="prev-post mb-30 pr-45">								
													<h5 class="h5-xs"><i class="fas fa-arrow-left"></i> Previous Post</h5>
													<a href="single-post.html">Congue magna eTreeks purus pretium magnis</a>
												</div>
											</div>

									
											<div class="col-md-4 text-center">
												<div class="all-posts mb-30">		
													<a href="blog-listing.html"><i class="fas fa-th-large"></i></a>
												</div>
											</div>

								
											<div class="col-md-4 text-right">
												<div class="next-post mb-30 pl-45">		
													<h5 class="h5-xs">Next Post <i class="fas fa-arrow-right"></i></h5>
													<a href="single-post.html">8 neque dolor primis a libero tempus blandit</a>
												</div>
											</div>

										</div>		
									</div>	 -->


						
									<!-- <div class="post-comments">


							
										<h4 class="h4-sm">4 Comments</h4>	


								
										<div class="media mt-50">

									
										  	<img class="mr-3" src="images/post-author-1.jpg" alt="comment-avatar">

										 	<div class="media-body">

										 
										 		<div class="comment-meta">
										 			<h5 class="h5-sm mt-0 noto-font-700 purple-color">Robert C.</h5>						
											   		<span class="comment-date">5 days ago&#8194;- </span>
													<span class="btn-reply"><a href="#leave-comment" class="internal-link"><i class="fas fa-reply"></i> Reply</a></span>
												</div>
												
										
										   		<p>Etiam sapien sem magna at vitae pulvinar congue augue egestas pretium neque id viverra suscipit
										   		   egestas magna porta ratione, mollis risus lectus porta rutrum arcu aenean primis in augue luctus
										   		   efficitur ipsum primis in cubilia laoreet augue egestas luctus donec diam neque
										   		</p>

										   		<hr />

										   
											    <div class="media mt-3">

											    
											      	<a href="#" class="pr-3">
											        	<img src="images/post-author-2.jpg" alt="comment-avatar">
											      	</a>

											      	<div class="media-body">

											      
												 		<div class="comment-meta">
													   		<h5 class="h5-sm mt-0 noto-font-700 purple-color">David Smith</h5>
													   		<span class="comment-date">6 days ago&#8194;- </span>
															<span class="btn-reply"><a href="#leave-comment" class="internal-link"><i class="fas fa-reply"></i> Reply</a></span>
														</div>

											        
												   		<p>Etiam sapien sem magna at vitae pulvinar congue augue egestas pretium neque id viverra
												   		   suscipit porta ratione, mollis risus lectus porta aliquet lorem and purus mollis 
												   		</p>

											     	 </div>											      
											    </div>

										  	</div>
										</div>	


										<hr />


									
										<div class="media mt-40">

										
										  	<img class="mr-3" src="images/post-author-3.jpg" alt="comment-avatar">

										 	<div class="media-body">

										 	
										 		<div class="comment-meta">
											   		<h5 class="h5-sm mt-0 noto-font-700 purple-color">Becky Reed</h5>
											   		<span class="comment-date">13 days ago&#8194;- </span>
													<span class="btn-reply"><a href="#leave-comment" class="internal-link"><i class="fas fa-reply"></i> Reply</a></span>
												</div>
												
											
										   		<p>Porta ratione, mollis risus lectus porta rutrum arcu aenean primis in augue luctus neque purus ipsum
										   		   neque dolor primis libero tempus, tempor posuere ligula varius impedit enim tempor vitae and pulvinar congue augue egestas. Praesent aliquet lorem purus, quis mollis laoreet vitae tortor
										   		</p>

										   </div>
										</div>	


										<hr />


								
										<div class="media mt-40">

										
										  	<img class="mr-3" src="images/post-author-4.jpg" alt="comment-avatar">

										 	<div class="media-body">

										 	
										 		<div class="comment-meta">
											   		<h5 class="h5-sm mt-0 noto-font-700 purple-color">Rady Smith</h5>
											   		<span class="comment-date">42 days ago&#8194;- </span>
													<span class="btn-reply"><a href="#leave-comment" class="internal-link"><i class="fas fa-reply"></i> Reply</a></span>
												</div>
												
										
										   		<p>Etiam sapien sem magna at vitae pulvinar congue augue egestas pretium neque id viverra suscipit
										   		   egestas magna porta ratione, mollis risus lectus porta rutrum arcu aenean primis in augue auctor 
										   		   efficitur ipsum primis in cubilia laoreet augue egestas luctus donec diam integer congue
										   		</p>

										   </div>
										</div>	


										<hr />	


									</div>	 -->


								 
									<!-- <div id="leave-comment">
										
										 
										<h4 class="h4-sm">Leave a Reply</h4>
										
										 
										<p class="grey-color">Your email address will not be published. All comments go through moderation, 
										  so your comment won't display immediately.
										</p>

										<form name="commentForm" class="row comment-form">

											<div id="input-message" class="col-md-12 input-message">
							                	<p>Add Comment *</p>
							                	<textarea class="form-control message" autocomplete="off" name="message" rows="6" placeholder="Enter Your Comment Here* ..." required></textarea>
							                </div> 
					                                            
							                <div id="input-name" class="col-md-12">
							                	<p>Name*</p>
							                	<input type="text" name="name" autocomplete="off" class="form-control name" placeholder="Enter Your Name*" required> 
							                </div>
							                        
							                <div id="input-email" class="col-md-12">
							                	<p>Email*</p>
							                	<input type="text" name="email" autocomplete="off" class="form-control email" placeholder="Enter Your Email*" required> 
							                </div>
							                                 
							               
							                <div class="col-lg-12 mt-15 form-btn text-right"> 						                 
							                	<button type="submit" class="btn btn-rose tra-black-hover submit">Post Your Comment</button> 
							                </div>
							                                                              
							             
							                <div class="col-md-12 comment-form-msg text-center">
							                	<div class="sending-msg"><span class="loading"></span></div>
							                </div>  
						                                              
						                </form>									
									
									</div>  -->
									

								</div>
							</div>	 


						</div>
					</div>
                    <!-- <form action="<?php echo base_url() ?>chatbot" method="post">
        <input type="text" name="user_input" placeholder="Ask me something...">
        <button type="submit">Send</button>
    </form> -->
				</section>	<!-- END SINGLE POST -->
          
