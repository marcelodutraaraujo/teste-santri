<?php
declare(strict_types=1);

namespace App\Exception;

class InvalidQuantityException extends DomainException
{
    protected $message = 'Quantidade deve ser maior que zero.';
}
