<?php
class sfAuditable extends Doctrine_Template 
{
	  protected $_options = array(
		"create" => array(
			"track"   => true,
			"message" => "User created %OBJECT_WITH_LINK%.",
		),
		"update" => array(
			"track"   => true,
			"message" => "User updated %OBJECT_WITH_LINK%.",
		),
		"delete" => array(
			"track"   => true,
			"message" => "User deleted %OBJECT% %NAME%.",
		),
	);
	
  public function setTableDefinition()
  {
		$this->addListener(new sfAuditableListener($this->_options));
  }
}