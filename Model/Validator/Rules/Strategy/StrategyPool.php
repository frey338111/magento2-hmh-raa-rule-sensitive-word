<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules\Strategy;

use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config\ConfigProvider;

class StrategyPool
{
    /**
     * @param array<string, MatchedWordsValidationStrategyInterface> $strategies
     */
    public function __construct(
        private readonly array $strategies = []
    ) {
    }

    public function get(string $filterMethod): MatchedWordsValidationStrategyInterface
    {
        return $this->strategies[$filterMethod]
            ?? $this->strategies[ConfigProvider::FILTER_METHOD_BY_QTY];
    }
}
