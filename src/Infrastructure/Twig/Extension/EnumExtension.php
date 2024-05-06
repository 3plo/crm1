<?php
/**
 * Created by PhpStorm.
 * Date: 06.05.2024
 * Time: 12:39
 */

namespace App\Infrastructure\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EnumExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('enum', [$this, 'createProxy']),
        ];
    }

    public function createProxy(string $enumFQN): object
    {
        return new class($enumFQN) {
            public function __construct(private readonly string $enum)
            {
                if (!enum_exists($this->enum)) {
                    throw new \InvalidArgumentException("$this->enum is not an Enum type and cannot be used in this function");
                }
            }

            public function __call(string $name, array $arguments)
            {
                $enumFQN = sprintf('%s::%s', $this->enum, $name);

                if (defined($enumFQN)) {
                    return constant($enumFQN);
                }

                if (method_exists($this->enum, $name)) {
                    return $this->enum::$name(...$arguments);
                }

                throw new \BadMethodCallException("Neither \"{$enumFQN}\" nor \"{$enumFQN}::{$name}()\" exist in this runtime.");
            }
        };
    }
}