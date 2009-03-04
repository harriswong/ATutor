<?php
/**
 * Members class for Social Networking
 * TODO: Extend it for the entire ATutor.
 */
class Member {
	var $id;		//member id
	var $profile;	//profile details

	function Member($id){
		$this->id = intval($id);
	}


	/**
	 * Add a new job position
	 * @param	string		Name of the company, in full.
	 * @param	string		Title of this position
	 * @param	int			Started date for this position, in the format of yyyymm
	 * @param	int			Position ended on this date, in the format of yyyymm, or 'NOW'. 
	 *						'NOW' means it is still on going.
	 * @param	string		Description of what the position was about
	 */
	function addPosition($company, $title, $from, $to, $description){
		global $addslashes, $db;
		$member_id	= $this->id;
		$company	= $addslashes($company);
		$title		= $addslashes($title);
		$from		= intval($from);
		$to			= intval($to);
		$description = $addslashes($description);

		$sql = 'INSERT INTO '.TABLE_PREFIX."member_position (member_id, company, title, from, to, description) VALUES ($member_id, '$company', '$title', $from, $to, '$description')";
		mysql_query($sql, $db);
	}

	
	/**
	 * Add a new education
	 * TODO: University names can be generated from another table.
	 * 
	 * @param	string		Name of the University, in full. Might need to pull from another table.
	 * @param	int			This education begins on this date, yyyymm
	 * @param	int			This education ends on this date, yyyymm, or can be 'NOW'
	 * @param	string		The full name of the country this University is in, ie. Canada
	 * @param	string		The full name of the province this University is in, ie. Ontario
	 * @param	string		The name of the degree, ie. B.Sc.
	 * @param	string		The field of study, ie. Computer Science
	 * @param	string		The description of this education.
	 */
	function addEducation($university, $from, $to, $country, 
						  $province, $degree, $field, $description){ 
		global $addslashes, $db;
		$member_id			= $this->id;
		$university			= $addslashes($university);
		$from				= intval($from);
		$to					= intval($to);
		$country			= $addslashes($country);
		$province			= $addslashes($province);
		$degree				= $addslashes($degree);
		$field				= $addslsahes($field);
		$description		= $addslashes($description);
		
		$sql = 'INSERT INTO '.TABLE_PREFIX."member_education (member_id, university, from, to, country, province, degree, field, description) VALUES ($member_id, '$university', $from, $to, '$country', '$province', '$degree', '$field', '$description')";
		mysql_query($sql, $db);
	}


	/**
	 * Add a new website associated with this member, can be blog, work, portfolio, etc.
	 * @param	string		Unique URL of the website
	 * @param	string		A name for the website.
	 */
	function addWebsite($url, $site_name){ 
		global $addslashes, $db;
		$member_id	= $this->id;
		$url		= urlencode($url);
		$site_name	= $addslashes($site_name);

		$sql = 'INSERT INTO '. TABLE_PREFIX ."member_websites (member_id, url, site_name) VALUES ($member_id, '$url', '$site_name')";
		mysql_query($sql, $db);		
	}


	/** 
	 * Add additional information, including interest, awards, associations.
	 * @param	string		CSV format of interests, ie. camping, biking, etc
	 * @param	string		CSV format of associations, clubs, groups, ie. IEEE
	 * @param	string		CSV format of awards, honors
	 */
	function addAdditionalInformation($interests, $associations, $awards){ 
		global $addslashes, $db;
		$member_id		= $this->id;
		$interests		= $addslashes($interests);
		$associations	= $addslashes($associations);
		$awards			= $addslashes($awards);
		$sql = 'INSERT INTO ' . TABLE_PREFIX . "member_additional_information (member_id, interests,  associations, awards) VALUES ($member_id, '$interests', '$associations', '$awards')";
		mysql_query($sql, $db);
	}


	/**
	 * Update a new job position
	 * @param	int			The id of this entry
	 * @param	string		Name of the company, in full.
	 * @param	string		Tht title of this position
	 * @param	int			Started date for this position, in the format of yyyymm
	 * @param	int			Position ended on this date, in the format of yyyymm, or 'NOW'. 
	 *						'NOW' means it is still on going.
	 * @param	string		Description of the position
	 */
	function updatePosition($id, $company, $title, $from, $to, $description){ 
		global $addslashes, $db;
		$id			 = intval($id);
		$company	 = $addslashes($company);
		$title		 = $addslashes($title);
		$form		 = intval($form);
		$to			 = intval($to);
		$description = $addslashes($description);

		$sql = 'UPDATE '.TABLE_PREFIX."member_position SET company='$company', title='$title', `from`=$from, `to`=$to, description='$description' WHERE id=$id";
		mysql_query($sql, $db);
	}


