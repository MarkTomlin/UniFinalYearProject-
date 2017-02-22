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
  <script type='text/javascript' src='http://edward2.solent.ac.uk//ewt/jquery.min.js'></script>
  <script type='text/javascript' src='http://edward2.solent.ac.uk//ewt/jquery-ui.min.js'></script>
  <script type='text/javascript'>
	function init()
	{
		$(document).ready(function() {
		$('select').material_select();
		});
	}
	function upvoteWeak(id)
	{
		var xhr2 = new XMLHttpRequest();
		
		xhr2.addEventListener ("load", receiveData);
		
		xhr2.open("GET", "upvoteWeak.php?id=" + id );
		xhr2.send();
	}
	function downvoteWeak(id)
	{
		var xhr3 = new XMLHttpRequest();
		
		xhr3.addEventListener ("load", receiveData2);
		
		xhr3.open("GET", "downvoteWeak.php?id=" + id );
		xhr3.send();
	}
	function upvoteStrong(id)
	{
		var xhr4 = new XMLHttpRequest();
		
		xhr4.addEventListener ("load", receiveData);
		
		xhr4.open("GET", "upvoteStrong.php?id=" + id );
		xhr4.send();
	}
	function downvoteStrong(id)
	{
		var xhr5 = new XMLHttpRequest();
		
		xhr5.addEventListener ("load", receiveData2);
		
		xhr5.open("GET", "downvoteStrong.php?id=" + id );
		xhr5.send();
	}
	// The callback function
	function receiveData(e)
	{
		alert("Upvote applied");
		location.reload();
	}
	function receiveData2(e)
	{
		alert("Downvote applied");
		location.reload();
	}
	
	$(init);
  </script>
