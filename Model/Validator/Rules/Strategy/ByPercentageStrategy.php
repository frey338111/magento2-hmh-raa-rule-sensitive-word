<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules\Strategy;

use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config\ConfigProvider;

class ByPercentageStrategy implements MatchedWordsValidationStrategyInterface
{
    public function __construct(
        private readonly ConfigProvider $configProvider
    ) {
    }

    public function isValid(int $matchedWordTotal, string $reviewText, ?int $storeId = null): bool
    {
        $totalWords = str_word_count($reviewText);
        if ($totalWords === 0) {
            return true;
        }

        $matchedPercentage = $matchedWordTotal / $totalWords * 100;

        return $matchedPercentage < $this->configProvider->getWordPercentage($storeId);
    }
}
