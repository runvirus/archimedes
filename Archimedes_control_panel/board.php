<?php require "login/loginheader.php"; 
require_once 'db.php';


class MyThing
{
    protected $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function mhash() {
        $row = $this->con->query("SELECT * FROM notification ORDER BY id DESC")->fetch_array();
        return $row["id"];
    }
	    public function files() {
        $row = $this->con->query("SELECT * FROM filelist ORDER BY id DESC")->fetch_array();
        return $row["id"];
    }
}


?>

<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Whisper Dashboard</title>

	<link href="login/img/favicon.144x144.png" rel="apple-touch-icon" type="image/png" sizes="144x144">
	<link href="login/img/favicon.114x114.png" rel="apple-touch-icon" type="image/png" sizes="114x114">
	<link href="login/img/favicon.72x72.png" rel="apple-touch-icon" type="image/png" sizes="72x72">
	<link href="login/img/favicon.57x57.png" rel="apple-touch-icon" type="image/png">
	<link href="login/img/favicon.png" rel="icon" type="image/png">
	<link href="login/img/favicon.ico" rel="shortcut icon">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
    <link rel="stylesheet" href="login/css/lib/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="login/css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="login/css/main.css">
</head>
<body class="with-side-menu">

		<header class="site-header">
	    <div class="container-fluid">
	
	        <a href="#" class="site-logo">
	            <img class="hidden-md-down" src="login/img/logo-2.png" alt="">
	            <img class="hidden-lg-up" src="login/img/logo-2-mob.png" alt="">
	        </a>
	
	        <button id="show-hide-sidebar-toggle" class="show-hide-sidebar">
	            <span>toggle menu</span>
	        </button>
	
	        <button class="hamburger hamburger--htla">
	            <span>toggle menu</span>
	        </button>
	        <div class="site-header-content">
	            <div class="site-header-content-in">
	                <div class="site-header-shown">
	                    
	
	                   
	                    <div class="dropdown user-menu">
	                        <button class="dropdown-toggle" id="dd-user-menu" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                            <img src="login/img/avatar-2-64.png" alt="">
	                        </button>
	                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
	                            
	                            <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-cog"></span>User Settings</a>
	                            <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-question-sign"></span>Help</a>
	                            <div class="dropdown-divider"></div>
	                            <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-log-out"></span>Logout</a>
	                        </div>
	                    </div>
	
	                    <button type="button" class="burger-right">
	                        <i class="font-icon-menu-addl"></i>
	                    </button>
	                </div><!--.site-header-shown-->
	
	            </div><!--site-header-content-in-->
	        </div><!--.site-header-content-->
	    </div><!--.container-fluid-->
	</header><!--.site-header-->

	<div class="mobile-menu-left-overlay"></div>
	<nav class="side-menu">
	    <ul class="side-menu-list">
	        <li class="grey with-sub">
	            <span>
	                <i class="font-icon font-icon-dashboard"></i>
	                <span class="lbl">Users</span>
	            </span>
	            <ul>
	                <li><a href="adduser.html"><span class="lbl">Add User</span></a></li>
	                <li><a href="changepass.html"><span class="lbl">Change password</span></a></li>
	                <li><a href="delusers.html"><span class="lbl">Remove users</span></a></li>

	            </ul>
	        </li>
	        <li class="brown with-sub">
	            <span>
	                <i class="font-icon glyphicon glyphicon-tint"></i>
	                <span class="lbl">Servers Settings</span>
	            </span>
	            <ul>
	                <li><a href="hostname.html"><span class="lbl">Add/Del HostName</span></a></li>
	                <li><a href="viewhostname.html"><span class="lbl">View Hostnames</span></a></li>
	            </ul>
	        </li>
	        <li class="purple with-sub">
	            <span>
	                <i class="font-icon font-icon-comments active"></i>
	                <span class="lbl">Files Settings</span>
	            </span>
	            <ul>
	                <li><a href="hashes.html"><span class="lbl">Add/Del hashes</span></a></li>
	                <li><a href="delhash.html"><span class="lbl">add Ban words</span></a></li>
	                <li><a href="banword.html"><span class="lbl">View All Hashes</span></a></li>
	            </ul>
	        </li>
	        <li class="red;purple with-sub">
	            <span>
	                <i class="font-icon glyphicon glyphicon-send"></i>
	                <span class="lbl">Alert</span></span>
	           	            </span>
	            <ul>
	                <li><a href="mfile.html"><span class="lbl">View Modified files</span></a></li>
	                <li><a href="bhash.html"><span class="lbl">View based on Hash</span></a></li>
	                <li><a href="bname.html"><span class="lbl">View based on Name</span></a></li>
	            </ul>
	        </li>
	
	    </section>
	</nav><!--.side-menu-->

	<div class="page-content">
		<div class="container-fluid">
			
		
			<div class="row">
	            <div class="col-xl-6">
	                
					
					 <div class="row">
	                    <div class="col-sm-6">
	                        <article class="statistic-box red">
	                            <div>
	                                <div class="number"><?php $obj = new MyThing($con);
