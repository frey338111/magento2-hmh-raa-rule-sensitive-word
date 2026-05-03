<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules\Strategy;

use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config\ConfigProvider;

class ByQtyStrategy implements MatchedWordsValidationStrategyInterface
{
    public function __construct(
        private readonly ConfigProvider $configProvider
    ) {
    }

    public function isValid(int $matchedWordTotal, string $reviewText, ?int $storeId = null): bool
    {
        return $matchedWordTotal <= $this->configProvider->getWordCount($storeId);
    }
}
