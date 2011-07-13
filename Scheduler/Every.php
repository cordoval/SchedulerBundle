<?php

namespace Palleas\SchedulerBundle\Scheduler;

/**
* 
*/
class Every extends Frequency
{
    const MINUTES = 'min';

    const HOURS  = 'h';

    const DAYS = 'd';

    const MONTHS = 'm';

    const YEARS = 'y';

    /**
     * @var integer 
     * Number of minutes/hours/days/months/years
     */
    private $count;

    /**
     * @var string
     * 
     */
    private $unit;

    public function setValue($value)
    {
        $extract = array();
        if (!preg_match('#^(?<count>\d+)(?<unit>min|h|d|m|Y)$#i', $value, $extract)) {
            throw new \InvalidArgumentException(sprintf('%s is not a valid value if you are using the "@Every" annotation', $value));
        }

        parent::setValue($value);

        $this->count = $extract['count'];
        $this->unit = $extract['unit'];
    }

    public function getUnit()
    {
        return $this->unit;
    }

    public function getCount()
    {
        return $this->count;
    }

    public static function getUnits()
    {
        return array(
            self::MINUTES,
            self::HOURS,
            self::DAYS,
            self::MONTHS,
            self::YEARS
        );
    }
}