	/**
	 * Update a new education
	 * TODO: University names can be generated from another table.
	 * 
	 * @param	int			ID of this entry
	 * @param	string		Name of the University, in full. Might need to pull from another table.
	 * @param	int			This education begins on this date, yyyymm
	 * @param	int			This education ends on this date, yyyymm, or can be 'NOW'
	 * @param	string		The full name of the country this University is in, ie. Canada
	 * @param	string		The full name of the province this University is in, ie. Ontario
	 * @param	string		The name of the degree, ie. B.Sc.
	 * @param	string		The field of study, ie. Computer Science
	 * @param	string		The description of this education.
	 */
	function updateEducation($id, $university, $from, $to, $country, $province, $degree, $field, $description){ 
		global $addslashes, $db;
		$id					= intval($id);
		$university			= $addslashes($university);
		$from				= intval($from);
		$to					= intval($to);
		$country			= $addslashes($country);
		$province			= $addslashes($province);
		$degree				= $addslashes($degree);
		$field				= $addslashes($field);
		$description		= $addslashes($description);

		$sql = 'UPDATE '.TABLE_PREFIX."member_education SET university='$university', `from`=$from, `to`=$to, country='$country', province='$province', degree='$degree', field='$field', description='$description' WHERE id=$id";
		mysql_query($sql, $db);		
	}


	/**
	 * Updates a new website associated with this member, can be blog, work, portfolio, etc.
	 * @param	int			ID of this entry
	 * @param	string		Unique URL of the website
	 * @param	string		A name for the website.
	 */
	function updateWebsite($id, $url, $site_name){ 
		global $addslashes, $db;
		$id			= intval($id);
		$url		= $addslashes($url);
		$site_name	= $addslashes($site_name);

		$sql = 'UPDATE '.TABLE_PREFIX."member_websites SET url='$url', site_name='$site_name' WHERE id=$id";
		mysql_query($sql, $db);
	}


	/** 
	 * Update additional information, including interest, awards, associations.
	 * @param	string		CSV format of interests, ie. camping, biking, etc
	 * @param	string		CSV format of associations, clubs, groups, ie. IEEE
	 * @param	string		CSV format of awards, honors
	 */
	function updateAdditionalInformation($interests, $associations, $awards){ 
		global $addslashes, $db;
		$interests = $addslashes($interests);
		$associations = $addslashes($associations);
		$awards = $addslashes($awards);

		$sql = 'UPDATE '.TABLE_PREFIX."member_additional_information SET interests='$interests', associations='$associations', awards='$awards' WHERE member_id=".$_SESSION['member_id'];
		mysql_query($sql, $db);
	}


	/**
	 * Get member info
	 */
	function getDetails(){
		$sql =	'SELECT core.*, T.interests, T.associations, T.awards FROM '.
				'(SELECT * FROM '.TABLE_PREFIX.'members WHERE member_id='.$this->id.') AS core '.
				'LEFT JOIN '.
				TABLE_PREFIX.'member_additional_information T ON core.member_id=T.member_id';
		$result = mysql_query($sql);
		if ($result){
			$row = mysql_fetch_assoc($result);
			$this->profile = $row;
		}
		return $this->profile;
	}
	

	/**
	 * Get position info
	 * @return	the array of job/position
	 */
	function getPosition(){
		global $db;
		$position = array();

		$sql = 'SELECT * FROM '.TABLE_PREFIX.'member_position WHERE member_id='.$this->id;
		$result = mysql_query($sql, $db);
		if ($result){
			while($row = mysql_fetch_assoc($result)){
				$position[] = $row;
			}
		}
		return $position;
	}


	/**
	 * Get education info
	 * can be 1+ 
	 * @return	the array of education details
	 */
	function getEducation(){
		global $db;
		$education = array();

		$sql = 'SELECT * FROM '.TABLE_PREFIX.'member_education WHERE member_id='.$this->id;
		$result = mysql_query($sql, $db);
		if ($result){
			while($row = mysql_fetch_assoc($result)){
				$education[] = $row;
			}
		}
		return $education;
	}


	/** 
	 * Get websites. can be 1+
	 * @return	the array of website details.
	 */
	function getWebsites(){
		global $db;
		$websites = array();

		$sql = 'SELECT * FROM '.TABLE_PREFIX.'member_websites WHERE member_id='.$this->id;
		$result = mysql_query($sql, $db);
		if ($result){
			while($row = mysql_fetch_assoc($result)){
				$websites[] = $row;
			}
		}
		return $websites;
	}


	/**
	 * Get the ID of this member
	 */
	function getID(){
		return $this->id;
	}
}
?>