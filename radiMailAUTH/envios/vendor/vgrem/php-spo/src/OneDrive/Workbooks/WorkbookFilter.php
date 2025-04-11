<?php

/**
 * Modified: 2020-05-26T22:07:25+00:00 
 */
namespace Office365\OneDrive\Workbooks;

use Office365\Entity;
use Office365\OneDrive\Workbooks;

class WorkbookFilter extends Entity
{
    /**
     * @return WorkbookFilterCriteria
     */
    public function getCriteria()
    {
        if (!$this->isPropertyAvailable("Criteria")) {
            return null;
        }
        return $this->getProperty("Criteria");
    }
    /**
     * @var WorkbookFilterCriteria
     */
    public function setCriteria($value)
    {
        $this->setProperty("Criteria", $value, true);
    }
}