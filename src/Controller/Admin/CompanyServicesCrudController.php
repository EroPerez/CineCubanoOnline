<?php

namespace App\Controller\Admin;

use App\Entity\CompanyServices;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use App\Admin\Field\VichImageField;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use Doctrine\ORM\QueryBuilder;

class CompanyServicesCrudController extends AbstractCrudController {

    public static function getEntityFqcn(): string {
        return CompanyServices::class;
    }

    private function getContext(): ?AdminContext {
        return $this->get(AdminContextProvider::class)->getContext();
    }

    public function configureFields(string $pageName): iterable {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('company', 'Company')->hideOnForm(),
            AssociationField::new('category', 'Category'),
            TextField::new('title', 'Title'),
            TextEditorField::new('description'),
            ImageField::new('logoUrl', 'Logo')->onlyOnDetail(),
            VichImageField::new('logoFile', 'Logo')->onlyOnForms(),
            DateTimeField::new('createdAt', 'CreatedAt')->hideOnForm(),
            DateTimeField::new('updatedAt', 'UpdatedAt')->hideOnForm()
        ];
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
                        // the labels used to refer to this entity in titles, buttons, etc.
                        ->setEntityLabelInSingular('CompanyService')
                        ->setEntityLabelInPlural('CompanyServices')

                        // the Symfony Security permission needed to manage the entity
                        // (none by default, so you can manage all instances of the entity)
                        ->setEntityPermission('ROLE_USER')
        ;
    }

    public function configureActions(Actions $actions): Actions {
        return $actions
                        // ...
                        ->add(Crud::PAGE_INDEX, Action::DETAIL)

        ;
    }

    public function createEntity(string $entityFqcn) {

        $context = $this->getContext();
        $id = $context->getRequest()->query->get('company_id');
        $company = $this->getDoctrine()->getRepository(\App\Entity\Company::class)->find($id);

        $newCompanyService = new $entityFqcn();
        $newCompanyService->setCompany($company);

        return $newCompanyService;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder {
        /**
         * @var QueryBuilder
         */
        $qb = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $context = $this->getContext();
        $id = $context->getRequest()->query->get('company_id');
        $company = $this->getDoctrine()->getRepository(\App\Entity\Company::class)->find($id);

        return $qb
                        ->andWhere('entity.company=:company')
                        ->setParameter(':company', $company);
    }

}
