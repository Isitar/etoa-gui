<?PHP

	class FleetActionHijack extends FleetAction
	{

		function FleetActionHijack()
		{
			$this->code = "hijack";
			$this->name = "Schiff entführen";
			$this->desc = "Versucht, ein Schiff vom Ziel zu stehlen.";
			$this->longDesc = "Nähert sich unbemerkt dem Zielplaneten und versucht dort, ein Schiff zu stehlen.";
			$this->visible = false;
			$this->exclusive = true;							
			$this->attitude = 3;
			
			$this->allowPlayerEntities = true;
			$this->allowOwnEntities = false;
			$this->allowNpcEntities = false;
			$this->allowSourceEntity = false;
			$this->allowAllianceEntities = false;
		}

		function startAction() {} 
		function cancelAction() {}		
		function targetAction() {} 
		function returningAction() {}		
		
	}

?>