<?php
namespace SiteMaster\Core\Registry\Sites;

use DB\RecordList;

class AutoQueue extends RecordList
{
    public function __construct(array $options = array())
    {
        $this->options = $options + $this->options;
        
        $options['array'] = self::getBySQL(array(
            'sql'         => $this->getSQL(),
            'returnArray' => true
        ));

        parent::__construct($options);
    }

    public function getDefaultOptions()
    {
        $options = array();
        $options['itemClass'] = '\SiteMaster\Core\Registry\Site';
        $options['listClass'] = __CLASS__;

        return $options;
    }
    
    public function getLimit()
    {
        if (isset($this->options['queue_limit'])) {
            return 'LIMIT ' . (int)$this->options['queue_limit'];
        }
        
        return 'LIMIT 5';
    }
    
    public function getWhere()
    {
        if (isset($this->options['only_not_scanned'])) {
            return "WHERE scans.id IS NULL";
        }
        
        return 'WHERE scans.status NOT IN ("RUNNING", "QUEUED", "CREATED")';
    }

    public function getSQL()
    {
        //Build the list
        $sql = "SELECT sites.id as id, max(scans.date_created) as date_created 
                FROM sites
                LEFT JOIN scans ON (scans.sites_id = sites.id)
                " . $this->getWhere() . "
                GROUP BY sites.id
                ORDER BY end_time ASC
                " . $this->getLimit();

        return $sql;
    }
}
