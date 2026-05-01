<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules;

use Hmh\ReviewAutoApproval\Model\Validator\Rules\AbstractValidator;
use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config\ConfigProvider;
use Magento\Review\Model\Review;

class WordsFilterValidator extends AbstractValidator
{
    public function __construct(
        private readonly ConfigProvider $configProvider
    ) {
    }

    public function isValid(Review $review): bool
    {
        $blackList = $this->configProvider->getWords($this->getStoreId($review));

        if ($blackList === []) {
            return true;
        }

        $reviewText = $this->normalizeText(implode(' ', [
            (string) $review->getTitle(),
            (string) $review->getDetail(),
            (string) $review->getNickname(),
        ]));

        if ($reviewText === '') {
            return true;
        }

        $matchedWord = $this->getMatchedWord($reviewText, $blackList);
        if ($matchedWord === null) {
            return true;
        }

        return false;
    }

    private function getMatchedWord(string $targetText, array $wordList): ?string
    {
        foreach ($wordList as $word) {
            $word = trim((string) $word);
            if ($word === '') {
                continue;
            }

            if (preg_match('/\b' . preg_quote($word, '/') . '\b/i', $targetText)) {
                return $word;
            }
        }

        return null;
    }

    private function normalizeText(string $text): string
    {
        return strtolower($text);
    }
}