$name1 = $obj->files();
echo $name1; ?></div>
	                                <div class="caption"><div>Files</div></div>
	                                <div class="percent">
	                                    <div class="arrow up"></div>
	                                    
	                                </div>
	                            </div>
	                        </article>
	                    </div><!--.col-->
	                    <div class="col-sm-6">
	                        <article class="statistic-box purple">
	                            <div>
	                                <div class="number"><?php $obj = new MyThing($con);
$name2 = $obj->mhash();
echo $name2; ?></div>
	                                <div class="caption"><div>Modified Files</div></div>
	                                <div class="percent">
	                                    <div class="arrow down"></div>
	                                    
	                                </div>
	                            </div>
	                        </article>
	                    </div><!--.col-->
	                    
	                </div><!--.row-->
					
					
					
	            </div><!--.col-->
	            <div class="col-xl-6">
	                <div class="row">
	                    <div class="col-sm-6">
	                        <article class="statistic-box yellow">
	                            <div>
	                                <div class="number">26</div>
	                                <div class="caption"><div>File Based on Hashes</div></div>
	                                <div class="percent">
	                                    <div class="arrow up"></div>
	                                    
	                                </div>
	                            </div>
	                        </article>
	                    </div><!--.col-->
	                    <div class="col-sm-6">
	                        <article class="statistic-box green">
	                            <div>
	                                <div class="number">12</div>
	                                <div class="caption"><div>File Based on Names</div></div>
	                                <div class="percent">
	                                    <div class="arrow down"></div>
	                                    <p>11%</p>
	                                </div>
	                            </div>
	                        </article>
	                   
	                </div><!--.row-->
	            </div><!--.col-->
	        </div><!--.row-->
			
						<br>
			<B>Latest files that have been changed<code></code>:</b>
			<table id="table-sm" class="table table-bordered table-hover table-sm">
				<thead>
				<tr>
					<th width="1">
						#
					</th>
					<th>Files Name</th>
					<th>Old Hash</th>
					<th>New Hash</th>
					<th width="120">Date</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>1</td>
					<td>Last quarter revene</td>
					<td class="color-blue-grey-lighter">dsada</td>
					<td class="color-blue-grey-lighter">dsada</td>
					
					<td>6 minets ago</td>
				</tr>
				<tr>
					<td>1</td>
					<td>Last quarter revene</td>
					<td class="color-blue-grey-lighter">adsdasdaas.</td>
					<td class="color-blue-grey-lighter">Revene for l13, whith...</td>
					
					<td>6 minets ago</td>
				</tr>
				<tr>
					<td>1</td>
					<td>Last quarter revene</td>
					<td class="color-blue-grey-lighter">Revene  year 2013, whith...</td>
				<td class="color-blue-grey-lighter">Revenete America for year 2013, whith...</td>
			<td>6 minets ago</td>
				</tr>
				<tr>
					<td>1</td>
					<td>Last quarter revene</td>
					<td class="color-blue-grey-lighter">Revene for ar 2013, whith...</td>
					<td class="color-blue-grey-lighter">Revene foyear 2013, whith...</td>
			<td>6 minets ago</td>
				</tr>
				<tr>
					<td>1</td>
					<td>Last quarter revene</td>
					<td class="color-blue-grey-lighter">Revenear 2013, whith...</td>
			<td class="color-blue-grey-lighter">Revene for last quwhith...</td>
			
					<td>6 minets ago</td>
				</tr>
				</tbody>
			</table>
			
			<br>
			<B>Latest files Based on hashes<code></code>:</b>
			<table id="table-sm" class="table table-bordered table-hover table-sm">
				<thead>
				<tr>
					<th width="1">
						#
					</th>
					<th>Files Name</th>
					<th>Hash</th>
					<th width="120">Date</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>1</td>
					<td>Last quarter revene</td>
					<td class="color-blue-grey-lighter">dsada</td>
					
					<td>6 minets ago</td>
				</tr>
				<tr>
					<td>1</td>
					<td>Last quarter revene</td>
					<td class="color-blue-grey-lighter">adsdasdaas.</td>
					
					<td>6 minets ago</td>
				</tr>
				<tr>
					<td>1</td>
					<td>Last quarter revene</td>
					<td class="color-blue-grey-lighter">Revene  year 2013, whith...</td>
			<td>6 minets ago</td>
				</tr>
				<tr>
					<td>1</td>
					<td>Last quarter revene</td>
					<td class="color-blue-grey-lighter">Revene for ar 2013, whith...</td>
			<td>6 minets ago</td>
				</tr>
				<tr>
					<td>1</td>
					<td>Last quarter revene</td>
					<td class="color-blue-grey-lighter">Revenear 2013, whith...</td>
			
					<td>6 minets ago</td>
				</tr>
				</tbody>
			</table>
			
			
		</div><!--.container-fluid-->
	</div><!--.page-content-->

	<script src="login/js/lib/jquery/jquery.min.js"></script>
	<script src="login/js/lib/tether/tether.min.js"></script>
	<script src="login/js/lib/bootstrap/bootstrap.min.js"></script>
	<script src="login/js/plugins.js"></script>

<script src="login/js/app.js"></script>
</body>
</html>