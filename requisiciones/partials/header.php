<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisiciones</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="design/css/styles.css">
    <link rel="icon" type="imge/png" href="../assets/images/favicon.ico">

    <!--Datatables-->
	<link href="../assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/plugins/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/plugins/datatables/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" />

	<!-- CSS bootstrap -->
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<!-- iconos fontawesom -->
	<link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
	<!-- CSS general -->
	<link href="../assets/css/style.css" rel="stylesheet" type="text/css">
	<link href="../assets/css/edit-text.css" rel="stylesheet" type="text/css">
	<link href="../assets/css/summernote.css" rel="stylesheet" type="text/css">
	
	<!-- Sweet Alert -->
    <link href="../assets/plugins/sweetalert2/sweetalert2.css" rel="stylesheet" type="text/css">
	
	<!-- CSS alerts-->
	<link href="../assets/css/alertas.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="../assets/css/newStyles.css">
</head>
<body>
<div class="header-bg">
		<!-- Navigation Bar-->
		<header id="topnav">
			<div class="topbar-main">
				<div class="container-fluid">
					<!-- Logo-->
					<div>
						<a href="#" class="logo">
							<img src="../assets/images/logo-light.png" class="logo-lg" alt="" height="26">
							<img src="../assets/images/logo-sm.png" class="logo-sm" alt="" height="28">
						</a>
					</div>
					<!-- End Logo-->
					<div class="menu-extras topbar-custom navbar p-0">
						<ul class="mb-0 nav navbar-right ml-auto list-inline">
							<li class="list-inline-item notification-list d-none d-sm-inline-block">
								<a href="#" id="btn-fullscreen"
									class="waves-effect waves-light notification-icon-box"><i
										class="fas fa-expand"></i></a>
							</li>
							<li class="dropdown">
								<a href="" class="dropdown-toggle profile waves-effect waves-light"
									data-toggle="dropdown" aria-expanded="true">
									<span class="profile-username">
                    <?php echo $usuario["persona"]["nombres"]; ?> <span class="mdi mdi-chevron-down font-15"></span>
                  </span>
								</a>
								<ul class="dropdown-menu">
									<li class="dropdown-divider"></li>
									<li><a href="../log-out.php" class="dropdown-item"> Salir</a></li>
								</ul>
							</li>
							<li class="menu-item dropdown notification-list list-inline-item">
								<!-- Mobile menu toggle-->
								<a class="navbar-toggle nav-link">
									<div class="lines">
										<span></span>
										<span></span>
										<span></span>
									</div>
								</a>
								<!-- End mobile menu toggle-->
							</li>
						</ul>
					</div>
					<!-- end menu-extras -->
					<div class="clearfix"></div>
				</div>
				<!-- end container -->
			</div>
			<!-- end topbar-main -->

		<?php include 'partials/nav.php'; ?>

		</header>
		<!-- End Navigation Bar-->
	</div>
	<!-- header-bg -->