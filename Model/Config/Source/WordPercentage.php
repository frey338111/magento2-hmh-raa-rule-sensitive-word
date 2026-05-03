<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class WordPercentage implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        $options = [];

        for ($percentage = 10; $percentage <= 100; $percentage += 10) {
            $options[] = [
                'value' => (string) $percentage,
                'label' => __('%1%', $percentage),
            ];
        }

        return $options;
    }
}
