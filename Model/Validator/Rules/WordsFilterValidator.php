<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules;

use Hmh\ReviewAutoApproval\Model\Validator\Rules\AbstractValidator;
use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config\ConfigProvider;
use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules\Strategy\StrategyPool;
use Magento\Review\Model\Review;

class WordsFilterValidator extends AbstractValidator
{
    public function __construct(
        private readonly ConfigProvider $configProvider,
        private readonly StrategyPool $strategyPool
    ) {
    }

    public function isValid(Review $review): bool
    {
        $storeId = $this->getStoreId($review);
        $blackList = $this->configProvider->getWords($storeId);

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

        $matchedWordTotal = $this->getMatchedWord($reviewText, $blackList);

        $strategy = $this->strategyPool->get($this->configProvider->getFilterMethod($storeId));

        return $strategy->isValid($matchedWordTotal, $reviewText, $storeId);
    }

    private function getMatchedWord(string $targetText, array $wordList): int
    {
        $matchedTotal = 0;

        foreach ($wordList as $word) {
            $word = trim((string) $word);
            if ($word === '') {
                continue;
            }

            $pattern = '/\\b' . preg_quote($word, '/') . '\\b/i';
            $matchCount = preg_match_all($pattern, $targetText);
            if ($matchCount === false || $matchCount === 0) {
                continue;
            }
            $matchedTotal += $matchCount;
        }

        return $matchedTotal;
    }

    private function normalizeText(string $text): string
    {
        return strtolower($text);
    }
}
