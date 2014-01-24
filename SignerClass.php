<?php
class Signer {
	private $dataholder=[entry_no=>NULL,
		salutation=>NULL,
		first_name=>NULL,
		last_name=>NULL,
		suffix=>NULL,
		congregation=>NULL,  // build from Wufoo, merging branch and denom as needed
		street_address=>NULL, // concatenate lines from Wufoo if necessary
		city=>NULL,
		state=>NULL,
		zip=>NULL,
		country=>NULL,
		website=>NULL,
		religion=>NULL,
		denomination=>NULL,  // build from Wufoo, merging branch and denom as needed
	];
	
	public function entry_no() {return $this->dataholder['entry_no'];}
	public function salutation() {return $this->dataholder['salutation'];}
	public function first_name() {return $this->dataholder['first_name'];}
	public function last_name() {return $this->dataholder['last_name'];}
	public function suffix() {return $this->dataholder['suffix'];}
	public function congregation() {return $this->dataholder['congregation'];}
	public function street_address() {return $this->dataholder['street_address'];}
	public function city() {return $this->dataholder['city'];}
	public function state() {return $this->dataholder['state'];}
	public function zip() {return $this->dataholder['zip'];}
	public function country() {return $this->dataholder['country'];}
	public function website() {return $this->dataholder['website'];}
	public function religion() {return $this->dataholder['religion'];}
	public function denomination() {return $this->dataholder['denomination'];}

/*

	TODO: Do for countries what we do for states. 
	Do for states and provinces in other major nations what we do for states now.
	Convert htmlize not to insert breaks, etc. 
		Put everything in <span> or <div> and let CSS handle that.
		
		Upgrade Wufoo to hold congregation!
		
		Upgrade Wufoo list of denoms, including Orthodox denoms, Islamic denominations (including maybe major affiliations from Hartford study of mosques?), etc.
		
		Be sure we have flags necessary to tag htmlized boxes by religion. Add a "class=religion-x" or "class=x" and perhaps include "id=$entry_no" to allow jQuery to pull out individual boxes

*/

