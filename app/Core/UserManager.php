<?php
namespace App\Core;


use Nette;
use Nette\Security\SimpleIdentity;

class UserManager implements Nette\Security\Authenticator
{
	public function __construct(
		private Nette\Security\Passwords $passwords,
                private \App\Model\Facade\UserFacade $userFacade
	) {
	}

	public function authenticate(string $username, string $password): SimpleIdentity
	{
		
                $row = $this->userFacade->getOne(['email'=>$username]);

		if (!$row) {
			throw new Nette\Security\AuthenticationException('Uživatel nenalezen.');
		}

		if (!$this->passwords->verify($password, $row->password)) {
			throw new Nette\Security\AuthenticationException('Neplatné heslo.');
		}

		return new SimpleIdentity(
			$row->id,
			$row->role,
			['username' => $row->name],
		);
	}
}
