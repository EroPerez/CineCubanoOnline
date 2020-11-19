<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class DashboardController extends AbstractDashboardController {

    /**
     * @var UserManagerInterface FOS user manager
     */
    var $userManager;

    public function __construct(UserManagerInterface $um) {
        $this->userManager = $um;

    }

    /**
     * @Route("/{_locale}/admin", name="admin")
     */
    public function index(): Response {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return parent::index();
        }

        return $this->redirectToRoute('fos_user_security_login');

    }

    public function configureDashboard(): Dashboard {
        return Dashboard::new()
            // the name visible to end users
            // you can include HTML contents too (e.g. to link to an image)
            ->setTitle('<img src="/build/images/favicon.png"> CineCubano <span class="text-small">Online</span>')

            // the path defined in this method is passed to the Twig asset() function
            ->setFaviconPath('/build/images/favicon.png')

        // the domain used by default is 'messages'
        // ->setTranslationDomain('app')
        ;

    }

    public function configureMenuItems(): iterable {
        yield MenuItem::linktoDashboard('admin.menu.dashboard', 'fa fa-dashboard');
        // yield MenuItem::linkToCrud('The Label', 'icon class', EntityClass::class);
        if ($this->isGranted('ROLE_ADMIN')) {

            yield MenuItem::section('admin.menu.configuration', 'fa fa-cogs');
            yield MenuItem::linkToCrud('admin.menu.province', 'fa fa-map', \App\Component\Province\Model\Province::class);
            yield MenuItem::linkToCrud('admin.menu.service', 'fa fa-tags', \App\Entity\Service::class);


            yield MenuItem::section('admin.menu.security', 'fa fa-lock');
            yield MenuItem::linkToCrud('admin.menu.user', 'fa fa-user', \App\Entity\User::class);
        }
        
        
        yield MenuItem::section('admin.menu.general', 'fa fa-folder')->setPermission('ROLE_USER');
        yield MenuItem::linkToCrud('admin.menu.company', 'fa fa-video-camera', \App\Entity\Company::class)
            ->setPermission('ROLE_USER');

    }

    public function configureCrud(): Crud {
        return Crud::new()
            // this defines the pagination size for all CRUD controllers
            // (each CRUD controller can override this value if needed)
            ->setPaginatorPageSize(25)
        ;

    }

    public function configureUserMenu(UserInterface $user): UserMenu {
        $fos_user = $this->userManager->findUserByUsername($user->getUsername());
        // Usually it's better to call the parent method because that gives you a
        // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
        // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
        return parent::configureUserMenu($user)
            // use the given $user object to get the user name
            ->setName($fos_user->getFullName())
            // use this method if you don't want to display the name of the user
            ->displayUserName(false)

            // you can return an URL with the avatar image           
            ->setAvatarUrl($fos_user->getAvatarPath())
            // use this method if you don't want to display the user image
//            ->displayUserAvatar(false)
            // you can also pass an email address to use gravatar's service
//            ->setGravatarEmail($fos_user->getEmail())
            // you can use any type of menu item, except submenus
            ->addMenuItems([
//                MenuItem::linkToRoute('My Profile', 'fa fa-id-card'),
//                MenuItem::linkToRoute('Settings', 'fa fa-user-cog']),
              MenuItem::section()
        ]);

    }

}
