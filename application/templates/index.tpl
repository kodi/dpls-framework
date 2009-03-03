<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>DPLS Framework example</title>
	<meta name="generator" content="moje dve ruke, dva rudnika..." />
	<link href="/main.css" rel="stylesheet" type="text/css" />
	<link rel="StyleSheet" href="/css/main.css" type="text/css" />
	<script type="text/javascript" src="/js/prototype.js"></script>
	
	
</head>
<body>
	<div id="container" >
		<?php DPLS_Template::show('header'); ?>
		<div class="clearfix">
			
			
			<div id="content">
				<h1>Main content</h1>
				
				<h4>Welcome <?php echo $this->v_user; ?></h4>
				<p>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas nec diam in mi scelerisque vehicula. Duis odio sem, tempor eget, commodo sed, luctus ut, elit. Nulla interdum. Sed dolor dolor, molestie eget, feugiat nec, suscipit quis, orci. Curabitur ac nisi. Morbi facilisis rhoncus ipsum. Mauris tempus libero sed urna. Pellentesque volutpat. Sed sem. Nullam mi odio, iaculis in, ultricies et, blandit sed, augue. In varius.
				
				</p>
				
				<p>
				Suspendisse quis tortor nec ante pellentesque mattis. Pellentesque posuere imperdiet eros. Suspendisse suscipit eros eu tellus. Sed sagittis tellus condimentum purus. Suspendisse potenti. Aliquam erat volutpat. Ut quis quam. Praesent nibh ante, vehicula vel, ullamcorper sit amet, venenatis quis, lacus. Quisque congue, velit a rhoncus pellentesque, purus nibh convallis eros, rutrum fermentum nisl metus in tortor. Donec eleifend, risus eu pretium ullamcorper, diam nibh semper mi, id dapibus erat metus at ipsum. Maecenas turpis elit, consequat eget, laoreet in, ullamcorper sed, libero. Proin viverra, dolor ut ultrices adipiscing, justo metus consequat magna, vel gravida ligula libero eu nisi.
				</p>
				
			</div> <!--container end -->
			
			<?php DPLS_Template::show('sidebar'); ?>
			
		</div><!-- clearfix end -->
		
		<?php DPLS_Template::show('footer'); ?>	
	</div><!-- container end -->
</body>
</html>