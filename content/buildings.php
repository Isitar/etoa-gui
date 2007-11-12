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
	// 	File: buildings.php
	// 	Created: 01.12.2004
	// 	Last edited: 07.07.2007
	// 	Last edited by: MrCage <mrcage@etoa.ch>
	//	
	/**
	* Bauhof-Modul
	*
	* @package etoa_gameserver
	* @author MrCage <mrcage@etoa.ch>
	* @copyright Copyright (c) 2004-2007 by EtoA Gaming, www.etoa.net
	*/	

  // DEFINITIONEN //

  define('NUM_BUILDINGS_PER_ROW',4);// Gebäude pro Reihe
  define('BUILD_BUILDING_ID',6);		// Bauhof
  define('CELL_WIDTH',175);					// Breite der Gebäudezelle in der Übersicht
	define('GEN_TECH_ID',23);				// ID der Gentechnologie

	if ($s['user']['image_filter']==1)
		$use_img_filter = true;
	else
		$use_img_filter = false;

/* This function has to be outsourced, of course, later */



function calcBuildingWaitTime($bc,$c)
{
	// Wartezeiten auf Ressourcen berechnen
	if ($c->prod->metal>0) $bwait['metal']=ceil(($bc['metal']-$c->res->metal)/$c->prod->metal*3600);else $bwait['metal']=0;
	if ($c->prod->crystal>0) $bwait['crystal']=ceil(($bc['crystal']-$c->res->crystal)/$c->prod->crystal*3600);else $bwait['crystal']=0;
	if ($c->prod->plastic>0) $bwait['plastic']=ceil(($bc['plastic']-$c->res->plastic)/$c->prod->plastic*3600);else $bwait['plastic']=0;
	if ($c->prod->fuel>0) $bwait['fuel']=ceil(($bc['fuel']-$c->res->fuel)/$c->prod->fuel*3600);else $bwait['fuel']=0;
	if ($c->prod->food>0) $bwait['food']=ceil(($bc['food']-$c->res->food)/$c->prod->food*3600);else $bwait['food']=0;
	$bwmax=max($bwait['metal'],$bwait['crystal'],$bwait['plastic'],$bwait['fuel'],$bwait['food']);

	// Baukosten-String
	$bcstring.="<td";
	if ($bc['metal']>$c->res->metal)
		$bcstring.= " class=\"tbldata2\" ".tm("Fehlender Rohstoff","<b>".nf($bc['metal']-$c->res->metal)."</b> ".RES_METAL."<br/>Bereit in <b>".tf($bwait['metal'])."</b>");
	else
		$bcstring.=" class=\"tbldata\"";
	$bcstring.= ">".nf($bc['metal'])."</td><td";
	if ($bc['crystal']>$c->res->crystal)
		$bcstring.= " class=\"tbldata2\" ".tm("Fehlender Rohstoff",nf($bc['crystal']-$c->res->crystal)." ".RES_CRYSTAL."<br/>Bereit in <b>".tf($bwait['crystal'])."</b>");
	else
		$bcstring.=" class=\"tbldata\"";
	$bcstring.= ">".nf($bc['crystal'])."</td><td";
	if ($bc['plastic']>$c->res->plastic)
		$bcstring.= " class=\"tbldata2\" ".tm("Fehlender Rohstoff",nf($bc['plastic']-$c->res->plastic)." ".RES_PLASTIC."<br/>Bereit in <b>".tf($bwait['plastic'])."</b>");
	else
		$bcstring.=" class=\"tbldata\"";
	$bcstring.= ">".nf($bc['plastic'])."</td><td";
	if ($bc['fuel']>$c->res->fuel)
		$bcstring.= " class=\"tbldata2\" ".tm("Fehlender Rohstoff",nf($bc['fuel']-$c->res->fuel)." ".RES_FUEL."<br/>Bereit in <b>".tf($bwait['fuel'])."</b>");
	else
		$bcstring.=" class=\"tbldata\"";
	$bcstring.= ">".nf($bc['fuel'])."</td><td";
	if ($bc['food']>$c->res->food)
		$bcstring.= " class=\"tbldata2\" ".tm("Fehlender Rohstoff",nf($bc['food']-$c->res->food)." ".RES_FOOD."<br/>Bereit in <b>".tf($bwait['food'])."</b>");
	else
		$bcstring.=" class=\"tbldata\"";
	$bcstring.= ">".nf($bc['food'])."</td><td";
	
	if ($bc['power']> $c->prod->power- $c->use->power && $bc['power']>0)
		$bcstring.= " class=\"tbldata2\" ".tm("Fehlender Rohstoff",nf($bc['power']-($c->prod->power-$c->use->power))." Energie");
	else
		$bcstring.=" class=\"tbldata\"";
	$bcstring.= ">".nf($bc['power'])."</td></tr>";
	return array($bcstring,$bwmax);
}

function calcDemolishingCosts($buildingArray, $buildingCosts)
{
	$dc=array();
	// Abrisskostenberechnung				Abrisskosten = Baukosten  * Abrisskostenfaktor
	$dc['metal'] = $buildingCosts['metal'] * $buildingArray['building_demolish_costs_factor'];
	$dc['crystal'] = $buildingCosts['crystal'] * $buildingArray['building_demolish_costs_factor'];
	$dc['plastic'] = $buildingCosts['plastic'] * $buildingArray['building_demolish_costs_factor'];
	$dc['fuel'] = $buildingCosts['fuel'] * $buildingArray['building_demolish_costs_factor'];
	$dc['food'] = $buildingCosts['food'] * $buildingArray['building_demolish_costs_factor'];
	$dc['power'] = $buildingCosts['power'] * $buildingArray['building_demolish_costs_factor'];
	return $dc;
}

