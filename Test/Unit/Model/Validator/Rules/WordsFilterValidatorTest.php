<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleWordFilter\Test\Unit\Model\Validator\Rules;

use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Config\ConfigProvider;
use Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules\WordsFilterValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class WordsFilterValidatorTest extends TestCase
{
    private ConfigProvider|MockObject $configProvider;
    private WordsFilterValidator $validator;

    protected function setUp(): void
    {
        $this->configProvider = $this->createMock(ConfigProvider::class);
        $this->validator = new WordsFilterValidator($this->configProvider);
    }

    /**
     * @param string[] $wordList
     * @dataProvider getMatchedWordDataProvider
     */
    public function testGetMatchedWord(string $targetText, array $wordList, ?string $expected): void
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
                'expected' => 'scam',
            ],
            'matches multi-word phrase' => [
                'targetText' => 'this was a waste of money',
                'wordList' => ['waste of money'],
                'expected' => 'waste of money',
            ],
            'matches phrase case insensitively' => [
                'targetText' => 'please DO NOT BUY this item',
                'wordList' => ['do not buy'],
                'expected' => 'do not buy',
            ],
            'does not match partial words' => [
                'targetText' => 'the showcase looked good',
                'wordList' => ['case'],
                'expected' => null,
            ],
            'returns first configured match' => [
                'targetText' => 'fake and scam product',
                'wordList' => ['scam', 'fake'],
                'expected' => 'scam',
            ],
            'ignores empty words' => [
                'targetText' => 'this product is terrible',
                'wordList' => ['', '   ', 'terrible'],
                'expected' => 'terrible',
            ],
            'returns null when nothing matches' => [
                'targetText' => 'this product is fine',
                'wordList' => ['scam', 'fraud'],
                'expected' => null,
            ],
        ];
    }
}
