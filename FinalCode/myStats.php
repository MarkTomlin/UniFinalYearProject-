<!DOCTYPE html>
<?php
	session_start(); 
	if ($_SESSION["gatekeeper"] == "")
	{
		header ("Location: index.php");
	}?>
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
    <div class="nav-wrapper container"><a href="#" class="brand-logo center">My LOL Picker</a><a id="logo-container" href="index.php" class="brand-logo"><i class="large mdi-action-home"></i></a>
      <ul class="right hide-on-med-and-down"> 
      <li><a href="myStats.php">My Stats</a></li>
      <li><a href="logout.php">Log Out</a></li>
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
      <div class="row center">
	  <?php
		$aName = $_SESSION ["gatekeeper"];
		
		$conn = new PDO("mysql:host=edward2.solent.ac.uk;dbname=mtomlin;","mtomlin","iechohva");
		
		$snameStatement = $conn->prepare("SELECT s_name,server FROM Users WHERE username=?");
		$snameStatement->bindParam (1, $aName);
		$snameStatement->execute();
		while($snameRet=$snameStatement->fetch())
		{
				$sname = $snameRet[s_name];
				$aServer = $snameRet[server];
		}
		
		$connection = curl_init();
		curl_setopt($connection, CURLOPT_URL, "https://$aServer.api.pvp.net/api/lol/$aServer/v1.4/summoner/by-name/$sname?api_key=0f720dfa-36f8-428a-b347-51db3d506746");
		curl_setopt($connection,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($connection,CURLOPT_HEADER, 0);
		$response = curl_exec($connection);
		curl_close($connection);

		$summID = json_decode($response,true);
		$sname2 = strtolower(str_replace(' ', '', $sname));
		$summ = $summID[$sname2];
		$sID = $summ["id"];
		
		
		$conn2 = curl_init();
		curl_setopt($conn2, CURLOPT_URL, "https://$aServer.api.pvp.net/api/lol/$aServer/v1.3/stats/by-summoner/$sID/ranked?season=SEASON2015&api_key=0f720dfa-36f8-428a-b347-51db3d506746");
		curl_setopt($conn2,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($conn2,CURLOPT_HEADER, 0);
		$response2 = curl_exec($conn2);
		curl_close($conn2);
		
		$summRanked = json_decode($response2);
		$RankChamps = $summRanked->champions;
		
		echo "<h5 class='header col s12 light'>Summoner Name: ".$sname."</h5><h5 class='header col s12 light'>Server: ".$aServer."</h5>";
		//echo "<br /> Lower case, no space: ".$sname2;
		//echo "<br /> SUMMONER ID: ".$sID; $sname
	?>
        
		
      </div>
	  <br />
      <div class="container blue lighten-5">
	  <br />
		<div class="row">
			<h5 class="header col s12 light">Ranked Champions</h5>
			<table class="bordered centered">
					<thead>
					  <tr>
						  <th data-field="id"></th>
						  <th data-field="name">Name</th>
						  <th data-field="price">Times Played</th>
						  <th data-field="price">Win Percent</th>
						  <th data-field="price">KDA Ratio</th>
						  <th data-field="price">Average Gold</th>
					  </tr>
					</thead>

					<tbody>
					<?php
						for ($i=0; $i< count($RankChamps); $i++)
						{
							if ($RankChamps[$i]->id != 0)
							{
								$stats = $RankChamps[$i]->stats;
								$totPlayed = $stats->totalSessionsPlayed;
								$kda = round((($stats->totalChampionKills + $stats->totalAssists)/($stats->totalDeathsPerSession)),2);
								$avrGold = round(($stats->totalGoldEarned / $stats->totalSessionsPlayed),2);
							
								$idStatement = $conn->prepare("SELECT * FROM Champions WHERE ID=?");
								$idStatement->bindParam (1, $RankChamps[$i]->id);
								$idStatement->execute();
								while($foundID=$idStatement->fetch())
								{
									echo  "<tr><td><img src='$foundID[image]' alt=''></img></td><td>$foundID[name]</td>";
									//echo "<img src='$foundID[image]' alt=''></img></th><td> $foundID[name]</td><td rowspan='3'>KDA ratio: ". $kda ."</td>";
								}
								
								//  (wins / totalgames) * 100 = win percent
								$winDecimal = ($stats->totalSessionsWon / ($stats->totalSessionsWon + $stats->totalSessionsLost));
								$winPercent = round(($winDecimal*100), 2);
								
								echo "<td>$totPlayed</td><td>". $winPercent ."%</td><td>$kda</td><td>$avrGold</td></tr>";
								//echo "<td rowspan='3'>Average Gold: ". $avrGold ."</td></tr><tr><td>Win Percent: ". $winPercent ."%</td></tr><tr><td>Times Played: ". $totPlayed . "</td></tr>";
							}
						}
					?>
					</tbody>
				  </table>
		</div>
	  <br />
      </div>
      <br><br><br>
    </div>
  </div>

  <div class="container">
    <div class="section">
    </div>
    <br><br>

    <div class="section">

    </div>
  </div> 
  
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
