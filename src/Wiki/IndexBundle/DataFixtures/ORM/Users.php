<?php 
namespace Wiki\IndexBundle\DataFixtures\ORM;
 
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Wiki\UserBundle\Entity\User;
 
class Users extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		// Les noms d'utilisateur à créer
		$usernames = array('delorme.jonathan');
		$noms = array('DELORME');
		$prenoms = array('Jonathan');
		$emails = array('delorme.jonathan@gmail.com');
 
		foreach ($noms as $i => $nom) {
			// On crée l'utilisateur
			$users[$i] = new User;
 
			// Le nom d'utilisateur et le mot de passe sont identiques
			$users[$i]->setLastname($nom);
			$users[$i]->setFirstname($prenoms[$i]);
			$users[$i]->setUsername($usernames[$i]);
			$users[$i]->setPlainPassword('test');
			$users[$i]->setEmail($emails[$i]);
			$users[$i]->setEnabled(true);
 
			// Le sel et les rôles sont vides pour l'instant
			// $users[$i]->setSalt('');
			$users[$i]->setRoles(array('ROLE_SUPER_ADMIN'));
 			$this->addReference('users_' . $i, $users[$i]);
	
			// On le persiste
			$manager->persist($users[$i]);
		}
 
		// On déclenche l'enregistrement
		$manager->flush();
	}


	public function getOrder()
    {
        return 0; // the order in which fixtures will be loaded
    }
}