	<div id="sidebar">
		
		<a href="?page=overview" id="logo"></a>
		
		<div id="navcontainer">
			<a href="?page=planetoverview" id="navplanetoverview"></a>
			<a href="?page=economy" id="naveconomy"></a>
			<a href="?page=population" id="navpopulation"></a>
			<a href="?page=haven" id="navhaven"></a>
			<a href="?page=buildings" id="navbuildings"></a>
			<a href="?page=research" id="navresearch"></a>
			<a href="?page=shipyard" id="navshipyard"></a>
			<a href="?page=defense" id="navdefense"></a>
			<a href="?page=market" id="navmarket"></a>
			<a href="?page=crypto" id="navcrypto"></a>
			<a href="?page=recycle" id="navrecycle"></a>
		</div>
		<div id="planetimage">
			<img src="{$currentPlanetImage}" alt="Planet" style="width:100px;height:100px;" />
		</div>
		{literal}
		<div id="planetname" 
			onmouseover="if (document.getElementById('planetlist').style.display=='none') Effect.Appear('planetlist',{duration:0.1});" 
			onmouseout="document.getElementById('planetlist').style.display='none'">
		{/literal}
			{$currentPlanetName}
		</div>	
		{literal}
		<div id="planetlist" style="display:none;" 
			onmouseover="this.style.display='block'" 
			onmouseout="this.style.display='none'" 
			>	
		{/literal}
				<div>
					{$planetListImages}
				</div>
		</div>
	</div>
	<div id="hbar">
		<a href="?page=stats" id="navstats" onmouseover="hideAllHbarMenus();"></a>
		{literal}<a href="?page=cell" id="navmap" onmouseover="		
				if (document.getElementById('hbarmapslide').style.display=='none') { Effect.SlideDown('hbarmapslide',{duration:0.2}); }
				if (document.getElementById('hbarallianceslide').style.display=='') Effect.SlideUp('hbarallianceslide',{duration:0.1}); 
				if (document.getElementById('hbarsettingsslide').style.display=='') Effect.SlideUp('hbarsettingsslide',{duration:0.1}); 
				if (document.getElementById('hbarhelpslide').style.display=='') Effect.SlideUp('hbarhelpslide',{duration:0.1}); 
				if (document.getElementById('hbarlogoutslide').style.display=='') Effect.SlideUp('hbarlogoutslide',{duration:0.1}); 
				return false;"></a>{/literal}		
		{if $fleetAttack > 0}
			<a href="?page=fleets" id="navfleetred" onmouseover="hideAllHbarMenus();"></a>
			<script type="text/javascript">
				{literal}	Effect.Pulsate('navfleetred',{duration:180,pulses:120,from:0.0}); {/literal}
			</script>	
		{/if}
		<a href="?page=fleets" id="navfleet" onmouseover="hideAllHbarMenus();"></a>
		{if $messages > 0}
			<a href="?page=messages" id="navmessagesgreen" onmouseover="hideAllHbarMenus();"></a>
			<script type="text/javascript">
				{literal}	Effect.Pulsate('navmessagesgreen',{duration:180,pulses:120,from:0.0}); {/literal}
			</script>	
		{/if}
		<a href="?page=messages" id="navmessages" onmouseover="hideAllHbarMenus();"></a>
		{literal}<a href="?page=alliance" id="navalliance" onmouseover="
				if (document.getElementById('hbarallianceslide').style.display=='none') { Effect.SlideDown('hbarallianceslide',{duration:0.2}); } 
				if (document.getElementById('hbarmapslide').style.display=='') Effect.SlideUp('hbarmapslide',{duration:0.1}); 
				if (document.getElementById('hbarsettingsslide').style.display=='') Effect.SlideUp('hbarsettingsslide',{duration:0.1}); 
				if (document.getElementById('hbarhelpslide').style.display=='') Effect.SlideUp('hbarhelpslide',{duration:0.1}); 
				if (document.getElementById('hbarlogoutslide').style.display=='') Effect.SlideUp('hbarlogoutslide',{duration:0.1}); 
				return false;"></a>{/literal}		
		{if $buddyreq>0}<a href="?page=buddylist" id="navbuddylistred" onmouseover="hideAllHbarMenus();"></a>
		{elseif $buddys > 0}<a href="?page=buddylist" id="navbuddylistgreen" onmouseover="hideAllHbarMenus();"></a>
		{else}<a href="?page=buddylist" id="navbuddylist" onmouseover="hideAllHbarMenus();"></a>{/if}
		{literal}<a href="?page=userconfig" id="navuserconfig" onmouseover="
				if (document.getElementById('hbarsettingsslide').style.display=='none') { Effect.SlideDown('hbarsettingsslide',{duration:0.2}); }
				if (document.getElementById('hbarallianceslide').style.display=='') Effect.SlideUp('hbarallianceslide',{duration:0.1}); 
				if (document.getElementById('hbarmapslide').style.display=='') Effect.SlideUp('hbarmapslide',{duration:0.1}); 
				if (document.getElementById('hbarhelpslide').style.display=='') Effect.SlideUp('hbarhelpslide',{duration:0.1}); 
				if (document.getElementById('hbarlogoutslide').style.display=='') Effect.SlideUp('hbarlogoutslide',{duration:0.1}); 
				return false;"></a>{/literal}
		{literal}<a href="?page=help" id="navhelp" onmouseover="		
				if (document.getElementById('hbarhelpslide').style.display=='none'){  Effect.SlideDown('hbarhelpslide',{duration:0.2}); }
				if (document.getElementById('hbarsettingsslide').style.display=='') Effect.SlideUp('hbarsettingsslide',{duration:0.1}); 
				if (document.getElementById('hbarallianceslide').style.display=='') Effect.SlideUp('hbarallianceslide',{duration:0.1}); 
				if (document.getElementById('hbarmapslide').style.display=='') Effect.SlideUp('hbarmapslide',{duration:0.1}); 
				if (document.getElementById('hbarlogoutslide').style.display=='') Effect.SlideUp('hbarlogoutslide',{duration:0.1}); 
				return false;"></a>{/literal}		
		{literal}<a href="?logout=1" id="navlogout" onmouseover="		
				if (document.getElementById('hbarlogoutslide').style.display=='none'){  Effect.SlideDown('hbarlogoutslide',{duration:0.2}); }
				if (document.getElementById('hbarhelpslide').style.display==''){  Effect.SlideUp('hbarhelpslide',{duration:0.2}); }
				if (document.getElementById('hbarsettingsslide').style.display=='') Effect.SlideUp('hbarsettingsslide',{duration:0.1}); 
				if (document.getElementById('hbarallianceslide').style.display=='') Effect.SlideUp('hbarallianceslide',{duration:0.1}); 
				if (document.getElementById('hbarmapslide').style.display=='') Effect.SlideUp('hbarmapslide',{duration:0.1}); 
				return false;"></a>{/literal}
	</div>


	<div id="hbarmap">
		<div id="hbarmapslide" style="display:none;">
			<div>
				<a href="?page=cell">Sonnensystem</a>		
				<a href="?page=map">Sektor</a>
				<a href="?page=galaxy">Galaxie</a>
			</div>
		</div>
	</div>	
	<div id="hbaralliance">
		<div id="hbarallianceslide" style="display:none;">
			<div>
				<a href="?page=alliance">Allianz</a>
				<a href="?page=allianceboard">Allianzforum</a>		
				<a href="?page=alliance&amp;action=base">Allianzbasis</a>		
				<a href="?page=townhall">Rathaus</a>
			</div>
		</div>
	</div>
	<div id="hbarsettings">
		<div id="hbarsettingsslide" style="display:none;">
			<div>
				<a href="?page=userconfig">Einstellungen</a>
				<a href="?page=bookmarks">Favoriten</a>
				<a href="?page=userinfo">Profil</a>
				<a href="?page=notepad">Notizen</a>		
			</div>
		</div>
	</div>
	<div id="hbarhelp">
		<div id="hbarhelpslide" style="display:none;">
			<div>
				<a href="?page=help">Hilfe</a>
				<a href="?page=techtree">Technikbaum</a>
				<a href="?page=ticket">Ticketsystem</a>
				<a href="#" onclick="window.open('{$bugreportUrl}');">Fehler melden</a>
				<a href="#" onclick="{$helpcenterOnclick}">Häufige Fragen</a>
				<a href="?page=contact">Über EtoA</a>
			</div>
		</div>
	</div>
	<div id="hbarlogout">
		<div id="hbarlogoutslide" style="display:none;">
			<div>
				<a href="?logout=1">Logout</a>
				<a href="#" onclick="window.open('{$urlForum}')">Forum</a>
				<a href="#" onclick="{$chatOnclick}">Chat</a>
				<a href="#" onclick="{$teamspeakOnclick}">Teamspeak</a>
				<a href="#" onclick="{$rulesOnclick}">Regeln</a>
			</div>
		</div>
	</div>	
	
	<div id="servertime">
		{$serverTime}
	</div>
	
	<div id="contentcontainer">
		{$content}
	</div>