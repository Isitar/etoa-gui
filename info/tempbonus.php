<?php
	echo "<h2>Temperaturbonus</h2>";
	Help::navi(array("Temperaturbonus","tempbonus"));

	infobox_start("Temperaturbonus",1);
	echo "<tr>
		<td class=\"tbltitle\" style=\"width:40px;\">Wärmebonus</td>
		<td class=\"tbldata\">Die Planetentemperatur verstärkt oder schwächt die Produktion von Energie durch Solarsatelliten. 
		Je näher ein Planet bei der Sonne ist, desto besser ist die Temperatur und demzufolge auch die Energieproduktion.
		Der angegebene Wert in der Planetenübersicht zeigt an, wie viel Energie <b>jeder einzelne</b> Solarsatellit zusätlich produziert.
		Gebäude mit grossem Energiebedarf sollten darum auf sonnennahen Planeten gebaut werden.
	</tr>";
	echo "<tr>
		<td class=\"tbltitle\" style=\"width:40px;\">Kältebonus</td>
		<td class=\"tbldata\">Tritium kann auf kälteren Planeten besonders leicht hergestellt werden. mit Der Kältebonus wirkt sich daher prozentual auf die
		Tritiumproduktion aus (zusätzlich zu allen anderen Boni). Kältere Planeten sind weit weg von einem Stern; sie sind für eine grosse Tritiumproduktion sehr zu empfehlen. 
		</td>
	</tr>";	
	infobox_end(1);
?>


