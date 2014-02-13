<?php
namespace SiteMaster\Core\Auditor\Site\Page\MetricGrades;

use DB\RecordList;
use SiteMaster\Core\InvalidArgumentException;

class AllForPage extends RecordList
{
    public function __construct(array $options = array())
    {
        $this->options = $options + $this->options;

        if (!isset($options['scanned_page_id'])) {
            throw new InvalidArgumentException('A scanned_page_id must be set', 500);
        }

        $options['array'] = self::getBySQL(array(
            'sql'         => $this->getSQL(),
            'returnArray' => true
        ));

        parent::__construct($options);
    }

    public function getDefaultOptions()
    {
        $options = array();
        $options['itemClass'] = '\SiteMaster\Core\Auditor\Site\Page\MetricGrade';
        $options['listClass'] = __CLASS__;

        return $options;
    }

    public function getWhere()
    {
        return 'WHERE scanned_page_id = ' .(int)$this->options['scanned_page_id'];
    }

    public function getSQL()
    {
        //Build the list
        $sql = "SELECT page_metric_grades.id
                FROM page_metric_grades
                " . $this->getWhere() . "
                ORDER BY page_metric_grades.weight ASC";

        return $sql;
    }
}
