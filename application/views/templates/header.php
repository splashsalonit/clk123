<html>
<head>
	<title><?php echo $name . ' Page'; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css">

<!-- the following script it will go in a seperate file later -->
	<script type="text/javascript">
	    function goToNewPage()
	    {
	        var url = document.getElementById('locations').value;
	        
	        if(url != 'none') {
	            window.location = url;
	        }
	    }
	</script>
</head>
<body>
	<?php
		// Other incoming Variables //

		//echo "Your Username is: " . $username . "<br>";
		//echo "Your e-mail is: " . $email . "<br>";
		//echo "Your Password is: " . $password . "<br>";
	?>
	<div id="header">
		<nav><?php 
				echo 'Hello <b id="welcome"><i>' . $name . '</i>! </b>';

				if ($name == 'Admin')
				{
					echo '<a href="' . base_url('index.php/main/home_admin') . '">Home</a>';
					echo '<a href="' . base_url('index.php/main/soho') . '">Soho</a>';
				} 
				else 
				{
					echo '<a href="' . base_url('index.php/main/home_user') . '">Home</a>';
					echo '<a href="' . base_url('index.php/main/clock_in_user') . '">Clock In</a>';
					echo '<a href="' . base_url('index.php/main/clock_out_user') . '">Clock Out</a>';
				}

				echo '<a href="' . base_url('index.php/user_authentication/logout') . '">Logout</a>'; 
			?>
		</nav>		
	</div>

