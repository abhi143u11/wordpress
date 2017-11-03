<?php
/*
Template Name: Project Page
*/
get_header(); 
?>

<div class="row int-header">
	<div class="small-12 columns">
		<h1>
			<?php the_title(); ?>
		</h1>
	</div>
</div>
<!--int-header-->
<div class="row breadcrumbs show-for-large-up">
	<div class="small-12 columns">
		<?php if (function_exists('qt_custom_breadcrumbs')) qt_custom_breadcrumbs(); ?>
	</div>
</div>
<!--breadcrumbs-->
<section class="row int">
	<div class="medium-12 large-4 sidebar columns">
		<div class="row intro hide-for-large-up">
			<div class="small-12 columns">
				<h2>Project Gallery</h2>
				<p>This is an example of intro text styling. Intro text is a great way to lead into more complex content on a page. It is slightly larger than standard body content and draws the viewers eye in. Intro paragraphs are not intended to be long simply because the intent of the content is to provide a brief lead in. Often we recommend that longer pararaphs can be broken to create the</p>
			</div>
		</div>
		<!--intro-->
		<div class="row">
			<div class="large-12 columns">
				<input type="text" class="project-search" placeholder="Search by Project ID" />
				<span class="text-center project-icon"><input type="image" alt="Search" src="<?php bloginfo('template_directory'); ?>/img/search-icon.png" /></span>
				<a href="#" class="button expand filter">Click to Filter<i class="fa fa-chevron-right"></i></a> <a href="#" class="button tiny reset">Reset</a> </div>
			<div class="large-12 columns">
				<p>Product Category</p>
					<select>
						<option value="husker">Cat 1</option>
						<option value="starbuck">Cat 2</option>
						<option value="hotdog">Cat 3</option>
						<option value="apollo">Cat 4</option>
					</select>
			</div>
			<ul class="accordion" data-accordion>
				<li class="accordion-navigation"> <a href="#panel1a">Product Type<i class="fa fa-chevron-right closed"></i><i class="fa fa-chevron-down open"></i></a>
					<div id="panel1a" class="content">
						<div class="row">
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1" class="checked">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox2" type="checkbox">
								<label for="checkbox2">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox3" type="checkbox">
								<label for="checkbox3">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox4" type="checkbox">
								<label for="checkbox4">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox5" type="checkbox">
								<label for="checkbox5">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox6" type="checkbox">
								<label for="checkbox6">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox7" type="checkbox">
								<label for="checkbox7">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox8" type="checkbox">
								<label for="checkbox8">Checkbox 1</label>
							</div>
						</div>
					</div>
				</li>
			</ul>
			<!--accordian-->
			<ul class="accordion" data-accordion>
				<li class="accordion-navigation"> <a href="#panel2a">Product Details<i class="fa fa-chevron-right closed"></i><i class="fa fa-chevron-down open"></i></a>
					<div id="panel2a" class="content">
						<div class="row">
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
						</div>
					</div>
				</li>
			</ul>
			<!--accordian-->
			<ul class="accordion" data-accordion>
				<li class="accordion-navigation"> <a href="#panel3a">Location<i class="fa fa-chevron-right closed"></i><i class="fa fa-chevron-down open"></i></a>
					<div id="panel3a" class="content">
						<div class="row">
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
						</div>
					</div>
				</li>
			</ul>
			<!--accordian-->
			<ul class="accordion" data-accordion>
				<li class="accordion-navigation"> <a href="#panel4a">Exterior Color<i class="fa fa-chevron-right closed"></i><i class="fa fa-chevron-down open"></i></a>
					<div id="panel4a" class="content">
						<div class="row">
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
						</div>
					</div>
				</li>
			</ul>
			<!--accordian-->
			<ul class="accordion" data-accordion>
				<li class="accordion-navigation"> <a href="#panel5a">Interior Color<i class="fa fa-chevron-right closed"></i><i class="fa fa-chevron-down open"></i></a>
					<div id="panel5a" class="content">
						<div class="row">
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
						</div>
					</div>
				</li>
			</ul>
			<!--accordian-->
			<ul class="accordion" data-accordion>
				<li class="accordion-navigation"> <a href="#panel6a">Application<i class="fa fa-chevron-right closed"></i><i class="fa fa-chevron-down open"></i></a>
					<div id="panel6a" class="content">
						<div class="row">
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
						</div>
					</div>
				</li>
			</ul>
			<!--accordian-->
			<ul class="accordion" data-accordion>
				<li class="accordion-navigation"> <a href="#panel7a">Glaze<i class="fa fa-chevron-right closed"></i><i class="fa fa-chevron-down open"></i></a>
					<div id="panel7a" class="content">
						<div class="row">
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
							<div class="small-12 columns checks">
								<input id="checkbox1" type="checkbox">
								<label for="checkbox1">Checkbox 1</label>
							</div>
						</div>
					</div>
				</li>
			</ul>
			<!--accordian--> 
			
		</div>
		<!--row--> 
	</div>
	<!--sidebar-->
	<div class="medium-12 large-8 int-main columns">
		<?php do_action( 'foundationpress_before_content' ); ?>
		<div class="row intro">
			<div class="small-12 show-for-large-up columns">
				<h2>Project Gallery</h2>
				<p>This is an example of intro text styling. Intro text is a great way to lead into more complex content on a page. It is slightly larger than standard body content and draws the viewers eye in. Intro paragraphs are not intended to be long simply because the intent of the content is to provide a brief lead in. Often we recommend that longer pararaphs can be broken to create the</p>
			</div>
		</div>
		<!--intro-->
		<div class="row">
			<div class="small-12 medium-4 large-4 columns">
				<div class="mask"> <a class="port-img" href="#"><img src="https://placeimg.com/640/480/any"></a> </div>
				<a class="port-heading" href="#">
				<h3>Architectural Enhancement</h3>
				</a> </div>
			<div class="small-12 medium-4 large-4 columns"> <a class="port-img" href="#"><img src="https://placeimg.com/640/480/any"></a> <a class="port-heading" href="#">
				<h3>Canopy</h3>
				</a> </div>
			<div class="small-12 medium-4 large-4 columns"> <a class="port-img" href="#"><img src="https://placeimg.com/640/480/any"></a> <a class="port-heading" href="#">
				<h3>Car Wash</h3>
				</a> </div>
		</div>
		<!--row-->
		<div class="row">
			<div class="small-12 medium-4 large-4 columns"> <img src="https://placeimg.com/640/480/any">
				<h3>Architectural Enhancement</h3>
			</div>
			<div class="small-12 medium-4 large-4 columns"> <img src="https://placeimg.com/640/480/any">
				<h3>Canopy</h3>
			</div>
			<div class="small-12 medium-4 large-4 columns"> <img src="https://placeimg.com/640/480/any">
				<h3>Car Wash</h3>
			</div>
		</div>
		<!--row-->
		<div class="row">
			<div class="small-12 medium-4 large-4 columns"> <img src="https://placeimg.com/640/480/any">
				<h3>Architectural Enhancement</h3>
			</div>
			<div class="small-12 medium-4 large-4 columns"> <img src="https://placeimg.com/640/480/any">
				<h3>Canopy</h3>
			</div>
			<div class="small-12 medium-4 large-4 columns"> <img src="https://placeimg.com/640/480/any">
				<h3>Car Wash</h3>
			</div>
		</div>
		<!--row-->
		<div class="row project-info">
			<div class="small-12 columns">
				<h2>Project: 10-02-333</h2>
				<div class="gallery-holder"> <img src="<?php bloginfo('template_directory'); ?>/img/slider-place.jpg"></div>
				<!--gallery-holder-->
				<div class="info-holder">
					<p>Curved Eave Double Pitch Sunroom(Ogee style) located in New York City's Central Park West District. NYC traffic was re-routed for install and the project was featured on DIY Network's "Million Dollar Contractor". The sunroom features a finial, solid base panels, and interior roof mounted shades.</p>
					<div class="row small-collapse">
						<div class="small-12 medium-4 columns">
							<p class="info-text-1">Location</p>
						</div>
						<div class="small-12 medium-8 columns">
							<p class="info-text-2">New York</p>
						</div>
					</div>
					<div class="row small-collapse">
						<div class="small-12 medium-4 columns">
							<p class="info-text-1">Application</p>
						</div>
						<div class="small-12 medium-8 columns">
							<p class="info-text-2">Living Space, Residential</p>
						</div>
					</div>
					<div class="row small-collapse">
						<div class="small-12 medium-4 columns">
							<p class="info-text-1">Exterior Color</p>
						</div>
						<div class="small-12 medium-8 columns">
							<p class="info-text-2">Specialty</p>
						</div>
					</div>
					<div class="row small-collapse">
						<div class="small-12 medium-4 columns">
							<p class="info-text-1">Interior Color</p>
						</div>
						<div class="small-12 medium-8 columns">
							<p class="info-text-2">Duracron</p>
						</div>
					</div>
					<div class="row small-collapse">
						<div class="small-12 medium-4 columns">
							<p class="info-text-1">Glaze</p>
						</div>
						<div class="small-12 medium-8 columns">
							<p class="info-text-2">Clear, Tempered</p>
						</div>
					</div>
					<div class="row small-collapse">
						<div class="small-12 medium-4 columns">
							<p class="info-text-1">Width</p>
						</div>
						<div class="small-12 medium-8 columns">
							<p class="info-text-2">25' 9</p>
						</div>
					</div>
					<div class="row small-collapse">
						<div class="small-12 medium-4 columns">
							<p class="info-text-1">Length / Projection</p>
						</div>
						<div class="small-12 medium-8 columns">
							<p class="info-text-2">25' 9</p>
						</div>
					</div>
					<div class="row small-collapse">
						<div class="small-12 medium-4 columns">
							<p class="info-text-1">Ridge Height</p>
						</div>
						<div class="small-12 medium-8 columns">
							<p class="info-text-2">7'</p>
						</div>
					</div>
					<div class="row small-collapse">
						<div class="small-12 medium-4 columns">
							<p class="info-text-1">Product Type</p>
						</div>
						<div class="small-12 medium-8 columns">
							<p class="info-text-2">Curved Eave Double Pitch</p>
						</div>
					</div>
					<div class="row small-collapse">
						<div class="small-12 medium-4 columns">
							<p class="info-text-1">Product Details</p>
						</div>
						<div class="small-12 medium-8 columns">
							<p class="info-text-2">1001-2001 SQF. Glass</p>
						</div>
					</div>
					<div class="row small-collapse">
						<div class="small-12 medium-4 columns">
							<p class="info-text-1">Download / Print PDF</p>
						</div>
						<div class="small-12 medium-8 columns">
							<p class="info-text-2">Download</p>
						</div>
					</div>
				</div>
				<!--info-holder--> 
			</div>
			<!--project-info--> 
		</div>
		<!--project-info-->
		<div class="row">
			<div class="small-12 columns">
				<h2 class="related">Related Projects</h2>
			</div>
			<div class="small-12 medium-4 large-4 columns"> <img src="https://placeimg.com/640/480/any">
				<h3>Architectural Enhancement</h3>
			</div>
			<div class="small-12 medium-4 large-4 columns"> <img src="https://placeimg.com/640/480/any">
				<h3>Canopy</h3>
			</div>
			<div class="small-12 medium-4 large-4 columns"> <img src="https://placeimg.com/640/480/any">
				<h3>Car Wash</h3>
			</div>
		</div>
		<!--row-->
		<div class="row">
			<div class="small-12 medium-4 large-4 columns"> <img src="https://placeimg.com/640/480/any">
				<h3>Architectural Enhancement</h3>
			</div>
			<div class="small-12 medium-4 large-4 columns"> <img src="https://placeimg.com/640/480/any">
				<h3>Canopy</h3>
			</div>
			<div class="small-12 medium-4 large-4 columns"> <img src="https://placeimg.com/640/480/any">
				<h3>Car Wash</h3>
			</div>
		</div>
		<!--row-->
		
		<?php do_action( 'foundationpress_after_content' ); ?>
	</div>
	<!--int-main--> 
</section>
<?php get_footer(); ?>
