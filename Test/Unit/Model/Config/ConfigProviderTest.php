<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Test\Unit\Model\Config;

use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config\ConfigProvider;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    private ScopeConfigInterface|MockObject $scopeConfig;
    private ConfigProvider $configProvider;

    protected function setUp(): void
    {
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->configProvider = new ConfigProvider($this->scopeConfig);
    }

    /**
     * @dataProvider getFilterMethodDataProvider
     */
    public function testGetFilterMethod(string $configValue, string $expected): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with(
                ConfigProvider::XML_PATH_FILTER_METHOD,
                ScopeInterface::SCOPE_STORE,
                1
            )
            ->willReturn($configValue);

        $this->assertSame($expected, $this->configProvider->getFilterMethod(1));
    }

    public static function getFilterMethodDataProvider(): array
    {
        return [
            'by quantity' => [
                'configValue' => ConfigProvider::FILTER_METHOD_BY_QTY,
                'expected' => ConfigProvider::FILTER_METHOD_BY_QTY,
            ],
            'by percentage' => [
                'configValue' => ConfigProvider::FILTER_METHOD_BY_PERCENTAGE,
                'expected' => ConfigProvider::FILTER_METHOD_BY_PERCENTAGE,
            ],
            'invalid value falls back to quantity' => [
                'configValue' => 'invalid',
                'expected' => ConfigProvider::FILTER_METHOD_BY_QTY,
            ],
        ];
    }

    /**
     * @dataProvider getWordCountDataProvider
     */
    public function testGetWordCount(mixed $configValue, int $expected): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with(
                ConfigProvider::XML_PATH_WORD_COUNT,
                ScopeInterface::SCOPE_STORE,
                1
            )
            ->willReturn($configValue);

        $this->assertSame($expected, $this->configProvider->getWordCount(1));
    }

    public static function getWordCountDataProvider(): array
    {
        return [
            'configured count' => [
                'configValue' => '3',
                'expected' => 3,
            ],
            'zero falls back to one' => [
                'configValue' => '0',
                'expected' => 1,
            ],
            'invalid value falls back to one' => [
                'configValue' => 'invalid',
                'expected' => 1,
            ],
        ];
    }

    /**
     * @dataProvider getWordPercentageDataProvider
     */
    public function testGetWordPercentage(mixed $configValue, float $expected): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with(
                ConfigProvider::XML_PATH_WORD_PERCENTAGE,
                ScopeInterface::SCOPE_STORE,
                1
            )
            ->willReturn($configValue);

        $this->assertSame($expected, $this->configProvider->getWordPercentage(1));
    }

    public static function getWordPercentageDataProvider(): array
    {
        return [
            'configured percentage' => [
                'configValue' => '25.5',
                'expected' => 25.5,
            ],
            'percentage below ten is clamped to ten' => [
                'configValue' => '-1',
                'expected' => 10.0,
            ],
            'percentage over one hundred is clamped' => [
                'configValue' => '101',
                'expected' => 100.0,
            ],
            'invalid value falls back to ten' => [
                'configValue' => 'invalid',
                'expected' => 10.0,
            ],
        ];
    }
}
