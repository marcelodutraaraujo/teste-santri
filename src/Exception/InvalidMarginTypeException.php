<?php
declare(strict_types=1);

namespace App\Exception;

class InvalidMarginTypeException extends DomainException
{
    protected $message = 'Tipo de margem inválido verifique a documentação sobre os tipos de margens';
}