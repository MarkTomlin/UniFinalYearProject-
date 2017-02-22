<!DOCTYPE html>
<?php
	session_start(); ?>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
  <title>My LOL Picker</title>

  <!-- CSS  -->
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <script type='text/javascript'>
	function init()
	{
		$(document).ready(function() {
		$('select').material_select();
		});
	}
  </script>
</head>
<body onload='init()'>
  <nav class="light-blue lighten-1" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo"><i class="large mdi-action-home"></i></a>
      <ul class="right hide-on-med-and-down"> 
      <li>	
		<?php 
		if ($_SESSION["gatekeeper"] != "")
		{
		//Displays username at the top of the page
		echo "<a href='myStats.php'>My Stats</a></li><li><a href='logout.php'>Log Out</a>";
		}
		else
		{
		//Display login message
		echo "<a href='register.html'>Register</a></li><li><a href='login.html'>Log In</a>";
		}
		?>
	  </li>
      </ul>

      <ul id="nav-mobile" class="side-nav">
        <li><a href="#">Navbar Link</a></li>
      </ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
    </div>
  </nav>
  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <br><br>
      <h1 class="header center orange-text">My LOL Picker</h1>
      <div class="row center">
        <h5 class="header col s12 light">Get champion counter picks personalised to your experience</h5>
      </div>
	  <br /><br />
      <div class="row center">
		 <div class="row">
				<form class="col s12" action="searchResults.php" method="get">
				  <div class="row">
					<div class="input-field col s6">
					  <input id="sname" type="text" name="sname" class="validate">
					  <label for="sname">Summoner Name</label>
					</div>
					<div class="input-field col s5 offset-s1">
						<select name="server">
						  <option value="" disabled selected>Choose your server</option>
						  <option value="na" name="na">NA</option>
						  <option value="euw" name="euw">EUW</option>
						  <option value="eune" name="eune">EUNE</option>
						</select>
						<label>Server</label>
					</div>
				  </div>
				  <div class="row">
					<div class="input-field col s12">
					  <input id="champ" type="text" name="champ" class="validate">
					  <label for="champ">Opponent Champion</label>
					</div>
				  </div>
				   <button class="btn waves-effect waves-light" type="submit" >Find Counters<i class="mdi-content-send right"></i></button>
				</form>
		</div>
      </div>
      <br><br><br><br><br>

    </div>
  </div>

  <div class="container">
    <div class="section">
    </div>
    <br><br>

    <div class="section">

    </div>
  </div> 
  <br /><br /><br /><br /><br /><br /><br /><br />
  <footer class="page-footer cyan">
   
    <div class="footer-copyright">
      <div class="container">
      Made by Mark Tomlin
      </div>
    </div>
  </footer>


  <!--  Scripts-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
	<link rel="stylesheet" type="text/css" href="http://assets.cookieconsent.silktide.com/current/style.min.css"/>
	<script type="text/javascript" src="http://assets.cookieconsent.silktide.com/current/plugin.min.js"></script>
	<script type="text/javascript">
	// <![CDATA[
	cc.initialise({
		cookies: {},
		settings: {
			consenttype: "implicit"
		}
	});
	// ]]>
	</script>
	<!-- End Cookie Consent plugin -->


  </body>
</html>
