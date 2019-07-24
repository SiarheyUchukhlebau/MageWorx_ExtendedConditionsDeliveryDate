<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\ExtendedConditionsDeliveryDate\Plugin;


use DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use MageWorx\DeliveryDate\Api\DeliveryOptionInterface;

class FridaySaturdayRelation
{
    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * FridaySaturdayRelation constructor.
     *
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        TimezoneInterface $timezone
    ) {
        $this->timezone = $timezone;
    }

    /**
     * @param DeliveryOptionInterface $subject
     * @param bool $result
     * @param \DateTime $futureDate
     * @return bool
     * @throws \Exception
     */
    public function afterIsDayHoliday(DeliveryOptionInterface $subject, $result, $futureDate)
    {
        $today = $this->getCurrentDateTime();
        if ($futureDate->format('l') === 'Saturday' &&
            $today->diff($futureDate)->days < 2 &&
            $today->format('l') === 'Friday' &&
            $today->format('G') >= 21
        ) {
            $result = true;
        }
        
        return $result;
    }

    /**
     * @return DateTime
     * @throws \Exception
     */
    private function getCurrentDateTime()
    {
        $currentDayTime = new DateTime();
        $storeTimeZone  = new \DateTimeZone($this->timezone->getConfigTimezone());
        $currentDayTime->setTimezone($storeTimeZone);

        return $currentDayTime;
    }
}