</head>
<body>
  <nav class="light-blue lighten-1" role="navigation">
    <div class="nav-wrapper container"><a href="#" class="brand-logo center">My LOL Picker</a><a id="logo-container" href="index.php" class="brand-logo"><i class="large mdi-action-home"></i></a>
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
	<?php
		$sname = $_GET["sname"];
		$champ = $_GET["champ"];
		$server = $_GET["server"];
		
		$conn = new PDO("mysql:host=edward2.solent.ac.uk;dbname=mtomlin;","mtomlin","iechohva");
		
		//Gets summoner IDs
		//https://euw.api.pvp.net/api/lol/euw/v1.4/summoner/by-name/xStarSkreaM?api_key=0f720dfa-36f8-428a-b347-51db3d506746
		//Ranked stats
		//https://euw.api.pvp.net/api/lol/euw/v1.3/stats/by-summoner/xStarSkreaM/ranked?season=SEASON2015&api_key=0f720dfa-36f8-428a-b347-51db3d506746
		
		$connection = curl_init();
		curl_setopt($connection, CURLOPT_URL, "https://$server.api.pvp.net/api/lol/$server/v1.4/summoner/by-name/$sname?api_key=0f720dfa-36f8-428a-b347-51db3d506746");
		curl_setopt($connection,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($connection,CURLOPT_HEADER, 0);
		$response = curl_exec($connection);
		curl_close($connection);

		$summID = json_decode($response,true); //Account JSON returned
		$sname2 = strtolower(str_replace(' ', '', $sname)); //Reformat
		$summ = $summID[$sname2]; 
		$sID = $summ["id"]; //Account ID
		
		$conn2 = curl_init();
		curl_setopt($conn2, CURLOPT_URL, "https://$server.api.pvp.net/api/lol/$server/v1.3/stats/by-summoner/$sID/ranked?season=SEASON2015&api_key=0f720dfa-36f8-428a-b347-51db3d506746");
		curl_setopt($conn2,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($conn2,CURLOPT_HEADER, 0);
		$response2 = curl_exec($conn2);
		curl_close($conn2);
		
		$summRanked = json_decode($response2);
		$RankChamps = $summRanked->champions;
		$rankChampArr = array();
		$winRateArr = array();
		
		$champStatement = $conn->prepare("SELECT ID,image FROM Champions WHERE name=?");
		$champStatement->bindParam (1, $champ);
		$champStatement->execute();
							
		while($cRow=$champStatement->fetch())
		{
			$idChampMatched = $cRow[ID];
			if ( $idChampMatched == 103)
			{
				$imageChampMatched = "http://4.bp.blogspot.com/-qFXUsGgkvmE/VMvaeGBg2vI/AAAAAAAACSc/TDZ1bBDD5XM/s320/Ahri.png";
			}
			elseif ( $idChampMatched == 84)
			{
				$imageChampMatched = "http://www.mobafire.com/images/champion/icon/akali.png";
			}
			elseif ( $idChampMatched == 61)
			{
				$imageChampMatched = "http://lol.hehagame.com/attach/icons/20140916033734.png";
			}
			else
			{
				$imageChampMatched = $cRow[image];
			}
		}
		
		$conn3 = curl_init();
		curl_setopt($conn3, CURLOPT_URL, "https://global.api.pvp.net/api/lol/static-data/$server/v1.2/champion/$idChampMatched?champData=enemytips,tags&api_key=0f720dfa-36f8-428a-b347-51db3d506746");
		curl_setopt($conn3,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($conn3,CURLOPT_HEADER, 0);
		$response3 = curl_exec($conn3);
		curl_close($conn3);
		
		$champMatched = json_decode($response3);
		
		for ($i=0; $i< count($RankChamps); $i++)
		{
			if ($RankChamps[$i]->id != 0)
			{
				$idStatement = $conn->prepare("SELECT * FROM Champions WHERE ID=?");
				$idStatement->bindParam (1, $RankChamps[$i]->id);
				$idStatement->execute();
				
				while($foundID=$idStatement->fetch())
				{
					//echo "<img src='$foundID[image]' alt=''></img></th> <td>Name: $foundID[name]</td></tr>";
					$rankChampArr[] = $foundID[name];
				}
				$stats = $RankChamps[$i]->stats;
				//  (wins / totalgames) * 100 = win percent
				$winDecimal = ($stats->totalSessionsWon / ($stats->totalSessionsWon + $stats->totalSessionsLost));
				$winPercent = round(($winDecimal*100), 2);
				//echo "<tr><td>Win Percent: ". $winPercent ."%</td></tr>";
				$winRateArr[] = $winPercent;
			}
		}
	?>
  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <br><br>
      <div class="row center">
        <h5 class="header col s12 light">Your Matchup</h5>
      </div>
	  <br />
      <div class="container blue lighten-5">
	  <br />
		<div class="row">
			<?php
				$ChampTags = $champMatched->tags;
				$ChampName = $champMatched->name;
				$ChampTip = $champMatched->enemytips;
				echo "<div class='col s3'><img style='width: 120px;height: auto;' src='$imageChampMatched' alt=''></img></div>
					<div class='col s9'><h4 class='header'>$ChampName</h4>Tip: ".$ChampTip[0] ."<br /><br />Role: ".$ChampTags[0]." ".$ChampTags[1]."</div>";
			?>
		</div>
	  <br />
      </div>
      <br><br><br><div class="row center">
        <h5 class="header col s12 light">Results</h5>
      </div>
	<div class="container blue lighten-5">
		<div class="row">
			<div class="col s5"><h5 class="header col s12 light">Weak Against</h5>
				<table class="bordered centered">	
					<thead>
					  <tr>
						  <th data-field="id"></th>
						  <th data-field="name">Name</th>
						  <th data-field="price">Rating</th>
						  <th data-field="price">Played</th>
						  <th data-field="price">vote</th>
					  </tr>
					</thead>

					<tbody>
					<?php
						$weakStatement = $conn->prepare("SELECT * FROM Weak_vs WHERE name=? ORDER BY rating DESC");
						$weakStatement->bindParam (1, $champ);
						$weakStatement->execute();
						
						while($row=$weakStatement->fetch())
						{
							$weakStatement2 = $conn->prepare("SELECT image FROM Champions WHERE name=?");
							$weakStatement2->bindParam (1, $row[vs_name]);
							$weakStatement2->execute();
							
							while($iRow=$weakStatement2->fetch())
							{
								if(in_array( $row[vs_name] , $rankChampArr ) == true )
								{
									$key = array_search( $row[vs_name], $rankChampArr); // $key = index Number;
									$winRate = ($winRateArr[$key]/50);
									$rankRating = $row[rating] * $winRate;
									echo "<tr><td><img style='width: 55px;height: 55px;' src='$iRow[image]' alt=''></img></td><td> $row[vs_name] </td><td style='color: #ff9800;font-weight: bold;'> $rankRating </td><td>Yes</td>
									<td><a id='$row[ID]' class='waves-effect waves-light btn' onclick='upvoteWeak($row[ID])'>&#65514;</a><a class='waves-effect waves-light btn' onclick='downvoteWeak($row[ID])'>&#65516;</a></td></tr>";
								}
								else
								{
									echo "<tr><td><img src='$iRow[image]' alt=''></img></td><td> $row[vs_name] </td><td> $row[rating] </td><td>No</td>
									<td><a id='$row[ID]' class='waves-effect waves-light btn' onclick='upvoteWeak($row[ID])'>&#65514;</a><a class='waves-effect waves-light btn' onclick='downvoteWeak($row[ID])'>&#65516;</a></td></tr>";
								}
								//echo "<tr><th rowspan='2'><img src='$iRow[image]' alt=''></img></th> <td>Name: $row[vs_name]</td></tr>
									//<tr><td>Rating: ". $row[rating] ."   <button onclick='window.location.href='''>UpVote</button>  <button onclick='window.location.href='''>DownVote</button></td></tr>";
							}
						}
					?>
					</tbody>
				  </table>
			</div>
			<div class="col s5 offset-s1"><h5 class="header col s12 light">Strong Against</h5>
			<table class="bordered centered">
					<thead>
					  <tr>
						  <th data-field="id"></th>
						  <th data-field="name">Name</th>
						  <th data-field="price">Rating</th>
						  <th data-field="price">Played</th>
						  <th data-field="price">vote</th>
					  </tr>
					</thead>

					<tbody>
					<?php
						$strongStatement = $conn->prepare("SELECT * FROM Strong_vs WHERE name=? ORDER BY rating DESC");
						$strongStatement->bindParam (1, $champ);
						$strongStatement->execute();
						
						while($row2=$strongStatement->fetch())
						{
							$strongStatement2 = $conn->prepare("SELECT image FROM Champions WHERE name=?");
							$strongStatement2->bindParam (1, $row2[vs_name]);
							$strongStatement2->execute();
							
							while($iRow2=$strongStatement2->fetch())
							{
								if(in_array( $row2[vs_name] , $rankChampArr ) == true )
								{
									echo "<tr><td><img style='width: 55px;height: 55px;' src='$iRow2[image]' alt=''></img></td><td> $row2[vs_name] </td><td> $row2[rating] </td><td>Yes</td>
									<td><a class='waves-effect waves-light btn' onclick='upvoteStrong($row2[ID])'>&#65514;</a><a class='waves-effect waves-light btn' onclick='downvoteStrong($row2[ID])'>&#65516;</a></td></tr>";
								}
								else
								{
									echo "<tr><td><img src='$iRow2[image]' alt=''></img></td><td> $row2[vs_name] </td><td> $row2[rating] </td><td>No</td>
									<td><a class='waves-effect waves-light btn' onclick='upvoteStrong($row2[ID])'>&#65514;</a><a class='waves-effect waves-light btn' onclick='downvoteStrong($row2[ID])'>&#65516;</a></td></tr>";
								}
							}
						}
					?>
					</tbody>
				  </table>			
			</div>
		</div>
	</div>
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
