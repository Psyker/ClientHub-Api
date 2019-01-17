<?php

namespace App\GraphQL\Type;

class AnyType
{
    public static function serialize($value)
    {
        if (\is_bool($value)) {
            return (bool)$value;
        }
        if (\is_string($value)) {
            return (string)$value;
        }
        if (\is_int($value)) {
            return (int)$value;
        }
        if(\is_float($value)) {
            return (float)$value;
        }

        return $value;
    }

    public static function parseValue($value)
    {
        if (\is_bool($value)) {
            return (bool)$value;
        }
        if (\is_string($value)) {
            return (string)$value;
        }
        if (\is_int($value)) {
            return (int)$value;
        }
        if(\is_float($value)) {
            return (float)$value;
        }

        return $value;
    }

    public static function parseLiteral($valueNode)
    {
        if (\is_bool($valueNode->value)) {
            return (bool)$valueNode->value;
        }
        if (\is_string($valueNode->value)) {
            return (string)$valueNode->value;
        }
        if (\is_int($valueNode->value)) {
            return (int)$valueNode->value;
        }
        if(\is_float($valueNode->value)) {
            return (float)$valueNode->value;
        }

        return $valueNode->value;
    }
}
