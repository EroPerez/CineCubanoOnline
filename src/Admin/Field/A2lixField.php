<?php

namespace App\Admin\Field;


use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;

/**
 * Description of A2lixField
 *
 * @author michel
 */
class A2lixField implements FieldInterface
{
    use FieldTrait;
    public static function new(string $propertyName, ?string $label = null): self {
        
         return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)            
            // you can use your own form types too
            ->setFormType(TranslationsType::class)            
        ;
        
    }

}
