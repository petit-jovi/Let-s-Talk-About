<?php

namespace App\Enums;

/**
 * Discriminant de la classe abstraite "Utilisateur" (Class Diagram) :
 * un compte est soit un Membre, soit un Administrateur (Bureau Executif).
 * Il n'existe pas de compte "Visiteur" : le visiteur est, par definition,
 * non authentifie.
 */
enum UserType: string
{
    case Membre = 'membre';
    case Admin = 'admin';
}
