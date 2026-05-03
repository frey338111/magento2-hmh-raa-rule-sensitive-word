<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigProvider
{
    public const XML_PATH_WORDS = 'hmh_review_auto_approval/word_filter/words';
    public const XML_PATH_FILTER_METHOD = 'hmh_review_auto_approval/word_filter/filter_method';
    public const XML_PATH_WORD_COUNT = 'hmh_review_auto_approval/word_filter/word_count';
    public const XML_PATH_WORD_PERCENTAGE = 'hmh_review_auto_approval/word_filter/word_percentage';
    public const FILTER_METHOD_BY_QTY = 'by_qty';
    public const FILTER_METHOD_BY_PERCENTAGE = 'by_percentage';
    private const DEFAULT_WORD_COUNT = 1;
    private const DEFAULT_WORD_PERCENTAGE = 10.0;

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function getWords(?int $storeId = null): array
    {
        $value = (string) $this->scopeConfig->getValue(
            self::XML_PATH_WORDS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if (trim($value) === '') {
            return [];
        }

        $words = preg_split('/[\r\n,]+/', $value) ?: [];

        return array_values(array_filter(array_map('trim', $words)));
    }

    public function getFilterMethod(?int $storeId = null): string
    {
        $value = (string) $this->scopeConfig->getValue(
            self::XML_PATH_FILTER_METHOD,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if (in_array($value, [self::FILTER_METHOD_BY_QTY, self::FILTER_METHOD_BY_PERCENTAGE], true)) {
            return $value;
        }

        return self::FILTER_METHOD_BY_QTY;
    }

    public function getWordCount(?int $storeId = null): int
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_WORD_COUNT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if (!is_numeric($value)) {
            return self::DEFAULT_WORD_COUNT;
        }

        $wordCount = (int) $value;

        return $wordCount > 0 ? $wordCount : self::DEFAULT_WORD_COUNT;
    }

    public function getWordPercentage(?int $storeId = null): float
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_WORD_PERCENTAGE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if (!is_numeric($value)) {
            return self::DEFAULT_WORD_PERCENTAGE;
        }

        return min(100.0, max(10.0, (float) $value));
    }
}
