<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Test\Unit\Model\Validator\Rules\Strategy;

use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config\ConfigProvider;
use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules\Strategy\MatchedWordsValidationStrategyInterface;
use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules\Strategy\StrategyPool;
use PHPUnit\Framework\TestCase;

class StrategyPoolTest extends TestCase
{
    public function testGetReturnsConfiguredStrategy(): void
    {
        $byQtyStrategy = $this->createMock(MatchedWordsValidationStrategyInterface::class);
        $byPercentageStrategy = $this->createMock(MatchedWordsValidationStrategyInterface::class);
        $strategyPool = new StrategyPool([
            ConfigProvider::FILTER_METHOD_BY_QTY => $byQtyStrategy,
            ConfigProvider::FILTER_METHOD_BY_PERCENTAGE => $byPercentageStrategy,
        ]);

        $this->assertSame($byPercentageStrategy, $strategyPool->get(ConfigProvider::FILTER_METHOD_BY_PERCENTAGE));
    }

    public function testGetFallsBackToQuantityStrategy(): void
    {
        $byQtyStrategy = $this->createMock(MatchedWordsValidationStrategyInterface::class);
        $strategyPool = new StrategyPool([
            ConfigProvider::FILTER_METHOD_BY_QTY => $byQtyStrategy,
        ]);

        $this->assertSame($byQtyStrategy, $strategyPool->get('invalid'));
    }
}
