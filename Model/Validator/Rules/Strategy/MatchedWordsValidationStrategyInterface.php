<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules\Strategy;

interface MatchedWordsValidationStrategyInterface
{
    public function isValid(int $matchedWordTotal, string $reviewText, ?int $storeId = null): bool;
}
