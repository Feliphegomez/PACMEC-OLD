<?php
/**
 *
 * @package    PACMEC
 * @category   System
 * @copyright  2020-2021 FelipheGomez CO
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @license    license.txt
 * @version    0.0.1
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Blank Page - PACMEC FelipheGomez</title>

	<!-- Main Styles -->
	<link rel="stylesheet" href="assets/styles/style.min.css">

	<!-- Material Design Icon -->
	<link rel="stylesheet" href="assets/fonts/material-design/css/materialdesignicons.css">

	<!-- mCustomScrollbar -->
	<link rel="stylesheet" href="assets/plugin/mCustomScrollbar/jquery.mCustomScrollbar.min.css">

	<!-- Waves Effect -->
	<link rel="stylesheet" href="assets/plugin/waves/waves.min.css">

	<!-- Sweet Alert -->
	<link rel="stylesheet" href="assets/plugin/sweet-alert/sweetalert.css">

	<!-- Color Picker -->
	<link rel="stylesheet" href="assets/color-switcher/color-switcher.min.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-router/3.0.2/vue-router.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
  <div id="app">
    <div class="main-menu" v-if="userData !== null">
    	<header class="header">
    		<a href="index.php" class="logo">PACMEC</a>
    		<button type="button" class="button-close fa fa-times js__menu_close"></button>
    	</header>
    	<!-- /.header -->
    	<div class="content">

    		<div class="navigation">
    			<ul class="menu js__accordion">
    				<li>
    					<a class="waves-effect" href="index.php"><i class="menu-icon mdi mdi-view-dashboard"></i><span>Dashboard</span></a>
    				</li>
    				<li>
    					<a class="waves-effect parent-item js__control" href="#"><i class="menu-icon mdi mdi-flower"></i><span>Icons</span><span class="menu-arrow fa fa-angle-down"></span></a>
    					<ul class="sub-menu js__content">
    						<li><a href="icons-font-awesome-icons.html">Font Awesome</a></li>
    						<li><a href="icons-fontello.html">Fontello</a></li>
    						<li><a href="icons-material-icons.html">Material Design Icons</a></li>
    						<li><a href="icons-material-design-iconic.html">Material Design Iconic Font</a></li>
    						<li><a href="icons-themify-icons.html">Themify Icons</a></li>
    					</ul>
    					<!-- /.sub-menu js__content -->
    				</li>
    				<li>
    					<a class="waves-effect" href="calendar.html"><i class="menu-icon mdi mdi-calendar-multiple"></i><span>Calendar</span><span class="notice notice-danger">New</span></a>
    				</li>
    				<li>
    					<a class="waves-effect parent-item js__control" href="#"><i class="menu-icon mdi mdi-chart-bar"></i><span>Charts</span><span class="menu-arrow fa fa-angle-down"></span></a>
    					<ul class="sub-menu js__content">
    						<li><a href="chart-3d.html">3D Charts</a></li>
    						<li><a href="chart-chartist.html">Chartist Charts</a></li>
    						<li><a href="chart-chartjs.html">Chartjs Chart</a></li>
    						<li><a href="chart-dynamic.html">Dynamic Chart</a></li>
    						<li><a href="chart-flot.html">Flot Chart</a></li>
    						<li><a href="chart-knob.html">Knob Chart</a></li>
    						<li><a href="chart-morris.html">Morris Chart</a></li>
    						<li><a href="chart-sparkline.html">Sparkline Chart</a></li>
    						<li><a href="chart-other.html">Other Chart</a></li>
    					</ul>
    					<!-- /.sub-menu js__content -->
    				</li>
    				<li>
    					<a class="waves-effect parent-item js__control" href="#"><i class="menu-icon mdi mdi-cube-outline"></i><span>Admin UI</span><span class="menu-arrow fa fa-angle-down"></span></a>
    					<ul class="sub-menu js__content">
    						<li><a href="ui-notification.html">Notification</a></li>
    						<li><a href="profile.html">Profile</a></li>
    						<li><a href="ui-range-slider.html">Range Slider</a></li>
    						<li><a href="ui-sweetalert.html">Sweet Alert</a></li>
    						<li><a href="ui-treeview.html">Tree view</a></li>
    						<li><a href="widgets.html">Widget</a></li>
    					</ul>
    					<!-- /.sub-menu js__content -->
    				</li>
    				<li>
    					<a class="waves-effect parent-item js__control" href="#"><i class="menu-icon mdi mdi-buffer"></i><span>User Interface</span><span class="menu-arrow fa fa-angle-down"></span></a>
    					<ul class="sub-menu js__content">
    						<li><a href="ui-buttons.html">Buttons</a></li>
    						<li><a href="ui-cards.html">Cards</a></li>
    						<li><a href="ui-checkbox-radio.html">Checkboxs-Radios</a></li>
    						<li><a href="ui-components.html">Components</a></li>
    						<li><a href="ui-draggable-cards.html">Draggable Cards</a></li>
    						<li><a href="ui-modals.html">Modals</a></li>
    						<li><a href="ui-typography.html">Typography</a></li>
    					</ul>
    					<!-- /.sub-menu js__content -->
    				</li>
    				<li>
    					<a class="waves-effect" href="inbox.html"><i class="menu-icon mdi mdi-email-outline"></i><span>Mail</span></a>
    				</li>
    				<li>
    					<a class="waves-effect parent-item js__control" href="#"><i class="menu-icon mdi mdi-pencil-box"></i><span>Forms</span><span class="notice notice-blue">7</span></a>
    					<ul class="sub-menu js__content">
    						<li><a href="form-elements.html">General Elements</a></li>
    						<li><a href="form-advanced.html">Advanced Form</a></li>
    						<li><a href="form-fileupload.html">Form Uploads</a></li>
    						<li><a href="form-validation.html">Form Validation</a></li>
    						<li><a href="form-wizard.html">Form Wizard</a></li>
    						<li><a href="form-wysiwig.html">Wysiwig Editors</a></li>
    						<li><a href="form-xeditable.html">X-editable</a></li>
    					</ul>
    					<!-- /.sub-menu js__content -->
    				</li>
    				<li>
    					<a class="waves-effect parent-item js__control" href="#"><i class="menu-icon mdi mdi-table"></i><span>Tables</span><span class="menu-arrow fa fa-angle-down"></span></a>
    					<ul class="sub-menu js__content">
    						<li><a href="tables-basic.html">Basic Tables</a></li>
    						<li><a href="tables-datatable.html">Data Tables</a></li>
    						<li><a href="tables-responsive.html">Responsive Tables</a></li>
    						<li><a href="tables-editable.html">Editable Tables</a></li>
    					</ul>
    					<!-- /.sub-menu js__content -->
    				</li>
    				<li class="current active">
    					<a class="waves-effect parent-item js__control" href="#"><i class="menu-icon mdi mdi-book-multiple-variant"></i><span>Page</span><span class="menu-arrow fa fa-angle-down"></span></a>
    					<ul class="sub-menu js__content">
    						<li class="current"><a href="page-starter.html">Starter Page</a></li>
    						<li><a href="page-login.html">Login</a></li>
    						<li><a href="page-register.html">Register</a></li>
    						<li><a href="page-recoverpw.html">Recover Password</a></li>
    						<li><a href="page-lock-screen.html">Lock Screen</a></li>
    						<li><a href="page-confirm-mail.html">Confirm Mail</a></li>
    						<li><a href="page-404.html">Error 404</a></li>
    						<li><a href="page-500.html">Error 500</a></li>
    					</ul>
    					<!-- /.sub-menu js__content -->
    				</li>
    				<li>
    					<a class="waves-effect parent-item js__control" href="#"><i class="menu-icon mdi mdi-folder-multiple"></i><span>Extra Pages</span><span class="menu-arrow fa fa-angle-down"></span></a>
    					<ul class="sub-menu js__content">
    						<li><a href="extras-contact.html">Contact list</a></li>
    						<li><a href="extras-email-template.html">Email template</a></li>
    						<li><a href="extras-faq.html">FAQ</a></li>
    						<li><a href="extras-gallery.html">Gallery</a></li>
    						<li><a href="extras-invoice.html">Invoice</a></li>
    						<li><a href="extras-maps.html">Maps</a></li>
    						<li><a href="extras-pricing.html">Pricing</a></li>
    						<li><a href="extras-projects.html">Projects</a></li>
    						<li><a href="extras-taskboard.html">Taskboard</a></li>
    						<li><a href="extras-timeline.html">Timeline</a></li>
    						<li><a href="extras-tour.html">Tour</a></li>
    					</ul>
    					<!-- /.sub-menu js__content -->
    				</li>
    			</ul>
    			<!-- /.menu js__accordion -->
    		</div>
    		<!-- /.navigation -->
    	</div>
    	<!-- /.content -->
    </div>
    <!-- /.main-menu -->

    <div class="fixed-navbar" v-if="userData !== null">
    	<div class="pull-left">
    		<button type="button" class="menu-mobile-button glyphicon glyphicon-menu-hamburger js__menu_mobile"></button>
    		<h1 class="page-title">Blank Page</h1>
    		<!-- /.page-title -->
    	</div>
    	<!-- /.pull-left -->
    	<div class="pull-right">
    		<div class="ico-item">
    			<a href="#" class="ico-item fa fa-search js__toggle_open" data-target="#searchform-header"></a>
    			<form action="#" id="searchform-header" class="searchform js__toggle"><input type="search" placeholder="Search..." class="input-search"><button class="fa fa-search button-search" type="submit"></button></form>
    			<!-- /.searchform -->
    		</div>
    		<!-- /.ico-item -->
    		<div class="ico-item fa fa-arrows-alt js__full_screen"></div>
    		<!-- /.ico-item fa fa-fa-arrows-alt -->
    		<div class="ico-item toggle-hover js__drop_down ">
    			<span class="fa fa-th js__drop_down_button"></span>
    			<div class="toggle-content">
    				<ul>
    					<li><a href="#"><i class="fa fa-github"></i><span class="txt">Github</span></a></li>
    					<li><a href="#"><i class="fa fa-bitbucket"></i><span class="txt">Bitbucket</span></a></li>
    					<li><a href="#"><i class="fa fa-slack"></i><span class="txt">Slack</span></a></li>
    					<li><a href="#"><i class="fa fa-dribbble"></i><span class="txt">Dribbble</span></a></li>
    					<li><a href="#"><i class="fa fa-amazon"></i><span class="txt">Amazon</span></a></li>
    					<li><a href="#"><i class="fa fa-dropbox"></i><span class="txt">Dropbox</span></a></li>
    				</ul>
    				<a href="#" class="read-more">More</a>
    			</div>
    			<!-- /.toggle-content -->
    		</div>
    		<!-- /.ico-item -->
    		<a href="#" class="ico-item fa fa-users notice-alarm js__toggle_open" data-target="#megroup-popup"></a>
    		<a href="#" class="ico-item fa fa-envelope notice-alarm js__toggle_open" data-target="#message-popup"></a>
    		<a href="#" class="ico-item pulse"><span class="ico-item fa fa-bell notice-alarm js__toggle_open" data-target="#notification-popup"></span></a>
    		<div class="ico-item">
    			<img src="//placehold.it/80x80" alt="" class="ico-img">
    			<ul class="sub-ico-item">
						<li v-if="userData !== null && userData.boss!==null" class="nav flex-column nav-pills">
							<router-link v-bind:to="{name: 'ViewProfile', params: {id: userData !== null && userData.boss.id}}" class="nav-link" :key="userData.boss.username">
			          Boss ({{ userData.boss.username }})
			        </router-link>
			      </li>

    				<li><a href="#">Settings</a></li>
    				<li><a href="#">Blog</a></li>
    				<li><a @click="signout" style="cursor:pointer;" class="_js__logout">signout</a></li>
    			</ul>
    			<!-- /.sub-ico-item -->
    		</div>
    		<!-- /.ico-item -->
    	</div>
    	<!-- /.pull-right -->
    </div>
    <!-- /.fixed-navbar -->

    <div id="megroup-popup" class="notice-popup js__toggle" data-space="50">
    	<h2 class="popup-title">Tu Grupo</h2>
    	<!-- /.popup-title -->
    	<div class="content">
    		<ul class="notice-list">
					<li v-if="userData !== null && userData.mt_users!==null" class="nav flex-column nav-pills">
		        <router-link tag="a" v-for="user in userData.mt_users" v-bind:to="{name: 'View', params: {subject: 'mt_users',id: user.id}}">
    					<span class="avatar"><img src="//placehold.it/80x80" alt=""></span>
    					<span class="name">{{ user.names }} {{ user.surname }}</span>
    					<span class="desc">{{ user.phone }} {{ user.mobile }}</span>
    					<span class="time">{{ user.username }}</span>
		        </router-link>
		      </li>

    		</ul>
    	</div>
    	<!-- /.content -->
    </div>
    <!-- /#notification-popup -->

    <div id="notification-popup" class="notice-popup js__toggle" data-space="50">
    	<h2 class="popup-title">Your Notifications</h2>
    	<!-- /.popup-title -->
    	<div class="content">
    		<ul class="notice-list">
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/80x80" alt=""></span>
    					<span class="name">John Doe</span>
    					<span class="desc">Like your post: “Contact Form 7 Multi-Step”</span>
    					<span class="time">10 min</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/80x80" alt=""></span>
    					<span class="name">Anna William</span>
    					<span class="desc">Like your post: “Facebook Messenger”</span>
    					<span class="time">15 min</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar bg-warning"><i class="fa fa-warning"></i></span>
    					<span class="name">Update Status</span>
    					<span class="desc">Failed to get available update data. To ensure the please contact us.</span>
    					<span class="time">30 min</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/128x128" alt=""></span>
    					<span class="name">Jennifer</span>
    					<span class="desc">Like your post: “Contact Form 7 Multi-Step”</span>
    					<span class="time">45 min</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/80x80" alt=""></span>
    					<span class="name">Michael Zenaty</span>
    					<span class="desc">Like your post: “Contact Form 7 Multi-Step”</span>
    					<span class="time">50 min</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/80x80" alt=""></span>
    					<span class="name">Simon</span>
    					<span class="desc">Like your post: “Facebook Messenger”</span>
    					<span class="time">1 hour</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar bg-violet"><i class="fa fa-flag"></i></span>
    					<span class="name">Account Contact Change</span>
    					<span class="desc">A contact detail associated with your account has been changed.</span>
    					<span class="time">2 hours</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/80x80" alt=""></span>
    					<span class="name">Helen 987</span>
    					<span class="desc">Like your post: “Facebook Messenger”</span>
    					<span class="time">Yesterday</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/128x128" alt=""></span>
    					<span class="name">Denise Jenny</span>
    					<span class="desc">Like your post: “Contact Form 7 Multi-Step”</span>
    					<span class="time">Oct, 28</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/80x80" alt=""></span>
    					<span class="name">Thomas William</span>
    					<span class="desc">Like your post: “Facebook Messenger”</span>
    					<span class="time">Oct, 27</span>
    				</a>
    			</li>
    		</ul>
    		<!-- /.notice-list -->
    		<a href="#" class="notice-read-more">See more messages <i class="fa fa-angle-down"></i></a>
    	</div>
    	<!-- /.content -->
    </div>
    <!-- /#notification-popup -->

    <div id="message-popup" class="notice-popup js__toggle" data-space="50">
    	<h2 class="popup-title">Recent Messages<a href="#" class="pull-right text-danger">New message</a></h2>
    	<!-- /.popup-title -->
    	<div class="content">
    		<ul class="notice-list">
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/80x80" alt=""></span>
    					<span class="name">John Doe</span>
    					<span class="desc">Amet odio neque nobis consequuntur consequatur a quae, impedit facere repellat voluptates.</span>
    					<span class="time">10 min</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/80x80" alt=""></span>
    					<span class="name">Harry Halen</span>
    					<span class="desc">Amet odio neque nobis consequuntur consequatur a quae, impedit facere repellat voluptates.</span>
    					<span class="time">15 min</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/80x80" alt=""></span>
    					<span class="name">Thomas Taylor</span>
    					<span class="desc">Amet odio neque nobis consequuntur consequatur a quae, impedit facere repellat voluptates.</span>
    					<span class="time">30 min</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/128x128" alt=""></span>
    					<span class="name">Jennifer</span>
    					<span class="desc">Amet odio neque nobis consequuntur consequatur a quae, impedit facere repellat voluptates.</span>
    					<span class="time">45 min</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/80x80" alt=""></span>
    					<span class="name">Helen Candy</span>
    					<span class="desc">Amet odio neque nobis consequuntur consequatur a quae, impedit facere repellat voluptates.</span>
    					<span class="time">45 min</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/128x128" alt=""></span>
    					<span class="name">Anna Cavan</span>
    					<span class="desc">Amet odio neque nobis consequuntur consequatur a quae, impedit facere repellat voluptates.</span>
    					<span class="time">1 hour ago</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar bg-success"><i class="fa fa-user"></i></span>
    					<span class="name">Jenny Betty</span>
    					<span class="desc">Amet odio neque nobis consequuntur consequatur a quae, impedit facere repellat voluptates.</span>
    					<span class="time">1 day ago</span>
    				</a>
    			</li>
    			<li>
    				<a href="#">
    					<span class="avatar"><img src="//placehold.it/128x128" alt=""></span>
    					<span class="name">Denise Peterson</span>
    					<span class="desc">Amet odio neque nobis consequuntur consequatur a quae, impedit facere repellat voluptates.</span>
    					<span class="time">1 year ago</span>
    				</a>
    			</li>
    		</ul>
    		<!-- /.notice-list -->
    		<a href="#" class="notice-read-more">See more messages <i class="fa fa-angle-down"></i></a>
    	</div>
    	<!-- /.content -->
    </div>
    <!-- /#message-popup -->
    <div id="color-switcher">
    	<div id="color-switcher-button" class="btn-switcher">
    		<div class="inside waves-effect waves-circle waves-light">
    			<i class="ico fa fa-gear"></i>
    		</div>
    		<!-- .inside waves-effect waves-circle -->
    	</div>
    	<!-- .btn-switcher -->
    	<div id="color-switcher-content" class="content">
    		<a href="#" data-color="red" class="item js__change_color"><span class="color" style="background-color: #f44336;"></span><span class="text">Red</span></a>
    		<a href="#" data-color="violet" class="item js__change_color"><span class="color" style="background-color: #673ab7;"></span><span class="text">Violet</span></a>
    		<a href="#" data-color="dark-blue" class="item js__change_color"><span class="color" style="background-color: #3f51b5;"></span><span class="text">Dark Blue</span></a>
    		<a href="#" data-color="blue" class="item js__change_color active"><span class="color" style="background-color: #304ffe;"></span><span class="text">Blue</span></a>
    		<a href="#" data-color="light-blue" class="item js__change_color"><span class="color" style="background-color: #2196f3;"></span><span class="text">Light Blue</span></a>
    		<a href="#" data-color="green" class="item js__change_color"><span class="color" style="background-color: #4caf50;"></span><span class="text">Green</span></a>
    		<a href="#" data-color="yellow" class="item js__change_color"><span class="color" style="background-color: #ffc107;"></span><span class="text">Yellow</span></a>
    		<a href="#" data-color="orange" class="item js__change_color"><span class="color" style="background-color: #ff5722;"></span><span class="text">Orange</span></a>
    		<a href="#" data-color="chocolate" class="item js__change_color"><span class="color" style="background-color: #795548;"></span><span class="text">Chocolate</span></a>
    		<a href="#" data-color="dark-green" class="item js__change_color"><span class="color" style="background-color: #263238;"></span><span class="text">Dark Green</span></a>
    		<span id="color-reset" class="btn-restore-default js__restore_default">Reset</span>
    	</div>
    	<!-- /.content -->
    </div>
    <!-- #color-switcher -->

    <div id="wrapper">
    	<div class="main-content">
        	<template v-if="userData == null">
						<div class="row">
	            <h4>Debes iniciar session</h4>
	            <h5>{{ forms.login.msg }}</h5>
	            <br>
	            <div class="col-md-12">
	              <form @submit="signin" method="post"  action="javascript:false;" class="form">
	                <input type="text" v-model="forms.login.username" required="true" class="form-control" />
	                <input type="password" v-model="forms.login.hash" required="true" class="form-control" />
	                <button type="submit" class="btn btn-md btn-success">iniciar sesion</button>
	              </form>
	            </div>
						</div>
          </template>
          <template v-else>
							<!--//
                <menu-component v-if="definition!==null" :subjects="definition.tags"></menu-component>
              -->
							<router-view :key="$route.fullPath" v-if="definition!==null" :definition="definition"></router-view>
          </template>

    		<footer class="footer">
    			<ul class="list-inline">
    				<li>2022 © PACMEC-FelipheGomez.</li>
    				<li><a href="#">Privacy</a></li>
    				<li><a href="#">Terms</a></li>
    				<li><a href="#">Help</a></li>
    			</ul>
    		</footer>
    	</div>
    	<!-- /.main-content -->
    </div><!--/#wrapper -->
  </div>
  <template id="menu">
    <div v-if="subjects!==null" class="nav flex-column nav-pills">
        <router-link v-for="subject in subjects" v-bind:to="{name: 'List', params: {subject: subject.name}}" class="nav-link" :key="subject.name">
          {{ subject.name }}
        </router-link>
        <a @click="$parent.signout" style="cursor:pointer;" class="nav-link">signout</a>
    </div>
  </template>

  <template id="orders">
    <div>
      <h5>Mis ordenes</h5>
      <div v-if="orders!==null" class="nav flex-column nav-pills">
        <router-link v-for="order in orders" v-bind:to="{name: 'View', params: {subject: 'orders',id: order.id}}" class="nav-link" :key="order.id">
          {{ order.id }}
        </router-link>
      </div>
    </div>
  </template>

  <template id="home">
    <div>Nothing</div>
  </template>

  <template id="list">
    <div>
      <h2>{{ subject }}</h2>
      <p>
        <router-link class="btn btn-primary" v-bind:to="{name: 'Add', params: {subject: subject}}">
          Add
        </router-link>
      </p>
      <div class="card bg-light" v-if="field"><div class="card-body">
        <div style="float:right;"><router-link v-bind:to="{name: 'List', params: {subject: subject}}">Clear filter</router-link></div>
        <p class="card-text">Filtered by: {{ field }} = {{ id }}</p>
      </div></div>
      <p v-if="records===null">Cargando...</p>
      <table v-else class="table">
        <thead>
          <tr>
            <th v-for="value in Object.keys(properties)">{{ value }}</th>
            <th v-if="related">related</th>
            <th v-if="primaryKey">actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="record in records">
            <template v-for="(value, key) in record">
              <td v-if="references[key] !== false">
                <router-link v-if="record[key] !== null && referenceId(references[key], record[key])>0" v-bind:to="{name: 'View', params: {subject: references[key], id: referenceId(references[key], record[key])}}">
                  {{ referenceText(references[key], record[key]) }}
                </router-link>
              </td>
              <td v-else>{{ value }}</td>
            </template>
            <td v-if="related">
              <template v-for="(relation, i) in referenced">
                <router-link v-bind:to="{name: 'Filter', params: {subject: relation[0], field: relation[1], id: record[primaryKey]}}">{{ relation[0] }}</router-link>&nbsp;
              </template>
            </td>
            <td v-if="primaryKey" style="padding: 6px; white-space: nowrap;">
              <router-link class="btn btn-secondary btn-sm" v-bind:to="{name: 'View', params: {subject: subject, id: record[primaryKey]}}">View</router-link>
              <router-link class="btn btn-secondary btn-sm" v-bind:to="{name: 'Edit', params: {subject: subject, id: record[primaryKey]}}">Edit</router-link>
              <router-link class="btn btn-danger btn-sm" v-bind:to="{name: 'Delete', params: {subject: subject, id: record[primaryKey]}}">Delete</router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>

  <template id="create">
    <div>
      <h2>{{ subject }} - add</h2>
      <form v-on:submit="createRecord">
        <template v-for="(value, key) in record">
          <div class="form-group">
            <label v-bind:for="key">{{ key }}</label>
            <input v-if="references[key] === false" class="form-control" v-bind:id="key" v-model="record[key]" :disabled="key === primaryKey" />
            <select v-else class="form-control" v-bind:id="key" v-model="record[key]">
              <option value=""></option>
              <option v-for="option in options[references[key]]" v-bind:value="option.key">{{ option.value }}</option>
            </select>
          </div>
        </template>
        <button type="submit" class="btn btn-primary">Create</button>
        <router-link class="btn btn-primary" v-bind:to="{name: 'List', params: {subject: subject}}">Cancel</router-link>
      </form>
    </div>
  </template>

  <template id="view">
    <div>
      <h2>{{ subject }} - view</h2>
      <p v-if="record===null">Loading...</p>
      <dl v-else>
        <template v-for="(value, key) in record">
          <dt>{{ key }} </dt>
          <dd>{{ value }}</dd>
        </template>
      </dl>
    </div>
  </template>

  <template id="update">
    <div>
      <h2>{{ subject }} - edit</h2>
      <p v-if="record===null">Loading...</p>
      <form v-else v-on:submit="updateRecord">
        <template v-for="(value, key) in record">
          <div class="form-group">
            <label v-bind:for="key">{{ key }}</label>
            <input v-if="references[key] === false" class="form-control" v-bind:id="key" v-model="record[key]" :disabled="key === primaryKey" />
            <select v-else-if="!options[references[key]]" class="form-control" disabled>
              <option value="" selected>Loading...</option>
            </select>
            <select v-else class="form-control" v-bind:id="key" v-model="record[key]">
              <option value=""></option>
              <option v-for="option in options[references[key]]" v-bind:value="option.key">{{ option.value }}</option>
            </select>
          </div>
        </template>
        <button type="submit" class="btn btn-primary">Save</button>
        <router-link class="btn btn-secondary" v-bind:to="{name: 'List', params: {subject: subject}}">Cancel</router-link>
      </form>
    </div>
  </template>

  <template id="delete">
    <div>
      <h2>{{ subject }} delete #{{ id }}</h2>
      <form v-on:submit="deleteRecord">
        <p>The action cannot be undone.</p>
        <button type="submit" class="btn btn-danger">Delete</button>
        <router-link class="btn btn-secondary" v-bind:to="{name: 'List', params: {subject: subject}}">Cancel</router-link>
      </form>
    </div>
  </template>

  <template id="view-profile">
    <div>
			<p v-if="record===null">Loading...</p>
			<template v-else class="row small-spacing">
				<h2>USER PROFILE #{{ id }} | {{ record.username }}</h2>
				<hr>
				<div class="row small-spacing">
					<div class="col-md-3 col-xs-12">
						<div class="box-content bordered primary margin-bottom-20">
							<div class="profile-avatar">
								<img src="http://placehold.it/450x450" alt="">
								<a href="#" class="btn btn-block btn-friend"><i class="fa fa-check-circle"></i> Friends</a>
								<a href="#" class="btn btn-block btn-inbox"><i class="fa fa-envelope"></i> Send Messages</a>
								<h3><strong>{{ record.names }} {{ record.surname }}</strong></h3>
								<h4>{{ record.role.name }}</h4>
								<p>
									{{ record.identification_type.code }} {{ record.identification_number }}
								</p>
							</div>
							<!-- .profile-avatar -->
							<table class="table table-hover no-margin">
								<tbody>
									<tr v-if="record.boss !== null">
										<td>Jefe directo</td>
										<td><router-link tag="span" class="notice notice-danger" v-bind:to="{name: 'ViewProfile', params: {id: record.boss.id}}"><span class="notice notice-danger">{{record.boss.username}}</span></router-link></td>
									</tr>
									<tr>
										<td>User Rating</td>
										<td><i class="fa fa-star text-warning"></i> <i class="fa fa-star text-warning"></i> <i class="fa fa-star text-warning"></i> <i class="fa fa-star text-warning"></i> <i class="fa fa-star text-warning"></i></td>
									</tr>
									<tr>
										<td>Member Since</td>
										<td>Jan 07, 2014</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- /.box-content bordered -->

						<div class="box-content">
							<h4 class="box-title"><i class="ico fa fa-users"></i>Grupo</h4>
							<ul class="profile-friends-list list-inline">
								<li v-for="team in record.mt_users">
									<router-link v-bind:to="{name: 'ViewProfile', params: {id: team.id}}" :key="team.username">
										<span class="name">{{ team.names }} {{ team.surname }}</span>
										<span class="time">{{ team.username }}</span>
					        </router-link>
								</li>
							</ul>
						</div>
						<!-- /.box-content -->
					</div>
					<!-- /.col-md-3 col-xs-12 -->
					<div class="col-md-9 col-xs-12">
						<div class="row">
							<div class="col-xs-12">
								<div class="box-content card">
									<h4 class="box-title"><i class="fa fa-user ico"></i>About</h4>
									<!-- /.box-title -->
									<div class="dropdown js__drop_down">
										<a href="#" class="dropdown-icon glyphicon glyphicon-option-vertical js__drop_down_button"></a>
										<ul class="sub-menu">
											<li><a href="#">Action</a></li>
											<li><a href="#">Another action</a></li>
											<li><a href="#">Something else there</a></li>
											<li class="split"></li>
											<li><a href="#">Separated link</a></li>
										</ul>
										<!-- /.sub-menu -->
									</div>
									<!-- /.dropdown js__dropdown -->
									<div class="card-content">
										<div class="row">

											<template v-for="key in Object.keys(properties)">
												<div class="col-md-6">
													<div class="row">
														<div class="col-xs-5"><label>{{ key }}:</label></div>
														<!-- /.col-xs-5 -->
														<div class="col-xs-7">
															<td v-if="references[key] !== false && record[key] !== null">
								                <router-link v-if="referenceId(references[key], record[key])>0" v-bind:to="{name: references[key]=='mt_users'?'ViewProfile':'View', params: {subject: references[key], id: referenceId(references[key], record[key])}}">
								                  {{ referenceText(references[key], record[key]) }}
								                </router-link>
								              </td>
								              <td v-else>{{ record[key] }}</td>
														</div>
														<!-- /.col-xs-7 -->
													</div>
													<!-- /.row -->
												</div>

							        </template>
											<!--//
											<td v-if="related">
												<template v-for="(relation, i) in referenced">
													<router-link v-bind:to="{name: 'Filter', params: {subject: relation[0], field: relation[1], id: record[primaryKey]}}">{{ relation[0] }}</router-link>&nbsp;
												</template>
											</td>
											-->
											<!-- /.col-md-6 -->
										</div>
										<!-- /.row -->
									</div>
									<!-- /.card-content -->
								</div>
								<!-- /.box-content card -->
							</div>
							<!-- /.col-md-12 -->
              {{related}}
              <div v-if="related">
                {{referenced}}
                <template v-for="(relation, i) in referenced">
                  <div class="col-md-6 col-xs-12">
    								<div class="box-content card">
    									<h4 class="box-title"><i class="fa fa-file-text ico"></i> Experience</h4>
    									<!-- /.box-title -->
    									<div class="dropdown js__drop_down">
                        <router-link v-bind:to="{name: 'Filter', params: {subject: relation[0], field: relation[1], id: record[primaryKey]}}">{{ relation[0] }}</router-link>&nbsp;

    										<a href="#" class="dropdown-icon glyphicon glyphicon-option-vertical js__drop_down_button"></a>
    										<ul class="sub-menu">
    											<li><a href="#">Action</a></li>
    											<li><a href="#">Another action</a></li>
    											<li><a href="#">Something else there</a></li>
    											<li class="split"></li>
    											<li><a href="#">Separated link</a></li>
    										</ul>
    										<!-- /.sub-menu -->
    									</div>
    									<!-- /.dropdown js__dropdown -->
    									<div class="card-content">
    										<ul class="dot-list">
    											<li><a href="#">Owner</a> at <a href="#">NinjaTeam</a>.<span class="date">March 2013 ~ Now</span></li>
    											<li><a href="#">CEO</a> at <a href="#">CEO Company</a>.<span class="date"> March 2011 ~ February 2013</span></li>
    											<li><a href="#">Web Designer</a> at <a href="#">Web Design Company Ltd.</a>.<span class="date"> March 2010 ~ February 2011</span></li>
    											<li><a href="#">Sales</a> at <a href="#">Sales Company Ltd.</a>.<span class="date"> March 2009 ~ February 2010</span></li>
    										</ul>
    									</div>
    									<!-- /.card-content -->
    								</div>
    								<!-- /.box-content card -->
    							</div>
                </template>


              </div>

							<!-- /.col-md-6 -->
							<div class="col-md-6 col-xs-12">
								<div class="box-content card">
									<h4 class="box-title"><i class="fa fa-trophy ico"></i> Education</h4>
									<!-- /.box-title -->
									<div class="dropdown js__drop_down">
										<a href="#" class="dropdown-icon glyphicon glyphicon-option-vertical js__drop_down_button"></a>
										<ul class="sub-menu">
											<li><a href="#">Action</a></li>
											<li><a href="#">Another action</a></li>
											<li><a href="#">Something else there</a></li>
											<li class="split"></li>
											<li><a href="#">Separated link</a></li>
										</ul>
										<!-- /.sub-menu -->
									</div>
									<!-- /.dropdown js__dropdown -->
									<div class="card-content">
										<ul class="dot-list">
											<li><a href="#">Students</a> at <a href="#">CEO Education</a>.<span class="date">March 2013 ~ Now</span></li>
											<li><a href="#">Students</a> at <a href="#">Web Design Education</a>.<span class="date">March 2011 ~ February 2013</span></li>
											<li><a href="#">Students</a> at <a href="#">Sales School</a>.<span class="date"> March 2010 ~ February 2011</span></li>
											<li><a href="#">Students</a> at <a href="#">High School</a>.<span class="date"> March 2009 ~ February 2010</span></li>
										</ul>
									</div>
									<!-- /.card-content -->
								</div>
								<!-- /.box-content card -->
							</div>
							<!-- /.col-md-6 -->
						</div>
						<div class="row">
							<div class="col-md-6 col-xs-12">
								<div class="box-content card">
									<h4 class="box-title"><i class="fa fa-globe ico"></i> Activity</h4>
									<!-- /.box-title -->
									<div class="dropdown js__drop_down">
										<a href="#" class="dropdown-icon glyphicon glyphicon-option-vertical js__drop_down_button"></a>
										<ul class="sub-menu">
											<li><a href="#">Action</a></li>
											<li><a href="#">Another action</a></li>
											<li><a href="#">Something else there</a></li>
											<li class="split"></li>
											<li><a href="#">Separated link</a></li>
										</ul>
										<!-- /.sub-menu -->
									</div>
									<!-- /.dropdown js__dropdown -->
									<div class="card-content">
										<ul class="notice-list">
											<li>
												<a href="#">
													<span class="avatar"><img src="http://placehold.it/128x128" alt=""></span>
													<span class="name">Betty Simmons</span>
													<span class="desc">There are new settings available</span>
													<span class="time">2 hours ago</span>
												</a>
											</li>
											<li>
												<a href="#">
													<span class="avatar bg-success"><i class="glyphicon glyphicon-user"></i></span>
													<span class="name">New Signup</span>
													<span class="desc">There are new settings available</span>
													<span class="time">5 hours ago</span>
												</a>
											</li>
											<li>
												<a href="#">
													<span class="avatar bg-warning"><img src="http://placehold.it/128x128" alt=""></span>
													<span class="name">Settings</span>
													<span class="desc">There are new settings available</span>
													<span class="time">1 year ago</span>
												</a>
											</li>
											<li>
												<a href="#">
													<span class="avatar bg-warning"><i class="fa fa-flag"></i></span>
													<span class="name">New Message received</span>
													<span class="desc">There are new settings available</span>
													<span class="time">1 day ago</span>
												</a>
											</li>
											<li>
												<a href="#">
													<span class="avatar bg-pink"><i class="fa fa-gear"></i></span>
													<span class="name">Settings</span>
													<span class="desc">There are new settings available</span>
													<span class="time">1 year ago</span>
												</a>
											</li>
										</ul>
										<!-- /.notice-list -->
										<div class="text-center margin-top-20"><a href="#" class="btn btn-default">See All Activities <i class="fa fa-angle-double-right"></i></a></div>
									</div>
									<!-- /.card-content -->
								</div>
								<!-- /.box-content card -->
							</div>
							<!-- /.col-md-6 -->
							<div class="col-md-6 col-xs-12">
								<div class="box-content card">
									<h4 class="box-title"><i class="fa fa-flask ico"></i> Skill</h4>
									<!-- /.box-title -->
									<div class="dropdown js__drop_down">
										<a href="#" class="dropdown-icon glyphicon glyphicon-option-vertical js__drop_down_button"></a>
										<ul class="sub-menu">
											<li><a href="#">Action</a></li>
											<li><a href="#">Another action</a></li>
											<li><a href="#">Something else there</a></li>
											<li class="split"></li>
											<li><a href="#">Separated link</a></li>
										</ul>
										<!-- /.sub-menu -->
									</div>
									<!-- /.dropdown js__dropdown -->
									<div class="card-content">
										<p>Photoshop</p>
										<div class="progress">
											<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
												<span class="sr-only">40% Complete (success)</span>
											</div>
										</div>
										<p>Illustrator</p>
										<div class="progress">
											<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
												<span class="sr-only">20% Complete</span>
											</div>
										</div>
										<p>PHP</p>
										<div class="progress">
											<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
												<span class="sr-only">60% Complete (warning)</span>
											</div>
										</div>
										<p>Javascript</p>
										<div class="progress">
											<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
												<span class="sr-only">80% Complete (danger)</span>
											</div>
										</div>
										<p>Communication</p>
										<div class="progress">
											<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 95%">
												<span class="sr-only">95% Complete (success)</span>
											</div>
										</div>
										<p>Writing</p>
										<div class="progress">
											<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
												<span class="sr-only">45% Complete (warning)</span>
											</div>
										</div>
									</div>
									<!-- /.card-content -->
								</div>
								<!-- /.box-content card -->
							</div>
							<!-- /.col-md-6 -->
						</div>
						<!-- /.row -->
					</div>
					<!-- /.col-md-9 col-xs-12 -->
				</div>
			</template>
    </div>
  </template>


  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="assets/script/html5shiv.min.js"></script>
		<script src="assets/script/respond.min.js"></script>
	<![endif]-->
	<!--
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="assets/scripts/jquery.min.js"></script>
	<script src="assets/scripts/modernizr.min.js"></script>
	<script src="assets/plugin/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/plugin/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
	<script src="assets/plugin/nprogress/nprogress.js"></script>
	<script src="assets/plugin/sweet-alert/sweetalert.min.js"></script>
	<script src="assets/plugin/waves/waves.min.js"></script>
	<!-- Full Screen Plugin -->
	<script src="assets/plugin/fullscreen/jquery.fullscreen-min.js"></script>

	<script src="assets/scripts/main.js"></script>
	<script src="assets/color-switcher/color-switcher.js"></script>

  <script>
    var api = axios.create({
      baseURL: '//api.monteverdeltda.com/api.php',
      withCredentials: true
    });

    api.interceptors.response.use(function (response) {
      if (response.headers['x-xsrf-token']) {
        document.cookie = 'XSRF-TOKEN=' + response.headers['x-xsrf-token'] + '; path=/';
      }
      return response;
    });

    var pacmec = {
      data(){
        return {
          userData: null,
          definition: null,
          forms: {
            login: {
              msg:  null,
              username: "feliphegomez",
              hash: "1035429360",
            },
          },
        };
      },
      created: function () {
        var self = this;
        self.userData = null;
        api.get('/openapi').then(function (response) {
          self.definition = response.data;
        }).catch(function (error) {
          console.log(error);
        });
				self.refreshSession();
      },
			mounted(){
				let self = this;

			},
      methods: {
				refreshSession(){
					let self = this;
	        api.get('/me').then(function (response) {
	          if(response.data.code !== undefined){
	            console.log('response', response);
	          } else {
	            self.userData = response.data;
	            api.get('/records/mt_users/' + response.data.id, {
	              params: {
	                join: [
	                  //'roles',
										'mt_permissions',
	                  'mt_users',
	                  'mt_orders',
	                ],
	              }
	            }).then(function (response) {
	              if(response.data.code !== undefined){
	                console.log('response', response);
	              } else {
	                self.userData = response.data;
									self.runTheme();
	              }
	            }).catch(function (error) {
	              console.log('error userData', error.response);
	              self.forms.login.msg = error.response.data.message;
	            });
	          }
	        }).catch(function (error) {
	          console.log('error userData', error.response);
	          self.forms.login.msg = error.response.data.message;
	        });
				},
        signin(){
          let self = this;
          self.forms.login.msg = null;
          console.log("login", self.forms.login);
          console.log("enviando", self.forms.login.username, self.forms.login.hash);

          api.post('/login', {
            username: self.forms.login.username,
            password: self.forms.login.hash,
          }).then(function (response) {
            console.log(response.data);
            location.reload();
            self.forms.login.msg = "welcome";
          }).catch(function (error) {
            self.forms.login.msg = error.response.data.message;
            console.log('error', error.response.data.message);
            console.error(error);
          });
        },
        signout(){
          let self = this;
          api.post('/logout', {
          }).then(function (response) {
            console.log(response.data);
            location.reload();
          }).catch(function (error) {
            console.log('error', error.response.data.message);
            console.error(error);
          });
        },
				runTheme(){
					let self = this;
					self.main_scripts();
					self.color_switcher();
				},
				color_switcher(){
					let self = this;
					$('#color-switcher-button').on('click',function(){
						$("#color-switcher-content").stop().toggle(200);
						return false;
					});
					$("#color-switcher-content").on('click','.js__change_color',function(event){
						event.preventDefault();
						if (!$(this).hasClass('active')){
							$("#color-switcher-content .active").removeClass("active");
							if($("#custom-color-themes").length){
								$("#custom-color-themes").remove();
							}
							if ($(this).data('color') != 'blue'){
								$("body").append('<link id="custom-color-themes" rel="stylesheet" href="assets/styles/color/' + $(this).data('color') + '.min.css">');
							}
							$(this).addClass('active');
						}
						return false;
					});
					$("#color-reset").on('click',function(){
						var selector = $('#color-switcher-content .js__change_color[data-color="blue"]');
						if($("#custom-color-themes").length){
							$("#custom-color-themes").remove();
						}
						if (selector.data('color') != 'blue'){
							$("body").append('<link id="custom-color-themes" rel="stylesheet" href="assets/styles/color/' + selector.data('color') + '.min.css">');
						}
						$('#color-switcher-content .js__change_color').removeClass('active');
						selector.addClass('active');
					});
					$("#wrapper").on('click',function(event){
						if ($(event.target).attr('id') == 'color-switcher' || $(event.target).parents('#color-switcher').length){

						}else{
							$("#color-switcher-content").stop().hide(200);
						}
					})
				},
				main_scripts(){
					let self = this;
					(function($) {
						"use strict";
						var Core = {};
						NProgress.start();
						$(document).ready(function(){
							Core.module.init();
							Core.plugin.init();
							if ($('[data-toggle="tooltip"]').length) $('[data-toggle="tooltip"]').tooltip() //Enable tooltip
							return false;
						});
						$(window).on("load",function(){
							Core.plugin.isotope.init();
							Core.func.resizeNotice();
							NProgress.done();
							return false;
						});
						$(window).on("resize",function(){
							Core.func.resizeNotice();
							Core.func.getChart();
							return false;
						})
						$(".js__full_screen").on('click',function(){
							$(document).fullScreen(true);
						});
						Core.module = {
							init : function(){
								Core.module.accordion();
								Core.module.card();
								Core.module.css($(".js__width"),"width");
								Core.module.dropDown("js__drop_down",false);
								Core.module.logout();
								Core.module.menu();
								Core.module.tab(".js__tab","li");
								Core.module.toggle();
								Core.module.todo();
								return false;
							},
							accordion: function(){
								$(".js__accordion").each(function(){
									var selector = $(this);
									selector.find(".js__control").on("click",function(event){
										event.preventDefault();
										if ($(this).parent().hasClass("active")){
											$(this).parent().removeClass("active");
											$(this).next().stop().slideUp(400);
										}else{
											var current = $(this);
											selector.find(".active").children(".js__content").stop().slideUp(400);
											selector.find(".active").removeClass("active");
											$(this).parent().addClass("active");
											$(this).next(".js__content").slideDown(400,function(){
												if (selector.parents(".main-menu").length){
													$(".main-menu .content").mCustomScrollbar("scrollTo",current,{
														// scroll as soon as clicked
														timeout:0,
														// scroll duration
														scrollInertia:200,
													});
												}
											});
										}
									});
								});
								return false;
							},
							card: function(){
								$(".js__card").each(function(){
									var selector = $(this);
									selector.on("click",".js__card_minus",function(){
										selector.toggleClass("card-closed");
										selector.find(".js__card_content").stop().slideToggle(400);
									});
									selector.on("click",".js__card_remove",function(){
										selector.slideUp(400);
									});
								});
								return false;
							},
							css : function(selector,name,data){
								if (!data){
									data = name;
								}
								selector.each(function(){
									var raw = $(this).data(data);
									if (raw){
										var dict = {};
										dict[name] = raw
										$(this).css(dict);
									}
								});
								return false;
							},
							dropDown : function(selectorTxt,isMobile){
								var selector = $("." + selectorTxt);
								selector.each(function(){
									var current_selector = $(this);
									current_selector.on("click",".js__drop_down_button",function(event){
										event.preventDefault();
										if ($(window).width() < 1025 || isMobile === false){
											if (current_selector.hasClass("active")){
												current_selector.removeClass("active");
											}else{
												selector.removeClass("active");
												current_selector.addClass("active");
											}
										}
										return false;
									});
								});
								$("html").on("click",function(event){
									var selector = $(event.target);
									if (!(selector.hasClass(selectorTxt) || selector.parents("." + selectorTxt).length)){
										$("." + selectorTxt + ".active").removeClass("active");
									}
								});
								return false;
							},
							logout: function(){
								$(".js__logout").on("click",function(event){
									event.preventDefault();
									swal({
										title: "Logout?",
										text: "Are you sure you want to logout?",
										type: "warning",
										showCancelButton: true,
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Yes, I'm out!",
										cancelButtonText: "No, stay plx!",
										closeOnConfirm: false,
										closeOnCancel: true,
										confirmButtonColor: '#f60e0e',
									}, function(isConfirm){
										if (isConfirm) {
											swal({
												title : "Logout success",
												text: "See you later!",
												type: "success",
												confirmButtonColor: '#304ffe',
											});
										} else {
										}
									});
									return false;
								});
							},
							menu: function(){
								$(".js__menu_mobile").on("click",function(){
									$("html").toggleClass("menu-active");
									$(window).trigger("resize");
								});
								$(".js__menu_close").on("click",function(){
									$("html").removeClass("menu-active");
								});
								$("body").on("click",function(event){
									if ($("html.menu-active").length && $(window).width() < 800){
										var selector = $(event.target);
										if (!(selector.hasClass("main-menu") || selector.hasClass("js__menu_mobile") || selector.parents(".main-menu").length || selector.parents(".js__menu_mobile").length)){
											$("html").removeClass("menu-active");
										}
									}
								});
								return false;
							},
							tab: function(name,index_name){
								$(".js__tab").each(function(){
									var selector = $(this);
									selector.on("click",".js__tab_control",function(event){
										var target = $(this).data("target");
										event.preventDefault();
										selector.find(".js__tab_content").removeClass("js__active");
										selector.find(".js__tab_control").removeClass("js__active");
										$(this).addClass("js__active");
										if (target){
											$(target).addClass("js__active");
										}else{
											var index;
											if (index_name){
												index = $(this).parents(index_name).first().index()
											}else{
												index = $(this).index()
											}

											selector.find(".js__tab_content").eq(index).addClass("js__active");
										}
										return false;
									});
								});
								return false;
							},
							todo: function(){
								$(".js__todo_widget").each(function(){
									var selector = $(this),
										list = $(this).find(".js__todo_list"),
										val = $(this).find(".js__todo_value"),
										button = $(this).find(".js__todo_button");
									button.on("click",function(){
										if (val.val() != ""){
											var rnd = Math.floor((Math.random() * 100000000) + 1);
											list.append('<div class="todo-item"><div class="checkbox"><input type="checkbox" id="todo-'+ rnd +'"><label for="todo-'+ rnd +'">' + val.val() +'</label></div></div>')
											val.val("");
										}else{
											alert("You must enter task name.")
										}
										return false;
									});
								});
								return false;
							},
							toggle: function(){
								$(".js__toggle_open").on("click",function(event){
									event.preventDefault();
									if ($($(this).data("target")).hasClass("active")){

									}else{
										$(".js__toggle").removeClass("active")
									}
									$($(this).data("target")).toggleClass("active");
									return false;
								});
								$(".js__toggle_close").on("click",function(event){
									event.preventDefault();
									$(this).parents(".js__toggle").removeClass("active");
									return false;
								});
								$("body").on("click",function(event){
									if ($(".js__toggle").hasClass("active")){
										var selector = $(event.target);
										if (!(selector.hasClass("js__toggle_open") || selector.hasClass("js__toggle") || selector.parents(".js__toggle_open").length || selector.parents(".js__toggle").length)){
											$(".js__toggle").removeClass("active")
										}
									}
								});
								return false;
							}
						}
						Core.func = {
							childReturnWidth : function(selector,current_width){
								if (selector.children("li").children(".sub-menu").length){
									var max_width = 0;
									selector.children("li").children(".sub-menu").each(function(){
										var this_width = Core.func.childReturnWidth($(this),current_width + $(this).outerWidth());
										if (this_width > max_width){
											max_width = this_width;
										}
									});
									return max_width;
								}else{
									return current_width;
								}
							},
							getResponsiveSettings: function(selector){
								var responsive = selector.data("responsive"),
									json = [];
								if (responsive){
									while(responsive.indexOf("'") > -1){
										responsive = responsive.replace("'",'"');
									}
									var json_temp = JSON.parse(responsive);
									$.each(json_temp, function (key, data) {
										json[json.length] = {
											breakpoint: key,
											settings: {
												slidesToShow: data,
												slidesToScroll: data,
											}
										}
									});
								}
								return json;
							},
							getChart: function(){
								$(".js__chart").each(function(){
									var selector = $(this),
										chart = selector.data("chart"),
										json = [],
										id = selector.attr("id"),
										type = selector.data("type"),
										options, dataTable,chart_draw,themes = ($(this).hasClass('black-chart') ? '#1b1c1c' : '#ffffff');
									if (chart){
										var json_temp = chart.split("|"),
											i,j;
										for (i = 0; i < json_temp.length; i++){
											json_temp[i] = json_temp[i].trim();
											json[i] = json_temp[i].split("/");
											for(j = 0; j < json[i].length; j++){
												if (json[i][j].indexOf("'") > -1){
													while(json[i][j].indexOf("'") > -1){
														json[i][j] = json[i][j].replace("'","");
													}
													json[i][j] = json[i][j].trim();
												}else{
													if (json[i][j].indexOf(".") > -1){
														json[i][j] = parseFloat(json[i][j]);
													}else{
														json[i][j] = parseInt(json[i][j],10);
													}
												}
											}
										}
										dataTable = google.visualization.arrayToDataTable(json);
										if ($(this).hasClass('black-chart')){
											switch (type){
												case "circle":
													options = {
														chartArea:{left:0,top:0,width:'100%',height:'75%'},
														colors: ["#304ffe", "#f60e0e","#ffa000"],
														fontName: 'Poppins',
														backgroundColor: themes,
														legend:{
															position: 'bottom',
															textStyle: {
																color: '#484848'
															}
														},
														vAxis: {
															baselineColor: '#484848',
															gridlines: {
																color: "#484848"
															},
															textStyle:{
																color: '#484848'
															}
														},
														hAxis: {
															textStyle:{
																color: '#484848'
															}
														}
													}
													chart_draw = new google.visualization.PieChart(document.getElementById(id));
													break;
												case "donut":
													options = {
														pieHole: 0.3,
														chartArea:{left:0,top:0,width:'100%',height:'75%'},
														legend:{
															position: 'bottom',
															textStyle: {
																color: '#484848'
															}
														},
														colors: ["#304ffe", "#f60e0e","#ffa000"],
														fontName: 'Poppins',
														backgroundColor: themes
													}
													chart_draw = new google.visualization.PieChart(document.getElementById(id));
													break;
												case "column":
													options = {
														chartArea:{left:30,top:10,width:'100%',height:'80%'},
														colors: ["#304ffe"],
														fontName: 'Poppins',
														backgroundColor: themes,
														vAxis: {
															baselineColor: '#484848',
															gridlines: {
																color: "#484848"
															},
															textStyle:{
																color: '#484848'
															}
														},
														hAxis: {
															textStyle:{
																color: '#484848'
															}
														}
													}
													chart_draw = new google.visualization.ColumnChart(document.getElementById(id));
													break;
												case "curve":
													options = {
														chartArea:{left:30,top:10,width:'90%',height:'80%'},
														curveType: 'function',
														colors: ["#304ffe", "#f60e0e","#ffa000"],
														fontName: 'Poppins',
														backgroundColor: themes,
														vAxis: {
															baselineColor: '#484848',
															gridlines: {
																color: "#484848"
															},
															textStyle:{
																color: '#484848'
															}
														},
														hAxis: {
															textStyle:{
																color: '#484848'
															}
														}
													}
													chart_draw = new google.visualization.LineChart(document.getElementById(id));
													break;
												case "line":
													options = {
														chartArea:{left:30,top:10,width:'90%',height:'80%'},
														fontName: 'Poppins',
														backgroundColor: themes,
														vAxis: {
															baselineColor: '#484848',
															gridlines: {
																color: "#484848"
															},
															textStyle:{
																color: '#484848'
															}
														},
														hAxis: {
															textStyle:{
																color: '#484848'
															}
														}
													}
													chart_draw = new google.visualization.LineChart(document.getElementById(id));
													break;
												case "area":
													options = {
														chartArea:{left:50,top:20,width:'100%',height:'70%'},
														legend: {
															position: 'bottom'
														},
														fontName: 'Poppins',
														backgroundColor: themes,
														vAxis: {
															baselineColor: '#484848',
															gridlines: {
																color: "#484848"
															},
															textStyle:{
																color: '#484848'
															}
														},
														hAxis: {
															textStyle:{
																color: '#484848'
															}
														}
													}
													chart_draw = new google.visualization.AreaChart(document.getElementById(id));
													break;
											}
										}else{
											switch (type){
												case "circle":
													options = {
														chartArea:{left:0,top:0,width:'100%',height:'75%'},
														legend:{
															position: 'bottom'
														},
														colors: ["#304ffe", "#f60e0e","#ffa000"],
														fontName: 'Poppins',
														backgroundColor: themes
													}
													chart_draw = new google.visualization.PieChart(document.getElementById(id));
													break;
												case "donut":
													options = {
														pieHole: 0.3,
														chartArea:{left:0,top:0,width:'100%',height:'75%'},
														legend:{
															position: 'bottom',
														},
														colors: ["#304ffe", "#f60e0e","#ffa000"],
														fontName: 'Poppins',
														backgroundColor: themes
													}
													chart_draw = new google.visualization.PieChart(document.getElementById(id));
													break;
												case "column":
													options = {
														chartArea:{left:30,top:10,width:'100%',height:'80%'},
														colors: ["#304ffe"],
														fontName: 'Poppins',
														backgroundColor: themes
													}
													chart_draw = new google.visualization.ColumnChart(document.getElementById(id));
													break;
												case "curve":
													options = {
														chartArea:{left:30,top:10,width:'90%',height:'80%'},
														curveType: 'function',
														colors: ["#304ffe", "#f60e0e","#ffa000"],
														fontName: 'Poppins',
														backgroundColor: themes
													}
													chart_draw = new google.visualization.LineChart(document.getElementById(id));
													break;
												case "line":
													options = {
														chartArea:{left:30,top:10,width:'90%',height:'80%'},
														fontName: 'Poppins',
														backgroundColor: themes
													}
													chart_draw = new google.visualization.LineChart(document.getElementById(id));
													break;
												case "area":
													options = {
														chartArea:{left:50,top:20,width:'100%',height:'70%'},
														legend: {
															position: 'bottom'
														},
														fontName: 'Poppins',
														backgroundColor: themes
													}
													chart_draw = new google.visualization.AreaChart(document.getElementById(id));
													break;
											}
										}
						        		chart_draw.draw(dataTable, options);
									}
								});
							},
							resizeNotice : function(){
								$(".notice-popup").each(function(){
									var selector = $(this),
										space = (parseInt(selector.data("space"),10) > 0) ? parseInt(selector.data("space"),10) : 75,
										window_height = $(window).height() - space;
									selector.attr("style","");
									if (selector.height() > window_height){
										selector.css({
											"height" : window_height
										});
									}
								});
							}
						}
						Core.plugin = {
							init : function(){
								Core.plugin.chart();
								Core.plugin.mCustomScrollbar();
								Core.plugin.select2();
								Core.plugin.ui.accordion();
								Core.plugin.ui.slider();
								Core.plugin.ui.sortable();
								Core.plugin.ui.tabs();
								Core.plugin.waves();
								Core.plugin.isotope.filter();
								return false;
							},
							chart: function(){
								if ($(".js__chart").length){
									google.charts.load("current", {packages:["corechart"]});
									google.charts.setOnLoadCallback(Core.func.getChart);
								}
								return false;
							},
							isotope : {
								init : function(){
									setTimeout(function(){
										$(".js__filter_isotope").each(function(){
											var selector = $(this);
											selector.find(".js__isotope_items").isotope({
												itemSelector: ".js__isotope_item",
												layoutMode: 'cellsByRow'
											});
										});
									},100);
									return false;
								},
								filter : function(){
									$(".js__filter_isotope").each(function(){
										var selector = $(this);
										selector.on("click",".js__filter_control",function(event){
											event.preventDefault();
											if (!($(this).hasClass(".js__active"))){
												selector.find(".js__filter_control").removeClass("js__active");
												$(this).addClass("js__active");
												selector.find(".js__isotope_items").isotope({
													filter : $(this).data("filter")
												});
											}
											return false;
										});
									});
									return false;
								}
							},
							mCustomScrollbar:function(){
								if ($(".main-menu").length){
									$(".main-menu .content").mCustomScrollbar();
								}
								if ($(".notice-popup").length){
									$(".notice-popup .content").mCustomScrollbar();
								}
								return false;
							},
							select2 : function(){
								$(".js__select2").each(function(){
									var minResults = $(this).data("min-results"),
										classContainer = $(this).data("container-class");
									if (minResults){
										if (minResults === "Infinity"){
											$(this).select2({
												minimumResultsForSearch: Infinity,
											});
										}else{
											$(this).select2({
												minimumResultsForSearch: parseInt(minResults,10)
											});
										}
										if (classContainer){
											$(this).on("select2:open", function(){
												$(".select2-container--open").addClass(classContainer);
												return false;
											});
										}
									}else{
										$(this).select2();
									}
								});
								return false;
							},
							ui: {
								accordion: function(){
									if ($( ".js__ui_accordion" ).length){
										$( ".js__ui_accordion" ).accordion({
											heightStyle: "content",
											collapsible: true
										});
									}
									return false;
								},
								slider: function(){
									$(".js__ui_slider").each(function(){
										var selector = $(this),
											slider = selector.find(".js__slider_range"),
											amount = selector.find(".js__slider_amount"),
											min = parseInt(selector.data("min"),10),
											max = parseInt(selector.data("max"),10),
											start = parseInt(selector.data("value-1"),10),
											end = parseInt(selector.data("value-2"),10),
											range = selector.data("range");

										if (end > 0){
											slider.slider({
												range: true,
												min: min,
												max: max,
												values: [ start, end ],
												slide: function( event, ui ) {
													amount.val( "$" + ui.values[0] + " - $" + ui.values[1] );
												}
											});
											amount.val( "$" + slider.slider( "values", 0 ) + " - $" + slider.slider( "values", 1 ) );
										}else{
											slider.slider({
												range: range,
												min: min,
												max: max,
												value: start,
												slide: function( event, ui ) {
													amount.val( "$" +  ui.value );
												}
											});
											amount.val("$" + slider.slider( "value" ) );
										}
									});
									return false;
								},
								sortable: function(){
									if ($(".js__sortable").length){
										$(".js__sortable").sortable({
											revert: true,
											start: function(e, ui){
												ui.placeholder.height(ui.item.height() - 20);
												ui.placeholder.css('visibility', 'visible');
											}
										});
									}
									return false;
								},
								tabs : function(){
									if ($(".js__ui_tab").length){
										$(".js__ui_tab").tabs();
									}
									return false;
								}
							},
							waves: function(){
								if ($('.js__control').length){
									Waves.attach('.js__control');
									Waves.init();
								}
								return false;
							}
						}
					})(jQuery);
				},
      },
    };

    var util = {
      methods: {
        resolve: function (path, obj) {
          return path.reduce(function(prev, curr) {
            return prev ? prev[curr] : undefined
          }, obj || this);
        },
        getDisplayColumn: function (columns) {
          var index = -1;
          var names = ['name', 'username', 'title', 'description', 'names', 'identification_number'];
          for (var i in names) {
            index = columns.indexOf(names[i]);
            if (index >= 0) {
              return names[i];
            }
          }
          return columns[0];
        },
        getPrimaryKey: function (properties) {
          for (var key in properties) {
            if (properties[key]['x-primary-key']) {
              return key;
            }
          }
          return false;
        },
        getReferenced: function (properties) {
          var referenced = [];
          for (var key in properties) {
            if (properties[key]['x-referenced']) {
              for (var i = 0; i < properties[key]['x-referenced'].length; i++) {
                referenced.push(properties[key]['x-referenced'][i].split('.'));
              }
            }
          }
          return referenced;
        },
        getReferences: function (properties) {
          var references = {};
          for (var key in properties) {
            if (properties[key]['x-references']) {
              references[key] = properties[key]['x-references'];
            } else {
              references[key] = false;
            }
          }
          return references;
        },
        getProperties: function (action, subject, definition) {
          if (action == 'list') {
            path = ['components', 'schemas', action + '-' + subject, 'properties', 'records', 'items', 'properties'];
          } else {
            path = ['components', 'schemas', action + '-' + subject, 'properties'];
          }
          return this.resolve(path, definition);
        }
      }
    };

    var orm = {
      methods: {
        readRecord: function () {
          this.id = this.$route.params.id;
          this.subject = this.$route.params.subject;
          this.record = null;
          var self = this;
          api.get('/records/' + this.subject + '/' + this.id).then(function (response) {
            self.record = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        },
        readRecords: function () {
          this.subject = this.$route.params.subject;
          this.records = null;
          var url = '/records/' + this.subject;
          var params = [];
          for (var i=0;i<this.join.length;i++) {
            params.push('join='+this.join[i]);
          }
          if (this.field) {
            params.push('filter='+this.field+',eq,'+this.id);
          }
          if (params.length>0) {
            url += '?'+params.join('&');
          }
          var self = this;
          api.get(url).then(function (response) {
            self.records = response.data.records;
          }).catch(function (error) {
            console.log(error);
          });
        },
        readOptions: function() {
          this.options = {};
          var self = this;
          for (var key in this.references) {
            var subject = this.references[key];
            if (subject !== false) {
              var properties = this.getProperties('list', subject, this.definition);
              var displayColumn = this.getDisplayColumn(Object.keys(properties));
              var primaryKey = this.getPrimaryKey(properties);
              api.get('/records/' + subject + '?include=' + primaryKey + ',' + displayColumn).then(function (subject, primaryKey, displayColumn, response) {
                self.options[subject] = response.data.records.map(function (record) {
                  return {key: record[primaryKey], value: record[displayColumn]};
                });
                self.$forceUpdate();
              }.bind(null, subject, primaryKey, displayColumn)).catch(function (error) {
                console.log(error);
              });
            }
          }
        },
        updateRecord: function () {
          api.put('/records/' + this.subject + '/' + this.id, this.record).then(function (response) {
            console.log(response.data);
          }).catch(function (error) {
            console.log(error);
          });
          router.push({name: 'List', params: {subject: this.subject}});
        },
        initRecord: function () {
          this.record = {};
          for (var key in this.properties) {
            if (!this.properties[key]['x-primary-key']) {
              if (this.properties[key].default) {
                this.record[key] = this.properties[key].default;
              } else {
                this.record[key] = '';
              }
            }
          }
        },
        createRecord: function() {
          var self = this;
          api.post('/records/' + this.subject, this.record).then(function (response) {
            self.record.id = response.data;
          }).catch(function (error) {
            console.log(error);
          });
          router.push({name: 'List', params: {subject: this.subject}});
        },
        deleteRecord: function () {
          api.delete('/records/' + this.subject + '/' + this.id).then(function (response) {
            console.log(response.data);
          }).catch(function (error) {
            console.log(error);
          });
          router.push({name: 'List', params: {subject: this.subject}});
        }
      }
    };

    Vue.component('orders-component', {
      template: '#orders',
      props: ['orders']
    })

    Vue.component('menu-component', {
      mixins: [util, orm],
      template: '#menu',
      props: ['subjects']
    })

		var ViewProfile = Vue.extend({
			mixins: [util],
			template: '#view-profile',
			props: ['definition'],
			data: function () {
				return {
					id: this.$route.params.id,
					subject: 'mt_users',
					params: {
						join: [
							'mt_users',
							// 'roles',
							// 'identifications_types',
						],
					},
					record: null
				};
			},
			created: function () {
				this.readRecord();
			},
			computed: {
				properties: function () {
					return this.getProperties('list', this.subject, this.definition);
				},
				related: function () {
          return (this.referenced.filter(function (value) { return value; }).length > 0);
        },
        join: function () {
          return Object.values(this.references).filter(function (value) { return value; });
        },
        references: function () {
          return this.getReferences(this.properties);
        },
        referenced: function () {
          return this.getReferenced(this.properties);
        },
        primaryKey: function () {
          return this.getPrimaryKey(this.properties);
        }
			},
			methods: {
				readRecord: function () {
          this.id = this.$route.params.id;
          this.record = null;
          var self = this;
					api.get('/records/' + this.subject + '/' + this.id, {params:this.params}).then(function (response) {
            self.record = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        },
        referenceText(subject, record) {
          var properties = this.getProperties('read', subject, this.definition);
          var displayColumn = this.getDisplayColumn(Object.keys(properties));
          return record[displayColumn];
        },
        referenceId(subject, record) {
          var properties = this.getProperties('read', subject, this.definition);
          var primaryKey = this.getPrimaryKey(properties);
          return record[primaryKey];
        },
			}
		});

    var Home = Vue.extend({
      mixins: [util],
      template: '#home'
    });

    var List = Vue.extend({
      mixins: [util, orm],
      template: '#list',
      data: function () {
        return {
          records: null,
          subject: this.$route.params.subject,
          field: this.$route.params.field,
          id: this.$route.params.id
        };
      },
      props: ['definition'],
      created: function () {
        this.readRecords();
      },
      computed: {
        related: function () {
          return (this.referenced.filter(function (value) { return value; }).length > 0);
        },
        join: function () {
          return Object.values(this.references).filter(function (value) { return value; });
        },
        properties: function () {
          return this.getProperties('list', this.subject, this.definition);
        },
        references: function () {
          return this.getReferences(this.properties);
        },
        referenced: function () {
          return this.getReferenced(this.properties);
        },
        primaryKey: function () {
          return this.getPrimaryKey(this.properties);
        }
      },
      methods: {
        referenceText(subject, record) {
          var properties = this.getProperties('read', subject, this.definition);
          var displayColumn = this.getDisplayColumn(Object.keys(properties));
          return record[displayColumn];
        },
        referenceId(subject, record) {
          var properties = this.getProperties('read', subject, this.definition);
          var primaryKey = this.getPrimaryKey(properties);
          return record[primaryKey];
        }
      }
    });

		var View = Vue.extend({
      mixins: [util, orm],
      template: '#view',
      props: ['definition'],
      data: function () {
        return {
          id: this.$route.params.id,
          subject: this.$route.params.subject,
          record: null
        };
      },
      created: function () {
        this.readRecord();
      },
      computed: {
        properties: function () {
          return this.getProperties('read', this.subject, this.definition);
        }
      },
      methods: {
      }
    });

    var Edit = Vue.extend({
      mixins: [util, orm],
      template: '#update',
      props: ['definition'],
      data: function () {
        return {
          id: this.$route.params.id,
          subject: this.$route.params.subject,
          record: null,
          options: {}
        };
      },
      created: function () {
        this.readRecord();
        this.readOptions();
      },
      computed: {
        properties: function () {
          return this.getProperties('update', this.subject, this.definition);
        },
        primaryKey: function () {
          return this.getPrimaryKey(this.properties);
        },
        references: function () {
          return this.getReferences(this.properties);
        },
      },
      methods: {
      }
    });

    var Delete = Vue.extend({
      mixins: [util, orm],
      template: '#delete',
      data: function () {
        return {
          id: this.$route.params.id,
          subject: this.$route.params.subject
        };
      },
      methods: {
      }
    });

    var Add = Vue.extend({
      mixins: [util, orm],
      template: '#create',
      props: ['definition'],
      data: function () {
        return {
          id: this.$route.params.id,
          subject: this.$route.params.subject,
          record: null,
          options: {}
        };
      },
      created: function () {
        this.initRecord();
        this.readOptions();
      },
      computed: {
        properties: function () {
          return this.getProperties('create', this.subject, this.definition);
        },
        primaryKey: function () {
          return this.getPrimaryKey(this.properties);
        },
        references: function () {
          return this.getReferences(this.properties);
        }
      },
      methods: {
      }
    });

    var router = new VueRouter({
      linkActiveClass: 'active',
      routes:[
        { path: '/', component: Home},
        { path: '/:subject/create', component: Add, name: 'Add'},
				{ path: '/view-profile/:id', component: ViewProfile, name: 'ViewProfile'},
        { path: '/mt_users/read/:id', component: ViewProfile, name: 'View2'},
				{ path: '/:subject/read/:id', component: View, name: 'View'},
        { path: '/:subject/update/:id', component: Edit, name: 'Edit'},
        { path: '/:subject/delete/:id', component: Delete, name: 'Delete'},
        { path: '/:subject/list', component: List, name: 'List'},
        { path: '/:subject/list/:field/:id', component: List, name: 'Filter'}
      ]
    });

    app = new Vue({
      router: router,
      mixins: [pacmec],
      data: function () {
        return {
        };
      },
      created: function () {
        var self = this;
      },
      methods: {

      },
    }).$mount('#app');
  </script>

</body>
</html>
