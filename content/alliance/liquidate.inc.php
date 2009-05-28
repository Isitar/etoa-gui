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
	// als Maturaarbeit '04 am Gymnasium Oberaargau	//
	// www.etoa.ch | mail@etoa.ch								 		//
	//////////////////////////////////////////////////
	//
	// $Author$
	// $Date$
	// $Rev$
	//

if (Alliance::checkActionRights('liquidate'))
{
		echo "<h2>Allianz aufl&ouml;sen</h2>";
		
		// Prüft, ob noch Mitglieder vorhanden sind (keine Bewerbungen!)
		if (count($ally->members) > 1)
		{
			error_msg("Allianz kann nicht aufgel&ouml;st werden, da sie noch Mitglieder hat. L&ouml;sche zuerst die Mitglieder!");
			echo "<input type=\"button\" onclick=\"document.location='?page=$page';\" value=\"Zur&uuml;ck\" />";
		}
		else
		{
			echo "<form action=\"?page=$page\" method=\"post\">";
			echo "<input name=\"id_control\" type=\"hidden\" value=\"".$cu->allianceId."\" />";
			checker_init();
			echo "Willst du die Allianz wirklich aufl&ouml;sen?<br/><br/>
			<input type=\"button\" onclick=\"document.location='?page=$page';\" value=\"Nein\" />&nbsp;&nbsp;&nbsp;
			<input type=\"submit\" name=\"liquidatesubmit\" value=\"Ja\" />";
			echo "</form>";
		}
}
?>