<?php

namespace App\Controller\Admin;

use App\Entity\Configuration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class ConfigurationCrudController extends AbstractCrudController {

    public static function getEntityFqcn(): string {
        return Configuration::class;
    }

    public function configureFields(string $pageName): iterable {
        return [
                    ChoiceField::new('id', 'Variable Name')
                    ->renderAsBadges()
                    ->setChoices([
                        'SOCIAL_ACCOUNT_FACEBOOK' => 'SOCIAL_ACCOUNT_FACEBOOK',
                        'SOCIAL_ACCOUNT_TWITTER' => 'SOCIAL_ACCOUNT_TWITTER',
                        'SOCIAL_ACCOUNT_INSTAGRAM' => 'SOCIAL_ACCOUNT_INSTAGRAM',
                        'SOCIAL_ACCOUNT_YOUTUBE' => 'SOCIAL_ACCOUNT_YOUTUBE',
                        'SOCIAL_ACCOUNT_LINKEDIN' => 'SOCIAL_ACCOUNT_LINKEDIN',
                            ]
                    ),
                    TextField::new('value', 'Value')
                    ->setRequired(false)
        ];
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
                        // the labels used to refer to this entity in titles, buttons, etc.
                        ->setEntityLabelInSingular('System Variable')
                        ->setEntityLabelInPlural('Systems Variable')

                        // the Symfony Security permission needed to manage the entity
                        // (none by default, so you can manage all instances of the entity)
                        ->setEntityPermission('ROLE_ADMIN')
        ;
    }

}
