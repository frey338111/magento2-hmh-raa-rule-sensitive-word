# Hmh Review Auto Approval Rule Word Filter

Adds a word-filter rule to `Hmh_ReviewAutoApproval`.

The rule prevents automatic approval when a pending product review contains any configured blocked word or phrase in the review title, detail, or nickname.

## Requirements

- Magento 2
- PHP 8.1+
- `Hmh_ReviewAutoApproval`

## Module

- Magento module name: `Hmh_ReviewAutoApprovalRuleWordFilter`
- Composer package: `hmh/magento2-review-auto-approval-rule-word-filter`
- Validator key: `words_filter`
- Validator class: `Hmh\ReviewAutoApprovalRuleWordFilter\Model\Validator\Rules\WordsFilterValidator`

## Configuration

The module adds a `Blocked Words` textarea under:

`Stores > Configuration > HMH > Review Auto Approval > Auto Approval by Word Filter`

Config path:

`hmh_review_auto_approval/word_filter/words`

Words can be separated by commas or new lines. Multi-word phrases are supported, for example:

```text
waste of money
do not buy
not as described
```

The module also registers `words_filter` in the existing `Hmh\ReviewAutoApproval\Model\Validator\ValidatorPool`, so it appears in the Review Auto Approval `Rules` multiselect.

## Default Blocklist

Default blocked words and phrases are defined in `etc/config.xml`.

## Behavior

When `words_filter` is selected as an auto-approval rule:

- If no blocked words are configured, the rule passes.
- If none of the configured words or phrases are found, the rule passes.
- If any configured word or phrase is found, the rule fails and the review is not auto-approved by this rule.

Matching is case-insensitive and uses word boundaries to avoid matching partial words.

## Tests

Run the unit test from the Magento root:

```bash
vendor/bin/phpunit --no-extensions --do-not-cache-result -c dev/tests/unit/phpunit.xml.dist app/code/Hmh/ReviewAutoApprovalRuleWordFilter/Test/Unit/Model/Validator/Rules/WordsFilterValidatorTest.php
```
