<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config\Source;

use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config\ConfigProvider;
use Magento\Framework\Data\OptionSourceInterface;

class FilterMethod implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            [
                'value' => ConfigProvider::FILTER_METHOD_BY_QTY,
                'label' => __('By Quantity'),
            ],
            [
                'value' => ConfigProvider::FILTER_METHOD_BY_PERCENTAGE,
                'label' => __('By Percentage'),
            ],
        ];
    }
}