function calcDemolishingWaitTime($dc,$c)
{
	if ($c->prod->metal>0)
		$dwait['metal']=ceil(($dc['metal']-$c->res->metal)/$c->prod->metal*3600);
	else
		$dwait['metal']=0;
	if ($c->prod->crystal>0)
		$dwait['crystal']=ceil(($dc['crystal']-$c->res->crystal)/$c->prod->crystal*3600);
	else
		$dwait['crystal']=0;
	if ($c->prod->plastic>0)
		$dwait['plastic']=ceil(($dc['plastic']-$c->res->plastic)/$c->prod->plastic*3600);
	else
		$dwait['plastic']=0;
	if ($c->prod->fuel>0)
		$dwait['fuel']=ceil(($dc['fuel']-$c->res->fuel)/$c->prod->fuel*3600);
	else
		$dwait['plastic']=0;
	if ($c->prod->food>0)
		$dwait['food']=ceil(($dc['food']-$c->res->food)/$c->prod->food*3600);
	else
		$dwait['food']=0;
	$dwmax=max($dwait['metal'],$dwait['crystal'],$dwait['plastic'],$dwait['fuel'],$dwait['food']);

	$dwstring= "<td";
	if ($dc['metal']>$c->res->metal)
		$dwstring.=" class=\"tbldata2\" ".tm("Fehlender Rohstoff","<b>".nf($dc['metal']-$c->res->metal)."</b> ".RES_METAL."<br/>Bereit in <b>".tf($dwait['metal'])."</b>");
	else
		$dwstring.=" class=\"tbldata\"";
	$dwstring.= ">".nf($dc['metal'])."</td><td";
	if ($dc['crystal']>$c->res->crystal)
		$dwstring.= " class=\"tbldata2\" ".tm("Fehlender Rohstoff",nf($dc['crystal']-$c->res->crystal)." ".RES_CRYSTAL."<br/>Bereit in <b>".tf($dwait['crystal'])."</b>");
	else
		$dwstring.=" class=\"tbldata\"";
	$dwstring.= ">".nf($dc['crystal'])."</td><td";
	if ($dc['plastic']>$c->res->plastic)
		$dwstring.= " class=\"tbldata2\" ".tm("Fehlender Rohstoff",nf($dc['plastic']-$c->res->plastic)." ".RES_PLASTIC."<br/>Bereit in <b>".tf($dwait['plastic'])."</b>");
	else
		$dwstring.=" class=\"tbldata\"";
	$dwstring.= ">".nf($dc['plastic'])."</td><td";
	if ($dc['fuel']>$c->res->fuel)
		$dwstring.= " class=\"tbldata2\" ".tm("Fehlender Rohstoff",nf($dc['fuel']-$c->res->fuel)." ".RES_FUEL."<br/>Bereit in <b>".tf($dwait['fuel'])."</b>");
	else
		$dwstring.=" class=\"tbldata\"";
	$dwstring.= ">".nf($dc['fuel'])."</td><td";
	if ($dc['food']>$c->res->food)
		$dwstring.= " class=\"tbldata2\" ".tm("Fehlender Rohstoff",nf($dc['food']-$c->res->food)." ".RES_FOOD."<br/>Bereit in <b>".tf($dwait['food'])."</b>");
	else
		$dwstring.=" class=\"tbldata\"";
	$dwstring.= ">".nf($dc['food'])."</td><td";
	if ($dc['power']>$c->prod->power-$c->use->power && $dc['power']>0)
		$dwstring.= " class=\"tbldata2\" ".tm("Fehlender Rohstoff",nf($dc['power']-($c->prod->power-$c->use->power))." Energie");
	else
		$dwstring.=" class=\"tbldata\"";
	$dwstring.= ">".nf($dc['power'])."</td></tr>";
	return array($dwstring,$dwmax);
}

	// SKRIPT //

	if ($planets->current)
	{
		echo "<h1>Bauhof des Planeten ".$c->name."</h1>";
		$c->resBox();

		// Gebäudeliste laden
		$sql ="
		SELECT 
			*
		FROM 
		"	.$db_table['buildlist']." 
		WHERE 
			buildlist_user_id='".$s['user']['id']."' 
			AND buildlist_planet_id='".$c->id."';";
		
		$blres = dbquery($sql);
		$builing_something=false;
		while ($blarr = mysql_fetch_array($blres))
		{
			$buildlist[$blarr['buildlist_building_id']]=$blarr;
			if ($blarr['buildlist_build_type']!=0)
			{ 
				$builing_something=true;
			}
		}

		// Technologieliste laden
		$tres = dbquery("
		SELECT 
			* 
		FROM 
			".$db_table['techlist']." 
		WHERE 
			techlist_user_id='".$s['user']['id']."'
		;");
		while ($tarr = mysql_fetch_array($tres))
		{
			$techlist[$tarr['techlist_tech_id']]=$tarr['techlist_current_level'];
		}
		
		// Requirements
		$rres = dbquery("
		SELECT 
			* 
		FROM 
			".$db_table['building_requirements']."
		;");
		while ($rarr = mysql_fetch_array($rres))
		{
			if ($rarr['req_req_building_id']>0) 
			{
				$b_req[$rarr['req_building_id']]['b'][$rarr['req_req_building_id']]=$rarr['req_req_building_level'];
			}
			
			if ($rarr['req_req_tech_id']>0) 
			{
				$b_req[$rarr['req_building_id']]['t'][$rarr['req_req_tech_id']]=$rarr['req_req_tech_level'];
			}
		}

		//Gentech level laden
		$tlres = dbquery("
		SELECT
			techlist_current_level
		FROM
			".$db_table['techlist']."
		WHERE
            techlist_user_id='".$s['user']['id']."'
            AND techlist_tech_id='".GEN_TECH_ID."';");
		if(mysql_num_rows($tlres)>0)
		{
			$tlarr = mysql_fetch_array($tlres);
			define("GEN_TECH_LEVEL",$tlarr['techlist_current_level']);
    }
    else
    {
    	define("GEN_TECH_LEVEL",0);
    }

		// Felder von bauender Def laden
		$res_def =	dbquery("
		SELECT 
			SUM(d.def_fields * dl.deflist_build_count) AS planet_def_fields_needed 
		FROM 
			".$db_table['defense']." AS d
			INNER JOIN
			".$db_table['deflist']." AS dl
			ON
			d.def_id = dl.deflist_def_id 
			AND dl.deflist_planet_id='".$c->id."';");
		$arr=mysql_fetch_array($res_def);
		if ($arr['planet_def_fields_needed']>0)
		{
			$def_field_needed = $arr['planet_def_fields_needed'];
    }

/********************
* Gebäudedetail     *
********************/

		//Gebäude ausbauen/abreissen/abbrechen
		if (count($_POST)>0 && checker_verify())
		{
			$bid = 0;
			foreach ($_POST as $k => $v)
			{
				if(stristr($k,'_x'))
				{
					$bid = eregi_replace('show_([0-9]+)_x', '\1', $k);
					break;
				}
			}
			if ($bid==0 && isset($_POST['show']))
			{
				$bid = $_POST['show'];
			}
			if ($bid==0 && isset($_POST['id']))
			{
				$bid = $_POST['id'];
			}			
			
			// Gebäudedaten laden
			$res = dbquery("
			SELECT 
				* 
			FROM 
				".$db_table['buildings']." 
			WHERE 
				building_show='1' 
				AND building_id='".$bid."';");
			if (mysql_num_rows($res)>0)
			{
				$arr = mysql_fetch_array($res);
				if ($buildlist[$arr['building_id']]['buildlist_current_level']!=null)
				{
					$b_level=$buildlist[$arr['building_id']]['buildlist_current_level'];
				}
				else
				{
					$b_level=0;
				}

				if ($buildlist[$arr['building_id']]['buildlist_build_type']!="")
				{
					$b_status=$buildlist[$arr['building_id']]['buildlist_build_type'];
				}
				else
				{
					$b_status=0;
				}

        $bc = calcBuildingCosts($arr,$b_level);
        $bcn = calcBuildingCosts($arr,$b_level+1);
				$dc = calcDemolishingCosts($arr, $bc);

				// Bauzeit
				$btime_global_factor = $conf['global_time']['v'];
				$btime_build_factor = $conf['build_build_time']['v'];
				$bonus = $c->race->buildtime + $c->type->buildtime + $c->sol->type->buildtime-2;

				$btime = ($bc['metal']+$bc['crystal']+$bc['plastic']+$bc['fuel']+$bc['food']) / 12 * $btime_global_factor * $btime_build_factor;
				$btime *= $bonus;

				$btimen = ($bcn['metal']+$bcn['crystal']+$bcn['plastic']+$bcn['fuel']+$bcn['food']) / 12 * $btime_global_factor * $btime_build_factor;
				$btimen  *= $bonus;

				$dtime = ($dc['metal']+$dc['crystal']+$dc['plastic']+$dc['fuel']+$dc['food']) / 12 * $btime_global_factor * $btime_build_factor;
				$dtime  *= $bonus;

				if ($buildlist[BUILD_BUILDING_ID]['buildlist_people_working']>0)
				{
					$btime_min=$btime*(0.1-(GEN_TECH_LEVEL/100));
					$btime=$btime-($buildlist[BUILD_BUILDING_ID]['buildlist_people_working']*3);
					if ($btime<$btime_min) $btime=$btime_min;
					$bc['food']+=$buildlist[BUILD_BUILDING_ID]['buildlist_people_working']*12;
				}

				$start_time = $buildlist[$arr['building_id']]['buildlist_build_start_time'];
				$end_time = $buildlist[$arr['building_id']]['buildlist_build_end_time'];

				//
				// Befehle ausführen
				//

				//Gebäude ausbauen
				if ($_POST['command_build']!="" && $b_status==0)
				{
					if (!$builing_something)
					{

						if ($c->fields_used+$arr['building_fields']+$def_field_needed <= $c->fields+$c->fields_extra || $arr['building_fields']==0)
						{
							if ($c->res->metal >= $bc['metal'] && $c->res->crystal >= $bc['crystal'] && $c->res->plastic >= $bc['plastic']  && $c->res->fuel >= $bc['fuel']  && $c->res->food >= $bc['food'])
							{
								$end_time = time()+$btime;
								
								//Gebäude bereits vorhanden
								if (sizeof($buildlist[$arr['building_id']])>0)
								{
									dbquery("
									UPDATE 
										".$db_table['buildlist']." 
									SET
										buildlist_build_type='1',
										buildlist_build_start_time='".time()."',
										buildlist_build_end_time='".$end_time."'
									WHERE 
										buildlist_building_id='".$arr['building_id']."'
										AND buildlist_user_id='".$s['user']['id']."'
										AND buildlist_planet_id='".$c->id."';");
								}
								//Gebäude noch nicht vorhanden
								else
								{
									dbquery("
									INSERT INTO 
									".$db_table['buildlist']." 
									(
										buildlist_build_type,
										buildlist_build_start_time,
										buildlist_build_end_time,
										buildlist_building_id,
										buildlist_user_id,
										buildlist_planet_id
									) 
									VALUES 
									( 
										'1',
										'".time()."',
										'".$end_time."',
										'".$arr['building_id']."',
										'".$s['user']['id']."',
										'".$c->id."'
									);");

								}
								
								//Rohstoffe vom Planeten abziehen und aktualisieren
								$c->changeRes(-$bc['metal'],-$bc['crystal'],-$bc['plastic'],-$bc['fuel'],-$bc['food']);
								$b_status=1;
								
								
								//Log schreiben
								$log_text = "
								<b>Gebäude Ausbau</b><br><br>
								<b>User:</b> [USER_ID=".$s['user']['id'].";USER_NICK=".$s['user']['nick']."]<br>
								<b>Planeten:</b> [PLANET_ID=".$c->id.";PLANET_NAME=".$c->name."]<br>
								<b>Gebäude:</b> ".$arr['building_name']."<br>
								<b>Gebäude Level:</b> ".$b_level." (vor Ausbau)<br>
								<b>Bau dauer:</b> ".tf($btime)."<br>
								<b>Ende:</b> ".date("Y-m-d H:i:s",$end_time)."<br>
								<b>Eingesetzte Bewohner:</b> ".nf($buildlist[BUILD_BUILDING_ID]['buildlist_people_working'])."<br>
								<b>Gen-Tech Level:</b> ".GEN_TECH_LEVEL."<br><br>
								<b>Kosten</b><br>
								<b>".RES_METAL.":</b> ".nf($bc['metal'])."<br>
								<b>".RES_CRYSTAL.":</b> ".nf($bc['crystal'])."<br>
								<b>".RES_PLASTIC.":</b> ".nf($bc['plastic'])."<br>
								<b>".RES_FUEL.":</b> ".nf($bc['fuel'])."<br>
								<b>".RES_FOOD.":</b> ".nf($bc['food'])."<br><br>
								<b>Restliche Rohstoffe auf dem Planeten</b><br><br>
								<b>".RES_METAL.":</b> ".nf($c->res->metal)."<br>
								<b>".RES_CRYSTAL.":</b> ".nf($c->res->crystal)."<br>
								<b>".RES_PLASTIC.":</b> ".nf($c->res->plastic)."<br>
								<b>".RES_FUEL.":</b> ".nf($c->res->fuel)."<br>
								<b>".RES_FOOD.":</b> ".nf($c->res->food)."<br><br>
								";
								
								//Log Speichern
								add_log_game_building($log_text,$s['user']['id'],$s['user']['alliance_id'],$c->id,$arr['building_id'],$b_status,time());
								
							}
							else
								echo "<i>Bauauftrag kann nicht gestartet werden, zuwenig Rohstoffe vorhanden!</i><br/><br/>";
						}
						else
							echo "<i>Bauauftrag kann nicht gestartet werden, zuwenig Felder vorhanden!</i><br/><br/>";
					}
					else
						echo "<i>Bauauftrag kann nicht gestartet werden, es wird bereits an einem Geb&auml;ude gearbeitet!</i><br/><br/>";
				}

				//Gebäude abbrechen
				if ($_POST['command_demolish']!="" && $b_status==0)
				{
					if (!$builing_something)
					{
						if ($c->res->metal >= $dc['metal'] && $c->res->crystal >= $dc['crystal'] && $c->res->plastic >= $dc['plastic']  && $c->res->fuel >= $dc['fuel']  && $c->res->food >= $dc['food'])
						{
							$end_time = time()+$dtime;
							dbquery("
							UPDATE 
								".$db_table['buildlist']." 
							SET
								buildlist_build_type='2',
								buildlist_build_start_time='".time()."',
								buildlist_build_end_time='".$end_time."'
							WHERE 
								buildlist_building_id='".$arr['building_id']."'
								AND buildlist_user_id='".$s['user']['id']."'
								AND buildlist_planet_id='".$c->id."';");
								
							//Rohstoffe vom Planeten abziehen und aktualisieren
							$c->changeRes(-$dc['metal'],-$dc['crystal'],-$dc['plastic'],-$dc['fuel'],-$dc['food']);
							$b_status=2;
							
							
							//Log schreiben
							$log_text = "
							<b>Gebäude Abriss</b><br><br>
							<b>User:</b> [USER_ID=".$s['user']['id'].";USER_NICK=".$s['user']['nick']."]<br>
							<b>Planeten:</b> [PLANET_ID=".$c->id.";PLANET_NAME=".$c->name."]<br>
							<b>Gebäude:</b> ".$arr['building_name']."<br>
							<b>Gebäude Level:</b> ".$b_level." (vor Abriss)<br>
							<b>Abriss dauer:</b> ".tf($dtime)."<br>
							<b>Ende:</b> ".date("Y-m-d H:i:s",$end_time)."<br><br>
							<b>Kosten</b><br>
							<b>".RES_METAL.":</b> ".nf($dc['metal'])."<br>
							<b>".RES_CRYSTAL.":</b> ".nf($dc['crystal'])."<br>
							<b>".RES_PLASTIC.":</b> ".nf($dc['plastic'])."<br>
							<b>".RES_FUEL.":</b> ".nf($dc['fuel'])."<br>
							<b>".RES_FOOD.":</b> ".nf($dc['food'])."<br><br>
							<b>Restliche Rohstoffe auf dem Planeten</b><br><br>
							<b>".RES_METAL.":</b> ".nf($c->res->metal)."<br>
							<b>".RES_CRYSTAL.":</b> ".nf($c->res->crystal)."<br>
							<b>".RES_PLASTIC.":</b> ".nf($c->res->plastic)."<br>
							<b>".RES_FUEL.":</b> ".nf($c->res->fuel)."<br>
							<b>".RES_FOOD.":</b> ".nf($c->res->food)."<br><br>
							";
							
							//Log Speichern
							add_log_game_building($log_text,$s['user']['id'],$s['user']['alliance_id'],$c->id,$arr['building_id'],$b_status,time());	
							
						}
						else
							echo "<i>Abbruchauftrag kann nicht gestartet werden, zuwenig Rohstoffe vorhanden!</i><br/><br/>";
					}
					else
						echo "<i>Abbruchauftrag kann nicht gestartet werden, es wird bereits an einem Geb&auml;ude gearbeitet!</i><br/><br/>";
				}

				//Bauauftrag abbrechen
				if ($_POST['command_cbuild']!="" && $b_status==1)
				{
					if ($buildlist[$arr['building_id']]['buildlist_build_end_time'] > time())
					{
						$fac = ($end_time-time())/($end_time-$start_time);
						dbquery("
						UPDATE 
							".$db_table['buildlist']." 
						SET
							buildlist_build_type=0,
							buildlist_build_start_time=0,
							buildlist_build_end_time=0
						WHERE 
							buildlist_building_id='".$arr['building_id']."'
							AND buildlist_user_id='".$s['user']['id']."'
							AND buildlist_planet_id='".$c->id."';");
							
						//Rohstoffe vom Planeten abziehen und aktualisieren
						$c->changeRes($bc['metal']*$fac,$bc['crystal']*$fac,$bc['plastic']*$fac,$bc['fuel']*$fac,$bc['food']*$fac);
						$b_status=0;
						$builing_something=false;
						
						//Log schreiben
						$log_text = "
						<b>Gebäudebau Abbruch</b><br><br>
						<b>User:</b> [USER_ID=".$s['user']['id'].";USER_NICK=".$s['user']['nick']."]<br>
						<b>Planeten:</b> [PLANET_ID=".$c->id.";PLANET_NAME=".$c->name."]<br>
						<b>Gebäude:</b> ".$arr['building_name']."<br>
						<b>Gebäude Level:</b> ".$b_level." (nach Abbruch)<br>
						<b>Start des Gebädes:</b> ".date("Y-m-d H:i:s",$start_time)."<br>
						<b>Ende des Gebädes:</b> ".date("Y-m-d H:i:s",$end_time)."<br><br>
						<b>Erhaltene Rohstoffe</b><br>
						<b>Faktor:</b> ".$fac."<br>
						<b>".RES_METAL.":</b> ".nf($bc['metal']*$fac)."<br>
						<b>".RES_CRYSTAL.":</b> ".nf($bc['crystal']*$fac)."<br>
						<b>".RES_PLASTIC.":</b> ".nf($bc['plastic']*$fac)."<br>
						<b>".RES_FUEL.":</b> ".nf($bc['fuel']*$fac)."<br>
						<b>".RES_FOOD.":</b> ".nf($bc['food']*$fac)."<br><br>
						<b>Rohstoffe auf dem Planeten</b><br><br>
						<b>".RES_METAL.":</b> ".nf($c->res->metal)."<br>
						<b>".RES_CRYSTAL.":</b> ".nf($c->res->crystal)."<br>
						<b>".RES_PLASTIC.":</b> ".nf($c->res->plastic)."<br>
						<b>".RES_FUEL.":</b> ".nf($c->res->fuel)."<br>
						<b>".RES_FOOD.":</b> ".nf($c->res->food)."<br><br>
						";
						
						//Log Speichern
						add_log_game_building($log_text,$s['user']['id'],$s['user']['alliance_id'],$c->id,$arr['building_id'],$b_status,time());								
					}
					else
						echo "<i>Bauauftrag kann nicht mehr abgebrochen werden, die Arbeit ist bereits fertiggestellt!</i><br/><br/>";
				}

				//Abbruchauftrag abbrechen
				if ($_POST['command_cdemolish']!="" && $b_status==2)
				{
					if ($buildlist[$arr['building_id']]['buildlist_build_end_time'] > time())
					{
						$fac = ($end_time-time())/($end_time-$start_time);
						dbquery("
						UPDATE 
							".$db_table['buildlist']." 
						SET
							buildlist_build_type=0,
							buildlist_build_start_time=0,
							buildlist_build_end_time=0
						WHERE 
							buildlist_building_id='".$arr['building_id']."'
							AND buildlist_user_id='".$s['user']['id']."'
							AND buildlist_planet_id='".$c->id."';");
						
						//Rohstoffe vom Planeten abziehen und aktualisieren
						$c->changeRes($dc['metal']*$fac,$dc['crystal']*$fac,$dc['plastic']*$fac,$dc['fuel']*$fac,$dc['food']*$fac);
						$b_status=0;
						$builing_something=false;
						
						//Log schreiben
						$log_text = "
						<b>Gebäudeabbruch Abbruch</b><br><br>
						<b>User:</b> [USER_ID=".$s['user']['id'].";USER_NICK=".$s['user']['nick']."]<br>
						<b>Planeten:</b> [PLANET_ID=".$c->id.";PLANET_NAME=".$c->name."]<br>
						<b>Gebäude:</b> ".$arr['building_name']."<br>
						<b>Gebäude Level:</b> ".$b_level." (nach Abbruch)<br>
						<b>Start des Gebädes:</b> ".date("Y-m-d H:i:s",$start_time)."<br>
						<b>Ende des Gebädes:</b> ".date("Y-m-d H:i:s",$end_time)."<br><br>
						<b>Erhaltene Rohstoffe</b><br>
						<b>Faktor:</b> ".$fac."<br>
						<b>".RES_METAL.":</b> ".nf($dc['metal']*$fac)."<br>
						<b>".RES_CRYSTAL.":</b> ".nf($dc['crystal']*$fac)."<br>
						<b>".RES_PLASTIC.":</b> ".nf($dc['plastic']*$fac)."<br>
						<b>".RES_FUEL.":</b> ".nf($dc['fuel']*$fac)."<br>
						<b>".RES_FOOD.":</b> ".nf($dc['food']*$fac)."<br><br>
						<b>Rohstoffe auf dem Planeten</b><br><br>
						<b>".RES_METAL.":</b> ".nf($c->res->metal)."<br>
						<b>".RES_CRYSTAL.":</b> ".nf($c->res->crystal)."<br>
						<b>".RES_PLASTIC.":</b> ".nf($c->res->plastic)."<br>
						<b>".RES_FUEL.":</b> ".nf($c->res->fuel)."<br>
						<b>".RES_FOOD.":</b> ".nf($c->res->food)."<br><br>
						";
						
						//Log Speichern
						add_log_game_building($log_text,$s['user']['id'],$s['user']['alliance_id'],$c->id,$arr['building_id'],$b_status,time());							
					}
					else
						echo "<i>Abbruchauftrag kann nicht mehr abgebrochen werden, die Arbeit ist bereits fertiggestellt!</i><br/><br/>";
				}


				if ($b_status==1 && $b_level>0)
				{
					$color="color:#0f0;";
					$status_text="Wird ausgebaut";
				}
				elseif ($b_status==1)
				{
					$color="color:#0f0;";
					$status_text="Wird gebaut";
				}
				elseif ($b_status==2)
				{
					$color="color:#f80;";
					$status_text="Wird abgerissen";
				}
				else
				{
					$color="";
					$status_text="Unt&auml;tig";
				}

				//
				// Gebäudedaten anzeigen
				//
				$path = IMAGE_PATH."/".IMAGE_BUILDING_DIR."/building".$arr['building_id'].".".IMAGE_EXT;
				$title = $arr['building_name'].' <span id="buildlevel">';
				$title.= $b_level > 0 ? $b_level : '';
				$title.= '</span>';
				infobox_start($title,1);
				echo "<tr>
                  <td rowspan=\"4\" class=\"tbldata\" style=\"width:220px;background:#000;vertical-align:middle;\">
                  	<a href=\"?page=help&amp;site=buildings&amp;id=".$arr['building_id']."\">
                  		<img src=\"".$path."\" style=\"width:220px;height:220px;border:none;\" alt=\"".$arr['building_name']."\" />
                  	</a>
                  </td>
                  <td colspan=\"2\" class=\"tbldata\" style=\"vertical-align:top;height:150px;\">
                  	".$arr['building_longcomment']."
                 	</td>
				     </tr>";
       	$f = $arr['building_fields'];
				echo "<tr>
                  <td class=\"tbltitle\" style=\"width:250px;height:20px;\">Platzverbrauch pro Ausbaustufe:</td>
                  <td class=\"tbldata\">".$f." ".($f!=1 ? 'Felder' : 'Feld')."</td>
      	</tr>";
      	$f = $arr['building_fields'] * $b_level;
				echo "<tr>
                  <td class=\"tbltitle\" style=\"width:250px;height:20px;\">Platzverbrauch total:</td>
       						<td class=\"tbldata\">".$f." ".($f!=1 ? 'Felder' : 'Feld')."</td>
						</tr>";
				echo "<tr>
                  <td class=\"tbltitle\" style=\"width:250px;height:20px;\">Status:</td>
                  <td class=\"tbldata\" style=\"".$color."\" id=\"buildstatus\" >$status_text</td>
				     </tr>";
				infobox_end(1);


				// Check requirements for this building
				$requirements_passed = true;
				$bid = $arr['building_id'];
				if (count($b_req[$bid]['b'])>0)
				{
					foreach ($b_req[$bid]['b'] as $b => $l)
					{
						if ($buildlist[$b]['buildlist_current_level']<$l)
						{
							$requirements_passed = false;
						}
					}
				}								
				if (count($b_req[$bid]['t'])>0)
				{
					foreach ($b_req[$bid]['t'] as $id => $level)
					{
						if ($techlist[$id]<$level)
						{
							$requirements_passed = false;
						}
					}
				}


				//
				// Baumenü
				//
				echo "<form action=\"?page=$page\" method=\"post\">";
        echo "<input type=\"hidden\" name=\"id\" value=\"".$arr['building_id']."\">";
        checker_init();
        if ($requirements_passed)
        {
					infobox_start("Bauoptionen",1);
					echo "<tr>
	                <td class=\"tbltitle\" width=\"16%\">Aktion</td>
	                <td class=\"tbltitle\" width=\"14%\">".RES_ICON_TIME." Zeit</th>
	                <td class=\"tbltitle\" width=\"14%\">".RES_ICON_METAL."</td>
	                <td class=\"tbltitle\" width=\"14%\">".RES_ICON_CRYSTAL."</td>
	                <td class=\"tbltitle\" width=\"14%\">".RES_ICON_PLASTIC."</td>
	                <td class=\"tbltitle\" width=\"14%\">".RES_ICON_FUEL."</td>
	                <td class=\"tbltitle\" width=\"14%\">".RES_ICON_FOOD."</td>
	                <td class=\"tbltitle\" width=\"14%\">".RES_ICON_POWER."</td>
							</tr>";
	
					// Bauen
					if ($b_status==0)
					{
						$bWaitArray = calcBuildingWaitTime($bc,$c);
	
						// Maximale Stufe erreicht
						if ($b_level>=$arr['building_last_level'])
						{
							echo "<tr>
											<td colspan=\"8\" class=\"tbldata\">
												<i>Kein weiterer Ausbau m&ouml;glich.</i>
											</td>
										</tr>";
						}
						// Es wird bereits an einem Gebäude gebaut
						elseif ($builing_something)
						{
							echo "<tr>
											<td class=\"tbldata\" style=\"color:red;\">Bauen</td>
											<td class=\"tbldata\">".tf($btime)."</td>";
							echo $bWaitArray[0];
							echo "<tr>
											<td class=\"tbldata\" colspan=\"8\">
												<i>Es kann nichts gebaut werden da gerade an einem anderen Geb&auml;ude gearbeitet wird!</i>
											</td>
										</tr>";
						}
						// Zuwenig Felder vorhanden
						elseif ($arr['building_fields']>0 && ($c->fields_used+$arr['building_fields']+$def_field_needed > $c->fields+$c->fields_extra))
						{
							echo "<tr>
											<td class=\"tbldata\" style=\"color:red;\">Bauen</td>
											<td class=\"tbldata\">".tf($btime)."</td>";
							echo $bWaitArray[0];
							echo "<tr>
											<td class=\"tbldata\" colspan=\"8\">
												<i>Kein Ausbau m&ouml;glich, da es zuwenig Platz (Total: ".($c->fields+$c->fields_extra).", reserviert: ".($c->fields_used+$def_field_needed).", benötigt: ".$arr['building_fields'].") f&uuml;r dieses Geb&auml;ude hat!</i>
											</td>
										</tr>";
						}
						// Zuwenig Rohstoffe vorhanden
						elseif ($c->res->metal<$bc['metal'] || 
						$c->res->crystal<$bc['crystal']  || 
						$c->res->plastic<$bc['plastic']  || 
						$c->res->fuel<$bc['fuel']  || 
						$c->res->food<$bc['food'] || 
						($c->prod->power - $c->use->power < $bc['power'] && $bc['power']>0)
						)
						{
							echo "<tr>
											<td class=\"tbldata\" style=\"color:red;\">Bauen</td>
											<td class=\"tbldata\">".tf($btime)."</td>";
							echo $bWaitArray[0];
							echo "<tr>
											<td class=\"tbldata\" colspan=\"8\">
												<i>Kein Ausbau m&ouml;glich, zuwenig Rohstoffe!</i>
											</td>
										</tr>";
						}
						else
						{
							// Bauen
							if ($b_level==0)
							{
								echo "<tr>
												<td class=\"tbldata\">
													<input type=\"submit\" class=\"button\" name=\"command_build\" value=\"Bauen\"
												</td>
												<td class=\"tbldata\">".tf($btime)."</td>";
							}
							// Ausbauen
							else
							{
								echo "<tr>
												<td class=\"tbldata\">
													<input type=\"submit\" class=\"button\" name=\"command_build\" value=\"Ausbauen\">
												</td>
												<td class=\"tbldata\">".tf($btime)."</td>";
							}
							
									echo "<td class=\"tbldata\">".nf($bc['metal'])."</td>
												<td class=\"tbldata\">".nf($bc['crystal'])."</td>
												<td class=\"tbldata\">".nf($bc['plastic'])."</td>
												<td class=\"tbldata\">".nf($bc['fuel'])."</td>
												<td class=\"tbldata\">".nf($bc['food'])."</td>
												<td class=\"tbldata\">".nf($bc['power'])."</td>
											</tr>";
						}
					}
	
					// Abreissen
					if ($b_level>0 && $arr['building_demolish_costs_factor']!=0 && $b_status==0)
					{
						$dWaitArray = calcDemolishingWaitTime($dc,$c);
						// Es wird bereits an einem Gebäude gebaut
						if ($builing_something)
						{
							echo "<tr>
											<td class=\"tbldata\" style=\"color:red;\">Abreissen</td>
											<td class=\"tbldata\">".tf($dtime)."</td>";
							echo $dWaitArray[0];
							echo "<tr>
											<td class=\"tbldata\" colspan=\"8\">
													<i>Kein Abriss m&ouml;glich, es wird gerade an einem anderen Geb&auml;ude gearbeitet!</i>
											</td>
										</tr>";
						}
						// Zuwenig Rohstoffe
						elseif ($c->res->metal<$dc['metal'] || 
						$c->res->crystal<$dc['crystal']  || 
						$c->res->plastic<$dc['plastic']  || 
						$c->res->fuel<$dc['fuel']  || 
						$c->res->food<$dc['food'] || 
						($c->prod->power - $c->use->power < $dc['power'] && $dc['power']>0)
						)
						{
							echo "<tr>
											<td class=\"tbldata\" style=\"color:red;\">Abreissen</td>
											<td class=\"tbldata\">".tf($dtime)."</td>";
							echo $dWaitArray[0];
							echo "<tr>
											<td class=\"tbldata\" colspan=\"8\">
												<i>Kein Abriss m&ouml;glich, zuwenig Rohstoffe!</i>
											</td>
										</tr>";
						}
						else
						{
							echo "<tr>
											<td class=\"tbldata\">
												<input type=\"submit\" class=\"button\" name=\"command_demolish\" value=\"Abreissen\">
											</td>
											<td class=\"tbldata\">".tf($dtime)."</td>
											<td class=\"tbldata\">".nf($dc['metal'])."</td>
											<td class=\"tbldata\">".nf($dc['crystal'])."</td>
											<td class=\"tbldata\">".nf($dc['plastic'])."</td>
											<td class=\"tbldata\">".nf($dc['fuel'])."</td>
											<td class=\"tbldata\">".nf($dc['food'])."</td>
											<td class=\"tbldata\">".nf($dc['power'])."</td>
										</tr>";
						}
					}
	
					// Bau abbrechen
					if ($b_status==1)
					{
		      	echo "<tr>
		      					<td class=\"tbldata\">
		      						<input type=\"submit\" class=\"button\" id=\"buildcancel\" name=\"command_cbuild\" value=\"Bau abbrechen\" onclick=\"if (this.value=='Bau abbrechen'){return confirm('Wirklich abbrechen?');}\" />
		      					</td>
		      					<td class=\"tbldata\" id=\"buildtime\">-</td>
		      					<td colspan=\"6\" class=\"tbldata\">&nbsp;</td>
		      				</tr>";
		      	if ($b_level<$arr['building_last_level']-1)
		      	{
		         	echo "<tr>
		         					<td class=\"tbldata\" width=\"90\">N&auml;chste Stufe:</td>
		         					<td class=\"tbldata\">".tf($btimen)."</td>
		         					<td class=\"tbldata\">".nf($bcn['metal'])."</td>
		         					<td class=\"tbldata\">".nf($bcn['crystal'])."</td>
		         					<td class=\"tbldata\">".nf($bcn['plastic'])."</td>
		         					<td class=\"tbldata\">".nf($bcn['fuel'])."</td>
		         					<td class=\"tbldata\">".nf($bcn['food'])."</td>
		         					<td class=\"tbldata\">".nf($bcn['power'])."</td>
		         				</tr>";
		         }
					}
	
					// Abriss abbrechen
					if ($b_status==2)
					{
		      	echo "<tr>
		      					<td class=\"tbldata\">
		      						<input type=\"submit\" class=\"button\" id=\"buildcancel\" name=\"command_cdemolish\" value=\"Abriss abbrechen\" onclick=\"if (this.value=='Abriss abbrechen'){return confirm('Wirklich abbrechen?');}\" />
		      					</td>
		      					<td class=\"tbldata\" id=\"buildtime\">-</td>
		      					<td class=\"tbldata\" colspan=\"6\">&nbsp;</td>
		      				</tr>";
					}
					infobox_end(1);
					
					
	
					if ($bWaitArray[1]>0)
					{
						echo "Wartezeit bis gen&uuml;gend Rohstoffe zum Bau vorhanden sind: <b>".tf($bWaitArray[1])."</b><br/>";
					}
					if ($dWaitArray[1]>0)
					{
						echo "Wartezeit bis gen&uuml;gend Rohstoffe zum Abriss vorhanden sind: <b>".tf($dWaitArray[1])."</b><br/>";
					}
					echo "<br/>";
	
					if ($b_status==1 || $b_status==2)
					{
						?>
							<script type="text/javascript">
								function setCountdown()
								{
									var ts;
									cTime = <?PHP echo time();?>;
									b_level = <?PHP echo $b_level;?>;
									te = <?PHP if($end_time) echo $end_time; else echo 0;?>;
									tc = cTime + cnt;
									window.status = tc;
									ts = te - tc;
	
									if(b_level>0)
									{
										<?PHP 
										if ($b_status==2) 
											echo "b_level=b_level-1;";
										else
											echo "b_level=b_level+1;";
										?>										
									}
									else
									{
										b_level=1;
									}
	
									if (ts>=0)
									{
										t = Math.floor(ts / 3600 / 24);
										h = Math.floor(ts / 3600);
										m = Math.floor((ts-(h*3600))/60);
										s = Math.floor((ts-(h*3600)-(m*60)));
										nv = h+"h "+m+"m "+s+"s";
									}
									else
									{
										nv = "-";
										document.getElementById('buildstatus').firstChild.nodeValue="Fertig";
										document.getElementById('buildlevel').innerHTML=b_level;
										document.getElementById("buildcancel").name = "command_show";
							  		document.getElementById("buildcancel").value = "Aktualisieren";
									}
									document.getElementById('buildtime').firstChild.nodeValue=nv;
									cnt = cnt + 1;
									setTimeout("setCountdown()",1000);
								}
								if (document.getElementById('buildtime')!=null)
								{
									cnt = 0;
									setCountdown();
								}
							</script>
						<?PHP
					}
				
				}
				else
				{
					echo '<div>Gebäude kann nicht (aus)gebaut werden, <a href="?page=techtree">Voraussetzungen</a> nicht erfüllt!<br/><br/></div>';
				}

				echo "<input type=\"submit\" name=\"command_show\" value=\"Aktualisieren\" /> &nbsp; ";
				echo "<input type=\"button\" value=\"Zur&uuml;ck zur &Uuml;bersicht\" onclick=\"document.location='?page=$page'\" />";
				echo "</form>";
			}
			else
				echo "<b>Fehler:</b> Geb&auml;ude nicht vorhanden!<br/><br/><a href=\"?page=$page\">&Uuml;bersicht</a>";
		}

/********************
* Übersicht         *
********************/

		else
		{
	
		
			$tres = dbquery("
			SELECT
				*
			FROM
        ".$db_table['building_types']."
			ORDER BY
				type_order ASC
			;");				
			if (mysql_num_rows($tres)>0)
			{

				// Gebäude laden
				$bres = dbquery("
				SELECT
					building_type_id,
					building_id,
					building_name,
					building_last_level,
					building_shortcomment,
					building_costs_metal,
					building_costs_crystal,
					building_costs_plastic,
					building_costs_fuel,
					building_costs_food,
					building_build_costs_factor,
					building_show
				FROM
					".$db_table['buildings']."
				ORDER BY
					building_order,
					building_name
				;");	
				$building = array();
				if (mysql_num_rows($bres)>0)			
				{
					while ($barr = mysql_Fetch_Array($bres))
					{
						$tid = $barr['building_type_id'];
						$bid = $barr['building_id'];
						$building[$tid][$bid]['name'] = $barr['building_name'];
						$building[$tid][$bid]['last_level'] = $barr['building_last_level'];
						$building[$tid][$bid]['shortcomment'] = $barr['building_shortcomment'];
						$building[$tid][$bid]['building_costs_metal'] = $barr['building_costs_metal'];
						$building[$tid][$bid]['building_costs_crystal'] = $barr['building_costs_crystal'];
						$building[$tid][$bid]['building_costs_plastic'] = $barr['building_costs_plastic'];
						$building[$tid][$bid]['building_costs_fuel'] = $barr['building_costs_fuel'];
						$building[$tid][$bid]['building_costs_food'] = $barr['building_costs_food'];
						$building[$tid][$bid]['building_build_costs_factor'] = $barr['building_build_costs_factor'];
						$building[$tid][$bid]['show'] = $barr['building_show'];
					}
				}
				
				// Jede Kategorie durchgehen
				$cstr=checker_init();
				echo "<form action=\"?page=$page\" method=\"post\"><div>";
				echo $cstr;

				while ($tarr = mysql_fetch_array($tres))
				{
					infobox_start($tarr['type_name'],1,1);

						$cnt = 0; // Counter for current row
						$scnt = 0; // Counter for shown buildings

						$bdata = $building[$tarr['type_id']];
						if (count($bdata)>0)
						{
							foreach ($bdata as $bid => $bv)
							{
								
								// Aktuellen Level feststellen
								$b_level = intval($buildlist[$bid]['buildlist_current_level']);
								$end_time = intval($buildlist[$bid]['buildlist_build_end_time']);

								// Check requirements for this building
								$requirements_passed = true;
								if (count($b_req[$bid]['b'])>0)
								{
									foreach ($b_req[$bid]['b'] as $b => $l)
									{
										if ($buildlist[$b]['buildlist_current_level']<$l)
										{
											$requirements_passed = false;
										}
									}
								}								
								if (count($b_req[$bid]['t'])>0)
								{
									foreach ($b_req[$bid]['t'] as $id => $level)
									{
										if ($techlist[$id]<$level)
										{
											$requirements_passed = false;
										}
									}
								}

								// Voraussetzungen nicht erfüllt
								if (!$requirements_passed)
								{
									$subtitle =  'Voraussetzungen nicht erfüllt';
									$tmtext = '<span style="color:#999">Baue zuerst die nötigen Gebäude und erforsche die nötigen Technologien um diese Gebäude zu bauen!</span><br/>';
									$color = '#999';
									if($use_img_filter)
									{
										$img = "inc/imagefilter.php?file=".IMAGE_PATH."/".IMAGE_BUILDING_DIR."/building".$bid."_middle.".IMAGE_EXT."&filter=na";
									}
									else
									{
										$img="".IMAGE_PATH."/".IMAGE_BUILDING_DIR."/building".$bid."_middle.".IMAGE_EXT."";
									}							
									
								}
								// Ist im Bau
								elseif ($buildlist[$bid]['buildlist_build_type']==1)
								{
									$subtitle =  "Ausbau auf Stufe ".($b_level+1);
									$tmtext = "<span style=\"color:#0f0\">Wird ausgebaut!<br/>Dauer: ".tf($end_time-time())."</span><br/>";
									$color = '#0f0';
									if($use_img_filter)
									{
										$img = "inc/imagefilter.php?file=".IMAGE_PATH."/".IMAGE_BUILDING_DIR."/building".$bid."_middle.".IMAGE_EXT."&filter=building";
									}
									else
									{
										$img="".IMAGE_PATH."/".IMAGE_BUILDING_DIR."/building".$bid."_middle.".IMAGE_EXT."";
									}
								}
								// Wird abgerissen
								elseif ($buildlist[$bid]['buildlist_build_type']==2)
								{
									$subtitle = "Abriss auf Stufe ".($b_level-1);
									$tmtext = "<span style=\"color:#f90\">Wird abgerissen!<br/>Dauer: ".tf($end_time-time())."</span><br/>";
									$color = '#f90';
									if($use_img_filter)
									{
										$img = "inc/imagefilter.php?file=".IMAGE_PATH."/".IMAGE_BUILDING_DIR."/building".$bid."_middle.".IMAGE_EXT."&filter=destructing";
									}
									else
									{
										$img="".IMAGE_PATH."/".IMAGE_BUILDING_DIR."/building".$bid."_middle.".IMAGE_EXT."";
									}
								}
								// Untätig
								else
								{
									// Zuwenig Ressourcen
									
                	$bc = calcBuildingCosts($bv,$b_level);
									if($b_level<$bv['last_level'] && $c->res->metal < $bc['metal'] || $c->res->crystal < $bc['crystal']  || $c->res->plastic < $bc['plastic']  || $c->res->fuel < $bc['fuel']  || $c->res->food < $bc['food'])
									{
										$tmtext = "<span style=\"color:#f00\">Zuwenig Ressourcen f&uuml;r<br/>weiteren Ausbau!</span><br/>";
										$color = '#f00';
										if($use_img_filter)
										{
											$img = "inc/imagefilter.php?file=".IMAGE_PATH."/".IMAGE_BUILDING_DIR."/building".$bid."_middle.".IMAGE_EXT."&filter=lowres";
										}
										else
										{
											$img="".IMAGE_PATH."/".IMAGE_BUILDING_DIR."/building".$bid."_middle.".IMAGE_EXT."";
										}
									}
									else
									{
										$tmtext = "";
										$color = '#fff';
										$img="".IMAGE_PATH."/".IMAGE_BUILDING_DIR."/building".$bid."_middle.".IMAGE_EXT."";
									}
									
									if ($b_level==0)
									{
										$subtitle = "Noch nicht gebaut";
									}
									elseif ($b_level>=$bv['last_level'])
									{
										$subtitle = 'Vollständig ausgebaut';
									}
									else
									{
										$subtitle = 'Gebaut';
									}
								}

								// Display all buildings that are buildable or are already built
								if (($requirements_passed && $bv['show']==1) || $b_level>0)
								{			
									// Display row starter if needed				
									if ($cnt==0) 
									{
										echo "<tr>";
									}

									echo "<td class=\"tbldata\" style=\"color:".$color.";text-align:center;width:".CELL_WIDTH."px\">
													<b>".$bv['name']."";
													if ($b_level>0) echo ' '.$b_level;
													echo "</b><br/>".$subtitle."<br/>
													<input name=\"show_".$bid."\" type=\"image\" value=\"".$bid."\" src=\"".$img."\" ".tm($bv['name'],$tmtext.$bv['shortcomment'])." style=\"width:120px;height:120px;\" />
									</td>\n";

									$cnt++;
									$scnt++;
								}
									
								// Display row finisher if needed			
								if ($cnt==NUM_BUILDINGS_PER_ROW)
								{
									echo "</tr>";
									$cnt = 0;
								}								
							}
							
							// Fill up missing cols and end row
							if ($cnt<NUM_BUILDINGS_PER_ROW && $cnt>0)
							{
								for ($x=0;$x < NUM_BUILDINGS_PER_ROW-$cnt;$x++)
								{
									echo "<td class=\"tbldata\" style=\"width:".CELL_WIDTH."px;\">&nbsp;</td>";
								}
								echo '</tr>';
							}							
							
							if ($scnt==0)
							{								
								echo "<tr>
												<td class=\"tbldata\" colspan=\"".NUM_BUILDINGS_PER_ROW."\" style=\"text-align:center;border:0;width:100%\">
													<i>In dieser Kategorie kann momentan noch nichts gebaut werden!</i>
												</td>
											</tr>";								
							}						
						}
						else
						{
							echo "<tr>
											<td class=\"tbldata\" colspan=\"".NUM_BUILDINGS_PER_ROW."\" style=\"text-align:center;border:0;width:100%\">
												<i>In dieser Kategorie kann momentan noch nichts gebaut werden!</i>
											</td>
										</tr>";
						}
					infobox_end(1);
				}				
				echo '</div></form>';
			}
			else
			{
				echo "<i>Es k&ouml;nnen noch keine Geb&auml;ude gebaut werden!</i>";
			}
		}

	}
	// ENDE SKRIPT //

	?>