	static $states=['AL','AK','AZ','AR','CA','CO','CT','DE','DC','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY','AS','FM','GU','MH','MP','PW','PR','VI','AA'];

static $stabbrevs=['ALABAMA'=>'AL',
					'ALA'=>'AL',
					'ALASKA'=>'AK',
					'ALAS'=>'AK',
					'ARIZONA'=>'AZ',
					'ARIZ'=>'AZ',
					'ARKANSAS'=>'AR',
					'ARK'=>'AR',
					'CALIFORNIA'=>'CA',
					'CF'=>'CA',
					'CAL'=>'CA',
					'CALI'=>'CA',
					'CALIF'=>'CA',
					'COLORADO'=>'CO',
					'CL'=>'CO',
					'COL'=>'CO',
					'COLO'=>'CO',
					'CONNECTICUT'=>'CT',
					'CONN'=>'CT',
					'DELAWARE'=>'DE',
					'DL'=>'DE',
					'DEL'=>'DE',
					'DISTRICTOFCOLUMBIA'=>'DC',
					'DISTOFCOLUMBIA'=>'DC',
					'DISTCOLUMBIA'=>'DC',
					'FLORIDA'=>'FL',
					'FLA'=>'FL',
					'FLOR'=>'FL',
					'GEORGIA'=>'GA',
					'HAWAII'=>'HI',
					'HA'=>'HI',
					'IDAHO'=>'ID',
					'IDA'=>'ID',
					'ILLINOIS'=>'IL',
					'ILL'=>'IL',
					'ILLS'=>'IL',
					'INDIANA'=>'IN',
					'IND'=>'IN',
					'IOWA'=>'IA',
					'KANSAS'=>'KS',
					'KA'=>'KS',
					'KANS'=>'KS',
					'KAN'=>'KS',
					'KENTUCKY'=>'KY',
					'KEN'=>'KY',
					'KENT'=>'KY',
					'LOUISIANA'=>'LA',
					'MAINE'=>'ME',
					'MARYLAND'=>'MD',
					'MASSACHUSETTS'=>'MA',
					'MASS'=>'MA',
					'MICHIGAN'=>'MI',
					'MC'=>'MI',
					'MICH'=>'MI',
					'MINNESOTA'=>'MN',
					'MINN'=>'MN',
					'MISSISSIPPI'=>'MS',
					'MISS'=>'MS',
					'MISSOURI'=>'MO',
					'MONTANA'=>'MT',
					'MONT'=>'MT',
					'NEBRASKA'=>'NE',
					'NB'=>'NE',
					'NEBR'=>'NE',
					'NEB'=>'NE',
					'NEVADA'=>'NV',
					'NEV'=>'NV',
					'NEWHAMPSHIRE'=>'NH',
					'NHAMPSHIRE'=>'NH',
					'NEWHAMP'=>'NH',
					'NHAMP'=>'NH',
					'NEWJERSEY'=>'NJ',
					'NJERSEY'=>'NJ',
					'NJERS'=>'NJ',
					'NEWMEXICO'=>'NM',
					'NEWMEX'=>'NM',
					'NMEX'=>'NM',
					'NEWM'=>'NM',
					'NEWYORK'=>'NY',
					'NYORK'=>'NY',
					'NORTHCAROLINA'=>'NC',
					'NOCAROLINA'=>'NC',
					'NCAROLINA'=>'NC',
					'NOCAR'=>'NC',
					'NCAR'=>'NC',
					'NORTHDAKOTA'=>'ND',
					'NODAKOTA'=>'ND',
					'NDAKOTA'=>'ND',
					'NODAK'=>'ND',
					'OHIO'=>'OH',
					'O'=>'OH',
					'OKLAHOMA'=>'OK',
					'OKLA'=>'OK',
					'OKL'=>'OK',
					'OREGON'=>'OR',
					'OREG'=>'OR',
					'ORE'=>'OR',
					'PENNSYLVANIA'=>'PA',
					'PENN'=>'PA',
					'PENNA'=>'PA',
					'RHODEISLAND'=>'RI',
					'RHODEISL'=>'RI',
					'RISLAND'=>'RI',
					'RISL'=>'RI',
					'SOUTHCAROLINA'=>'SC',
					'SOCAROLINA'=>'SC',
					'SCAROLINA'=>'SC',
					'SOUTHCAR'=>'SC',
					'SOCAR'=>'SC',
					'SCAR'=>'SC',
					'SOUTHDAKOTA'=>'SD',
					'SODAKOTA'=>'SD',
					'SDAKOTA'=>'SD',
					'SODAK'=>'SD',
					'SDAK'=>'SD',
					'TENNESSEE'=>'TN',
					'TENN'=>'TN',
					'TEXAS'=>'TX',
					'TEX'=>'TX',
					'UTAH'=>'UT',
					'VERMONT'=>'VT',
					'VIRGINIA'=>'VA',
					'VIRG'=>'VA',
					'WASHINGTON'=>'WA',
					'WN'=>'WA',
					'WASH'=>'WA',
					'WESTVIRGINIA'=>'WV',
					'WVIRGINIA'=>'WV',
					'WESTVIRG'=>'WV',
					'WVIRG'=>'WV',
					'WVA'=>'WV',
					'WISCONSIN'=>'WI',
					'WISC'=>'WI',
					'WIS'=>'WI',
					'WS'=>'WI',
					'WYOMING'=>'WY',
					'WYOM'=>'WY',
					'WYO'=>'WY',
					'AMERICANSAMOA'=>'AS',
					'AMSAMOA'=>'AS',
					'GUAM'=>'GU',
					'NORTHERNMARIANAISLANDS'=>'MP',
					'NORTHERNMARIANASISLANDS'=>'MP',
					'NORTHERNMARIANASISL'=>'MP',
					'NOMARIANASISLANDS'=>'MP',
					'NOMARIANAISLANDS'=>'MP',
					'NOMARIANASISL'=>'MP',
					'NORTHERNMARIANAS'=>'MP',
					'NMARIANASISLANDS'=>'MP',
					'NMARIANAISLANDS'=>'MP',
					'NMARIANAISL'=>'MP',
					'NOMARIANAS'=>'MP',
					'NMARIANAS'=>'MP',
					'MNP'=>'MP',
					'CM'=>'MP',
					'PUERTORICO'=>'PR',
					'PRI'=>'PR',
					'VIRGINISLANDS'=>'VI',
					'USVIRGINISLANDS'=>'VI',
					'UNITEDSTATESVIRGINISLANDS'=>'VI',
					'VIR'=>'VI',
					'USVI'=>'VI',
					'USMINOROUTLYINGISLANDS'=>'UM',
					'UMI'=>'UM',
					'FEDERATEDSTATESOFMICRONESIA'=>'FM',
					'FEDSTATESOFMICRONESIA'=>'FM',
					'FEDSTATESMICRONESIA'=>'FM',
					'MICRONESIA'=>'FM',
					'FSM'=>'FM',
					'MARSHALLISLANDS'=>'MH',
					'MARSHALLISL'=>'MH',
					'MHL'=>'MH',
					'PALAU'=>'PW',
					'PLW'=>'PW',
					'USARMEDFORCES'=>'AA',
					'AE'=>'AA',
					'AP'=>'AA',
];

