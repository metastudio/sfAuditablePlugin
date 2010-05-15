<?php
class sfAuditableListener extends Doctrine_Record_Listener 
{
	protected $_options;
	
  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }

	public function postInsert(Doctrine_Event $event)
	{
		if ($this->_options["create"]["track"])
		{
			$this->logEvent($event->getInvoker(), $this->_options["create"]);
		}
	}
	
	public function preUpdate(Doctrine_Event $event)
	{
		if ($this->_options["update"]["track"])
		{
			$this->logEvent($event->getInvoker(), $this->_options["update"]);
		}
	}
	
	public function preDelete(Doctrine_Event $event)
	{
		if ($this->_options["delete"]["track"])
		{
			$this->logEvent($event->getInvoker(), $this->_options["delete"]);
		}
	}
	
	protected function logEvent($object, $options)
	{
		// If there is no context, then the event is triggered by the CLI task maybe
		if (!($context = sfContext::getInstance()))
		{
			return;
		}
		
		$user = $context->getUser();
		$logItem = new sfAuditableLogItem();
		$logItem->setIssuer($user->getGuardUser());
		if ($object != null && is_callable(array($object, "getId")));
		{
			$logItem->setObjectId($object->getId());
			$logItem->setObjectClass(get_class($object));
		}
		$logItem->save();
	}
}