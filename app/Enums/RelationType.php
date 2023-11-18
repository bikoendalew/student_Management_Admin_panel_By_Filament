<?php 
namespace App\Enums;

enum RelationType:string{

    case Father = 'Father';
    case Mother = 'Mother';
    case Sister = 'Sister';
    case Brother = 'Brother';

    public static function getvalues() : array{
        return array_column(RelationType::cases(), 'value');
    }

    public static function getKeyvalues() : array{
        return array_column(RelationType::cases(), 'value','value');
    }
}