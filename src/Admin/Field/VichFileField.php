<?php

namespace App\Admin\Field;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Vich\UploaderBundle\Form\Type\VichFileType;

/**
 * Description of VichImage
 *
 * @author Michel
 */
class VichFileField implements FieldInterface
{
    use FieldTrait;
    public static function new(string $propertyName, ?string $label = null): self {
        
         return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)            
            // you can use your own form types too
            ->setFormType(VichFileType::class)            
        ;
        
    }

}
