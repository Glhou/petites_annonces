<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;



class CommentVoter extends Voter
{
    private $security;

    public function __construct(Security $security){
        $this->security = $security;
    }

    protected function supports($attribute, $comment)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['COMMENT_EDIT'])
            && $comment instanceof \App\Entity\Comment;
    }

    protected function voteOnAttribute($attribute, $comment, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'COMMENT_EDIT':
                return ($this->security->isGranted('ROLE_ADMIN')) || ($this->security->isGranted('ROLE_MODO')) || ($user->getId() == $comment->getAuthor()->getId());
        }

        return false;
    }
}
