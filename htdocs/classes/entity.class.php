<?PHP

	/**
	* Abstract class for all space entities
	*
	* @author Nicolas Perrenoud <mrcage@etoa.ch>
	*/ 
	abstract class Entity
	{
    protected $isVisible;
    
		/**
		* Private constructor
		* Prevents usage as object
		*/
		private function Entity() {}

		//
		// Abstract methods
		//

		/**
		* Return entity-id
		*/
		public abstract function id();	
		
		/**
		* Return entity name
		*/
		public abstract function name();	
		
		/**
		* Return entity-owner
		*/
		public abstract function owner();	
		
		/**
		* Return entity-owner id
		*/
		public abstract function ownerId();	

		/**
		* Return true if this is the owner's main entity
		*/
		public abstract function ownerMain();	
		
		/**
		* Return entity-code 
		*/
		public abstract function entityCode();	

		/**
		* Return entity-code string
		*/
		public abstract function entityCodeString();	
		
		/**
		* Some entities are dividet into special
		* types; return this type
		*/
		public abstract function type();	

		/**
		* Provies the current image path
		*/
		public abstract function imagePath($opt="");	
	
		/**
		* Return coordinates
		*/		
		public abstract function __toString();	
	  
	  /**
	  * Return cell id
	  */
	  public abstract function cellId();
	
		public abstract function getFleetTargetForwarder();
		
	
		//
		// General methods
		//
		
		public function smallImage()
		{
			return "<img src=\"".$this->imagePath()."\" style=\"width:40px;height:40px;\" alt=\"".$this->name()."\" />";
		}
	
    /**
    * Return if entity is visible in map
    */
    public function isVisible()
    {
      return $this->isVisible; 
    }    
    
    
    public function getCell()
    {
    	return new Cell($this->cellId());
    }
    
    public abstract function allowedFleetActions();
    
		function sx() 
		{ 
			if (!$this->coordsLoaded)
			{
				$this->loadCoords();
			}
			return $this->sx;			
		}    
  
		function sy() 
		{ 
			if (!$this->coordsLoaded)
			{
				$this->loadCoords();
			}
			return $this->sy;			
		}  
		
		function cx() 
		{ 
			if (!$this->coordsLoaded)
			{
				$this->loadCoords();
			}
			return $this->cx;			
		}  
		
		function cy() 
		{ 
			if (!$this->coordsLoaded)
			{
				$this->loadCoords();
			}
			return $this->cy;			
		}  				  
    
		/**
		* Returns owner
		*/                        
		function pos() 
		{ 
			if (!$this->coordsLoaded)
			{
				$this->loadCoords();
			}			
			return $this->pos; 
		}      

		// Overwritable functions  
		function ownerPoints() { return 0; }
		function ownerHoliday() { return false; }
		function ownerLocked() { return false; }
		function ownerAlliance() { return 0; }
		function lastUserCheck() { return 0; }
    
		/**
		* check if data could be loaded
		*/
		public function isValid()
		{
			return $this->isValid;
		}	
	  
	  public function loadCoords()
	  {   
	  	if (!$this->coordsLoaded)
	  	{
		  	$res = dbquery("
		  	SELECT
		  		sx,
		  		sy,
		  		cx,
		  		cy,
		  		pos,
		  		cells.id
		  	FROM	
		  		cells
		  	INNER JOIN
		  		entities 
		  		ON entities.cell_id=cells.id	  	
		  		AND entities.id='".$this->id."'
		  	LIMIT 1;
		  	");
		  	if (mysql_num_rows($res)>0)
		  	{
		  		$arr=mysql_Fetch_row($res);
		  		$this->sx=$arr[0];
		  		$this->sy=$arr[1];
		  		$this->cx=$arr[2];
		  		$this->cy=$arr[3];
		  		$this->pos=$arr[4];
		  		$this->cellId=$arr[5];
		  		$this->coordsLoaded=true;
		  	}
		  }
	  }	  
	   
	  protected function formatedCoords()
	  {
	  	$this->loadCoords();
	  	return $this->sx."/".$this->sy." : ".$this->cx."/".$this->cy." : ".$this->pos;
	  }
	   
	   
	  public function coordsArray()
	  {
	  	$this->loadCoords();
	  	return array($this->sx,$this->sy,$this->cx,$this->cy,$this->pos);
	  }	   
	   
	  public function distance(Entity $target)
	  {
	  	return $this->distanceByCoords($target->sx(), $target->sy(), $target->cx(), $target->cy(), $target->pos());
	  }	
	  
    /**
    * Calculates the distance to another cell specified by its coordinates
    *
    * @param int $sx Sector X
    * @param int $sy Sector Y
    * @param int $cx Cell X
    * @param int $cy Cell Y
    * @param int $p Entity position
    */
	  public function distanceByCoords($sx, $sy, $cx, $cy, $p)
	  {
	  	$cfg = Config::getInstance();
      
      // Länge vom Solsys in AE
			$cellLengthAE = $cfg->value('cell_length');
      // Max. Planeten im Solsys
			$maxNumEntitiesPerSystem = $cfg->param2('num_planets');
      
      // Number of cells per sector
      $cellsPerSectorX = $cfg->param1('num_of_cells');
      $cellsPerSectorY = $cfg->param2('num_of_cells');

	  	// Absolute coordinates of current cell
			$cAbsX = (($this->sx()-1) * $cellsPerSectorX) + $this->cx();
			$cAbsY = (($this->sy()-1) * $cellsPerSectorY) + $this->cy();
			
      // Absolute coordinates of target cell
			$tAbsX = (($sx-1) * $cellsPerSectorX) + $cx;
			$tAbsY = (($sy-1) * $cellsPerSectorY) + $cy;						

      // Entity position in cell (Planet position in sol system)
	  	$p1 = $this->pos();
	  	$p2 = $p;
	  	
      // Get difference on x axis in absolute coordinates
			$dx = abs($tAbsX - $cAbsX);
      // Get difference on y axis in absolute coordinates
			$dy = abs($tAbsY - $cAbsY); 
      // Use Pythagorean theorem to get the absolute length
			$hypotenuse = sqrt(pow($dx,2) + pow($dy,2));
      // Multiply with AE units per cell
			$cellDistanceAE = $hypotenuse * $cellLengthAE;		
			
      // The distance between the innermost and outermost possible entity in the system
      // The outermost entity lies at half distance to the cell edge
      $distanceInnerOuterEntity = $cellLengthAE/4/$maxNumEntitiesPerSystem;
      
			// Planetendistanz wenn sie im selben Solsys sind
			if ($cellDistanceAE == 0)
			{
				$finalDistance = abs($p2 - $p1) * $distanceInnerOuterEntity;									
			}
			// Planetendistanz wenn sie nicht im selben Solsys sind
			else
			{
				$finalDistance = $cellDistanceAE + $cellLengthAE - ($distanceInnerOuterEntity * ($p1 + $p2));
			}
			return round($finalDistance);		  	
	  }		       
	     
	  /**
	  * Creates an instance of a child class
	  * using the factory design pattern
	  */ 
		static public function createFactory($type,$id=0)
		{
			switch ($type)
			{
				case 's':
					return new Star($id);
				case 'p':
					return Planet::getById($id);
				case 'a':
					return new AsteroidField($id);
				case 'n':
					return new Nebula($id);
				case 'w':
					return new Wormhole($id);
				case 'e':
					return new EmptySpace($id);
				case 'm':
					return new Market($id);	
				case 'u':
					return new UnExplored($id);
				case 'x':
					return new Allianz($id);			
				default:
					return new UnknownEntity($id);
			}		
			return false;	
		}	
		
	  /**
	  * Creates an instance of a child class
	  * using the factory design pattern
	  */ 
		static public function createFactoryById($id)
		{
			$res=dbquery("
			SELECT
				code
			FROM
				entities
			WHERE
				id=".intval($id)."
			");
			if (mysql_num_rows($res)>0)
			{
				$arr = mysql_fetch_array($res);
				$type = $arr[0];
				
				switch ($type)
				{
					case 's':
						return new Star($id);
					case 'p':
						return Planet::getById($id);
					case 'a':
						return new AsteroidField($id);
					case 'n':
						return new Nebula($id);
					case 'w':
						return new Wormhole($id);
					case 'e':
						return new EmptySpace($id);
					case 'm':
						return new Market($id);	
					case 'x':
						return new Allianz($id);	
					default:
						return new UnknownEntity($id);
				}			
			}
            
			return null;
			//die ("Ungültige ID");
		}
		
	  /**
	  * Creates an instance of a child class
	  * using the factory design pattern
	  */ 
		static public function createFactoryByCoords($c1,$c2,$c3,$c4,$c5)
		{
			$res=dbquery("
			SELECT
				entities.id,
				code
			FROM
				entities
			INNER JOIN	
				cells on entities.cell_id=cells.id
			AND sx=".intval($c1)."
			AND sy=".intval($c2)."
			AND cx=".intval($c3)."
			AND cy=".intval($c4)."
			AND pos=".intval($c5)."
			LIMIT 1;
			");
			if (mysql_num_rows($res)>0)
			{
				$arr = mysql_fetch_array($res);
				$type = $arr[1];
				$id = $arr[0];
				
				switch ($type)
				{
					case 's':
						return new Star($id);
					case 'p':
						return Planet::getById($id);
					case 'a':
						return new AsteroidField($id);
					case 'n':
						return new Nebula($id);
					case 'w':
						return new Wormhole($id);
					case 'e':
						return new EmptySpace($id);
					case 'm':
						return new Market($id);
					case 'x':
						return new Allianz($id);
					default:
						return new UnknownEntity($id);
				}			
			}
			else
			{
				return false;
			}
		}	
		
  	  /**
	  * Creates an instance of a child class
	  * 
	  */ 
		static public function createFactoryUnkownCell($cell=0)
		{
			$res=dbquery("
			SELECT
				entities.id
			FROM
				entities
			WHERE 	
				entities.cell_id='".intval($cell)."'
				AND entities.pos='0'
			LIMIT 1;");
			
			if (mysql_num_rows($res)>0)
			{
				$arr = mysql_fetch_array($res);
				$id = $arr[0];
				return new UnknownEntity($id);
			}
				
			return false;	
		}		
		
		static public $entityColors = array(
		's'=>'#ff0',
		'p'=>'#0f0',
		'a'=>'#ccc',
		'n'=>'#FF00FF',
		'w'=>'#8000FF',
		'e'=>'#55f',
		'x'=>'#fff',
		'm'=>'#fff'
		);

		public function detailLink()
		{
			return "<a href=\"?page=entity&amp;id=".$this->id."\">".$this->__toString()."</a>";
		}

	}

?>