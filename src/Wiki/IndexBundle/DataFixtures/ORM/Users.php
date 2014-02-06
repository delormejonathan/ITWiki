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
		$usernames = array('admin');
		$emails = array('admin');
 
		foreach ($noms as $i => $nom) {
			$users[$i] = new User;
 
			$users[$i]->setUsername($usernames[$i]);
			$users[$i]->setPlainPassword('itwiki');
			$users[$i]->setEmail($emails[$i]);
			$users[$i]->setEnabled(true);
 
			$users[$i]->setRoles(array('ROLE_SUPER_ADMIN'));
 			$this->addReference('users_' . $i, $users[$i]);
	
			$manager->persist($users[$i]);
		}
 
		$manager->flush();
	}


	public function getOrder()
    {
        return 0; // the order in which fixtures will be loaded
    }
}