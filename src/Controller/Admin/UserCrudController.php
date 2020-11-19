<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use FOS\UserBundle\Model\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Admin\Field\VichImageField;

class UserCrudController extends AbstractCrudController {

    /**
     * @var UserManagerInterface FOS user manager
     */
    var $userManager;

    public function __construct(UserManagerInterface $um) {
        $this->userManager = $um;

    }

    public static function getEntityFqcn(): string {
        return User::class;

    }

    public function configureFields(string $pageName): iterable {
        return [
          AvatarField::new('avatarPath')->onlyOnIndex(),
          TextField::new('firstName', 'FirstName')->onlyOnForms(),
          TextField::new('lastName', 'LastName')->onlyOnForms(),
          TextField::new('username', 'Username'),
            TextField::new('plainPassword', 'Password')
            ->onlyOnForms()
            ->setRequired(false),
          EmailField::new('email', 'Email')->hideOnIndex(),
            ChoiceField::new('roles', 'Roles')
            ->allowMultipleChoices()
            ->renderAsBadges()
            ->setChoices(['roles.user' => 'ROLE_USER', 'roles.admin' => 'ROLE_ADMIN', 'roles.super.admin' => 'ROLE_SUPER_ADMIN', 'roles.editor' => 'ROLE_EDITOR']),
          TextField::new('fullName', 'FullName')->onlyOnIndex(),
          DateTimeField::new('createdAt', 'CreatedAt')->hideOnForm()->onlyOnDetail(),
          DateTimeField::new('updatedAt', 'UpdatedAt')->hideOnForm()->onlyOnDetail(),
          DateTimeField::new('lastLogin','LastLogin')->hideOnForm(),
          BooleanField::new('enabled', 'Enabled?'),
          BooleanField::new('superAdmin', 'SuperAdmin?'),
          VichImageField::new('avatarFile', 'Avatar')->onlyOnForms(),
        ];

    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('Users')

            // the Symfony Security permission needed to manage the entity
            // (none by default, so you can manage all instances of the entity)
            ->setEntityPermission('ROLE_ADMIN')
        ;

    }

    public function createEntity(string $entityFqcn) {
        return $this->userManager->createUser();

    }

    public function updateEntity(EntityManagerInterface $entityManager, $user): void {
        $this->userManager->updateUser($user);

    }

    public function deleteEntity(EntityManagerInterface $entityManager, $user): void {
        $this->userManager->deleteUser($user);

    }

    public function persistEntity(EntityManagerInterface $entityManager, $user): void {

        $this->userManager->updateUser($user);

    }

}
