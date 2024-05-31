<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname', 'Prénom'),
            TextField::new('lastname', 'Nom'),
            TextField::new('email', 'Email'),
            ImageField::new('profile_picture', 'Image du user')
                ->setBasePath('/uploads/profile_pictures') // Le chemin pour accéder aux images
                ->setUploadDir('public/uploads/profile_pictures') // Le chemin où les images seront stockées
                ->setRequired(false) // Rendre le téléchargement d'image facultatif
                ->setLabel('Image'),
            BooleanField::new('IsVerified', 'Verifier'),
            BooleanField::new('isSub', 'Abonné')


        ];
    }

}
