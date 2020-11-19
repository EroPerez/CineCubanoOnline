<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;

use App\Admin\Field\VichImageField;

class CompanyCrudController extends AbstractCrudController {

    public static function getEntityFqcn(): string {
        return Company::class;

    }

    public function configureFields(string $pageName): iterable {
        return [
          TextField::new('name', 'Name'),
          TextField::new('legal_poeple', 'LegalPoeple'),
          TextField::new('jurist_people', 'JuristPeople'),
          TextField::new('inscription_number', 'InscriptionNumber'),
          TextField::new('address', 'Address')->onlyOnDetail(),
          TelephoneField::new('phone', 'Phone')->onlyOnDetail(),
          ImageField::new('logoUrl', 'Logo')->onlyOnDetail(),
          VichImageField::new('logoFile', 'Logo')->onlyOnForms(),
          AssociationField::new('province', 'Province'),
          AssociationField::new('owner', 'Owner')->onlyOnIndex(),
          DateTimeField::new('createdAt', 'CreatedAt')->hideOnForm()->onlyOnDetail(),
          DateTimeField::new('updatedAt', 'UpdatedAt')->hideOnForm()->onlyOnDetail()
        ];

    }
    
    public function createEntity(string $entityFqcn)
    {
        $company = new Company();
        $company->setOwner($this->getUser());

        return $company;
    }
    
    public function configureCrud(Crud $crud): Crud {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Company')
            ->setEntityLabelInPlural('Companies')

            // the Symfony Security permission needed to manage the entity
            // (none by default, so you can manage all instances of the entity)
            ->setEntityPermission('ROLE_USER')
        ;

    }


}
