<?PHP
	/**
	* Remove old defense build list records
	*/
	class CleanupDeflistTask implements IPeriodicTask 
	{		
		function run()
		{
			$nr = Deflist::cleanUp();
			return "$nr alte Verteidigungseinträge gelöscht";
		}
		
		function getDescription() {
			return "Alte Verteidigungsbaudatensätze löschen";
		}
	}
?>