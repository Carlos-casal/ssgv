<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Extension\Core\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Transforms between a number type and a localized number with grouping
 * (each thousand) and comma separators.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Florian Eckerstorfer <florian@eckerstorfer.org>
 *
 * @implements DataTransformerInterface<int|float, string>
 */
class NumberToLocalizedStringTransformer implements DataTransformerInterface
{
    protected bool $grouping;
    protected int $roundingMode;

    public function __construct(
        private ?int $scale = null,
        ?bool $grouping = false,
        ?int $roundingMode = \NumberFormatter::ROUND_HALFUP,
        private ?string $locale = null,
    ) {
        $this->grouping = $grouping ?? false;
        $this->roundingMode = $roundingMode ?? \NumberFormatter::ROUND_HALFUP;
    }

    /**
     * Transforms a number type into localized number.
     *
     * @param int|float|string|null $value Number value
     *
     * @throws TransformationFailedException if the given value is not numeric
     *                                       or if the value cannot be transformed
     */
    public function transform(mixed $value): string
    {
        if (null === $value || '' === $value) {
            return '';
        }

        if (!is_numeric($value)) {
            throw new TransformationFailedException('Expected a numeric.');
        }

        $formatter = $this->getNumberFormatter();
        $value = $formatter->format($value);

        if (intl_is_failure($formatter->getErrorCode())) {
            throw new TransformationFailedException($formatter->getErrorMessage());
        }

        // Convert non-breaking and narrow non-breaking spaces to normal ones
        return str_replace(["\xc2\xa0", "\xe2\x80\xaf"], ' ', $value);
    }

    /**
     * Transforms a localized number into an integer or float.
     *
     * @param string $value The localized value
     *
     * @throws TransformationFailedException if the given value is not a string
     *                                       or if the value cannot be transformed
     */
    public function reverseTransform(mixed $value): int|float|null
    {
        if (null !== $value && !\is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        if (null === $value || '' === $value) {
            return null;
        }

        if (\in_array($value, ['NaN', 'NAN', 'nan'], true)) {
            throw new TransformationFailedException('"NaN" is not a valid number.');
        }

        $position = 0;
        $formatter = $this->getNumberFormatter();
        $groupSep = $formatter->getSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL);
        $decSep = $formatter->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);

        if ('.' !== $decSep && (!$this->grouping || '.' !== $groupSep)) {
            $value = str_replace('.', $decSep, $value);
        }

        if (',' !== $decSep && (!$this->grouping || ',' !== $groupSep)) {
            $value = str_replace(',', $decSep, $value);
        }

        // If the value is in exponential notation with a negative exponent, we end up with a float value too
        if (str_contains($value, $decSep) || false !== stripos($value, 'e-')) {
            $type = \NumberFormatter::TYPE_DOUBLE;
        } else {
            $type = \PHP_INT_SIZE === 8
                ? \NumberFormatter::TYPE_INT64
                : \NumberFormatter::TYPE_INT32;
        }

        try {
            $result = @$formatter->parse($value, $type, $position);
        } catch (\IntlException $e) {
            throw new TransformationFailedException($e->getMessage(), $e->getCode(), $e);
        }

        if (intl_is_failure($formatter->getErrorCode())) {
            throw new TransformationFailedException($formatter->getErrorMessage(), $formatter->getErrorCode());
        }

        if ($result >= \PHP_INT_MAX || $result <= -\PHP_INT_MAX) {
            throw new TransformationFailedException('I don\'t have a clear idea what infinity looks like.');
        }

        $result = $this->castParsedValue($result);

        if (false !== $encoding = mb_detect_encoding($value, null, true)) {
            $length = mb_strlen($value, $encoding);
            $remainder = mb_substr($value, $position, $length, $encoding);
        } else {
            $length = \strlen($value);
            $remainder = substr($value, $position, $length);
        }

        // After parsing, position holds the index of the character where the
        // parsing stopped
        if ($position < $length) {
            // Check if there are unrecognized characters at the end of the
            // number (excluding whitespace characters)
            $remainder = trim($remainder, " \t\n\r\0\x0b\xc2\xa0");

            if ('' !== $remainder) {
                throw new TransformationFailedException(\sprintf('The number contains unrecognized characters: "%s".', $remainder));
            }
        }

        // NumberFormatter::parse() does not round
        return $this->round($result);
    }

    /**
     * Returns a preconfigured \NumberFormatter instance.
     */
    protected function getNumberFormatter(): \NumberFormatter
    {
        $formatter = new \NumberFormatter($this->locale ?? \Locale::getDefault(), \NumberFormatter::DECIMAL);

        if (null !== $this->scale) {
            $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, $this->scale);
            $formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, $this->roundingMode);
        }

        $formatter->setAttribute(\NumberFormatter::GROUPING_USED, $this->grouping);

        return $formatter;
    }

    /**
     * @internal
     */
    protected function castParsedValue(int|float $value): int|float
    {
        if (\is_int($value) && $value === (int) $float = (float) $value) {
            return $float;
        }

        return $value;
    }

    /**
     * Rounds a number according to the configured scale and rounding mode.
     */
    private function round(int|float $number): int|float
    {
        if (null !== $this->scale) {
            // shift number to maintain the correct scale during rounding
            $roundingCoef = 10 ** $this->scale;
            // string representation to avoid rounding errors, similar to bcmul()
            $number = (string) ($number * $roundingCoef);

            $number = match ($this->roundingMode) {
                \NumberFormatter::ROUND_CEILING => ceil($number),
                \NumberFormatter::ROUND_FLOOR => floor($number),
                \NumberFormatter::ROUND_UP => $number > 0 ? ceil($number) : floor($number),
                \NumberFormatter::ROUND_DOWN => $number > 0 ? floor($number) : ceil($number),
                \NumberFormatter::ROUND_HALFEVEN => round($number, 0, \PHP_ROUND_HALF_EVEN),
                \NumberFormatter::ROUND_HALFUP => round($number, 0, \PHP_ROUND_HALF_UP),
                \NumberFormatter::ROUND_HALFDOWN => round($number, 0, \PHP_ROUND_HALF_DOWN),
            };

            $number = 1 === $roundingCoef ? (int) $number : $number / $roundingCoef;
        }

        return $number;
    }
}
