<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Admin\Field\A2lixField;

class ServiceCrudController extends AbstractCrudController {

    public static function getEntityFqcn(): string {
        return Service::class;
    }

    public function configureFields(string $pageName): iterable {
        return [            
            A2lixField::new('translations')->onlyOnForms()->setLabel('Name')
                ->setFormTypeOptions([
                    'default_locale'=>'%locale%',
                    'fields'=>[
                        'name'=>[
                            'field_type'=> 'Symfony\Component\Form\Extension\Core\Type\TextType',
                            'locale_options'=>[
                                'en'=>['label'=>'English '],
                                'es'=>['label'=>'Spanish']
                            ]
                            
                        ]
                    ]
                ]),
            TextField::new('name', 'Name')->hideOnForm(),
            DateTimeField::new('createdAt', 'CreatedAt')->hideOnForm(),
            DateTimeField::new('updatedAt', 'UpdatedAt')->hideOnForm()
        ];
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
                        // the labels used to refer to this entity in titles, buttons, etc.
                        ->setEntityLabelInSingular('Category')
                        ->setEntityLabelInPlural('Categories')

                        // the Symfony Security permission needed to manage the entity
                        // (none by default, so you can manage all instances of the entity)
                        ->setEntityPermission('ROLE_USER')
        ;
    }

    public function configureActions(Actions $actions): Actions {
        return $actions
                        // ...
                        ->add(Crud::PAGE_INDEX, Action::DETAIL)
                        ->setPermissions([Action::NEW => 'ROLE_ADMIN', Action::EDIT => 'ROLE_ADMIN', Action::DELETE => 'ROLE_ADMIN'])

        ;
    }

}
