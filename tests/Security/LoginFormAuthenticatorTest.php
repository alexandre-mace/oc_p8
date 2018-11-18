<?php

namespace App\Tests\Security;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Tests\Security\ExposedTaskVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class LoginFormAuthenticatorTest extends TestCase
{
    public function testGetUserFirstException()
    {
        $manager = $this->createMock('Doctrine\ORM\EntityManagerInterface');
        $router = $this->createMock('Symfony\Component\Routing\RouterInterface');
        $csrfTokenManager = $this->createMock('Symfony\Component\Security\Csrf\CsrfTokenManagerInterface');
        $passwordEncoder = $this->createMock('Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface');
        $loginFormAuthenticator = new LoginFormAuthenticator($manager, $router, $csrfTokenManager, $passwordEncoder);
        $credentials = [
            'csrf_token' => 'abracadabra'
        ];
        $userProvider = $this->createMock('Symfony\Component\Security\Core\User\UserProviderInterface');
        try {
            $loginFormAuthenticator->getUser($credentials, $userProvider);    
        } catch (InvalidCsrfTokenException $exception) {
            $this->assertTrue($exception instanceof InvalidCsrfTokenException);
        }
    }

    public function testGetUserSecondException()
    {
        $manager = $this->createMock('Doctrine\ORM\EntityManagerInterface');
        $router = $this->createMock('Symfony\Component\Routing\RouterInterface');
        $csrfTokenManager = $this->createMock('Symfony\Component\Security\Csrf\CsrfTokenManagerInterface');
        $passwordEncoder = $this->createMock('Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface');
        $loginFormAuthenticator = new LoginFormAuthenticator($manager, $router, $csrfTokenManager, $passwordEncoder);
        $credentials = [
            'csrf_token' => 'valid token',
            'username' => 'abracadabra'
        ];
        $userProvider = $this->createMock('Symfony\Component\Security\Core\User\UserProviderInterface');
        $csrfTokenManager->expects($this->any())
            ->method('isTokenValid')
            ->willReturn(true);
        $repository = $this->createMock('App\Repository\UserRepository');
        $repository->expects($this->any())
            ->method('findOneBy')
            ->willReturn(null);
        $manager->expects($this->any())
            ->method('getRepository')
            ->willReturn($repository);
        try {
            $loginFormAuthenticator->getUser($credentials, $userProvider);    
        } catch (CustomUserMessageAuthenticationException $exception) {
            $this->assertTrue($exception instanceof CustomUserMessageAuthenticationException);
        }       
    }

    public function testOnAuthenticationSuccess(){
        
    }
}