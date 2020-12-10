<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use App\Admin\Field\VichImageField;


class CompanyCrudController extends AbstractCrudController {

    /**
     * @var CrudUrlGenerator
     */
    private $crudUrlGenerator;

    public function __construct(CrudUrlGenerator $crudUrlGenerator) {
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    public static function getEntityFqcn(): string {
        return Company::class;
    }

    public function configureFields(string $pageName): iterable {
        return [
            TextField::new('name', 'Name'),
            TextField::new('legal_poeple', 'LegalPoeple'),
            TextField::new('jurist_people', 'JuristPeople'),
            TextField::new('inscription_number', 'InscriptionNumber'),
            TextField::new('address', 'Address')->onlyOnDetail()->onlyOnForms(),
            TelephoneField::new('phone', 'Phone')->onlyOnForms(),
            AvatarField::new('logoUrl', 'Logo')->onlyOnDetail(),
            VichImageField::new('logoFile', 'Logo')->onlyOnForms(),
            UrlField::new('videoUrl', 'Trailer URL')->hideOnIndex(),
            AssociationField::new('province', 'Province'),
            AssociationField::new('owner', 'Owner')->onlyOnIndex()->onlyOnDetail(),
            DateTimeField::new('createdAt', 'CreatedAt')->hideOnForm()->onlyOnDetail(),
            DateTimeField::new('updatedAt', 'UpdatedAt')->hideOnForm()->onlyOnDetail()
        ];
    }

    public function createEntity(string $entityFqcn) {
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

    public function configureActions(Actions $actions): Actions {

        $currentUser = $this->getUser();

        $addService = Action::new('addService', 'Add Service', 'fa fa-tags')
                ->linkToCrudAction('addService')
                ->setLabel(false)
                ->displayIf(static function ($entity) use($currentUser) {
                    return $entity->isOwner($currentUser) || $currentUser->isSuperAdmin();
                });
        $viewService = Action::new('viewService', 'View Service', 'fa fa-list')
                ->linkToCrudAction('viewService')
                ->setLabel(false)
                ->displayIf(static function ($entity) use($currentUser) {
                    return $entity->isOwner($currentUser) || $currentUser->isSuperAdmin();
                });

        return $actions
                        ->add(Crud::PAGE_INDEX, $addService)
                        ->add(Crud::PAGE_INDEX, $viewService)
                        // Crud::PAGE_INDEX
                        ->add(Crud::PAGE_INDEX, Action::DETAIL)
                        ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                            return $action
                                    ->setIcon('fa fa-eye')
                                    ->setLabel(false);
                        })
                        ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) use($currentUser) {
                            return $action
                                    ->setIcon('fa fa-pencil')
                                    ->setLabel(false)
                                    ->displayIf(static function ($entity) use($currentUser) {
                                        return $entity->isOwner($currentUser) || $currentUser->isSuperAdmin();
                                    });
                        })
                        ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) use($currentUser) {
                            return $action
                                    ->setIcon('fa fa-trash')
                                    ->setLabel(false)
                                    ->displayIf(static function ($entity) use($currentUser) {
                                        return $entity->isOwner($currentUser) || $currentUser->isSuperAdmin();
                                    });
                        })
                        // Crud::PAGE_DETAIL
                        ->update(Crud::PAGE_DETAIL, Action::EDIT, function (Action $action) use($currentUser) {
                            return $action
                                    ->setIcon('fa fa-pencil')
                                    ->displayIf(static function ($entity) use($currentUser) {
                                        return $entity->isOwner($currentUser) || $currentUser->isSuperAdmin();
                                    });
                        })
                        ->update(Crud::PAGE_DETAIL, Action::DELETE, function (Action $action) use($currentUser) {
                            return $action
                                    ->setIcon('fa fa-trash')
                                    ->displayIf(static function ($entity) use($currentUser) {
                                        return $entity->isOwner($currentUser) || $currentUser->isSuperAdmin();
                                    });
                        })
        ;
    }

    public function addService(AdminContext $context) {

        $entityId= $context->getRequest()->query->get('entityId');

        $url = $this->crudUrlGenerator->build()
                ->setController(CompanyServicesCrudController::class)
                ->setEntityId(null)
                ->set('entityFqcn', \App\Entity\CompanyServices::class)
                ->set('company_id', $entityId)
                ->setAction(Action::NEW)
                ->generateUrl();

        return $this->redirect($url);
    }

    public function viewService(AdminContext $context) {

        $entityId= $context->getRequest()->query->get('entityId');

        $url = $this->crudUrlGenerator->build()
                ->setController(CompanyServicesCrudController::class)
                ->setEntityId(null)
                ->set('entityFqcn', \App\Entity\CompanyServices::class)
                ->set('company_id', $entityId)
                ->setAction(Action::INDEX)
                ->generateUrl();

        return $this->redirect($url);
    }

}
