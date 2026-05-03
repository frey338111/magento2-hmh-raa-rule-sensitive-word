<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Test\Unit\Model\Validator\Rules\Strategy;

use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config\ConfigProvider;
use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules\Strategy\ByQtyStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ByQtyStrategyTest extends TestCase
{
    private ConfigProvider|MockObject $configProvider;
    private ByQtyStrategy $strategy;

    protected function setUp(): void
    {
        $this->configProvider = $this->createMock(ConfigProvider::class);
        $this->strategy = new ByQtyStrategy($this->configProvider);
    }

    /**
     * @dataProvider isValidDataProvider
     */
    public function testIsValid(int $matchedWordTotal, int $wordCount, bool $expected): void
    {
        $this->configProvider->expects($this->once())
            ->method('getWordCount')
            ->with(1)
            ->willReturn($wordCount);

        $this->assertSame($expected, $this->strategy->isValid($matchedWordTotal, 'review text', 1));
    }

    public static function isValidDataProvider(): array
    {
        return [
            'passes below configured count' => [
                'matchedWordTotal' => 1,
                'wordCount' => 2,
                'expected' => true,
            ],
            'passes at configured count' => [
                'matchedWordTotal' => 2,
                'wordCount' => 2,
                'expected' => true,
            ],
            'fails above configured count' => [
                'matchedWordTotal' => 3,
                'wordCount' => 2,
                'expected' => false,
            ],
        ];
    }
}
