<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Test\Unit\Model\Validator\Rules\Strategy;

use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config\ConfigProvider;
use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules\Strategy\ByPercentageStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ByPercentageStrategyTest extends TestCase
{
    private ConfigProvider|MockObject $configProvider;
    private ByPercentageStrategy $strategy;

    protected function setUp(): void
    {
        $this->configProvider = $this->createMock(ConfigProvider::class);
        $this->strategy = new ByPercentageStrategy($this->configProvider);
    }

    /**
     * @dataProvider isValidDataProvider
     */
    public function testIsValid(int $matchedWordTotal, string $reviewText, float $wordPercentage, bool $expected): void
    {
        $this->configProvider->expects($this->once())
            ->method('getWordPercentage')
            ->with(1)
            ->willReturn($wordPercentage);

        $this->assertSame($expected, $this->strategy->isValid($matchedWordTotal, $reviewText, 1));
    }

    public function testIsValidPassesEmptyReviewText(): void
    {
        $this->configProvider->expects($this->never())
            ->method('getWordPercentage');

        $this->assertTrue($this->strategy->isValid(1, '', 1));
    }

    public static function isValidDataProvider(): array
    {
        return [
            'passes below configured percentage' => [
                'matchedWordTotal' => 1,
                'reviewText' => 'scam product works fine today',
                'wordPercentage' => 25.0,
                'expected' => true,
            ],
            'fails at configured percentage' => [
                'matchedWordTotal' => 1,
                'reviewText' => 'scam product works fine',
                'wordPercentage' => 25.0,
                'expected' => false,
            ],
            'fails above configured percentage with repeated matches' => [
                'matchedWordTotal' => 2,
                'reviewText' => 'scam product another scam product',
                'wordPercentage' => 25.0,
                'expected' => false,
            ],
        ];
    }
}
