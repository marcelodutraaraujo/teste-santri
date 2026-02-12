<?php
declare(strict_types=1);

namespace App\Exception;

class InvalidMarginValueException extends DomainException
{
    protected $message = 'Valor da margem de lucro deve ser maior que zero.';
}