<?php
declare(strict_types=1);

namespace App\Exception;

class InvalidBasePriceException extends DomainException
{
    protected $message = 'O preço base deve ser maior que zero.';
}
