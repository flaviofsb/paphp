<?php

namespace App\Security\Voter;

use App\Entity\Transacoes;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TransacoesVoter extends Voter
{

    public function __construct(private Security $security)
    {
       
    }
    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [Transacoes::EDIT, Transacoes::VIEW])
            && $subject instanceof \App\Entity\Transacoes;
    }
    /**
     *  @param Transacoes $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        /**
         *  @var User $user
         */
        $user = $token->getUser();

        $usuarioAutenticado = $user instanceof UserInterface;
        // if the user is anonymous, do not grant access
        //if (!$user instanceof UserInterface) {
        //    return false;
        //}

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case Transacoes::EDIT:

                return $usuarioAutenticado && ( $this->security->isGranted('ROLE_GERENTE') || $subject->getUser()->getId() === $user->getId());
                
            case Transacoes::VIEW:

                return $usuarioAutenticado && ( $this->security->isGranted('ROLE_GERENTE') || $subject->getUser()->getId() === $user->getId());
        }

        return false;
    }
}
