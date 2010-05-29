<?php
/**
 */
class PluginsfAuditableLogItemTable extends Doctrine_Table
{
	public function fetchForIssuer($issuerID, $groupByDate = true)
	{
		$logItems = Doctrine_Query::create()
			->select("DATE_FORMAT(li.created_at, '%Y-%m-%d') AS date, DATE_FORMAT(li.created_at, '%H:%i') AS time, li.text")
			->from("sfAuditableLogItem li")
			->where("li.issuer_id = ? OR li.issuer_id = 0", $issuerID)
			->orderBy("li.created_at DESC")->execute();
		if ($groupByDate)
		{
			foreach($logItems as $logItem)
			{
				if (!isset($byDate[$logItem->date])) { $byDate[$logItem->date] = array(); }
				$byDate[$logItem->date][] = $logItem;
			}
			return $byDate;
		} else {
			return $logItems;
		}
	}
}