	function __construct(array $entry) {
		$this->dataholder['entry_no']=$entry["EntryId"];
		$this->dataholder['salutation']=$entry["Field17"]; // Validate in Wufoo
		$this->dataholder['first_name']=$entry["Field18"]; // Ditto
		$this->dataholder['last_name']=$entry["Field19"];  // Ditto
		$this->dataholder['suffix']=$entry["Field20"];     // Ditto
		$this->dataholder['street_address']=$entry["Field3"];  // Not displayed on the site, ignore
		if($entry["Field4"]){ $this->street_address.="\n".$entry["Field4"];print $this->street_address;}
		$this->dataholder['city']=$entry["Field5"];        // Validate in Wufoo
		if(NULL!==($teststate=self::stdstate($entry["Field6"]))){ // Clean data
			$this->dataholder['state']=$teststate;
		} else {
			$this->dataholder['state']=$entry["Field6"];			
		}
		$this->dataholder['zip']=$entry["Field7"];
		$this->dataholder['country']=$entry["Field8"];
		$this->dataholder['website']=$entry["Field23"];
		$this->dataholder['congregation']=$entry["FieldTK"];
		$this->dataholder['religion']=$entry["Field24"];
		if($entry["Field26"]) { $this->dataholder['denomination']=$entry["Field26"];}
		elseif($entry["Field27"]) { $this->dataholder['denomination']=$entry["Field27"];}
		elseif($entry["Field28"]) { $this->dataholder['denomination']=$entry["Field28"];}
		else{ $this->dataholder['denomination']=$entry["Field25"];}		
	}

	static function stdstate($s) {
		if(array_search($s,self::$states)){ // Clean data
			return $s;
		} //That was easy!
		$s=strtoupper($s);
		str_replace(['.',' '],NULL,$s);
		if(array_search($s,self::$states)){ // Almost clean data.
			return $s;
		} 
		return self::$stabbrevs[$s];  // Returns NULL if no match. Test using ===
	}

	function fullname() {
		$output;
		if(strlen($this->salutation())>0) {$output .= $this->salutation()." ";}
		$output .= $this->first_name()." ".$this->last_name();
		if(strlen($this->suffix())>0) {$output .= ", ".$this->suffix();}
		return $output;
	}

	function htmlize() {
		$output='<div class="signer religion-'.strtolower($this->religion()).' id="'.strtolower($this->entry_no()).'"><span class="name">';
		$output.=$this->fullname();
		$output .= '</span>';
		if($this->congregation()){
			 $output .= '<br /><span class="church">';
			 if($this->website()) {$output.="<a href=\"".$this->website()."\">";}
			 $output .= $this->congregation();
			 if($this->website()) {$output.='</a>';}
			 $output .= '</span>';
		}
		if($this->city() || $this->state() || $this->country()){
			 $output .= '<br /><span class="address">';
			 if($this->city()) {
				 $output.=$this->city();
				 if($this->state()) {$output .= ', ';}
			 }
			 if($this->state()) {$output.=$this->state()." ";}
			 if($this->country()) {
				 if($this->city() || $this->state()) {$output.='<br />';}
				 $output.=$this->country();
			 }
			 $output .= "</span>";
		}
		$output .= '</div>';
		return $output;
	}

	public function __toString() {
		$output=$this->fullname().NULL;
		if($this->congregation()){
			 if($this->website()) {$output.=$this->website().NULL;}
			 $output .= $this->congregation().NULL;
		}
		if($this->city() || $this->state() || $this->country()){
			 if($this->city()) {
				 $output.=$this->city();
				 if($this->state()) {$output .= ', ';}
				 else {$output.=NULL;}
			 }
			 if($this->state()) {$output.=$this->state().NULL;}
			 if($this->country()) { $output.=$this->country().NULL; }
		}
		if($this->religion()){ $output .= $this->religion().NULL;}

		if($this->denomination()){ $output .= $this->denomination().NULL;}
		return $output;
	}

 /*
	Since this is a private static function, we're making some assumptions about data.
	First, assume that $lhs and $rhs have been validated by a constructor.
	If the names don't match something in $states, we'll just treat it as a string, 
	but put it after anything that *is* on the list.
	Empty values go after all full values.
	We don't have access to $this->country because we're static, sorted by nation first!
	Future revision may require bringing in country along with state.
 */
	static function cmpstate($lhs,$rhs) {
		if ($lhs==$rhs) {return 0;}
		if ($lhs=="") {return 1;}
		if ($rhs=="") {return +1;}
		$a = array_search($lhs,self::$states);
		$b = array_search($rhs,self::$states);
		if ($a!==FALSE && $b !== FALSE) {
			return ($a>$b) ? +1 : -1;
		}
		if ($a!==FALSE) {return -1;}
		if ($b!==FALSE) {return 1;}
		return (strtolower($lhs) > $strtolower($lhs)) ? +1 : -1;
	}

	static function cmp($lhs,$rhs){
		$a = strtolower($lhs->country());
		$b = strtolower($rhs->country());
		if ($a != $b) {
			return ($a > $b) ? +1 : -1;
		} else {
			$c=Signer::cmpstate($lhs->state(),$rhs->state());
			if ($c!=0) {
				return $c;
			} else {
				$a = strtolower($lhs->last_name());
				$b = strtolower($rhs->last_name());
				if ($a != $b) {
					return ($a > $b) ? +1 : -1;
				} else {
					$a = strtolower($lhs->first_name());
					$b = strtolower($rhs->first_name());
					if ($a != $b) {
						return ($a > $b) ? +1 : -1;
					}
					return 0;
			}	}
			return ($a > $b) ? +1 : -1;
		}
	}
	
	function search(string $s){
		return true; // string $s matches this object!
	}
}
?>