<?PHP


	//////////////////////////////////////////////////
	//		 	 ____    __           ______       			//
	//			/\  _`\ /\ \__       /\  _  \      			//
	//			\ \ \L\_\ \ ,_\   ___\ \ \L\ \     			//
	//			 \ \  _\L\ \ \/  / __`\ \  __ \    			//
	//			  \ \ \L\ \ \ \_/\ \L\ \ \ \/\ \   			//
	//	  		 \ \____/\ \__\ \____/\ \_\ \_\  			//
	//			    \/___/  \/__/\/___/  \/_/\/_/  	 		//
	//																					 		//
	//////////////////////////////////////////////////
	// The Andromeda-Project-Browsergame				 		//
	// Ein Massive-Multiplayer-Online-Spiel			 		//
	// Programmiert von Nicolas Perrenoud				 		//
	// www.nicu.ch | mail@nicu.ch								 		//
	// als Maturaarbeit '04 am Gymnasium Oberaargau	//
	//////////////////////////////////////////////////
	//
	// 	File: fleetinfo.php
	// 	Created: 01.12.2004
	// 	Last edited: 19.06.2008
	// 	Last edited by: MrCage <mrcage@etoa.ch>
	//	
	/**
	* Shows information about a given fleet
	*
	* @author MrCage <mrcage@etoa.ch>
	* @copyright Copyright (c) 2004-2008 by EtoA Gaming, www.etoa.net
	*/	


	// DEFINITIONEN //

	echo "<h1>Flotten</h1>";
	echo "<h2>Details</h2>";

	// BEGIN SKRIPT //

	if (intval($_GET['id'])>0)
	$fleet_id=intval($_GET['id']);

	//
	// Flottendaten laden und �berpr�fen ob die Flotte existiert
	//
	$fd = new Fleet($fleet_id);
	if ($fd->valid())
	{
		if ($fd->getAction()->code()=="support" && $fd->ownerAllianceId()==$cu->allianceId() && $cu->allianceId()>0 && ($fd->status()==0 || $fd->status->status()==3))
		{
			include("fleetinfo/support.php");
		}
		elseif ($fd->getAction()->code()=="alliance" && $fd->nextId==$cu->allianceId() && ($fd->status()==0 || $fd->status->status()==3))
		{
			include("fleetinfo/alliance.php");
		}
		elseif ($fd->ownerId()==$cu->id)
		{
			// Flugabbruch ausl�sen
			if (isset($_POST['cancel'])!="" && checker_verify())
			{
				if ($fd->cancelFlight())
				{
					ok_msg("Flug erfolgreich abgebrochen!");
					add_log(13,"Der Spieler [b]".$s['user']['nick']."[/b] bricht den Flug seiner Flotte [b]".$fleet_id."[/b] ab",time());
				}
				else
				{
					err_msg("Flug konnte nicht abgebrochen werden. ".$fd->getError());
				}
			}
	
	
			echo "<table>
			<tr><td colspan=\"3\">";			

			tableStart("Flugdaten");
			
			$progrssWidth = 590;
				
			$perc = (time()-$fd->launchTime()) / ($fd->landTime()-$fd->launchTime());
			$perc = min(1,$perc);
			$pxl = 25 + round($perc * $progrssWidth);
				
			echo "<tr>
				<td style=\"background:#000 url('images/fleetbg.png');\">
					<div style=\"position:relative;height:80px;\">
						<div style=\"position:absolute;left:0px;top:5px;\">
						".$fd->getSource()->smallImage()."<br/>
						<a href=\"?page=cell&amp;id=".$fd->getSource()->cellId()."&amp;hl=".$fd->getSource()->id()."\">".$fd->getSource()."</a><br/>
						<b>Start:</b> ".date("d.m.Y H:i:s",$fd->launchTime())."
						</div>
						<div style=\"position:absolute;right:0px;top:5px;text-align:right;\">";
						if ($cu->discovered($fd->getTarget()->getCell()->absX(),$fd->getTarget()->getCell()->absY()))
						{
							echo $fd->getTarget()->smallImage()."<br/>
							<a href=\"?page=cell&amp;id=".$fd->getTarget()->cellId()."&amp;hl=".$fd->getTarget()->id()."\">".$fd->getTarget()."</a><br/>";
						}
						else
						{
							$ent = Entity::createFactory('u',$fd->getTarget()->id());
							echo $ent->smallImage()."<br/>
							<a href=\"?page=cell&amp;id=".$ent->cellId()."&amp;hl=".$ent->id()."\">".$ent."</a><br/>";
						}
						echo "<b>Landung:</b> ".date("d.m.Y H:i:s",$fd->landTime())."
						</div>						
						<div style=\"position:absolute;left:".$pxl."px;top:17px;\" id=\"fleetProgress\">
							<img src=\"images/fleetmove.png\" alt=\"Fleet\" />
						</div>
						<div style=\"position:absolute;left:270px;top:46px;text-align:center;\">
							<div style=\"color:".FleetAction::$attitudeColor[$fd->getAction()->attitude()]."\">
				".$fd->getAction()->name()." [".FleetAction::$statusCode[$fd->status()]."]</div>
							<div id=\"flighttime\"></div>
						</div>
					</div>
				</td>
			</tr>";
			tableEnd();
			
			echo "</td></tr><tr><td style=\"50%\">";
			
			// Flugdaten

	
			tableStart("Piloten &amp; Verbrauch","50%");
			echo "<tr>
				<th style=\"width:150px;\">".RES_ICON_PEOPLE."Piloten:</th>
				<td class=\"tbldata\">".nf($fd->pilots())."</td></tr>";
			echo "<tr>
				<th>".RES_ICON_FUEL."".RES_FUEL.":</th>
				<td class=\"tbldata\">".nf($fd->usageFuel())."</td></tr>";
			echo "<tr>
				<th>".RES_ICON_FOOD."".RES_FOOD.":</th>
				<td class=\"tbldata\">".nf($fd->usageFood())."</td></tr>";
			echo "<tr>
				<th>".RES_ICON_POWER." ".RES_POWER.":</th>
				<td class=\"tbldata\">".nf($fd->usagePower())."</td></tr>";
			tableEnd();
	
				tableStart("Passagierraum","50%");
			echo "<tr><th>".RES_ICON_PEOPLE."Passagiere</th><td class=\"tbldata\">".nf($fd->resPeople())."</td></tr>";
			echo "<tr><th style=\"width:150px;\">Freier Platz:</th><td class=\"tbldata\">".nf($fd->getFreePeopleCapacity())."</td></tr>";
			echo "<tr><th style=\"width:150px;\">Totaler Platz:</th><td class=\"tbldata\">".nf($fd->getPeopleCapacity())."</td></tr>";
			tableEnd();
	
			echo "</td><td style=\"width:5%;vertical-align:top;\"></td><td style=\"width:45%;vertical-align:top;\">";
	
			// Frachtraum
			tableStart("Frachtraum","50%");
			echo "<tr><th>".RES_ICON_METAL."".RES_METAL."</th><td class=\"tbldata\">".nf($fd->resMetal())." t</td></tr>";
			echo "<tr><th>".RES_ICON_CRYSTAL."".RES_CRYSTAL."</th><td class=\"tbldata\" >".nf($fd->resCrystal())." t</td></tr>";
			echo "<tr><th>".RES_ICON_PLASTIC."".RES_PLASTIC."</th><td class=\"tbldata\">".nf($fd->resPlastic())." t</td></tr>";
			echo "<tr><th>".RES_ICON_FUEL."".RES_FUEL."</th><td class=\"tbldata\">".nf($fd->resFuel())." t</td></tr>";
			echo "<tr><th>".RES_ICON_FOOD."".RES_FOOD."</th><td class=\"tbldata\">".nf($fd->resFood())." t</td></tr>";
			echo "<tr><th>".RES_ICON_POWER."".RES_POWER."</th><td class=\"tbldata\">".nf($fd->resPower())." t</td></tr>";
			echo "<tr><th style=\"width:150px;\">Freier Frachtraum:</th><td class=\"tbldata\">".nf($fd->getFreeCapacity())." t</td></tr>";
			echo "<tr><th style=\"width:150px;\">Totaler Frachtraum:</th><td class=\"tbldata\">".nf($fd->getCapacity())." t</td></tr>";
			tableEnd();
			

	
			echo "</td></tr><tr><td colspan=\"3\">";
		
	
			// Schiffe laden
			if ($fd->countShips() > 0)
			{
				// Schiffe anzeigen
				tableStart("Schiffe");
				echo "<tr>
					<th colspan=\"2\">Schifftyp</th>
					<th width=\"50\">Anzahl</th></tr>";
				foreach ($fd->getShipIds() as $sid=> $scnt)
				{
					$ship = new Ship($sid);
					echo "<tr>
						<td class=\"tbldata\" style=\"width:40px;background:#000\">
							".$ship->imgSmall()."</td>";
					echo "<td class=\"tbldata\">
						<b>".$ship->name()."</b><br/>
					".text2html($ship->shortComment())."</td>";
					echo "<td class=\"tbldata\" style=\"width:50px;\">".nf($scnt)."</td></tr>";
				}
				tableEnd();
			}
	
			echo "</td></tr></table>";
	
			echo "<form action=\"?page=$page&amp;id=$fleet_id\" method=\"post\">";
			echo "<input type=\"button\" onClick=\"document.location='?page=fleets'\" value=\"Zur&uuml;ck zur Flotten&uuml;bersicht\"> &nbsp;";
	
			// Abbrechen-Button anzeigen
			if ($fd->status() == 0 && $fd->landTime() > time())
			{
				checker_init();
				echo "<input type=\"submit\" name=\"cancel\" value=\"Flug abbrechen und zum Heimatplanet zur&uuml;ckkehren\"  onclick=\"return confirm('Willst du diesen Flug wirklich abbrechen?');\">";
			}
			echo "</form>";
	
			countDown('flighttime',$fd->landTime());
			
			echo "<script type=\"text/javascript;\">
			function moveFleet(t)
			{
				perc = (t-".$fd->launchTime().") / (".($fd->landTime()-$fd->launchTime()).");
				perc = Math.min(1,perc);
				pxl = 25 + Math.round(perc * ".$progrssWidth.");
				document.getElementById('fleetProgress').style.left = pxl+'px';
				
				setTimeout(\"moveFleet(\"+(t+1)+\")\",1000);
			}
			moveFleet(".time().");
			</script>";
			
		}
		else {
			echo "Diese Flotte existiert nicht mehr! Wahrscheinlich sind die Schiffe schon <br/>auf dem Zielplaneten gelandet oder der Flug wurde abgebrochen.<br/><br/>";
			echo "<input type=\"button\" onclick=\"document.location='?page=fleets'\" value=\"Zur&uuml;ck zur Flotten&uuml;bersicht\">";
		}
	}
	else
	{
		echo "Diese Flotte existiert nicht mehr! Wahrscheinlich sind die Schiffe schon <br/>auf dem Zielplaneten gelandet oder der Flug wurde abgebrochen.<br/><br/>";
		echo "<input type=\"button\" onclick=\"document.location='?page=fleets'\" value=\"Zur&uuml;ck zur Flotten&uuml;bersicht\">";
	}
?>
