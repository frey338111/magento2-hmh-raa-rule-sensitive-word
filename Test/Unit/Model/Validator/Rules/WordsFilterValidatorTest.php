<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Test\Unit\Model\Validator\Rules;

use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config\ConfigProvider;
use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules\Strategy\StrategyPool;
use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules\WordsFilterValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use ReflectionMethod;

class WordsFilterValidatorTest extends TestCase
{
    private ConfigProvider|MockObject $configProvider;
    private StrategyPool|MockObject $strategyPool;
    private LoggerInterface|MockObject $logger;
    private WordsFilterValidator $validator;

    protected function setUp(): void
    {
        $this->configProvider = $this->createMock(ConfigProvider::class);
        $this->strategyPool = $this->createMock(StrategyPool::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->validator = new WordsFilterValidator($this->configProvider, $this->strategyPool, $this->logger);
    }

    /**
     * @param string[] $wordList
     * @dataProvider getMatchedWordDataProvider
     */
    public function testGetMatchedWord(string $targetText, array $wordList, int $expected): void
    {
        $method = new ReflectionMethod(WordsFilterValidator::class, 'getMatchedWord');
        $method->setAccessible(true);

        $this->assertSame(
            $expected,
            $method->invoke($this->validator, $targetText, $wordList)
        );
    }

    public static function getMatchedWordDataProvider(): array
    {
        return [
            'matches single word' => [
                'targetText' => 'this product is a scam',
                'wordList' => ['fraud', 'scam'],
                'expected' => 1,
            ],
            'matches multi-word phrase' => [
                'targetText' => 'this was a waste of money',
                'wordList' => ['waste of money'],
                'expected' => 1,
            ],
            'matches phrase case insensitively' => [
                'targetText' => 'please DO NOT BUY this item',
                'wordList' => ['do not buy'],
                'expected' => 1,
            ],
            'does not match partial words' => [
                'targetText' => 'the showcase looked good',
                'wordList' => ['case'],
                'expected' => 0,
            ],
            'returns all configured matches' => [
                'targetText' => 'fake and scam product',
                'wordList' => ['scam', 'fake'],
                'expected' => 2,
            ],
            'keeps repeated matches' => [
                'targetText' => 'scam product, another scam product',
                'wordList' => ['scam'],
                'expected' => 2,
            ],
            'ignores empty words' => [
                'targetText' => 'this product is terrible',
                'wordList' => ['', '   ', 'terrible'],
                'expected' => 1,
            ],
            'returns zero when nothing matches' => [
                'targetText' => 'this product is fine',
                'wordList' => ['scam', 'fraud'],
                'expected' => 0,
            ],
        ];
    }
}
